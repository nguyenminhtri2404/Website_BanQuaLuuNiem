<?php
require_once '../class/Database.php';
require_once '../class/Orders.php';
require_once '../class/User.php';
require_once '../class/Auth.php';
require_once '../class/Product.php';
require_once "../inc/init.php";

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

$countUser = User::countUser($pdo);
$countOrder = Orders::countOrder($pdo);
$sumTotal = Orders::sumTotal($pdo);
$countProduct = Product::countProduct($pdo);

if (isset($_POST['year'])) {
    $year = $_POST['year'];
} else {
    $year = date('Y');
}

$ordersByMonth = [];
for ($i = 1; $i <= 12; $i++) {
    $ordersByMonth[] = Orders::countOrderByMonth($pdo, $i);
}

$orderNotConfirm = Orders::getAllOrderDetailNotConfirm($pdo);

?>

<?php require_once "inc/header.php" ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center text-primary">Thống Kê</h1>
        <div class="row mt-4">
            <div class="col-xl-3 col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body text-primary fs-5">
                        Tổng số đơn hàng
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="card-footer ">
                        <span class="fs-5"><?= $countOrder ?></span>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body text-warning fs-5">Số lượng tài khoản
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="card-footer">
                        <span class="small fs-5"><?= $countUser ?></span>


                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body text-success fs-5">Tổng doanh thu
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="card-footer">
                        <span class="small fs-5"><?= number_format($sumTotal, 0, ',', '.') ?> VNĐ</span>


                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card mb-4 text-center">
                    <div class="card-body text-danger fs-5">Số lượng sản phẩm
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <div class="card-footer">
                        <span class="small fs-5"><?= $countProduct ?></span>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Số đơn hàng theo tháng
                    </div>
                    <div class="card-body"><canvas id="Chart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('Chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    datasets: [{
                        label: '# Đơn hàng',
                        data: <?php echo json_encode($ordersByMonth); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh sách đơn hàng mới nhất
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
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
                        <?php foreach ($orderNotConfirm as $key => $order) : ?>
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
            </div>
        </div>
    </div>
</main>
<?php require_once "inc/footer.php" ?>