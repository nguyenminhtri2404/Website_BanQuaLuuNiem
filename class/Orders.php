<?php
class Orders
{
    //thêm đơn hàng
    public static function addOrder($pdo, $user_id, $email, $phone, $address, $order_note, $method)
    {
        $sql = "INSERT INTO order_bill (user_id, email, phone, address, order_note, order_date, method,status) VALUES (:user_id, :email, :phone, :address, :order_note, now(), :method, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
        $stmt->bindParam(":order_note", $order_note, PDO::PARAM_STR);
        $stmt->bindParam(":method", $method, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //lấy tất cả đơn hàng
    public static function getAll($pdo)
    {
        $sql = "SELECT * FROM order_bill";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //phân trang
    public static function pagination($pdo, $limit, $offset)
    {
        $sql = "SELECT order_bill.*, user.name
        FROM order_bill
        JOIN user ON order_bill.user_id = user.id
        ORDER BY order_id DESC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //đếm số lượng đơn hàng
    public static function countOrder($pdo)
    {
        $sql = "SELECT COUNT(order_id) FROM order_bill";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //tổng tiền đơn hàng của tất cả đơn hàng
    public static function sumTotal($pdo)
    {
        $sql = "SELECT SUM(total_price) FROM order_bill_detail";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //tổng số lượng đơn hàng theo tháng
    public static function countOrderByMonth($pdo, $month)
    {
        $sql = "SELECT COUNT(order_id) FROM order_bill WHERE MONTH(order_date) = :month";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //lấy đơn hàng theo id
    public static function getOrder($pdo, $order_id)
    {
        $sql = "SELECT * FROM order_bill WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //lấy đơn hàng theo user_id
    public static function getOrderByUser($pdo, $user_id)
    {
        $sql = "SELECT * FROM order_bill WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //xóa đơn hàng
    public static function deleteOrder($pdo, $order_id)
    {
        $sql = "DELETE FROM order_bill WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //thêm chi tiết đơn hàng
    public static function addOrderDetail($pdo, $order_id, $product_id, $quantity, $price, $total_price)
    {
        $sql = "INSERT INTO order_bill_detail (order_id, product_id, quantity, price, total_price) VALUES (:order_id, :product_id, :quantity, :price, :total_price)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $stmt->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":price", $price, PDO::PARAM_INT);
        $stmt->bindParam(":total_price", $total_price, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //update trạng thái đơn hàng
    public static function updateStatus($pdo, $order_id, $product_id, $status)
    {
        $sql = "UPDATE order_bill SET status = :status WHERE order_id = :order_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $product_id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $status, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //lấy id cuối cùng
    public static function getLastID($pdo)
    {
        $sql = "SELECT order_id FROM order_bill ORDER BY order_id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //lấy thông tin đơn hàng và chi tiết đơn hàng theo user_id
    public static function getOrderDetail($pdo, $user_id)
    {
        $sql = "SELECT order_bill.*, order_bill_detail.*, product.pro_name, product.image 
        FROM order_bill 
        JOIN order_bill_detail ON order_bill.order_id = order_bill_detail.order_id 
        JOIN product ON order_bill_detail.product_id = product.id 
        WHERE order_bill.user_id =:user_id ORDER BY order_bill.order_date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }

    //lấy thông tin đơn hàng và chi tiết đơn hàng theo order_id
    public static function getOrderDetailById($pdo, $order_id)
    {
        $sql = "SELECT order_bill.*, order_bill_detail.*, product.pro_name, product.image, user.name, user.email, user.phone, user.address
        FROM order_bill
        JOIN order_bill_detail ON order_bill.order_id = order_bill_detail.order_id
        JOIN product ON order_bill_detail.product_id = product.id
        JOIN user ON order_bill.user_id = user.id
        WHERE order_bill.order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":order_id", $order_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } 
        
    }

    //lấy tất cả đơn hàng và chi tiết đơn hàng
    public static function getAllOrderDetail($pdo)
    {
        $sql = "SELECT order_bill.*, user.name
        FROM order_bill
        JOIN user ON order_bill.user_id = user.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //lấy tất cả đơn hàng và chi tiết đơn hàng những đơn hàng chưa xác nhận
    public static function getAllOrderDetailNotConfirm($pdo)
    {
        $sql = "SELECT order_bill.*, user.name
            FROM order_bill
            JOIN user ON order_bill.user_id = user.id
            WHERE order_bill.status = 0
            ORDER BY order_bill.order_date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
