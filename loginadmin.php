<?php
session_start();
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = $_POST['admin_username'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';

    if (empty($admin_username) || empty($admin_password)) {
        echo "<p style='color:red;'>Please enter both username and password.</p>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($admin_password, $admin['admin_password'])) {
            $_SESSION['admin_id'] = $admin['adminID'];
            $_SESSION['admin_username'] = $admin['admin_username'];
            header("Location: administrationinterface.php");
            exit();
        } else {
            echo "<!DOCTYPE html>
            <html><head><title>Login Failed</title></head><body>
            <p style='color:red;'>Incorrect password.</p>
            <p>Redirecting in 3 seconds...</p>
            </body></html>";
            header("refresh:3; url=adminlogin.html");
            exit();
        }
    } else {
        echo "<!DOCTYPE html>
        <html><head><title>Login Failed</title></head><body>
        <p style='color:red;'>Username not found.</p>
        <p>Redirecting in 3 seconds...</p>
        </body></html>";
        header("refresh:3; url=adminlogin.html");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
