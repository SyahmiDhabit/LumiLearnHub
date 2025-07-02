<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['student_username'] ?? '');
    $password = $_POST['student_password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $student = $result->fetch_assoc();

        // Plaintext password match (use password_verify if using hashing)
        if (password_verify($password, $student['student_password'])) {
            $_SESSION['studentID'] = $student['studentID'];
            $_SESSION['student_username'] = $student['student_username'];
            $_SESSION['student_fullName'] = $student['student_fullName'];

            header("Location: studentinterface.php");
            exit();
        } else {
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'studentlogin.html';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Account not found. Please register first.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
