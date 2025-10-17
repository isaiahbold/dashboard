<?php
include 'connect.php';
// Load Composer's autoloader (this loads PHPMailer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// require 'autoload.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';



// include __DIR__ . '/vendor/autoload.php'; // Make sure Composer installed PHPMailer






    
    


// Start session
session_start();

// Include database connection and mail function
include 'connect.php';
//include 'send_mail.php';

// Handle form submission
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        // Check if the email exists in the users table
        $checkUser = $conn->prepare("SELECT * FROM dash WHERE email = ? LIMIT 1");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows > 0) {
            // Generate unique reset token & expiry
            $token = bin2hex(random_bytes(32)); // secure token
            $expiry = date("Y-m-d H:i:s", time() + 3600); // expires in 1 hour (UNIX timestamp)
            //secho $expiry;

            // Save token & expiry in DB
            $update = $conn->prepare("UPDATE dash SET reset_token = ?, token_expiry = ? WHERE email = ?");
            $update->bind_param('sss', $token, $expiry, $email);
            $update->execute();

            function sendMail($email,$subject, $body)
            {
                $mail = new PHPMailer(true);

                try {
                    // SMTP server settings
                    $mail->SMTPDebug  = 0;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'isaiahaderinto4@gmail.com';     
                    $mail->Password   = 'hiej kztd guco skdc';   
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                    $mail->Port       = 465;
                    // Sender & Recipient
                    $mail->setFrom('isaiahaderinto4@gmail.com', 'Aiteh support');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = $body;

                    // Send mail
                    $mail->send();
                    return true;

    } catch (Exception $e) {
        // Optional: log error $mail->ErrorInfo for debugging
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

            // Create password reset link
            $resetLink = "http://localhost/dashboard/dist/pages/samples/reset_password.php?token=" . $token;

            // Send reset email
            $subject = "Password Reset Request";
            $body = "
                <p>Hello,</p>
                <p>We received a request to reset your password. Click the link below to set a new password:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, you can ignore this email.</p>
            ";

            if (sendMail($email, $subject, $body)) {
                $message = "A password reset link has been sent to your email.";
            } else {
                $message = "Failed to send the reset email. Please try again later.";
            }

        } else {
            $message = "No account found with that email.";
        }

    } else {
        $message = "Please enter your email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 350px;
            text-align: center;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: #fff;
            padding: 10px;
            width: 100%;
            border: none;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .msg {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="submit">Send Reset Link</button>
    </form>
    <?php if (!empty($message)) { ?>
        <div class="msg"><?php echo $message; ?></div>
    <?php } ?>
</div>

</body>
</html>



