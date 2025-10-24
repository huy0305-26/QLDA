<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <section class="hero">
        <div class="hero-content">
            <h1>Chào mừng đến với Shop Quần Áo</h1>
            <p>Thời trang cao cấp - Giá cả hợp lý - Chất lượng đảm bảo</p>
        </div>
    </section>

    <?php if ($currentCategory): ?>
    <div class="breadcrumb">
        <a href="index.php">Trang chủ</a> / <span><?php echo htmlspecialchars($currentCategory['TenDM']); ?></span>
    </div>
    <?php endif; ?>

    <section class="products">
        <h2>Sản phẩm <?php echo $currentCategory ? '- ' . htmlspecialchars($currentCategory['TenDM']) : 'mới nhất'; ?></h2>
        
        <?php if (!empty($products)): ?>
        <div class="product-grid">
            <?php foreach($products as $product): ?>
            <div class="product-card">
                <?php 
                // Tính phần trăm giảm giá
                $discountPercent = 0;
                if (!empty($product['GiaGocThapNhat']) && $product['GiaGocThapNhat'] > $product['GiaThapNhat']) {
                    $discountPercent = round((($product['GiaGocThapNhat'] - $product['GiaThapNhat']) / $product['GiaGocThapNhat']) * 100);
                }
                ?>
                
                <?php if ($discountPercent > 0): ?>
                <div class="product-badge">-<?php echo $discountPercent; ?>%</div>
                <?php endif; ?>
                
                <a href="index.php?controller=product&action=detail&id=<?php echo $product['MaSP']; ?>" class="product-link">
                    <div class="product-image">
                        <?php if (!empty($product['HinhAnh'])): ?>
                            <img src="uploads/products/<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['TenSP']); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-tshirt fa-5x"></i>
                                <p>Chưa có ảnh</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="product-info">
                    <a href="index.php?controller=product&action=detail&id=<?php echo $product['MaSP']; ?>" class="product-title-link">
                        <h3><?php echo htmlspecialchars($product['TenSP']); ?></h3>
                    </a>
                    <p class="product-brand"><?php echo htmlspecialchars($product['TenTH']); ?></p>
                    
                    <div class="product-price-box">
                        <?php if ($product['GiaThapNhat'] == $product['GiaCaoNhat']): ?>
                            <div class="price-current"><?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>₫</div>
                            <?php if (!empty($product['GiaGocThapNhat']) && $product['GiaGocThapNhat'] > $product['GiaThapNhat']): ?>
                                <div class="price-original"><?php echo number_format($product['GiaGocThapNhat'], 0, ',', '.'); ?>₫</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="price-current"><?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>₫ - <?php echo number_format($product['GiaCaoNhat'], 0, ',', '.'); ?>₫</div>
                            <?php if (!empty($product['GiaGocThapNhat']) && $product['GiaGocThapNhat'] > $product['GiaThapNhat']): ?>
                                <div class="price-original"><?php echo number_format($product['GiaGocThapNhat'], 0, ',', '.'); ?>₫</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-products">
            <i class="fas fa-shopping-bag fa-3x"></i>
            <p>Không có sản phẩm nào trong danh mục này</p>
        </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>



