<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4 text-center">Giỏ hàng của bạn</h1>

    <?php
    // Hiển thị thông báo thành công
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-check-circle mr-2"></i>';
        echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8');
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
        unset($_SESSION['success_message']);
    }

    // Hiển thị thông báo lỗi
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<i class="fas fa-exclamation-circle mr-2"></i>';
        echo htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8');
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <?php if (empty($cart) || !is_array($cart)): ?>
        <div class="alert alert-info text-center py-4" role="alert">
            <h4 class="alert-heading"><i class="fas fa-shopping-basket fa-2x mb-3"></i></h4>
            <p>Giỏ hàng của bạn hiện đang trống.</p>
            <hr>
            <a href="/webbanhang/Product/list" class="btn btn-primary mt-2">
                <i class="fas fa-arrow-circle-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col" class="text-center">Giá</th>
                                <th scope="col" class="text-center">Số lượng</th>
                                <th scope="col" class="text-center">Tổng cộng</th>
                                <th scope="col" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_cart_price = 0; ?>
                            <?php $item_count = 1; ?>
                            <?php foreach ($cart as $product_id => $item): ?>
                                <?php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total_cart_price += $subtotal;
                                ?>
                                <tr>
                                    <th scope="row" class="text-center"><?php echo $item_count++; ?></th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php
                                            // Kiểm tra đường dẫn ảnh và hiển thị
                                            $imagePathForUrl = str_replace('\\', '/', htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'));
                                            $imageUrl = '/webbanhang/' . $imagePathForUrl;
                                            // Bạn có thể thêm kiểm tra file_exists() nếu cần, nhưng thường thì ảnh trong giỏ hàng đã được thêm sau khi kiểm tra.
                                            ?>
                                            <img src="<?php echo $imageUrl; ?>" class="img-thumbnail mr-3" style="width: 80px; height: 80px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <a href="/webbanhang/Product/show/<?php echo $product_id; ?>" class="text-dark text-decoration-none font-weight-bold">
                                                <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                    <td class="text-center">
                                        <form action="/webbanhang/Cart/update" method="POST" class="d-inline-flex">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm text-center" style="width: 70px;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="text-center font-weight-bold text-danger"><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</td>
                                    <td class="text-center">
                                        <a href="/webbanhang/Cart/remove/<?php echo $product_id; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Tổng tiền giỏ hàng:</td>
                                <td class="text-center font-weight-bold text-success" colspan="2">
                                    <h4 class="mb-0"><?php echo number_format($total_cart_price, 0, ',', '.'); ?> VND</h4>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/webbanhang/Product/list" class="btn btn-secondary">
                <i class="fas fa-arrow-circle-left"></i> Tiếp tục mua sắm
            </a>
            <a href="/webbanhang/Cart/checkout" class="btn btn-success btn-lg">
                Tiến hành Thanh toán <i class="fas fa-credit-card ml-2"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>