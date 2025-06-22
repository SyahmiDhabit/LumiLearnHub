<?php
session_start();
include("connection.php");

// Check if the student is logged in
if (!isset($_SESSION['studentID'])) {
    header("Location: studentlogin.html");
    exit();
}

// Get student name from session
$studentName = $_SESSION['student_fullName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - Student Interface</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="studentinterface.css" />
</head>
<body>
  <div class="header-bar">
    <button class="logo">LumiLearnHub</button>
    <a href="profilestudent.php">
      <img class="user-top-icon" src="image/LoginUser.png" alt="User Icon">
    </a>
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?php echo htmlspecialchars($studentName); ?>!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace. 
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning 
      progress all in one convenient place. Whether you’re brushing up on a subject or aiming for top scores, 
      LumiLearnHub is here to support every step of your journey. Let’s make the most of your time here and 
      reach your goals together!
    </p>
  </div>

  <div class="top-buttons">
    <button id="explore-subject-btn"><img src="image/subject.png" alt=""> Explore Subject</button>
    <button id="find-tutor-btn"><img src="image/findtutor.png" alt=""> Find a Tutor</button>
    <button id="view-feedback-btn"><img src="image/viewfeedback.png" alt=""> View Feedback</button>
  </div>

  <div class="main-section">
  <div class="left-column">
    <img class="poster-image" src="image/Poster student.jpg" alt="Poster">
  </div>

  <div class="right-column">
    <div class="big-button" id="my-schedule-btn">
      <img src="image/calendaricon.png" alt="My Schedule">
      <span>My Schedule</span>
    </div>
    <div class="big-button" id="my-subject-btn">
      <img src="image/subject.png" alt="My Subject">
      <span>My Subject</span>
    </div>
    <div class="big-button" id="feedback-btn">
      <img src="image/feedbackicon.png" alt="Feedback">
      <span>Feedback</span>
    </div>
  </div>
</div>

  <footer>
    2025 LumiLearnHub. All rights reserved
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById("explore-subject-btn").addEventListener("click", function () {
        window.location.href = "searchsubject.php";
      });

      document.getElementById("find-tutor-btn").addEventListener("click", function () {
        window.location.href = "findtutor.php";
      });

      document.getElementById("view-feedback-btn").addEventListener("click", function () {
        window.location.href = "viewfeedback.php";
      });

      document.getElementById("my-schedule-btn").addEventListener("click", function () {
        window.location.href = "schedulestudent.php";
      });

      document.getElementById("my-subject-btn").addEventListener("click", function () {
        window.location.href = "subjectstudent.php";
      });

      document.getElementById("feedback-btn").addEventListener("click", function () {
        window.location.href = "feedbackstudent.php";
      });
    });
  </script>

</body>
</html>
