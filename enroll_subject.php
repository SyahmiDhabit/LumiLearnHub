<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subjectID'])) {
    $subjectID = $_POST['subjectID'];

    if (!isset($_SESSION['studentID'])) {
        echo "Error: Student not logged in.";
        exit;
    }
    $studentID = $_SESSION['studentID'];

    include("connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if already enrolled
    $checkStmt = $conn->prepare("SELECT * FROM student_subject WHERE studentID = ? AND subjectID = ?");
    $checkStmt->bind_param("ii", $studentID, $subjectID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "You have already enrolled in this subject.";
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // Proceed with enrollment if not already enrolled
    $enrollmentDate = date('Y-m-d');
    $status = "Enrolled";

    $stmt = $conn->prepare("INSERT INTO student_subject (studentID, subjectID, enrollment_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $studentID, $subjectID, $enrollmentDate, $status);

    if ($stmt->execute()) {
        echo "Successfully enrolled in subject ID: $subjectID";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
