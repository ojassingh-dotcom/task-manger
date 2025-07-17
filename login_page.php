<?php
$login = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'partials/new_dbconnect.php';
  $username = $_POST["username"];
  $password = $_POST["password"];

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);
  if ($num == 1) {
    $login = true;
    session_start();
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    $row = mysqli_fetch_assoc($result);
    $_SESSION['rollno'] = $row['rollno'];
    header("location: cal_dash.php");
    exit();
  } else {
    $showError = "Invalid Credentials";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Student Task Manager - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to left, #4595e4, white);
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }
    .navbar-custom {
      background: #fff;
      /* border-bottom: 2px solid #0d6efd; */
      padding: 10px 30px;
      /* margin-bottom: 30px; */
      box-shadow: 0 2px 8px rgba(13, 110, 253, 0.04);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .navbar-brand {
      font-weight: bold;
      color: #0d6efd;
      font-size: 1.5rem;
      letter-spacing: 1px;
    }
    .college-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .college-logo {
      height: 48px;
      width: auto;
    }
    .college-name {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0d47a1;
      white-space: nowrap;
    }
    .container-main {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
    }
    .left-content {
      max-width: 500px;
      margin-right: 50px;
    }
    .page-title {
      font-size: 40px;
      font-weight: bold;
      color: #0d47a1;
    }
    .subtitle {
      font-size: 18px;
      color: #333;
    }
    .signup-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .signup-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #0d47a1;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background-color: #0d47a1;
      border-color: #0d47a1;
    }
    .btn-primary:hover {
      background-color: #08397b;
    }
    .login {
      text-align: center;
      margin-top: 15px;
      color: #333;
    }
    .login a {
      color: #0d47a1;
      text-decoration: none;
      font-weight: 500;
    }
    .login a:hover {
      text-decoration: underline;
    }
    @media (max-width: 768px) {
      .container-main {
        flex-direction: column;
        text-align: center;
      }
      .left-content {
        margin-right: 0;
        margin-bottom: 40px;
      }
      .college-name {
        font-size: 0.95rem;
      }
      .college-logo {
        height: 36px;
      }
    }
    .footer {
      text-align: center;
      font-size: 14px;
      color: #555;
      margin-top: 150px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="college-info">
      <img src="images.png" alt="College Logo" class="college-logo" />
      <span class="college-name">Guru Gobind Singh Indraprastha University</span>
    </div>
  </nav>

  <?php
    if ($login) {
      echo ' <div class="alert alert-success" role="alert">
  <strong>Success!</strong> You are logged in.
</div>';
    }
    if ($showError) {
      echo ' <div class="alert alert-danger" role="alert">
        <strong>Error!</strong> ' . $showError . '
</div>';
    }
  ?>

  <div class="container-main">
    <div class="left-content">
      <h1 class="page-title">Welcome to Student Task Manager</h1>
      <p class="subtitle">Manage your assignments, deadlines, and notifications in one place. Stay on top of your
        academic goals with ease!</p>
    </div>

    <div class="signup-container">
      <h2>Login</h2>
      <form action="login_page.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username<span class="required">*</span></label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password<span class="required">*</span></label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <p class="login">
          Don't have an account? <a href="sign_up_page.php">Sign up</a><br>
          <span style="font-size: 15px;">Are you an admin? <a href="admin_login.php">Go to Admin Login</a></span>
        </p>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>We sincerely thank <strong>DRDO-SSPL</strong> for giving us the opportunity to develop this project.</p>
  </div>
</body>
</html>