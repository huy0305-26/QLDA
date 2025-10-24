<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-tags"></i> Quản lý danh mục</h2>
</div>

<div class="admin-grid">
    <!-- Form thêm danh mục -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-plus"></i> Thêm danh mục mới</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="admin-form">
                <div class="form-group">
                    <label for="ten_dm">Tên danh mục</label>
                    <input type="text" 
                           id="ten_dm" 
                           name="ten_dm" 
                           class="form-control" 
                           placeholder="Nhập tên danh mục"
                           required>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary btn-block">
                    <i class="fas fa-plus"></i> Thêm danh mục
                </button>
            </form>
        </div>
    </div>
    
    <!-- Danh sách danh mục -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Danh sách danh mục</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($categories)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã DM</th>
                        <th>Tên danh mục</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr id="cat-<?php echo $category['MaDM']; ?>">
                        <td>#<?php echo $category['MaDM']; ?></td>
                        <td>
                            <span class="cat-name"><?php echo htmlspecialchars($category['TenDM']); ?></span>
                            <form method="POST" action="" class="edit-form" style="display:none;">
                                <input type="hidden" name="ma_dm" value="<?php echo $category['MaDM']; ?>">
                                <input type="text" name="ten_dm" class="form-control" value="<?php echo htmlspecialchars($category['TenDM']); ?>" required>
                            </form>
                        </td>
                        <td class="actions">
                            <a href="../public/index.php?category=<?php echo $category['MaDM']; ?>" 
                               class="btn btn-sm btn-info"
                               title="Xem sản phẩm"
                               target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="editCategory(<?php echo $category['MaDM']; ?>)" 
                                    class="btn btn-sm btn-primary btn-edit"
                                    title="Sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="saveCategory(<?php echo $category['MaDM']; ?>)" 
                                    class="btn btn-sm btn-success btn-save" 
                                    style="display:none;"
                                    title="Lưu">
                                <i class="fas fa-save"></i>
                            </button>
                            <button onclick="cancelEdit(<?php echo $category['MaDM']; ?>)" 
                                    class="btn btn-sm btn-secondary btn-cancel" 
                                    style="display:none;"
                                    title="Hủy">
                                <i class="fas fa-times"></i>
                            </button>
                            <a href="index.php?action=categories&delete=<?php echo $category['MaDM']; ?>" 
                               class="btn btn-sm btn-danger btn-delete"
                               title="Xóa"
                               onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <script>
            function editCategory(id) {
                const row = document.getElementById('cat-' + id);
                row.querySelector('.cat-name').style.display = 'none';
                row.querySelector('.edit-form').style.display = 'block';
                row.querySelector('.btn-edit').style.display = 'none';
                row.querySelector('.btn-delete').style.display = 'none';
                row.querySelector('.btn-save').style.display = 'inline-block';
                row.querySelector('.btn-cancel').style.display = 'inline-block';
            }
            
            function cancelEdit(id) {
                const row = document.getElementById('cat-' + id);
                row.querySelector('.cat-name').style.display = 'inline';
                row.querySelector('.edit-form').style.display = 'none';
                row.querySelector('.btn-edit').style.display = 'inline-block';
                row.querySelector('.btn-delete').style.display = 'inline-block';
                row.querySelector('.btn-save').style.display = 'none';
                row.querySelector('.btn-cancel').style.display = 'none';
            }
            
            function saveCategory(id) {
                const row = document.getElementById('cat-' + id);
                const form = row.querySelector('.edit-form');
                
                // Thêm hidden input để submit
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'update_category';
                input.value = '1';
                form.appendChild(input);
                
                form.submit();
            }
            </script>
            <?php else: ?>
            <p class="text-center text-muted">Chưa có danh mục nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

