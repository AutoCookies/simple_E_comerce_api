<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card text-center shadow-lg border-success">
                <div class="card-header bg-success text-white py-4">
                    <h2 class="mb-0"><i class="fas fa-check-circle fa-3x"></i></h2>
                    <h2 class="mb-0 mt-2">Đơn hàng của bạn đã được đặt thành công!</h2>
                </div>
                <div class="card-body p-4">
                    <p class="lead">Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng của chúng tôi.</p>
                    <p>Đơn hàng của bạn đã được ghi nhận và sẽ được xử lý trong thời gian sớm nhất. Chúng tôi sẽ liên hệ với bạn qua số điện thoại đã cung cấp để xác nhận chi tiết đơn hàng và thời gian giao hàng.</p>
                    
                    <hr class="my-4">
                    
                    <h5 class="text-info mb-3"><i class="fas fa-truck mr-2"></i> Thông tin giao hàng dự kiến</h5>
                    <p>Đơn hàng của bạn dự kiến sẽ được giao trong vòng 2-3 ngày làm việc.</p>
                    <p class="text-muted small">Mọi thắc mắc vui lòng liên hệ bộ phận chăm sóc khách hàng của chúng tôi.</p>

                    <a href="/webbanhang/Product/list" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-shopping-basket"></i> Tiếp tục mua sắm
                    </a>
                </div>
                <div class="card-footer text-muted py-3">
                    Chúng tôi mong được phục vụ bạn lần nữa!
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>