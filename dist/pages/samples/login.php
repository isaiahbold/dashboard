<?php
include "connect.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM dash WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify hashed password
      if (password_verify($pass, $row['password'])) {
        $_SESSION['username'] = $user;
        header("Location: ../../../dist/index.php");
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Aitech World</title>
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
    <link rel="shortcut icon" href="dist/pages/samples/image/Web logo.png" />
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
                <h4>Hello! let's get started</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                <form class="pt-3" action="" method="post">
                  <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" require>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" require>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    <a href="forgot_password.php" class="auth-link text-black">Forgot password?</a>
                  </div>
                  <div class="mb-2 d-grid gap-2">
                    <a href="connect-using-facebook.php" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="ti-facebook me-2"></i>Connect using facebook </a>
                  </div>
                  <div class="text-center mt-4 fw-light"> Don't have an account? <a href="register.php" class="text-primary">Create</a>
                  </div>
                </form>
                <?php

                if (!empty($error)) {
                    echo '<div class="alert alert-danger mt-3" role="alert">' . htmlspecialchars($error) . '</div>';
                }
                ?>
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