<?php
require_once 'class/Database.php';
require_once 'inc/sendmail.php';

$conn = new Database();
$pdo = $conn->getConnect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$guest_name = $_POST['guest_name'];
	$guest_email = $_POST['guest_email'];
	$guest_phone = $_POST['guest_phone'];
	$guest_mess = $_POST['guest_mess'];

	$tieude = 'Liên hệ từ khách hàng';
	$noidung = '
	<p> Xin chào,</p>
	<p>Tôi tên là: <b>' . $guest_name . '</b></p>
	<p>Email: <b>' . $guest_email . '</b></p>
	<p>Số điện thoại: <b>' . $guest_phone . '</b></p>
	<p>Lời nhắn: <b>' . $guest_mess . '</b></p>
	<p>Mong sớm nhận được phản hồi từ bạn!</p>';
	$email_send = new Mailer();
	$email_send->sendMail('trisssouvenirshop.contact@gmail.com',$tieude, $noidung);
	if ($email_send) {
		echo "<script>alert('Gửi câu hỏi thành công!');</script>";
	} else {
		echo "<script>alert('Gửi câu hỏi thất bại!');</script>";
	}
}
?>

<?php
$title = 'Liên hệ';
require_once 'inc/header.php'
?>

<main class="main">
	<nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
		<div class="container">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
			</ol>
		</div><!-- End .container -->
	</nav><!-- End .breadcrumb-nav -->
	<div class="container">
		<div class="page-header page-header-big text-center" style="background-image: url('assets/images/contact-header-bg.jpg')">
			<h1 class="page-title text-white">Liên hệ<span class="text-white">Hãy giữ liên lạc với chúng tôi</span></h1>
		</div><!-- End .page-header -->
	</div><!-- End .container -->

	<div class="page-content pb-0">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 mb-2 mb-lg-0">
					<h2 class="title mb-1">Thông tin liên hệ</h2><!-- End .title mb-2 -->
					<div class="row">
						<div class="col-sm-7">
							<div class="contact-info">
								<h3>Cơ sở chính</h3>

								<ul class="contact-list">
									<li>
										<i class="icon-map-marker"></i>
										140, Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP.Hồ Chí Minh
									</li>
									<li>
										<i class="icon-phone"></i>
										<a href="tel:#">+84 23456 789</a>
									</li>
									<li>
										<i class="icon-envelope"></i>
										<a href="mailto:#">info@trisssouvenir.com</a>
									</li>
								</ul><!-- End .contact-list -->
							</div><!-- End .contact-info -->
						</div><!-- End .col-sm-7 -->

						<div class="col-sm-5">
							<div class="contact-info">
								<h3>Hoạt động</h3>

								<ul class="contact-list">
									<li>
										<i class="icon-clock-o"></i>
										<span class="text-dark">Thời gian mở cửa </span> <br>8h sáng - 22h tối
									</li>
									<li>
										<i class="icon-calendar"></i>
										<span class="text-dark">Các ngày trong tuần</span> <br>(Trừ lễ - Tết)
									</li>
								</ul><!-- End .contact-list -->
							</div><!-- End .contact-info -->
						</div><!-- End .col-sm-5 -->
					</div><!-- End .row -->
				</div><!-- End .col-lg-6 -->
				<div class="col-lg-6">
					<h2 class="title mb-1">Bạn có câu hỏi?</h2><!-- End .title mb-2 -->
					<p class="mb-2">Vui lòng điền vào form dưới đây.
						<br>
						Chúng tôi sẽ phản hồi bạn qua email bạn cung cấp trong thời gian sớm nhất.
					</p>

					<form method="post" class="contact-form mb-3">
						<div class="row">
							<div class="col-sm-6">
								<label for="cname" class="sr-only">Tên</label>
								<input type="text" class="form-control" id="cname" name="guest_name" placeholder="Tên *" required>
							</div><!-- End .col-sm-6 -->

							<div class="col-sm-6">
								<label for="cemail" class="sr-only">Email</label>
								<input type="email" class="form-control" id="cemail" name="guest_email" placeholder="Email *" required>
							</div><!-- End .col-sm-6 -->
						</div><!-- End .row -->

						<div class="row">
							<div class="col-sm-12">
								<label for="cphone" class="sr-only">Số điện thoại</label>
								<input type="tel" class="form-control" id="cphone" name="guest_phone" placeholder="Số điện thoại">
							</div><!-- End .col-sm-6 -->


						</div><!-- End .row -->

						<label for="cmessage" class="sr-only">Lời nhắn</label>
						<textarea class="form-control" cols="30" rows="4" id="cmessage" name="guest_mess" required placeholder="Lời nhăn *"></textarea>

						<button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
							<span>SUBMIT</span>
							<i class="icon-long-arrow-right"></i>
						</button>
					</form><!-- End .contact-form -->
				</div><!-- End .col-lg-6 -->
			</div><!-- End .row -->

			<hr class="mt-4 mb-5">


		</div><!-- End .container -->
		<h3 class="px-3">Google Maps</h3>
		<div id="map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6591.011690981646!2d106.63171012540575!3d10.8083250482205!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27ea41e05%3A0xfa77697a39f13ab0!2zMTQwIMSQLiBMw6ogVHLhu41uZyBU4bqlbiwgVMOieSBUaOG6oW5oLCBUw6JuIFBow7osIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1715398243545!5m2!1svi!2s" width="100%" height="492" style="border:0;" allowfullscreen="yes" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div><!-- End #map -->
	</div><!-- End .page-content -->
</main><!-- End .main -->

<?php require_once 'inc/footer.php' ?>