<?php
require_once '../class/Database.php';
require_once '../class/Auth.php';
require_once '../class/Product.php';
require_once '../class/Category.php';
require_once "../inc/init.php";

$conn = new Database();
$pdo = $conn->getConnect();
$data = Category::getAll($pdo);

if (!(isset($_SESSION['logged_user'])) || $_SESSION['role'] != "admin") {
    echo "<script>
                alert('Bạn cần đăng nhập để truy cập trang này hoặc bạn không có quyền truy cập vào trang này!');
                setTimeout(function(){
                    window.location.href = '../login.php';
                }, 0);
         </script>";
}



$nameError = '';
$priceError = '';
$descError = '';
$quantityError = '';
$categoryError = '';
$imageError = '';
$name = '';
$price = '';
$desc = '';
$quantity = '';
$image = '';
$category_id = '';


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
        $priceError = "Giá phải chia hết cho 1000";
    }

    if (empty($category_id)) {
        $categoryError = "Phải chọn loại sản phẩm";
    }
    
    //Xử lí ảnh
    try{
        if (empty($image['name'])) {
            throw new Exception("Phải chọn ảnh");
        }
        
        switch ($image['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception("Không có file nào được tải lên");
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

     
    }
    catch(Exception $e){
        $imageError = $e->getMessage();
    }


    //Kiểm tra không có lỗi thì thêm vào database
    if (!$nameError && !$descError && !$priceError && !$quantityError && !$imageError && !$categoryError) {
        Product::addProduct($pdo, $name, $price, $quantity, $desc, $new_image_name, $category_id);
    }
}


?>
<?php require_once "inc/header.php" ?>
<h2 class="text-center text-primary">Thêm sản phẩm mới</h2>
<form class="w-50 m-auto" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm</label>
        <input class="form-control" id="name" name="name" type="text" value="<?= $name ?>">
        <span class="text-danger fw-bold"><?= $nameError ?></span>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Giá bán</label>
        <input class="form-control" id="price" name="price" type="text" value="<?= $price ?>">
        <span class="text-danger fw-bold"><?= $priceError ?></span>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Số lượng</label>
        <input class="form-control" id="quantity" name="quantity" type="text" value="<?= $quantity ?>">
        <span class="text-danger fw-bold"><?= $quantityError ?></span>
    </div>

    <div class="mb-3">
        <label for="desc" class="form-label">Mô tả sản phẩm</label>
        <textarea class="form-control" id="desc" name="desc"><?= $desc ?></textarea>
        <span class="text-danger fw-bold"><?= $descError ?></span>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Hình</label>
        <img id="preview" alt="Preview Image" style="max-width: 200px; max-height: 200px; display: none;">
        <input class="form-control w-50" id="image" name="image" type="file" onchange="previewImage(event)">
        <span class="text-danger fw-bold"><?= $priceError ?></span>
        
    </div>

    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            var imgElement = document.getElementById('preview');

            reader.onload = function () {
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
        <option value="" selected>Chọn loại sản phẩm</option>
            <?php foreach ($data as $category) : ?>
                <option value="<?= $category->id ?>" <?php if ($category_id == $category->id) echo 'selected' ?>><?= $category->cate_name ?></option>
            <?php endforeach; ?>
        </select>
        <span class="text-danger fw-bold"><?= $categoryError ?></span>
    </div>
    <button type="submit" class="btn btn-outline-primary m-auto"> Thêm sản phẩm</button>
</form>

<?php require_once "inc/footer.php" ?>