<?php
session_start();
include 'connection.php'; // sambung database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['tutor_username']);
    $password = $_POST['tutor_password'];

    // Prepared statement untuk elak SQL injection
    $stmt = $conn->prepare("SELECT * FROM tutor WHERE tutor_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Semak jika akaun wujud
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Semak password
        if (password_verify($password, $row['tutor_password'])) {
            // Simpan username dalam session
            $_SESSION['tutor_username'] = $row['tutor_username'];

            // Redirect ke interface
            header("Location: tutorinterface.php");
            exit();
        } else {
            // Password salah
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'tutorlogin.html';
                  </script>";
            exit();
        }
    } else {
        // Akaun tiada
        echo "<script>
                alert('Account not found. Please register first.');
                window.location.href = 'tutorlogin.html';
              </script>";
        exit();
    }
} else {
    // Akses tidak sah
    echo "<script>
            alert('Invalid access.');
            window.location.href = 'tutorlogin.html';
          </script>";
    exit();
}
?>
