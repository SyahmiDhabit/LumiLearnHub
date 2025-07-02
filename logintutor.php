<?php
session_start();
include 'connection.php'; // Connect to DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['tutor_username'] ?? '');
    $password = $_POST['tutor_password'] ?? '';

    // Check for empty fields
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'tutorlogin.html';
              </script>";
        exit();
    }

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM tutor WHERE tutor_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate account
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['tutor_password'])) {
            // Store tutor info in session
            $_SESSION['tutor_id'] = $row['tutorID'];
            $_SESSION['tutor_username'] = $row['tutor_username'];
            $_SESSION['tutor_fullname'] = $row['tutor_fullName'];

            // Redirect to tutor interface
            header("Location: tutorinterface.php");
            exit();
        } else {
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'tutorlogin.html';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Account not found. Please register first.');
                window.location.href = 'tutorlogin.html';
              </script>";
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
