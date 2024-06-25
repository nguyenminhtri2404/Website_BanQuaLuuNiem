<?php
require_once 'class/Database.php';
require_once 'class/Category.php';
require_once 'class/Cart.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();
$data = Category::getAll($pdo);
//Nếu chưa đăng nhập thì SESSION['user_id'] chưa tồn tại
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null;
}

$num_cart = Cart::countCart($pdo, $_SESSION['user_id']);

if (!$num_cart) {
    $num_cart = 0;
}

$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    header("location: result_search.php?pro_name=$search");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <meta name="souvenir" content="Triss Souvenir">
    <meta name="description" content="Triss Souvenir">
    <meta name="author" content="Triss">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/icon_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/icon_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/icon_logo.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/icon_logo.png">
    <meta name="apple-mobile-web-app-title" content="Triss Souvenir Shop">
    <meta name="application-name" content="Triss Souvenir Shop">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/nouislider/nouislider.css">
    <link rel="stylesheet" href="assets/css/demos/demo-11.css">
    <link rel="stylesheet" href="assets/css/demos/demo-2.css">

</head>

<body>
    <div class="page-wrapper">
        <header class="header header-7">
            <div class="header-top mt-1" style="font-size: 10pt;">
                <div class="container">
                    <div class="header-left">
                        <a href="tel:#"><i class="icon-phone"></i>Call: +84 23456 789</a>
                    </div><!-- End .header-left -->

                    <div class="header-right">

                        <ul class="top-menu">
                            <li>
                                <ul>
                                    <li>
                                        <?php if (isset($_SESSION['logged_user'])) : ?>
                                            <a href="logout.php" style="font-weight: 500;">Đăng xuất</a>
                                        <?php else : ?>
                                            <a href="login.php" style="font-weight: 500;">Đăng nhập / Đăng ký</a>
                                        <?php endif; ?>
                                        <!-- <a href="#signin-modal" data-toggle="modal" style="font-weight: 500;">Đăng ký / Đăng nhập</a> -->
                                    </li>

                                </ul>
                            </li>
                        </ul><!-- End .top-menu -->
                    </div><!-- End .header-right -->

                </div><!-- End .container -->
            </div><!-- End .header-top -->
            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button>

                        <a href="index.php" class="logo">
                            <img src="assets/images/demos/demo-11/logo.png" alt="Triss Logo" width="130" height="35">
                        </a>
                    </div><!-- End .header-left -->

                    <div class="header-right">

                        <nav class="main-nav">
                            <ul class="menu sf-arrows">
                                <!-- <li class="megamenu-container active"> -->
                                <li class="megamenu-container">
                                    <a href="index.php" class="">TRANG CHỦ</a>
                                </li>

                                <li>
                                    <a href="list_product.php" class="sf-with-ul">SẢN PHẨM</a>

                                    <div class="megamenu megamenu-sm">
                                        <div class="row no-gutters">
                                            <div class="col-md-6">
                                                <div class="menu-col">
                                                    <div class="menu-title">Danh mục sản phẩm</div><!-- End .menu-title -->
                                                    <ul>
                                                        <?php foreach ($data as $cate) : ?>
                                                            <li><a href="product_category.php?cateid=<?= $cate->id ?>"><?= $cate->cate_name ?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div><!-- End .menu-col -->
                                            </div><!-- End .col-md-6 -->

                                            <div class="col-md-6">
                                                <div class="banner banner-overlay">
                                                    <a href="category.html">
                                                        <img src="assets/images/menu/banner-2.jpg" alt="Banner">
                                                    </a>
                                                </div><!-- End .banner -->
                                            </div><!-- End .col-md-6 -->
                                        </div><!-- End .row -->
                                    </div><!-- End .megamenu megamenu-sm -->
                                </li>
                                <li>
                                    <a href="about.php">ABOUT</a>
                                </li>

                                <li>
                                    <a href="contact.php">LIÊN HỆ</a>
                                </li>
                            </ul><!-- End .menu -->

                            <script>
                                // Get all the menu items
                                var menuItems = document.querySelectorAll('.menu li');

                                // Get the current URL
                                var currentUrl = window.location.href;

                                // Check each menu item
                                menuItems.forEach(function(menuItem) {
                                    var link = menuItem.querySelector('a');
                                    if (link.href === currentUrl) {
                                        // If the href of the link matches the current URL, add the 'active' class
                                        menuItem.classList.add('active');
                                    }
                                });
                            </script>
                        </nav><!-- End .main-nav -->

                        <div class="header-search">
                            <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                            <form method="post">
                                <div class="header-search-wrapper">
                                    <label for="q" class="sr-only">Search</label>
                                    <input type="text" class="form-control" name="search" id="search" placeholder="Tìm kiếm..." required>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </div><!-- End .header-search -->


                        <div class="dropdown cart-dropdown">
                            <a href="cart.php" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false" data-display="static">
                                <i class="icon-shopping-cart"></i>
                                <span class="cart-count"><?= $num_cart ?></span>
                            </a>

                        </div><!-- End .cart-dropdown -->


                        <div class="dropdown cart-dropdown">
                            <a href="infouser.php" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false" data-display="static">
                                <div class="icon " style="font-size: 1.6rem">
                                    <i class="icon-user"> </i>
                                    <?php if (isset($_SESSION['logged_user'])) : ?>
                                        <span>Hi <?= $_SESSION['logged_user'] ?></span>
                                    <?php else : ?>
                                        <span class="fw-bold">Tài khoản</span>
                                    <?php endif; ?>
                                </div>
                            </a>

                        </div><!-- End .cart-dropdown -->


                    </div><!-- End .header-right -->
                </div><!-- End .container -->
            </div><!-- End .header-middle -->
        </header><!-- End .header -->