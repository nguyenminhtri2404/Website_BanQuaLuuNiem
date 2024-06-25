<?php
$title = 'Trang đăng ký';
require_once 'class/Database.php';
require_once 'class/Auth.php';

$nameError = '';
$emailError = '';
$passError = '';
$phoneError = '';
$addressError = '';
$repassError = '';


$name= '';
$email='';
$pass= '';
$repass= '';
$phone= '';
$address= '';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $repass = $_POST['repassword'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $conn= new Database();
    $pdo = $conn->getConnect();

    $passwd_hash= Auth::hashPassword($pass);

    $result = Auth::register($pdo, $name, $email, $passwd_hash, $repass, $phone, $address);

    if ($result === true)
    {
        header('Location: login.php');
    }
        
    else {
        if (isset($result['nameError'])) {
            $nameError = $result['nameError'];
        }
        if (isset($result['emailError'])) {
            $emailError = $result['emailError'];
        }
        if (isset($result['genderError'])) {
            $genderError = $result['genderError'];
        }
        if (isset($result['passError'])) {
            $passError = $result['passError'];
        }
        if (isset($result['repassError'])) {
            $repassError = $result['repassError'];
        }
        if (isset($result['phoneError'])) {
            $phoneError = $result['phoneError'];
        }
        if (isset($result['addressError'])) {
            $addressError = $result['addressError'];
        }       
    }
}

?>
<?php require_once "inc/header.php" ?>
<div class="container-fluid bg-success-subtle py-3">
    <h3 style="text-align: center">ĐĂNG KÝ TÀI KHOẢN</h3>
    <div class="container w-50">
        <form method="post">
            <div class="form-group">
                <label for="name" class="col-form-label " style="font-weight: bold;">Họ tên <span class="text-danger">*</span> </label> <br />
                <input id="name" name="name" class="form-control" placeholder="Nhập họ tên" value="<?=$name?>"/>
                <span class="text-danger fw-bold"> <?= $nameError?> </span>
            </div>
          
            <div class="form-group">
                <label for="email" class="col-form-label" style="font-weight: bold;">Email <span class="text-danger">*</span>  </label> <br />
                <input id="email" name="email" class="form-control" placeholder="Nhập email" value="<?=$email?>"/>
                <span class="text-danger fw-bold"> <?= $emailError?> </span>
            </div>

            <div class="form-group">
                <label for="password" class="col-form-label" style="font-weight: bold;">Mật khẩu <span class="text-danger">*</span>  </label> <br />
                <input type="password" id="password" name="password" class="form-control" value="<?=$pass?>" />
                <span class="text-danger fw-bold"> <?= $passError?> </span>
            </div>

            <div class="form-group">
                <label for="repassword" class="col-form-label" style="font-weight: bold;">Xác nhận mật khẩu <span class="text-danger">*</span>  </label> <br />
                <input type="password" id="repassword" name="repassword" class="form-control" value="<?=$repass?>" />
                <span class="text-danger fw-bold"> <?= $repassError?> </span>
            </div>

            <div class="form-group">
                <label for="phone" class="col-form-label" style="font-weight: bold;">Số điện thoại <span class="text-danger">*</span>  </label> <br />
                <input id="phone" name="phone" class="form-control" value="<?=$phone?>" />
                <span class="text-danger fw-bold"> <?= $phoneError?> </span>
            </div> 

            <div class="form-group">
                <label for="address" class="col-form-label" style="font-weight: bold;">Địa chỉ</label> <br />
                <input id="address" name="address" class="form-control" value="<?=$address?>" />
                <span class="text-danger fw-bold"> <?= $addressError?> </span>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-outline-primary-2 w-100 m-auto">
                    <span>ĐĂNG KÝ TÀI KHOẢN</span>
                    <i class="icon-long-arrow-right"></i>
                </button>
            </div><!-- End .form-footer -->

        </form>
    </div>
</div>
<?php require_once "inc/footer.php" ?>