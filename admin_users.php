<?php
// Database connection
include 'connect.php'; // Assuming db_connection.php contains the connection code
$result = $conn->query("SELECT id, username, email FROM dash");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Admin Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>User Admin Table</h2>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $conn->close(); ?>
