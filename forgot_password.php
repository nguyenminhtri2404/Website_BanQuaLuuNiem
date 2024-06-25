<?php
$title = 'Quên mật khẩu';
require_once 'class/Database.php';
require_once 'class/Auth.php';
require_once 'inc/sendmail.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();
$test ='';
$otp='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $result = Auth::checkMailExist($pdo, $email);
    if ($result) {
        $opt= Auth::generateOTP();
        $email_send = new Mailer();
        $tieude = 'Lấy lại mật khẩu';

        $noidung = '
        <p>Chào bạn, chúng tôi nhận được yêu cầu đổi mật khẩu</p>
        <p>Mã OTP của bạn là: <b>'.$opt.'</b> </p>
        <p style="color:red;"><b>Lưu ý:</b> Mã chỉ có hiệu lực trong vòng 5 phút.</p>
        <p>Click vào link sau để đổi mật khẩu: <a href="http://localhost/Test3/reset_password.php?email='.$email.'">Đổi mật khẩu</a></p>
        <p>Trân trọng!</p>';
        $email_send->sendMail($email, $tieude, $noidung);

        //Tao session luu lai OTP
        $_SESSION['code'] = $opt;
        $test=$otp;
        //Thiêt lập thời gian sống cho session
        $session_lifetime = 300;
        $_SESSION['exp_time'] = time()+$session_lifetime;
        $sucess = "Vui lòng kiểm tra email để nhận OTP đổi mật khẩu!";
        //Sau 5s chuyển hướng về trang reset_password
        header("refresh:5;url=reset_password.php?email=$email");
    } else {
        $error = "Email không tồn tại!";
    }
}


?>


<?php require_once 'inc/header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($sucess)) : ?>
                <div class="alert alert-success"><?php echo $sucess; ?></div>
            <?php endif; ?>
            <h2 class="text-center">Quên mật khẩu</h2>
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="forgot_password">Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php'; ?>