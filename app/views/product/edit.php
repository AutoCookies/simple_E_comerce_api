<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa sản phẩm</h1>

<form id="edit-product-form">
    <input type="hidden" id="id" name="id">
    
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    
    <div class="form-group">
        <label for="price">Giá:</label> 
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>
    
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<a href="/webbanhang/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<?php include 'app/views/shares/footer.php'; ?>

<script>
    // Truyền productId từ PHP sang JavaScript
    const editProductId = <?= $editId ?>;
</script>
<script src="/webbanhang/public/js/product-edit.js"></script>