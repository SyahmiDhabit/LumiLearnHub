<?php
session_start();

if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.php");
    exit;
}

$tutorID = $_SESSION['tutor_id'];
include('connection.php'); 

$tutorID = $_SESSION['tutor_id'];
$tutorFullname = $_SESSION['tutor_fullname']; 


$feedbackQuery = "
  SELECT 
    s.subject_name,
    stu.student_fullName,
    f.rate,
    f.comment
  FROM tutor_subject ts
  JOIN subject s ON ts.subjectID = s.subjectID
  JOIN student_subject ss ON ss.subjectID = s.subjectID
  JOIN student stu ON ss.studentID = stu.studentID
  LEFT JOIN feedback f 
      ON f.studentID = stu.studentID AND f.subjectID = s.subjectID
  WHERE ts.tutorID = $tutorID
  GROUP BY s.subjectID, stu.studentID
";


$result = $conn->query($feedbackQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tutor Feedback</title>
  <link rel="stylesheet" href="feedbacktutor.css" />
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
    <a href="feedbacktutor.php" class="menu-chosen">
      <span>Feedback</span>
      <img src="image/feedbackicon.png" alt="feedback icon" class="menu-icon">
    </a>
    <a href="applicationtutor.php" class="menu-item">
        <span>Subject Tutoring Request</span>
      <img src="image/requesticon.png" alt="request icon" class="menu-icon">
    </a>
  </div>

    <div class="feedback-section">
      <h2>Feedback</h2>
        <div class="feedback-scroll">
      <table class="feedback-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Subject</th>
            <th>Student</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody>
    <?php
        $no = 1;
        if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
         $student = htmlspecialchars($row['student_fullName'], ENT_QUOTES);
        $comment = htmlspecialchars($row['comment'] ?? 'No comment', ENT_QUOTES);
        echo "<tr onclick=\"showCommentPopup('{$student}', '{$comment}')\">";

        echo "<td>{$no}</td>";
        echo "<td>{$row['subject_name']}</td>";
        echo "<td>{$row['student_fullName']}</td>";
        echo "<td>";
        if ($row['rate'] !== null && $row['rate'] !== '') {
         echo "{$row['rate']} <img src='image/starfeedback.png' alt='starfeedback' class='menu-icon'>";
          } else {
       echo "Unrated";
          }
          echo "</td>";
        echo "</tr>";
        $no++;

         }
        } else {
         echo "<tr><td colspan='4'>No students found.</td></tr>";
        }
        ?>
         <div id="commentPopup" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); background:#fff; color:#000; padding:20px; border-radius:10px; z-index:1000; box-shadow:0 0 15px rgba(0,0,0,0.5); max-width:400px;">
        <p><strong>STUDENT:</strong> <span id="popupStudent"></span></p>
        <p><strong>COMMENT:</strong> <span id="popupComment"></span></p>
        <button onclick="document.getElementById('commentPopup').style.display='none'" style="margin-top:10px; padding:5px 10px; background:#ac5656; color:white; border:none; border-radius:5px;">Close</button>
        </div>


        </tbody>
      </table>
    </div>
  </div>
  </div>
  <script>
    function showCommentPopup(student, comment) {
    document.getElementById('popupStudent').innerText = student;
    document.getElementById('popupComment').innerText = comment;
    document.getElementById('commentPopup').style.display = 'block';
  }
</script>


</body>
</html>
