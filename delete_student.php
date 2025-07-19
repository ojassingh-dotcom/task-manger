<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


if (!isset($_GET['rollno']) ) {
    die("Invalid student number.");
}
$rollno = $_GET['rollno'];

$delete_sql = "DELETE FROM rollnos WHERE rollno = '$rollno'";
if (!mysqli_query($conn, $delete_sql)) {
    die("Error deleting user: " . mysqli_error($conn));
}
$delete_sql = "DELETE FROM users WHERE rollno = '$rollno'";
if (!mysqli_query($conn, $delete_sql)) {
    die("Error deleting user: " . mysqli_error($conn));
}
$delete_sql = "DELETE FROM tasks WHERE assigned_to = '$rollno'";
if (!mysqli_query($conn, $delete_sql)) {
    die("Error deleting user: " . mysqli_error($conn));
}
$delete_sql = "DELETE FROM submitted_tasks WHERE submitted_by = '$rollno'";
if (!mysqli_query($conn, $delete_sql)) {
    die("Error deleting user: " . mysqli_error($conn));
}
// Reorder rollno and reset AUTO_INCREMENT
mysqli_query($conn, "SET @count = 0");
mysqli_query($conn, "UPDATE rollnos SET sno = @count:=@count+1");
mysqli_query($conn, "ALTER TABLE rollnos AUTO_INCREMENT = 1");

mysqli_close($conn);
header("Location: manage_stud.php");
exit();