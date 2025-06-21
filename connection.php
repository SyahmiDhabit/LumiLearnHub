<?php
<<<<<<< Updated upstream
<<<<<<< Updated upstream
$server = 'localhost:3301';
$username = 'root';
$password = '1234';
$dbname = 'student_lumilearn';
=======


$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_lumilearn1';
>>>>>>> Stashed changes
=======


$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_lumilearn1';
>>>>>>> Stashed changes

//Create connection
$conn = mysqli_connect($server, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

?>
