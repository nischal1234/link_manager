<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/header.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch categories
$categories = getUserCategories($user_id);

// Fetch statistics for each category
$stats = [];
foreach ($categories as $category) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total_links FROM links WHERE category_id = ?");
    $stmt->bind_param("i", $category['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stats[$category['id']] = $result['total_links'];
    $stmt->close();
}

// Fetch all links
$links_stmt = $conn->prepare("SELECT links.*, categories.category_name FROM links JOIN categories ON links.category_id = categories.id WHERE links.user_id = ?");
$links_stmt->bind_param("i", $user_id);
$links_stmt->execute();
$links_result = $links_stmt->get_result();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <h1 class="text-center fw-bold mb-4">📊 Dashboard</h1>

    <!-- Stats Section -->
    <div class="row g-4 mb-4">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-lg p-4 rounded">
                    <h5 class="fw-bold"><?php echo htmlspecialchars($category['category_name']); ?></h5>
                    <p>Total Links: <?php echo $stats[$category['id']] ?? 0; ?></p>
                    <form method="POST" action="../ajax/delete_category.php" onsubmit="return confirm('Are you sure you want to delete this category and its links?');">
                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm w-100">Delete Category</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Add Category and Link -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-lg p-4">
                <h3>Add Category</h3>
                <form method="POST" action="../ajax/add_category.php">
                    <input type="text" name="category_name" class="form-control mb-3" placeholder="Category Name" required>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn btn-primary w-100">Add Category</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-lg p-4">
                <h3>Add Link</h3>
                <form method="POST" action="../ajax/add_link.php">
                    <input type="text" name="title" class="form-control mb-3" placeholder="Link Title" required>
                    <input type="url" name="url" class="form-control mb-3" placeholder="URL" required>
                    <select name="category_id" class="form-select mb-3" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="description" class="form-control mb-3" placeholder="Description"></textarea>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn btn-success w-100">Add Link</button>
                </form>
            </div>
        </div>
    </div>

    <!-- All Links -->
    <div class="card shadow-lg p-4 mb-4">
        <h3 class="mb-4 fw-bold text-primary">🔗 All Links</h3>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($link = $links_result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="../ajax/edit_link.php">
                            <td>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($link['title']); ?>" required>
                            </td>
                            <td>
                                <input type="url" name="url" class="form-control" value="<?php echo htmlspecialchars($link['url']); ?>" required>
                            </td>
                            <td>
                                <textarea name="description" class="form-control"><?php echo htmlspecialchars($link['description']); ?></textarea>
                            </td>
                            <td>
                                <select name="category_id" class="form-select">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($link['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="link_id" value="<?php echo $link['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">Update</button>
                        </form>
                        <form method="POST" action="../ajax/delete_link.php" onsubmit="return confirm('Are you sure you want to delete this link?');">
                            <input type="hidden" name="link_id" value="<?php echo $link['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
