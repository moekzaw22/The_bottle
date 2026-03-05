<?php 
session_start();
include('connect.php');

if (isset($_POST['btnlogin'])) {
    $username = $_POST['txtusername'];
    $password = $_POST['txtpassword'];

    $check = "SELECT * FROM admin WHERE Username = '$username' AND Password = '$password'";
    $check_query = mysqli_query($connect, $check);
    $check_exist = mysqli_num_rows($check_query);
    $arra = mysqli_fetch_array($check_query);

    if ($check_exist == 1 ) {
        $_SESSION['Username'] = $arra['Username'];
        header("Location: admin_product_list.php");
        exit();
    } else {
        echo "<script>alert('Login Failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        /* Reset & Base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #050D23, #0D1B3B);
            color: white;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 360px;
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 30px;
            font-size: 36px;
            letter-spacing: 2px;
            color: #00BFFF;
        }

        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            outline: none;
        }

        .login-card input[type="text"]::placeholder,
        .login-card input[type="password"]::placeholder {
            color: #ccc;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
        }

        .login-card input[type="submit"],
        .login-card input[type="reset"] {
            width: 48%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-card input[type="submit"] {
            background: #00BFFF;
            color: white;
        }

        .login-card input[type="submit"]:hover {
            background: #0090d1;
        }

        .login-card input[type="reset"] {
            background: #FF4C4C;
            color: white;
        }

        .login-card input[type="reset"]:hover {
            background: #e43a3a;
        }

        .login-card .cancel-link {
            display: block;
            margin-top: 25px;
            color: #ccc;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .login-card .cancel-link:hover {
            color: #00BFFF;
        }
    </style>
</head>
<body>
    <form action="admin_login.php" method="POST" class="login-card">
        <h2>ADMIN LOGIN</h2>

        <input type="text" name="txtusername" placeholder="Username" autocomplete="off" autofocus required>
        <input type="password" name="txtpassword" placeholder="Password" autocomplete="off" required>

        <div class="btn-group">
            <input type="submit" value="Login" name="btnlogin">
            <input type="reset" value="Clear">
        </div>

        <a href="purchase.php" class="cancel-link">Cancel Login</a>
    </form>
</body>
</html>