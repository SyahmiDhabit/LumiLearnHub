<?php
session_start();
require('connection.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.html");
    exit();
}
$studentID = $_SESSION['student_id'];
$studentFullname = $_SESSION['student_fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LumiLearnHub - View Feedback</title>
  <link rel="stylesheet" href="toptutors.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
  <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/LoginUser.png" alt="User Icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?php echo htmlspecialchars($studentFullname); ?>!</h1>
    <p class="description">
      We're excited to have you here. This is your space to explore, learn, and grow at your own pace.
      You can find the right tutors, book sessions that suit your schedule, and keep track of your learning
      progress all in one convenient place. Whether you're brushing up on a subject or aiming for top scores,
      LumiLearnHub is here to support every step of your journey. Let's make the most of your time here and
      reach your goals together!
    </p>
  </div>

  <div class="action-buttons">
    <div class="button" id="explore-subject-btn">
      <img src="image/subject.png" alt="Explore Subject">
      <span>Explore Subject</span>
    </div>
    <div class="button" id="find-tutor-btn">
      <img src="image/findtutor.png" alt="Find a Tutor">
      <span>Find a Tutor</span>
    </div>
    <div class="button">
      <img src="image/feedbackicon.png" alt="Feedback">
      <span>View Feedback</span>
    </div>
  </div>

  <div class="ranking-section">
    <div class="ranking">
      <h2>LIST TUTOR</h2>
      <ul>
        <?php
        $query = "SELECT t.tutor_fullName, ROUND(AVG(f.rate),1) AS avg_score
                  FROM feedback f
                  JOIN tutor_subject ts ON f.subjectID = ts.subjectID
                  JOIN tutor t ON ts.tutorID = t.tutorID
                  GROUP BY t.tutorID
                  ORDER BY avg_score DESC
                  LIMIT 10";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()):
        ?>
        <li>
          <img src="image/LoginUser.png" class="user-icon">
          <?= htmlspecialchars($row['tutor_fullName']) ?> - <?= $row['avg_score'] ?>/10
        </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>

  <div class="right-buttons">
    <div class="right-button" id="my-schedule-btn">
      <img src="image/calendaricon.png" alt="My Schedule">
      <span>My Schedule</span>
    </div>
    <div class="right-button" id="my-subject-btn">
      <img src="image/subject.png" alt="My Subject">
      <span>My Subject</span>
    </div>
    <div class="right-button" id="feedback-btn">
      <img src="image/feedbackicon.png" alt="Feedback">
      <span>Feedback</span>
    </div>
  </div>

  <footer>
    2025 LumiLearnHub. All rights reserved
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelector(".back-button").addEventListener("click", function () {
        window.location.href = "studentinterface.php";
      });

      document.querySelector(".user-top-icon").addEventListener("click", function () {
        window.location.href = "profile2.html";
      });

      document.getElementById("explore-subject-btn").addEventListener("click", function () {
        window.location.href = "searchsubject.php";
      });

      document.getElementById("find-tutor-btn").addEventListener("click", function () {
        window.location.href = "findtutor.php";
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
