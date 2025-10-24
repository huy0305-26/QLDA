<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-edit"></i> Sửa sản phẩm</h2>
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
                    <label>Mã sản phẩm</label>
                    <input type="text" class="form-control" value="#<?php echo $product['MaSP']; ?>" disabled>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ten_sp">Tên sản phẩm <span class="required">*</span></label>
                    <input type="text" 
                           id="ten_sp" 
                           name="ten_sp" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($product['TenSP']); ?>"
                           required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="hinh_anh">Hình ảnh sản phẩm</label>
                    <?php if (!empty($product['HinhAnh'])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="../public/uploads/products/<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
                             style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; padding: 5px; border-radius: 8px;">
                        <p style="margin: 5px 0; font-size: 12px; color: #666;">
                            <i class="fas fa-image"></i> Ảnh hiện tại
                        </p>
                    </div>
                    <?php endif; ?>
                    <input type="file" 
                           id="hinh_anh" 
                           name="hinh_anh" 
                           class="form-control"
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                    <small class="form-hint">
                        <i class="fas fa-info-circle"></i> 
                        Chọn ảnh mới để thay thế (JPG, PNG, GIF, WEBP - Max 5MB)
                    </small>
                    <div id="preview-image" style="margin-top: 10px;"></div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ma_dm">Danh mục <span class="required">*</span></label>
                    <select id="ma_dm" name="ma_dm" class="form-control" required>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['MaDM']; ?>" 
                                <?php echo $category['MaDM'] == $product['MaDM'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['TenDM']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ma_th">Thương hiệu <span class="required">*</span></label>
                    <select id="ma_th" name="ma_th" class="form-control" required>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['MaTH']; ?>"
                                <?php echo $brand['MaTH'] == $product['MaTH'] ? 'selected' : ''; ?>>
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
                           value="<?php echo htmlspecialchars($product['XuatXu']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="mo_ta">Mô tả sản phẩm</label>
                    <textarea id="mo_ta" 
                              name="mo_ta" 
                              class="form-control" 
                              rows="5"><?php echo htmlspecialchars($product['MoTa']); ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật sản phẩm
                </button>
                <a href="index.php?action=products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quản lý biến thể (Giá, Size, Màu, Tồn kho) -->
<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-palette"></i> Quản lý biến thể (Size, Màu sắc, Giá)</h3>
        <button onclick="showAddVariantForm()" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Thêm biến thể
        </button>
    </div>
    <div class="card-body">
        <?php if (!empty($variants)): ?>
        <table class="data-table" id="variants-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Size</th>
                    <th>Màu sắc</th>
                    <th>Giá nhập</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($variants as $variant): ?>
                <tr id="variant-<?php echo $variant['MaSP_BienThe']; ?>">
                    <td>#<?php echo $variant['MaSP_BienThe']; ?></td>
                    <td>
                        <span class="var-text"><?php echo htmlspecialchars($variant['KichThuoc']); ?></span>
                        <input type="text" class="form-control var-input" style="display:none;" value="<?php echo htmlspecialchars($variant['KichThuoc']); ?>">
                    </td>
                    <td>
                        <span class="var-text"><?php echo htmlspecialchars($variant['MauSac']); ?></span>
                        <input type="text" class="form-control var-input" style="display:none;" value="<?php echo htmlspecialchars($variant['MauSac']); ?>">
                    </td>
                    <td>
                        <span class="var-text"><?php echo number_format($variant['GiaNhap'], 0, ',', '.'); ?>đ</span>
                        <input type="number" class="form-control var-input" style="display:none;" value="<?php echo $variant['GiaNhap']; ?>">
                    </td>
                    <td>
                        <span class="var-text text-success"><strong><?php echo number_format($variant['GiaBan'], 0, ',', '.'); ?>đ</strong></span>
                        <input type="number" class="form-control var-input" style="display:none;" value="<?php echo $variant['GiaBan']; ?>">
                    </td>
                    <td>
                        <span class="var-text"><?php echo $variant['TonKho']; ?></span>
                        <input type="number" class="form-control var-input" style="display:none;" value="<?php echo $variant['TonKho']; ?>">
                    </td>
                    <td class="actions">
                        <button onclick="editVariant(<?php echo $variant['MaSP_BienThe']; ?>)" class="btn btn-sm btn-primary btn-edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="saveVariant(<?php echo $variant['MaSP_BienThe']; ?>)" class="btn btn-sm btn-success btn-save" style="display:none;">
                            <i class="fas fa-save"></i>
                        </button>
                        <button onclick="cancelEditVariant(<?php echo $variant['MaSP_BienThe']; ?>)" class="btn btn-sm btn-secondary btn-cancel" style="display:none;">
                            <i class="fas fa-times"></i>
                        </button>
                        <a href="index.php?action=deleteVariant&id=<?php echo $variant['MaSP_BienThe']; ?>&product_id=<?php echo $product['MaSP']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc muốn xóa biến thể này?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Sản phẩm chưa có biến thể nào. Hãy thêm biến thể để khách hàng có thể mua sản phẩm này.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form thêm biến thể -->
<div id="add-variant-form" class="admin-card" style="display:none;">
    <div class="card-header">
        <h3><i class="fas fa-plus"></i> Thêm biến thể mới</h3>
        <button onclick="hideAddVariantForm()" class="btn btn-sm btn-secondary">
            <i class="fas fa-times"></i> Đóng
        </button>
    </div>
    <div class="card-body">
        <form method="POST" action="" class="admin-form">
            <input type="hidden" name="add_variant" value="1">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="kich_thuoc">Kích thước/Size <span class="required">*</span></label>
                    <input type="text" id="kich_thuoc" name="kich_thuoc" class="form-control" placeholder="VD: M, L, XL, 29, 30..." required>
                </div>
                
                <div class="form-group">
                    <label for="mau_sac">Màu sắc <span class="required">*</span></label>
                    <input type="text" id="mau_sac" name="mau_sac" class="form-control" placeholder="VD: Đen, Trắng, Xanh..." required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="gia_nhap">Giá nhập <span class="required">*</span></label>
                    <input type="number" id="gia_nhap" name="gia_nhap" class="form-control" placeholder="VD: 100000" required>
                </div>
                
                <div class="form-group">
                    <label for="gia_ban">Giá bán <span class="required">*</span></label>
                    <input type="number" id="gia_ban" name="gia_ban" class="form-control" placeholder="VD: 150000" required>
                </div>
                
                <div class="form-group">
                    <label for="ton_kho">Tồn kho <span class="required">*</span></label>
                    <input type="number" id="ton_kho" name="ton_kho" class="form-control" value="0" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Thêm biến thể
                </button>
                <button type="button" onclick="hideAddVariantForm()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit variant
function editVariant(id) {
    const row = document.getElementById('variant-' + id);
    const texts = row.querySelectorAll('.var-text');
    const inputs = row.querySelectorAll('.var-input');
    
    texts.forEach(text => text.style.display = 'none');
    inputs.forEach(input => input.style.display = 'block');
    
    row.querySelector('.btn-edit').style.display = 'none';
    row.querySelector('.btn-save').style.display = 'inline-block';
    row.querySelector('.btn-cancel').style.display = 'inline-block';
}

// Cancel edit
function cancelEditVariant(id) {
    const row = document.getElementById('variant-' + id);
    const texts = row.querySelectorAll('.var-text');
    const inputs = row.querySelectorAll('.var-input');
    
    texts.forEach(text => text.style.display = 'inline');
    inputs.forEach(input => input.style.display = 'none');
    
    row.querySelector('.btn-edit').style.display = 'inline-block';
    row.querySelector('.btn-save').style.display = 'none';
    row.querySelector('.btn-cancel').style.display = 'none';
}

// Save variant
function saveVariant(id) {
    const row = document.getElementById('variant-' + id);
    const inputs = row.querySelectorAll('.var-input');
    
    const data = {
        id: id,
        kich_thuoc: inputs[0].value,
        mau_sac: inputs[1].value,
        gia_nhap: inputs[2].value,
        gia_ban: inputs[3].value,
        ton_kho: inputs[4].value
    };
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    
    for (const key in data) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }
    
    const updateInput = document.createElement('input');
    updateInput.type = 'hidden';
    updateInput.name = 'update_variant';
    updateInput.value = '1';
    form.appendChild(updateInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Show/Hide add form
function showAddVariantForm() {
    document.getElementById('add-variant-form').style.display = 'block';
    document.getElementById('add-variant-form').scrollIntoView({ behavior: 'smooth' });
}

function hideAddVariantForm() {
    document.getElementById('add-variant-form').style.display = 'none';
}

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
                <div style="border: 2px solid #4CAF50; padding: 10px; border-radius: 8px; display: inline-block; background: #f0f8f0;">
                    <p style="margin: 0 0 10px 0; font-weight: bold; color: #4CAF50;">
                        <i class="fas fa-arrow-right"></i> Ảnh mới sẽ thay thế:
                    </p>
                    <img src="${e.target.result}" style="max-width: 300px; max-height: 300px; display: block;">
                    <p style="margin: 5px 0 0 0; text-align: center; font-size: 12px; color: #666;">
                        <i class="fas fa-check-circle" style="color: green;"></i> ${file.name}
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

