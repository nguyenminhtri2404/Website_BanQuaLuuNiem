<?php
require_once '../class/Database.php';
require_once '../class/User.php';
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

$data = User::getAll($pdo);

$ppp = 4;
$limit = $ppp;
$offset = ($page - 1) * $ppp;

$totalUsers = count($data);

$totalPage = ceil($totalUsers / $ppp);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    $user = User::getOneUserById($pdo, $userId);
    if (!$user) {
        die("ID không hợp lệ");
    }

    $count = User::checkUserInOrder($pdo, $userId);
    if ($count == 0) {
        User::deleteUser($pdo, $userId);
    } else {
        $error = "Không thể xóa tài khoản đã có trong đơn hàng!";
    }
}

$data = User::pagination($pdo, $limit, $offset);
?>

<?php require_once 'inc/header.php'; ?>
<main>
    <div class="container-fuld px-2 mx-3">
        <h2 class="text-center text-primary">Quản lý tài khoản</h2>
        <a class="btn btn-success" href="new_user.php"> + Thêm tài khoản</a>
        <table class="mt-2 table table-striped table-hove">
            <thead>
                <tr>
                    <th class="table-info text-center text-black">Tên user</th>
                    <th class="table-info text-center">Email</th>
                    <th class="table-info text-center">Số điện thoại</th>
                    <th class="table-info text-center">Địa chỉ</th>
                    <th class="table-info text-center">Vai trò</th>
                    <th class="table-info text-center">Tác vụ</th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($data as $key => $user) : ?>
                    <tr>
                        <td class="text-center"><?php echo $user['name']; ?></td>
                        <td class="text-center"><?php echo $user['email']; ?></td>
                        <td class="text-center"><?php echo $user['phone']; ?></td>
                        <td class="text-center"><?php echo $user['address']; ?></td>
                        <td class="text-center"><?php echo $user['role']; ?></td>
                        <td class="text-center">
                            <?php if ($user['role'] != 'admin') : ?>
                                <a href="edit_user.php?user_id=<?php echo $user['id'] ?>" class="btn btn-outline-primary">Sửa</a>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $user['id'] ?>" data-name="<?= $user['name'] ?>">Xóa</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_TaiKhoan.php?page=<?= max($page - 1, 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Tính toán vị trí của nút trang đầu tiên
                $startPage = max(1, min($page - 1, $totalPage - 2));
                // Hiển thị 3 nút trang từ $startPage
                for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="ql_TaiKhoan.php?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                    <a class="page-link" href="ql_TaiKhoan.php?page=<?= min($page + 1, $totalPage) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa tài khoản</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa tài khoản <strong id="userName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form id="deleteForm" method="post" action="">
                            <input type="hidden" name="userId" id="userId">
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
                    var userId = button.getAttribute('data-id');
                    var userName = button.getAttribute('data-name');
                    var modalBodyuserName = deleteModal.querySelector('#userName');
                    var modalForm = deleteModal.querySelector('#deleteForm');
                    var modalInputId = deleteModal.querySelector('#userId');

                    modalBodyuserName.textContent = userName;
                    modalInputId.value = userId;
                });
            });
        </script>


    </div>
</main>


<?php require_once 'inc/footer.php'; ?>