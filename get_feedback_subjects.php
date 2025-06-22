<?php
header('Content-Type: application/json');
session_start();


$studentID = $_SESSION['studentID'] ?? 3;

include("connection.php");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

$sql = "
    SELECT 
        sub.subject_name,
        tut.tutor_fullName,
        IF(f.feedbackID IS NOT NULL, 'Rated', 'Unrated') AS status
    FROM 
        student_subject ss
    JOIN 
        tutor_subject ts ON ss.subjectID = ts.subjectID
    JOIN 
        subject sub ON ss.subjectID = sub.subjectID
    JOIN 
        tutor tut ON ts.tutorID = tut.tutorID
    LEFT JOIN 
        feedback f ON f.studentID = ss.studentID AND f.subjectID = ss.subjectID
    WHERE 
        ss.studentID = ?
    GROUP BY
        sub.subjectID, tut.tutorID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($data);
