<?php
session_start();
if (!isset($_SESSION['student_fullName'])) {
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
  <title>LumiLearnHub - Search Subject</title>
  <link rel="stylesheet" href="searchsubject.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

  <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/PpLogo.jpg" alt="User Icon">
  </div>
    
    <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?php echo htmlspecialchars($studentFullname); ?>!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace.
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning progress all in one convenient place.
      Whether you’re brushing up on a subject or aiming for top scores, LumiLearnHub is here to support every step of your journey.
      Let’s make the most of your time here and reach your goals together!
    </p>
    </div>

  <div class="container"></div>
  <div class="main-buttons">
  <button class="btn active">
    <img src="image/subject.png" alt="Explore Subject" style="width: 20px; vertical-align: middle; margin-right: 5px;">
    Explore Subject
  </button>
  <button class="btn" id="find-tutor-btn" >
    <img src="image/findtutor.png" alt="Find a Tutor" style="width: 20px; vertical-align: middle; margin-right: 5px;">
    Find a Tutor
  </button>
  <button class="btn" id="view-feedback-btn">
    <img src="image/viewfeedback.png" alt="View Feedback" style="width: 20px; vertical-align: middle; margin-right: 5px;">
    View Feedback
  </button>
  </div>

    <div class="search-bar">
      <input type="text" placeholder="Search Subject">
    </div>

  <div id="subject-info-modal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2 id="subject-title">Subject Details</h2>
    <p><strong>Subject ID:</strong> <span id="subject-id"></span></p>
    <p><strong>Description:</strong> <span id="subject-description"></span></p>

    <!-- 👇 Add this section for selecting tutor -->
    <p><strong>Tutor:</strong>
      <select id="tutor-select">
        <option value="">Select a tutor</option>
      </select>
    </p>

    <button id="enroll-btn">Enroll</button>
  </div>
</div>


 
    <div class="subject-section">
      <div class="subject-grid">
        <button class="subject-btn">Bahasa Melayu</button>
        <button class="subject-btn">English</button>
        <button class="subject-btn">Computer Science</button>
        <button class="subject-btn">Add Math</button>
        <button class="subject-btn">Biology</button>
        <button class="subject-btn">Math</button>
        <button class="subject-btn">Chemistry</button>
        <button class="subject-btn">Science</button>
        <button class="subject-btn">Physics</button>
        <button class="subject-btn">History</button>
        <button class="subject-btn">Geography</button>
        <button class="subject-btn">Moral Studies</button>
        <button class="subject-btn">Islamic Studies</button>
        <button class="subject-btn">Art</button>
        <button class="subject-btn">Literature</button>
        <button class="subject-btn">Economics</button>
        <button class="subject-btn">Account</button>
        <button class="subject-btn">Business</button>
        <button class="subject-btn">Music</button>
        <button class="subject-btn">Basic Math</button>
        <button class="subject-btn">Civics</button>
      </div>
    </div>

  
    <div class="right-buttons">
      <div class="right-btn" id="my-schedule-btn">
        <img src="image/calendaricon.png" alt="My Schedule">
        <span>My Schedule</span>
      </div>
      <div class="right-btn" id="my-subject-btn">
        <img src="image/subject.png" alt="My Subject">
        <span>My Subject</span>
      </div>
      <div class="right-btn" id="feedback-btn">
        <img src="image/feedbackicon.png" alt="Feedback">
        <span>Feedback</span>
      </div>
    </div>


  <footer>
    2025 LumiLearnHub. All rights reserved
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function () {

  const searchInput = document.querySelector(".search-bar input");
  const subjectButtonsContainer = document.querySelector(".subject-grid");
  const modal = document.getElementById("subject-info-modal");
  const closeBtn = document.querySelector(".close-btn");

  const subjectTitle = document.getElementById("subject-title");
  const subjectId = document.getElementById("subject-id");
  const subjectDescription = document.getElementById("subject-description");

  let subjectDetails = {};

  fetch("get_subjects.php")
    .then(response => response.json())
    .then(data => {
      subjectDetails = data;
      renderSubjectButtons(data);
    });

  function renderSubjectButtons(data) {
    subjectButtonsContainer.innerHTML = "";

    Object.keys(data).forEach(subjectName => {
      const button = document.createElement("button");
      button.classList.add("subject-btn");
      button.textContent = subjectName;

      button.addEventListener("click", function () {
  const selectedSubject = subjectName;
  const selectedSubjectId = data[subjectName].id;
  subjectTitle.textContent = selectedSubject;
  subjectId.textContent = selectedSubjectId;
  subjectDescription.textContent = data[subjectName].description;
  loadTutorsForSubject(selectedSubjectId);
  modal.style.display = "block";
});


      subjectButtonsContainer.appendChild(button);
    });
  }

  document.getElementById("enroll-btn").addEventListener("click", function () {
  const subjectID = document.getElementById("subject-id").textContent;
  const tutorID = document.getElementById("tutor-select").value;

  if (!tutorID) {
    alert("Please select a tutor.");
    return;
  }

  fetch("enroll_subject.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `subjectID=${subjectID}&tutorID=${tutorID}`
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
  });
});

  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  searchInput.addEventListener("input", function () {
    const filter = searchInput.value.trim().toLowerCase();
    const buttons = document.querySelectorAll(".subject-btn");

    buttons.forEach(button => {
      const subjectName = button.textContent.trim().toLowerCase();
      button.style.display = filter === "" || subjectName.startsWith(filter) ? "block" : "none";
    });
  });

  // Navigation
  document.querySelector(".back-button").addEventListener("click", () => window.location.href = "studentinterface.php");
  document.querySelector(".user-top-icon").addEventListener("click", () => window.location.href = "profilestudent.php");
  document.getElementById("find-tutor-btn").addEventListener("click", () => window.location.href = "findtutor.php");
  document.getElementById("view-feedback-btn").addEventListener("click", () => window.location.href = "viewfeedback.php");
  document.getElementById("my-schedule-btn").addEventListener("click", () => window.location.href = "schedulestudent.php");
  document.getElementById("my-subject-btn").addEventListener("click", () => window.location.href = "subjectstudent.php");
  document.getElementById("feedback-btn").addEventListener("click", () => window.location.href = "feedbackstudent.php");
});
function loadTutorsForSubject(subjectID) {
  fetch("get_tutors_by_subject.php?subjectID=" + subjectID)
    .then(res => res.json())
    .then(tutors => {
      const tutorSelect = document.getElementById("tutor-select");
      tutorSelect.innerHTML = "<option value=''>Select a tutor</option>";
      if (tutors.length === 0) {
        tutorSelect.innerHTML = "<option value=''>No tutor available</option>";
      } else {
        tutors.forEach(tutor => {
          const option = document.createElement("option");
          option.value = tutor.tutorID;
          option.textContent = tutor.tutor_fullName;
          tutorSelect.appendChild(option);
        });
      }
    });
}

</script>

</body>
</html>
