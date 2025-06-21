<?php
session_start();
include 'connection.php'; // sambung ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['student_username']);
    $password = $_POST['student_password'];

    // Elak SQL Injection guna prepared statement
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Semak jika akaun pelajar wujud
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Semak password
        if (password_verify($password, $row['student_password'])) {
            // Simpan maklumat dalam session
            $_SESSION['student_username'] = $row['student_username'];

            // Redirect ke student interface
            header("Location: studentinterface.php");
            exit();
        } else {
            // Password salah
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'studentlogin.html';
                  </script>";
            exit();
        }
    } else {
        // Akaun tiada
        echo "<script>
                alert('Account not found. Please register first.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }
} else {
    // Bukan POST request
    echo "<script>
            alert('Invalid access.');
            window.location.href = 'studentlogin.html';
          </script>";
    exit();
}
?>
