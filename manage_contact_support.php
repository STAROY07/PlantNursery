<?php
session_start();

// Admin – Manage Contact Support Messages
include("db_connect.php");

/* 🔒 CHECK ADMIN LOGIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// BASE URL (VERY IMPORTANT)
$BASE_URL = "http://localhost:8080/plant_nursery/";

// Delete message
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM contact_support WHERE id = $id");
    header("Location: manage_contact_support.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Contact Support Messages</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4fff4;
    margin:0;
    padding:20px;
}
.container{
    max-width:1000px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}
h2{
    text-align:center;
    color:green;
    margin-bottom:20px;
}
table{
    width:100%;
    border-collapse:collapse;
}
table th, table td{
    border:1px solid #ccc;
    padding:10px;
    text-align:left;
    vertical-align: middle;
}
table th{
    background:#e6ffe6;
    color:#006400;
}
img{
    border-radius:5px;
    border:1px solid #ccc;
}
.back{
    margin-top:20px;
    text-align:center;
}
.back a{
    text-decoration:none;
    color:green;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">
    <h2>📨 Contact Support Messages</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Damage Photo</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM contact_support ORDER BY id DESC");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>

            <td>
                <?php if (!empty($row['damage_photo'])) { ?>
                    <a href="<?= $BASE_URL . htmlspecialchars($row['damage_photo']); ?>" target="_blank">
                        <img src="<?= $BASE_URL . htmlspecialchars($row['damage_photo']); ?>" width="80">
                    </a>
                <?php } else { ?>
                    No Photo
                <?php } ?>
            </td>

            <td><?= $row['created_at']; ?></td>

            <td>
                <a href="reply_support.php?id=<?= $row['id']; ?>" style="color:green;">Reply</a> |
                <a href="manage_contact_support.php?delete_id=<?= $row['id']; ?>"
                   onclick="return confirm('Delete this message?');"
                   style="color:red;">Delete</a>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No messages found</td></tr>";
        }
        ?>
    </table>

    <div class="back">
        <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
    </div>
</div>

</body>
</html>
