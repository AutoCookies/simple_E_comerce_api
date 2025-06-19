<?php

// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

// Bắt đầu session nếu chưa được bắt đầu (cho thông báo flash messages)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // --- HIỂN THỊ DANH SÁCH DANH MỤC ---
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // --- HIỂN THỊ FORM THÊM DANH MỤC ---
    public function add()
    {
        $errors = []; // Khởi tạo mảng lỗi trống
        // Tạo một đối tượng category rỗng để tránh lỗi 'Undefined variable' trong view
        $category = (object)['name' => '', 'description' => ''];
        include 'app/views/category/add.php';
    }

    // --- LƯU DANH MỤC MỚI (Xử lý POST từ form add) ---
    public function store() // Đổi tên từ save() sang store() cho rõ ràng hơn
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Category/add'); // Đã sửa đường dẫn
            exit;
        }

        $errors = []; // Khởi tạo mảng lỗi
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Gán lại dữ liệu vào object để hiển thị lại trên form nếu có lỗi
        $category = (object)[
            'name' => $name,
            'description' => $description
        ];

        // 1. Validate dữ liệu đầu vào
        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống.";
        }
        if (empty($description)) {
            $errors[] = "Mô tả danh mục không được để trống.";
        }

        // Nếu có lỗi, hiển thị lại form với thông báo lỗi và dữ liệu cũ
        if (!empty($errors)) {
            include 'app/views/category/add.php';
            return; // Dừng thực thi controller
        }

        // 2. Gọi model để thêm danh mục
        if ($this->categoryModel->addCategory($name, $description)) {
            // Sử dụng session cho flash message
            $_SESSION['success_message'] = "Thêm danh mục thành công!";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        } else {
            $errors[] = "Đã xảy ra lỗi khi lưu danh mục vào cơ sở dữ liệu.";
            include 'app/views/category/add.php';
        }
    }

    // --- HIỂN THỊ FORM SỬA DANH MỤC ---
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        $errors = []; // Khởi tạo mảng lỗi

        if (!$category) {
            $_SESSION['error_message'] = "Không tìm thấy danh mục để chỉnh sửa.";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        }

        include 'app/views/category/edit.php';
    }

    // --- CẬP NHẬT DANH MỤC (Xử lý POST từ form edit) ---
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        }

        $errors = [];
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Lấy lại thông tin danh mục ban đầu để hiển thị lại trên form nếu có lỗi
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $_SESSION['error_message'] = "Danh mục không tồn tại để cập nhật.";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        }

        // Cập nhật các trường từ POST (để hiển thị lại nếu có lỗi)
        $category->name = $name;
        $category->description = $description;

        // 1. Validate dữ liệu đầu vào
        if ($id === false || $id <= 0) {
            $errors[] = "ID danh mục không hợp lệ.";
        }
        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống.";
        }
        if (empty($description)) {
            $errors[] = "Mô tả danh mục không được để trống.";
        }

        // Nếu có lỗi, hiển thị lại form với thông báo lỗi và dữ liệu cũ
        if (!empty($errors)) {
            include 'app/views/category/edit.php';
            return;
        }

        // 2. Gọi model để cập nhật danh mục
        if ($this->categoryModel->updateCategory($id, $name, $description)) {
            $_SESSION['success_message'] = "Cập nhật danh mục thành công!";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        } else {
            $errors[] = "Đã xảy ra lỗi khi cập nhật danh mục vào cơ sở dữ liệu.";
            include 'app/views/category/edit.php';
        }
    }

    // --- XÓA DANH MỤC ---
    public function delete($id)
    {
        // Trong môi trường thực tế, bạn nên xác nhận trước khi xóa (vd: dùng POST request)
        // Hiện tại, nó chỉ đơn giản là xóa dựa trên ID
        if ($this->categoryModel->deleteCategory($id)) {
            $_SESSION['success_message'] = "Xóa danh mục thành công!";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        } else {
            $_SESSION['error_message'] = "Đã xảy ra lỗi khi xóa danh mục.";
            header('Location: /webbanhang/Category/list'); // Đã sửa đường dẫn
            exit;
        }
    }
}