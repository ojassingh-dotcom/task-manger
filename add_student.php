<?php
include 'partials/new_dbconnect.php';

session_start();

  $sql= "SELECT * FROM rollnos ";
  $result = mysqli_query($conn, $sql);
 
$alert = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
 
 
    $rollno = $_POST['rollno'];
    $department = $_POST['department'];
    //  while ($user = mysqli_fetch_assoc($result)) {
    //     if ($rollno == $user['rollno']) {
    //         echo "This roll number already exists in the database.";
    //         exit();
    //     }
    //     else{
    //      $sql = "INSERT INTO rollnos (`rollno`, `department`) VALUES ('$rollno', '$department')";
    //       mysqli_query($conn, $sql);
    //     }
    // }
   $sql = "SELECT * FROM rollnos WHERE rollno = '$rollno'";
   $result = mysqli_query($conn, $sql);
   

 if (mysqli_num_rows($result) > 0) {
        $alert = true;
    } 
    else {
        $sql = "INSERT INTO rollnos (`rollno`, `department`) VALUES ('$rollno', '$department')";
        $result = mysqli_query($conn, $sql);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      font-family: 'Segoe UI', sans-serif;
      /* margin: 0;
      padding: 40px; */
    }
    .container-box {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: 50px auto;
      padding: 40px;
      
    }
    input.form-control { width: 100%; }
    h5 {
      color: #0d6efd;
      font-weight: bold;
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
        color: white; border: none;
         border-radius: 4px; padding: 5px 12px; 
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
      <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'User' ?></div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>
<?php if ($alert): ?>
<div class="alert alert-danger" role="alert">
  the rollno already exists in the database.
</div>
<?php endif; ?>
</div>



  <div class="container-box">
    <div class="d-flex justify-content-between mb-3">
      <h5><?=  'Add Student' ?></h5>
      <a href="manage_stud.php" class="btn btn-primary">All Students</a>
    </div>
    <form action="add_student.php" method="POST">
      <input type="hidden" name="sno" value="<?= $sno ?>">
      <div class="mb-2">
        <label>Roll No</label>
        <input type="number" name="rollno" class="form-control" required >
      </div>
      <div class="mb-2">
        <label>Department</label>
        <input type="text" name="department" class="form-control" required >
      </div>

      <button class="btn btn-primary mt-2" type="submit">Add</button>
    </form>
  </div>
</body>
</html>