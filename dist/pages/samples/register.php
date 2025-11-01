<?php
include "connect.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $agreement = isset($_POST['agreement']) ? 1 : 0;

    // $referal_code = $_POST['referal_code'];
     function generateInviteCode($length = 8) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $code;
    }

    
    $referal_code = generateInviteCode();

    // $stmt = $conn->prepare("SELECT id FROM dash WHERE referal_code = ?");
    // $stmt->bind_param("s", $referal_code);
    // $stmt->execute();
    // $stmt->store_result();

    // if ($stmt->num_rows > 0) {
    //     $message = "Invite code already exists. Please try again.";
    // } else {
    //     $stmt->close();

        // $stmt = $conn->prepare("INSERT INTO dash (referal_code) VALUES (?)");
        // $stmt->bind_param("s", $referal_code);

        // if ($stmt->execute()) {
        //     $message = "Invite code generated and saved: <strong>" . htmlspecialchars($referal_code) . "</strong>";
        // } else {
        //     $message = "Error: " . $stmt->error;
        // }
        // $stmt->close();
   

    // Handle file upload
    $user_image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadDir = dirname(__DIR__, 2) . '/uploads/'; // Absolute path to /uploads/
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imgDest = $uploadDir . $imgName;
        if (move_uploaded_file($imgTmp, $imgDest)) {
            $user_image = 'uploads/' . $imgName; // Save relative path for browser
        }
    }

    // Hash the password before storing
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO dash (username, email, country, password, referal_code, agreement, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user, $email, $country, $hashedPass, $referal_code, $agreement, $user_image);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                 <img src="image/Web logo 4.png" alt="logo">
                </div>
                <h4>New here?</h4>
                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="Username1" name="username" placeholder="Username" required>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="Email1" name="email" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <input type="file" class="form-control form-control-lg" id="image1" name="image" placeholder="Image" required>
                  </div>
                  <div class="form-group">
                    <select class="form-select form-select-lg" name="country" id="selectcountry" required>
                      <option>Country</option>
                      <option>United States of America</option>
                      <option>United Kingdom</option>
                      <option>India</option>
                      <option>Germany</option>
                      <option>Argentina</option>
                      <option>Nigeria</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="referal_code" name="referal_code" placeholder="referal_code">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="Password1" name="password" placeholder="Password" required>
                  </div>
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" name="agreement" required> I agree to all Terms & Conditions </label>
                    </div>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    
                    <button class="btn-primary btn-lg fw-medium auth-form-btn">SIGN UP</button>




                  </div>
                  <div class="text-center mt-4 fw-light"> Already have an account? <a href="login.php" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/template.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>