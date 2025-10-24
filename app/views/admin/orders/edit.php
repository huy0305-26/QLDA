<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-edit"></i> Sửa đơn hàng #<?php echo $order['MaHD']; ?></h2>
    <a href="index.php?action=orders" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<?php if ($message): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?php echo $message; ?>
</div>
<?php endif; ?>

<!-- Form chỉnh sửa đơn hàng -->
<form method="POST" action="" class="admin-form">
<div class="admin-grid">
    <!-- Thông tin khách hàng -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Thông tin khách hàng</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="ten_kh">Tên khách hàng <span class="required">*</span></label>
                <input type="text" 
                       id="ten_kh" 
                       name="ten_kh" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($order['TenKH']); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại <span class="required">*</span></label>
                <input type="text" 
                       id="so_dien_thoai" 
                       name="so_dien_thoai" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($order['SoDienThoai']); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="dia_chi">Địa chỉ giao hàng <span class="required">*</span></label>
                <textarea id="dia_chi" 
                          name="dia_chi" 
                          class="form-control" 
                          rows="3"
                          required><?php echo htmlspecialchars($order['DiaChi']); ?></textarea>
            </div>
            
            <?php if (!empty($order['MaKH'])): ?>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="checkbox-label">
                    <input type="checkbox" name="update_customer_info" value="1" id="update-customer-checkbox">
                    <span class="checkbox-text">
                        <strong>🔄 Cập nhật thông tin vào tài khoản khách hàng</strong>
                        <small style="display: block; color: #7f8c8d; margin-top: 0.3rem;">
                            Thông tin sẽ được cập nhật vào trang "Quản lý khách hàng"
                        </small>
                    </span>
                </label>
            </div>
            <?php endif; ?>
            
            <table class="info-table" style="margin-top: 1rem;">
                <tr>
                    <td><strong>Mã đơn hàng:</strong></td>
                    <td>#<?php echo $order['MaHD']; ?></td>
                </tr>
                <tr>
                    <td><strong>Mã khách hàng:</strong></td>
                    <td><?php echo $order['MaKH'] ? '#' . $order['MaKH'] : '<em>Khách vãng lai</em>'; ?></td>
                </tr>
                <tr>
                    <td><strong>Ngày lập:</strong></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['NgayLap'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Nhân viên xử lý:</strong></td>
                    <td><?php echo htmlspecialchars($order['TenNV']); ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Trạng thái & Thanh toán -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Trạng thái & Thanh toán</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="trang_thai">Trạng thái đơn hàng <span class="required">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-control">
                    <option value="Đang xử lý" <?php echo ($order['TrangThai'] ?? 'Đang xử lý') == 'Đang xử lý' ? 'selected' : ''; ?>>⏳ Đang xử lý</option>
                    <option value="Đã xác nhận" <?php echo ($order['TrangThai'] ?? '') == 'Đã xác nhận' ? 'selected' : ''; ?>>✅ Đã xác nhận</option>
                    <option value="Đang giao" <?php echo ($order['TrangThai'] ?? '') == 'Đang giao' ? 'selected' : ''; ?>>🚚 Đang giao</option>
                    <option value="Hoàn thành" <?php echo ($order['TrangThai'] ?? '') == 'Hoàn thành' ? 'selected' : ''; ?>>✔️ Hoàn thành</option>
                    <option value="Đã hủy" <?php echo ($order['TrangThai'] ?? '') == 'Đã hủy' ? 'selected' : ''; ?>>❌ Đã hủy</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="hinh_thuc_thanh_toan">Hình thức thanh toán <span class="required">*</span></label>
                <select id="hinh_thuc_thanh_toan" name="hinh_thuc_thanh_toan" class="form-control">
                    <option value="Tiền mặt" <?php echo $order['HinhThucThanhToan'] == 'Tiền mặt' ? 'selected' : ''; ?>>💵 Tiền mặt</option>
                    <option value="Chuyển khoản" <?php echo $order['HinhThucThanhToan'] == 'Chuyển khoản' ? 'selected' : ''; ?>>🏦 Chuyển khoản</option>
                    <option value="Ví điện tử" <?php echo $order['HinhThucThanhToan'] == 'Ví điện tử' ? 'selected' : ''; ?>>💳 Ví điện tử</option>
                    <option value="COD" <?php echo $order['HinhThucThanhToan'] == 'COD' ? 'selected' : ''; ?>>📦 COD</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="ghi_chu">Ghi chú</label>
                <textarea id="ghi_chu" 
                          name="ghi_chu" 
                          class="form-control" 
                          rows="4"
                          placeholder="Ghi chú về đơn hàng..."><?php echo htmlspecialchars($order['GhiChu']); ?></textarea>
            </div>
            
            <div class="total-summary">
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span class="total-amount" id="total-amount"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chi tiết sản phẩm -->
<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Chi tiết đơn hàng</h3>
        <span class="badge">Tổng: <span id="total-items"><?php echo count($orderDetails); ?></span> sản phẩm</span>
    </div>
    <div class="card-body">
        <table class="data-table" id="order-details-table">
            <thead>
                <tr>
                    <th style="width: 30%">Sản phẩm</th>
                    <th style="width: 15%">Biến thể</th>
                    <th style="width: 15%">Đơn giá</th>
                    <th style="width: 15%">Số lượng</th>
                    <th style="width: 15%">Thành tiền</th>
                    <th style="width: 10%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $index => $detail): ?>
                <tr id="detail-row-<?php echo $detail['MaCTHD']; ?>" data-price="<?php echo $detail['DonGia']; ?>">
                    <td><?php echo htmlspecialchars($detail['TenSP']); ?></td>
                    <td>
                        <small>Size: <strong><?php echo $detail['KichThuoc']; ?></strong><br>
                        Màu: <strong><?php echo $detail['MauSac']; ?></strong></small>
                    </td>
                    <td class="text-center">
                        <span class="price-text"><?php echo number_format($detail['DonGia'], 0, ',', '.'); ?>đ</span>
                    </td>
                    <td class="text-center">
                        <div class="quantity-control">
                            <button type="button" class="qty-btn" onclick="decreaseQty(<?php echo $detail['MaCTHD']; ?>)">-</button>
                            <input type="number" 
                                   name="quantities[<?php echo $detail['MaCTHD']; ?>]" 
                                   id="qty-<?php echo $detail['MaCTHD']; ?>" 
                                   class="qty-input" 
                                   value="<?php echo $detail['SoLuong']; ?>" 
                                   min="1"
                                   onchange="updateRowTotal(<?php echo $detail['MaCTHD']; ?>)">
                            <button type="button" class="qty-btn" onclick="increaseQty(<?php echo $detail['MaCTHD']; ?>)">+</button>
                        </div>
                    </td>
                    <td class="text-center">
                        <strong class="row-total text-success" id="row-total-<?php echo $detail['MaCTHD']; ?>">
                            <?php echo number_format($detail['ThanhTien'], 0, ',', '.'); ?>đ
                        </strong>
                    </td>
                    <td class="text-center">
                        <button type="button" 
                                class="btn btn-sm btn-danger" 
                                onclick="removeProduct(<?php echo $detail['MaCTHD']; ?>)"
                                title="Xóa sản phẩm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>TỔNG CỘNG:</strong></td>
                    <td class="text-center">
                        <strong class="text-success" id="footer-total"><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</strong>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Nút lưu -->
<div class="form-actions" style="margin-top: 1rem; text-align: center;">
    <button type="submit" class="btn btn-primary btn-lg" style="min-width: 200px;">
        <i class="fas fa-save"></i> Lưu thay đổi
    </button>
    <a href="index.php?action=orders" class="btn btn-secondary btn-lg" style="min-width: 200px;">
        <i class="fas fa-times"></i> Hủy
    </a>
</div>

<!-- Input ẩn để gửi danh sách xóa -->
<input type="hidden" name="removed_items" id="removed-items" value="">
</form>

<style>
.info-table {
    width: 100%;
}

.info-table tr {
    border-bottom: 1px solid #e1e8ed;
}

.info-table td {
    padding: 0.8rem 0;
}

.info-table td:first-child {
    width: 40%;
    color: #7f8c8d;
}

.total-summary {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e1e8ed;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.2rem;
    font-weight: bold;
}

.total-amount {
    color: #27ae60;
    font-size: 1.5rem;
}

.quantity-control {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.qty-btn {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: #f8f9fa;
    cursor: pointer;
    font-size: 16px;
    border-radius: 4px;
    transition: all 0.2s;
}

.qty-btn:hover {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.qty-input {
    width: 60px;
    height: 30px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-weight: bold;
}

.badge {
    background: #3498db;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
}

.row-removed {
    opacity: 0.4;
    text-decoration: line-through;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
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
    margin-right: 0.8rem;
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 0.2rem;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-text {
    color: #2980b9;
}

.checkbox-text {
    flex: 1;
}
</style>

<script>
// Danh sách các item bị xóa
let removedItems = [];

// Tăng số lượng
function increaseQty(detailId) {
    const input = document.getElementById('qty-' + detailId);
    input.value = parseInt(input.value) + 1;
    updateRowTotal(detailId);
}

// Giảm số lượng
function decreaseQty(detailId) {
    const input = document.getElementById('qty-' + detailId);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateRowTotal(detailId);
    }
}

// Cập nhật tổng tiền từng dòng
function updateRowTotal(detailId) {
    const row = document.getElementById('detail-row-' + detailId);
    const price = parseFloat(row.dataset.price);
    const qty = parseInt(document.getElementById('qty-' + detailId).value);
    const total = price * qty;
    
    document.getElementById('row-total-' + detailId).textContent = 
        new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    
    updateGrandTotal();
}

// Xóa sản phẩm
function removeProduct(detailId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi đơn hàng?')) {
        const row = document.getElementById('detail-row-' + detailId);
        row.classList.add('row-removed');
        
        // Đặt số lượng = 0
        document.getElementById('qty-' + detailId).value = 0;
        
        // Thêm vào danh sách xóa
        removedItems.push(detailId);
        document.getElementById('removed-items').value = removedItems.join(',');
        
        updateGrandTotal();
        updateTotalItems();
    }
}

// Cập nhật tổng cộng
function updateGrandTotal() {
    let grandTotal = 0;
    const rows = document.querySelectorAll('#order-details-table tbody tr');
    
    rows.forEach(row => {
        if (!row.classList.contains('row-removed')) {
            const detailId = row.id.replace('detail-row-', '');
            const price = parseFloat(row.dataset.price);
            const qty = parseInt(document.getElementById('qty-' + detailId).value);
            grandTotal += price * qty;
        }
    });
    
    const formatted = new Intl.NumberFormat('vi-VN').format(grandTotal) + 'đ';
    document.getElementById('footer-total').textContent = formatted;
    document.getElementById('total-amount').textContent = formatted;
}

// Cập nhật tổng số sản phẩm
function updateTotalItems() {
    const rows = document.querySelectorAll('#order-details-table tbody tr');
    let count = 0;
    rows.forEach(row => {
        if (!row.classList.contains('row-removed')) {
            count++;
        }
    });
    document.getElementById('total-items').textContent = count;
}
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

