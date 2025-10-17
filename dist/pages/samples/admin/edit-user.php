<?php
include "connect.php";

// Get user ID from query string
if (!isset($_GET['id'])) {
    echo "User ID not specified.";
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $country = $conn->real_escape_string($_POST['country']);

    $conn->query("UPDATE dash SET username='$username', email='$email', country='$country' WHERE id=$id");
    header("Location: view-users.php");
    exit;
}

// Fetch user data
$result = $conn->query("SELECT username, email, country FROM dash WHERE id=$id");
if (!$result || $result->num_rows === 0) {
    echo "User not found.";
    exit;
}
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ...existing code... -->
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['country']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="view-users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>