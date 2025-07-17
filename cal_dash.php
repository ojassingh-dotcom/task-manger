<?php
session_start();
include 'partials/new_dbconnect.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login_page.php");
    exit;
}

$allDates = [];
if (isset($_SESSION['rollno'])) {
    $rollno = $_SESSION['rollno'];
    $sql = "SELECT due_date, status FROM tasks WHERE assigned_to='$rollno'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $allDates[] = [
                'date' => date('Y-m-d', strtotime($row['due_date'])),
                'status' => $row['status']
            ];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      margin: 0;
    }
    .navbar-custom {
      background: #fff;
      padding: 12px 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 24px;
      color: #0d6efd;
    }
    .btn-logout {
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 12px;
      font-size: 14px;
    }
    .main-layout {
      max-width: 600px;
      margin: 40px auto;
      display: flex;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
      padding: 20px;
    }
     .right-panel {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 40px;
    }
    
    
    .btn-tile {
      background: white;
      border: 2px solid #0d6efd;
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      display: block;
      color: inherit;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-tile:hover {
      background: #0d6efd;
      color: white;
      transform: translateY(-4px);
    }
    .icon {
      font-size: 40px;
    }
    .highlight-red {
      background: #ff4d4d !important;
      color: white;
      font-weight: bold;
    }
    .highlight-yellow {
      background: #ffe066 !important;
      color: #333;
      font-weight: bold;
    }
    .highlight-green {
      background: #51cf66 !important;
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
  <span class="navbar-brand">Student Task Manager</span>
  <div class="d-flex align-items-center gap-3">
    <div class="text-center">
      <div style="font-size: 24px; color: #0d6efd;">👤</div>
      <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'User' ?></div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>

<div class="main-layout">
 
  <div class="right-panel">
    <div class="text-center mb-4">
      <h2>📚 Welcome to Your Dashboard</h2>
      <p>Choose an action to stay on top of your academic goals.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6"><a href="my_tasks.php" class="btn-tile"><div class="icon">📝</div><h4>My Tasks</h4></a></div>
      <div class="col-md-6"><a href="profile.php" class="btn-tile"><div class="icon">👤</div><h4>Profile</h4></a></div>
      <div class="col-md-6"><a href="submit_task.php" class="btn-tile"><div class="icon">📥</div><h4>Submit Tasks</h4></a></div>
      <div class="col-md-6"><a href="user_progress_bar.php" class="btn-tile"><div class="icon">📊</div><h4>Progress</h4></a></div>
    </div>
  </div>
</div>


</body>
</html>