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
        
        // Lấy dữ liệu
        $products = $productModel->getAllProducts($categoryId);
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
            'categoryId' => $categoryId
        ];
        
        $this->view('home/index', $data);
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



