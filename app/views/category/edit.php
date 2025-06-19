<?php include 'app/views/shares/header.php'; ?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h1 class="mb-4 text-center">Chỉnh sửa danh mục</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (isset($category) && $category): ?>
                <form method="POST" action="/webbanhang/Category/update">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($category->id ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="form-group">
                        <label for="name">Tên danh mục:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category->name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($category->description ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">Cập nhật danh mục</button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning text-center" role="alert">
                    Không tìm thấy danh mục để chỉnh sửa. Vui lòng quay lại danh sách.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="/webbanhang/Category/list" class="btn btn-secondary">Quay lại danh sách danh mục</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>