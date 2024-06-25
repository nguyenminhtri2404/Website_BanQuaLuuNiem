<?php
$title = 'Tất cả sản phẩm';
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Category.php';
require_once 'class/Auth.php';
require_once 'class/Cart.php';
require_once "inc/init.php";

$conn = new Database();
$pdo = $conn->getConnect();
$pro = Product::getAll($pdo);
$data = Category::getAll($pdo);

if (empty($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$ppp = 6;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalProducts = count($pro);

$totalPage = ceil($totalProducts / $ppp);

$pro = Product::pagination($pdo, $limit, $offset);

$quantity = 1;
$total_price = 0;

$user_id = $_SESSION['user_id'];

if (isset($_GET['addcart'])) {
    if (empty ($_SESSION['user_id'])){
        echo "<script>alert('Thêm vào giỏ hàng thất bại. Bạn phải đăng nhập để tiếp tục')</script>";
        header("refresh:0; url=list_product.php");
    }else {
        $pro_id = $_GET['Id'];
        $product2 = Product::getOneProductById($pdo, $pro_id);
        $total_price = $product2->price * $quantity;
        Cart::addCart($pdo, $user_id, $pro_id, $quantity, $total_price);
    }  
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sortby'])) {
        $sortby = $_POST['sortby'];
        switch ($sortby) {
            case 'lasted':
    
                $pro = Product::sortLatest($pdo);
                break;
            case 'pricedown':
           
                $pro = Product::sortPriceDown($pdo);
                break;
            case 'priceup':
            
                $pro = Product::sortPriceUp($pdo);
                break;
            default:
              
                $pro = Product::getAll($pdo);
                break;
        }
    } else {
      
        $pro = Product::getAll($pdo);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category'])) {
        $pro = Product::getProductByCategory($pdo, $_POST['category']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['price-range'])) {
        $price_range = $_POST['price-range'];
        $price_range = explode("-", $price_range);
        $min_price = $price_range[0];
        $max_price = $price_range[1];
        $pro = Product::getProductByPrice($pdo, $min_price, $max_price);
    }
}

?>

<?php require_once 'inc/header.php' ?>
<main class="main">
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Tất cả sản phẩm<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Tất cả sản phẩm</a></li>
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
                                    <form method="post" id="sortForm">
                                        <select name="sortby" id="sortby" class="form-control" onchange="submitForm()">
                                            <option value="" <?= isset($_POST['sortby']) && $_POST['sortby'] == '' ? 'selected' : '' ?>>Chọn</option>
                                            <option value="lasted" <?= isset($_POST['sortby']) && $_POST['sortby'] == 'lasted' ? 'selected' : '' ?>>Mới nhất</option>
                                            <option value="pricedown" <?= isset($_POST['sortby']) && $_POST['sortby'] == 'pricedown' ? 'selected' : '' ?>>Giá từ cao đến thấp</option>
                                            <option value="priceup" <?= isset($_POST['sortby']) && $_POST['sortby'] == 'priceup' ? 'selected' : '' ?>>Giá từ thấp đến cao</option>
                                        </select>
                                    </form>

                                    <script>
                                        function submitForm() {
                                            document.getElementById("sortForm").submit();
                                        }
                                    </script>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="products mb-3">
                        <div class="row justify-content-center">
                            <?php foreach ($pro as $product) : ?>
                                <div class="col-6 col-md-4 col-lg-4">
                                    <div class="product product-7 text-center">
                                        <figure class="product-media">
                                            <span class="product-label label-new">New</span>
                                            <a href="detail_product.php?Id=<?= $product->id ?>">
                                                <img src="uploads/<?= $product->image ?>" alt="" class="product-image">
                                            </a>

                                            <div class="product-action">
                                                <a href="list_product.php?Id=<?= $product->id ?>&addcart=true" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->
                                        </figure><!-- End .product-media -->

                                        <div class="product-body">
                                            <?php foreach ($data as $cate) : ?>
                                                <?php if ($product->category_id == $cate->id) : ?>
                                                    <div class="product-cat">
                                                        <a href="product_category.php?cateid=<?= $cate->id ?>"><?= $cate->cate_name ?></a>
                                                    </div><!-- End .product-cat -->
                                                <?php endif; ?>
                                            <?php endforeach; ?>
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
                                <a class="page-link page-link-prev" href="list_product.php?page=<?= max($page - 1, 1) ?>" aria-label="Previous" tabindex="-1" aria-disabled="true">
                                    <span aria-hidden="true"><i class="icon-long-arrow-left"></i></span>Prev
                                </a>
                            </li>
                            <?php
                            // Tính toán vị trí của nút trang đầu tiên
                            $startPage = max(1, min($page - 1, $totalPage - 2));
                            // Hiển thị 3 nút trang từ $startPage
                            for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="list_product.php?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item-total">of <?= $totalPage ?></li>
                            <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                                <a class="page-link page-link-next" href="list_product.php?page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                                    Next <span aria-hidden="true"><i class="icon-long-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div><!-- End .col-lg-9 -->
                <aside class="col-lg-3 order-lg-first">
                    <div class="sidebar sidebar-shop">
                        <div class="widget widget-clean">
                            <label>Lọc sản phẩm:</label>
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
                                        <form method="post" id="categoryForm">
                                            <?php foreach ($data as $cate) : ?>
                                                <div class="filter-item">
                                                    <div>
                                                        <input type="radio" id="cate-<?= $cate->id ?>" name="category" value="<?= $cate->id ?>" onchange="submitFormCate()" <?= isset($_POST['category']) && $_POST['category'] == $cate->id ? 'checked' : '' ?>>
                                                        <label for="cate-<?= $cate->id ?>"><?= $cate->cate_name ?></label>
                                                    </div><!-- End .custom-checkbox -->
                                                    <span class="item-count"><?php echo Product::countProductByCateId($pdo, $cate->id) ?></span>
                                                </div><!-- End .filter-item -->
                                            <?php endforeach; ?>
                                        </form>
                                    </div><!-- End .filter-items -->

                                    <script>
                                        function submitFormCate() {
                                            document.getElementById("categoryForm").submit();
                                        }
                                    </script>
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->


                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true" aria-controls="widget-5">
                                    Khoảng giá
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-5">
                                <div class="widget-body">
                                    <div class="filter-price">
                                        <div id="price-slider">
                                            <form method="post" id="priceForm">
                                                
                                                <div class="filter-item">
                                                    <!--Dưới 10.000đ-->
                                                    <input type="radio" name="price-range" value="0-10000" onchange="submitFormPrice()" <?= isset($_POST['price-range']) && $_POST['price-range'] == '0-10000' ? 'checked' : '' ?>> Dưới 10.000đ
                                                </div>
                                                
                                                <div class="filter-item">
                                                    <!--10.000đ - 50.000đ-->
                                                    <input type="radio" name="price-range" value="10000-50000" onchange="submitFormPrice()" <?= isset($_POST['price-range']) && $_POST['price-range'] == '10000-50000' ? 'checked' : '' ?>> 10.000đ - 50.000đ
                                                </div>

                                                <div class="filter-item">
                                                     <!--Từ 100.000đ - 500.000đ-->
                                                    <input type="radio" name="price-range" value="100000-500000" onchange="submitFormPrice()" <?= isset($_POST['price-range']) && $_POST['price-range'] == '100000-500000' ? 'checked' : '' ?>> 100.000đ - 500.000đ
                                                </div>

                                               
                                               <div class="filter-item">
                                                    <!--Trên 500.000đ-->
                                                    <input type="radio" name="price-range" value="500000-1000000" onchange="submitFormPrice()" <?= isset($_POST['price-range']) && $_POST['price-range'] == '500000-1000000' ? 'checked' : '' ?>> Trên 500.000đ
                                               </div>
                                            </form>

                                            <script>
                                                function submitFormPrice() {
                                                    document.getElementById("priceForm").submit();
                                                }
                                            </script>



                                        </div><!-- End #price-slider -->
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