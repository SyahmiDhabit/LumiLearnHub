<?php
session_start();
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_username = trim($_POST['student_username'] ?? '');
    $student_password = trim($_POST['student_password'] ?? '');

    if (empty($student_username) || empty($student_password)) {
        echo "<p style='color:red;'>Please enter both username and password.</p>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $student = $result->fetch_assoc();

        if (password_verify($student_password, $student['student_password'])) {
            $_SESSION['studentID'] = $student['studentID'];
            $_SESSION['student_fullname'] = $student['student_fullName'];
            header("Location: studentinterface.php");
            exit();
        } else {
            echo "<!DOCTYPE html>
            <html><head><title>Login Failed</title></head><body>
            <p style='color:red;'>Incorrect password.</p>
            <p>Redirecting in 3 seconds...</p>
            </body></html>";
            header("refresh:3; url=studentlogin.html");
            exit();
        }
    } else {
        echo "<!DOCTYPE html>
        <html><head><title>Login Failed</title></head><body>
        <p style='color:red;'>Username not found.</p>
        <p>Redirecting in 3 seconds...</p>
        </body></html>";
        header("refresh:3; url=studentlogin.html");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
