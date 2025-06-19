$(document).ready(function () {
    // Tải danh mục từ API và điền vào dropdown
    $.ajax({
        url: '/webbanhang/api/category',
        method: 'GET',
        dataType: 'json',
        success: function (categories) {
            const categorySelect = $('#category_id');
            categorySelect.empty(); // Xóa các tùy chọn cũ

            if (categories.length > 0) {
                categorySelect.append('<option value="">-- Chọn danh mục --</option>'); // Tùy chọn mặc định
                categories.forEach(category => {
                    const option = `<option value="${category.id}">${category.name}</option>`;
                    categorySelect.append(option);
                });
            } else {
                categorySelect.append('<option value="">Không có danh mục nào</option>');
            }
        },
        error: function (xhr, status, error) {
            console.error("Lỗi khi tải danh mục:", status, error);
            $('#category_id').append('<option value="">Lỗi khi tải danh mục</option>');
            alert('Không thể tải danh mục. Vui lòng thử lại sau.');
        }
    });

    // Xử lý sự kiện submit form thêm sản phẩm
    $('#add-product-form').submit(function (event) {
        event.preventDefault(); // Ngăn chặn hành vi submit mặc định của form

        const formData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val()
        };

        $.ajax({
            url: '/webbanhang/productapi/store',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('jwtToken')
            },
            success: function (response) {
                if (response.message === 'Product created successfully') {
                    alert('Thêm sản phẩm thành công!');
                    window.location.href = '/webbanhang/Product/list'; // Chuyển hướng
                } else if (response.errors) {
                    let errorMessages = 'Vui lòng kiểm tra lại thông tin:\n';
                    for (const key in response.errors) {
                        errorMessages += `- ${response.errors[key]}\n`;
                    }
                    alert(errorMessages);
                } else {
                    alert('Thêm sản phẩm thất bại: ' + (response.message || 'Lỗi không xác định.'));
                }
            },
            error: function (xhr, status, error) {
                console.error("Lỗi khi thêm sản phẩm:", status, error);
                let errorMessage = 'Có lỗi xảy ra trong quá trình thêm sản phẩm.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += '\n' + xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
});