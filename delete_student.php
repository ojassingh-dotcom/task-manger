<?php
$mysqli = new mysqli("localhost", "root", "", "new_task_manag_db");
$sno = $_GET['sno'];
$mysqli->query("DELETE FROM users WHERE sno = $sno");
$mysqli->query("SET @count = 0");
$mysqli->query("UPDATE users SET sno = @count:=@count+1");
$mysqli->query("ALTER TABLE users AUTO_INCREMENT = 1");
header("Location: manage_stud.php");