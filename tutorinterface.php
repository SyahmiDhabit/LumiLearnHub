<?php
session_start();

// Semak jika tutorID wujud dalam sesi
if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.php");
    exit();
}

// Ambil data tutor daripada sesi
$tutorID = $_SESSION['tutor_id'];
$tutorFullname = $_SESSION['tutor_fullname'];

// Sambung ke pangkalan data
include 'connection.php';

// Dapatkan semua subjek daripada table subject
$query = "SELECT * FROM subject";
$result = $conn->query($query);

// Dapatkan subjek yang sudah dipohon oleh tutor daripada table tutor_subject
$queryApplied = "SELECT subjectID FROM tutor_subject WHERE tutorID = ?";
$stmtApplied = $conn->prepare($queryApplied);
$stmtApplied->bind_param("i", $tutorID);
$stmtApplied->execute();
$appliedSubjectsResult = $stmtApplied->get_result();

// Simpan ID subjek yang sudah dipohon oleh tutor
$appliedSubjectIDs = [];
while ($row = $appliedSubjectsResult->fetch_assoc()) {
    $appliedSubjectIDs[] = $row['subjectID'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tutor Dashboard</title>
  <link rel="stylesheet" href="tutorinterface.css" />
</head>
<body>
  <div class="header">
    <a href="tutorinterface.php" class="brand">LumiLearnHub</a>
    <div class="welcome">WELCOME, <?php echo strtoupper($tutorFullname); ?>!</div>
    <div class="profile-icon"></div>
  </div>

  <div class="container">
    <div class="sidebar">
      <div class="menu-title">MENU OPTION</div>
      <a href="scheduletutor.php" class="menu-item">
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
      <div class="content-section">
        <!-- Subject List Section -->
        <div class="subject-list-container">
          <h3>Subjects to Apply</h3>
          <ul class="subject-list">
            <?php while ($row = $result->fetch_assoc()): ?>
              <li>
                <?php if (in_array($row['subjectID'], $appliedSubjectIDs)): ?>
                  <!-- If applied, show as non-clickable -->
                  <div class="subject-box applied">
                    <span class="subject-name"><?php echo $row['subject_name']; ?></span>
                    <span class="status">Applied</span>
                  </div>
                <?php else: ?>
                  <!-- For available subjects, make them clickable -->
                  <a href="applicationtutor.php?subjectID=<?php echo $row['subjectID']; ?>" class="subject-box">
                    <span class="subject-name"><?php echo $row['subject_name']; ?></span>
                  </a>
                <?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>

        <!-- Image Section -->
        <div class="qualities">
          <img src="image/goodtutor.jpg" alt="Tutor Qualities Poster" />
        </div>
      </div>
    </div>
  </div>
</body>
</html>
