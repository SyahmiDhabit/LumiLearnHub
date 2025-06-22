<?php
session_start();
include 'connection.php'; // Connect to DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['student_username'] ?? '');
    $password = $_POST['student_password'] ?? '';

    // Check for empty fields
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate account
    if ($result && $result->num_rows === 1) {
        $student = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['student_password'])) {
            // Store student info in session
            $_SESSION['studentID'] = $row['studentID'];
            $_SESSION['student_username'] = $row['student_username'];
            $_SESSION['student_fullName'] = $row['student_fullName'];

            // Redirect to student interface
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
