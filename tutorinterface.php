<?php
session_start();

// Semak jika tutorID wujud dalam sesi
if (!isset($_SESSION['tutor_id'])) {
    // Arahkan ke log masuk jika tiada sesi
    header("Location: tutorlogin.html");
    exit();
}

// Ambil data tutor daripada sesi
$tutorID = $_SESSION['tutor_id'];
$tutorFullname = $_SESSION['tutor_fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tutor Dashboard</title>
  <link rel="stylesheet" href="tutorinterface.css" />
</head>
<body>
  <div class="header">
<<<<<<< Updated upstream
    <div class="brand">LumiLearnHub</div>
    <div class="welcome">WELCOME TUTOR!</div>
    <a href="profiletutor.php" class="profile-icon"></a>
=======
    <a href="tutorinterface.html" class="brand">LumiLearnHub</a>
    <div class="welcome">WELCOME, <?php echo strtoupper($tutorFullname); ?>!</div>
    <div class="profile-icon"></div>
>>>>>>> Stashed changes
  </div>

  <div class="container">
    <div class="sidebar">
      <div class="menu-title">MENU OPTION</div>
      <a href="scheduletutor.html" class="menu-item">
        <span>My Schedule</span>
        <img src="image/calendaricon.png" alt="calendar icon" class="menu-icon">
      </a>

      <a href="mystudenttutor.html" class="menu-item">
        <span>My Student</span>
        <img src="image/usericon.png" alt="user icon" class="menu-icon">
      </a>

      <a href="remindertutor.html" class="menu-item">
        <span>Reminder</span>
        <img src="image/clockicon.png" alt="clock icon" class="menu-icon">
      </a>

      <a href="requeststudenttutor.html" class="menu-item">
        <span>Student Request</span>
        <img src="image/requesticon.png" alt="request icon" class="menu-icon">
      </a>

      <a href="feedbacktutor.html" class="menu-item">
        <span>Feedback</span>
        <img src="image/feedbackicon.png" alt="feedback icon" class="menu-icon">
      </a>
    </div>

    <div class="main-content">
      <div class="top-buttons">
        <a href="availabletutor.html"><button class="top-btn">Availability</button></a>
        <a href="applicationtutor.php"><button class="top-btn">Application for Subject Tutoring</button></a>
      </div>

      <div class="content-section">
        <div class="top-tutors">
          <h3>Top Tutors</h3>
          <ul>
            <li><div class="avatar"></div> Subiyamin bin Sulaiman</li>
            <li><div class="avatar"></div> Muhammad Sumbul</li>
            <li><div class="avatar"></div> Saidatul Syuhada</li>
            <li><div class="avatar"></div> Fakhrul Razi</li>
            <li><div class="avatar"></div> Felix Zemdegs</li>
            <li><div class="avatar"></div> Atiqah Lazim</li>
          </ul>
        </div>

        <div class="qualities">
          <img src="image/goodtutor.jpg" alt="Tutor Qualities Poster" />
        </div>
      </div>
    </div>
  </div>
</body>
</html>
