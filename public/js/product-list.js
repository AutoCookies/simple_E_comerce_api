$(document).ready(function() {
    // Hàm tải danh sách sản phẩm
    function loadProducts() {
        $.ajax({
            url: '/webbanhang/api/product',
            method: 'GET',
            dataType: 'json',
            success: function(products) {
                const productList = $('#product-list');
                productList.empty(); // Xóa các mục cũ trước khi thêm mới

                if (products.length > 0) {
                    products.forEach(product => {
                        const productItem = `
                            <li class="list-group-item">
                                <h2><a href="/webbanhang/Product/show/${product.id}">${product.name}</a></h2>
                                <p>${product.description}</p>
                                <p>Giá: ${product.price} VND</p>
                                <p>Danh mục: ${product.category_name}</p>
                                <a href="/webbanhang/Product/edit/${product.id}" class="btn btn-warning">Sửa</a>
                                <button class="btn btn-danger delete-product-btn" data-id="${product.id}">Xóa</button>
                            </li>
                        `;
                        productList.append(productItem);
                    });
                } else {
                    productList.append('<li class="list-group-item">Không có sản phẩm nào.</li>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải sản phẩm:", status, error);
                $('#product-list').append('<li class="list-group-item text-danger">Không thể tải danh sách sản phẩm.</li>');
            }
        });
    }

    // Tải sản phẩm khi trang được load
    loadProducts();

    // Xử lý sự kiện xóa sản phẩm (sử dụng event delegation)
    $(document).on('click', '.delete-product-btn', function() {
        const productId = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            $.ajax({
                url: `/webbanhang/api/product/${productId}`,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.message === 'Product deleted successfully') {
                        alert('Xóa sản phẩm thành công!');
                        loadProducts(); // Tải lại danh sách sau khi xóa
                    } else {
                        alert('Xóa sản phẩm thất bại: ' + (response.message || 'Lỗi không xác định.'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi xóa sản phẩm:", status, error);
                    alert('Lỗi khi xóa sản phẩm. Vui lòng thử lại.');
                }
            });
        }
    });
});