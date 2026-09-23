<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'admin_db_connection.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT name, email, password 
         FROM admin_login 
         WHERE email=? AND role='admin' 
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin'] = true;
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: admin_dashboard.php");
            exit();

        } else {
            $error = "Invalid password";
        }

    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
}

.login-box {
    width: 350px;
    margin: 100px auto;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px #ccc;
    border-radius: 6px;
}

input[type="email"],
input[type="password"],
input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    box-sizing: border-box;
}

.show-pass {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    margin-bottom: 10px;
}

.show-pass input {
    width: auto;
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
    text-align: center;
}
</style>
</head>

<body>

<div class="login-box">
<h2 align="center">Admin Login</h2>

<?php if (!empty($error)) { ?>
<p class="error"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">

<input type="email" name="email" placeholder="Admin Email" required>

<input type="password" id="admin_password" name="password" placeholder="Password" required>

<div class="show-pass">
    <input type="checkbox" onclick="toggleAdminPassword()">
    <label>Show Password</label>
</div>

<button type="submit">Login</button>

</form>
</div>

<script>
function toggleAdminPassword() {
    var pass = document.getElementById("admin_password");
    pass.type = (pass.type === "password") ? "text" : "password";
}
</script>

</body>
</html>
