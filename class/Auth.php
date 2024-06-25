<?php
class Auth
{
    public static function checkLogin()
    {
        if (!isset($_SESSION['logged_user'])) {
            return false;
        }
        return true;
    }


    // Hàm mã hóa password
    public static function hashPassword($passwd)
    {
        return password_hash($passwd, PASSWORD_DEFAULT);
    }

    public static function login($pdo, $username, $password, $nameError, $passError)
    {
        if (empty($username) || empty($password)) {
            return ['Error' => "Vui lòng nhập đầy đủ thông tin"];
        }
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $pass_h = $user['password'];
                $role = $user['role'];
                $user_id = $user['id'];
            }
            else{
                return ['nameError' => "Email không tồn tại"];
            }
 
            if ($username && password_verify($password, $pass_h)) {
                $_SESSION['logged_user'] = $user['name'];
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $user_id;
                return true;
            } else {
                return ['Error' => "Password hoặc email không đúng!"];
            }
        } catch (PDOException $e) {
            // Xử lý lỗi khi không thể thực hiện truy vấn
            return "Có lỗi xảy ra khi truy vấn cơ sở dữ liệu: " . $e->getMessage();
        }
    }

    public static function register($pdo, $name, $email, $password, $repassword, $phone, $address)
    {
        $nameError = '';
        $emailError = '';
        $passError = '';
        $repassError = '';
        $phoneError = '';
        $addressError = '';

        if (empty($name))
            $nameError = "Họ tên không được bỏ trống!";

        if (empty($email)) {
            $emailError = "Email không được bỏ trống!";
        } elseif (!preg_match('/^\\S+@\\S+\\.\\S+$/', $email)) {
            $emailError = "Email không hợp lệ!";
        }

        if (empty($password)) {
            $passError = "Mật khẩu không được bỏ trống!";
        } elseif (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $passError = "Password phải đủ 8 kí tự, chứa chữ in hoa, chữ thường và chứa kí tự đặc biệt!";
        }

        if (empty($repassword)) {
            $repassError = "Xác nhận mật khẩu không được bỏ trống!";
        } elseif ($password != $repassword) {
            $repassError = "Mật khẩu không khớp!";
        }

        if (empty($phone)) {
            $phoneError = "Số điện thoại không được bỏ trống!";
        } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneError = "Sô điện thoại không hợp lệ!";
        }

        if (empty($address)) {
            $addressError = "Địa chỉ không được bỏ trống!";
        }

        // Đăng ký thành công

        $sql = "INSERT INTO user (name, email, password, phone, address, role) VALUES (:name, :email, :password, :phone, :address,'custumer')";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);

        if (!$nameError && !$emailError && !$passError && !$phoneError && !$addressError && $stmt->execute()) {
            return true;
        } else {
            // Đăng ký thất bại, trả về các thông báo lỗi
            return [
                'nameError' => $nameError,
                'emailError' => $emailError,
                'passError' => $passError,
                'repassError' => $repassError,
                'phoneError' => $phoneError,
                'addressError' => $addressError
            ];
        }
    }

    public static function generateOTP($lenght = 6){
        $otp = '';
        for ($i = 0;$i < $lenght; $i++){
            $otp .= mt_rand(0,9);
        }
        return $otp;
    }

    public static function checkMailExist($pdo, $email)
    {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return true;
        }
        return false;
    }

    

    public static function resetPassword($pdo, $email, $password)
    {
        $stmt = $pdo->prepare("UPDATE user SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

}
