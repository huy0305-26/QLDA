<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Order Controller
 * Xử lý đặt hàng
 */
class OrderController extends Controller {
    
    /**
     * Hiển thị trang thanh toán
     */
    public function checkout() {
        require_once __DIR__ . '/../models/Cart.php';
        $cartModel = new Cart();
        
        // Lấy items trong giỏ hàng
        $cartItems = $cartModel->getItems();
        
        // Kiểm tra giỏ hàng có sản phẩm không
        if (empty($cartItems)) {
            $_SESSION['cart_message'] = 'Giỏ hàng của bạn đang trống!';
            $this->redirect('index.php?controller=cart');
            return;
        }
        
        $total = $cartModel->getTotal();
        
        // Truyền dữ liệu vào view
        $data = [
            'page_title' => 'Thanh toán',
            'cartItems' => $cartItems,
            'total' => $total
        ];
        
        $this->view('order/checkout', $data);
    }
    
    /**
     * Xử lý đặt hàng
     */
    public function placeOrder() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('index.php?controller=order&action=checkout');
            return;
        }
        
        require_once __DIR__ . '/../models/Cart.php';
        require_once __DIR__ . '/../models/Order.php';
        
        $cartModel = new Cart();
        $orderModel = new Order();
        
        // Lấy items trong giỏ hàng
        $cartItems = $cartModel->getItems();
        
        if (empty($cartItems)) {
            $_SESSION['cart_message'] = 'Giỏ hàng của bạn đang trống!';
            $this->redirect('index.php?controller=cart');
            return;
        }
        
        // Validate dữ liệu
        $errors = [];
        
        if (empty($_POST['ten_kh'])) {
            $errors[] = 'Vui lòng nhập họ tên';
        }
        
        if (empty($_POST['so_dien_thoai'])) {
            $errors[] = 'Vui lòng nhập số điện thoại';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $_POST['so_dien_thoai'])) {
            $errors[] = 'Số điện thoại không hợp lệ';
        }
        
        if (empty($_POST['dia_chi'])) {
            $errors[] = 'Vui lòng nhập địa chỉ giao hàng';
        }
        
        if (empty($_POST['hinh_thuc_thanh_toan'])) {
            $errors[] = 'Vui lòng chọn hình thức thanh toán';
        }
        
        // Nếu có lỗi, quay lại trang checkout
        if (!empty($errors)) {
            $_SESSION['checkout_errors'] = $errors;
            $_SESSION['checkout_data'] = $_POST;
            $this->redirect('index.php?controller=order&action=checkout');
            return;
        }
        
        // Tạo đơn hàng
        $orderData = [
            'ten_kh' => $_POST['ten_kh'],
            'so_dien_thoai' => $_POST['so_dien_thoai'],
            'dia_chi' => $_POST['dia_chi'],
            'ghi_chu' => $_POST['ghi_chu'] ?? '',
            'hinh_thuc_thanh_toan' => $_POST['hinh_thuc_thanh_toan'],
            'tong_tien' => $cartModel->getTotal()
        ];
        
        $orderId = $orderModel->createOrder($orderData);
        
        if ($orderId) {
            // Tạo chi tiết đơn hàng
            if ($orderModel->createOrderDetails($orderId, $cartItems)) {
                // Xử lý tích điểm cho khách hàng
                require_once __DIR__ . '/../models/Customer.php';
                $customerModel = new Customer();
                
                $soDienThoai = $_POST['so_dien_thoai'];
                $customer = $customerModel->findByPhone($soDienThoai);
                
                $customerId = null;
                $isNewCustomer = false;
                $pointsEarned = 0;
                
                if ($customer) {
                    // Khách hàng cũ - cập nhật thông tin
                    $customerId = $customer['MaKH'];
                    $customerModel->update($customerId, [
                        'ho_ten' => $_POST['ten_kh'],
                        'dia_chi' => $_POST['dia_chi']
                    ]);
                } else {
                    // Khách hàng mới - tạo tài khoản
                    $customerId = $customerModel->create([
                        'ho_ten' => $_POST['ten_kh'],
                        'so_dien_thoai' => $soDienThoai,
                        'dia_chi' => $_POST['dia_chi']
                    ]);
                    $isNewCustomer = true;
                }
                
                if ($customerId) {
                    // Cập nhật MaKH vào đơn hàng
                    $orderModel->updateCustomerId($orderId, $customerId);
                    
                    // Thêm điểm tích lũy (1000đ = 1 điểm)
                    $pointsEarned = $customerModel->addPoints($customerId, $orderData['tong_tien']);
                    
                    // Lưu thông tin tích điểm vào session để hiển thị
                    $_SESSION['loyalty_info'] = [
                        'is_new_customer' => $isNewCustomer,
                        'points_earned' => $pointsEarned,
                        'customer_id' => $customerId
                    ];
                }
                
                // Xóa giỏ hàng
                $cartModel->clear();
                
                // Redirect đến trang xác nhận
                $this->redirect('index.php?controller=order&action=success&id=' . $orderId);
            } else {
                $_SESSION['checkout_errors'] = ['Có lỗi xảy ra khi tạo chi tiết đơn hàng'];
                $this->redirect('index.php?controller=order&action=checkout');
            }
        } else {
            $_SESSION['checkout_errors'] = ['Có lỗi xảy ra khi tạo đơn hàng'];
            $this->redirect('index.php?controller=order&action=checkout');
        }
    }
    
    /**
     * Trang xác nhận đơn hàng thành công
     */
    public function success() {
        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($orderId <= 0) {
            $this->redirect('index.php');
            return;
        }
        
        require_once __DIR__ . '/../models/Order.php';
        require_once __DIR__ . '/../models/Customer.php';
        
        $orderModel = new Order();
        $customerModel = new Customer();
        
        $order = $orderModel->getOrderById($orderId);
        $orderDetails = $orderModel->getOrderDetails($orderId);
        
        if (!$order) {
            $this->redirect('index.php');
            return;
        }
        
        // Lấy thông tin tích điểm từ session
        $loyaltyInfo = null;
        $customerInfo = null;
        
        if (isset($_SESSION['loyalty_info'])) {
            $loyaltyInfo = $_SESSION['loyalty_info'];
            
            // Lấy thông tin khách hàng hiện tại
            if (isset($loyaltyInfo['customer_id'])) {
                $customerInfo = $customerModel->getById($loyaltyInfo['customer_id']);
            }
            
            // Xóa session sau khi lấy
            unset($_SESSION['loyalty_info']);
        }
        
        $data = [
            'page_title' => 'Đặt hàng thành công',
            'order' => $order,
            'orderDetails' => $orderDetails,
            'loyaltyInfo' => $loyaltyInfo,
            'customerInfo' => $customerInfo
        ];
        
        $this->view('order/success', $data);
    }
}
?>

