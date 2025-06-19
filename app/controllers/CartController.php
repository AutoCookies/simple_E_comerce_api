<?php
// app/controllers/CartController.php

// Đảm bảo các Model và Database được yêu cầu
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
// Nếu bạn có SessionHelper hoặc Base Controller chứa session_start(), bạn có thể xóa dòng này
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CartController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        // Khởi tạo kết nối DB và ProductModel
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     * @param int $id ID của sản phẩm cần thêm.
     */
    public function add($id)
    {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $_SESSION['error_message'] = "Sản phẩm bạn muốn thêm không tồn tại.";
            header('Location: /webbanhang/Product/list');
            exit();
        }

        // Khởi tạo giỏ hàng nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
            $_SESSION['success_message'] = "Đã cập nhật số lượng sản phẩm '{$product->name}' trong giỏ hàng.";
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $_SESSION['cart'][$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image, // Lưu đường dẫn ảnh để hiển thị trong giỏ hàng
                'quantity' => 1
            ];
            $_SESSION['success_message'] = "Đã thêm sản phẩm '{$product->name}' vào giỏ hàng.";
        }

        // Chuyển hướng người dùng về trang danh sách sản phẩm hoặc trang giỏ hàng
        header('Location: /webbanhang/Product/list');
        exit();
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     * Phương thức này nhận dữ liệu qua POST từ form trong view giỏ hàng.
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            // Kiểm tra tính hợp lệ của dữ liệu đầu vào
            if ($id && is_numeric($id) && $quantity !== null && is_numeric($quantity) && $quantity >= 0) {
                $id = (int)$id;
                $quantity = (int)$quantity;

                if (isset($_SESSION['cart'][$id])) {
                    if ($quantity === 0) {
                        // Nếu số lượng là 0, xóa sản phẩm khỏi giỏ hàng
                        unset($_SESSION['cart'][$id]);
                        $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
                    } else {
                        // Cập nhật số lượng sản phẩm
                        $_SESSION['cart'][$id]['quantity'] = $quantity;
                        $_SESSION['success_message'] = "Đã cập nhật số lượng sản phẩm trong giỏ hàng.";
                    }
                } else {
                    $_SESSION['error_message'] = "Sản phẩm này không có trong giỏ hàng của bạn.";
                }
            } else {
                $_SESSION['error_message'] = "Dữ liệu cập nhật không hợp lệ.";
            }
        } else {
            $_SESSION['error_message'] = "Yêu cầu không hợp lệ.";
        }

        header('Location: /webbanhang/Cart/view');
        exit();
    }

    /**
     * Xóa một sản phẩm cụ thể khỏi giỏ hàng.
     * @param int $id ID của sản phẩm cần xóa.
     */
    public function remove($id)
    {
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
        } else {
            $_SESSION['error_message'] = "Sản phẩm bạn muốn xóa không có trong giỏ hàng.";
        }

        header('Location: /webbanhang/Cart/view');
        exit();
    }

    /**
     * Hiển thị trang giỏ hàng.
     */
    public function view()
    {
        // Lấy dữ liệu giỏ hàng từ session
        $cart = $_SESSION['cart'] ?? [];
        // Đường dẫn tới view giỏ hàng.
        // Bạn đã chỉ ra là app/views/product/cart.php
        include 'app/views/product/cart.php';
    }

    /**
     * Hiển thị trang thanh toán.
     * Kiểm tra giỏ hàng trước khi cho phép thanh toán.
     */
    public function checkout()
    {
        // Lấy dữ liệu giỏ hàng để kiểm tra
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['error_message'] = "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm để thanh toán.";
            header('Location: /webbanhang/Product/list');
            exit();
        }
        // Đường dẫn tới view thanh toán.
        // Bạn đã chỉ ra là app/views/product/checkout.php
        include 'app/views/product/checkout.php';
    }

    /**
     * Xử lý quá trình đặt hàng và lưu vào cơ sở dữ liệu.
     * Phương thức này nhận dữ liệu qua POST từ form thanh toán.
     */
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            // Kiểm tra giỏ hàng và dữ liệu form
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                $_SESSION['error_message'] = "Giỏ hàng trống, không thể thanh toán.";
                header('Location: /webbanhang/Product/list'); // Hoặc quay lại trang giỏ hàng
                exit();
            }

            // Basic validation cho thông tin khách hàng
            if (empty($name) || empty($phone) || empty($address)) {
                $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin giao hàng.";
                header('Location: /webbanhang/Cart/checkout');
                exit();
            }

            // Bắt đầu một giao dịch cơ sở dữ liệu
            $this->db->beginTransaction();

            try {
                // 1. Lưu thông tin đơn hàng vào bảng `orders`
                // Đảm bảo tên cột trong bảng 'orders' khớp với truy vấn này
                $query = "INSERT INTO orders (name, phone, address, created_at) VALUES (:name, :phone, :address, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId(); // Lấy ID của đơn hàng vừa tạo

                // 2. Lưu chi tiết đơn hàng vào bảng `order_details`
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                // Nếu mọi thứ thành công, xóa giỏ hàng và commit giao dịch
                unset($_SESSION['cart']);
                $this->db->commit();

                $_SESSION['success_message'] = "Đơn hàng của bạn đã được đặt thành công! Chúng tôi sẽ liên hệ để xác nhận.";
                header('Location: /webbanhang/Cart/orderConfirmation');
                exit();
            } catch (PDOException $e) {
                // Nếu có lỗi, rollback giao dịch và hiển thị thông báo lỗi
                $this->db->rollBack();
                $_SESSION['error_message'] = "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
                header('Location: /webbanhang/Cart/checkout'); // Quay lại trang thanh toán
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Phương thức yêu cầu không hợp lệ để xử lý thanh toán.";
            header('Location: /webbanhang/Cart/checkout');
            exit();
        }
    }

    /**
     * Hiển thị trang xác nhận đơn hàng sau khi thanh toán thành công.
     */
    public function orderConfirmation()
    {
        // Đường dẫn tới view xác nhận đơn hàng.
        // Bạn đã chỉ ra là app/views/product/orderConfirmation.php
        include 'app/views/product/orderConfirmation.php';
    }
}