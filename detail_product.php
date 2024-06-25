<?php
$title = "Chi tiết sản phẩm";
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Auth.php';
require_once 'class/Cart.php';
require_once "inc/init.php"; 

$id = $_GET['Id'];

if (!isset($_GET['Id'])) {
    die("Cần cung cấp thông tin sản phẩm");
}

$conn = new Database();
$pdo = $conn->getConnect();

$newPro = Product::getNewProduct($pdo);

if (empty ($_SESSION['user_id'])) {
        $user_id = null;
    } else {
        $user_id = $_SESSION['user_id'];
    }


    $quantity = 1;
    $total_price = 0;

    $product = Product::getOneProductById($pdo, $id);

    if (!$product) {
        die("id không hợp lệ");
    }

    //Xử lý khi nhấn nút thêm vào giỏ hàng
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_SESSION['user_id'])) {
            echo "<script>alert('Thêm vào giỏ hàng thất bại. Bạn phải đăng nhập để tiếp tục')</script>";
            header("refresh:0; url=login.php");
        } else {
            $pro_id = $_GET['Id'];
            $product2 = Product::getOneProductById($pdo, $pro_id);
            $total_price = $product2->price * $quantity;
            Cart::addCart($pdo, $user_id, $pro_id, $quantity, $total_price);
        }
    }

    ?>

    <?php require_once 'inc/header.php'; ?>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container d-flex align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="list_product.php">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="page-content">
            <div class="container">
                <div class="product-details-top mb-2">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="product-gallery">
                                <figure class="product-main-image">
                                    <img id="product-zoom" src="uploads/<?= $product->image ?>" alt="" style="image-rendering: crisp-edges;">
                                </figure>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <form method="post">
                                <div class="product-details">
                                    <h1 class="product-title"> <?= $product->pro_name ?> </h1>

                                    <div class="product-price">
                                        <?= number_format($product->price, 0, ',', '.') ?> VNĐ
                                    </div>

                                    <div class="details-filter-row details-row-size">
                                        <label for="quantity">Số lượng:</label>
                                        <div class="product-details-quantity">
                                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" step="1" data-decimals="0" required>
                                        </div><!-- End .product-details-quantity -->
                                    </div><!-- End .details-filter-row -->

                                    <div class="product-details-action">
                                        <style>
                                            .btn-cart:nth-child(3):before {

                                                content: "\f07a";
                                                margin-right: 0;

                                            }

                                            .product-details-action .btn-cart span a {
                                                color: black;
                                            }

                                            .product-details-action .btn-cart span a:hover {
                                                color: white;
                                            }

                                            .product-details-action .btn-cart:nth-child(3):hover,
                                            .product-details-action .btn-cart:nth-child(3):focus {
                                                color: #fff;
                                                border-color: #ee5ba5;
                                                background-color: lightcoral;
                                            }
                                        </style>
                                        <button type="submit" class="btn btn-product btn-cart"><span>Thêm vào giỏ hàng</span></button>
                                        <button class="btn btn-product btn-cart mx-4">
                                            <span><a href="cart.php">Xem giỏ hàng</a></span>
                                        </button>

                                    </div><!-- End .product-details-action -->

                                    <div class="product-details-footer">
                                        <div class="product-cat">
                                            <span>Danh mục:</span>
                                            <?php foreach ($data as $category) : ?>
                                                <?php if ($category->id == $product->category_id) : ?>
                                                    <a href="product_category.php?cateid=<?= $category->id ?>"><?= $category->cate_name ?></a>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </div><!-- End .product-cat -->

                                        <div class="social-icons social-icons-sm">
                                            <span class="social-label">Share:</span>
                                            <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                            <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                            <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            <a href="#" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                        </div>
                                    </div><!-- End .product-details-footer -->
                                </div><!-- End .product-details -->
                            </form>
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->
                </div><!-- End .product-details-top -->
            </div><!-- End .container -->

            <div class="product-details-tab product-details-extended">
                <div class="container">
                    <ul class="nav nav-pills justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="product-info-link" data-toggle="tab" href="#product-info-tab" role="tab" aria-controls="product-info-tab" aria-selected="false">Thông tin chi tiết</a>
                        </li>
                        <!--                  
                        <li class="nav-item">
                            <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab" role="tab" aria-controls="product-review-tab" aria-selected="false">Reviews (2)</a>
                        </li> -->
                    </ul>
                </div><!-- End .container -->

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-info-tab" role="tabpanel" aria-labelledby="product-info-link">
                        <div class="product-desc-content">
                            <div class="container w-25 m-auto">
                                <ul>
                                    <li>Tên sản phẩm: <?= $product->pro_name ?></li>
                                    <li>Mô tả: <?= $product->description ?></li>
                                    <li>Giá bán: <?= number_format($product->price, 0, ',', '.') ?> VNĐ</li>

                                </ul>

                            </div><!-- End .container -->
                        </div><!-- End .product-desc-content -->
                    </div><!-- .End .tab-pane -->


                </div><!-- End .tab-content -->
            </div><!-- End .product-details-tab -->

            <div class="container">
                <h2 class="title text-center mb-4">Có thể bạn sẽ thích</h2><!-- End .title text-center -->
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":1
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
                        <div class="product product-7">
                            <figure class="product-media">
                                <span class="product-label label-new">New</span>
                                <a href="detail_product.php?Id=<?= $proNew->id ?>">
                                <img src="uploads/<?= $proNew->image ?>" alt="Product image" class="product-image">
                            </a>

                            <div class="product-action">
                                <a href="detail_product.php?Id=<?= $proNew->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </figure><!-- End .product-media -->

                        <div class="product-body">
                            <div class="product-cat">
                                <a href="#">Women</a>
                            </div><!-- End .product-cat -->
                            <h3 class="product-title"><a href="detail_product.php?Id=<?= $proNew->id ?>"> <?= $proNew->pro_name ?> </a></h3><!-- End .product-title -->
                            <div class="product-price">
                                <?= number_format($proNew->price, 0, ',', '.') ?> VNĐ
                            </div><!-- End .product-price -->

                        </div><!-- End .product-body -->
                    </div><!-- End .product -->
                <?php endforeach ?>

            </div><!-- End .owl-carousel -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
<?php require_once 'inc/footer.php'; ?>