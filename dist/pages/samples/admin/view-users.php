<?php
// Database connection
include "connect.php";

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_email'])) {
    $email = $conn->real_escape_string($_POST['delete_email']);
    $conn->query("DELETE FROM dash WHERE email = '$email'");
}

// Fetch users
$result = $conn->query("SELECT id, username, email, country FROM dash");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Admin Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <h2>User Admin Table</h2>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">s/n</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Country</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sn = 1;
        if ($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()):
        ?>
        <tr>
          <th scope="row"><?= $sn++ ?></th>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['country']) ?></td>
          <td>
            <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <form method="post" action="" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="delete_email" value="<?= htmlspecialchars($row['email']) ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
          <td colspan="5" class="text-center">No users found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
</div>
<?php $conn->close(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>