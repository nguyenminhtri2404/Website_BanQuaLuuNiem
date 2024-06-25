
<?php 
$title = 'Về chúng tôi';
require_once 'inc/header.php'
?>

<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Về chúng tôi</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->
    <div class="container">
        <div class="page-header page-header-big text-center" style="background-image: url('assets/images/about_banner.png'); ">
            <h1 class="page-title text-white" style="z-index: 2; position: absolute;">About</h1>
        </div><!-- End .page-header -->
    </div><!-- End .container -->

    <div class="page-content pb-0">
        <div class="container">
            <h3 class="text-center">Tên đồ án: Website cửa hàng bán quà lưu niệm Triss Souvenir Shop</h3>
            <h3 class="text-center">GVHD: Đinh Nguyễn Trọng Nghĩa</h3>
            <h3 class="text-center">Sinh viên thực hiện: Nguyễn Minh Trí</h3>
            <h3 class="text-center">Mã sinh viên: 2001216237</h3>
            <h5 class="text-primary">Mô tả đồ án</h5>
            <p>Triss Souvenir Shop là một dự án cuối kỳ nhằm xây dựng một website bán hàng trực tuyến chuyên cung cấp các sản phẩm quà lưu niệm. Mục tiêu của dự án là tạo ra một nền tảng thương mại điện tử thân thiện với người dùng, dễ dàng quản lý và tích hợp các chức năng cần thiết để hỗ trợ quá trình kinh doanh trực tuyến hiệu quả.</p>
            <h5 class="text-primary mt-1" class="mt-1">Công nghệ sử dụng:</h5>
            <p style="font-weight: bold;">* Frontend</p>
            <ul>
                <li>HTML</li>
                <li>CSS</li>
                <li>Bootstrap</li>
                <li>JavaScript</li> 
            </ul>
            <p style="font-weight: bold;">* Backend</p>
            <ul>
                <li>PHP</li>
                <li>Cơ sở dữ liệu MySQL phpmyadmin</li>
            </ul>
            <h5 class="text-primary">Tính năng chính:</h5>
            <p> <span class="text-primary" style="font-weight:bold">Quản lý sản phẩm: </span> Hiển thị danh sách các sản phẩm quà lưu niệm, chi tiết sản phẩm, và quản lý kho hàng.</p>
            <p> <span class="text-primary" style="font-weight:bold">Quản lý đơn hàng: </span>  Hiển thị danh sách đơn hàng, chi tiết đơn hàng, và quản lý trạng thái đơn hàng.</p>
            <p> <span class="text-primary" style="font-weight:bold">Quản lý người dùng: </span>  Hiển thị danh sách người dùng, chi tiết người dùng, và quản lý quyền truy cập người dùng.</p>
            <p> <span class="text-primary" style="font-weight:bold">Quản lý danh mục: </span>  Hiển thị danh sách danh mục sản phẩm, chi tiết danh mục, và quản lý danh mục sản phẩm.</p>
            <p> <span class="text-primary" style="font-weight:bold">Giỏ hàng: </span>  Thêm sản phẩm vào giỏ hàng, xóa sản phẩm khỏi giỏ hàng, cập nhật số lượng sản phẩm trong giỏ hàng.</p>
            <p> <span class="text-primary" style="font-weight:bold">Tìm kiếm và lọc sản phẩm: </span>  Tìm kiếm sản phẩm theo tên, lọc sản phẩm theo nhiều tiêu chí khác nhau.</p>
     
        </div>
   
    </div><!-- End .page-content -->
</main><!-- End .main -->

<?php require_once 'inc/footer.php'?>