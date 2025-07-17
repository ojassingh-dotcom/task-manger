<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      margin: 0;
    }
    .navbar-custom {
      background: #fff;
      /* border-bottom: 2px solid #0d6efd; */
      padding: 10px 30px;
      
      margin-bottom: 30px;
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
    .btn-logout {
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 12px;
      font-size: 14px;
    }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
      <div class="text-center">
        <div style="font-size: 24px; color: #0d6efd;">👤</div>
        <div style="font-size: 13px;">
          @<?=  $_SESSION['username']  ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
  </nav>
  <div class="container mt-5">
    <div class="card p-4 shadow-sm">
      <h3 class="mb-3">👤 Profile Details</h3>
      <?php if ($user){ ?>
        <p><strong>Name:</strong> <?= $user['username'] ?></p>
        <p><strong>Roll No:</strong> <?= $user['rollno'] ?></p>
        <p><strong>Department:</strong> <?= $user['department'] ?></p>
        <p><strong>Created On:</strong> <?= $user['dt'] ?></p>
        <a href="cal_dash.php" class="btn btn-primary mt-3">← Back to Dashboard</a>
      <?php } 
      else { ?>
        <p>User not found.</p>
      <?php } ?>
    </div>
  </div>
</body>
</html>
