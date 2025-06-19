<?php
// Đảm bảo ROOT_PATH được định nghĩa ở đây hoặc trong một file cấu hình chung (ví dụ: index.php)
// Ví dụ: Nếu file index.php của bạn nằm trong C:\laragon\www\webbanhang\index.php
// thì ROOT_PATH sẽ là C:\laragon\www\webbanhang
// Bạn có thể đặt dòng này ở đầu index.php hoặc app/config/config.php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', 'C:/laragon/www/webbanhang'); // Thay đổi nếu đường dẫn gốc của bạn khác
}
?>

<?php include 'app/views/shares/header.php'; ?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container mt-4">
    <h1 class="mb-4 text-center">Chi tiết sản phẩm</h1>

    <?php if (isset($product) && $product): ?>
        <div class="card shadow-lg mb-4">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <?php
                    // Chuyển dấu gạch chéo ngược thành gạch chéo xuôi cho đường dẫn trên URL
                    $imagePathForUrl = str_replace('\\', '/', htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'));
                    // Tạo đường dẫn tuyệt đối đến file ảnh trên server để kiểm tra sự tồn tại
                    $imagePathOnServer = ROOT_PATH . '/' . $imagePathForUrl;

                    // Kiểm tra sự tồn tại của file ảnh
                    if ($product->image && file_exists($imagePathOnServer)):
                        $imageUrl = '/webbanhang/' . $imagePathForUrl;
                    ?>
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top img-fluid product-detail-img" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php else: ?>
                        <img src="/webbanhang/public/images/placeholder.png" class="card-img-top img-fluid product-detail-img" alt="No Image">
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <h2 class="card-title text-primary"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                        <h4 class="text-danger mb-3">Giá: <?php echo number_format($product->price, 0, ',', '.'); ?> VND</h4>
                        <p class="card-text"><strong>Danh mục:</strong> <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>
                        <hr>
                        <h5>Mô tả sản phẩm:</h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success btn-lg">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </a>
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-md">
                                <i class="fas fa-edit"></i> Sửa sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Không tìm thấy sản phẩm.
        </div>
    <?php endif; ?>

    <div class="text-center mt-3">
        <a href="/webbanhang/Product/list" class="btn btn-secondary">
            <i class="fas fa-arrow-circle-left"></i> Quay lại danh sách sản phẩm
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    .product-detail-img {
        width: 100%;
        height: 400px; /* Chiều cao cố định cho ảnh chi tiết */
        object-fit: cover; /* Đảm bảo ảnh được căn chỉnh và không bị méo mó */
        border-radius: 0.25rem; /* Bo góc ảnh */
    }
    .card-body {
        padding: 2rem;
    }
</style>