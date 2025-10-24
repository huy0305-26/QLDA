<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-users-cog"></i> Quản lý người dùng & Phân quyền</h2>
</div>

<?php if (isset($message)): ?>
<div class="alert alert-<?php echo $message['type']; ?>">
    <i class="fas fa-<?php echo $message['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
    <?php echo $message['text']; ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-user-shield"></i> Danh sách nhân viên</h3>
        <span class="badge">Tổng: <?php echo count($users); ?> người dùng</span>
    </div>
    <div class="card-body">
        <?php if (!empty($users)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr id="user-row-<?php echo $user['MaNV']; ?>" class="<?php echo $user['TrangThai'] != 'active' ? 'inactive-user' : ''; ?>">
                    <td><strong>#<?php echo $user['MaNV']; ?></strong></td>
                    <td>
                        <div class="user-info">
                            <i class="fas fa-user-circle" style="margin-right: 0.5rem; color: #3498db;"></i>
                            <strong><?php echo htmlspecialchars($user['HoTen']); ?></strong>
                        </div>
                    </td>
                    <td><code><?php echo htmlspecialchars($user['TenDangNhap']); ?></code></td>
                    <td><small><?php echo htmlspecialchars($user['Email'] ?? 'Chưa có'); ?></small></td>
                    <td><small><?php echo htmlspecialchars($user['SoDienThoai'] ?? 'Chưa có'); ?></small></td>
                    <td>
                        <?php if ($user['MaNV'] == $_SESSION['admin_id']): ?>
                            <!-- Không cho phép tự thay đổi vai trò của chính mình -->
                            <span class="role-badge role-<?php echo $user['QuyenHan']; ?>">
                                <?php 
                                $roleIcons = [
                                    'ToanQuyen' => '👑',
                                    'NhapXuat' => '📦',
                                    'XemBanHang' => '👤'
                                ];
                                echo ($roleIcons[$user['QuyenHan']] ?? '') . ' ' . htmlspecialchars($user['TenVaiTro']);
                                ?>
                            </span>
                        <?php else: ?>
                            <select class="role-select" 
                                    onchange="updateUserRole(<?php echo $user['MaNV']; ?>, this.value)"
                                    <?php echo $user['TrangThai'] != 'active' ? 'disabled' : ''; ?>>
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
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['MaNV'] == $_SESSION['admin_id']): ?>
                            <span class="status-badge status-active">Đang đăng nhập</span>
                        <?php else: ?>
                            <button class="status-toggle" 
                                    onclick="toggleUserStatus(<?php echo $user['MaNV']; ?>)"
                                    data-status="<?php echo $user['TrangThai']; ?>">
                                <span class="status-badge status-<?php echo $user['TrangThai'] == 'active' ? 'active' : 'inactive'; ?>">
                                    <?php echo $user['TrangThai'] == 'active' ? '✅ Hoạt động' : '🔒 Đã khóa'; ?>
                                </span>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="index.php?action=editUser&id=<?php echo $user['MaNV']; ?>" 
                           class="btn btn-sm btn-primary"
                           title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($user['MaNV'] != $_SESSION['admin_id']): ?>
                            <button onclick="resetPassword(<?php echo $user['MaNV']; ?>)" 
                                    class="btn btn-sm btn-warning"
                                    title="Reset mật khẩu">
                                <i class="fas fa-key"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users fa-5x"></i>
            <h3>Chưa có người dùng nào</h3>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bảng phân quyền chi tiết -->
