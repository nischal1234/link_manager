<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $title = sanitizeInput($_POST['title']);
    $url = sanitizeInput($_POST['url']);
    $category_id = intval($_POST['category_id']);
    $description = sanitizeInput($_POST['description']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO links (user_id, category_id, title, url, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $category_id, $title, $url, $description);

    if ($stmt->execute()) {
        echo "Link added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>