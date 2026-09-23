<?php
// view_message.php - admin page to view contact messages
require_once 'db_connect.php';

$sql = "SELECT id, name, email, message, rating, created_at FROM contact ORDER BY id DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contact Messages</title>
  <style>
    body { font-family: Arial, sans-serif; padding:20px; }
    h1 { font-size:24px; }
    table { width:100%; border-collapse:collapse; margin-top:12px; }
    th, td { padding:10px; border:1px solid #e0e0e0; vertical-align:top; }
    th { background:#3aa33a; color:white; text-align:left; }
    td { background:#fff; }
    .stars { color: #f1c40f; font-weight:bold; }
    .no-rating { color:#999; }
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
  <h1>FeedBack Messages</h1>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Rating</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?php echo (int)$row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
          <td>
            <?php
              $r = $row['rating'];
              if ($r === null || $r === '' || $r == 0) {
                echo '<span class="no-rating">-</span>';
              } else {
                echo '<span class="stars">';
                for ($i=1;$i<=5;$i++) echo ($i <= $r) ? '★' : '☆';
                echo "</span> ({$r})";
              }
            ?>
          </td>
          <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <div class="back">
        <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
    </div>
</body>
</html>
