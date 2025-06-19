<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4 text-center">Thông tin Thanh toán</h1>

    <?php
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
        <div class="alert alert-warning text-center py-4" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i></h4>
            <p>Giỏ hàng của bạn đang trống. Không thể tiến hành thanh toán.</p>
            <hr>
            <a href="/webbanhang/Product/list" class="btn btn-primary mt-2">
                <i class="fas fa-arrow-circle-left"></i> Quay lại mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="fas fa-shipping-fast mr-2"></i> Thông tin giao hàng</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="/webbanhang/Cart/processCheckout" method="POST">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-user"></i> Tên của bạn:</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Nhập họ và tên">
                            </div>
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone-alt"></i> Số điện thoại:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Nhập số điện thoại" pattern="[0-9]{10,11}">
                                <small class="form-text text-muted">Ví dụ: 0912345678</small>
                            </div>
                            <div class="form-group">
                                <label for="address"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng:</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"></textarea>
                            </div>

                            <hr>
                            <h5 class="mb-3 text-center text-secondary"><i class="fas fa-info-circle mr-2"></i> Tóm tắt đơn hàng</h5>
                            <ul class="list-group mb-3">
                                <?php $total_price = 0; ?>
                                <?php foreach ($cart as $item_id => $item): ?>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                            <small class="text-muted"><?php echo number_format($item['price'], 0, ',', '.'); ?> VND x <?php echo $item['quantity']; ?></small>
                                        </div>
                                        <span class="text-muted"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</span>
                                    </li>
                                    <?php $total_price += $item['price'] * $item['quantity']; ?>
                                <?php endforeach; ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong class="text-primary">Tổng cộng (Tạm tính)</strong>
                                    <strong class="text-danger"><?php echo number_format($total_price, 0, ',', '.'); ?> VND</strong>
                                </li>
                            </ul>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="/webbanhang/Cart/view" class="btn btn-secondary">
                                    <i class="fas fa-arrow-circle-left"></i> Quay lại giỏ hàng
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane"></i> Xác nhận Đặt hàng
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>