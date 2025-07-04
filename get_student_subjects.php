<?php
header('Content-Type: application/json');
session_start();
include('connection.php');

if (!isset($_SESSION['studentID'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$studentID = $_SESSION['studentID'];

$sql = "
    SELECT 
        subj.subjectID,
        subj.subject_name,
        COALESCE(tut.tutor_fullName, 'Not assigned yet') AS tutor_fullName,
        COALESCE(tut.tutor_phoneNumber, 'Not available') AS tutor_phoneNumber
    FROM student_subject ss
    INNER JOIN subject subj ON ss.subjectID = subj.subjectID
    LEFT JOIN tutor_subject ts ON ss.subjectID = ts.subjectID
    LEFT JOIN tutor tut ON ts.tutorID = tut.tutorID
    WHERE ss.studentID = ?
    GROUP BY subj.subjectID
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$seen = [];
while ($row = $result->fetch_assoc()) {
    $subjID = $row['subjectID'];
    if (!isset($seen[$subjID])) {
        $data[] = $row;
        $seen[$subjID] = true;
    }
}

if (empty($data)) {
    echo json_encode(["error" => "No subjects found for this student", "studentID" => $studentID]);
} else {
    echo json_encode($data, JSON_PRETTY_PRINT);
}

$stmt->close();
$conn->close();
?>
