<?php
$alert = FALSE;
$servername = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login_page.php");
    exit;
}

$taskOptions = '';
$today = date('Y-m-d');
if (isset($_SESSION['rollno'])) {
    $rollno = $_SESSION['rollno'];
    $task_query = "SELECT id, title FROM tasks WHERE due_date >= '$today' AND status != 'completed' AND assigned_to = '$rollno'";
    $task_result = mysqli_query($conn, $task_query);
    if ($task_result && mysqli_num_rows($task_result) > 0) {
        
        while ($row = mysqli_fetch_assoc($task_result)) {
            $taskOptions .= "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rollno = $_SESSION['rollno'];
    $task_id = $_POST["task_id"];
    $description = $_POST["description"];
    $submission_date = date('Y-m-d');   

    $info_query = "SELECT username, department FROM users WHERE rollno = '$rollno'";
    $info_result = mysqli_query($conn, $info_query);
    if ($info_result && mysqli_num_rows($info_result) > 0) {
        $row = mysqli_fetch_assoc($info_result);
        $username = $row['username'];
        $department = $row['department'];
    }

    $title = '';
    $task_title_query = "SELECT title FROM tasks WHERE id = " . intval($task_id);
    $task_title_result = mysqli_query($conn, $task_title_query);
    if ($task_title_result && mysqli_num_rows($task_title_result) > 0) {
        $row = mysqli_fetch_assoc($task_title_result);
        $title = $row['title'];
    }


    $file_path = '';
    if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['task_file']['tmp_name'];
        $fileName = $_FILES['task_file']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'tasks/uploads/';
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

    
    $sql = "INSERT INTO `submitted_tasks` (`task_id`,`title`, `description`, `submitted_by`, `username`, `department`, `submission_date`, `file_path`) 
            VALUES ('$task_id', '$title', '$description', '$rollno', '$username', '$department', '$submission_date', '$file_path')";
    $result = mysqli_query($conn, $sql);
    $sql2 = "UPDATE `tasks` SET `status` = 'completed' WHERE `id` = '$task_id'";
    mysqli_query($conn, $sql2);
    if (!$result) {
        echo "The record was not inserted: " . mysqli_error($conn) . "<br>";
    } else {
        $alert= TRUE;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Task - TaskMaster</title>
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
      margin: 0 auto;
      margin-top: 40px;
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
    input[type="date"], select {
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
            echo '@' . (isset($_SESSION['username']) ? $_SESSION['username'] : 'User');
          ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='login_page.php'">Logout</button>
    </div>
  </nav>
  <?php
  
  if ($alert) {
    echo '<div class="alert alert-success" role="alert">
  <strong>Success!</strong> Task submitted successfully.
</div>';
  }
?>
  <div class="task-form-container">
    <h2>Submit Task</h2>
    <form action="submit_task.php" method="post" enctype="multipart/form-data">
        <label for="task_id">Select Task</label>
        <select id="task_id" name="task_id" required>
            <option value="">-- Select a Task --</option>
            <?= $taskOptions ?>
        </select>

      <label for="description">Add Description</label>
      <textarea id="description" name="description" placeholder="Details about the task..." required></textarea>

      <label for="task_file">Attach File (PDF, Image):</label>
      <input type="file" id="task_file" name="task_file" accept=".pdf,.jpg,.jpeg,.png,.gif">
      <button type="submit">Submit Task</button>
    </form>
  </div>
</body>
</html>