<?php
session_start();
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_username = trim($_POST['student_username'] ?? '');
    $student_password = trim($_POST['student_password'] ?? '');

    if (empty($student_username) || empty($student_password)) {
        $_SESSION['login_error'] = 'Please enter both username and password.';
        header("Location: studentlogin.html");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $student = $result->fetch_assoc();

        if (password_verify($student_password, $student['student_password'])) {
            $_SESSION['student_id'] = $student['studentID'];
            $_SESSION['student_fullname'] = $student['student_fullName'];
            header("Location: studentinterface.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Incorrect password.';
            header("Location: studentlogin.html");
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Username not found.';
        header("Location: studentlogin.html");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
