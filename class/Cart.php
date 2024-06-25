<?php
class Cart
{
    //thêm sản phẩm vào giỏ hàng
    public static function addCart($pdo, $user_id, $pro_id, $quantity, $total_cart)
    {
        //kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $sql = "SELECT * FROM cart WHERE user_id=:user_id AND product_id=:pro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cart) {
                //nếu có rồi thì cập nhật số lượng và tổng tiền
                $sql = "UPDATE cart SET quantity=quantity+:quantity, total_cart=total_cart+:total_cart WHERE user_id=:user_id AND product_id=:pro_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                $stmt->bindParam(":total_cart", $total_cart, PDO::PARAM_INT);
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
                if($stmt->execute()){
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
            } else {
                //nếu chưa có thì thêm mới
                $sql = "INSERT INTO cart(user_id, product_id, quantity, total_cart, created_at) VALUES(:user_id, :pro_id, :quantity, :total_cart, now())";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
                $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                $stmt->bindParam(":total_cart", $total_cart, PDO::PARAM_INT);
                if($stmt->execute()){
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
            
        }
        
    }

    //update giỏ hàng
    public static function updateCart($pdo, $user_id, $pro_id, $quantity, $total_cart)
    {
        $sql = "UPDATE cart SET quantity=:quantity, total_cart=:total_cart WHERE user_id=:user_id AND product_id=:pro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":total_cart", $total_cart, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //lấy thông tin giỏ hàng
    public static function getCart($pdo, $user_id)
    {
        $sql = "SELECT product.id, product.pro_name, product.price, product.image, cart.quantity, cart.total_cart, user_id FROM cart JOIN product ON cart.product_id=product.id WHERE user_id = :user_id ORDER BY cart.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //xóa sản phẩm khỏi giỏ hàng
    public static function deleteCart($pdo, $user_id, $pro_id)
    {
        $sql = "DELETE FROM cart WHERE user_id=:user_id AND product_id=:pro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //xóa toàn bộ giỏ hàng
    public static function emptyCart($pdo, $user_id)
    {
        $sql = "DELETE FROM cart WHERE user_id=:user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //đếm số lượng sản phẩm trong giỏ hàng
    public static function countCart($pdo, $user_id)
    {
        $sql = "SELECT COUNT(DISTINCT product_id) FROM cart WHERE user_id=:user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
