<?php
include("../db_connect.php");

$success = "";
$error = "";

if (isset($_POST['send'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $photo_path = NULL;

    /* IMAGE UPLOAD */
    if (!empty($_FILES['damage_photo']['name'])) {

        $allowed = ['jpg','jpeg','png','webp'];

        $file_name = time() . "_" . basename($_FILES["damage_photo"]["name"]);
        $image_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($image_type, $allowed)) {

            // Path saved in DB
            $target_dir = "uploads/damaged_plants/";
            $photo_path = $target_dir . $file_name;

            // Actual server folder
            $server_dir = __DIR__ . "/../" . $target_dir;
            if (!is_dir($server_dir)) {
                mkdir($server_dir, 0777, true);
            }

            if (!move_uploaded_file(
                $_FILES["damage_photo"]["tmp_name"],
                $server_dir . $file_name
            )) {
                $error = "Image upload failed";
            }

        } else {
            $error = "Only JPG, JPEG, PNG, WEBP images allowed";
        }
    }

    if (!$error) {
        $query = "INSERT INTO contact_support (name, email, message, damage_photo)
                  VALUES ('$name','$email','$message','$photo_path')";

        if (mysqli_query($conn, $query)) {
            $success = "Your message has been sent successfully. We will contact you soon.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Support</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4fff4;
    margin:0;
}
.contact-container{
    max-width:600px;
    background:#fff;
    margin:50px auto;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}
.contact-container h2{
    text-align:center;
    color:green;
}
label{
    font-weight:bold;
    display:block;
    margin-top:15px;
}
input, textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    border:1px solid #ccc;
    border-radius:5px;
}
textarea{
    resize:none;
    height:120px;
}
button{
    margin-top:20px;
    width:100%;
    padding:12px;
    background:green;
    color:white;
    border:none;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    background:#006400;
}
.success{
    background:#e6ffe6;
    color:green;
    padding:10px;
    border-radius:5px;
    margin-bottom:15px;
    text-align:center;
}
.error{
    background:#ffe6e6;
    color:red;
    padding:10px;
    border-radius:5px;
    margin-bottom:15px;
    text-align:center;
}
.back-link{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:green;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="contact-container">
    <h2>📞 Contact Support</h2>

    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Message</label>
        <textarea name="message" required></textarea>

        <label>Upload Damaged Plant Photo (optional)</label>
        <input type="file" name="damage_photo" accept="image/*">

        <button type="submit" name="send">Send Message</button>
    </form>

    <a class="back-link" href="help_center.php">← Back to Help Center</a>
</div>

</body>
</html>
