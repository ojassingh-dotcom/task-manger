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
$sql = "SELECT users.username, users.rollno, tasks.title, tasks.due_date, tasks.status, tasks.id
        FROM tasks
        INNER JOIN users ON tasks.assigned_to = users.rollno
        ORDER BY users.username, tasks.due_date";
$result = mysqli_query($conn, $sql);
$tasksByStudent = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $uname = $row['username'];
        $tasksByStudent[$uname][] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Task Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to left, #4595e4, white);
      margin: 0;
      padding: 0;
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

    .container {
      margin-top: 30px;
    }

    .student-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .task-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-left: 6px solid;
      border-radius: 5px;
      padding: 15px 20px;
      margin-bottom: 10px;
    }

    .task-title {
      font-weight: 600;
      font-size: 16px;
    }

    .status-badge {
      font-size: 0.8rem;
      padding: 6px 12px;
      border-radius: 20px;
      color: white;
      margin-left: 10px;
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
          @<?=  $_SESSION['username']; ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
  </nav>

  <!-- Task Display -->
  <div class="container">
    <?php foreach ($tasksByStudent as $username => $tasks) { ?>
      <div class="student-section">
        <h4 class="mb-3"><?php echo $username; ?></h4>
        <?php foreach ($tasks as $task) {
          $status = strtolower(trim($task['status']));
          if ($task['due_date'] < date('Y-m-d') && $status !== 'completed') {
            $status = 'overdue';
          }
          if ($status === 'completed') {
            $statusColor = '#28a745'; $bgColor = '#e8f5e9';
          } elseif ($status === 'pending' || $status === 'in_progress') {
            $statusColor = '#ffc107'; $bgColor = '#fff9e6';
          } elseif ($status === 'overdue') {
            $statusColor = '#dc3545'; $bgColor = '#f8d7da';
          } else {
            $statusColor = '#999495ff'; $bgColor = '#f0f0f0';
          }
        ?>
        <div class="task-bar" style="border-left-color: <?php echo $statusColor; ?>; background-color: <?php echo $bgColor; ?>;">
          <span class="task-title">📌 <?php echo $task['title']; ?> <small class="text-muted">(Due: <?php echo $task['due_date']; ?>)</small></span>
          <div>
            <?php if ($status === 'completed') { ?>
              <a href="view_submission.php?rollno=<?php echo $task['rollno']; ?>&title=<?php echo $task['title']; ?>" class="btn btn-sm btn-primary">View Submission</a>
            <?php } ?>
            <span class="status-badge" style="background-color: <?php echo $statusColor; ?>;">
              <?php echo $status; ?>
            </span>
          </div>
        </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
</body>

</html>