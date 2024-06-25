<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../class/Database.php';
require_once '../class/Orders.php';
require_once '../class/Product.php';
require_once '../class/Auth.php';
require_once '../inc/init.php';


$conn = new Database();
$pdo = $conn->getConnect();

$checkLogin = Auth::checkLogin();

if (!$checkLogin) {
    header("location: login.php");
}

$orders_id = $_GET['order_id'];


$orders_detail = Orders::getOrderDetailById($pdo, $orders_id);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    var_dump($status);
    Orders::updateStatus($pdo, $orders_id, $user_id, $status);
    header("location: ql_DonHang.php");
}

?>
<?php require_once 'inc/header.php'; ?>
<h1 class="text-center text-primary">Chi tiết đơn hàng</h1>
<form method="POST">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="table-info text-center text-black">Mã đơn hàng</th>
                <th class="table-info text-center">Tên khách hàng</th>
                <th class="table-info text-center">Địa chỉ</th>
                <th class="table-info text-center">Tên sản phẩm</th>
                <th class="table-info text-center">Giá</th>
                <th class="table-info text-center">Số lượng</th>
                <th class="table-info text-center">Tổng tiền</th>
                <th class="table-info text-center">Ngày đặt</th>
                <th class="table-info text-center">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentOrderId = null;
            foreach ($orders_detail as $order_detail) :
                if ($currentOrderId !== $order_detail['order_id']) {
                    $currentOrderId = $order_detail['order_id'];
                    $firstRow = true;
                } else {
                    $firstRow = false;
                }
            ?>
                <tr>
                    <?php if ($firstRow) : ?>
                        <td class="text-center" rowspan="<?= count($orders_detail) ?>"><?= $order_detail['order_id'] ?></td>
                        <td class="text-center" rowspan="<?= count($orders_detail) ?>"><?= $order_detail['name'] ?></td>
                        <td class="text-center" rowspan="<?= count($orders_detail) ?>"><?= $order_detail['address'] ?></td>
                    <?php endif; ?>
                    <td class="text-center"><?= $order_detail['pro_name'] ?></td>
                    <td class="text-center"><?= number_format($order_detail['price'], 0, ",", ".") ?></td>
                    <td class="text-center"><?= $order_detail['quantity'] ?></td>
                    <td class="text-center"><?= number_format($order_detail['total_price'], 0, ",", ".") ?></td>
                    <?php if ($firstRow) : ?>
                        <td class="text-center" rowspan="<?= count($orders_detail) ?>"><?= $order_detail['order_date'] ?></td>
                        <td class="text-center" rowspan="<?= count($orders_detail) ?>">
                            <select name="status" id="status" class="form-select">
                                <option value="0" <?php if ($order_detail['status'] == 0) echo 'selected' ?>>Chờ xác nhận</option>
                                <option value="1" <?php if ($order_detail['status'] == 1) echo 'selected' ?>>Đã xác nhận</option>
                            </select>
                        </td>
                        <input type="hidden" name="user_id" value="<?= $order_detail['user_id'] ?>">
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary mx-3">Cập nhật trạng thái</button>

</form>



<?php require_once 'inc/footer.php'; ?>