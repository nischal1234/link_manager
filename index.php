<?php
session_start();
require 'includes/db.php';

// Generate CSRF Token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch categories and links
$stmt = $conn->prepare("SELECT categories.id, categories.category_name, links.title, links.url, links.description 
                        FROM categories 
                        LEFT JOIN links ON categories.id = links.category_id");
$stmt->execute();
$result = $stmt->get_result();

$links_by_category = [];
while ($row = $result->fetch_assoc()) {
    $links_by_category[$row['category_name']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>Link Manager Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>
<body>

<header class="bg-dark text-white py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="fw-bold">Link Manager</h1>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a href="pages/login.php" class="nav-link text-white">Login</a></li>
                <li class="nav-item"><a href="pages/register.php" class="nav-link text-white">Register</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container py-5">
    <div class="mb-5">
        <h2 class="text-primary fw-bold">🔍 Search Your Links</h2>
        <input type="text" id="search" class="form-control shadow-sm" placeholder="Search by title or category">
        <div id="results" class="mt-4"></div>
    </div>

    <div id="links-container">
        <?php foreach ($links_by_category as $category => $links): ?>
            <div class="mb-5 animate__animated animate__fadeInUp">
                <h3 class="text-success fw-bold">📂 Category: <?php echo htmlspecialchars($category); ?></h3>
                <div class="row">
                    <?php foreach ($links as $link): ?>
                        <div class="col-md-4">
                            <div class="card shadow-lg border-0 mb-4 animate__animated animate__zoomIn">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($link['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($link['description']); ?></p>
                                    <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="btn btn-outline-primary w-100">
                                        🌐 Visit Link
                                    </a>
                                    <p class="card-text mt-2 text-muted"><small>Category: <?php echo htmlspecialchars($link['category_name']); ?></small></p>
                                    <p class="card-text"><small>URL: <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($link['url']); ?></a></small></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Link Manager. All rights reserved.</p>
</footer>

<script>
$(document).ready(function() {
    $('#search').on('keyup', function() {
        const query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: 'ajax/search_link.php',
                method: 'POST',
                data: {query: query, csrf_token: $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    $('#results').html(data);
                },
                error: function(xhr, status, error) {
                    $('#results').html('<p class="text-danger">AJAX Error: ' + xhr.responseText + '</p>');
                    console.error('Error:', error);
                }
            });
        } else {
            $('#results').html('');
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
    background-color: #f1f3f6;
}

.card {
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
}

#search {
    padding: 12px;
    border: 2px solid #007bff;
    border-radius: 8px;
    transition: box-shadow 0.3s ease;
}

#search:focus {
    box-shadow: 0 0 15px rgba(0, 123, 255, 0.6);
}

footer {
    position: fixed;
    width: 100%;
    bottom: 0;
    background: #212529;
}
</style>

</body>
</html>
