<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Cart Controller
 * Xử lý giỏ hàng
 */
class CartController extends Controller {
    
    /**
     * Hiển thị giỏ hàng
     */
    public function index() {
        require_once __DIR__ . '/../models/Cart.php';
        $cartModel = new Cart();
        
        // Xử lý các actions
        $message = $this->handleActions($cartModel);
        
        // Lấy items trong giỏ hàng
        $cartItems = $cartModel->getItems();
        $total = $cartModel->getTotal();
        
        // Truyền dữ liệu vào view
        $data = [
            'page_title' => 'Giỏ hàng',
            'cartItems' => $cartItems,
            'total' => $total,
            'message' => $message
        ];
        
        $this->view('cart/index', $data);
    }
    
    /**
     * Xử lý các actions (update, remove, clear)
     * @param Cart $cartModel
     * @return string|null
     */
    private function handleActions($cartModel) {
        // Cập nhật giỏ hàng
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
            if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
                foreach ($_POST['quantity'] as $variantId => $quantity) {
                    $cartModel->update(intval($variantId), intval($quantity));
                }
                return 'Đã cập nhật giỏ hàng!';
            }
        }
        
        // Xóa sản phẩm
        if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
            $variantId = intval($_GET['id']);
            $cartModel->remove($variantId);
            $this->redirect('index.php?controller=cart');
            return null;
        }
        
        // Xóa toàn bộ giỏ hàng
        if (isset($_GET['action']) && $_GET['action'] == 'clear') {
            $cartModel->clear();
            $this->redirect('index.php?controller=cart');
            return null;
        }
        
        return null;
    }
    
    /**
     * Thêm sản phẩm vào giỏ (Ajax)
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            
            if ($variantId > 0 && $quantity > 0) {
                require_once __DIR__ . '/../models/Cart.php';
                $cartModel = new Cart();
                $cartModel->add($variantId, $quantity);
                
                // Trả về JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã thêm vào giỏ hàng',
                    'cart_count' => $cartModel->count()
                ]);
                exit();
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        exit();
    }
}
?>



