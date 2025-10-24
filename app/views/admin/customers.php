<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-users"></i> Quản lý khách hàng</h2>
</div>

<div class="admin-card">
    <div class="card-body">
        <?php if (!empty($customers)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã KH</th>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Hạng TV</th>
                    <th>Điểm tích lũy</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><strong>#<?php echo $customer['MaKH']; ?></strong></td>
                    <td><?php echo htmlspecialchars($customer['HoTen']); ?></td>
                    <td><?php echo htmlspecialchars($customer['SoDienThoai']); ?></td>
                    <td><?php echo htmlspecialchars($customer['Email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['DiaChi']); ?></td>
                    <td>
                        <?php 
                        $badgeClass = 'badge-info';
                        switch($customer['HangThanhVien']) {
                            case 'KimCuong': $badgeClass = 'badge-primary'; break;
                            case 'Vang': $badgeClass = 'badge-warning'; break;
                            case 'Bac': $badgeClass = 'badge-secondary'; break;
                        }
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo $customer['HangThanhVien']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-success">
                            <?php echo number_format($customer['DiemTichLuy']); ?> điểm
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users fa-5x"></i>
            <h3>Chưa có khách hàng nào</h3>
            <p>Thông tin khách hàng sẽ xuất hiện ở đây</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>




