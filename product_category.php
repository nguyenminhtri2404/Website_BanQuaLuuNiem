<?php
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Category.php';
require_once 'class/Auth.php';
require_once 'class/Cart.php';
require_once "inc/init.php"; 

if (!isset($_GET['cateid'])) {
    die("Cần cung cấp thông tin danh mục sản phẩm");
}

$id = $_GET['cateid'];


$conn = new Database();
$pdo = $conn->getConnect();
$pro = Product::getProductByCategory($pdo, $id);
$data = Category::getAll($pdo);
foreach ($data as $cate) {
    if ($cate->id == $id) {
        $title = $cate->cate_name;
    }
}


if (!$pro) {
    $error = "Không tìm thấy sản phẩm nào thuộc danh mục này";
}

if (empty($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$ppp = 6;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalProducts = count($pro);

$totalPage = ceil($totalProducts / $ppp);

$pro = Product::paginationByCategory($pdo, $limit, $offset, $id);

$quantity = 1;
$total_price = 0;

$user_id = $_SESSION['user_id'];

if (isset($_GET['addcart'])) {
    if (empty ($_SESSION['user_id'])){
        echo "<script>alert('Thêm vào giỏ hàng thất bại. Bạn phải đăng nhập để tiếp tục')</script>";
        header("refresh:0; url=product_category.php?cateid=$id");
    }else {
        $pro_id = $_GET['proid'];
        $product2 = Product::getOneProductById($pdo, $pro_id);
        $total_price = $product2->price * $quantity;
        Cart::addCart($pdo, $user_id, $pro_id, $quantity, $total_price);
    }  
}






?>

<?php require_once 'inc/header.php' ?>
<main class="main">
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Danh sách sản phẩm<span>
                <?php foreach ($data as $cate) : ?>
                    <?php if ($cate->id == $id) : ?>
                        <?= $cate->cate_name ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#"><?= $title ?></a></li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="toolbox">
                        <div class="toolbox-left">
                            <div class="toolbox-info">
                                <!--Đếm số sản phẩm hiển thị-->
                                Có <span><?= count($pro) ?></span> trên <?= count($pro) ?> sản phẩm
                            </div><!-- End .toolbox-info -->
                        </div><!-- End .toolbox-left -->

                        <div class="toolbox-right">
                            <div class="toolbox-sort">
                                <label for="sortby">Sắp xếp theo:</label>
                                <div class="select-custom">
                                    <select name="sortby" id="sortby" class="form-control">
                                        <option value="" selected="selected">Chọn</option>
                                        <option value="lasted">Mới nhất</option>
                                        <option value="pricedown">Giấ từ cao đến thấp</option>
                                        <option value="priceup">Giá từ thấp đến cao</option>
                                    </select>
                                </div>
                            </div><!-- End .toolbox-sort -->
                        </div><!-- End .toolbox-right -->
                    </div><!-- End .toolbox -->

                    <div class="products mb-3">
                        <div class="row justify-content-center">
                            <?php if (isset($error)) : ?>
                                <div><?= $error ?></div>
                            <?php endif; ?>
                            <?php foreach ($pro as $product) : ?>
                                <div class="col-6 col-md-4 col-lg-4">
                                    <div class="product product-7 text-center">
                                        <figure class="product-media">
                                            <span class="product-label label-new">New</span>
                                            <a href="detail_product.php?Id=<?= $product->id ?>">
                                                <img src="uploads/<?= $product->image ?>" alt="Product image" class="product-image">
                                                <!-- <img src="imgs/gaubong1.jpg" alt="Product image" class="product-image"> -->
                                            </a>

                                            <!-- <div class="product-action-vertical">
                                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                                        </div>End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="product_category.php?cateid=<?= $id ?>&addcart=true&proid=<?= $product->id ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->
                                        </figure><!-- End .product-media -->

                                        <div class="product-body">
                                            <div class="product-cat">
                                                <a href="#">Thú nhồi bông</a>
                                            </div><!-- End .product-cat -->
                                            <h3 class="product-title"><a href="detail_product.php?Id=<?= $product->id ?>"> <?= $product->pro_name ?> </a></h3><!-- End .product-title -->
                                            <div class="product-price">
                                                <?= number_format($product->price, 0, ',', '.') ?> VNĐ
                                            </div><!-- End .product-price -->


                                            <!-- End .product-nav -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div><!-- End .col-sm-6 col-lg-4 -->

                            <?php endforeach; ?>





                        </div><!-- End .row -->
                    </div><!-- End .products -->

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link page-link-prev" href="product_category.php?cateid=<?= $id ?>&page=<?= max($page - 1, 1) ?>" aria-label="Previous">
                                    <span aria-hidden="true"><i class="icon-long-arrow-left"></i></span>Prev
                                </a>
                            </li>
                            <?php
                            // Tính toán vị trí của nút trang đầu tiên
                            $startPage = max(1, min($page - 1, $totalPage - 2));
                            // Hiển thị 3 nút trang từ $startPage
                            for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="product_category.php?cateid=<?= $id ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item-total">of <?= $totalPage ?></li>
                            <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                                <a class="page-link page-link-next" href="product_category.php?cateid=<?= $id ?>&page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                                    Next <span aria-hidden="true"><i class="icon-long-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div><!-- End .col-lg-9 -->
                <aside class="col-lg-3 order-lg-first">
                    <div class="sidebar sidebar-shop">
                        <div class="widget widget-clean">
                            <label>Filters:</label>
                            <a href="#" class="sidebar-filter-clear">Clean All</a>
                        </div><!-- End .widget widget-clean -->

                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
                                    Danh mục sản phẩm
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-1">
                                <div class="widget-body">
                                    <div class="filter-items filter-items-count">

                                        <?php foreach ($data as $cate) : ?>
                                            <div class="filter-item">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="cate-<?= $cate->id ?>" name="cate-<?= $cate->id ?>">
                                                    <label class="custom-control-label" for="cate-<?= $cate->id ?>"><?= $cate->cate_name ?></label>
                                                </div><!-- End .custom-checkbox -->
                                                <span class="item-count"><?php echo Product::countProductByCateId($pdo, $cate->id) ?></span>
                                            </div><!-- End .filter-item -->
                                        <?php endforeach; ?>

                                    </div><!-- End .filter-items -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true" aria-controls="widget-5">
                                    Price
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-5">
                                <div class="widget-body">
                                    <div class="filter-price">
                                        <div class="filter-price-text">
                                            Price Range:
                                            <span id="filter-price-range"></span>
                                        </div><!-- End .filter-price-text -->

                                        <div id="price-slider"></div><!-- End #price-slider -->
                                    </div><!-- End .filter-price -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar sidebar-shop -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
<?php require_once 'inc/footer.php' ?>