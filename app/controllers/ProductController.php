<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Product Controller
 * Xử lý các trang liên quan đến sản phẩm
 */
class ProductController extends Controller {
    
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function detail() {
        // Lấy ID sản phẩm
        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($productId == 0) {
            $this->redirect('index.php');
            return;
        }
        
        // Load model
        $productModel = $this->model('Product');
        
        // Lấy thông tin sản phẩm
        $product = $productModel->getProductDetail($productId);
        
        if (!$product) {
            $this->redirect('index.php');
            return;
        }
        
        // Lấy các biến thể (chỉ lấy biến thể còn hàng)
        $variants = $productModel->getProductVariants($productId, true);
        
        // Xử lý thêm vào giỏ hàng
        $successMessage = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
            $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            
            if ($variantId > 0 && $quantity > 0) {
                require_once __DIR__ . '/../models/Cart.php';
                $cartModel = new Cart();
                $cartModel->add($variantId, $quantity);
                $successMessage = 'Đã thêm vào giỏ hàng!';
            }
        }
        
        // Truyền dữ liệu vào view
        $data = [
            'page_title' => $product['TenSP'],
            'product' => $product,
            'variants' => $variants,
            'successMessage' => $successMessage
        ];
        
        $this->view('product/detail', $data);
    }
}
?>



