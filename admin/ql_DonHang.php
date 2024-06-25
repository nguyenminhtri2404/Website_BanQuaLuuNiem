<?php
require_once '../class/Database.php';
require_once '../class/Orders.php';
require_once '../class/Auth.php';
require_once '../inc/init.php';

$conn = new Database();
$pdo = $conn->getConnect();

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}

if (empty($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$orders = Orders::getAllOrderDetail($pdo);

$ppp = 8;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalOrders = count($orders);

$totalPage = ceil($totalOrders / $ppp);

$orders = Orders::pagination($pdo, $limit, $offset);
//var_dump($orders);


?>
<?php require_once 'inc/header.php'; ?>
<main>
    <div class="container-fuld px-2 mx-3">
        <h2 class="text-center text-primary">Danh sách đơn đặt hàng</h2>
        <table class="table table-bordered table-striped table-hove">
            <thead>
                <tr>
                    <th class="table-info text-center text-black">Mã đơn hàng</th>
                    <th class="table-info text-center">Tên khách hàng</th>
                    <th class="table-info text-center">Ngày đặt</th>
                    <th class="table-info text-center">Trạng thái</th>
                    <th class="table-info text-center">Xem chi tiết đơn</th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($orders as $key => $order) : ?>
                    <tr>
                        <td class="text-center"><?php echo $order['order_id']; ?></td>
                        <td class="text-center"><?php echo $order['name']; ?></td>
                        <td class="text-center"><?php echo date_format(date_create($order['order_date']), 'd/m/Y H:i:s'); ?></td>
                        <td class="text-center"><?php echo $order['status'] == 1 ? 'Đã xác nhận' : 'Chưa xác nhận'; ?></td>
                        <td class="text-center">
                            <a href="order_detail.php?order_id=<?php echo $order['order_id'] ?>" class="btn btn-outline-primary">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_DonHang.php?page=<?= max($page - 1, 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Tính toán vị trí của nút trang đầu tiên
                $startPage = max(1, min($page - 1, $totalPage - 2));
                // Hiển thị 3 nút trang từ $startPage
                for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="ql_DonHang.php?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_DonHang.php?page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

</main>
<?php require_once 'inc/footer.php'; ?>