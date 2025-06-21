<?php
session_start();
include 'db_connection.php'; // sambung database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['student_username'] ?? '');
    $password = $_POST['student_password'] ?? '';

    // Semak jika input kosong
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }

    // Cari student berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika wujud
    if ($result && $result->num_rows === 1) {
        $student = $result->fetch_assoc();

        // Semak password
        if (password_verify($password, $student['student_password'])) {
            $_SESSION['student_id'] = $student['studentID'];
            $_SESSION['student_username'] = $student['student_username'];
            $_SESSION['student_fullname'] = $student['student_fullName'];

            // Redirect ke dashboard pelajar
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
        // Username tidak wujud
        echo "<script>
                alert('Student account not found. Please sign up first.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }
}
?>
