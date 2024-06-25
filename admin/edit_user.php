<?php
require_once '../class/Database.php';
require_once '../class/User.php';
require_once '../class/Auth.php';
require_once '../inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}

$nameError = '';
$emailError = '';
$passError = '';
$phoneError = '';
$addressError = '';
$roleError = '';

$user_id = $_GET['user_id'];
$user = User::getUser($pdo, $user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    if (empty($name)) {
        $nameError = "Phải nhập tên";
    }

    if (empty($email)) {
        $emailError = "Phải nhập email";
    }

    if (empty($phone)) {
        $phoneError = "Số điện thoại không được bỏ trống!";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $phoneError = "Sô điện thoại không hợp lệ!";
    }

    if (empty($address)) {
        $addressError = "Phải nhập địa chỉ";
    }

    if (empty($role)) {
        $roleError = "Phải chọn chức vụ";
    }

    if (empty($nameError) && empty($emailError) && empty($phoneError) && empty($addressError) && empty($roleError)) {
        User::updateUser($pdo, $name, $email, $phone, $address, $role, $user_id);
        header("location: ql_TaiKhoan.php");
    }
}


?>
<?php require_once 'inc/header.php' ?>
<main>
    <div class="container">
        <h2 class="text-center text-primary">Chỉnh sửa thông tin tài khoản</h2>
        <form method="post" class="w-50 m-auto">
            <div class="form-group">
                <label for="name">Tên user</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
                <span class="text-danger"><?php echo $phoneError; ?></span>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>">
                <span class="text-danger"><?php echo $addressError; ?></span>
            </div>
            <div class="form-group">
                <label for="role">Chức vụ</label>
                <select class="form-control" id="role" name="role">
                    <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
                <span class="text-danger"><?php echo $roleError; ?></span>
            </div>
            <button type="submit" class="btn btn-primary mt-2 m-auto">Lưu</button>
        </form>
    </div>
</main>


<?php require_once 'inc/footer.php' ?>