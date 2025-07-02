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

// Dapatkan semua subjek dengan status 'Approved' daripada table subject
$query = "SELECT * FROM subject";
$result = $conn->query($query);

// Dapatkan subjek yang sudah dipohon oleh tutor daripada table tutor_subject dengan status 'Approved'
$queryApplied = "SELECT subjectID, status FROM tutor_subject WHERE tutorID = ?";
$stmtApplied = $conn->prepare($queryApplied);
$stmtApplied->bind_param("i", $tutorID);
$stmtApplied->execute();
$appliedSubjectsResult = $stmtApplied->get_result();

// Simpan ID subjek yang sudah dipohon oleh tutor dengan status 'Approved'
$appliedSubjectStatuses = []; // subjectID => status
while ($row = $appliedSubjectsResult->fetch_assoc()) {
    $appliedSubjectStatuses[$row['subjectID']] = $row['status'];
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
    <a href="profiletutor.php">
      <div class="profile-icon"></div>
    </a>  
  </div>

<div class="container">
  <div class="sidebar">
    <div class="menu-title">MENU OPTION</div>
    <a href="applicationtutor.php" class="menu-item">
      <span>Subject Tutoring Request</span>
      <img src="image/requesticon.png" alt="request icon" class="menu-icon">
    </a>
    <a href="mystudenttutor.php" class="menu-item">
      <span>My Student</span>
      <img src="image/usericon.png" alt="user icon" class="menu-icon">
    </a>
    <a href="scheduletutor.php" class="menu-item">
      <span>My Schedule</span>
      <img src="image/calendaricon.png" alt="calendar icon" class="menu-icon">
    </a>
    <a href="feedbacktutor.php" class="menu-item">
      <span>Feedback</span>
      <img src="image/feedbackicon.png" alt="feedback icon" class="menu-icon">
    </a>
  </div>

    <div class="main-content">
      <div class="content-section">
        <!-- Subject List Section -->
        <div class="subject-list-container">
          <h3>Subjects to Apply</h3>
          <ul class="subject-list">
           <?php while ($row = $result->fetch_assoc()): 
  $subjectID = $row['subjectID'];
  $subjectName = $row['subject_name'];
  $status = isset($appliedSubjectStatuses[$subjectID]) ? strtolower($appliedSubjectStatuses[$subjectID]) : null;
?>
  <li>
    <?php if ($status === 'approved'): ?>
      <div class="subject-box applied">
        <span class="subject-name"><?php echo $subjectName; ?></span>
        <span class="status">Approved</span>
      </div>
    <?php elseif ($status === 'pending'): ?>
  <div class="subject-box pending">
    <span class="subject-name"><?php echo $subjectName; ?></span>
    <span class="status">Pending</span>
  </div>

    <?php elseif ($status === 'rejected'): ?>
      <a href="applicationtutor.php?subjectID=<?php echo $subjectID; ?>" class="subject-box rejected">
        <span class="subject-name"><?php echo $subjectName; ?></span>
        <span class="status">Rejected - Reapply</span>
      </a>
    <?php else: ?>
      <a href="applicationtutor.php?subjectID=<?php echo $subjectID; ?>" class="subject-box">
        <span class="subject-name"><?php echo $subjectName; ?></span>
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
