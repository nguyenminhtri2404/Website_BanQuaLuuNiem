
<?php
    $title = 'Trang đăng nhập';
    require_once 'class/Database.php';
    require_once 'inc/init.php';
    require_once 'class/Auth.php';

    
    $conn= new Database();
    $pdo = $conn->getConnect();


    $nameError= '';
    $passError= '';
    $name='';
    $pass= '';

    if ($_SERVER['REQUEST_METHOD'] == "POST"  ) {
        $name = $_POST['name'];
        $pass = $_POST['pass'];

        $result= Auth::login($pdo, $name, $pass, $nameError, $passError);
        //var_dump($result);

        if (empty($name) || empty($pass)) {
            $result['Error'] = "Vui lòng nhập đầy đủ thông tin";
        }


        if ($result === true && $_SESSION['role'] === "admin")
        {
            header("location: admin/index.php");
        }
        else if ( $result === true)
        {
            //$_SESSION['logged_user'] = $name; // 
            header("location: index.php");

        } else 
        
        {
            echo "<script>alert('Đăng nhập thất bại');</script>";
            // Đăng nhập thất bại, xử lý thông báo lỗi nếu có
            if (isset($result['nameError'])) {
                $nameError = $result['nameError'];
            }

            if (isset($result['Error'])) {
                $passError = $result['Error'];
            }

        }
    
        
}

?>

<?php require_once "inc/header.php"  ?>
<div class="container-fluid bg-success-subtle py-3">
    <div class="container w-50">
    <h3 style="text-align: center">ĐĂNG NHẬP</h3>
        <form method="post">
            <div class="form-group">
                <label for="name">Email <span class="text-danger">*</span>  </label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>">
                <span class="text-danger"><?= $nameError ?></span>
            </div>
            <div class="form-group">
                <label for="pass">Mật khẩu <span class="text-danger">*</span>  </label>
                <input type="password" class="form-control" id="pass" name="pass" value="<?= $pass ?>">
                <span class="text-danger"><?= $passError ?></span>
            </div>
   
            <div class="form-group">
                Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a>
            </div>
            <div class="form-group">
                <a href="forgot_password.php">Quên mật khẩu</a>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-outline-primary-2 w-100 m-auto">
                    <span>ĐĂNG NHẬP</span>
                    <i class="icon-long-arrow-right"></i>
                </button>
            </div><!-- End .form-footer -->

        </form>
    </div>
</div>
<?php require_once "inc/footer.php" ?>