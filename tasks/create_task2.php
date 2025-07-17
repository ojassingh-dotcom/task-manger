<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}

session_start();

if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $title = $_POST["title"];
  $description = $_POST["description"];
  $due_date = $_POST["due_date"];


  $file_path = '';
  if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['task_file']['tmp_name'];
      $fileName = $_FILES['task_file']['name'];
      $fileNameCmps = explode(".", $fileName);
      $fileExtension = strtolower(end($fileNameCmps));
      $allowedfileExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

      if (in_array($fileExtension, $allowedfileExtensions)) {
          $uploadFileDir = 'uploads/';
          $dest_path = $uploadFileDir . $fileName;

          if(move_uploaded_file($fileTmpPath, $dest_path)) {
              $file_path = $dest_path;
          } else {
              echo "Error moving the uploaded file.<br>";
          }
      } else {
          echo "Upload failed. Allowed types: " . implode(',', $allowedfileExtensions) . "<br>";
      }
  }


  $user_query = "SELECT rollno, username, department FROM users";
  $user_result = mysqli_query($conn, $user_query);

  if ($user_result && mysqli_num_rows($user_result) > 0) {
    while ($row = mysqli_fetch_assoc($user_result)) {
      $rollno = $row['rollno'];
      $department = $row['department'];
      $username = $row['username'];

      $sql = "INSERT INTO `tasks` (`title`, `description`, `assigned_to`, `username`, `department`,`due_date`, `file_path`) 
              VALUES ('$title', '$description', '$rollno', '$username', '$department', '$due_date', '$file_path')";
      $result = mysqli_query($conn, $sql);
      if (!$result) {
        echo "The record was not inserted for rollno $rollno: " . mysqli_error($conn) . "<br>";
      }
    }
    // echo "Task assigned to all users!";
  } else {
    echo "No users found to assign the task.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Task - TaskMaster</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      padding: 12px 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      /* margin-bottom: 30px; */
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      margin: auto;
    }
    .task-form-container {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 400px;
      margin: 30px auto;
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }
    input[type="text"],
    textarea,
    input[type="date"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #981c1c;
      border-radius: 5px;
      font-size: 14px;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }
    button:hover {
      background: #0056b3;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
      <div class="text-center">
        <div style="font-size: 24px; color: #0d6efd;">👤</div>
        <div style="font-size: 13px;">
          <?php
          
            echo '@' . (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User');
          ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='../login_page.php'">Logout</button>
    </div>
  </nav>

  <div class="task-form-container">
    <h2>Add New Task</h2>
    <form action="create_task2.php" method="post" enctype="multipart/form-data">
      <label for="title">Task Title</label>
      <input type="text" id="title" name="title" placeholder="e.g. Submit Assignment" required>

      <label for="description">Task Description</label>
      <textarea id="description" name="description" placeholder="Details about the task..." required></textarea>

      <label for="due_date">Due Date</label>
      <input type="date" id="due_date" name="due_date">
      <label for="task_file">Attach File (PDF, Image):</label>
      <input type="file" id="task_file" name="task_file" accept=".pdf,.jpg,.jpeg,.png,.gif">
      <button type="submit">Add Task</button>
    </form>
  </div>
</body>
</html>