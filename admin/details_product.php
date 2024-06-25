<?php

if (! isset($_GET['Id']))
{
    die("Cần cung cấp thông tin sản phẩm");
}

$id= $_GET['Id'];

require_once '../class/Database.php';
require_once '../class/Product.php';
require_once "../class/Category.php";
require_once "../class/Auth.php";
require_once "../inc/init.php"; 

$conn= new Database();
$pdo = $conn->getConnect();
$product= Product::getOneProductById($pdo, $id);
$data= Category::getAll($pdo);

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}


if(! $product)
{
    die("id không hợp lệ");
}
?>

<?php require_once "inc/header.php"?>
<h1 class="text-center text-primary"> Thông tin sản phẩm </h1>
<table class= "table table-bordered table-light">
    <tr>
        <th class= "table-info"> Tên sản phẩm </th>
        <td> <?= $product->pro_name ?> </td>
    </tr>
    <tr>
        <th class= "table-info"> Giá bản</th>
        <td> <?= number_format($product->price, 0, ",", ".") ?> </td>
    </tr>
    <tr>
        <th class= "table-info"> Số lượng </th>
        <td> <?= $product->quantity ?> </td>
    </tr>
    <tr>
        <th class= "table-info"> Mô tả </th>
        <td> <?= $product->description ?></td>
    </tr>
    <tr >
        <th class= "table-info"> Hình ảnh </th>
        <td> <img src="../uploads/<?= $product->image ?>" width= "300px" height= "300px" style="object-fit: cover;"> </td>
    </tr>

    <tr>
        <th class= "table-info"> Danh mục </th>
        <td> 
            <?php foreach($data as $category): ?>
                <?php if($category->id == $product->category_id): ?>
                    <?= $category->cate_name ?>
                <?php endif ?>
            <?php endforeach ?>
        </td>
    </tr>
</table>

<?php require_once "inc/footer.php"?>