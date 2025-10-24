<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-shopping-cart"></i> Quản lý đơn hàng</h2>
</div>

<div class="admin-card">
    <div class="card-body">
        <?php if (!empty($orders)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Ngày lập</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Nhân viên</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong>#<?php echo $order['MaHD']; ?></strong></td>
                    <td><?php echo htmlspecialchars($order['TenKH']); ?></td>
                    <td><?php echo htmlspecialchars($order['SoDienThoai']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($order['NgayLap'])); ?></td>
                    <td class="text-success">
                        <strong><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</strong>
                    </td>
                    <td>
                        <span class="badge badge-info">
                            <?php echo htmlspecialchars($order['HinhThucThanhToan']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($order['TenNV']); ?></td>
                    <td><small><?php echo htmlspecialchars($order['GhiChu']); ?></small></td>
                    <td class="actions">
                        <a href="index.php?action=editOrder&id=<?php echo $order['MaHD']; ?>" 
                           class="btn btn-sm btn-primary" 
                           title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-shopping-cart fa-5x"></i>
            <h3>Chưa có đơn hàng nào</h3>
            <p>Đơn hàng sẽ xuất hiện ở đây khi khách hàng đặt mua</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>



