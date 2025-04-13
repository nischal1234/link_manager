<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $category_name = sanitizeInput($_POST['category_name']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO categories (user_id, category_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $category_name);

    if ($stmt->execute()) {
        echo "Category added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>