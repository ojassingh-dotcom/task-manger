<?php
session_start();
include 'partials/new_dbconnect.php';
if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin']!=true){
    header("location: admin_login.php");
    exit;
}


$dueDates = [];
$sql = "SELECT due_date FROM tasks";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dueDates[] = date('Y-m-d', strtotime($row['due_date']));
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
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
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      padding: 0 20px;
    }
     .right-panel {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
   
    .right-panel {
      max-width: 600px;
      margin-top: 30px;
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
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
  <span class="navbar-brand">Student Task Manager</span>
  <div class="d-flex align-items-center gap-3">
    <div class="text-center">
      <div style="font-size: 24px; color: #0d6efd;">👤</div>
      <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?></div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>
<div class="container">
  <div class="right-panel">
    <div class="text-center mb-4">
      <h2>📚 Admin Dashboard</h2>
      <p>Monitor and Manage students' progress and deadlines.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6"><a href="all_task.php" class="btn-tile"><div class="icon">📝</div><h4>All Students Tasks</h4></a></div>
      <div class="col-md-6"><a href="manage_stud.php" class="btn-tile"><div class="icon">👥</div><h4>Manage Students</h4></a></div>
      <div class="col-md-6"><a href="tasks/create_task2.php" class="btn-tile"><div class="icon">📅</div><h4>Add Task</h4></a></div>
      <div class="col-md-6"><a href="admin_prog_bar.php" class="btn-tile"><div class="icon">📊</div><h4>Track Progress</h4></a></div>
    </div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>