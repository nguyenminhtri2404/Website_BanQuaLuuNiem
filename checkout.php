<?php
$title = 'Trang đặt hàng';
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Cart.php';
require_once 'class/Orders.php';
require_once 'class/User.php';
require_once 'class/Auth.php';
require_once 'inc/init.php';
require_once 'inc/sendmail.php';

$conn = new Database();
$pdo = $conn->getConnect();

$checkLogin = Auth::checkLogin();

if (!$checkLogin) {
    header("location: login.php");
}

$user_id = $_SESSION['user_id'];
$errorrMessage = "";

$user = User::getUser($pdo, $user_id);
$cart = Cart::getCart($pdo, $user_id);

if (empty($cart)) {
    $errorrCart = "Chưa có sản phẩm nào trong giỏ hàng";
}

$sum_cart = 0;
foreach ($cart as $item) {
    $sum_cart += $item['total_cart'];
}

$errorrEmail = "";
$errorrPhone = "";
$errorrAddress = "";
$errorrName = "";
$errorrStreet = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $street = $_POST['street'];
    $order_note = $_POST['order_note'];
    $method = $_POST['method'];
    //var_dump($method);


    if (empty($method)) {
        $errorrMethod = "Vui lòng chọn phương thức thanh toán";
    }

    if (empty($email)) {
        $errorrEmail = "Vui lòng nhập email";
    }

    if (empty($phone)) {
        $errorrPhone = "Vui lòng nhập số điện thoại";
    }

    if (empty($address)) {
        $errorrAddress = "Vui lòng nhập địa chỉ";
    }

    if (empty($name)) {
        $errorrName = "Vui lòng nhập họ tên";
    }

    if (empty($street)) {
        $errorrStreet = "Vui lòng nhập số nhà, tên đường";
    }

    if (empty($errorrMessage) && empty($errorrEmail) && empty($errorrPhone) && empty($errorrAddress) && empty($errorrName) && empty($errorrStreet) && empty($errorrMethod)) {
        $addOrder = Orders::addOrder($pdo, $user_id, $email, $phone, $address, $order_note, $method);
        if ($addOrder) {

            //Thêm chi tiết đơn hàng
            $order_id = Orders::getLastID($pdo);
            foreach ($cart as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $total_price = $item['total_cart'];

                Orders::addOrderDetail($pdo, $order_id, $product_id, $quantity, $price, $total_price);
            }

            //Xóa giỏ hàng
            Cart::emptyCart($pdo, $user_id);
            //Gửi mail
            $email = new Mailer();
            $tieude = "Đơn hàng mới từ Triss Souvenir Shop";
            $noidung = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            </head>
            <body>
                <div>
                    <h2>Đơn hàng mới từ Triss Souvenir Shop</h2>
                    <h3>Xin chào {$user['name']},</h3>
                    <h3>Thông tin đơn hàng của bạn:</h3>
                    <table style='border: 1px solid black;border-collapse: collapse;'>
                <thead >
                    <tr>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Mã đơn hàng</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Địa chỉ</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Ngày đặt</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Tên sản phẩm</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Giá</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Số lượng</th>
                        <th style='border: 1px solid black;border-collapse: collapse;'>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>";
            $firstItem = true;
            foreach ($cart as $item) {
                $noidung .= "
                    <tr>";
                if ($firstItem) {
                    $noidung .= "<td rowspan='" . count($cart) . "' style='border: 1px solid black;border-collapse: collapse;'>{$order_id}</td>";
                    $noidung .= "<td rowspan='" . count($cart) . "' style='border: 1px solid black;border-collapse: collapse;'>{$address}</td>";
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $noidung .= "<td rowspan='" . count($cart) . "' style='border: 1px solid black;border-collapse: collapse;'>" . date('H:i:s d-m-Y') . "</td>";
                    $firstItem = false;
                }
                $noidung .= "
                    <td style='border: 1px solid black;border-collapse: collapse;'>{$item['pro_name']}</td>
                    <td style='border: 1px solid black;border-collapse: collapse;'>" . number_format($item['price']) . "</td>
                    <td style='border: 1px solid black;border-collapse: collapse;'>{$item['quantity']}</td>
                    <td style='border: 1px solid black;border-collapse: collapse;'>" . number_format($item['total_cart']) . "</td>               
                </tr>";
            }

            $noidung .= "
                <tr>
                    <td colspan='6' style='border: 1px solid black;border-collapse: collapse;'>Thành tiền</td>
                    <td style='border: 1px solid black;border-collapse: collapse;'>" . number_format($sum_cart) . "</td>
                </tr>
            </tbody>
            </table>
            <p>Chúng tôi sẽ liên hệ với bạn sớm nhất có thể để xác nhận đơn hàng.
            <br>
            Cảm ơn bạn đã mua hàng tại Triss Souvenir Shop❤️
            </p>

            </div>

            </body>
            </html>";
            $email->sendMail($user['email'], $tieude, $noidung);
            header("location: order_success.php");
        }
    }
}

