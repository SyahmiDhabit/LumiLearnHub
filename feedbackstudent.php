<?php
session_start();
include('connection.php');

if (!isset($_SESSION['studentID']) || !isset($_SESSION['student_fullName'])) {
  header("Location: studentlogin.html");
  exit();
}

$studentFullname = $_SESSION['student_fullName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - FeedbackStudent</title>
  <link rel="stylesheet" href="feedbackstudent.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
  <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/PpLogo.jpg" alt="User Icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?= strtoupper($studentFullname) ?>!</h1>
    <p class="description">We’re excited to have you here. This is your space to explore, learn, and grow at your own pace. You can find the right tutors, book sessions that suit your schedule, and keep track of your learning progress all in one convenient place. Whether you're brushing up on a subject or aiming for top scores, LumiLearnHub is here to support every step of your journey. Let’s make the most of your time here and reach your goals together!</p>
  </div>

  <div class="button-group">
    <button><img src="image/subject.png" style="width: 20px; margin-right: 8px;">Explore Subject</button>
    <button><img src="image/findtutor.png" style="width: 20px; margin-right: 8px;">Find a Tutor</button>
    <button><img src="image/viewfeedback.png" style="width: 20px; margin-right: 8px;">View Feedback</button>
  </div>

  <div class="main-content">
    <div class="table-section" >
      <table class="subject-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Tutor</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="feedback-body">
          <!-- Dynamic content here -->
        </tbody>
      </table>
    </div>

    <div class="side-buttons">
      <div class="side-button" onclick="window.location.href='schedulestudent.php'">
        <img src="image/calendaricon.png" alt="My Schedule"><span>My Schedule</span>
      </div>
      <div class="side-button" onclick="window.location.href='subjectstudent.php'">
        <img src="image/subject.png" alt="My Subject"><span>My Subject</span>
      </div>
      <div class="side-button" onclick="window.location.href='feedbackstudent.php'">
        <img src="image/feedbackicon.png" alt="Feedback"><span>Feedback</span>
      </div>
    </div>
  </div>

  <footer>2025 LumiLearnHub. All rights reserved</footer>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".back-button").onclick = () => window.location.href = "studentinterface.php";
    document.querySelector(".user-top-icon").onclick = () => window.location.href = "profilestudent.php";
    document.querySelector(".button-group button:nth-child(1)").onclick = () => window.location.href = "searchsubject.php";
    document.querySelector(".button-group button:nth-child(2)").onclick = () => window.location.href = "findtutor.php";
    document.querySelector(".button-group button:nth-child(3)").onclick = () => window.location.href = "viewfeedback.php";


    // Load feedback data dynamically
    $.getJSON("give_feedback.php", function(data) {
  console.log("Response from give_feedbacks.php:", data); // DEBUG LOG
  const tbody = $("#feedback-body").empty();

  if (!Array.isArray(data) || data.length === 0 || data.error) {
    console.warn("Invalid or empty data received.");
    tbody.append("<tr><td colspan='4'>No subjects found or not logged in.</td></tr>");
  } else {
    data.forEach((row, index) => {
      const status = row.isRated > 0
        ? `<span style="color:green;text-decoration:underline;">Rated</span>`
        : `<a href="give_rating.php?subjectID=${row.subjectID}&tutor=${encodeURIComponent(row.tutor_fullName)}" style="color:blue;text-decoration:underline;">Unrated</a>`;

      tbody.append(`
        <tr>
          <td>${index + 1}</td>
          <td>${row.subject_name}</td>
          <td>${row.tutor_fullName}</td>
          <td>${status}</td>
        </tr>
      `);
    });
  }
}).fail((jqXHR, textStatus, errorThrown) => {
  console.error("AJAX failed!", textStatus, errorThrown);
  console.log("Response text:", jqXHR.responseText);
  alert("Failed to load feedback data.");
});
  });
</script>
</body>
</html>
