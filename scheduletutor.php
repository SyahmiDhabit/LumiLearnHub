<?php
session_start();
include 'connection.php'; // Sambung ke pangkalan data

// Semak jika tutorID wujud dalam sesi
if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.html");
    exit();
}

// Ambil data tutor dari sesi
$tutorID = $_SESSION['tutor_id'];

// Dapatkan data jadual dari pangkalan data, disusun mengikut hari dan waktu
$query = "
    SELECT * 
    FROM tutor_schedule 
    WHERE tutorID = ? 
    ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
             CASE 
                WHEN time_slot = '8:30am - 10:30am' THEN 1
                WHEN time_slot = '12:30pm - 2:30pm' THEN 2
                WHEN time_slot = '4:30pm - 6:30pm' THEN 3
                WHEN time_slot = '9:30pm - 10:30pm' THEN 4
                WHEN time_slot = '11:30pm - 12:30am' THEN 5
             END";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$result = $stmt->get_result();
$schedule = [];

while ($row = $result->fetch_assoc()) {
    $schedule[] = $row; // Menyimpan jadual tutor dalam array
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schedule Tutor</title>
  <link rel="stylesheet" href="scheduletutor.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  
<div class="header">
  <a href="tutorinterface.php" class="brand">LumiLearnHub</a>
  <div class="welcome">WELCOME, <?php echo strtoupper($_SESSION['tutor_fullname']); ?>!</div>
  <a href="profile.html">
    <div class="profile-icon"></div>
  </a>
</div>

<div class="container">
  <div class="sidebar">
    <div class="menu-title">MENU OPTION</div>
    <a href="scheduletutor.php" class="menu-chosen">
      <span>My Schedule</span>
      <img src="image/calendaricon.png" alt="calendar icon" class="menu-icon">
    </a>
    <a href="mystudenttutor.html" class="menu-item">
      <span>My Student</span>
      <img src="image/usericon.png" alt="user icon" class="menu-icon">
    </a>
    <a href="feedbacktutor.html" class="menu-item">
      <span>Feedback</span>
      <img src="image/feedbackicon.png" alt="feedback icon" class="menu-icon">
    </a>
    <a href="applicationtutor.php" class="menu-item">
      <span>Subject Tutoring Request</span>
      <img src="image/requesticon.png" alt="request icon" class="menu-icon">
    </a>
  </div>

  <div class="main-content">
    <form method="POST" action="add_scheduletutor.php">
      <div class="form-group">
        <label for="day">Day:</label>
        <select id="day" name="day" class="form-control" required>
          <option value="Monday">Monday</option>
          <option value="Tuesday">Tuesday</option>
          <option value="Wednesday">Wednesday</option>
          <option value="Thursday">Thursday</option>
          <option value="Friday">Friday</option>
          <option value="Saturday">Saturday</option>
          <option value="Sunday">Sunday</option>
        </select>
      </div>

      <div class="form-group">
        <label for="time_slot">Time Slot:</label>
        <select id="time_slot" name="time_slot" class="form-control" required>
          <option value="8:30am - 10:30am">8:30am - 10:30am</option>
          <option value="12:30am - 2:30pm">12:30pm - 2:30pm</option>
          <option value="4:30pm - 6:30pm">4:30pm - 6:30pm</option>
          <option value="9:30pm - 10:30pm">9:30pm - 10:30pm</option>
          <option value="11:30pm - 12:30am">11:30pm - 12:30am</option>
        </select>
      </div>

      <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" class="form-control" required placeholder="Enter subject" />
      </div>

      <div class="form-group">
        <label for="student_name">Student Name:</label>
        <input type="text" id="student_name" name="student_name" class="form-control" required placeholder="Enter student name" />
      </div>

      <button type="submit" class="action-btn" name="add_schedule">
        <i class="fas fa-plus-circle"></i> Add Schedule
      </button>
    </form>

    <table class="schedule-table">
      <thead>
        <tr>
          <th>Time<br>Day</th>
          <th>Subject</th>
          <th>Student</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($schedule as $row): ?>
          <tr>
            <td><?php echo $row['day'] . " " . $row['time_slot']; ?></td>
            <td><?php echo $row['subject']; ?></td>
            <td><?php echo $row['student_name']; ?></td>
            <td>
              <!-- Form untuk memadam jadual -->
              <form method="POST" action="delete_scheduletutor.php">
                <!-- Hantar ID Jadual untuk Dihapuskan -->
                <input type="hidden" name="schedule_id" value="<?php echo $row['scheduleID']; ?>" />
                <button type="submit" class="action-btn-remove" name="delete_schedule">
                  <i class="fas fa-trash-alt"></i> Remove
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
