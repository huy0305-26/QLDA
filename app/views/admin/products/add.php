<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-plus"></i> Thêm sản phẩm mới</h2>
    <a href="index.php?action=products" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<?php if ($message): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?php echo $message; ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="ten_sp">Tên sản phẩm <span class="required">*</span></label>
                    <input type="text" 
                           id="ten_sp" 
                           name="ten_sp" 
                           class="form-control" 
                           placeholder="Nhập tên sản phẩm"
                           required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="hinh_anh">Hình ảnh sản phẩm</label>
                    <input type="file" 
                           id="hinh_anh" 
                           name="hinh_anh" 
                           class="form-control"
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                    <small class="form-hint">
                        <i class="fas fa-info-circle"></i> 
                        Chấp nhận: JPG, PNG, GIF, WEBP. Kích thước tối đa: 5MB
                    </small>
                    <div id="preview-image" style="margin-top: 10px;"></div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ma_dm">Danh mục <span class="required">*</span></label>
                    <select id="ma_dm" name="ma_dm" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['MaDM']; ?>">
                            <?php echo htmlspecialchars($category['TenDM']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ma_th">Thương hiệu <span class="required">*</span></label>
                    <select id="ma_th" name="ma_th" class="form-control" required>
                        <option value="">-- Chọn thương hiệu --</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['MaTH']; ?>">
                            <?php echo htmlspecialchars($brand['TenTH']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="xuat_xu">Xuất xứ</label>
                    <input type="text" 
                           id="xuat_xu" 
                           name="xuat_xu" 
                           class="form-control" 
                           placeholder="Ví dụ: Việt Nam">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="mo_ta">Mô tả sản phẩm</label>
                    <textarea id="mo_ta" 
                              name="mo_ta" 
                              class="form-control" 
                              rows="5"
                              placeholder="Nhập mô tả chi tiết về sản phẩm"></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu sản phẩm
                </button>
                <a href="index.php?action=products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Lưu ý</h3>
    </div>
    <div class="card-body">
        <ul>
            <li>Sau khi thêm sản phẩm, bạn cần thêm biến thể (size, màu sắc, giá) trong database</li>
            <li>Sản phẩm cần có ít nhất 1 biến thể để hiển thị trên trang chủ</li>
            <li>Các trường đánh dấu <span class="required">*</span> là bắt buộc</li>
        </ul>
    </div>
</div>

<script>
// Preview ảnh trước khi upload
document.getElementById('hinh_anh').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview-image');
    
    if (file) {
        // Kiểm tra kích thước file (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Kích thước file quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
            e.target.value = '';
            preview.innerHTML = '';
            return;
        }
        
        // Hiển thị preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div style="border: 2px solid #ddd; padding: 10px; border-radius: 8px; display: inline-block;">
                    <img src="${e.target.result}" style="max-width: 300px; max-height: 300px; display: block;">
                    <p style="margin: 5px 0 0 0; text-align: center; font-size: 12px; color: #666;">
                        <i class="fas fa-check-circle" style="color: green;"></i> Đã chọn: ${file.name}
                    </p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

