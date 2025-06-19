<?php
session_start();
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tutor_username = $_POST['tutor_username'] ?? '';
    $tutor_password = $_POST['tutor_password'] ?? '';

    if (empty($tutor_username) || empty($tutor_password)) {
        echo "<p style='color:red;'>Please enter both username and password.</p>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM tutor WHERE tutor_username = ?");
    $stmt->bind_param("s", $tutor_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($tutor_password, $user['tutor_password'])) {
            $_SESSION['tutor_id'] = $user['tutorID'];
            $_SESSION['tutor_fullname'] = $user['tutor_fullName'];
            header("Location: tutorinterface.php");
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password.</p>";
            echo "<meta http-equiv='refresh' content='3;URL=tutorlogin.html'>";
        }
    } else {
        echo "<p style='color:red;'>Username not found.</p>";
        echo "<meta http-equiv='refresh' content='3;URL=tutorlogin.html'>";
    }

    $stmt->close();
}
$conn->close();
?>
