 <?php
session_start();
include 'partials/new_dbconnect.php';

$rollno = $_GET['rollno'] ;
$title = $_GET['title'];

$sql = "SELECT * FROM submitted_tasks WHERE submitted_by = '$rollno' AND title = '$title'";
$result = mysqli_query($conn, $sql);
$task = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view_submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
            background: linear-gradient(to left, #4595e4, white);
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
    .navbar-custom {
      background: #fff;
      padding: 12px 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
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
    }
    .box{
      max-width: 700px;
      margin: 40px auto;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      border-radius: 15px;
      background: #fff;
      padding: 40px 30px;
    }
    .text_area {
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      background-color: #f8f9fa;
    }
    </style>
</head>
<body>
   <!-- Navbar -->
  <nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
      <div class="text-center">
        <div style="font-size: 24px; color: #0d6efd;">👤</div>
        <div style="font-size: 13px;">
          @<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'User' ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
  </nav>

  <div class="container">
    <div class="box" style="text-align: center;">
      <h3 style="font-family: Arial, sans-serif;" >Task Submission</h3>
      <div class="details" style="text-align : left;">
      <p><strong>Title:</strong> <?= $task['title'] ?></p>
        <p><strong>Description:</strong> 
        <div class="text_area" ><?= $task['description'] ?></div>
          </p>
        <p><strong>Submitted By:</strong> <?= $task['submitted_by'] ?></p>
        <p><strong>Submitted On:</strong> <?= $task['submission_date'] ?></p>
        <p><strong>Uploaded file:</strong> </p>
        <?php
        if (!empty($task['file_path'])) {
                    $fileName = basename($task['file_path']);
                    $fileUrl = "/loginsystem/tasks/uploads/" . $fileName;
                    echo "<a href='$fileUrl' class='btn btn-primary btn-sm' download>Download</a>";
                } else {
                    echo "<span class='text'>No file</span>";
                }
        ?>
      </div>
    </div>
  </div>


</body>
</html>