<?php
$host = "localhost";
$user = "root";
$pass = "1234";
$dbname = "student_lumilearn";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT t.tutorID, t.tutor_fullName, ts.subjectID, ts.duration, ts.qualification, ts.level
        FROM tutor_subject ts
        JOIN tutor t ON t.tutorID = ts.tutorID";

$result = $conn->query($sql);

$tutors = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $tutors[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($tutors);

$conn->close();
?>
