<?php

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_lumilearn1';
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes

//Create connection
$conn = mysqli_connect($server, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

?>
