<?php

$server = 'localhost:3301';
$username = 'root';
<<<<<<< HEAD
$password = '1234';
=======
$password = '';
>>>>>>> c495e0145c6fa7c43d2e09ed6f4f48fa238b7412
$dbname = 'student_lumilearn';


//Create connection
$conn = mysqli_connect($server, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

?>
