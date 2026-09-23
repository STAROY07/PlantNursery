<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

if (!$conn) {
    die("Database connection failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
        header("Location: register.php?error=weak_password");
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        header("Location: register.php?error=invalid_email");
        exit();
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$check) {
        die("Prepare failed: " . $conn->error);
    }

    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Insert failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php?success=registered");
        exit();
    } else {
        die("Execution failed: " . $stmt->error);
    }
}
?>