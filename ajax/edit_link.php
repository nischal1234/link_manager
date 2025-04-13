<?php
session_start();
require '../includes/db.php';

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo "Invalid CSRF token.";
    exit;
}

if (isset($_POST['link_id'], $_POST['title'], $_POST['url'], $_POST['description'], $_POST['category_id'])) {
    $link_id = $_POST['link_id'];
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];

    $stmt = $conn->prepare("UPDATE links SET title = ?, url = ?, description = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $url, $description, $category_id, $link_id);

    if ($stmt->execute()) {
        echo "Link updated successfully!";
    } else {
        echo "Error updating link: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid input.";
}
?>
