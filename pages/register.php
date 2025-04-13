<?php
require '../includes/db.php';

$allowed_email = "nishchalmandal@gmail.com";
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email matches the allowed email
    if ($email !== $allowed_email) {
        $error = "No registration is allowed for this email.";
    } else {
        // Check if the email is already registered
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // Register the user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $username, $email, $password);

            if ($stmt->execute()) {
                $success = "Registration successful!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4 fw-bold text-success">Register for Link Manager</h3>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold">Register</button>
        </form>

        <div class="mt-3 text-center">
            <p class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none">Login Here</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
    background: linear-gradient(135deg, #55efc4, #00b894);
    height: 100vh;
}

.card {
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

input:focus, button:focus {
    box-shadow: 0 0 5px rgba(0, 184, 148, 0.5);
}

button:hover {
    background-color: #00976b;
}
</style>
