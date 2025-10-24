<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Admin Controller
 * Xử lý các chức năng quản trị
 */
class AdminController extends Controller {
    
    private $adminModel;
    
    public function __construct() {
        $this->adminModel = $this->model('Admin');
    }
    
    /**
     * Kiểm tra đăng nhập
     */
    private function checkAuth() {
        if (!$this->adminModel->isLoggedIn()) {
            $this->redirect('index.php?action=login');
            exit();
        }
    }
    
    /**
     * Trang đăng nhập
     */
    public function login() {
        // Nếu đã đăng nhập, chuyển về dashboard
        if ($this->adminModel->isLoggedIn()) {
            $this->redirect('index.php?action=dashboard');
            return;
        }
        
        $error = null;
        
        // Xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin!';
            } else {
                $admin = $this->adminModel->login($username, $password);
                
                if ($admin) {
                    // Lưu session
                    $_SESSION['admin_id'] = $admin['MaNV'];
                    $_SESSION['admin_name'] = $admin['HoTen'];
                    $_SESSION['admin_role'] = $admin['TenVaiTro'];
                    $_SESSION['admin_role_id'] = $admin['MaVaiTro'];
                    $_SESSION['admin_permission'] = $admin['QuyenHan'];
                    
                    $this->redirect('index.php?action=dashboard');
                    return;
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
                }
            }
        }
        
        $data = [
            'page_title' => 'Đăng nhập Admin',
            'error' => $error
        ];
        
        $this->view('admin/login', $data);
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        $this->adminModel->logout();
        $this->redirect('index.php?action=login');
    }
    
    /**
     * Dashboard - Trang chủ admin
     */
    public function dashboard() {
        $this->checkAuth();
        
        $stats = $this->adminModel->getDashboardStats();
        
        $data = [
            'page_title' => 'Dashboard',
            'stats' => $stats
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    /**
     * Quản lý sản phẩm - Danh sách
     */
    public function products() {
        $this->checkAuth();
        
        $productModel = $this->model('Product');
        
        // Xử lý xóa sản phẩm
        if (isset($_GET['delete'])) {
            $productId = intval($_GET['delete']);
            $sql = "DELETE FROM sanpham WHERE MaSP = {$productId}";
            $productModel->query($sql);
            $this->redirect('index.php?action=products');
            return;
        }
        
        // Lấy TẤT CẢ sản phẩm cho admin (bao gồm cả hết hàng và chưa có biến thể)
        $products = $productModel->getAllProductsForAdmin();
        
        $data = [
            'page_title' => 'Quản lý sản phẩm',
            'products' => $products
        ];
        
        $this->view('admin/products/list', $data);
    }
    
    /**
     * Thêm sản phẩm mới
     */
    public function addProduct() {
        $this->checkAuth();
        
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getAllCategories();
        
        // Lấy danh sách thương hiệu
        $productModel = $this->model('Product');
        $brands = $productModel->query("SELECT * FROM thuonghieu ORDER BY TenTH ASC")->fetch_all(MYSQLI_ASSOC);
        
        $message = null;
        
        // Xử lý thêm thương hiệu qua AJAX
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_brand'])) {
            header('Content-Type: application/json');
            
            $tenTH = isset($_POST['ten_th']) ? $productModel->escape(trim($_POST['ten_th'])) : '';
            
            if (empty($tenTH)) {
                echo json_encode(['success' => false, 'message' => 'Tên thương hiệu không được để trống']);
                exit();
            }
            
            // Kiểm tra trùng tên
            $checkSql = "SELECT MaTH FROM thuonghieu WHERE TenTH = '{$tenTH}'";
            $checkResult = $productModel->query($checkSql);
            
            if ($checkResult->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Thương hiệu đã tồn tại']);
                exit();
            }
            
            // Thêm thương hiệu mới
            $sql = "INSERT INTO thuonghieu (TenTH) VALUES ('{$tenTH}')";
            if ($productModel->query($sql)) {
                $brandId = $productModel->getLastInsertId();
                echo json_encode(['success' => true, 'brand_id' => $brandId, 'brand_name' => $_POST['ten_th']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm vào database']);
            }
            exit();
        }
        
        // Xử lý thêm sản phẩm
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['add_brand'])) {
            $tenSP = $_POST['ten_sp'] ?? '';
            $maDM = intval($_POST['ma_dm']);
            $maTH = intval($_POST['ma_th']);
            $xuatXu = $_POST['xuat_xu'] ?? '';
            $moTa = $_POST['mo_ta'] ?? '';
            
            if (!empty($tenSP)) {
                $tenSP = $productModel->escape($tenSP);
                $xuatXu = $productModel->escape($xuatXu);
                $moTa = $productModel->escape($moTa);
                
                // Xử lý upload hình ảnh
                $hinhAnh = '';
                if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
                    $file = $_FILES['hinh_anh'];
                    $fileName = time() . '_' . basename($file['name']);
                    $targetPath = __DIR__ . '/../../public/uploads/products/' . $fileName;
                    
                    // Kiểm tra định dạng file
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (in_array($file['type'], $allowedTypes)) {
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $hinhAnh = $fileName;
                        }
                    }
                }
                
                $sql = "INSERT INTO sanpham (TenSP, MaDM, MaTH, XuatXu, MoTa, HinhAnh) 
                        VALUES ('{$tenSP}', {$maDM}, {$maTH}, '{$xuatXu}', '{$moTa}', '{$hinhAnh}')";
                
                if ($productModel->query($sql)) {
                    $this->redirect('index.php?action=products');
                    return;
                } else {
                    $message = 'Có lỗi xảy ra khi thêm sản phẩm!';
                }
            } else {
                $message = 'Vui lòng nhập tên sản phẩm!';
            }
        }
        
        $data = [
            'page_title' => 'Thêm sản phẩm mới',
            'categories' => $categories,
            'brands' => $brands,
            'message' => $message
        ];
        
        $this->view('admin/products/add', $data);
    }
    
    /**
     * Sửa sản phẩm
     */
    public function editProduct() {
        $this->checkAuth();
        
        // Lấy ID sản phẩm
        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($productId == 0) {
            $this->redirect('index.php?action=products');
            return;
        }
        
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        
        // Lấy thông tin sản phẩm
        $product = $productModel->getProductDetail($productId);
        
        if (!$product) {
            $this->redirect('index.php?action=products');
            return;
        }
        
        // Lấy danh sách danh mục và thương hiệu
        $categories = $categoryModel->getAllCategories();
        $brands = $productModel->query("SELECT * FROM thuonghieu")->fetch_all(MYSQLI_ASSOC);
        
        // Lấy các biến thể của sản phẩm
        $variants = $productModel->getProductVariants($productId);
        
        $message = null;
        
        // Xử lý thêm biến thể mới
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_variant'])) {
            $kichThuoc = $productModel->escape($_POST['kich_thuoc']);
            $mauSac = $productModel->escape($_POST['mau_sac']);
            $giaNhap = intval($_POST['gia_nhap']);
            $giaBan = intval($_POST['gia_ban']);
            $phanTramGiam = isset($_POST['phan_tram_giam']) ? intval($_POST['phan_tram_giam']) : 0;
            $tonKho = intval($_POST['ton_kho']);
            
            // Tính giá gốc từ % giảm giá
            // Công thức: GiaGoc = GiaBan / (1 - %GiamGia/100)
            $giaGoc = 0;
            if ($phanTramGiam > 0 && $phanTramGiam < 100) {
                $giaGoc = round($giaBan / (1 - $phanTramGiam / 100));
            }
            
            $sql = "INSERT INTO sanpham_bienthe (MaSP, KichThuoc, MauSac, GiaNhap, GiaBan, GiaGoc, TonKho)
                    VALUES ({$productId}, '{$kichThuoc}', '{$mauSac}', {$giaNhap}, {$giaBan}, {$giaGoc}, {$tonKho})";
            
            if ($productModel->query($sql)) {
                $this->redirect("index.php?action=editProduct&id={$productId}");
                return;
            } else {
                $message = 'Có lỗi khi thêm biến thể!';
            }
        }
        
        // Xử lý cập nhật biến thể
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_variant'])) {
            $variantId = intval($_POST['id']);
            $kichThuoc = $productModel->escape($_POST['kich_thuoc']);
            $mauSac = $productModel->escape($_POST['mau_sac']);
            $giaNhap = intval($_POST['gia_nhap']);
            $giaBan = intval($_POST['gia_ban']);
            $phanTramGiam = isset($_POST['phan_tram_giam']) ? intval($_POST['phan_tram_giam']) : 0;
            $tonKho = intval($_POST['ton_kho']);
            
            // Tính giá gốc từ % giảm giá
            // Công thức: GiaGoc = GiaBan / (1 - %GiamGia/100)
            $giaGoc = 0;
            if ($phanTramGiam > 0 && $phanTramGiam < 100) {
                $giaGoc = round($giaBan / (1 - $phanTramGiam / 100));
            }
            
            $sql = "UPDATE sanpham_bienthe 
                    SET KichThuoc = '{$kichThuoc}',
                        MauSac = '{$mauSac}',
                        GiaNhap = {$giaNhap},
                        GiaBan = {$giaBan},
                        GiaGoc = {$giaGoc},
                        TonKho = {$tonKho}
                    WHERE MaSP_BienThe = {$variantId}";
            
            if ($productModel->query($sql)) {
                $this->redirect("index.php?action=editProduct&id={$productId}");
                return;
            } else {
                $message = 'Có lỗi khi cập nhật biến thể!';
            }
        }
        
        // Xử lý cập nhật sản phẩm
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['add_variant']) && !isset($_POST['update_variant'])) {
            $tenSP = $_POST['ten_sp'] ?? '';
            $maDM = intval($_POST['ma_dm']);
            $maTH = intval($_POST['ma_th']);
            $xuatXu = $_POST['xuat_xu'] ?? '';
            $moTa = $_POST['mo_ta'] ?? '';
            
            if (!empty($tenSP)) {
                $tenSP = $productModel->escape($tenSP);
                $xuatXu = $productModel->escape($xuatXu);
                $moTa = $productModel->escape($moTa);
                
                // Xử lý upload hình ảnh mới
                $updateImage = '';
                if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
                    $file = $_FILES['hinh_anh'];
                    $fileName = time() . '_' . basename($file['name']);
                    $targetPath = __DIR__ . '/../../public/uploads/products/' . $fileName;
                    
                    // Kiểm tra định dạng file
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (in_array($file['type'], $allowedTypes)) {
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            // Xóa ảnh cũ nếu có
                            if (!empty($product['HinhAnh'])) {
                                $oldImagePath = __DIR__ . '/../../public/uploads/products/' . $product['HinhAnh'];
                                if (file_exists($oldImagePath)) {
                                    unlink($oldImagePath);
                                }
                            }
                            $updateImage = ", HinhAnh = '{$fileName}'";
                        }
                    }
                }
                
                $sql = "UPDATE sanpham 
                        SET TenSP = '{$tenSP}', 
                            MaDM = {$maDM}, 
                            MaTH = {$maTH}, 
                            XuatXu = '{$xuatXu}', 
                            MoTa = '{$moTa}'
                            {$updateImage}
                        WHERE MaSP = {$productId}";
                
                if ($productModel->query($sql)) {
                    $this->redirect('index.php?action=products');
                    return;
                } else {
                    $message = 'Có lỗi xảy ra khi cập nhật sản phẩm!';
                }
            } else {
                $message = 'Vui lòng nhập tên sản phẩm!';
            }
        }
        
        $data = [
            'page_title' => 'Sửa sản phẩm',
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'variants' => $variants,
            'message' => $message
        ];
        
        $this->view('admin/products/edit', $data);
    }
    
    /**
     * Xóa biến thể sản phẩm
     */
    public function deleteVariant() {
        $this->checkAuth();
        
        $variantId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        
        if ($variantId > 0) {
            $productModel = $this->model('Product');
            $sql = "DELETE FROM sanpham_bienthe WHERE MaSP_BienThe = {$variantId}";
            $productModel->query($sql);
        }
        
        if ($productId > 0) {
            $this->redirect("index.php?action=editProduct&id={$productId}");
        } else {
            $this->redirect("index.php?action=products");
        }
    }
    
    /**
     * Quản lý danh mục
     */
    public function categories() {
        $this->checkAuth();
        
        $categoryModel = $this->model('Category');
        
        // Xử lý thêm danh mục
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
            $tenDM = $categoryModel->escape($_POST['ten_dm']);
            $maDMCha = isset($_POST['ma_dm_cha']) && $_POST['ma_dm_cha'] != '' ? intval($_POST['ma_dm_cha']) : null;
            
            if ($maDMCha !== null) {
                $sql = "INSERT INTO danhmuc (TenDM, MaDM_Cha) VALUES ('{$tenDM}', {$maDMCha})";
            } else {
                $sql = "INSERT INTO danhmuc (TenDM, MaDM_Cha) VALUES ('{$tenDM}', NULL)";
            }
            
            $categoryModel->query($sql);
            $this->redirect('index.php?action=categories');
            return;
        }
        
        // Xử lý cập nhật danh mục
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
            $maDM = intval($_POST['ma_dm']);
            $tenDM = $categoryModel->escape($_POST['ten_dm']);
            $maDMCha = isset($_POST['ma_dm_cha']) && $_POST['ma_dm_cha'] != '' ? intval($_POST['ma_dm_cha']) : null;
            
            if ($maDMCha !== null) {
                $sql = "UPDATE danhmuc SET TenDM = '{$tenDM}', MaDM_Cha = {$maDMCha} WHERE MaDM = {$maDM}";
            } else {
                $sql = "UPDATE danhmuc SET TenDM = '{$tenDM}', MaDM_Cha = NULL WHERE MaDM = {$maDM}";
            }
            
            $categoryModel->query($sql);
            $this->redirect('index.php?action=categories');
            return;
        }
        
        // Xử lý xóa danh mục
        if (isset($_GET['delete'])) {
            $maDM = intval($_GET['delete']);
            $sql = "DELETE FROM danhmuc WHERE MaDM = {$maDM}";
            $categoryModel->query($sql);
            $this->redirect('index.php?action=categories');
            return;
        }
        
        $categories = $categoryModel->getAllCategories();
        $parentCategories = $categoryModel->getParentCategories();
        
        $data = [
            'page_title' => 'Quản lý danh mục',
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ];
        
        $this->view('admin/categories', $data);
    }
    
    /**
     * Quản lý đơn hàng
     */
    public function orders() {
        $this->checkAuth();
        
        $productModel = $this->model('Product');
        
        // TenKH, SoDienThoai đã được lưu trực tiếp trong bảng hoadon
        $sql = "SELECT hd.*, nv.HoTen as TenNV
                FROM hoadon hd
                LEFT JOIN nhanvien nv ON hd.MaNV = nv.MaNV
                ORDER BY hd.NgayLap DESC";
        
        $orders = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        $data = [
            'page_title' => 'Quản lý đơn hàng',
            'orders' => $orders
        ];
        
        $this->view('admin/orders', $data);
    }
    
    /**
     * Sửa đơn hàng
     */
    public function editOrder() {
        $this->checkAuth();
        
        // Lấy ID đơn hàng
        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($orderId == 0) {
            $this->redirect('index.php?action=orders');
            return;
        }
        
        $productModel = $this->model('Product');
        
        // Lấy thông tin đơn hàng
        // Sau khi thêm cột TenKH, SoDienThoai, DiaChi vào bảng hoadon, 
        // dữ liệu này đã được lưu trực tiếp trong bảng
        $sql = "SELECT hd.*, nv.HoTen as TenNV
                FROM hoadon hd
                LEFT JOIN nhanvien nv ON hd.MaNV = nv.MaNV
                WHERE hd.MaHD = {$orderId}";
        
        $result = $productModel->query($sql);
        $order = $result->fetch_assoc();
        
        if (!$order) {
            $this->redirect('index.php?action=orders');
            return;
        }
        
        // Lấy chi tiết đơn hàng (cthd.* đã bao gồm DonGia, SoLuong, ThanhTien)
        $sql = "SELECT cthd.*, sp.TenSP, bt.KichThuoc, bt.MauSac
                FROM chitiethoadon cthd
                LEFT JOIN sanpham_bienthe bt ON cthd.MaSP_BienThe = bt.MaSP_BienThe
                LEFT JOIN sanpham sp ON bt.MaSP = sp.MaSP
                WHERE cthd.MaHD = {$orderId}";
        
        $orderDetails = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        $message = null;
        
        // Xử lý cập nhật đơn hàng
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Cập nhật thông tin khách hàng
            $tenKH = $productModel->escape($_POST['ten_kh']);
            $soDienThoai = $productModel->escape($_POST['so_dien_thoai']);
            $diaChi = $productModel->escape($_POST['dia_chi']);
            
            // 2. Cập nhật trạng thái và thanh toán
            $hinhThucThanhToan = $productModel->escape($_POST['hinh_thuc_thanh_toan']);
            $ghiChu = $productModel->escape($_POST['ghi_chu']);
            $trangThai = $productModel->escape($_POST['trang_thai'] ?? 'Đang xử lý');
            
            // 3. Xử lý xóa sản phẩm
            $removedItems = isset($_POST['removed_items']) && $_POST['removed_items'] != '' 
                ? explode(',', $_POST['removed_items']) 
                : [];
            
            if (!empty($removedItems)) {
                foreach ($removedItems as $itemId) {
                    $itemId = intval($itemId);
                    $sql = "DELETE FROM chitiethoadon WHERE MaCTHD = {$itemId}";
                    $productModel->query($sql);
                }
            }
            
            // 4. Cập nhật số lượng sản phẩm
            if (isset($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $detailId => $quantity) {
                    $detailId = intval($detailId);
                    $quantity = intval($quantity);
                    
                    if ($quantity > 0 && !in_array($detailId, $removedItems)) {
                        // Lấy giá của sản phẩm
                        $sql = "SELECT DonGia FROM chitiethoadon WHERE MaCTHD = {$detailId}";
                        $result = $productModel->query($sql)->fetch_assoc();
                        $donGia = $result['DonGia'];
                        $thanhTien = $donGia * $quantity;
                        
                        // Cập nhật số lượng và thành tiền
                        $sql = "UPDATE chitiethoadon 
                                SET SoLuong = {$quantity}, 
                                    ThanhTien = {$thanhTien}
                                WHERE MaCTHD = {$detailId}";
                        $productModel->query($sql);
                    }
                }
            }
            
            // 5. Tính lại tổng tiền đơn hàng
            $sql = "SELECT SUM(ThanhTien) as tongTien FROM chitiethoadon WHERE MaHD = {$orderId}";
            $result = $productModel->query($sql)->fetch_assoc();
            $tongTien = $result['tongTien'] ?? 0;
            
            // 6. Cập nhật thông tin đơn hàng
            $sql = "UPDATE hoadon 
                    SET TenKH = '{$tenKH}',
                        SoDienThoai = '{$soDienThoai}',
                        DiaChi = '{$diaChi}',
                        HinhThucThanhToan = '{$hinhThucThanhToan}',
                        GhiChu = '{$ghiChu}',
                        TrangThai = '{$trangThai}',
                        TongTien = {$tongTien}
                    WHERE MaHD = {$orderId}";
            
            if ($productModel->query($sql)) {
                // 7. Cập nhật thông tin khách hàng (nếu được chọn)
                if (isset($_POST['update_customer_info']) && $_POST['update_customer_info'] == '1') {
                    // Lấy MaKH từ đơn hàng
                    $maKH = $order['MaKH'];
                    
                    if ($maKH) {
                        // Cập nhật thông tin vào bảng khachhang
                        $sqlUpdateCustomer = "UPDATE khachhang 
                                            SET HoTen = '{$tenKH}',
                                                SoDienThoai = '{$soDienThoai}',
                                                DiaChi = '{$diaChi}'
                                            WHERE MaKH = {$maKH}";
                        $productModel->query($sqlUpdateCustomer);
                    }
                }
                
                $this->redirect('index.php?action=orders');
                return;
            } else {
                $message = 'Có lỗi xảy ra khi cập nhật đơn hàng!';
            }
        }
        
        $data = [
            'page_title' => 'Sửa đơn hàng #' . $orderId,
            'order' => $order,
            'orderDetails' => $orderDetails,
            'message' => $message
        ];
        
        $this->view('admin/orders/edit', $data);
    }
    
    /**
     * Quản lý khách hàng
     */
    public function customers() {
        $this->checkAuth();
        
        $productModel = $this->model('Product');
        
        $sql = "SELECT * FROM khachhang ORDER BY DiemTichLuy DESC";
        $customers = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        $data = [
            'page_title' => 'Quản lý khách hàng',
            'customers' => $customers
        ];
        
        $this->view('admin/customers', $data);
    }
    
    /**
     * Quản lý người dùng và phân quyền
     */
    public function users() {
        $this->checkAuth();
        
        // Chỉ Admin (quyền ToanQuyen) mới có quyền truy cập
        if (!$this->adminModel->isAdmin()) {
            $this->redirect('index.php?action=dashboard');
            return;
        }
        
        $productModel = $this->model('Product');
        $vaiTroModel = $this->model('VaiTro');
        $message = null;
        
        // Xử lý cập nhật vai trò
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $userId = intval($_POST['user_id']);
            
            // Không cho phép thay đổi thông tin của chính mình
            if ($userId == $_SESSION['admin_id']) {
                $message = ['type' => 'error', 'text' => 'Không thể thay đổi thông tin của chính bạn!'];
            } else {
                switch ($_POST['action']) {
                    case 'update_role':
                        $newRoleId = intval($_POST['new_role_id']);
                        $sql = "UPDATE nhanvien SET MaVaiTro = {$newRoleId} WHERE MaNV = {$userId}";
                        if ($productModel->query($sql)) {
                            $message = ['type' => 'success', 'text' => 'Đã cập nhật vai trò thành công!'];
                        }
                        break;
                        
                    case 'toggle_status':
                        $newStatus = $productModel->escape($_POST['new_status']); // 'active' or 'inactive'
                        $sql = "UPDATE nhanvien SET TrangThai = '{$newStatus}' WHERE MaNV = {$userId}";
                        if ($productModel->query($sql)) {
                            $action = $newStatus == 'active' ? 'mở khóa' : 'khóa';
                            $message = ['type' => 'success', 'text' => "Đã {$action} người dùng thành công!"];
                        }
                        break;
                        
                    case 'reset_password':
                        // Lưu mật khẩu plain text (không hash)
                        $newPassword = $productModel->escape($_POST['new_password']);
                        $sql = "UPDATE nhanvien SET MatKhau = '{$newPassword}' WHERE MaNV = {$userId}";
                        if ($productModel->query($sql)) {
                            $message = ['type' => 'success', 'text' => 'Đã reset mật khẩu thành công!'];
                        }
                        break;
                }
            }
        }
        
        // Lấy danh sách người dùng với thông tin vai trò
        $sql = "SELECT nv.MaNV, nv.HoTen, nv.TenDangNhap, nv.Email, nv.SoDienThoai, 
                       nv.MaVaiTro, vt.TenVaiTro, vt.QuyenHan, nv.TrangThai 
                FROM nhanvien nv
                LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                ORDER BY 
                    CASE vt.QuyenHan
                        WHEN 'ToanQuyen' THEN 1
                        WHEN 'NhapXuat' THEN 2
                        WHEN 'XemBanHang' THEN 3
                        ELSE 4
                    END,
                    nv.HoTen";
        
        $users = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        // Lấy danh sách vai trò
        $roles = $vaiTroModel->getAllRoles();
        
        $data = [
            'page_title' => 'Quản lý người dùng',
            'users' => $users,
            'roles' => $roles,
            'message' => $message
        ];
        
        $this->view('admin/users', $data);
    }
    
    /**
     * Chỉnh sửa người dùng
     */
    public function editUser() {
        $this->checkAuth();
        
        // Chỉ Admin mới có quyền
        if (!$this->adminModel->isAdmin()) {
            $this->redirect('index.php?action=dashboard');
            return;
        }
        
        $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($userId == 0) {
            $this->redirect('index.php?action=users');
            return;
        }
        
        $productModel = $this->model('Product');
        $vaiTroModel = $this->model('VaiTro');
        
        // Lấy thông tin người dùng
        $sql = "SELECT nv.*, vt.TenVaiTro, vt.QuyenHan
                FROM nhanvien nv
                LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                WHERE nv.MaNV = {$userId}";
        $result = $productModel->query($sql);
        $user = $result->fetch_assoc();
        
        if (!$user) {
            $this->redirect('index.php?action=users');
            return;
        }
        
        // Lấy danh sách vai trò
        $roles = $vaiTroModel->getAllRoles();
        
        $message = null;
        
        // Xử lý cập nhật
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $hoTen = $productModel->escape($_POST['ho_ten']);
            $email = $productModel->escape($_POST['email'] ?? '');
            $soDienThoai = $productModel->escape($_POST['so_dien_thoai'] ?? '');
            $tenDangNhap = $productModel->escape($_POST['ten_dang_nhap']);
            
            // Kiểm tra tên đăng nhập đã tồn tại chưa (ngoại trừ user hiện tại)
            $sqlCheck = "SELECT MaNV FROM nhanvien 
                        WHERE TenDangNhap = '{$tenDangNhap}' 
                        AND MaNV != {$userId}";
            $resultCheck = $productModel->query($sqlCheck);
            
            if ($resultCheck->num_rows > 0) {
                $message = ['type' => 'error', 'text' => 'Tên đăng nhập đã tồn tại!'];
            } else {
                // Cập nhật thông tin cơ bản
                $sql = "UPDATE nhanvien 
                        SET HoTen = '{$hoTen}',
                            Email = '{$email}',
                            SoDienThoai = '{$soDienThoai}',
                            TenDangNhap = '{$tenDangNhap}'";
                
                // Chỉ update vai trò và trạng thái nếu không phải chính mình
                if ($userId != $_SESSION['admin_id']) {
                    $maVaiTro = intval($_POST['ma_vai_tro']);
                    $trangThai = $productModel->escape($_POST['trang_thai']); // 'active' or 'inactive'
                    $sql .= ", MaVaiTro = {$maVaiTro}, TrangThai = '{$trangThai}'";
                }
                
                $sql .= " WHERE MaNV = {$userId}";
                
                if ($productModel->query($sql)) {
                    // Xử lý đổi mật khẩu (nếu có)
                    if (isset($_POST['change_password']) && !empty($_POST['new_password'])) {
                        $newPassword = $_POST['new_password'];
                        $confirmPassword = $_POST['confirm_password'];
                        
                        if ($newPassword === $confirmPassword && strlen($newPassword) >= 6) {
                            // Lưu mật khẩu plain text (không hash)
                            $escapedPassword = $productModel->escape($newPassword);
                            $sqlPassword = "UPDATE nhanvien 
                                          SET MatKhau = '{$escapedPassword}' 
                                          WHERE MaNV = {$userId}";
                            $productModel->query($sqlPassword);
                        }
                    }
                    
                    $message = ['type' => 'success', 'text' => 'Đã cập nhật thông tin thành công!'];
                    
                    // Reload lại thông tin user
                    $sql = "SELECT nv.*, vt.TenVaiTro, vt.QuyenHan
                            FROM nhanvien nv
                            LEFT JOIN vaitro vt ON nv.MaVaiTro = vt.MaVaiTro
                            WHERE nv.MaNV = {$userId}";
                    $result = $productModel->query($sql);
                    $user = $result->fetch_assoc();
                } else {
                    $message = ['type' => 'error', 'text' => 'Có lỗi khi cập nhật thông tin!'];
                }
            }
        }
        
        $data = [
            'page_title' => 'Chỉnh sửa người dùng',
            'user' => $user,
            'roles' => $roles,
            'message' => $message
        ];
        
        $this->view('admin/users/edit', $data);
    }
    
    /**
     * Báo cáo doanh số
     */
    public function reports() {
        $this->checkAuth();
        
        $productModel = $this->model('Product');
        
        // Doanh thu theo tháng
        $sql = "SELECT MONTH(NgayLap) as thang, YEAR(NgayLap) as nam, 
                SUM(TongTien) as doanh_thu, COUNT(*) as so_don
                FROM hoadon
                WHERE YEAR(NgayLap) = YEAR(CURDATE())
                GROUP BY MONTH(NgayLap), YEAR(NgayLap)
                ORDER BY thang";
        
        $monthlyRevenue = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        // Sản phẩm bán chạy
        $sql = "SELECT sp.TenSP, SUM(cthd.SoLuong) as tong_ban, SUM(cthd.ThanhTien) as doanh_thu
                FROM chitiethoadon cthd
                JOIN sanpham_bienthe bt ON cthd.MaSP_BienThe = bt.MaSP_BienThe
                JOIN sanpham sp ON bt.MaSP = sp.MaSP
                GROUP BY sp.MaSP
                ORDER BY tong_ban DESC
                LIMIT 10";
        
        $topProducts = $productModel->query($sql)->fetch_all(MYSQLI_ASSOC);
        
        $data = [
            'page_title' => 'Báo cáo doanh số',
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts
        ];
        
        $this->view('admin/reports', $data);
    }
}
?>

