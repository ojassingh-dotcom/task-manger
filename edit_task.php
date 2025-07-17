<?php
session_start();
include 'partials/new_dbconnect.php';

if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] != true) {
    header("location: admin_login.php");
    exit;
}

$task = null;
$showSuccess = "";
$showError = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid task ID.");
}
$task_id = $_GET['id'];

$sql = "SELECT * FROM tasks WHERE id = $task_id";
$result = mysqli_query($conn, $sql);
$task = mysqli_fetch_assoc($result);

if (!$task) {
    die("Task not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $due_date = $_POST["due_date"];

    $update_sql = "UPDATE tasks SET title='$title', description='$description', due_date='$due_date' WHERE id=$task_id";
    if (mysqli_query($conn, $update_sql)) {
        $showSuccess = "Task updated successfully!";
        $task['title'] = $title;
        $task['description'] = $description;
        $task['due_date'] = $due_date;
    } else {
        $showError = "Failed to update task. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task - TaskMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to left, #4595e4, white);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .navbar-custom {
            background: #fff;
            border-bottom: 2px solid #0d6efd;
            padding: 12px 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            margin-bottom: 30px;
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
        }
        .task-form-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            min-width: 350px;
            max-width: 400px;
            margin: 40px auto;
        }
        h2 {
            color: #0d6efd;
            margin-bottom: 25px;
            text-align: center;
        }
        .input-holder {
            margin-bottom: 18px;
        }
        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }
        .input-1, textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #bcdffb;
            border-radius: 6px;
            font-size: 15px;
        }
        button[type="submit"] {
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 30px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
        }
        button[type="submit"]:hover {
            background: #0b5ed7;
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
                if (isset($_SESSION['username'])) {
                    echo '@' . $_SESSION['username'];
                } else {
                    echo 'User';
                }
                ?>
            </div>
        </div>
        <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
</nav>

<div class="task-form-container">
    <h2>Edit Task</h2>
    <?php
    if ($showSuccess != "") {
        echo "<div class='alert alert-success'>$showSuccess</div>";
    }
    if ($showError != "") {
        echo "<div class='alert alert-danger'>$showError</div>";
    }
    ?>
    <form method="post" action="edit_task.php">
        <div class="input-holder">
            <label for="title">Title</label>
            <input type="text" name="title" class="input-1" id="title" value="<?php echo $task['title']; ?>" required>
        </div>
        <div class="input-holder">
            <label for="description">Description</label>
            <textarea name="description" class="input-1" id="description" required><?php echo $task['description']; ?></textarea>
        </div>
        <div class="input-holder">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" class="input-1" id="due_date" value="<?php echo $task['due_date']; ?>" required>
        </div>
        <button type="submit">Update Task</button>
    </form>
</div>
</body>
</html>
