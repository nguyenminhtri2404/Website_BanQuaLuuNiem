<?php
$title = 'Quản lý danh mục';

require '../class/Database.php';
require '../class/Product.php';
require '../class/Auth.php';
require '../class/Category.php';
require "../inc/init.php";

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
$cate = Category::getAll($pdo);


if (empty($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$ppp = 4;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalProducts = count($cate);

$totalPage = ceil($totalProducts / $ppp);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $category = Category::getOneCategoryById($pdo, $id);
    if (!$category) {
        die("ID không hợp lệ");
    }

    $count = Category::checkCategoryInProduct($pdo, $id);
    if ($count == 0) {
        Category::deleteCategory($pdo, $id);
    } else {
        $error = "Không thể xóa danh mục đã có trong sản phẩm!";
    }
}

$cate = Category::pagination($pdo, $limit, $offset);


?>

<?php require_once "inc/header.php" ?>
<main>
    <div class="container-fluid">
        <h1 class="text-center text-primary">Danh mục sản phẩm</h1>
        <a class="btn btn-success" href="new_category.php"> + Thêm danh mục</a>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th class="table-info">Tên danh mục</th>
                    <th class="table-info">Tác vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cate as $category) : ?>
                    <tr>
                        <td><?= $category->cate_name ?></td>
                        <td>
                            <a class="btn btn-primary" href="edit_category.php?Id=<?= $category->id ?>">Sửa</a>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $category->id ?>" data-name="<?= $category->cate_name ?>">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_DanhMuc.php?page=<?= max($page - 1, 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Tính toán vị trí của nút trang đầu tiên
                $startPage = max(1, min($page - 1, $totalPage - 2));
                // Hiển thị 3 nút trang từ $startPage
                for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="ql_DanhMuc.php?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_DanhMuc.php?page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Bootstrap Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa danh mục <strong id="categoryName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form id="deleteForm" method="post" action="">
                            <input type="hidden" name="id" id="categoryId">
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


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var categoryId = button.getAttribute('data-id');
                var categoryName = button.getAttribute('data-name');
                var modalBodyCategoryName = deleteModal.querySelector('#categoryName');
                var modalForm = deleteModal.querySelector('#deleteForm');
                var modalInputId = deleteModal.querySelector('#categoryId');

                modalBodyCategoryName.textContent = categoryName;
                modalInputId.value = categoryId;
            });
        });
    </script>
</main>

<?php require_once "inc/footer.php" ?>