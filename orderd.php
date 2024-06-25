<?php

$title = 'Đơn hàng của bạn';
require_once 'class/Database.php';
require_once 'class/Product.php';
require_once 'class/Cart.php';
require_once 'class/Orders.php';
require_once 'class/User.php';
require_once 'class/Auth.php';
require_once 'inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();

$checkLogin = Auth::checkLogin();

if (!$checkLogin) {
    header("location: login.php");
}

$user_id = $_SESSION['user_id'];


$infoOrder = Orders::getOrderDetail($pdo, $user_id);
$sum_order = 0;
foreach ($infoOrder as $item) {
    $sum_order += $item['total_price'];
}

$product = Product::getAll($pdo);

?>

<?php require_once 'inc/header.php'; ?>
<div class="container-fuld px-2 mx-3">

    <h2 class="text-center text-primary">Đơn hàng của bạn</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="table-info text-center text-black">Mã đơn hàng</th>
                <th class="table-info text-center">Tên sản phẩm</th>
                <th class="table-info text-center">Ảnh sản phẩm</th>
                <th class="table-info text-center">Giá</th>
                <th class="table-info text-center">Số lượng</th>
                <th class="table-info text-center">Tổng tiền</th>
                <th class="table-info text-center">Ngày đặt</th>
                <th class="table-info text-center">Phương thức thanh toán</th>
                <th class="table-info text-center">Trạng thái đơn hàng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentOrderId = null;
            foreach ($infoOrder as $order_detail) :
                if ($currentOrderId !== $order_detail['order_id']) {
                    $currentOrderId = $order_detail['order_id'];
                    $firstRow = true;
                    $rowCount = array_reduce($infoOrder, function ($carry, $item) use ($currentOrderId) {
                        return $carry + ($item['order_id'] === $currentOrderId ? 1 : 0);
                    }, 0);
                } else {
                    $firstRow = false;
                }
            ?>
                <tr>
                    <?php if ($firstRow) : ?>
                        <td class="text-center" rowspan="<?= $rowCount ?>"><?= $order_detail['order_id'] ?></td>
                    <?php endif ?>
                    <td class="text-center"><?= $order_detail['pro_name'] ?></td>
                    <td class="text-center"><img src="uploads/<?= $order_detail['image'] ?>" width="100px" height="100px" style="object-fit: cover;"></td>
                    <td class="text-center"><?= number_format($order_detail['price'], 0, ",", ".") ?> VNĐ</td>
                    <td class="text-center"><?= $order_detail['quantity'] ?></td>
                    <td class="text-center"><?= number_format($order_detail['total_price'], 0, ",", ".") ?> VNĐ</td>
                    <?php if ($firstRow) : ?>
                        <td class="text-center" rowspan="<?= $rowCount ?>"><?= date_format(date_create($order_detail['order_date']), 'd/m/Y H:i:s') ?></td>
                        <td class="text-center" rowspan="<?= $rowCount ?>"><?= $order_detail['method'] == 1 ? 'Thanh toán khi nhận hàng COD' : 'Chuyển khoản' ?></td>
                        <td class="text-center" rowspan="<?= $rowCount ?>"><?= $order_detail['status'] == 0 ? '<span class="text-danger">Chờ xác nhận</span>' : '<span class="text-success">Đã xác nhận</span>' ?></td>
                    <?php endif ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h4 class="text-center text-danger">Tổng tiền: <?php echo number_format($sum_order); ?> VNĐ</h4>

</div>

<?php require_once 'inc/footer.php'; ?>