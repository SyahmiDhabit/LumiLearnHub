<?php
session_start();
include 'connection.php'; // sambungan ke database

// Semak jika pelajar telah login
if (!isset($_SESSION['student_username'])) {
    echo "Unauthorized access. Please log in first.";
    exit;
}

$student_username = $_SESSION['student_username'];

// Ambil maklumat pelajar dari database
$sql = "SELECT * FROM student WHERE student_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "No data found for this student.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="profile.css" type="text/css">
</head>
<body>
    <div class="up-bar"> 
        <a href="studentinterface.php"><button class="btn-back">BACK TO HOME</button></a>
    </div>

    <article class="profile-card"> 
        <div class="left-section"> 
            <div class="profile-img">
                <img src="image/DPimage.png" alt="Profile Picture">
            </div>
            <p id="username">Username: <?= htmlspecialchars($student['student_username']) ?></p>
            <p id="age">Age: <?= htmlspecialchars($student['student_age']) ?></p>
            <p id="country">Country: <?= htmlspecialchars($student['student_country']) ?></p>
        </div>

        <div class="right-section"> 
            <div>
                <img src="image/gear.png" width="50px" alt="Settings">
                <div>
                    <p id="fname">Full Name: <?= htmlspecialchars($student['student_fullName']) ?></p>
                    <p id="dob">Date Of Birth: <?= htmlspecialchars($student['student_dob']) ?></p>
                    <p id="gender">Gender: <?= htmlspecialchars($student['student_gender']) ?></p>
                    <p id="phonenumber">Phone Number: <?= htmlspecialchars($student['student_phoneNumber']) ?></p>
                    <p id="email">Email: <?= htmlspecialchars($student['student_email']) ?></p>
                    <p id="bio">Bio: <?= htmlspecialchars($student['student_bio']) ?></p>

                    <br>
                    <a href="studentlogin.html"><button class="btn-logout">LOG OUT</button></a>
                   <a href="updatestudentprofile.php"><button class="btn-logout">UPDATE</button></a>

                </div>
            </div>
        </div>
    </article>
</body>
<footer>
    2025 LumiLearnHub. All rights reserved
</footer>
</html>
