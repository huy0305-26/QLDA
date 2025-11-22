<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="hero-full-width">
    <img src="uploads/home/HOMEPAGE_BLACK_FRIDAY_141125_PC.webp" alt="Black Friday Sale">
</div>

<div class="container">

    <?php if ($currentCategory): ?>
    <div class="breadcrumb">
        <a href="index.php">Trang chủ</a> / <span><?php echo htmlspecialchars($currentCategory['TenDM']); ?></span>
    </div>
    <?php endif; ?>

    <section class="products">
        <h2 class="section-title">Sản phẩm mới</h2>        
        <?php if (!empty($products)): ?>
        <div class="product-grid" id="product-grid">
            <?php foreach($products as $product): ?>
            <div class="product-card">
                <a href="index.php?controller=product&action=detail&id=<?php echo $product['MaSP']; ?>" class="product-card-link">
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
                
                    <div class="product-image">
                        <?php if (!empty($product['HinhAnh'])): ?>
                            <?php 
                            $imageSrc = (preg_match('/^https?:\\/\\//', $product['HinhAnh'])) 
                                ? $product['HinhAnh'] 
                                : 'uploads/products/' . $product['HinhAnh'];
                            ?>
                            <img src="<?php echo htmlspecialchars($imageSrc); ?>" 
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
                
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($product['TenSP']); ?></h3>
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
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($showLoadMore && $currentCount < $totalProducts): ?>
        <div class="load-more-container">
            <button id="loadMoreBtn" class="btn btn-primary btn-load-more" data-category="<?php echo $categoryId; ?>" data-offset="<?php echo $currentCount; ?>">
                Xem thêm
            </button>
            <div id="loadingSpinner" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Đang tải...
            </div>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="no-products">
            <i class="fas fa-shopping-bag fa-3x"></i>
            <p>Không có sản phẩm nào trong danh mục này</p>
        </div>
        <?php endif; ?>
    </section>

</div>

<!-- Scrolling Banner -->
<div class="scrolling-banner">
    <div class="scrolling-content">
        <?php for($i = 0; $i < 10; $i++): ?>
        <span class="scrolling-item">MIỄN PHÍ SHIP</span>
        <?php endfor; ?>
        <!-- Duplicate for seamless loop -->
        <?php for($i = 0; $i < 10; $i++): ?>
        <span class="scrolling-item">MIỄN PHÍ SHIP</span>
        <?php endfor; ?>
    </div>
</div>

<div class="container">

    <?php if (!empty($collectionImages)): ?>
    <section class="collection-section">
        <h2 class="section-title">Trạm phong cách</h2>
        <div class="collection-grid">
            <?php foreach($collectionImages as $image): ?>
            <div class="collection-item">
                <img src="uploads/collection/<?php echo htmlspecialchars($image); ?>" alt="Collection Image">
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const productGrid = document.getElementById('product-grid');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');
            const offset = parseInt(this.getAttribute('data-offset'));
            
            // Ẩn nút và hiện spinner
            loadMoreBtn.style.display = 'none';
            loadingSpinner.style.display = 'block';
            
            // Gửi AJAX request
            fetch('index.php?controller=home&action=loadMore', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'category=' + categoryId + '&offset=' + offset
            })
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    // Thêm sản phẩm vào grid
                    data.products.forEach(product => {
                        const productCard = createProductCard(product);
                        productGrid.insertAdjacentHTML('beforeend', productCard);
                    });
                    
                    // Cập nhật offset
                    const newOffset = offset + data.products.length;
                    loadMoreBtn.setAttribute('data-offset', newOffset);
                    
                    // Hiện lại nút nếu còn sản phẩm
                    if (data.hasMore) {
                        loadMoreBtn.style.display = 'block';
                    }
                }
                
                loadingSpinner.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                loadingSpinner.style.display = 'none';
                loadMoreBtn.style.display = 'block';
                alert('Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại!');
            });
        });
    }
    
    function createProductCard(product) {
        // Tính phần trăm giảm giá
        let discountPercent = 0;
        if (product.GiaGocThapNhat && product.GiaGocThapNhat > product.GiaThapNhat) {
            discountPercent = Math.round(((product.GiaGocThapNhat - product.GiaThapNhat) / product.GiaGocThapNhat) * 100);
        }
        
        let discountBadge = '';
        if (discountPercent > 0) {
            discountBadge = `<div class="product-badge">-${discountPercent}%</div>`;
        }
        
        const freeshipBadge = `
            <div class="freeship-badge">
                <i class="fas fa-truck"></i> Freeship
            </div>
        `;
        
        let imageHtml = '';
        if (product.HinhAnh) {
            const isUrl = /^https?:\/\//i.test(product.HinhAnh);
            const src = isUrl ? product.HinhAnh : `uploads/products/${escapeHtml(product.HinhAnh)}`;
            imageHtml = `<img src="${src}" alt="${escapeHtml(product.TenSP)}">`;
        } else {
            imageHtml = `<div class="no-image"><i class="fas fa-tshirt fa-5x"></i><p>Chưa có ảnh</p></div>`;
        }
        
        let priceHtml = '';
        if (product.GiaThapNhat == product.GiaCaoNhat) {
            priceHtml = `<div class="price-current">${formatPrice(product.GiaThapNhat)}₫</div>`;
            if (product.GiaGocThapNhat && product.GiaGocThapNhat > product.GiaThapNhat) {
                priceHtml += `<div class="price-original">${formatPrice(product.GiaGocThapNhat)}₫</div>`;
            }
        } else {
            priceHtml = `<div class="price-current">${formatPrice(product.GiaThapNhat)}₫ - ${formatPrice(product.GiaCaoNhat)}₫</div>`;
            if (product.GiaGocThapNhat && product.GiaGocThapNhat > product.GiaThapNhat) {
                priceHtml += `<div class="price-original">${formatPrice(product.GiaGocThapNhat)}₫</div>`;
            }
        }
        
        return `
            <div class="product-card">
                <a href="index.php?controller=product&action=detail&id=${product.MaSP}" class="product-card-link">
                ${discountBadge}
                <div class="product-image">
                    ${imageHtml}
                    ${freeshipBadge}
                </div>
                <div class="product-info">
                    <h3 class="product-title">${escapeHtml(product.TenSP)}</h3>
                    <p class="product-brand">${escapeHtml(product.TenTH)}</p>
                    <div class="product-price-box">${priceHtml}</div>
                </div>
                </a>
            </div>
        `;
    }
    
    function formatPrice(price) {
        return Number(price).toLocaleString('vi-VN');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>



