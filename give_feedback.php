<?php
header('Content-Type: application/json');
session_start();
include("connection.php");

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$studentID = $_SESSION['student_id'];

$sql = "
    SELECT 
        subj.subjectID,
        subj.subject_name,
        tut.tutor_fullName,
        (
            SELECT COUNT(*) 
            FROM feedback f 
            WHERE f.studentID = ss.studentID AND f.subjectID = subj.subjectID
        ) AS isRated
    FROM student_subject ss
    JOIN subject subj ON ss.subjectID = subj.subjectID
    JOIN tutor_subject ts ON subj.subjectID = ts.subjectID
    JOIN tutor tut ON ts.tutorID = tut.tutorID
    WHERE ss.studentID = ?
    GROUP BY subj.subjectID, tut.tutor_fullName
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
$stmt->close();
$conn->close();
?>
