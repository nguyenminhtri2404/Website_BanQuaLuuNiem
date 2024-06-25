<?php
$title = 'Trang giỏ hàng';
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Cart.php';
require_once 'class/Auth.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();

$product = Product::getAll($pdo);
$checkLogin = Auth::checkLogin();

if (!$checkLogin) {
    header("location: login.php");
}

$user_id = $_SESSION['user_id'];
$cart = Cart::getCart($pdo, $user_id);
$product = Product::getAll($pdo);



//Nếu người dùng chưa có giỏ hàng thì hiển thị thông báo chưa có sản phẩm nào
if (!$cart) {
    $empty_cart = "Chưa có sản phẩm nào trong giỏ hàng";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($cart as $c) {
        $pro_id = $c['id'];
        $price = $_POST['price'][$pro_id];
        $quantity = $_POST['quantity'][$pro_id];
        $total_cart = $price * $quantity;
        Cart::updateCart($pdo, $user_id, $pro_id, $quantity, $total_cart);
    }
    header("location: cart.php");
}

if (isset($_GET['delete_cart'])) {
    $pro_id = $_GET['pro_id'];
    Cart::deleteCart($pdo, $user_id, $pro_id);
    header("location: cart.php");
}

if (isset($_GET['emptycart'])) {
    Cart::emptyCart($pdo, $user_id);
    header("location: cart.php");
}

$sum_cart = 0;
foreach ($cart as $c) {
    $sum_cart += $c['total_cart'];
}







?>

<?php require_once 'inc/header.php' ?>

<main class="main">
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Giỏ hàng của bạn<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="list_product.php">List sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="cart">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <form method="POST">
                            <?php if (isset($empty_cart)) : ?>
                                <h4 class="arlert text-center"><?php echo $empty_cart ?></h4>
                            <?php endif; ?>
                            <table class="table table-cart table-mobile">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th>Xóa</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($cart as $c) : ?>
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="uploads/<?php echo $c['image'] ?>" alt="Product image">
                                                            <!-- <img src="assets/images/products/table/product-1.jpg" alt="Product image"> -->
                                                        </a>
                                                    </figure>

                                                    <h3 class="product-title">
                                                        <a href="#"><?php echo $c['pro_name'] ?></a>
                                                    </h3><!-- End .product-title -->
                                                </div><!-- End .product -->
                                            </td>
                                            <td class="price-col">
                                                <input style="border:none;outline: none;" type="text" id="price" name="price[<?php echo $c['id'] ?>]" value="<?php echo $c['price'] ?>" readonly>
                                            </td>
                                            <td class="quantity-col">
                                                <div class="cart-product-quantity">
                                                    <input type="number" class="form-control" name="quantity[<?php echo $c['id'] ?>]" id="quantity" value="<?php echo $c['quantity'] ?>" min="1" step="1" data-decimals="0" required>
                                                </div><!-- End .cart-product-quantity -->
                                            </td>
                                            <td class="total-col">
                                                <input style="border:none;outline: none;" type="text" id="total_cart" name="total_cart" value="<?php echo $c['total_cart']; ?>" readonly>
                                            </td>
                                            <td class="remove-col"><a href="cart.php?delete_cart=true&pro_id=<?php echo $c['id'] ?>" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table><!-- End .table table-wishlist -->

                            <div class="cart-bottom">
                                <div class="cart-discount">
                                    <a href="cart.php?emptycart" class="btn btn-outline-danger" type="submit"> <span class="p-2"> Xóa toàn bộ giỏ hàng </span> </a>
                                </div>

                                <button type="submit" class="btn btn-outline-primary-2"><span class="p-2">Cập nhật lại giỏ</span><i class="icon-refresh"></i></button>
                            </div><!-- End .cart-bottom -->
                        </form>


                    </div><!-- End .col-lg-9 -->
                    <aside class="col-lg-3">
                        <div class="summary summary-cart">
                            <h3 class="summary-title">Tổng giỏ hàng</h3><!-- End .summary-title -->

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-subtotal">
                                        <td>Tổng tiền:</td>
                                        <td><?php echo number_format($sum_cart, 0, ',', '.') ?> VNĐ</td>
                                    </tr><!-- End .summary-subtotal -->
                                </tbody>
                            </table><!-- End .table table-summary -->

                            <?php
                            if (empty($cart)) {
                                echo '<a href="list_product.php" hidden class="btn btn-outline-dark-2 btn-block mb-3"><span>TIẾP TỤC MUA</span><i class="icon-refresh"></i></a>';
                            } else {
                                echo '<a href="checkout.php" class="btn btn-outline-primary-2 btn-order btn-block" >ĐẾN TRANG ĐẶT HÀNG</a>';
                            }
                            ?>
                        </div><!-- End .summary -->

                        <a href="list_product.php" class="btn btn-outline-dark-2 btn-block mb-3"><span>TIẾP TỤC MUA</span><i class="icon-refresh"></i></a>
                    </aside><!-- End .col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
</main><!-- End .main -->

<?php require_once 'inc/footer.php' ?>