<?php

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_lumilearn';


//Create connection
$conn = mysqli_connect($server, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

?>
