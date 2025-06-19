$(document).ready(function() {
    // Biến editProductId được truyền từ PHP trong tệp HTML
    if (typeof editProductId === 'undefined') {
        alert('Không tìm thấy ID sản phẩm để chỉnh sửa.');
        window.location.href = '/webbanhang/Product/list';
        return;
    }

    let currentProductData = null; // Biến để lưu trữ dữ liệu sản phẩm hiện tại

    // Hàm tải chi tiết sản phẩm
    function loadProductDetails(productId) {
        $.ajax({
            url: `/webbanhang/api/product/${productId}`,
            method: 'GET',
            dataType: 'json',
            success: function(product) {
                if (product) {
                    currentProductData = product; // Lưu dữ liệu sản phẩm
                    $('#id').val(product.id);
                    $('#name').val(product.name);
                    $('#description').val(product.description);
                    $('#price').val(product.price);
                    // category_id sẽ được chọn sau khi danh mục được tải
                } else {
                    alert('Không tìm thấy sản phẩm với ID này.');
                    window.location.href = '/webbanhang/Product/list';
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải thông tin sản phẩm:", status, error);
                alert('Không thể tải thông tin sản phẩm. Vui lòng kiểm tra lại ID hoặc kết nối mạng.');
                window.location.href = '/webbanhang/Product/list';
            }
        });
    }

    // Hàm tải danh mục và chọn danh mục hiện tại của sản phẩm
    function loadCategoriesAndSetSelected() {
        $.ajax({
            url: '/webbanhang/api/category',
            method: 'GET',
            dataType: 'json',
            success: function(categories) {
                const categorySelect = $('#category_id');
                categorySelect.empty();
                if (categories.length > 0) {
                    categories.forEach(category => {
                        const option = `<option value="${category.id}">${category.name}</option>`;
                        categorySelect.append(option);
                    });

                    // Sau khi danh mục được tải, chọn danh mục hiện tại của sản phẩm
                    if (currentProductData && currentProductData.category_id) {
                        categorySelect.val(currentProductData.category_id);
                    }
                } else {
                    categorySelect.append('<option value="">Không có danh mục nào</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh mục:", status, error);
                alert('Không thể tải danh mục sản phẩm. Vui lòng thử lại.');
            }
        });
    }

    // Tải dữ liệu khi trang load
    loadProductDetails(editProductId);
    loadCategoriesAndSetSelected(); // Tải danh mục ngay

    // Xử lý sự kiện submit form sửa sản phẩm
    $('#edit-product-form').submit(function(event) {
        event.preventDefault();

        const productIdToUpdate = $('#id').val();
        const formData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val()
        };

        $.ajax({
            url: `/webbanhang/api/product/${productIdToUpdate}`,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(response) {
                if (response.message === 'Product updated successfully') {
                    alert('Cập nhật sản phẩm thành công!');
                    window.location.href = '/webbanhang/Product/list';
                } else if (response.errors) {
                    let errorMessages = 'Vui lòng kiểm tra lại thông tin:\n';
                    for (const key in response.errors) {
                        errorMessages += `- ${response.errors[key]}\n`;
                    }
                    alert(errorMessages);
                } else {
                    alert('Cập nhật sản phẩm thất bại: ' + (response.message || 'Lỗi không xác định.'));
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi cập nhật sản phẩm:", status, error);
                let errorMessage = 'Có lỗi xảy ra trong quá trình cập nhật sản phẩm.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += '\n' + xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
});
