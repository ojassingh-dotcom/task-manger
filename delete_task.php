<?php
session_start();
include 'partials/new_dbconnect.php';

$showSuccess = false;
$showError = false;

$task_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "DELETE FROM tasks WHERE id = $task_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: all_task.php?msg=Task+deleted+successfully");
        exit();
    } else {
        $showError = "Failed to delete task. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Task - TaskMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <?php if ($showError): ?>
        <div class="alert alert-danger"><?= $showError ?></div>
    <?php endif; ?>
    <a href="show_task.php" class="btn btn-primary">Back to All Tasks</a>
</div>
</body>
</html>