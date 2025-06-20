<?php
header('Content-Type: application/json');
session_start();

// Force test with studentID = 3
$studentID = 3;

$conn = new mysqli("localhost", "root", "1234", "student_lumilearn");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Fetch enrolled subjects with description and enrollment date
$sql = "
    SELECT 
        s.subject_name, 
        s.subject_description, 
        ss.enrollment_date
    FROM 
        student_subject ss
    JOIN 
        subject s ON ss.subjectID = s.subjectID
    WHERE 
        ss.studentID = ?
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
