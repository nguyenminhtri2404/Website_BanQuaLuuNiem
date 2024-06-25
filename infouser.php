<?php
$title = 'Thông tin khách hàng';
require_once 'class/Database.php';
require_once 'class/User.php';
require_once 'class/Orders.php';
require_once 'class/Auth.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();

$checkLogin = Auth::checkLogin();
if (!$checkLogin) {
	header("location: login.php");
}

$user_id = $_SESSION['user_id'];
$user = User::getUser($pdo, $user_id);

?>

<?php require_once 'inc/header.php' ?>

<main class="main">
	<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
		<div class="container">
			<h1 class="page-title">Xin chào <?= $user['name'] ?> <span>Shop</span></h1>
		</div><!-- End .container -->
	</div><!-- End .page-header -->
	<nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
		<div class="container">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Home</a></li>
				<li class="breadcrumb-item" aria-current="page">Tài khoản của tôi</li>
			</ol>
		</div><!-- End .container -->
	</nav><!-- End .breadcrumb-nav -->

	<div class="page-content">
		<div class="dashboard">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-12">
						<div class="tab-content w-50 m-auto">
							<div>
								<label for="name">Họ tên</label>
								<input type="text" class="form-control" id="name" value="<?= $user['name'] ?>" disabled>
							</div>
							<div>
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" value="<?= $user['email'] ?>" disabled>
							</div>
							<div>
								<label for="phone">Số điện thoại</label>
								<input type="text" class="form-control" id="phone" value="<?= $user['phone'] ?>" disabled>
							</div>
							<div>
								<label for="address">Địa chỉ</label>
								<input type="text" class="form-control" id="address" value="<?= $user['address'] ?>" disabled>
							</div>
							<div class="row justify-content-between align-items-center px-3">
								<a href="orderd.php" class="btn btn-outline-primary">Xem đơn hàng đã đặt</a>
								<a href="change_password.php" class="btn btn-primary">Đổi mật khẩu</a>
							</div>
						</div>
					</div>
				</div><!-- End .col-lg-9 -->
			</div><!-- End .row -->
		</div><!-- End .container -->
	</div><!-- End .dashboard -->
	</div><!-- End .page-content -->
</main><!-- End .main -->
<?php require_once 'inc/footer.php' ?>