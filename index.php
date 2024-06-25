<?php

$title = 'Trang chủ | Chào mừng bạn đến với Triss Souvenir Shop 💕';
require_once 'inc/init.php';
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Cart.php';
require_once 'class/Category.php';

$conn = new Database();
$pdo = $conn->getConnect();

$data = Category::getAll($pdo);
$newPro = Product::getNewProduct($pdo);
$pro_Bear = Product::getProductBear($pdo);
$pro_Toys = Product::getProductToys($pdo);
$pro_Pen = Product::getProductPen($pdo);

$quantity = 1;
$total_price = 0;

if (empty ($_SESSION['user_id'])) {
    $user_id = null;
}
else {
    $user_id = $_SESSION['user_id'];
}


if (isset($_GET['addcart'])) {
    if (empty ($_SESSION['user_id'])){
        echo "<script>alert('Thêm vào giỏ hàng thất bại. Bạn phải đăng nhập để tiếp tục')</script>";
        header("refresh:0; url=index.php");
    }else {
        $pro_id = $_GET['Id'];
        $product2 = Product::getOneProductById($pdo, $pro_id);
        $total_price = $product2->price * $quantity;
        Cart::addCart($pdo, $user_id, $pro_id, $quantity, $total_price);
    }  
}

?>


<?php require_once 'inc/header.php' ?>
<main class="main">
    <div class="intro-slider-container">

        <div class="owl-carousel owl-simple owl-light owl-nav-inside" data-toggle="owl" data-owl-options='{"nav": false, "dots": true, "loop": true, "autoplay": true, "autoplayTimeout": 5000 }'>
            <a href="list_product.php">
                <div class="intro-slide" style="background-image: url(assets/images/slider/slider1.png);">
                    <div class="container intro-content">
                    </div>
                </div>
            </a>

            <a href="list_product.php">
                <div class="intro-slide" style="background-image: url(assets/images/slider/slider2.png);">
                    <div class="container intro-content">
                    </div>
                </div>

            </a>

            <a href="list_product.php">
                <div class="intro-slide" style="background-image: url(assets/images/slider/slider3.jpg);">
                    <div class="container intro-content">
                    </div>
                </div>
            </a>
        </div>


        <span class="slider-loader text-white"></span>
    </div>

    <div class="container mt-2">
        <div class="heading heading-center mb-3">
            <h2 class="title-lg">Sản phẩm nổi bật của Triss Souvenir Shop</h2><!-- End .title -->

            <ul class="nav nav-pills justify-content-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="trendy-all-link" data-toggle="tab" href="#trendy-all-tab" role="tab" aria-controls="trendy-all-tab" aria-selected="true">Mới nhất</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="trendy-fur-link" data-toggle="tab" href="#trendy-fur-tab" role="tab" aria-controls="trendy-fur-tab" aria-selected="false">Gấu nhồi bông</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="trendy-decor-link" data-toggle="tab" href="#trendy-decor-tab" role="tab" aria-controls="trendy-decor-tab" aria-selected="false">Đồ chơi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="trendy-light-link" data-toggle="tab" href="#trendy-light-tab" role="tab" aria-controls="trendy-light-tab" aria-selected="false">Bút</a>
                </li>
            </ul>
        </div><!-- End .heading -->

        <div class="tab-content tab-content-carousel">
            <div class="tab-pane p-0 fade show active" id="trendy-all-tab" role="tabpanel" aria-labelledby="trendy-all-link">
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>


                    <?php foreach ($newPro as $proNew) : ?>
                        <div class="product product-11 text-center">
                            <figure class="product-media">
                                <span class="product-label label-new">NEW</span>
                                <a href="detail_product.php?Id=<?= $proNew->id ?>">
                                    <img src="uploads/<?= $proNew->image ?>" alt="Product image" class="product-image">
                                </a>
                            </figure><!-- End .product-media -->

                            <div class="product-body">
                                <h3 class="product-title"><a href="detail_product.php?Id=<?= $proNew->id ?>"> <?= $proNew->pro_name ?> </a></h3><!-- End .product-title -->
                                <div class="product-price">
                                    <?= number_format($proNew->price, 0, ',', '.') ?> VNĐ
                                </div><!-- End .product-price -->
                            </div><!-- End .product-body -->
                            <div class="product-action">
                                <a href="index.php?Id=<?= $proNew->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </div><!-- End .product -->
                    <?php endforeach; ?>


                </div><!-- End .owl-carousel -->
            </div><!-- .End .tab-pane -->
            <div class="tab-pane p-0 fade" id="trendy-fur-tab" role="tabpanel" aria-labelledby="trendy-fur-link">
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>

                    <?php foreach ($pro_Bear as $proBear) : ?>
                        <div class="product product-11 text-center">
                            <figure class="product-media">
                                <span class="product-label label-new">NEW</span>
                                <a href="detail_product.php?Id=<?= $proBear->id ?>">
                                    <img src="uploads/<?= $proBear->image ?>" alt="Product image" class="product-image">
                                </a>
                            </figure><!-- End .product-media -->

                            <div class="product-body">
                                <h3 class="product-title"><a href="detail_product.php?Id=<?= $proBear->id ?>"> <?= $proBear->pro_name ?> </a></h3><!-- End .product-title -->
                                <div class="product-price">
                                    <?= number_format($proBear->price, 0, ',', '.') ?> VNĐ
                                </div><!-- End .product-price -->
                            </div><!-- End .product-body -->
                            <div class="product-action">
                                <a href="index.php?Id=<?= $proBear->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </div><!-- End .product -->
                    <?php endforeach; ?>





                </div><!-- End .owl-carousel -->
            </div><!-- .End .tab-pane -->
            <div class="tab-pane p-0 fade" id="trendy-decor-tab" role="tabpanel" aria-labelledby="trendy-decor-link">
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>

                    <?php foreach ($pro_Toys as $proToys) : ?>
                        <div class="product product-11 text-center">
                            <figure class="product-media">
                                <span class="product-label label-new">NEW</span>
                                <a href="product.html">
                                    <img src="uploads/<?= $proToys->image ?>" alt="Product image" class="product-image">
                                </a>
                            </figure><!-- End .product-media -->

                            <div class="product-body">
                                <h3 class="product-title"><a href="detail_product.php?Id=<?= $proToys->id ?>"> <?= $proToys->pro_name ?> </a></h3><!-- End .product-title -->
                                <div class="product-price">
                                    <?= number_format($proToys->price, 0, ',', '.') ?> VNĐ
                                </div><!-- End .product-price -->
                            </div><!-- End .product-body -->
                            <div class="product-action">
                                <a href="index.php?Id=<?= $proToys->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </div><!-- End .product -->
                    <?php endforeach; ?>


                </div><!-- End .owl-carousel -->
            </div><!-- .End .tab-pane -->
            <div class="tab-pane p-0 fade" id="trendy-light-tab" role="tabpanel" aria-labelledby="trendy-light-link">
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>

                    <?php foreach ($pro_Pen as $proPen) : ?>
                        <div class="product product-11 text-center">
                            <figure class="product-media">
                                <span class="product-label label-new">NEW</span>
                                <a href="detail_product.php?Id=<?= $proPen->id ?>">
                                    <img src="uploads/<?= $proPen->image ?>" alt="Product image" class="product-image">
                                </a>
                            </figure><!-- End .product-media -->

                            <div class="product-body">
                                <h3 class="product-title"><a href="detail_product.php?Id=<?= $proPen->id ?>"> <?= $proPen->pro_name ?> </a></h3><!-- End .product-title -->
                                <div class="product-price">
                                    <?= number_format($proPen->price, 0, ',', '.') ?> VNĐ
                                </div><!-- End .product-price -->
                            </div><!-- End .product-body -->
                            <div class="product-action">
                                <a href="index.php?Id=<?= $proPen->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </div><!-- End .product -->
                    <?php endforeach; ?>

                </div><!-- End .owl-carousel -->
            </div><!-- .End .tab-pane -->
        </div><!-- End .tab-content -->

        <div class="more-container text-center">
            <a href="list_product.php" class="btn btn-outline-primary  "><span>Xem thêm sản phẩm</span><i class="icon-long-arrow-down"></i></a>
        </div><!-- End .more-container -->
    </div><!-- End .container -->

</main>
<?php require_once 'inc/footer.php' ?>