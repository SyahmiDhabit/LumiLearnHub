<?php
session_start();
include("connection.php");

if (!isset($_SESSION['studentID'])) {
  header("Location: studentlogin.html");
  exit();
}
$studentID = $_SESSION['studentID'];
$studentFullname = $_SESSION['student_fullName'];
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
      We're excited to have you here. This is your space to explore, learn, and grow at your own pace. 
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning 
      progress all in one convenient place. Whether you're brushing up on a subject or aiming for top scores, 
      LumiLearnHub is here to support every step of your journey. Let's make the most of your time here and 
      reach your goals together!
    </p>
  </div>

  <div class="button-group">
    <button>
      <img src="image/subject.png" alt="Subject" /> Explore Subject
    </button>
    <button>
      <img src="image/findtutor.png" alt="Add Tutor" /> Find a Tutor
    </button>
    <button>
      <img src="image/viewfeedback.png" alt="View Feedback" /> View Feedback
    </button>
  </div>

  <div class="subject-section">
    <h2> My Subjects </h2>
    <div style="max-height: 180px; overflow-y: auto; width: 80%;">
      <table id="subject-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Subject</th>
            <th>Tutor</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="right-buttons">
    <div class="right-button">
      <img src="image/calendaricon.png" alt="My Schedule">
      <span>My Schedule</span>
    </div>
    <div class="right-button">
      <img src="image/subject.png" alt="My Subject">
      <span>My Subject</span>
    </div>
    <div class="right-button">
      <img src="image/feedbackicon.png" alt="Feedback">
      <span>Feedback</span>
    </div>
  </div>

  <footer>
    2025 LumiLearnHub. All rights reserved
  </footer>

  <script>
    $(document).ready(function () {
  // Navigation button actions using jQuery
  $(".back-button").on("click", function () {
    window.location.href = "studentinterface.php";
  });

  $(".user-top-icon").on("click", function () {
    window.location.href = "profile2.html";
  });

  $(".button-group button:eq(0)").on("click", function () {
    window.location.href = "searchsubject.php";
  });

  $(".button-group button:eq(1)").on("click", function () {
    window.location.href = "findtutor.php";
  });

  $(".button-group button:eq(2)").on("click", function () {
    window.location.href = "viewfeedback.php";
  });

  $(".right-button:eq(0)").on("click", function () {
    window.location.href = "schedulestudent.php";
  });

  $(".right-button:eq(2)").on("click", function () {
    window.location.href = "feedbackstudent.php";
  });

  // Load subject and tutor from DB
  $.getJSON("get_student_subjects.php", function (data) {
    const tbody = $("#subject-table tbody").empty();

    if (!Array.isArray(data) || data.length === 0 || data.error) {
      tbody.append('<tr><td colspan="3">No subjects found.</td></tr>');
      return;
    }

    data.forEach(function (row, index) {
      const subject = row.subject_name || "N/A";
      const tutor = row.tutor_fullName || "Not assigned yet";
      const subjectID = row.subjectID;

      tbody.append(
        "<tr data-subject-id='" + subjectID + "'>" +
          "<td>" + (index + 1) + "</td>" +
          "<td>" + subject + "</td>" +
          "<td>" + tutor + "</td>" +
        "</tr>"
      );
    });

    // Optional row click action
    $("#subject-table tbody").on("click", "tr", function () {
      const subjectID = $(this).data("subject-id");
      console.log("Clicked subject ID:", subjectID);
    });
  })
  .fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.log("Response text:", jqXHR.responseText);
    alert("Failed to load subject data. Please check your connection.");
  });
});
</script>
</body>
</html>
