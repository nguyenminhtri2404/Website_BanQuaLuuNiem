<?php
class Category
{
    public static function getAll($pdo)
    {
        $sql = "SELECT * FROM category";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Category");
            return $stmt->fetchAll();
        }
    }

    public static function pagination($pdo, $limit, $offset)
    {
        $sql = "SELECT * FROM category ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Category");
            return $stmt->fetchAll();
        }
    }


    public static function getOneCategoryById($pdo, $id)
    {
        $sql = "SELECT * FROM category WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Category");
            return $stmt->fetch();
        }
    }

    //Thêm danh mục
    public static function addCategory($pdo, $cate_name)
    {
        $sql = "INSERT INTO category (cate_name) VALUES (:cate_name)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("location: ql_DanhMuc.php");
        } else {
            echo "Thêm thất bại";
        }
    }

    //Xóa danh mục
    public static function deleteCategory($pdo, $id)
    {
        $sql = "DELETE FROM category WHERE id=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("location: ql_DanhMuc.php");
        }
    }

    //kiểm tra danh mục đã tồn tại trong sản phẩm chưa
    public static function checkCategoryInProduct($pdo, $id)
    {
        $sql = "SELECT COUNT(*) FROM product WHERE category_id=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchColumn();
        }
    }

    //Sửa danh mục
    public static function editCategory($pdo, $id, $cate_name)
    {
        $sql = "UPDATE category SET cate_name=:cate_name WHERE id=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':cate_name', $cate_name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("location: ql_DanhMuc.php");
        } else {
            echo "Sửa thất bại";
        }
    }
}
