<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subjectID'])) {
    $subjectID = $_POST['subjectID'];

    $conn = new mysqli("localhost", "root", "1234", "student_lumilearn");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $studentID = ""; // Replace with dynamic student ID if using sessions
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
