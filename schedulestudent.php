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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - Shcedule Student</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="schedulestudent.css" />
</head>
<body>
  
    <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/LoginUser.png" alt="User Icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT: <?php echo htmlspecialchars($studentFullname); ?>!</h1>
    <p class="description">
      We’re excited to have you here. This is your space to explore, learn, and grow at your own pace. 
      You can find the right tutors, book sessions that suit your schedule, and keep track of your 
      learning progress all in one convenient place. Whether you’re brushing up on a subject or aiming 
      for top scores, LumiLearnHub is here to support every step of your journey. Let’s make the most 
      of your time here and reach your goals together!
    </p>
  </div>

    <div class="button-group">
      <button>
        <img src="image/subject.png" alt="Subject" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Explore Subject
      </button>
      <button>
        <img src="image/findtutor.png" alt="Add Tutor" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Find a Tutor
      </button>
      <button>
        <img src="image/toptutor.png" alt="Top Tutor" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Top Tutors
      </button>
    </div>

    <div class="main-grid">
      <div class="sidebar">
        <button class="icon-btn">⚙️</button>
        <div class="sidebar">
          <button class="icon-btn edit">✏️ Edit Schedule</button>
          <button class="icon-btn add">➕ Add Subject</button>
        </div>
      </div>

      <table class="schedule">
        <thead>
          <tr>
            <th>Day / Time</th>
            <th>8.30am - 10.30am</th>
            <th>12.30pm - 2.30pm</th>
            <th>4.30pm - 6.30pm</th>
            <th>9.30pm - 10.30pm</th>
            <th>11.30pm - 12.30am</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Sunday</td><td></td><td></td><td></td><td></td><td></td></tr>
          <tr><td>Monday</td><td></td><td></td><td></td><td></td><td></td></tr>
          <tr><td>Tuesday</td><td></td><td></td><td></td><td></td><td></td></tr>
          <tr><td>Wednesday</td><td></td><td></td><td></td><td></td><td></td></tr>
          <tr><td>Thursday</td><td></td><td></td><td></td><td></td><td></td></tr>
        </tbody>
      </table>

      
  
    <div class="side-buttons">
      <div id="my-schedule-btn" class="side-button">
        <img src="image/calendaricon.png" alt="My Schedule">
        <span>My Schedule</span>
      </div>

      <div class="side-button">
        <img src="image/subject.png" alt="My Subject">
        <span>My Subject</span>
      </div>

      <div class="side-button">
        <img src="image/feedbackicon.png" alt="Feedback">
        <span>Feedback</span>
      </div>
    </div>

  <footer>
    2025 LumiLearnHub. All rights reserved
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    let selected = localStorage.getItem("selectedPackage");
if (selected) {
  let { subject, day, timeSlot } = JSON.parse(selected);

  const validDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];
  const validTimes = [
    "8.30am - 10.30am",
    "12.30pm - 2.30pm",
    "4.30pm - 6.30pm",
    "9.30pm - 10.30pm",
    "11.30pm - 12.30am"
  ];

  let timeIndex = -1;
  document.querySelectorAll(".schedule thead th").forEach((th, index) => {
    if (th.textContent.trim() === timeSlot) timeIndex = index;
  });

  document.querySelectorAll(".schedule tbody tr").forEach(row => {
    if (row.cells[0].textContent.trim() === day) {
      row.cells[timeIndex].textContent = subject;
    }
  });

  localStorage.removeItem("selectedPackage"); // Clear after use
}
    // Back to home button
    document.querySelector(".back-button").addEventListener("click", function () {
        window.location.href = "studentinterface.php";
    });

    // User profile icon
    document.querySelector(".user-top-icon").addEventListener("click", function () {
        window.location.href = "profile2.html";
    });

    // Explore Subject Button
    document.querySelector(".button-group button:nth-child(1)").addEventListener("click", function () {
        window.location.href = "searchsubject.php";
    });

    // Find a Tutor Button
    document.querySelector(".button-group button:nth-child(2)").addEventListener("click", function () {
        window.location.href = "findtutor.php";
    });

    // Top Tutors Button
    document.querySelector(".button-group button:nth-child(3)").addEventListener("click", function () {
        window.location.href = "toptutors.php";
    });

    // My Subject Button
    document.querySelector(".side-button:nth-child(2)").addEventListener("click", function () {
        window.location.href = "subjectstudent.php";
    });

    // Feedback Button
    document.querySelector(".side-button:nth-child(3)").addEventListener("click", function () {
        window.location.href = "feedbackstudent.php";
    });

    function validateInput(subject, day, timeSlot) {
        const validDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];
        const validTimeSlots = [
            "8.30am - 10.30am",
            "12.30pm - 2.30pm",
            "4.30pm - 6.30pm",
            "9.30pm - 10.30pm",
            "11.30pm - 12.30am"
        ];

        if (!subject || subject.trim() === "") {
            alert("Subject name cannot be empty.");
            return false;
        }
        
        if (!validDays.includes(day)) {
            alert("Invalid day entered. Please enter one of the following: " + validDays.join(", "));
            return false;
        }
        
        if (!validTimeSlots.includes(timeSlot)) {
            alert("Invalid time slot entered. Please enter one of the following: " + validTimeSlots.join("\n"));
            return false;
        }
        
        return true;
    }

    document.querySelector(".icon-btn.add").addEventListener("click", function () {
  window.open("choosepackage.html", "_blank", "width=600,height=600");
});

    // ✅ Edit Existing Schedule Entry with Validation
    document.querySelector(".icon-btn.edit").addEventListener("click", function () {
        let currentSubject = prompt("Enter the current subject to edit:");
        let newDay = prompt("Enter the new day (e.g., 'Tuesday'):");
        let newTimeSlot = prompt("Enter the new time slot (e.g., '12.30pm - 2.30pm'):");

        if (!validateInput(currentSubject, newDay, newTimeSlot)) return;

        let table = document.querySelector(".schedule tbody");
        let subjectFound = false;
        let newTimeIndex = -1;

        // Get the correct column index for the new time slot
        document.querySelectorAll(".schedule thead th").forEach((th, index) => {
            if (th.textContent.trim() === newTimeSlot) {
                newTimeIndex = index;
            }
        });

        table.querySelectorAll("tr").forEach(row => {
            row.querySelectorAll("td").forEach((cell, index) => {
                if (index > 0 && cell.textContent.trim() === currentSubject) {
                    subjectFound = true;
                    cell.textContent = ""; // Clear old subject

                    if (row.cells[0].textContent.trim() === newDay) {
                        row.cells[newTimeIndex].textContent = currentSubject; // Move subject to new slot
                    }
                }
            });
        });

        if (!subjectFound) alert("Subject not found in the schedule. Try again.");
    });
  });



</script>

</body>
</html>
