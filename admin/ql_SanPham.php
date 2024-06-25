<?php
$title = 'Home page';
require_once '../class/Database.php';
require_once '../class/Product.php';
require_once "../inc/init.php";

$conn = new Database();
$pdo = $conn->getConnect();
$data = Product::getAll($pdo);

if (empty($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$ppp = 4;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalProducts = count($data);

$totalPage = ceil($totalProducts / $ppp);



if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productId'])) {
    $productId = $_POST['productId'];
    var_dump($productId);

    $product = Product::getOneProductById($pdo, $productId);
    if (!$product) {
        die("ID không hợp lệ");
    }

    $count = Product::checkProductInOrderDetail($pdo, $productId);
    if ($count == 0) {
        $result = Product::deleteProduct($pdo, $productId);
        if ($result) {
            echo "<script>
                alert('Xóa sản phẩm thành công!');
                setTimeout(function(){
                    window.location.href = 'ql_SanPham.php';
                }, 0);
            </script>";
        } else {
            $error = "Xóa sản phẩm thất bại!";
        }
    } else {
        $error = "Không thể xóa sản phẩm đã có trong chi tiết đơn hàng!";
    }
}


$data = Product::pagination($pdo, $limit, $offset);

?>

<?php require_once "inc/header.php" ?>
<main>
    <div class="container-fluid">
        <h1 class="text-center text-primary">Danh sách sản phẩm</h1>
        <a class="btn btn-success" href="new_product.php"> + Thêm sản phẩm</a>
        <table class="table table-striped table-bordered mt-2">
            <thead>
                <tr>
                    <th class="table-info">Tên sản phẩm</th>
                    <th class="table-info">Giá</th>
                    <th class="table-info">Hình</th>
                    <th class="table-info">Tác vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $product) : ?>
                    <tr>
                        <td><?= $product->pro_name ?></td>
                        <td><?= number_format($product->price, 0, ',', '.') ?> VNĐ</td>
                        <td><img src="../uploads/<?= $product->image ?>" alt="" width="100" height="100" style="object-fit: cover;"></td>
                        <td>
                            <a class="btn btn-primary" href="edit_product.php?Id=<?= $product->id ?>">Sửa</a>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $product->id ?>" data-name="<?= $product->pro_name ?>">Xóa</button>
                            <a class="btn btn-outline-secondary" href="details_product.php?Id=<?= $product->id ?>">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_SanPham.php?page=<?= max($page - 1, 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Tính toán vị trí của nút trang đầu tiên
                $startPage = max(1, min($page - 1, $totalPage - 2));
                // Hiển thị 3 nút trang từ $startPage
                for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="ql_SanPham.php?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_SanPham.php?page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>


        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa sản phẩm <strong id="productName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form id="deleteForm" method="post" action="">
                            <input type="hidden" name="productId" id="productId">
                            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($error)) : ?>
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="errorModalLabel">Lỗi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?= $error ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var errorModalElement = document.getElementById('errorModal');
                    if (errorModalElement) {
                        var errorModal = new bootstrap.Modal(errorModalElement, {
                            keyboard: false
                        });
                        errorModal.show();
                    }
                });
            </script>

        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var deleteModal = document.getElementById('deleteModal');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var productId = button.getAttribute('data-id');
                    var productName = button.getAttribute('data-name');
                    var modalBodyproductName = deleteModal.querySelector('#productName');
                    var modalForm = deleteModal.querySelector('#deleteForm');
                    var modalInputId = deleteModal.querySelector('#productId');

                    modalBodyproductName.textContent = productName;
                    modalInputId.value = productId;
                });
            });
        </script>

    </div>
</main>

<?php require_once "inc/footer.php" ?>