?>

<?php require_once 'inc/header.php' ?>

<main class="main">
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Đặt hàng<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Giỏ hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đặt hàng</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="checkout">
            <div class="container">
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-9">
                            <h1 class="checkout-title">Chi tiết đơn đặt hàng</h1><!-- End .checkout-title -->

                            <label>Họ tên <span style="color: red;">*</span> </label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name'] ?>">
                            <span class="text-danger"><?php echo $errorrName ?></span> <br>


                            <label>Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $user['email'] ?>">
                            <span class="text-danger"><?php echo $errorrEmail ?></span> <br>

                            <label>Số điện thoại <span style="color: red;">*</span></label>
                            <input type="tel" class="form-control" name="phone" id="phone" value="<?php echo $user['phone'] ?>">
                            <span class="text-danger"><?php echo $errorrPhone ?></span> <br>

                            <label>Chọn địa chỉ <span style="color:red;">(Vui lòng chọn địa chỉ đầy đủ để shop gửi hàng đến đúng nơi ạ ^^)</span></label>
                            <div>
                                <select id="city" class="form-control">
                                    <option value="" selected>Chọn tỉnh thành</option>
                                </select>
                            </div>

                            <div>
                                <select id="district" class="form-control">
                                    <option value="" selected>Chọn quận huyện</option>
                                </select>

                            </div>

                            <div>
                                <select id="ward" class="form-control">
                                    <option value="" selected>Chọn phường xã</option>
                                </select>
                            </div>


                            <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
                            <script>
                                var citis = document.getElementById("city");
                                var districts = document.getElementById("district");
                                var wards = document.getElementById("ward");
                                var Parameter = {
                                    url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
                                    method: "GET",
                                    responseType: "application/json",
                                };
                                var promise = axios(Parameter);
                                promise.then(function(result) {
                                    renderCity(result.data);
                                });

                                function getSelectedAddress() {
                                    var selectedCity = citis.options[citis.selectedIndex].text;
                                    var selectedDistrict = districts.options[districts.selectedIndex].text; // Sửa tên biến thành 'districts'
                                    var selectedWard = wards.options[wards.selectedIndex].text;
                                    return selectedWard + ", " + selectedDistrict + ", " + selectedCity;
                                }

                                // Hàm cập nhật địa chỉ đầy đủ
                                function updateAddress() {
                                    var fullAddress = getSelectedAddress(); // Lấy địa chỉ đã chọn
                                    var street = document.getElementById("street").value.trim(); // Lấy giá trị của trường street

                                    // Nếu có giá trị của street và địa chỉ đã chọn
                                    if (street && fullAddress) {
                                        fullAddress = street + ", " + fullAddress; // Kết hợp street vào địa chỉ đã chọn
                                    }

                                    // Gán giá trị mới cho trường address
                                    document.getElementById("address").value = fullAddress;
                                }

                                function renderCity(data) {
                                    for (const x of data) {
                                        var opt = document.createElement('option');
                                        opt.value = x.Name;
                                        opt.text = x.Name;
                                        opt.setAttribute('data-id', x.Id);
                                        citis.options.add(opt);
                                    }
                                    citis.onchange = function() {
                                        districts.length = 1;
                                        wards.length = 1;
                                        if (this.options[this.selectedIndex].dataset.id != "") {
                                            const result = data.filter(n => n.Id === this.options[this.selectedIndex].dataset.id);

                                            for (const k of result[0].Districts) {
                                                var opt = document.createElement('option');
                                                opt.value = k.Name;
                                                opt.text = k.Name;
                                                opt.setAttribute('data-id', k.Id);
                                                districts.options.add(opt);
                                            }
                                        }
                                        document.getElementById("address").value = getSelectedAddress();
                                    };
                                    districts.onchange = function() {
                                        wards.length = 1;
                                        const dataCity = data.filter((n) => n.Id === citis.options[citis.selectedIndex].dataset.id);
                                        if (this.options[this.selectedIndex].dataset.id != "") {
                                            const dataWards = dataCity[0].Districts.filter(n => n.Id === this.options[this.selectedIndex].dataset.id)[0].Wards;

                                            for (const w of dataWards) {
                                                var opt = document.createElement('option');
                                                opt.value = w.Name;
                                                opt.text = w.Name;
                                                opt.setAttribute('data-id', w.Id);
                                                wards.options.add(opt);

                                            }
                                        }
                                        updateAddress(); // Gọi hàm updateAddress() sau khi quận/huyện được chọn
                                    };
                                    wards.onchange = function() {
                                        updateAddress();
                                    };
                                }
                            </script>

                            <!--Số nhà, tên đường, tên phố, tên khu vực-->
                            <label>Số nhà, tên tường <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="street" id="street" onblur="updateAddress()">
                            <span class="text-danger"><?php echo $errorrStreet ?></span> <br>



                            <label>Địa chỉ đầy chủ <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="address" id="address" value="<?php echo $user['address'] ?>">
                            <span class="text-danger"><?php echo $errorrAddress ?></span> <br>


                            <label>Ghi chú</label>
                            <textarea class="form-control" cols="30" rows="4" name="order_note" placeholder="Ví dụ: Nhờ shipper giao đến tận cửa giúp mình nha, cảm ơn ^^ "></textarea>
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-3">
                            <div class="summary">
                                <h3 class="summary-title">Sản phẩm đã chọn</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Tổng tiền</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($errorrCart)) : ?>
                                            <tr>
                                                <td colspan="2">
                                                    <h6 class="text-danger"><?php echo $errorrCart ?></h6>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php foreach ($cart as $item) : ?>
                                            <tr>
                                                <td><a href="#"><?php echo $item['pro_name'] ?></a></td>
                                                <td><?php echo number_format($item['total_cart']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>


                                        <tr>
                                            <td>Shipping:</td>
                                            <td>Free shipping</td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td><?php echo number_format($sum_cart) ?></td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->

                                <div class="accordion-summary" id="accordion-payment">
                                    <h6>Phương thức thanh toán:</h6>
                                    <select class="form-control form-select-lg w-100" style="height: 45px;" name="method" id="method">
                                        <option value="" selected>Chọn phương thức thanh toán</option>
                                        <option value="1">Thanh toán khi nhận hàng COD</option>
                                        <option value="2">Chuyển khoản</option>
                                    </select>
                                    <span class="text-danger"><?php echo $errorrMessage ?></span>


                                </div><!-- End .card -->
                            </div><!-- End .accordion -->

                            <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                <span class="btn-text">Đặt hàng</span>
                                <span class="btn-hover-text">Tiến hành đặt hàng</span>
                            </button>
                    </div><!-- End .summary -->
                    </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
            </form>
        </div><!-- End .container -->
    </div><!-- End .checkout -->
    </div><!-- End .page-content -->
</main><!-- End .main -->

<?php require_once 'inc/footer.php' ?>