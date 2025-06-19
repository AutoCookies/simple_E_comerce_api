<?php

class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Phương thức lấy tất cả sản phẩm cùng với tên danh mục (đã bỏ image)
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.name ASC"; 
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Phương thức lấy sản phẩm theo ID cùng với tên danh mục (đã bỏ image)
    public function getProductById($id)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.category_id, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id
                  LIMIT 0,1"; 
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    // Phương thức thêm sản phẩm (đã bỏ image)
    public function addProduct($name, $description, $price, $category_id)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) { 
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (empty($category_id)) {
            $errors['category_id'] = 'Danh mục không được để trống';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id) VALUES (:name, :description, :price, :category_id)";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        // Bind values
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Phương thức cập nhật sản phẩm (đã bỏ image)
    public function updateProduct($id, $name, $description, $price, $category_id)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id WHERE id=:id"; 
        $stmt = $this->conn->prepare($query); 

        // Sanitize data
        $id = htmlspecialchars(strip_tags($id));
        $name = htmlspecialchars(strip_tags($name)); 
        $description = htmlspecialchars(strip_tags($description)); 
        $price = htmlspecialchars(strip_tags($price)); 
        $category_id = htmlspecialchars(strip_tags($category_id)); 

        // Bind values
        $stmt->bindParam(':id', $id); 
        $stmt->bindParam(':name', $name); 
        $stmt->bindParam(':description', $description); 
        $stmt->bindParam(':price', $price); 
        $stmt->bindParam(':category_id', $category_id); 

        if ($stmt->execute()) { 
            return true; 
        } 
        return false; 
    } 

    public function deleteProduct($id) 
    { 
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindParam(':id', $id); 
        if ($stmt->execute()) { 
            return true; 
        } 
        return false; 
    } 
} 
?>