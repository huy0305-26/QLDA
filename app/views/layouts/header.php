<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>DTH Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    require_once __DIR__ . '/../../models/Cart.php';
    require_once __DIR__ . '/../../models/Category.php';
    $cartModel = new Cart();
    $cartCount = $cartModel->count();
    
    // Lấy cây danh mục
    $categoryModel = new Category();
    $categoriesTree = $categoryModel->getCategoriesTree();
    ?>
    
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <span>DTH Store</span>
                    </a>
                </div>
                <nav class="nav">
                    <?php foreach ($categoriesTree as $parent): ?>
                        <?php if (!empty($parent['children'])): ?>
                            <!-- Danh mục có con - hiển thị dropdown -->
                            <div class="nav-dropdown">
                                <a href="index.php?category=<?php echo $parent['MaDM']; ?>" class="nav-link">
                                    <?php echo htmlspecialchars($parent['TenDM']); ?>
                                    <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 3px;"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="index.php?category=<?php echo $parent['MaDM']; ?>" class="dropdown-item">
                                        <i class="fas fa-th"></i> Tất cả <?php echo htmlspecialchars($parent['TenDM']); ?>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <?php foreach ($parent['children'] as $child): ?>
                                    <a href="index.php?category=<?php echo $child['MaDM']; ?>" class="dropdown-item">
                                        <?php echo htmlspecialchars($child['TenDM']); ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Danh mục không có con - link thường -->
                            <a href="index.php?category=<?php echo $parent['MaDM']; ?>" class="nav-link">
                                <?php echo htmlspecialchars($parent['TenDM']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
                <div class="header-actions">
                    <form class="search-form" action="index.php" method="get" id="siteSearchForm">
                        <input type="hidden" name="controller" value="home">
                        <input type="hidden" name="action" value="search">
                        <input type="text" name="q" class="search-input" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';?>" aria-label="Tìm kiếm">
                        <button type="submit" class="search-toggle" id="searchToggleBtn" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                    </form>
                    <a href="index.php?controller=cart" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if($cartCount > 0): ?>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main class="main">
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('siteSearchForm');
            const input = form.querySelector('.search-input');
            const btn = document.getElementById('searchToggleBtn');

            // Collapse initially
            if (input.value.trim() !== '') {
                form.classList.add('active');
            }

            btn.addEventListener('click', function(e) {
                // If collapsed, expand and focus instead of submitting
                if (!form.classList.contains('active')) {
                    e.preventDefault();
                    form.classList.add('active');
                    input.focus();
                } else if (input.value.trim() === '') {
                    // If expanded but empty, don't submit
                    e.preventDefault();
                    input.focus();
                }
            });

            // Collapse when leaving the input with empty value
            input.addEventListener('blur', function() {
                setTimeout(function() {
                    if (input.value.trim() === '') {
                        form.classList.remove('active');
                    }
                }, 100);
            });
        });
        </script>



