<?php
require('connection.php');

$subjectID = $_GET['subjectID'] ?? 0;

$query = "SELECT t.tutorID, t.tutor_fullName 
          FROM tutor_subject ts 
          JOIN tutor t ON ts.tutorID = t.tutorID 
          WHERE ts.subjectID = ? AND ts.status = 'Approved'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subjectID);
$stmt->execute();

$result = $stmt->get_result();
$tutors = [];

while ($row = $result->fetch_assoc()) {
    $tutors[] = $row;
}

echo json_encode($tutors);
