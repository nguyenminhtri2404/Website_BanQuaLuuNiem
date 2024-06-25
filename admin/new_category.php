<?php
require_once '../class/Database.php';
require_once '../class/Auth.php';
require_once '../class/Product.php';
require_once '../class/Category.php';
require_once "../inc/init.php";

//Auth::doneLogin();

$nameCategoryError = '';
$name_category = '';

$conn = new Database();
$pdo = $conn->getConnect();
$data = Category::getAll($pdo);

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") //kiểm tra có submit không
{
    $name_category = $_POST['name_category'];

    //Xử lí form
    if (empty($name_category)) {
        $nameCategoryError = "Phải nhập tên danh mục!";
    }

    if (!$nameCategoryError) {
        Category::addCategory($pdo, $name_category);
    }
}


?>
<?php require_once "inc/header.php" ?>

<h2 class="text-center text-primary">Thêm danh mục mới</h2>
<form class="w-50 m-auto" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Tên danh mục</label>
        <input class="form-control" id="name_category" name="name_category" type="text" value="<?= $name_category ?>">
        <span class="text-danger fw-bold"><?= $nameCategoryError ?></span>
    </div>

    <button type="submit" class="btn btn-primary"> Thêm danh mục </button>
</form>

<?php require_once "inc/footer.php" ?>