<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-box"></i> Quản lý sản phẩm</h2>
    <a href="index.php?action=addProduct" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        <?php if (!empty($products)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td>#<?php echo $product['MaSP']; ?></td>
                    <td>
                        <?php if (!empty($product['HinhAnh'])): ?>
                            <img src="../public/uploads/products/<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['TenSP']); ?>"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #ccc; font-size: 20px;"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($product['TenSP']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($product['TenDM']); ?></td>
                    <td><?php echo htmlspecialchars($product['TenTH']); ?></td>
                    <td>
                        <?php if ($product['GiaThapNhat'] == $product['GiaCaoNhat']): ?>
                            <span class="text-success"><?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>đ</span>
                        <?php else: ?>
                            <span class="text-success">
                                <?php echo number_format($product['GiaThapNhat'], 0, ',', '.'); ?>đ - 
                                <?php echo number_format($product['GiaCaoNhat'], 0, ',', '.'); ?>đ
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($product['TongTonKho'] < 10): ?>
                            <span class="badge badge-warning"><?php echo $product['TongTonKho']; ?></span>
                        <?php else: ?>
                            <span class="badge badge-success"><?php echo $product['TongTonKho']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="../public/index.php?controller=product&action=detail&id=<?php echo $product['MaSP']; ?>" 
                           class="btn btn-sm btn-info" 
                           title="Xem"
                           target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="index.php?action=editProduct&id=<?php echo $product['MaSP']; ?>" 
                           class="btn btn-sm btn-primary" 
                           title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="index.php?action=products&delete=<?php echo $product['MaSP']; ?>" 
                           class="btn btn-sm btn-danger" 
                           title="Xóa"
                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open fa-5x"></i>
            <h3>Chưa có sản phẩm nào</h3>
            <p>Hãy thêm sản phẩm mới để bắt đầu</p>
            <a href="index.php?action=addProduct" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

