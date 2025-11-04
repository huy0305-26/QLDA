<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Home Controller
 * Xử lý trang chủ
 */
class HomeController extends Controller {
    
    /**
     * Hiển thị trang chủ
     */
    public function index() {
        // Load models
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        
        // Lấy danh mục filter nếu có
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
        
        // Nếu đang ở trang chủ (không có category): chỉ lấy 4 sản phẩm
        // Nếu đang lọc theo danh mục: lấy tất cả sản phẩm
        $limit = ($categoryId > 0) ? 0 : 4;
        $products = $productModel->getAllProducts($categoryId, $limit, 0);
        $totalProducts = $productModel->countProducts($categoryId);
        
        $categories = $categoryModel->getAllCategories();
        $categoriesTree = $categoryModel->getCategoriesTree();
        
        // Lấy tên danh mục nếu đang filter
        $currentCategory = null;
        if ($categoryId > 0) {
            $currentCategory = $categoryModel->getCategoryById($categoryId);
        }
        
        // Truyền dữ liệu vào view
        $data = [
            'page_title' => $categoryId > 0 && $currentCategory ? $currentCategory['TenDM'] : 'Trang chủ',
            'products' => $products,
            'categories' => $categories,
            'categoriesTree' => $categoriesTree,
            'currentCategory' => $currentCategory,
            'categoryId' => $categoryId,
            'totalProducts' => $totalProducts,
            'currentCount' => count($products),
            'showLoadMore' => ($categoryId == 0) // Chỉ hiển thị nút "Xem thêm" ở trang chủ
        ];
        
        $this->view('home/index', $data);
    }
    
    /**
     * Load thêm sản phẩm (AJAX)
     */
    public function loadMore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $productModel = $this->model('Product');
        
        $categoryId = isset($_POST['category']) ? intval($_POST['category']) : 0;
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
        $limit = 4;
        
        $products = $productModel->getAllProducts($categoryId, $limit, $offset);
        $totalProducts = $productModel->countProducts($categoryId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'products' => $products,
            'hasMore' => ($offset + count($products)) < $totalProducts
        ]);
    }
    
    /**
     * Tìm kiếm sản phẩm
     */
    public function search() {
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            $this->redirect('index.php');
            return;
        }
        
        $products = $productModel->search($keyword);
        $categories = $categoryModel->getAllCategories();
        
        $data = [
            'page_title' => 'Kết quả tìm kiếm: ' . $keyword,
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword
        ];
        
        $this->view('home/search', $data);
    }
}
?>



