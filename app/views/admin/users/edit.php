<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-user-edit"></i> Chỉnh sửa người dùng #<?php echo $user['MaNV']; ?></h2>
    <a href="index.php?action=users" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<?php if (isset($message)): ?>
<div class="alert alert-<?php echo $message['type']; ?>">
    <i class="fas fa-<?php echo $message['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
    <?php echo $message['text']; ?>
</div>
<?php endif; ?>

<form method="POST" action="" class="admin-form">
<div class="admin-grid">
    <!-- Thông tin cơ bản -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Thông tin cơ bản</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Mã nhân viên</label>
                <input type="text" class="form-control" value="#<?php echo $user['MaNV']; ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="ho_ten">Họ và tên <span class="required">*</span></label>
                <input type="text" 
                       id="ho_ten" 
                       name="ho_ten" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($user['HoTen']); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>"
                       placeholder="example@email.com">
            </div>
            
            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" 
                       id="so_dien_thoai" 
                       name="so_dien_thoai" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($user['SoDienThoai'] ?? ''); ?>"
                       placeholder="0901234567">
            </div>
        </div>
    </div>
    
    <!-- Tài khoản & Phân quyền -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-lock"></i> Tài khoản & Phân quyền</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="ten_dang_nhap">Tên đăng nhập <span class="required">*</span></label>
                <input type="text" 
                       id="ten_dang_nhap" 
                       name="ten_dang_nhap" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($user['TenDangNhap']); ?>"
                       required>
                <small class="form-hint">⚠️ Thay đổi tên đăng nhập sẽ ảnh hưởng đến việc đăng nhập</small>
            </div>
            
            <?php if ($user['MaNV'] != $_SESSION['admin_id']): ?>
            <div class="form-group">
                <label for="ma_vai_tro">Vai trò <span class="required">*</span></label>
                <select id="ma_vai_tro" name="ma_vai_tro" class="form-control" required>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['MaVaiTro']; ?>" 
                            <?php echo $user['MaVaiTro'] == $role['MaVaiTro'] ? 'selected' : ''; ?>>
                        <?php 
                        $roleIcons = [
                            'ToanQuyen' => '👑',
                            'NhapXuat' => '📦',
                            'XemBanHang' => '👤'
                        ];
                        echo ($roleIcons[$role['QuyenHan']] ?? '') . ' ' . htmlspecialchars($role['TenVaiTro']);
                        ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-hint">
                    Quyền hạn hiện tại: <strong><?php echo htmlspecialchars($user['QuyenHan']); ?></strong>
                </small>
            </div>
            
            <div class="form-group">
                <label for="trang_thai">Trạng thái <span class="required">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-control" required>
                    <option value="active" <?php echo $user['TrangThai'] == 'active' ? 'selected' : ''; ?>>✅ Hoạt động</option>
                    <option value="inactive" <?php echo $user['TrangThai'] != 'active' ? 'selected' : ''; ?>>🔒 Đã khóa</option>
                </select>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Không thể thay đổi vai trò và trạng thái của chính mình
                <br>
                <small>Vai trò hiện tại: <strong><?php echo htmlspecialchars($user['TenVaiTro']); ?></strong></small>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="change_password" id="change-password" onchange="togglePasswordFields()">
                    <span>🔑 Đổi mật khẩu</span>
                </label>
            </div>
            
            <div id="password-fields" style="display: none;">
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới <span class="required">*</span></label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-control" 
                           placeholder="Tối thiểu 6 ký tự">
                    <small class="form-hint">💡 Mật khẩu nên có ít nhất 6 ký tự</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu <span class="required">*</span></label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           class="form-control" 
                           placeholder="Nhập lại mật khẩu mới">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nút lưu -->
<div class="form-actions" style="text-align: center; margin-top: 1rem;">
    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i> Lưu thay đổi
    </button>
    <a href="index.php?action=users" class="btn btn-secondary btn-lg">
        <i class="fas fa-times"></i> Hủy
    </a>
</div>
</form>

<style>
.admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-hint {
    display: block;
    color: #7f8c8d;
    font-size: 0.85rem;
    margin-top: 0.3rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    padding: 0.8rem;
    background: #f8f9fa;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    transition: all 0.3s;
}

.checkbox-label:hover {
    background: #e3f2fd;
    border-color: #3498db;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

#password-fields {
    padding: 1rem;
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 8px;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .admin-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Toggle password fields
function togglePasswordFields() {
    const checkbox = document.getElementById('change-password');
    const passwordFields = document.getElementById('password-fields');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (checkbox.checked) {
        passwordFields.style.display = 'block';
        newPassword.required = true;
        confirmPassword.required = true;
    } else {
        passwordFields.style.display = 'none';
        newPassword.required = false;
        confirmPassword.required = false;
        newPassword.value = '';
        confirmPassword.value = '';
    }
}

// Validate form before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const changePassword = document.getElementById('change-password').checked;
    
    if (changePassword) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp!');
            return false;
        }
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

