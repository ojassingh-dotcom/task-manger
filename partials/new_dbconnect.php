<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "new_task_manag_db";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn){
    echo "success";
}
// else{
//     die("Error". mysqli_connect_error());
// }
// else{
//     echo "database connected";
// }
?>
