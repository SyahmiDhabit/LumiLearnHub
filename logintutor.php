
<?php
session_start();

include 'connection.php'; // sambung database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tutor_username = $_POST['tutor_username'] ?? '';
    $tutor_password = $_POST['tutor_password'] ?? '';

    if (empty($tutor_username) || empty($tutor_password)) {
        echo "<p style='color:red;'>Please enter both username and password.</p>";
        exit();
    }
    // Sanitize input
    $username = trim($_POST['tutor_username']);
    $password = $_POST['tutor_password'];

    // Prepared statement untuk elak SQL injection
    $stmt = $conn->prepare("SELECT * FROM tutor WHERE tutor_username = ?");
    $stmt->bind_param("s", $tutor_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Semak jika akaun wujud
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $row = $result->fetch_assoc();

        if (password_verify($tutor_password, $user['tutor_password'])) {
            $_SESSION['tutor_id'] = $user['tutorID'];
            $_SESSION['tutor_fullname'] = $user['tutor_fullName'];
        // Semak password
        if (password_verify($password, $row['tutor_password'])) {
            // Simpan username dalam session
            $_SESSION['tutor_username'] = $row['tutor_username'];

            // Redirect ke interface
            header("Location: tutorinterface.php");
            exit();
        } else {
            echo "<!DOCTYPE html>
            <html><head><title>Login Failed</title></head><body>
            <p style='color:red;'>Incorrect password.</p>
            <p>Redirecting in 3 seconds...</p>
            </body></html>";
            header("refresh:3; url=tutorlogin.html");
            // Password salah
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = 'tutorlogin.html';
                  </script>";
            exit();
        }
    } else {
        echo "<!DOCTYPE html>
        <html><head><title>Login Failed</title></head><body>
        <p style='color:red;'>Username not found.</p>
        <p>Redirecting in 3 seconds...</p>
        </body></html>";
        header("refresh:3; url=tutorlogin.html");
        // Akaun tiada
        echo "<script>
                alert('Account not found. Please register first.');
                window.location.href = 'tutorlogin.html';
              </script>";
        exit();
    }

    $stmt->close();
} else {
    // Akses tidak sah
    echo "<script>
            alert('Invalid access.');
            window.location.href = 'tutorlogin.html';
          </script>";
    exit();
}
}
$conn->close();
?>