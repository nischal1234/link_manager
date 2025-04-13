<?php
session_start();
require '../includes/db.php';

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo "Invalid CSRF token.";
    exit;
}

if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // First, delete associated links
    $stmt = $conn->prepare("DELETE FROM links WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->close();

    // Then, delete the category
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo "Category and associated links deleted successfully!";
    } else {
        echo "Error deleting category: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid Category ID.";
}
?>
