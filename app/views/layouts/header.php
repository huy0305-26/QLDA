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
    $cartModel = new Cart();
    $cartCount = $cartModel->count();
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
                    <a href="index.php?category=1" class="nav-link">Áo thun</a>
                    <a href="index.php?category=2" class="nav-link">Áo sơ mi</a>
                    <a href="index.php?category=3" class="nav-link">Quần jean</a>
                    <a href="index.php?category=4" class="nav-link">Quần tây</a>
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



