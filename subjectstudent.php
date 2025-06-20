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
    <h1 class="welcome-title">WELCOME STUDENT!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace.
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning progress all in one convenient place.
      Whether you’re brushing up on a subject or aiming for top scores, LumiLearnHub is here to support every step of your journey.
      Let’s make the most of your time here and reach your goals together!
    </p>
  </div>

  <div class="button-group">
    <button id="explore-subject-btn">
      <img src="image/subject.png" alt="Subject" /> Explore Subject
    </button>
    <button id="find-tutor-btn">
      <img src="image/findtutor.png" alt="Find Tutor" /> Find a Tutor
    </button>
    <button id="top-tutors-btn">
      <img src="image/toptutor.png" alt="Top Tutors" /> Top Tutors
    </button>
  </div>

  <div class="subject-section">
    <h2>My Subjects</h2>
    <div style="max-height: 180px; overflow-y: auto; width: 80%;">
      <table id="subject-table">
        <thead>
          <tr><th>No</th><th>Subject</th><th>Description</th><th>Enrollment Date</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="right-buttons">
    <div class="right-button" id="schedule-btn">
      <img src="image/calendaricon.png" alt="My Schedule"><span>My Schedule</span>
    </div>
    <div class="right-button" id="subject-btn">
      <img src="image/subject.png" alt="My Subject"><span>My Subject</span>
    </div>
    <div class="right-button" id="feedback-btn">
      <img src="image/feedbackicon.png" alt="Feedback"><span>Feedback</span>
    </div>
  </div>

  <footer>2025 LumiLearnHub. All rights reserved</footer>

<script>
$(function(){
  // Navigation buttons
  $(".back-button").click(() => location.href = "studentinterface.php");
  $(".user-top-icon").click(() => location.href = "profile2.html");
  $("#explore-subject-btn").click(() => location.href = "searchsubject.php");
  $("#find-tutor-btn").click(() => location.href = "findtutor.php");
  $("#top-tutors-btn").click(() => location.href = "toptutors.html");
  $("#schedule-btn").click(() => location.href = "schedulestudent.html");
  $("#subject-btn").click(() => location.href = "subjectstudent.html");
  $("#feedback-btn").click(() => location.href = "feedbackstudent.php");

  // Load subjects from backend
      $.getJSON("get_student_subjects.php", function(data) {
        const tbody = $("#subject-table tbody").empty();
        if (data.length === 0) {
          tbody.append(`<tr><td colspan="3">No subjects found.</td></tr>`);
        } else {
          data.forEach((row, index) => {
            tbody.append(`
              <tr>
                <td>${index + 1}</td>
                <td>${row.subject_name}</td>
                <td>${row.subject_description}</td>
                <td>${row.enrollment_date}</td>
              </tr>
            `);
          });
        }
      }).fail(() => {
        alert("Failed to load subject data. Please check your database connection.");
      });
    });
</script>
</body>
</html>
