<?php
require_once 'class/Database.php';
require_once 'class/Auth.php';
require_once 'class/User.php';
require_once 'inc/sendmail.php';
require_once 'inc/init.php';

$title = 'Đổi mật khẩu';
$conn = new Database();
$pdo = $conn->getConnect();

$checkLogin = Auth::checkLogin();
if (!$checkLogin) {
    header("location: login.php");
}

$user_id = $_SESSION['user_id'];
$user = User::getUser($pdo, $user_id);

$email = $user['email'];
$oldpassword = $user['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $repassword = $_POST['repassword'];
    if (password_verify($password, $oldpassword)) {
        if ($newpassword == $repassword) {
            $newpassword = Auth::hashPassword($newpassword);
            $result = Auth::resetPassword($pdo, $email, $newpassword);
            if ($result) {
                $sucess = "Đổi mật khẩu thành công!";
                $email_send = new Mailer();
                $tieude = 'Mật khẩu của bạn đã được thay đổi';
                $noidung = '
                <p>Chào bạn,!</p>
                <p>Mật khẩu của bạn đã được thay đổi thành công!</p>';
                $email_send->sendMail($email, $tieude, $noidung);
                //Sau 5s chuyển hướng về trang login
                header("refresh:5;url=login.php");
            } else {
                $error = "Đổi mật khẩu thất bại!";
            }
        } else {
            $error = "Mật khẩu không trùng khớp!";
        }
    } else {
        $error = "Mật khẩu cũ không đúng!";
    }
}
?>
<?php require_once 'inc/header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Đổi mật khẩu</h2>
            <form method="post">
                <div class="form-group ">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" disabled>
                </div>
                <div class="form-group ">
                    <label for="password">Nhập mật khẩu cũ:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group ">
                    <label for="newpassword">Nhập mật khẩu mới:</label>
                    <input type="password" name="newpassword" id="newpassword" class="form-control" required>
                </div>
                <div class="form-group ">
                    <label for="repassword">Nhập lại mật khẩu mới:</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" required>
                </div>
                <div class="form-group ">
                    <button type="submit" class="btn btn-primary" name="change_password">Đổi mật khẩu</button>
                </div>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (isset($sucess)) : ?>
                    <div class="alert alert-success"><?php echo $sucess; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>