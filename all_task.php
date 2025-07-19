<?php
include 'partials/new_dbconnect.php';

session_start();

if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tasks - Task Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <style>
        body {
            background: linear-gradient(to left, #4595e4, white);
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        .navbar-custom {
            background: #fff;
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
        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }
        h2 {
            color: #0d6efd;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-edit {
            background: #ffc107;
            color: #333;
            border: none;
            border-radius: 4px;
            padding: 5px 14px;
            margin-right: 6px;
        }
        .btn-edit:hover {
            background: #e0a800;
            color: #fff;
        }
        .btn-delete {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 14px;
        }
        .btn-delete:hover {
            background: #b52a37;
        }
        table.dataTable thead th {
            background: #0d6efd;
            color: #fff;
        }
        .btn-logout { background: #0d6efd; 
          color: white; 
          border: none; 
          border-radius: 4px; 
          padding: 5px 12px; 
          font-size: 14px; }

    </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
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
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>
<div class="dashboard-container">
    <h2>📋 All Tasks</h2>
    <table class="table" id="myTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Assigned To</th>
            <th>Username</th>
            <th>Department</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>File</th> <!-- Added File column -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM tasks where `status`!= 'completed'";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>" . $row['id'] . "</td>
            <td>" . $row['title'] . "</td>
            <td>" . $row['description'] . "</td>
            <td>" . $row['assigned_to'] . "</td>
            <td>" . $row['username'] . "</td>
            <td>" . $row['department'] . "</td>
            <td>" . $row['due_date'] . "</td>
            <td>" . $row['status'] . "</td>";

        // File column
        echo "<td>";
        if (!empty($row['file_path'])) {
            $fileName = basename($row['file_path']);
            $fileUrl = "/loginsystem/tasks/uploads/" . $fileName;
            echo "<a href='$fileUrl' class='btn btn-primary btn-sm' download>Download</a>";
        } else {
            echo "<span class='text-muted'>No file</span>";
        }
        echo "</td>";

        echo "<td>
                <a href='edit_task.php?id=" . $row['id'] . "' class='btn btn-edit btn-sm'>Edit</a>
                <a href='delete_task.php?id=" . $row['id'] . "' class='btn btn-delete btn-sm' onclick=\"return confirm('Are you sure you want to delete this task?');\">Delete</a>
            </td>
        </tr>";
    }
        
        ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let table = new DataTable('#myTable');
    });
</script>
</body>
</html>