<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="dashboard">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-box fa-3x"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['total_products']); ?></h3>
                <p>Tổng sản phẩm</p>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart fa-3x"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['total_orders']); ?></h3>
                <p>Đơn hàng</p>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-users fa-3x"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['total_customers']); ?></h3>
                <p>Khách hàng</p>
            </div>
        </div>
        
        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign fa-3x"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?>đ</h3>
                <p>Doanh thu</p>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders & Low Stock -->
    <div class="dashboard-grid">
        <!-- Recent Orders -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-shopping-cart"></i> Đơn hàng gần đây</h3>
                <a href="index.php?action=orders" class="btn btn-sm">Xem tất cả</a>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['recent_orders'])): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Ngày</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['recent_orders'] as $order): ?>
                        <tr>
                            <td>#<?php echo $order['MaHD']; ?></td>
                            <td><?php echo htmlspecialchars($order['TenKH']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($order['NgayLap'])); ?></td>
                            <td class="text-success">
                                <strong><?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</strong>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center text-muted">Chưa có đơn hàng nào</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Sản phẩm sắp hết</h3>
                <a href="index.php?action=products" class="btn btn-sm">Quản lý</a>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['low_stock'])): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Tồn kho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['low_stock'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['TenSP']); ?></td>
                            <td>
                                <small>Size <?php echo $item['KichThuoc']; ?> - <?php echo $item['MauSac']; ?></small>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    <?php echo $item['TonKho']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center text-muted">Tất cả sản phẩm đều đủ hàng</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

