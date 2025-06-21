<?php
session_start();
if (!isset($_SESSION['studentID'])) {
  header("Location: studentlogin.html");
  exit();
}
$studentID = $_SESSION['studentID'];
$studentFullname = $_SESSION['student_fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - Subject Student</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="subjectstudent.css"/>
</head>
<body>

  <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/LoginUser.png" alt="User Icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?= htmlspecialchars($studentFullname) ?>!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace.
    </p>
  </div>

  <div class="button-group">
    <button id="explore-subject-btn"><img src="image/subject.png" /> Explore Subject</button>
    <button id="find-tutor-btn"><img src="image/findtutor.png" /> Find a Tutor</button>
    <button id="top-tutors-btn"><img src="image/toptutor.png" /> Top Tutors</button>
  </div>

  <div class="subject-section">
    <h2>My Subjects</h2>
    <div style="max-height: 180px; overflow-y: auto; width: 80%;">
      <table id="subject-table">
        <thead>
          <tr><th>No</th><th>Subject</th><th>Tutor</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="right-buttons">
    <div class="right-button" id="schedule-btn"><img src="image/calendaricon.png"><span>My Schedule</span></div>
    <div class="right-button" id="subject-btn"><img src="image/subject.png"><span>My Subject</span></div>
    <div class="right-button" id="feedback-btn"><img src="image/feedbackicon.png"><span>Feedback</span></div>
  </div>

  <footer>2025 LumiLearnHub. All rights reserved</footer>

<script>
$(function() {
  // Navigation bindings
  $(".back-button").click(() => location.href = "studentinterface.php");
  $(".user-top-icon").click(() => location.href = "profilestudent.php");
  $("#explore-subject-btn").click(() => location.href = "searchsubject.php");
  $("#find-tutor-btn").click(() => location.href = "findtutor.php");
  $("#top-tutors-btn").click(() => location.href = "toptutors.php");
  $("#schedule-btn").click(() => location.href = "schedulestudent.php");
  $("#subject-btn").click(() => location.href = "subjectstudent.php");
  $("#feedback-btn").click(() => location.href = "feedbackstudent.php");

  // Load subjects via AJAX
  $.getJSON("get_student_subjects.php", function(data) {
    console.log("Fetched data:", data); // for debugging
    const tbody = $("#subject-table tbody").empty();

    if (!Array.isArray(data) || data.length === 0 || data.error) {
      tbody.append('<tr><td colspan="3">No subjects found.</td></tr>');
      return;
    }

    data.forEach((row, index) => {
      // Ensure field names are correct
      const subject = row.subject_name || "N/A";
      const tutor = row.tutor_fullName || "N/A";

      tbody.append(
        "<tr>" +
          "<td>" + (index + 1) + "</td>" +
          "<td>" + subject + "</td>" +
          "<td>" + tutor + "</td>" +
        "</tr>"
      );
    });
  }).fail((jqXHR, textStatus, errorThrown) => {
    console.error("AJAX Error:", textStatus, errorThrown);
    alert("Failed to load subject data. Please check your database connection.");
  });
});
</script>
</body>
</html>
