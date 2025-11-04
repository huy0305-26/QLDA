<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>DTH Admin</title>
    <link rel="stylesheet" href="../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-panel">
    <?php
    require_once __DIR__ . '/../../models/Admin.php';
    $adminModel = new Admin();
    $currentAdmin = $adminModel->getCurrentAdmin();
    ?>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <i class="fas fa-tshirt"></i>
            <h2>DTH Admin</h2>
        </div>
        
        <nav class="sidebar-nav">
            <a href="index.php?action=dashboard" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="index.php?action=products" class="nav-item">
                <i class="fas fa-box"></i>
                <span>Sản phẩm</span>
            </a>
            <a href="index.php?action=categories" class="nav-item">
                <i class="fas fa-tags"></i>
                <span>Danh mục</span>
            </a>
            <a href="index.php?action=orders" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Đơn hàng</span>
            </a>
            <a href="index.php?action=customers" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Khách hàng</span>
            </a>
            <a href="index.php?action=reports" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Báo cáo</span>
            </a>
            <?php if (isset($_SESSION['admin_permission']) && $_SESSION['admin_permission'] == 'ToanQuyen'): ?>
            <a href="index.php?action=users" class="nav-item">
                <i class="fas fa-user-shield"></i>
                <span>Phân quyền</span>
            </a>
            <?php endif; ?>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../public/index.php" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Trang chủ</span>
            </a>
            <a href="index.php?action=logout" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <h1><?php echo $page_title ?? 'DTH Admin'; ?></h1>
            </div>
            <div class="topbar-right">
                <div class="admin-info">
                    <i class="fas fa-user-circle fa-2x"></i>
                    <div>
                        <strong><?php echo $currentAdmin['HoTen'] ?? 'Admin'; ?></strong>
                        <small>
                            <?php 
                            // Lấy thông tin vai trò từ database
                            $roleName = $currentAdmin['TenVaiTro'] ?? $_SESSION['admin_role'] ?? 'Nhân viên';
                            $permission = $currentAdmin['QuyenHan'] ?? $_SESSION['admin_permission'] ?? '';
                            
                            $roleIcons = [
                                'ToanQuyen' => '👑',
                                'NhapXuat' => '📦',
                                'XemBanHang' => '👤'
                            ];
                            echo ($roleIcons[$permission] ?? '👤') . ' ' . htmlspecialchars($roleName);
                            ?>
                        </small>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <main class="admin-content">

