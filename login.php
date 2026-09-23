<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user'] = $row['email'];
            $_SESSION['email'] = $email;

            header("Location: index.php");
            exit();

        } else {
            $error = "Invalid password.";
        }

    } else {
        $error = "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Plant Nursery</title>

<style>
body {
    background: url('images/bg.jpg') no-repeat center center fixed; /* 🔥 match your global bg */
    background-size: cover;

    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* 🔥 GLASS LOGIN BOX */
.login-box {
    background: rgba(255, 255, 255, 0.9);
    padding: 40px;
    border-radius: 14px;
    width: 350px;

    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
}

/* TITLE */
.login-box h2 {
    text-align: center;
    color: #2e7d32;
    margin-bottom: 20px;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.2);
}

/* LABEL */
.login-box label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: bold;
}

/* INPUTS */
.login-box input[type="email"],
.login-box input[type="password"],
.login-box input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;

    border: none;
    border-radius: 8px;

    background: rgba(255,255,255,0.95);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);

    box-sizing: border-box;
    display: block;
    transition: 0.3s;
}

.login-box input:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(46,125,50,0.5);
}

/* 🔥 BUTTON */
.login-box input[type="submit"] {
    width: 100%;
    padding: 10px;

    background: linear-gradient(45deg,#43a047,#66bb6a);
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;

    transition: 0.3s;
}

.login-box input[type="submit"]:hover {
    transform: scale(1.03);
}

/* ERROR */
.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
    font-weight: 500;
}

/* BACK LINK */
.back-link {
    text-align: center;
    margin-top: 10px;
}

.back-link a {
    color: #2e7d32;
    text-decoration: none;
    font-weight: 600;
}

.back-link a:hover {
    text-decoration: underline;
}
</style>
</head>
<?php include 'global_style.php'; ?>
<body>

<div class="login-box">
<h2>Login to Plant Nursery</h2>

<?php if ($error): ?>
<div class="error-message"><?= $error ?></div>
<?php endif; ?>

<?php if (isset($_GET['reset'])): ?>
<p style="color:green;text-align:center;">
    ✅ Password reset successfully. Please login.
</p>
<?php endif; ?>

<form method="POST">

<label>Email:</label>
<input type="email" name="email" required>

<label>Password:</label>
<input type="password" id="password" name="password" required>

<label style="font-weight:normal;">
    <input type="checkbox" onclick="togglePassword()"> Show Password
</label>

<input type="submit" value="Login">

</form>

<div class="back-link">
<a href="Register.php">Don't have an account? Register here</a>
<p style="text-align:center;">
<a href="forgot_password.php">Forgot Password?</a>
</p>
</div>

</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}
</script>

</body>
</html>
