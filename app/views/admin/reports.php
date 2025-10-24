<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-file-alt"></i> Báo cáo doanh số</h2>
</div>

<div class="admin-grid">
    <!-- Doanh thu theo tháng -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> Doanh thu theo tháng (<?php echo date('Y'); ?>)</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($monthlyRevenue)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Số đơn</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyRevenue as $month): ?>
                    <tr>
                        <td><strong>Tháng <?php echo $month['thang']; ?>/<?php echo $month['nam']; ?></strong></td>
                        <td><?php echo number_format($month['so_don']); ?> đơn</td>
                        <td class="text-success">
                            <strong><?php echo number_format($month['doanh_thu'], 0, ',', '.'); ?>đ</strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td><strong>TỔNG CỘNG</strong></td>
                        <td><strong><?php echo number_format(array_sum(array_column($monthlyRevenue, 'so_don'))); ?> đơn</strong></td>
                        <td class="text-success">
                            <strong><?php echo number_format(array_sum(array_column($monthlyRevenue, 'doanh_thu')), 0, ',', '.'); ?>đ</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-center text-muted">Chưa có dữ liệu doanh thu</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sản phẩm bán chạy -->
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-fire"></i> Top 10 sản phẩm bán chạy</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($topProducts)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đã bán</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topProducts as $index => $product): ?>
                    <tr>
                        <td>
                            <span class="rank">#<?php echo $index + 1; ?></span>
                            <?php echo htmlspecialchars($product['TenSP']); ?>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                <?php echo number_format($product['tong_ban']); ?> sp
                            </span>
                        </td>
                        <td class="text-success">
                            <strong><?php echo number_format($product['doanh_thu'], 0, ',', '.'); ?>đ</strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-center text-muted">Chưa có dữ liệu bán hàng</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>




