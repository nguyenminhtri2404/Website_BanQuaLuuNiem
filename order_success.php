<?php 
    $title = 'Cảm ơn bạn đã đặt hàng';
    require_once 'inc/init.php';
?>
<?php require_once 'inc/header.php' ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="alert " role="alert">
                <h3 class="alert-heading text-primary">Cảm ơn bạn đã đặt hàng!</h3>
                <p>Đơn hàng của bạn đã được ghi nhận. Vui lòng kiểm tra email, chúng tôi sẽ liên hệ với bạn sớm nhất có thể.</p>
                <hr>
                <p>
                    <a href="orderd.php" class="btn btn-primary">Xem đơn hàng đã đặt
                    </a>
                    <a href="index.php" class="btn btn-primary">Quay lại trang chủ</a>

                </p>
            </div>
        </div>
    </div>
</div>
<?php require_once 'inc/footer.php' ?>






