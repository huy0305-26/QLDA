<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-user-shield fa-3x"></i>
                <h1>Đăng nhập Admin</h1>
                <p>Hệ thống quản lý Shop Quần Áo</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Tên đăng nhập
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           placeholder="Nhập tên đăng nhập"
                           required
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật khẩu
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Nhập mật khẩu"
                           required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>

            <div class="login-footer">
                <p>Tài khoản mẫu:</p>
                <p><strong>admin</strong> / <strong>123456</strong> (Quản trị viên)</p>
                <p><strong>mai_nv</strong> / <strong>123456</strong> (Nhân viên bán hàng)</p>
            </div>

            <div class="back-to-site">
                <a href="../public/index.php">
                    <i class="fas fa-arrow-left"></i> Quay về trang chủ
                </a>
            </div>
        </div>
    </div>
</body>
</html>

