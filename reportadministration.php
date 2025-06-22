<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="reportadministration.css" type="text/css" />
  <title>Report Administration</title>
</head>
<body>

<!-- NAVIGATION BAR -->
<div class="bar">
  <nav class="navbar">
    <div class="navleft">
      <h1 class="Lumilearn">LumiLearnHub</h1>
    </div>
    <div class="navcenter">
      <h1 class="admin">ADMINISTRATION</h1>
    </div>
    <div class="navright">
      <a class="userIcon">
        <img src="image/PpLogo.jpg" alt="User Icon" class="social-icon">
      </a>
    </div>
  </nav>
</div>

<!-- MENU -->
<div class="firstMenu">
  <ul>
    <li><a href="applicationadministration.php">TUTORING <br>SUBJECT <br> APPLICATION</a></li>
  </ul>
</div>

<div class="Menu">
  <ul>
    <li><a href="feedbackadministration.php">FEEDBACK</a></li>
    <li><a href="reportadministration.php" id="report">REPORT</a></li>
    <li><a href="administrationlist.php">LIST</a></li>
     <li><a href="mainpage.php" onclick="return confirmLogout()">LOGOUT</a></li>
  </ul>
</div>


<!-- REPORT SECTIONS -->
<div class="report-section">
  <h2>TUTOR REGISTRATION</h2>
  <form method="GET" style="margin: 20px 0;">
  <!-- Tutor Name Search (first) -->
  <label for="tutorSearch">Tutor Name:</label>
  <input type="text" name="tutorSearch" id="tutorSearch" placeholder="Enter tutor name..." value="<?= htmlspecialchars($_GET['tutorSearch'] ?? '') ?>">

  <!-- Subject Dropdown -->
  <label for="subject">Subject:</label>
  <select name="subject" id="subject">
    <option value="">All</option>
    <?php
      $subjects = $conn->query("SELECT DISTINCT subject_name FROM subject ORDER BY subject_name ASC");
      while ($s = $subjects->fetch_assoc()) {
        $selected = ($_GET['subject'] ?? '') == $s['subject_name'] ? 'selected' : '';
        echo "<option value='{$s['subject_name']}' $selected>{$s['subject_name']}</option>";
      }
    ?>
  </select>

  <!-- Status Dropdown -->
  <label for="status">Status:</label>
  <select name="status" id="status">
    <option value="">All</option>
    <option value="Pending" <?= ($_GET['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Approved" <?= ($_GET['status'] ?? '') == 'Approved' ? 'selected' : '' ?>>Approved</option>
    <option value="Rejected" <?= ($_GET['status'] ?? '') == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
  </select>

  <!-- Submit -->
  <button type="submit">Filter</button>
</form>


  <div class="scroll-box">
  <table border="1">
    <tr>
      <th>Tutor ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Subjects Taught</th>
      <th>Status</th>
    </tr>
    <?php
    $subjectFilter = $_GET['subject'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$tutorSearch = $_GET['tutorSearch'] ?? '';

$tutorQuery = "
  SELECT 
    t.tutorID, 
    t.tutor_fullName, 
    t.tutor_email, 
    GROUP_CONCAT(s.subject_name SEPARATOR ', ') AS subjects, 
    ts.status
  FROM tutor t
  INNER JOIN tutor_subject ts ON t.tutorID = ts.tutorID
  INNER JOIN subject s ON ts.subjectID = s.subjectID
  WHERE 1
";

if (!empty($subjectFilter)) {
  $tutorQuery .= " AND s.subject_name = '" . $conn->real_escape_string($subjectFilter) . "'";
}
if (!empty($statusFilter)) {
  $tutorQuery .= " AND ts.status = '" . $conn->real_escape_string($statusFilter) . "'";
}
if (!empty($tutorSearch)) {
  $tutorQuery .= " AND t.tutor_fullName LIKE '%" . $conn->real_escape_string($tutorSearch) . "%'";
}

$tutorQuery .= " GROUP BY t.tutorID";


    $tutorResult = $conn->query($tutorQuery);
    while ($row = $tutorResult->fetch_assoc()) {
      echo "<tr>
              <td>{$row['tutorID']}</td>
              <td>{$row['tutor_fullName']}</td>
              <td>{$row['tutor_email']}</td>
              <td>{$row['subjects']}</td>
              <td>{$row['status']}</td>
            </tr>";
    }
    ?>
  </table>
</div>
</div>

<div class="report-section">
  <h2>STUDENT ENROLLMENT</h2>
  <form method="GET" style="margin: 20px 0;">
  <label for="studentSearch">Student Name:</label>
  <input type="text" name="studentSearch" id="studentSearch" placeholder="Enter student..." value="<?= htmlspecialchars($_GET['studentSearch'] ?? '') ?>">

  <label for="class">Class:</label>
  <select name="class" id="class">
    <option value="">All</option>
    <?php
    $classes = $conn->query("SELECT DISTINCT subject_name FROM subject ORDER BY subject_name ASC");
    while ($c = $classes->fetch_assoc()) {
      $selected = ($_GET['class'] ?? '') == $c['subject_name'] ? 'selected' : '';
      echo "<option value='{$c['subject_name']}' $selected>{$c['subject_name']}</option>";
    }
    ?>
  </select>

  <button type="submit">Filter</button>
</form>
  <div class="scroll-box">
  <table border="1">
    <tr>
      <th>Student Name</th>
      <th>Class Enrolled</th>
      <th>Tutor Name</th>
    </tr>
    <?php
   $studentSearch = $_GET['studentSearch'] ?? '';
$classFilter = $_GET['class'] ?? '';

$studentQuery = "
  SELECT st.student_fullName, sb.subject_name, t.tutor_fullName AS tutorName
  FROM student_subject ss
  JOIN student st ON ss.studentID = st.studentID
  JOIN subject sb ON ss.subjectID = sb.subjectID
  LEFT JOIN tutor_subject ts ON sb.subjectID = ts.subjectID
  LEFT JOIN tutor t ON ts.tutorID = t.tutorID
  WHERE 1
";

if (!empty($studentSearch)) {
  $studentQuery .= " AND st.student_fullName LIKE '%" . $conn->real_escape_string($studentSearch) . "%'";
}
if (!empty($classFilter)) {
  $studentQuery .= " AND sb.subject_name = '" . $conn->real_escape_string($classFilter) . "'";
}

$studentQuery .= " GROUP BY st.student_fullName, sb.subject_name";

    $studentResult = $conn->query($studentQuery);
    while ($row = $studentResult->fetch_assoc()) {
      echo "<tr>
              <td>{$row['student_fullName']}</td>
              <td>{$row['subject_name']}</td>
              <td>{$row['tutorName']}</td>
            </tr>";
    }
    
    ?>
  </table>
</div>
</div>



<div class="report-section">
  <h2>TUTOR RATING</h2>
  <form method="GET" style="margin: 20px 0;">
  <label for="tutorRatingSearch">Tutor Name:</label>
  <input type="text" name="tutorRatingSearch" id="tutorRatingSearch" placeholder="Enter tutor..." value="<?= htmlspecialchars($_GET['tutorRatingSearch'] ?? '') ?>">

  <label for="ratingSubject">Subject:</label>
  <select name="ratingSubject" id="ratingSubject">
    <option value="">All</option>
    <?php
    $subjects = $conn->query("SELECT DISTINCT subject_name FROM subject ORDER BY subject_name ASC");
    while ($s = $subjects->fetch_assoc()) {
      $selected = ($_GET['ratingSubject'] ?? '') == $s['subject_name'] ? 'selected' : '';
      echo "<option value='{$s['subject_name']}' $selected>{$s['subject_name']}</option>";
    }
    ?>
  </select>

  <button type="submit">Filter</button>
</form>
  <div class="scroll-box">
    <table border="1">
      <tr>
        <th>Tutor Name</th>
        <th>Class Name</th>
        <th>Average Rating</th>
        <th>Total Ratings</th>
      </tr>
      <?php
      $tutorRatingSearch = $_GET['tutorRatingSearch'] ?? '';
$ratingSubject = $_GET['ratingSubject'] ?? '';

$ratingQuery = "
  SELECT 
    t.tutor_fullName AS tutorName,
    s.subject_name AS className,
    ROUND(AVG(f.rate), 2) AS avgRating,
    COUNT(f.rate) AS totalRatings
  FROM feedback f
  JOIN subject s ON f.subjectID = s.subjectID
  JOIN tutor_subject ts ON ts.subjectID = s.subjectID
  JOIN tutor t ON ts.tutorID = t.tutorID
  WHERE 1
";

if (!empty($tutorRatingSearch)) {
  $ratingQuery .= " AND t.tutor_fullName LIKE '%" . $conn->real_escape_string($tutorRatingSearch) . "%'";
}
if (!empty($ratingSubject)) {
  $ratingQuery .= " AND s.subject_name = '" . $conn->real_escape_string($ratingSubject) . "'";
}

$ratingQuery .= " GROUP BY t.tutorID, s.subjectID";

                      
      $ratingResult = $conn->query($ratingQuery);
      while ($row = $ratingResult->fetch_assoc()) {
        echo "<tr>
                <td>{$row['tutorName']}</td>
                <td>{$row['className']}</td>
                <td>{$row['avgRating']}</td>
                <td>{$row['totalRatings']}</td>
              </tr>";
      }
      ?>
    </table>
  </div>
</div>

<script>
  function confirmLogout() {
  return confirm("Are you sure you want to logout?");
}
</script>
</body>
</html>
