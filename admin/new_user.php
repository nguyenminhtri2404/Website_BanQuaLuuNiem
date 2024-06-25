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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $passwd_hash = Auth::hashPassword($password);

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

    if (empty($password)) {
        $passError = "Phải nhập mật khẩu";
    } elseif (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
        $passError = "Password phải đủ 8 kí tự, chứa chữ in hoa, chữ thường và chứa kí tự đặc biệt!";
    }

    if (empty($role)) {
        $roleError = "Phải chọn vai trò";
    }

    if (!$nameError && !$emailError && !$phoneError && !$addressError && !$passError && !$roleError) {
        $result = User::addUser($pdo, $name, $email, $phone, $address, $passwd_hash, $role);
        if ($result) {
            header("location: ql_TaiKhoan.php");
        }
    }
}



?>
<?php require_once 'inc/header.php' ?>
<main>
    <div class="container-fluid">
        <h1 class="text-center text-primary">Thêm người dùng</h1>
        <form method="post" class="w-50 m-auto">
            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($name)) echo $name; ?>">
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($email)) echo $email; ?>">
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php if (isset($phone)) echo $phone; ?>">
                <span class="text-danger"><?php echo $phoneError; ?></span>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php if (isset($address)) echo $address; ?>">
                <span class="text-danger"><?php echo $addressError; ?></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="row">
                    <div class="col-10">
                        <input type="password" class="form-control" id="password" name="password" value="<?php if (isset($password)) echo $password; ?>">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-secondary" onclick="showPassword()"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mt-2" onclick="generatePassword()">Tạo mật khẩu</button>
                <button type="button" class="btn btn-secondary mt-2" onclick="clearPassword()">Clear</button>

                <br>
                <span class="text-danger"><?php echo $passError; ?></span>

                <script>
                    function generatePassword() {
                        var length = 8,
                            charsetLower = "abcdefghijklmnopqrstuvwxyz",
                            charsetUpper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                            charsetNumbers = "0123456789",
                            charsetSpecial = "!@#$%^&*()_+",
                            password = "";
                        password += charsetLower.charAt(Math.floor(Math.random() * charsetLower.length));
                        password += charsetUpper.charAt(Math.floor(Math.random() * charsetUpper.length));
                        password += charsetNumbers.charAt(Math.floor(Math.random() * charsetNumbers.length));
                        password += charsetSpecial.charAt(Math.floor(Math.random() * charsetSpecial.length));
                        for (var i = 4, n = charsetLower.length + charsetUpper.length + charsetNumbers.length + charsetSpecial.length; i < length; ++i) {
                            var randomCharset = Math.floor(Math.random() * 4);
                            if (randomCharset === 0) {
                                password += charsetLower.charAt(Math.floor(Math.random() * charsetLower.length));
                            } else if (randomCharset === 1) {
                                password += charsetUpper.charAt(Math.floor(Math.random() * charsetUpper.length));
                            } else if (randomCharset === 2) {
                                password += charsetNumbers.charAt(Math.floor(Math.random() * charsetNumbers.length));
                            } else {
                                password += charsetSpecial.charAt(Math.floor(Math.random() * charsetSpecial.length));
                            }
                        }
                        document.getElementById('password').value = password;
                    }

                    function clearPassword() {
                        document.getElementById('password').value = '';
                    }

                    function showPassword() {
                        var x = document.getElementById("password");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    }
                </script>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Vai trò</label>
                <select class="form-select" id="role" name="role">
                    <option value="" <?php if (isset($role) && $role == '') echo 'selected'; ?>>Chọn vai trò</option>
                    <option value="admin" <?php if (isset($role) && $role == 'admin') echo 'selected'; ?>>admin</option>
                    <option value="customer" <?php if (isset($role) && $role == 'customer') echo 'selected'; ?>>customer</option>
                </select>
                <span class="text-danger"><?php echo $roleError; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Thêm tài khoản mới</button>
        </form>
    </div>

    <?php require_once 'inc/footer.php' ?>