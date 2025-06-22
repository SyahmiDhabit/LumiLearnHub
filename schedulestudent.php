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
  <title>LumiLearnHub - Schedule Student</title>
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
    learning progress all in one convenient place. Let’s make the most of your time here and reach your goals together!
  </p>
</div>

<div class="button-group">
  <button><img src="image/subject.png" style="width: 20px; margin-right: 8px;" />Explore Subject</button>
  <button><img src="image/findtutor.png" style="width: 20px; margin-right: 8px;" />Find a Tutor</button>
  <button><img src="image/viewfeedback.png" style="width: 20px; margin-right: 8px;" />View Feedback</button>
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
      <?php
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $slots = ['8.30am - 10.30am', '12.30pm - 2.30pm', '4.30pm - 6.30pm', '9.30pm - 10.30pm', '11.30pm - 12.30am'];

        $schedule = [];
        $sql = "SELECT day, time_slot, subject FROM schedule WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $studentID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $schedule[$row['day']][$row['time_slot']] = $row['subject'];
        }

        foreach ($days as $day) {
            echo "<tr><td>$day</td>";
            foreach ($slots as $slot) {
                $subject = $schedule[$day][$slot] ?? "";
                echo "<td>" . htmlspecialchars($subject) . "</td>";
            }
            echo "</tr>";
        }
      ?>
    </tbody>
  </table>

  <div class="side-buttons">
    <div id="my-schedule-btn" class="side-button"><img src="image/calendaricon.png" /><span>My Schedule</span></div>
    <div class="side-button"><img src="image/subject.png" /><span>My Subject</span></div>
    <div class="side-button"><img src="image/feedbackicon.png" /><span>Feedback</span></div>
  </div>
</div>

<footer>2025 LumiLearnHub. All rights reserved</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
  let selected = localStorage.getItem("selectedPackage");
  if (selected) {
    let { subject, day, timeSlot } = JSON.parse(selected);

    $.post("store_schedule.php", {
      subject: subject,
      day: day,
      time_slot: timeSlot
    }, function(response) {
      if (response === "DUPLICATE") {
        alert("You have already selected this subject. Please choose a different one.");
        localStorage.removeItem("selectedPackage");
        window.location.href = "schedulestudent.php";
      } else if (response === "SUCCESS") {
        localStorage.removeItem("selectedPackage");
        location.reload();
      } else {
        alert("Something went wrong. Please try again.");
      }
    });
  }

  document.querySelector(".back-button").onclick = () => window.location.href = "studentinterface.php";
  document.querySelector(".user-top-icon").onclick = () => window.location.href = "profilestudent.php";
  document.querySelector(".button-group button:nth-child(1)").onclick = () => window.location.href = "searchsubject.php";
  document.querySelector(".button-group button:nth-child(2)").onclick = () => window.location.href = "findtutor.php";
  document.querySelector(".button-group button:nth-child(3)").onclick = () => window.location.href = "viewfeedback.php";
  document.querySelector(".side-button:nth-child(2)").onclick = () => window.location.href = "subjectstudent.php";
  document.querySelector(".side-button:nth-child(3)").onclick = () => window.location.href = "feedbackstudent.php";

  document.querySelector(".icon-btn.add").onclick = () => {
    window.open("choosepackage.html", "_blank", "width=600,height=600");
  };

  document.querySelector(".icon-btn.edit").onclick = function () {
  let oldSubject = prompt("Enter the subject name you want to move:");
  if (!oldSubject) return;

  let newDay = prompt("Enter the new day (e.g., Monday):");
  let newTime = prompt("Enter the new time slot (e.g., 12.30pm - 2.30pm):");

  const validDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];
  const validTimes = [
    "8.30am - 10.30am",
    "12.30pm - 2.30pm",
    "4.30pm - 6.30pm",
    "9.30pm - 10.30pm",
    "11.30pm - 12.30am"
  ];

  if (!validDays.includes(newDay)) {
    alert("Invalid day. Please try again.");
    return;
  }

  if (!validTimes.includes(newTime)) {
    alert("Invalid time slot. Please try again.");
    return;
  }

  $.post("update_schedule.php", {
    oldSubject: oldSubject.trim(),
    newDay: newDay.trim(),
    newTime: newTime.trim()
  }, function(response) {
    if (response === "UPDATED") {
      alert("Schedule updated successfully.");
      location.reload();
    } else if (response === "NOT_FOUND") {
      alert("Subject not found in your schedule.");
    } else {
      alert("Update failed. Please try again.");
    }
  });
};
});
</script>

</body>
</html>
