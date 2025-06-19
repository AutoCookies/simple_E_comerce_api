<?php

require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ?: null; // Trả về toàn bộ thông tin user
            }
        }
        return null;
    }

    // Lấy tất cả danh mục
    public function index()
    {
        header('Content-Type: application/json');
        $categories = $this->categoryModel->getCategories();
        echo json_encode($categories);
    }

    // Lấy danh mục theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
        }
    }

    // Thêm mới danh mục (admin)
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['message' => 'Name is required']);
            return;
        }

        $result = $this->categoryModel->addCategory($name, $description);
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Category created']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create category']);
        }
    }

    // Cập nhật danh mục (admin)
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['message' => 'Name is required']);
            return;
        }

        $result = $this->categoryModel->updateCategory($id, $name, $description);
        if ($result) {
            echo json_encode(['message' => 'Category updated']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update category']);
        }
    }

    // Xoá danh mục (admin)
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            echo json_encode(['message' => 'Category deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete category']);
        }
    }
}
