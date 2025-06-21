<?php
session_start();
include 'connection.php'; // sambung database

// Semak jika borang log masuk dihantar
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

<<<<<<< Updated upstream
<<<<<<< Updated upstream
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
=======
        // Semak kata laluan menggunakan password_verify
        if (password_verify($tutor_password, $user['tutor_password'])) {
            // Simpan tutorID dalam sesi
            $_SESSION['tutor_id'] = $user['tutorID'];
            $_SESSION['tutor_fullname'] = $user['tutor_fullName'];

            // Alihkan ke tutorinterface.php selepas log masuk berjaya
            header("Location: tutorinterface.php");
            exit();
        } else {
=======
        // Semak kata laluan menggunakan password_verify
        if (password_verify($tutor_password, $user['tutor_password'])) {
            // Simpan tutorID dalam sesi
            $_SESSION['tutor_id'] = $user['tutorID'];
            $_SESSION['tutor_fullname'] = $user['tutor_fullName'];

            // Alihkan ke tutorinterface.php selepas log masuk berjaya
            header("Location: tutorinterface.php");
            exit();
        } else {
>>>>>>> Stashed changes
            echo "<p style='color:red;'>Incorrect password.</p>";
            header("refresh:3; url=tutorlogin.html");
            exit();
        }
    } else {
        echo "<p style='color:red;'>Username not found.</p>";
        header("refresh:3; url=tutorlogin.html");
>>>>>>> Stashed changes
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
