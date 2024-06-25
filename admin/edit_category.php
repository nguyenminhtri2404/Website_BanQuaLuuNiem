<?php
if (! isset($_GET['Id']))
{
    die("Cần cung cấp thông tin sản phẩm");
}

$id= $_GET['Id'];

require_once '../class/Database.php';
require_once '../class/Product.php';
require_once '../class/Auth.php';
require_once '../class/Category.php';
require_once "../inc/init.php"; //đọc session 1 lần

$conn= new Database();
$pdo = $conn->getConnect();
$category = Category::getOneCategoryById($pdo, $id);

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}


if(! $category)
{
    die("id không hợp lệ");
}


$nameCategoryError= '';
$nameCategory='';

if($_SERVER["REQUEST_METHOD"] == "POST") //kiểm tra có submit không
{
    $nameCategory= $_POST['name'];
    
    //Xử lí form
    if(empty($nameCategory))
    {
        $nameCategoryError= "Phải nhập tên danh mục";
    }
    if (!$nameCategoryError)
    {
        Category::editCategory($pdo, $id, $nameCategory);
    }
}


?>

<?php require_once "inc/header.php" ?>
<h2 class="text-center">Chỉnh sửa danh mục</h2>
<form class= "w-50 m-auto" method="post">

    <div class="mb-3">
        <label for="name" class="form-label">Tên danh mục</label>
        <input class="form-control" id="name" name= "name" type="text" value= "<?= $category->cate_name?>">
        <span class= "text-danger fw-bold"><?= $nameCategoryError ?></span>
    </div>
    
    
    <button type="submit" class="btn btn-primary"> Sửa danh mục</button>
</form>

<?php require_once "inc/footer.php" ?>
