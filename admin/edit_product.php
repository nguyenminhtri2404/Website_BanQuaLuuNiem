<?php
if (!isset($_GET['Id'])) {
    die("Cần cung cấp thông tin sản phẩm");
}

$id = $_GET['Id'];

require_once '../class/Database.php';
require_once '../class/Product.php';
require_once '../class/Auth.php';
require_once '../class/Category.php';
require_once "../inc/init.php"; 

$conn = new Database();
$pdo = $conn->getConnect();
$product = Product::getOneProductById($pdo, $id);
$data = Category::getAll($pdo);

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}




if (!$product) {
    die("id không hợp lệ");
}

$nameError = '';
$priceError = '';
$descError = '';
$quantityError = '';
$imageError = '';
$categoryError = '';
$name = '';
$price = '';
$desc = '';
$quantity = '';
$image = '';
$imageError = '';
$old_image = $product->image;

if ($_SERVER["REQUEST_METHOD"] == "POST") //kiểm tra có submit không
{
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $desc = $_POST['desc'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image'];


    //Xử lí form
    if (empty($name)) {
        $nameError = "Phải nhập tên";
    }

    if (empty($desc)) {
        $descError = "Phải nhập mô tả";
    }

    if (empty($quantity)) {
        $quantityError = "Phải nhập số lượng";
    }

    if (empty($price)) {
        $priceError = "Phải nhập giá";
    } elseif ($price % 1000 != 0) {
        $priceError = "Gía phải chia hết cho 1000";
    }

    if (empty($category_id)) {
        $categoryError = "Phải chọn loại sản phẩm";
    }

    //Xử lí ảnh
    if (empty($image['name'])) {
        //$id= $product->id;
        Product::editProduct($pdo, $id, $name, $price, $quantity, $desc, $old_image, $category_id);
    } else {
        //Xu ly hinh anh
        try {
            switch ($image['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    //throw new Exception("Không có file nào được tải lên");
                    break;
                default:
                    throw new Exception("Lỗi không xác định");
                    break;
            }

            if ($image['size'] > 4000000) {
                throw new Exception("Ảnh phải nhỏ hơn 4MB");
            }

            $mime_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $image['tmp_name']);
            if (!in_array($mime_type, $mime_types)) {
                throw new Exception("Chỉ chấp nhận ảnh jpg, jpeg, png");
            }
            $path_info = pathinfo($image['name']);
            $file_name = 'image';
            $extention = $path_info['extension'];
            $destination = '../uploads/' . $file_name . '.' . $extention;
            $i = 1;
            while (file_exists($destination)) {
                $destination = '../uploads/' . $file_name . "-$i" . '.' . $extention; //nếu trùng tên thì thêm "-$i
                $i++;
            }
            if (!move_uploaded_file($image['tmp_name'], $destination)) {
                throw new Exception("Không thể lưu file");
            }

            // Lưu tên file mới vào biến $new_image_name
            $new_image_name = basename($destination);
        } catch (Exception $e) {
            $imageError = $e->getMessage();
        }


        if (!$nameError && !$descError && !$priceError && !$quantityError && !$imageError && !$categoryError) {
            Product::editProduct($pdo, $id, $name, $price, $quantity, $desc, $new_image_name, $category_id);
        }
    }
}


?>

<?php require_once "inc/header.php" ?>

<h2 class="text-center text-primary">Chỉnh sửa sản phẩm</h2>
<form class="w-50 m-auto" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm</label>
        <input class="form-control" id="name" name="name" type="text" value="<?= $product->pro_name ?>">
        <span class="text-danger fw-bold"><?= $nameError ?></span>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Giá bán</label>
        <input class="form-control" id="price" name="price" type="text" value="<?= $product->price ?>">
        <span class="text-danger fw-bold"><?= $priceError ?></span>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Số lượng</label>
        <input class="form-control" id="quantity" name="quantity" type="text" value="<?= $product->quantity ?>">
        <span class="text-danger fw-bold"><?= $quantityError ?></span>
    </div>

    <div class="mb-3">
        <label for="desc" class="form-label">Mô tả sản phẩm</label>
        <textarea class="form-control" id="desc" name="desc"><?= $product->description ?></textarea>
        <span class="text-danger fw-bold"><?= $descError ?></span>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Hình</label>
        <br>
        <img src="../uploads/<?= $product->image ?>" id="preview" alt="Preview Image" style="max-width: 200px; max-height: 200px;">
        <input class="form-control" id="image" name="image" type="file" value="<?= $product->image ?>" onchange="previewImage(event)">
        <span class="text-danger fw-bold"><?= $imageError ?></span>

    </div>

    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            var imgElement = document.getElementById('preview');

            reader.onload = function() {
                imgElement.src = reader.result;
                imgElement.style.display = 'block'; // Hiển thị thẻ img
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            } else {
                imgElement.style.display = 'none'; // Ẩn thẻ img nếu không có hình ảnh
            }
        }
    </script>

    <div class="mb-3">
        <label for="category_id" class="form-label">Loại sản phẩm</label>
        <br>
        <select id="category_id" name="category_id" class="form-select">
            <?php foreach ($data as $category) : ?>
                <option value="<?= $category->id ?>" <?php if ($product->category_id == $category->id) echo 'selected' ?>><?= $category->cate_name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-outline-secondary m-auto"> Sửa sản phẩm</button>
</form>


<?php require_once "inc/footer.php" ?>