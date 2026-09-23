<?php
session_start();
include 'admin_db_connection.php'; // make sure this connects correctly

// Check admin login
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete user if requested
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']); // sanitize input
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id")
        or die("Delete Error: " . mysqli_error($conn));
    header("Location: manage_users.php");
    exit();
}

// Fetch users
$query = "
SELECT 
    id AS user_id,
    name,
    email,
    created_at
FROM users
ORDER BY created_at DESC
";
$result = mysqli_query($conn, $query)
    or die("SQL Error: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users | Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; }
        h2 { text-align:center; color:#2b7a2b; }
        table {
            width:90%;
            margin:20px auto;
            border-collapse:collapse;
            background:white;
        }
        th, td {
            border:1px solid #ccc;
            padding:10px;
            text-align:center;
        }
        th {
            background:#2b7a2b;
            color:white;
        }
        a.button {
            background:#dc3545;
            color:white;
            padding:5px 10px;
            text-decoration:none;
            border-radius:4px;
        }
        a.button:hover { background:#c82333; }

        .back {
            margin-top:20px;
            text-align:center;
        }
        .back a {
            text-decoration:none;
            color:green;
            font-weight:bold;
        }
    </style>
</head>

<body>

<h2>Manage Users</h2>

<table>
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Registered At</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['user_id']; ?></td>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['email']); ?></td>
        <td><?= $row['created_at']; ?></td>
        <td>
            <a href="manage_users.php?delete=<?= $row['user_id']; ?>"
               class="button"
               onclick="return confirm('Delete this user?')">
               Delete
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

<div class="back">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
</div>

</body>
</html>
