<?php
// contact_process.php - robust insert handling for optional rating_message column

require_once 'db_connect.php'; // must set $conn (mysqli)

/** helper */
function post($k) { return isset($_POST[$k]) ? trim($_POST[$k]) : null; }

// get form values
$name    = post('name');
$email   = post('email');
$message = post('message');
$rating  = isset($_POST['rating']) ? intval($_POST['rating']) : null;

// basic server-side validation
if (!$name || !$email || !$message) {
    header("Location: contact.php");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contact.php");
    exit;
}
if ($rating !== null && ($rating < 1 || $rating > 5)) {
    $rating = null;
}

// Map rating number to short text
$ratingTexts = [
    1 => 'Very Poor',
    2 => 'Poor',
    3 => 'Good',
    4 => 'Very Good',
    5 => 'Excellent'
];

$rating_message = $rating !== null ? ($ratingTexts[$rating] ?? '') : null;

// trim lengths
if (mb_strlen($name) > 200) $name = mb_substr($name, 0, 200);
if (mb_strlen($email) > 200) $email = mb_substr($email, 0, 200);
if (mb_strlen($message) > 2000) $message = mb_substr($message, 0, 2000);
if ($rating_message !== null && mb_strlen($rating_message) > 255) $rating_message = mb_substr($rating_message, 0, 255);

// Check if rating_message column exists
$hasRatingMessage = false;
$check = $conn->query("SHOW COLUMNS FROM `contact` LIKE 'rating_message'");
if ($check && $check->num_rows > 0) $hasRatingMessage = true;

// Prepare SQL & bind depending on whether rating_message exists and whether rating is provided
if ($rating === null) {
    if ($hasRatingMessage) {
        $sql = "INSERT INTO contact (name, email, message, rating, rating_message, created_at) VALUES (?, ?, ?, NULL, NULL, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { header("Location: contact.php"); exit; }
        $stmt->bind_param("sss", $name, $email, $message);
    } else {
        $sql = "INSERT INTO contact (name, email, message, rating, created_at) VALUES (?, ?, ?, NULL, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { header("Location: contact.php"); exit; }
        $stmt->bind_param("sss", $name, $email, $message);
    }
} else {
    if ($hasRatingMessage) {
        // Insert rating + rating_message
        $sql = "INSERT INTO contact (name, email, message, rating, rating_message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { header("Location: contact.php"); exit; }
        // types: s (name), s (email), s (message), i (rating), s (rating_message)
        $stmt->bind_param("sssis", $name, $email, $message, $rating, $rating_message);
    } else {
        // Insert rating only
        $sql = "INSERT INTO contact (name, email, message, rating, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { header("Location: contact.php"); exit; }
        // types: s, s, s, i
        $stmt->bind_param("sssi", $name, $email, $message, $rating);
    }
}

$ok = $stmt->execute();
$stmt->close();
$conn->close();

// redirect with success flag
if ($ok) header("Location: contact.php?sent=1");
else header("Location: contact.php");
exit;
