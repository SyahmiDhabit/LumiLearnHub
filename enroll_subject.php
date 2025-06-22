<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subjectID'])) {
    $subjectID = $_POST['subjectID'];

    // Get the logged-in student ID from session
    if (!isset($_SESSION['studentID'])) {
        echo "Error: Student not logged in.";
        exit;
    }
    $studentID = $_SESSION['studentID'];

    include("connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $enrollmentDate = date('Y-m-d'); // today's date
    $status = "Enrolled"; // or any appropriate status value

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
