<?php
session_start();
require('connection.php');

if (!isset($_SESSION['studentID'])) {
    echo "Unauthorized access.";
    exit();
}

$studentID = $_SESSION['studentID'];
$subjectID = $_POST['subjectID'];
$tutorID = $_POST['tutorID'];

// Verify the tutor is allowed to teach this subject
$verify = "SELECT * FROM tutor_subject WHERE tutorID = ? AND subjectID = ? AND status = 'Approved'";
$stmt = $conn->prepare($verify);
$stmt->bind_param("ii", $tutorID, $subjectID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Enrollment failed: Invalid tutor selection.";
    exit();
}

// Check if already enrolled
$check = "SELECT * FROM student_subject WHERE studentID = ? AND subjectID = ?";
$stmt = $conn->prepare($check);
$stmt->bind_param("ii", $studentID, $subjectID);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo "You are already enrolled in this subject.";
    exit();
}

// Enroll the student (modify if storing tutorID separately)
$insert = "INSERT INTO student_subject (studentID, subjectID, tutorID, enrollment_date, status) 
           VALUES (?, ?, ?, CURDATE(), 'Enrolled')";
$stmt = $conn->prepare($insert);
$stmt->bind_param("iii", $studentID, $subjectID, $tutorID);

if ($stmt->execute()) {
    echo "Successfully enrolled with your selected tutor.";
} else {
    echo "Failed to enroll.";
}
?>
