<?php
session_start();
include 'connection.php'; // Sambung database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dapatkan input & sanitize
    $username = trim($_POST['student_username'] ?? '');
    $password = $_POST['student_password'] ?? '';

    // Periksa jika medan kosong
    if (empty($username) || empty($password)) {
        echo "<script>
                alert('Please enter both username and password.');
                window.location.href = 'studentlogin.html';
              </script>";
        exit();
    }

    // Prepared statement untuk elak SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE student_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Semak akaun
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Semak password
        if (password_verify($password, $row['student_password'])) {
            // Simpan info pelajar dalam session
            $_SESSION['student_id'] = $row['studentID'];
            $_SESSION['student_username'] = $row['student_username'];
            $_SESSION['student_fullname'] = $row['student_fullName'];

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

    $stmt->close();
}
$conn->close();
?>
