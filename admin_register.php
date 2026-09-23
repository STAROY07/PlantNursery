<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'admin_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    /* 🔐 Check if admin already exists */
    $check = mysqli_prepare(
        $conn,
        "SELECT email FROM admin_login WHERE email=? LIMIT 1"
    );
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        header("Location: admin_register.php?error=exists");
        exit();
    }

    /* 🔐 Hash password */
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    /* ➕ Insert admin */
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO admin_login (name, email, password, role)
         VALUES (?, ?, ?, 'admin')"
    );
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        /* ✅ Direct redirect to login */
        header("Location: admin_login.php?registered=1");
        exit();
    } else {
        header("Location: admin_register.php?error=failed");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .register-box {
            width: 380px;
            margin: 100px auto;
            background: white;
            padding: 25px;
            box-shadow: 0 0 10px #ccc;
            border-radius: 6px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2b7a2b;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover {
            background: #256628;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #2b7a2b;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Admin Registration</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
        <p class="error">Admin already registered. Please login.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'failed'): ?>
        <p class="error">Registration failed. Try again.</p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Admin Name" required>
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register Admin</button>
    </form>

    <!-- ✅ LOGIN OPTION -->
    <div class="login-link">
        Already registered? <a href="admin_login.php">Login</a>
    </div>
</div>

</body>
</html>
