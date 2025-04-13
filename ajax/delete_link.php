<?php
session_start();
require '../includes/db.php';

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo "Invalid CSRF token.";
    exit;
}

if (isset($_POST['link_id']) && is_numeric($_POST['link_id'])) {
    $link_id = $_POST['link_id'];

    $stmt = $conn->prepare("DELETE FROM links WHERE id = ?");
    $stmt->bind_param("i", $link_id);

    if ($stmt->execute()) {
        echo "Link deleted successfully!";
    } else {
        echo "Error deleting link: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid Link ID.";
}
?>
