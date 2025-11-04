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
                    <th class="text-center">Mã SP</th>
                    <th class="text-center">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th class="text-center">Tồn kho</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="text-center"><strong>#<?php echo $product['MaSP']; ?></strong></td>
                    <td>
                        <?php if (!empty($product['HinhAnh'])): ?>
                            <?php 
                            $imageSrc = (preg_match('/^https?:\\/\\//', $product['HinhAnh'])) 
                                ? $product['HinhAnh'] 
                                : '../public/uploads/products/' . $product['HinhAnh'];
                            ?>
                            <img src="<?php echo htmlspecialchars($imageSrc); ?>" 
                                 alt="<?php echo htmlspecialchars($product['TenSP']); ?>"
                                 class="product-img">
                        <?php else: ?>
                            <div class="product-img-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong class="product-name" title="<?php echo htmlspecialchars($product['TenSP']); ?>">
                            <?php echo htmlspecialchars($product['TenSP']); ?>
                        </strong>
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
                    <td class="text-center">
                        <?php if ($product['TongTonKho'] < 10): ?>
                            <span class="badge badge-warning"><?php echo $product['TongTonKho']; ?></span>
                        <?php else: ?>
                            <span class="badge badge-success"><?php echo $product['TongTonKho']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
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

