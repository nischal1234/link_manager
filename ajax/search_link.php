<?php
session_start();

// Correct the path using relative directory traversal
require __DIR__ . '/../includes/db.php'; // Go up one level to find includes folder

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo 'Invalid CSRF Token.';
    exit;
}

$search = trim($_POST['query']);
$response = '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT links.title, links.url, links.description, categories.category_name 
                            FROM links 
                            JOIN categories ON links.category_id = categories.id 
                            WHERE links.title LIKE CONCAT('%', ?, '%') 
                            OR categories.category_name LIKE CONCAT('%', ?, '%')");
    if (!$stmt) {
        http_response_code(500);
        echo 'Prepare failed: (' . $conn->errno . ') ' . $conn->error;
        exit;
    }

    $stmt->bind_param('ss', $search, $search);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo 'Execute failed: (' . $stmt->errno . ') ' . $stmt->error;
        exit;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response .= '<div class="row">';
        while ($row = $result->fetch_assoc()) {
            $response .= '
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-3 animate__animated animate__fadeIn">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">'.htmlspecialchars($row['title']).'</h5>
                            <p class="card-text">'.htmlspecialchars($row['description']).'</p>
                            <p class="card-text text-muted">Category: '.htmlspecialchars($row['category_name']).'</p>
                            <a href="'.htmlspecialchars($row['url']).'" target="_blank" class="btn btn-primary btn-sm">Visit Link</a>
                        </div>
                    </div>
                </div>';
        }
        $response .= '</div>';
    } else {
        $response .= '<p class="text-warning">No links found for the given search term.</p>';
    }

    $stmt->close();
} else {
    $response .= '<p class="text-danger">Please enter a search term.</p>';
}

echo $response;
?>
