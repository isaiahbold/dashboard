<?php
include 'connect.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die('Invalid reset link.');
}

// Fetch the user with the given token
$stmt = mysqli_prepare($conn, "SELECT * FROM dash WHERE reset_token = ?");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    die('Invalid or expired reset link.');
}

$row = mysqli_fetch_assoc($result);

// Output server and token expiry time for reference


$email = $row['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($newPass !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPass) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);

        // Update password and invalidate token
        $update = mysqli_prepare($conn, "UPDATE dash SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        mysqli_stmt_bind_param($update, "ss", $hash, $email);

        if (mysqli_stmt_execute($update)) {
            echo "<p>Password updated successfully. <a href='login.php'>Login here</a></p>";
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<h2>Reset Your Password</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<div id="reset-form-container">
<form method="post" id="reset-form">
    <input type="password" name="password" placeholder="New Password" required>
    <input type="password" name="confirm" placeholder="Confirm Password" required>
    <button type="submit">Update Password</button>
</form>
</div>
<script>
// Get token expiry from PHP (as UTC string)
var tokenExpiry = "<?php echo addslashes($row['token_expiry']); ?>";
// Always treat as UTC for consistency
var expiryDate = new Date(tokenExpiry.replace(' ', 'T') + 'Z'); // force UTC

var now = new Date();

// If local time is after expiry, hide form and show message
if (now.getTime() > expiryDate.getTime()) {
    document.getElementById('reset-form-container').innerHTML = "<p style='color:red;'>Invalid or expired reset link (local time check).</p>";
}
</script>

</script>
</script>
