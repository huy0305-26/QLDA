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
                    <div style="margin:8px 0;text-align:center;">— hoặc —</div>
                    <input type="url" 
                           id="hinh_anh_url" 
                           name="hinh_anh_url" 
                           class="form-control" 
                           placeholder="Dán URL ảnh (https://...)">
                    <small class="form-hint">
                        <i class="fas fa-info-circle"></i> 
                        Tải file hoặc dùng URL (Cloud). Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 5MB khi tải file
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
                    <div style="display: flex; gap: 0.5rem;">
                        <select id="ma_th" name="ma_th" class="form-control" required style="flex: 1;">
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['MaTH']; ?>">
                                <?php echo htmlspecialchars($brand['TenTH']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" onclick="openBrandModal()" class="btn btn-success" style="white-space: nowrap;" title="Thêm thương hiệu mới">
                            <i class="fas fa-plus"></i> Thêm mới
                        </button>
                    </div>
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

<!-- Modal thêm thương hiệu -->
<div id="brandModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Thêm thương hiệu mới</h3>
            <button type="button" class="close-modal" onclick="closeBrandModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="brandForm" onsubmit="addBrand(event)">
                <div class="form-group">
                    <label for="ten_th">Tên thương hiệu <span class="required">*</span></label>
                    <input type="text" 
                           id="ten_th" 
                           name="ten_th" 
                           class="form-control" 
                           placeholder="Ví dụ: Nike, Adidas..."
                           required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thương hiệu
                    </button>
                    <button type="button" onclick="closeBrandModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </form>
        </div>
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

<style>
/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e1e8ed;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #999;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.close-modal:hover {
    background-color: #f0f0f0;
    color: #333;
}

.modal-body {
    padding: 1.5rem;
}
</style>

<script>
// Modal functions
function openBrandModal() {
    document.getElementById('brandModal').style.display = 'flex';
    document.getElementById('ten_th').focus();
}

function closeBrandModal() {
    document.getElementById('brandModal').style.display = 'none';
    document.getElementById('brandForm').reset();
}

// Close modal khi click bên ngoài
document.addEventListener('click', function(e) {
    const modal = document.getElementById('brandModal');
    if (e.target === modal) {
        closeBrandModal();
    }
});

// Thêm thương hiệu
function addBrand(event) {
    event.preventDefault();
    
    const tenTH = document.getElementById('ten_th').value.trim();
    
    if (!tenTH) {
        alert('Vui lòng nhập tên thương hiệu!');
        return;
    }
    
    // Tạo form data
    const formData = new FormData();
    formData.append('add_brand', '1');
    formData.append('ten_th', tenTH);
    
    // Gửi request
    fetch('index.php?action=addProduct', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Thêm option mới vào select
            const select = document.getElementById('ma_th');
            const option = new Option(tenTH, data.brand_id, true, true);
            select.add(option);
            
            // Đóng modal và reset form
            closeBrandModal();
            
            // Thông báo thành công
            alert('Đã thêm thương hiệu "' + tenTH + '" thành công!');
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm thương hiệu'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm thương hiệu!');
    });
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

// Preview từ URL ảnh cloud
document.getElementById('hinh_anh_url').addEventListener('input', function(e) {
    const url = e.target.value.trim();
    const preview = document.getElementById('preview-image');
    if (url.startsWith('http://') || url.startsWith('https://')) {
        preview.innerHTML = `
            <div style="border: 2px solid #ddd; padding: 10px; border-radius: 8px; display: inline-block;">
                <img src="${url}" style="max-width: 300px; max-height: 300px; display: block;">
                <p style="margin: 5px 0 0 0; text-align: center; font-size: 12px; color: #666;">
                    <i class=\"fas fa-link\"></i> URL ảnh đã nhập
                </p>
            </div>
        `;
    } else if (url === '') {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