<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-shield-alt"></i> Chi tiết phân quyền</h3>
    </div>
    <div class="card-body">
        <table class="permission-table">
            <thead>
                <tr>
                    <th>Chức năng</th>
                    <th>👤 Nhân viên bán hàng</th>
                    <th>📦 Quản lý kho</th>
                    <th>👑 Quản trị viên</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Dashboard</strong></td>
                    <td>✅ Xem</td>
                    <td>✅ Xem</td>
                    <td>✅ Xem</td>
                </tr>
                <tr>
                    <td><strong>Sản phẩm</strong></td>
                    <td>👁️ Chỉ xem</td>
                    <td>✅ Thêm/Sửa/Xóa</td>
                    <td>✅ Toàn quyền</td>
                </tr>
                <tr>
                    <td><strong>Danh mục</strong></td>
                    <td>👁️ Chỉ xem</td>
                    <td>✅ Thêm/Sửa/Xóa</td>
                    <td>✅ Toàn quyền</td>
                </tr>
                <tr>
                    <td><strong>Đơn hàng</strong></td>
                    <td>✅ Xem/Xử lý</td>
                    <td>👁️ Chỉ xem</td>
                    <td>✅ Toàn quyền</td>
                </tr>
                <tr>
                    <td><strong>Khách hàng</strong></td>
                    <td>✅ Xem/Sửa</td>
                    <td>👁️ Chỉ xem</td>
                    <td>✅ Toàn quyền</td>
                </tr>
                <tr>
                    <td><strong>Báo cáo</strong></td>
                    <td>✅ Xem</td>
                    <td>✅ Xem</td>
                    <td>✅ Xem</td>
                </tr>
                <tr class="highlight-row">
                    <td><strong>Phân quyền người dùng</strong></td>
                    <td>❌ Không</td>
                    <td>❌ Không</td>
                    <td>✅ Toàn quyền</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.role-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.85rem;
}

.role-ToanQuyen {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.role-NhapXuat {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.role-XemBanHang {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.role-select {
    padding: 0.5rem;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.role-select:hover:not(:disabled) {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.role-select:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.status-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: bold;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-toggle {
    background: none;
    border: none;
    cursor: pointer;
    transition: transform 0.2s;
}

.status-toggle:hover {
    transform: scale(1.05);
}

.inactive-user {
    opacity: 0.6;
    background: #f8f9fa;
}

.user-info {
    display: flex;
    align-items: center;
}

.permission-table {
    width: 100%;
    border-collapse: collapse;
}

.permission-table th,
.permission-table td {
    padding: 1rem;
    text-align: center;
    border: 1px solid #e1e8ed;
}

.permission-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.permission-table td:first-child {
    text-align: left;
    background: #f8f9fa;
}

.permission-table tbody tr:hover {
    background: #f0f8ff;
}

.highlight-row {
    background: #fff3cd !important;
}

.highlight-row:hover {
    background: #ffe69c !important;
}

.badge {
    background: #3498db;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
}
</style>

<script>
// Cập nhật vai trò người dùng
function updateUserRole(userId, newRoleId) {
    const selectElement = event.target;
    const selectedText = selectElement.options[selectElement.selectedIndex].text;
    
    if (confirm(`Bạn có chắc muốn đổi vai trò người dùng này thành "${selectedText}"?`)) {
        // Tạo form và submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        form.appendChild(userIdInput);
        
        const roleInput = document.createElement('input');
        roleInput.type = 'hidden';
        roleInput.name = 'new_role_id';
        roleInput.value = newRoleId;
        form.appendChild(roleInput);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'update_role';
        form.appendChild(actionInput);
        
        document.body.appendChild(form);
        form.submit();
    } else {
        // Reset về giá trị cũ nếu hủy
        location.reload();
    }
}

// Toggle trạng thái người dùng (Active/Inactive)
function toggleUserStatus(userId) {
    const row = document.getElementById('user-row-' + userId);
    const button = row.querySelector('.status-toggle');
    const currentStatus = button.dataset.status;
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'mở khóa' : 'khóa';
    
    if (confirm(`Bạn có chắc muốn ${action} người dùng này?`)) {
        // Tạo form và submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        form.appendChild(userIdInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'new_status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'toggle_status';
        form.appendChild(actionInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Reset mật khẩu
function resetPassword(userId) {
    const newPassword = prompt('Nhập mật khẩu mới cho người dùng:');
    
    if (newPassword && newPassword.length >= 6) {
        if (confirm('Bạn có chắc muốn reset mật khẩu người dùng này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = userId;
            form.appendChild(userIdInput);
            
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'new_password';
            passwordInput.value = newPassword;
            form.appendChild(passwordInput);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'reset_password';
            form.appendChild(actionInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    } else if (newPassword !== null) {
        alert('Mật khẩu phải có ít nhất 6 ký tự!');
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

