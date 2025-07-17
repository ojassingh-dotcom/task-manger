<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}
$sql = "SELECT * FROM rollnos ORDER BY sno ASC";
$students = mysqli_query($conn, $sql);

session_start();?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      min-height: 100vh;
    }

    .container-box {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      margin-top: 50px;
    }

    h2,
    h5 {
      color: #0d6efd;
      font-weight: bold;
    }

    .btn-primary,
    .btn-success {
      background-color: #0d6efd;
      border: none;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover,
    .btn-success:hover {
      background-color: #084298;
    }

    .btn-danger {
      background-color: #dc3545;
      border: none;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-danger:hover {
      background-color: #a71d2a;
    }

    .table thead {
      background-color: #cfe2ff;
      color: #000;
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
      font-size: 14px;
    }
  </style>
</head>

<body>
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
  <div class="container container-box">
    <div class="d-flex justify-content-between mb-3">
      <h2>All Students</h2>
      <a href="add_student.php" class="btn btn-primary">+ Add Student</a>
    </div>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>S. No.</th>
          <th>Department</th>
          <th>Roll Number</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 1; while($row = mysqli_fetch_assoc($students)) { ?>
        <tr>
          <td>
            <?php echo $count++; ?>
          </td>
          <td>
            <?php echo $row['department']; ?>
          </td>
          <td>
            <?php echo $row['rollno']; ?>
          </td>
          <td>

            <a href="delete_student.php?rollno=<?php echo $row['rollno']; ?>" class="btn btn-sm btn-danger"
              onclick="return confirm('Delete this user?')">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>

    </table>
  </div>
</body>

</html>