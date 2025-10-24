<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Shop Quần Áo</title>
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
                        <i class="fas fa-tshirt"></i>
                        <span>Shop Quần Áo</span>
                    </a>
                </div>
                <nav class="nav">
                    <a href="index.php" class="nav-link">Trang chủ</a>
                    
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



