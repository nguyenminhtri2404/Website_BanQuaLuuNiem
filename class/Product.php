<?php
class Product {

    //tìm kiếm sản phẩm
    public static function search($pdo, $search)
    {
        $sql= "SELECT * FROM product WHERE pro_name LIKE :search";
        $stmt= $pdo->prepare($sql);
    
        $search = "%$search%";
        $stmt->bindParam(":search", $search, PDO::PARAM_STR);
        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy tất cả sản phẩm
    public static function getAll($pdo)
    {
       $sql= "SELECT * FROM product";
       $stmt= $pdo->prepare($sql);

        if($stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //đếm số lượng sản phẩm
    public static function countProduct($pdo)
    {
        $sql= "SELECT COUNT(*) FROM product";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            return $stmt->fetchColumn();
        }
    }

    //phân trang
    public static function pagination($pdo, $limit, $offset)
    {
        $sql= "SELECT * FROM product ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    public static function paginationWithSearch($pdo, $search, $limit, $offset)
    {
        $sql= "SELECT * FROM product WHERE pro_name LIKE :search ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt= $pdo->prepare($sql);

        $search = "%$search%";
        $stmt->bindParam(":search", $search, PDO::PARAM_STR);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //sắp xếp sản phẩm theo giá từ thấp đến cao
    public static function sortPriceUp($pdo)
    {
        $sql= "SELECT * FROM product ORDER BY price ASC";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //sắp xếp sản phẩm theo giá từ cao đến thấp
    public static function sortPriceDown($pdo)
    {
        $sql= "SELECT * FROM product ORDER BY price DESC";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //sắp xếp sản phẩm theo id mới nhất
    public static function sortLatest($pdo)
    {
        $sql= "SELECT * FROM product ORDER BY id DESC";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy sản phẩm theo category
    public static function getProductByCategory($pdo, $id)
    {
        $sql= "SELECT * FROM product WHERE category_id=:cateid";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":cateid", $id, PDO::PARAM_INT);
        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy sản phẩm theo khoảng giá
    public static function getProductByPrice($pdo, $min, $max)
    {
        $sql= "SELECT * FROM product WHERE price BETWEEN :min AND :max";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":min", $min, PDO::PARAM_INT);
        $stmt->bindParam(":max", $max, PDO::PARAM_INT);
        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy sản phẩm theo mã sản phẩm
    public static function getOneProductById($pdo, $id)
    {
        $sql= "SELECT * FROM product WHERE id=:id";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetch();
        }
    }

    //phan trang theo category
    public static function paginationByCategory($pdo, $limit, $offset, $id)
    {
        $sql= "SELECT * FROM product WHERE category_id=:cateid ORDER BY id ASC LIMIT :limit OFFSET :offset";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":cateid", $id, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    
    //lấy sản phẩm với id lớn nhất
    public static function getLastID($pdo)
    {
        $sql = "SELECT id FROM product ORDER BY id DESC LIMIT 1";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchColumn();
        }

    }

    //lấy sản phẩm mới nhất
    public static function getNewProduct($pdo)
    {
        $sql= "SELECT * FROM product ORDER BY id DESC LIMIT 5";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy gấu bông
    public static function getProductBear($pdo)
    {
        $sql= "SELECT * FROM product WHERE category_id=1 LIMIT 5";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy đồ chơi
    public static function getProductToys($pdo)
    {
        $sql= "SELECT * FROM product WHERE category_id=2 LIMIT 5";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }

    //lấy bút
    public static function getProductPen($pdo)
    {
        $sql= "SELECT * FROM product WHERE category_id=3 LIMIT 5";
        $stmt= $pdo->prepare($sql);

        if( $stmt->execute())
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
            return $stmt->fetchAll();
        }
    }


    //thêm sản phẩm
    public static function addProduct($pdo, $pro_name,  $price,  $quantity, $desc, $image, $category_id)
    {
        $sql= "INSERT INTO product (pro_name, price, quantity, description ,image, category_id ) VALUES(:pro_name, :price, :quantity, :desc, :image, :category_id)";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":pro_name", $pro_name, PDO::PARAM_STR);
        $stmt->bindParam(":price", $price, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":desc", $desc, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_STR); // Sử dụng tên file mới
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);

        if ($stmt->execute())
        {
            header("location: ql_SanPham.php");
        }
        else
        {
            return false;
        }
    }

    //sửa sản phẩm
    public static function editProduct($pdo, $id, $pro_name, $price, $quantity, $desc, $image, $category_id)
    {
        $sql= "UPDATE product SET pro_name=:pro_name, price=:price, quantity=:quantity, description=:desc, image=:image, category_id=:category_id WHERE id=:id";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":pro_name", $pro_name, PDO::PARAM_STR);
        $stmt->bindParam(":price", $price, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":desc", $desc, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_STR);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);

        if ($stmt->execute())
        {
            header("location: ql_SanPham.php");
        }
        else
        {
            return false;
        }
    }

    //xóa sản phẩm
    public static function deleteProduct($pdo, $id)
    {
        $sql= "DELETE FROM product WHERE id=:id";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute())
        {
            header("location: ql_SanPham.php");
        }
    }

    //kiểm tra sản phẩm có tồn tại trong bảng order_bill_detail không
    public static function checkProductInOrderDetail($pdo, $id)
    {
        $sql= "SELECT COUNT(*) FROM order_bill_detail WHERE product_id=:id";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if( $stmt->execute())
        {
            return $stmt->fetchColumn();
        }
    }

    public static function countProductByCateId($pdo, $id)
    {
        $sql= "SELECT COUNT(*) FROM product WHERE category_id=:cateid";
        $stmt= $pdo->prepare($sql);

        $stmt->bindParam(":cateid", $id, PDO::PARAM_INT);
        if( $stmt->execute())
        {
            return $stmt->fetchColumn();
        }
    }
   
}

?>