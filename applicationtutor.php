<?php
session_start();

if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.php");
    exit();
}

$tutorID = $_SESSION['tutor_id'];
$tutorFullname = $_SESSION['tutor_fullname'];

include 'connection.php';
$query = "SELECT subjectID, subject_name FROM subject";
$result = $conn->query($query);
$subjects = [];
$selectedSubjectID = isset($_GET['subjectID']) ? $_GET['subjectID'] : null;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$queryApplied = "SELECT subjectID, status FROM tutor_subject WHERE tutorID = ?";
$stmtApplied = $conn->prepare($queryApplied);
$stmtApplied->bind_param("i", $tutorID);
$stmtApplied->execute();
$appliedSubjectsResult = $stmtApplied->get_result();

$appliedSubjectStatus = [];
while ($row = $appliedSubjectsResult->fetch_assoc()) {
    $appliedSubjectStatus[$row['subjectID']] = $row['status'];
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subject Application</title>
  <link rel="stylesheet" href="applicationtutor.css" />
</head>
<body>
<div class="header">
  <a href="tutorinterface.php" class="brand">LumiLearnHub</a>
  <div class="welcome">WELCOME, <?php echo strtoupper($tutorFullname); ?>!</div>
    <a href="profiletutor.php">
     <div class="profile-icon"></div>
      </a>  
    </div></div>

<div class="container">
  <div class="sidebar">
    <div class="menu-title">MENU OPTION</div>
    <a href="applicationtutor.php" class="menu-chosen">
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
    <section class="application-form">
      <h2>Application for Subject Tutoring</h2>

      <form method="POST" action="submitapplication.php">
        <div class="form-group">
          <label for="subject">Subject:</label>
          <select id="subject" name="subject" class="form-control" required>
  <?php
  foreach ($subjects as $subject) {
      $subjectID = $subject['subjectID'];
      $subjectName = $subject['subject_name'];
      $status = isset($appliedSubjectStatus[$subjectID]) ? $appliedSubjectStatus[$subjectID] : null;
      $selected = ($subjectID == $selectedSubjectID) ? 'selected' : '';
      $disabled = ($status && strtolower($status) !== 'rejected') ? 'disabled' : '';
      $statusText = $status && strtolower($status) !== 'rejected' ? " ($status)" : '';
      echo "<option value='$subjectID' $selected $disabled>$subjectName$statusText</option>";
  }
  ?>
</select>

        </div>

        <div class="form-group">
          <label for="level">Level:</label>
          <select id="level" name="level" class="form-control" required>
            <option value="Primary School">Primary School</option>
            <option value="Secondary School">Secondary School</option>
            <option value="SPM">SPM</option>
          </select>
        </div>

        <fieldset class="form-group">
          <legend>Duration:</legend>
          <label><input type="checkbox" name="duration[]" value="3 Months" /> 3 Months</label>
          <label><input type="checkbox" name="duration[]" value="6 Months" /> 6 Months</label>
          <label>
            <input type="checkbox" id="customCheck" name="duration[]" value="Custom" /> Custom:
            <input type="text" id="customText" name="custom_duration" class="form-control" placeholder="Enter duration" style="display:none;" />
          </label>
        </fieldset>

        <div class="form-group">
          <label for="qualification">Qualification:</label>
          <textarea id="qualification" name="qualification" class="form-control" rows="5" required></textarea>
        </div>

        <input type="hidden" name="tutorID" value="<?php session_start(); echo $_SESSION['tutor_id']; ?>">

        <button type="submit" class="apply-btn">APPLY</button>
      </form>
    </section>
  </div>
</div>

<script>
  const customCheck = document.getElementById("customCheck");
  const customText = document.getElementById("customText");

  customCheck.addEventListener("change", () => {
    customText.style.display = customCheck.checked ? "inline-block" : "none";
  });
  
</script>
</body>
</html>
