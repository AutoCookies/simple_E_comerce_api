<?php

class CategoryModel
{
    private $conn;
    private $table_name = "category"; // Đảm bảo tên bảng này khớp với tên trong cơ sở dữ liệu của bạn

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức đã có
    public function getCategories()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " ORDER BY name ASC"; // Thêm ORDER BY để sắp xếp
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // --- PHƯƠNG THỨC BỔ SUNG: Lấy danh mục theo ID ---
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT); // Ràng buộc ID là kiểu số nguyên
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row;
    }

    // --- PHƯƠNG THỨC BỔ SUNG: Thêm danh mục mới ---
    public function addCategory($name, $description)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dữ liệu trước khi binding để tránh XSS
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $description);

        try {
            return $stmt->execute(); // Trả về true nếu thành công, false nếu thất bại
        } catch (PDOException $e) {
            // Ghi log lỗi để dễ dàng gỡ lỗi trong môi trường phát triển
            error_log("Error adding category: " . $e->getMessage());
            return false;
        }
    }

    // --- PHƯƠNG THỨC BỔ SUNG: Cập nhật danh mục ---
    public function updateCategory($id, $name, $description)
    {
        $query = "UPDATE " . $this->table_name . " SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitize dữ liệu trước khi binding
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $id, PDO::PARAM_INT); // Ràng buộc ID là kiểu số nguyên

        try {
            return $stmt->execute(); // Trả về true nếu thành công, false nếu thất bại
        } catch (PDOException $e) {
            error_log("Error updating category: " . $e->getMessage());
            return false;
        }
    }

    // --- PHƯƠNG THỨC BỔ SUNG: Xóa danh mục ---
    public function deleteCategory($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT); // Ràng buộc ID là kiểu số nguyên

        try {
            return $stmt->execute(); // Trả về true nếu thành công, false nếu thất bại
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            return false;
        }
    }
}