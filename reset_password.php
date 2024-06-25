<?php
$title = 'Đổi mật khẩu';
require_once 'class/Database.php';
require_once 'class/Auth.php';
require_once 'inc/sendmail.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();
$email = $_GET['email'];

//Nếu session chưa tồn tại thì set giá trị rỗng
if (!isset($_SESSION['code'])  && !isset($_SESSION['exp_time'])) {
    $_SESSION['code'] = null;
    $_SESSION['exp_time'] = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $repassword = $_POST['repassword']; 
    $code_verify = $_POST['code_verify']; 

    //Kiem tra session có hết hạn chưa
    if (isset($_SESSION['exp_time']) && time() > $_SESSION['exp_time']) {
        unset($_SESSION['code']);
        unset($_SESSION['exp_time']);
    }

    if ($password == $repassword && $otp == $code_verify ) {
        $password = Auth::hashPassword($password);
        $result = Auth::resetPassword($pdo, $email, $password);
        if ($result) {
            $sucess = "Đổi mật khẩu thành công!";
            $email_send = new Mailer();
            $tieude = 'Đổi mật khẩu thành công';
            $noidung = '
            <p>Chào bạn,!</p>
            <p>Mật khẩu của bạn đã được thay đổi thành công!</p>';
            $email_send->sendMail($email, $tieude, $noidung);
            //Sau 5s chuyển hướng về trang login
            unset($_SESSION['code']);
            header("refresh:5;url=login.php");
        } else {
            $error = "Đổi mật khẩu thất bại do OTP đã hết hạn!";
        }
    } else {
        $error = "Mật khẩu không trùng khớp!";
    }
}



?>
<?php require_once 'inc/header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
          
            <h2 class="text-center">Đổi mật khẩu</h2>
            <form method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $_GET['email']; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu mới:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group ">
                    <label for="repassword">Nhập lại mật khẩu:</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="code_verify">OTP:</label>
                    <input type="text" name="code_verify" id="code_verify" class="form-control" value="<?php $code_verify  ?>">
                </div>

                <div class="form-group ">
                    <button type="submit" class="btn btn-primary" name="reset_password">Đổi mật khẩu</button>
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