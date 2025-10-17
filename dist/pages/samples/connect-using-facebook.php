<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; // Adjust path if needed

session_start();

$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_APP_ID', // Replace with your Facebook App ID
  'app_secret' => 'YOUR_APP_SECRET', // Replace with your Facebook App Secret
  'default_graph_version' => 'v19.0',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions

$callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
    if (isset($_GET['code'])) {
        $accessToken = $helper->getAccessToken($callbackUrl);
        if (!isset($accessToken)) {
            throw new Exception('No access token received');
        }
        $_SESSION['fb_access_token'] = (string) $accessToken;

        // Get user info
        $response = $fb->get('/me?fields=id,name,email', $accessToken);
        $user = $response->getGraphUser();
        $_SESSION['fb_user'] = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];

        // --- Add your authentication logic here ---
        include 'connect.php';
        $email = $user->getEmail();
        $name = $user->getName();

        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM dash WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Register new user (customize as needed)
            $username = explode('@', $email)[0];
            $defaultPassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO dash (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $defaultPassword);
            $insert->execute();
        }

        // Log user in (set your session variable)
        $_SESSION['username'] = $email;

        header('Location: ../../../dist/index.php');
        exit;
    }
} catch(Exception $e) {
    echo 'Facebook Login Error: ' . htmlspecialchars($e->getMessage());
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['fb_access_token'], $_SESSION['fb_user']);
    header('Location: ' . $callbackUrl);
    exit;
}

// Display
if (isset($_SESSION['fb_user'])) {
    echo "<h3>Facebook User Info</h3>";
    echo "Name: " . htmlspecialchars($_SESSION['fb_user']['name']) . "<br>";
    echo "Email: " . htmlspecialchars($_SESSION['fb_user']['email']) . "<br>";
    echo '<a href="?logout=1">Logout</a>';
} else {
    $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);
    echo '<a href="' . htmlspecialchars($loginUrl) . '">
        <img src="https://developers.facebook.com/resources/facebook-login-button.png" alt="Login with Facebook"/>
    </a>';
}