<?php
session_start();
if (!isset($_SESSION['rollno'])) {
    header("Location: login_page.php");
    exit;
}
include 'partials/new_dbconnect.php';
$rollno = $_SESSION['rollno'];
$today = date("Y-m-d");
$sql = "SELECT title, description, due_date, status FROM tasks WHERE assigned_to = '$rollno'";
$result = mysqli_query($conn, $sql);
$pending = [];
$completed = [];
$overdue = [];
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'];
    $due = $row['due_date'];
    if ($status == 'completed') {
        $completed[] = $row;
    } elseif (strtotime($due) < strtotime($today)) {
        $overdue[] = $row;
    } else {
        $pending[] = $row;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
             background: linear-gradient(to left, #4595e4, white); 
            font-family: 'Segoe UI'; 
        }
        .task-panel { 
            margin-top: 30px;
             padding: 20px; 
             background: #fff; 
             border-radius: 10px; 
             box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
             margin-bottom: 30px;
             }
        
        .status-heading { 
            font-size: 20px; 
            color: #0d6efd;
             margin-bottom: 15px;
             }
        .badge-pending {
             background-color: #ffc107;
             }
        .badge-overdue { 
            background-color: #dc3545; 
        }
        .badge-completed {
             background-color: #28a745; 
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
        }
    </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
        <div class="text">
            <div style="font-size: 24px; color: #0d6efd;">👤</div>
            <div style="font-size: 13px;">@<?php echo $_SESSION['username']; ?></div>
        </div>
        <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
</nav>
<div class="container">

    <div class="task-panel">
        <h3 class="status-heading">Pending 
            <span class="badge badge-pending"><?php echo count($pending); ?></span>
        </h3>
        <?php if (count($pending) == 0) {
            echo "<p class='text'>No pending tasks.</p>";
        } else {
            echo "<ul class='list-group'>";
            for ($i = 0; $i < count($pending); $i++) {
                echo "<li class='list-group-item'>";
                echo  "<h6>" . $pending[$i]['title'] . "</h6>";
                echo  $pending[$i]['description'] . "<br>";
                echo  $pending[$i]['due_date'] . "<br>";
                echo "</li>";
            }
            echo "</ul>";
        } ?>
    </div>

    <div class="task-panel">
        <h3 class="status-heading">completed 
            <span class="badge badge-completed"><?php echo count($completed); ?></span>
        </h3>
        <?php if (count($completed) == 0) {
            echo "<p class='text'>No completed tasks.</p>";
        } else {
            echo "<ul class='list-group'>";
            for ($i = 0; $i < count($completed); $i++) {
                echo "<li class='list-group-item'>";
                echo  "<h6>" . $completed[$i]['title'] . "</h6>";
                echo  $completed[$i]['description'] . "<br>";
                echo  $completed[$i]['due_date'] . "<br>";
                echo "</li>";
            }
            echo "</ul>";
        } ?>
    </div>

    <div class="task-panel">
        <h3 class="status-heading">Overdue 
            <span class="badge badge-overdue"><?php echo count($overdue); ?></span>
        </h3>
        <?php if (count($overdue) == 0) {
            echo "<p class='text'>No overdue tasks.</p>";
        } else {
            echo "<ul class='list-group'>";
            for ($i = 0; $i < count($overdue); $i++) {
                echo "<li class='list-group-item'>";
                echo  "<h6>" . $overdue[$i]['title'] . "</h6>";
                echo  $overdue[$i]['description'] . "<br>";
                echo  $overdue[$i]['due_date'] . "<br>";
                echo "</li>";
            }
            echo "</ul>";
        } ?>
    </div>

</div>
</body>
</html>
