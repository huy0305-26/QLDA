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
        
        // Nếu có category ID, chuyển hướng sang trang tìm kiếm
        if ($categoryId > 0) {
            $category = $categoryModel->getCategoryById($categoryId);
            if ($category) {
                $this->redirect('index.php?controller=home&action=search&q=' . urlencode($category['TenDM']));
                return;
            }
        }
        
        // Nếu đang ở trang chủ (không có category): chỉ lấy 4 sản phẩm
        $limit = 4;
        $products = $productModel->getAllProducts(0, $limit, 0);
        $totalProducts = $productModel->countProducts(0);
        
        $categories = $categoryModel->getAllCategories();
        $categoriesTree = $categoryModel->getCategoriesTree();
        
        // Truyền dữ liệu vào view
        $data = [
            'page_title' => 'Trang chủ',
            'products' => $products,
            'categories' => $categories,
            'categoriesTree' => $categoriesTree,
            'currentCategory' => null,
            'categoryId' => 0,
            'totalProducts' => $totalProducts,
            'currentCount' => count($products),
            'showLoadMore' => true
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
        $brandModel = $this->model('Brand');
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $brandId = isset($_GET['brand']) ? intval($_GET['brand']) : 0;
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
        $minPrice = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
        $maxPrice = isset($_GET['max_price']) ? intval($_GET['max_price']) : 0;
        $origin = isset($_GET['origin']) ? trim($_GET['origin']) : '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 9; // 9 sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;
        
        if (empty($keyword)) {
            $this->redirect('index.php');
            return;
        }
        
        // Tìm kiếm sản phẩm với các bộ lọc và phân trang
        $products = $productModel->search($keyword, $brandId, $categoryId, $minPrice, $maxPrice, $origin, $limit, $offset);
        $totalRecords = $productModel->countSearch($keyword, $brandId, $categoryId, $minPrice, $maxPrice, $origin);
        $totalPages = ceil($totalRecords / $limit);
        
        // Lấy danh mục và thương hiệu dựa trên từ khóa tìm kiếm (Context-Aware)
        $categories = $productModel->getCategoriesByKeyword($keyword);
        $brands = $productModel->getBrandsByKeyword($keyword);
        $origins = $productModel->getOriginsByKeyword($keyword);
        
        $data = [
            'page_title' => 'Kết quả tìm kiếm: ' . $keyword,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'origins' => $origins,
            'keyword' => $keyword,
            'brandId' => $brandId,
            'categoryId' => $categoryId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'origin' => $origin,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ];
        
        $this->view('home/search', $data);
    }
}
?>



