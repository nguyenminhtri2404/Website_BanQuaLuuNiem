<?php
class User {

    //dem so luong user
    public static function countUser($pdo)
    {
        $sql = "SELECT COUNT(id) FROM user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function getAll($pdo)
    {
        $sql = "SELECT * FROM user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //phan trang
    public static function pagination($pdo, $limit, $offset)
    {
        $sql = "SELECT * FROM user ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOneUserById($pdo, $user_id)
    {
        $sql = "SELECT * FROM user WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function getUser($pdo, $user_id)
    {
        $sql = "SELECT * FROM user WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function addUser($pdo, $name, $email, $phone, $address, $password, $role)
    {
        $sql = "INSERT INTO user(name, email, phone, address, password, role) VALUES(:name, :email, :phone, :address, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function updateUser($pdo, $name, $email, $phone, $address, $role, $user_id)
    {
        $sql = "UPDATE user SET name = :name, email = :email, phone = :phone, address = :address, role = :role WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function deleteUser($pdo, $user_id)
    {
        $sql = "DELETE FROM user WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            header("location: ql_TaiKhoan.php");
        } 
    }

    //kiểm tra xem user đã tồn tại trong bảng order chưa
    public static function checkUserInOrder($pdo, $user_id)
    {
        $sql = "SELECT COUNT(*) FROM `order_bill` WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchColumn();
        }
    }
}
?>