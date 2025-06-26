<?php
session_start();
include('connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['studentID'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$studentID = $_SESSION['studentID'];

$sql = "
    SELECT ss.subjectID, s.subject_name, t.tutor_fullName,
        (SELECT COUNT(*) FROM feedback 
         WHERE feedback.subjectID = ss.subjectID 
         AND feedback.studentID = ss.studentID) AS isRated
    FROM student_subject ss
    JOIN subject s ON ss.subjectID = s.subjectID
    JOIN tutor t ON ss.tutorID = t.tutorID
    WHERE ss.studentID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
