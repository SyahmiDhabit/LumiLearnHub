<?php
session_start();
include 'connection.php'; // file untuk sambungan database

// Semak jika session 'tutor_username' wujud
if (!isset($_SESSION['tutor_username'])) {
    echo "Unauthorized access. Please log in first.";
    exit;
}

$tutor_username = $_SESSION['tutor_username'];

// Ambil maklumat tutor berdasarkan username
$sql = "SELECT * FROM tutor WHERE tutor_username = '$tutor_username'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $tutor = mysqli_fetch_assoc($result);
} else {
    echo "No data found for this tutor.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Profile</title>
    <link rel="stylesheet" href="profile.css" type="text/css">
</head>
<body>
    <div class="up-bar"> 
        <a href="tutorinterface.php"><button class="btn-back">BACK TO HOME</button></a>
    </div>

    <article class="profile-card"> 
        <div class="left-section"> 
            <div class="profile-img">
                <img src="image/DPimage.png" alt="Profile Picture">
            </div>
            <p id="username">Username: <?= htmlspecialchars($tutor['tutor_username']) ?></p>
            <p id="age">Age: <?= htmlspecialchars($tutor['tutor_age']) ?></p>
            <p id="country">Country: <?= htmlspecialchars($tutor['tutor_country']) ?></p>
        </div>

        <div class="right-section"> 
            <div>
                <img src="image/gear.png" width="50px" alt="Settings">
                <div>
                    <p id="fname">Full Name: <?= htmlspecialchars($tutor['tutor_fullName']) ?></p>
                    <p id="dob">Date Of Birth: <?= htmlspecialchars($tutor['tutor_dob']) ?></p>
                    <p id="gender">Gender: <?= htmlspecialchars($tutor['tutor_gender']) ?></p>
                    <p id="phonenumber">Phone Number: <?= htmlspecialchars($tutor['tutor_phoneNumber']) ?></p>
                    <p id="email">Email: <?= htmlspecialchars($tutor['tutor_email']) ?></p>
                    <p id="bio">Bio: <?= htmlspecialchars($tutor['tutor_bio']) ?></p>

                    <br>
                    <a href="tutorlogin.html"><button class="btn-logout">LOG OUT</button></a>
                    <a href="updatetutorprofile.php"><button class="btn-logout">UPDATE</button></a>

                </div>
            </div>
        </div>
    </article>
</body>
<footer>
    2025 LumiLearnHub. All rights reserved
</footer>
</html>
