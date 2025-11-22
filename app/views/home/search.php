<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container search-page">
    <section class="search-hero">
        <div class="hero-content">
            <h1>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($keyword); ?>"</h1>
            <p class="text-muted"><?php echo count($products); ?> sản phẩm được tìm thấy</p>
        </div>
    </section>

    <div class="search-layout">
        <!-- Sidebar Filter -->
        <aside class="search-sidebar">
            <!-- Danh mục -->
            <?php if (!empty($categories)): ?>
            <div class="filter-group">
                <h3 class="filter-title">Danh mục</h3>
                <ul class="filter-list">
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&brand=<?php echo $brandId; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>&origin=<?php echo urlencode($origin); ?>" 
                           class="filter-link <?php echo $categoryId == 0 ? 'active' : ''; ?>">
                            <i class="fas fa-th-large"></i> Tất cả danh mục
                        </a>
                    </li>
                    <?php foreach($categories as $cat): ?>
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $cat['MaDM']; ?>&brand=<?php echo $brandId; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>&origin=<?php echo urlencode($origin); ?>" 
                           class="filter-link <?php echo $categoryId == $cat['MaDM'] ? 'active' : ''; ?>">
                            <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($cat['TenDM']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Thương hiệu -->
            <?php if (!empty($brands)): ?>
            <div class="filter-group">
                <h3 class="filter-title">Thương hiệu</h3>
                <ul class="filter-list">
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>&origin=<?php echo urlencode($origin); ?>" 
                           class="filter-link <?php echo $brandId == 0 ? 'active' : ''; ?>">
                            <i class="fas fa-certificate"></i> Tất cả thương hiệu
                        </a>
                    </li>
                    <?php foreach($brands as $brand): ?>
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>&brand=<?php echo $brand['MaTH']; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>&origin=<?php echo urlencode($origin); ?>" 
                           class="filter-link <?php echo $brandId == $brand['MaTH'] ? 'active' : ''; ?>">
                            <i class="fas fa-check"></i> <?php echo htmlspecialchars($brand['TenTH']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Giá bán -->
            <div class="filter-group">
                <h3 class="filter-title">Giá bán</h3>
                <ul class="filter-list">
                    <?php
                    $priceRanges = [
                        ['min' => 0, 'max' => 0, 'label' => 'Tất cả mức giá'],
                        ['min' => 0, 'max' => 100000, 'label' => 'Dưới 100.000đ'],
                        ['min' => 100000, 'max' => 300000, 'label' => '100.000đ - 300.000đ'],
                        ['min' => 300000, 'max' => 500000, 'label' => '300.000đ - 500.000đ'],
                        ['min' => 500000, 'max' => 0, 'label' => 'Trên 500.000đ']
                    ];
                    ?>
                    <?php foreach($priceRanges as $range): ?>
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>&brand=<?php echo $brandId; ?>&min_price=<?php echo $range['min']; ?>&max_price=<?php echo $range['max']; ?>&origin=<?php echo urlencode($origin); ?>" 
                           class="filter-link <?php echo ($minPrice == $range['min'] && $maxPrice == $range['max']) ? 'active' : ''; ?>">
                            <i class="fas fa-tag"></i> <?php echo $range['label']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Xuất xứ -->
            <?php if (!empty($origins)): ?>
            <div class="filter-group">
                <h3 class="filter-title">Xuất xứ</h3>
                <ul class="filter-list">
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>&brand=<?php echo $brandId; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>" 
                           class="filter-link <?php echo empty($origin) ? 'active' : ''; ?>">
                            <i class="fas fa-globe"></i> Tất cả xuất xứ
                        </a>
                    </li>
                    <?php foreach($origins as $org): ?>
                    <li>
                        <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>&brand=<?php echo $brandId; ?>&min_price=<?php echo $minPrice; ?>&max_price=<?php echo $maxPrice; ?>&origin=<?php echo urlencode($org); ?>" 
                           class="filter-link <?php echo $origin == $org ? 'active' : ''; ?>">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($org); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>" class="btn btn-secondary btn-block" style="margin-top: 1rem;">
                <i class="fas fa-sync-alt"></i> Xóa bộ lọc
            </a>
        </aside>

        <!-- Product Results -->
        <section class="search-results">
            <div class="results-header">
                <h2>Sản phẩm tìm được</h2>
                <div class="results-count">
                    <?php echo $totalRecords; ?> kết quả
                    <?php if ($totalPages > 1): ?>
                    (Trang <?php echo $page; ?>/<?php echo $totalPages; ?>)
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach($products as $product): ?>
                <div class="product-card">
                    <?php 
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
                            
                            <div class="freeship-badge">
                                <i class="fas fa-truck"></i> Freeship
                            </div>
                        </div>
                    </a>
                    <div class="product-info">
                        <a href="index.php?controller=product&action=detail&id=<?php echo $product['MaSP']; ?>" class="product-title-link">
                            <h3><?php echo htmlspecialchars($product['TenSP']); ?></h3>
                        </a>
                        <p class="product-brand"><?php echo htmlspecialchars($product['TenTH']); ?></p>

                        <div class="product-price-box">
                            <?php if (isset($product['GiaCaoNhat']) && $product['GiaThapNhat'] != $product['GiaCaoNhat']): ?>
                                <div class="price-current"><?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>₫ - <?php echo number_format($product['GiaCaoNhat'], 0, ',', '.'); ?>₫</div>
                                <?php if (!empty($product['GiaGocThapNhat']) && $product['GiaGocThapNhat'] > $product['GiaThapNhat']): ?>
                                    <div class="price-original"><?php echo number_format($product['GiaGocThapNhat'], 0, ',', '.'); ?>₫</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="price-current"><?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>₫</div>
                                <?php if (!empty($product['GiaGocThapNhat']) && $product['GiaGocThapNhat'] > $product['GiaThapNhat']): ?>
                                    <div class="price-original"><?php echo number_format($product['GiaGocThapNhat'], 0, ',', '.'); ?>₫</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php 
                $queryParams = $_GET;
                unset($queryParams['page']);
                $queryString = http_build_query($queryParams);
                ?>
                
                <?php if ($page > 1): ?>
                <a href="index.php?<?php echo $queryString; ?>&page=<?php echo ($page - 1); ?>" class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?<?php echo $queryString; ?>&page=<?php echo $i; ?>" 
                   class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <a href="index.php?<?php echo $queryString; ?>&page=<?php echo ($page + 1); ?>" class="page-link">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="no-products">
                <i class="fas fa-search fa-3x"></i>
                <p>Không tìm thấy sản phẩm nào phù hợp với tiêu chí lọc.</p>
                <a href="index.php?controller=home&action=search&q=<?php echo urlencode($keyword); ?>" class="btn btn-primary">Xóa bộ lọc</a>
            </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
