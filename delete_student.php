<?php
session_start();
include 'partials/new_dbconnect.php';

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