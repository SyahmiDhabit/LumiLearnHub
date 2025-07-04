<?php
session_start();
include("connection.php");
if (!isset($_SESSION['student_fullName'])) {
  header("Location: studentlogin.html"); // Redirect to login if not logged in
  exit();
}
$studentFullname = $_SESSION['student_fullName'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - FindTutor</title>
  <link rel="stylesheet" href="findtutor.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

  <div class="header">
    <button class="back-btn">BACK TO HOME</button>
    <img src="image/PpLogo.jpg" alt="User Icon" class="login-user-icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?php echo strtoupper($studentFullname); ?>!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace.
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning progress all in one convenient place.
      Whether you’re brushing up on a subject or aiming for top scores, LumiLearnHub is here to support every step of your journey.
      Let’s make the most of your time here and reach your goals together!
    </p>
  </div>

  <div class="main-buttons">
    <button class="btn" id="explore-subject-btn">
      <img src="image/subject.png" alt="Explore Subject" style="width: 20px; vertical-align: middle; margin-right: 5px;">
      Explore Subject
    </button>
    <button class="btn active">
      <img src="image/findtutor.png" alt="Find a Tutor" style="width: 20px; vertical-align: middle; margin-right: 5px;">
      Find a Tutor
    </button>
    <button class="btn" id="top-tutors-btn">
      <img src="image/viewfeedback.png" alt="View Feedback" style="width: 20px; vertical-align: middle; margin-right: 5px;">
      View Feedback
    </button>
  </div>

  <div class="search-section">
    <input type="text" placeholder="Search Tutor" class="search-input">
  </div>

  <!-- Modal -->
  <div id="tutor-modal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
     <h2 id="tutor-name"></h2>
<p><strong>Email:</strong> <span id="tutor-email"></span></p>
<p><strong>Phone:</strong> <span id="tutor-phone"></span></p>
<hr>
<label for="subject-select"><strong>Subjects:</strong></label>
<select id="subject-select" style="width: 100%; padding: 5px;"></select>

<div id="subject-details"></div>

    </div>
  </div>

  <div class="tutor-list" id="tutor-list">
    <!-- Tutors will be inserted dynamically -->
  </div>

  <div class="right-buttons">
    <button class="right-btn" id="my-schedule-btn">
      <img src="image/calendaricon.png" alt="Schedule" style="width: 20px; vertical-align: middle; margin-right: 8px;">
      My Schedule
    </button>
    <button class="right-btn" id="my-subject-btn">
      <img src="image/subject.png" alt="Subject" style="width: 20px; vertical-align: middle; margin-right: 8px;">
      My Subject
    </button>
    <button class="right-btn" id="feedback-btn">
      <img src="image/feedbackicon.png" alt="Feedback" style="width: 20px; vertical-align: middle; margin-right: 8px;">
      Feedback
    </button>
  </div>

  <footer>
    2025 LumiLearnHub. All rights reserved
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let tutorData = [];

      // Fetch tutor data from backend
      $.ajax({
        url: "get_tutors.php",
        method: "GET",
        dataType: "json",
       success: function (data) {
  tutorData = data;
  const list = document.getElementById("tutor-list");
  list.innerHTML = "";

  data.forEach((tutor, index) => {
    const div = document.createElement("div");
    div.className = "tutor-item";
    div.innerHTML = `<img src="image/LoginUser.png" alt="Tutor">${tutor.tutor_fullName}`;
    div.dataset.index = index;
    list.appendChild(div);
  });

  document.querySelectorAll(".tutor-item").forEach(item => {
    item.addEventListener("click", function () {
      const t = tutorData[this.dataset.index];
      document.getElementById("tutor-name").textContent = t.tutor_fullName;
      document.getElementById("tutor-email").textContent = t.tutor_email;
      document.getElementById("tutor-phone").textContent = t.tutor_phoneNumber;

      document.getElementById("tutor-email").textContent = t.tutor_email;
document.getElementById("tutor-phone").textContent = t.tutor_phoneNumber;

const subjectSelect = document.getElementById("subject-select");
const subjectDetails = document.getElementById("subject-details");
subjectSelect.innerHTML = "";
subjectDetails.innerHTML = "";

// Populate combo box
t.subjects.forEach((sub, idx) => {
  const option = document.createElement("option");
  option.value = idx;
  option.textContent = sub.subject_name;
  subjectSelect.appendChild(option);
});

// Show default subject details
function showSubjectInfo(index) {
  const sub = t.subjects[index];
  subjectDetails.innerHTML = `
    <p><strong>Duration:</strong> ${sub.duration}</p>
    <p><strong>Qualification:</strong> ${sub.qualification}</p>
    <p><strong>Level:</strong> ${sub.level}</p>
  `;
}
showSubjectInfo(0); // Default

// Update details on combo change
subjectSelect.addEventListener("change", function () {
  showSubjectInfo(this.value);
});

      document.getElementById("tutor-modal").style.display = "block";
    });
  });
}


      });

      document.querySelector(".close-btn").addEventListener("click", function () {
        document.getElementById("tutor-modal").style.display = "none";
      });

      window.addEventListener("click", function (e) {
        if (e.target === document.getElementById("tutor-modal")) {
          document.getElementById("tutor-modal").style.display = "none";
        }
      });

      document.querySelector(".search-input").addEventListener("input", function () {
        let query = this.value.trim().toLowerCase();
        document.querySelectorAll(".tutor-item").forEach(item => {
          let name = item.textContent.trim().toLowerCase();
          item.style.display = name.startsWith(query) ? "block" : "none";
        }); 
      });

      document.querySelector(".back-btn").addEventListener("click", () => {
        window.location.href = "studentinterface.php";
      });

      document.querySelector(".login-user-icon").addEventListener("click", () => {
        window.location.href = "profilestudent.php";
      });

      document.getElementById("explore-subject-btn").addEventListener("click", () => {
        window.location.href = "searchsubject.php";
      });

      document.getElementById("top-tutors-btn").addEventListener("click", () => {
        window.location.href = "viewfeedback.php";
      });

      document.getElementById("my-schedule-btn").addEventListener("click", () => {
        window.location.href = "schedulestudent.php";
      });

      document.getElementById("my-subject-btn").addEventListener("click", () => {
        window.location.href = "subjectstudent.php";
      });

      document.getElementById("feedback-btn").addEventListener("click", () => {
        window.location.href = "feedbackstudent.php";
      });
    });
  </script>

</body>
</html>
