<?php
session_start();

// Ensure the tutor is logged in
if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.php");
    exit;
}

$tutorID = $_SESSION['tutor_id'];
include('connection.php'); // Include database connection

// Query to get the list of students enrolled in subjects taught by the logged-in tutor
$studentQuery = "
    SELECT 
        s.subject_name,
        st.student_fullName,
        st.student_phoneNumber,
        st.student_email
    FROM student_subject ss
    JOIN subject s ON ss.subjectID = s.subjectID
    JOIN student st ON ss.studentID = st.studentID
    WHERE ss.tutorID = ? AND ss.status = 'Enrolled'
    ORDER BY st.student_fullName, s.subject_name
";

$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Student - Tutor</title>
  <link rel="stylesheet" href="mystudenttutor.css" />
  <script>
    function showContactPopup(studentName, phoneNumber, email) {
      document.getElementById('popupStudentName').innerText = studentName;
      document.getElementById('popupPhoneNumber').innerText = phoneNumber;
      document.getElementById('popupEmail').innerText = email;
      document.getElementById('contactPopup').style.display = 'block';
    }

    function closePopup() {
      document.getElementById('contactPopup').style.display = 'none';
    }
  </script>
</head>
<body>
  <div class="header">
  <a href="tutorinterface.php" class="brand">LumiLearnHub</a>
  <div class="welcome">WELCOME, <?php echo strtoupper($_SESSION['tutor_fullname']); ?>!</div>
  <a href="profiletutor.php">
    <div class="profile-icon"></div>
  </a>
</div>

  <div class="container">
    <div class="sidebar">
      <div class="menu-title">MENU OPTION</div>

      <a href="scheduletutor.php" class="menu-item">
        <span>My Schedule</span>
        <img src="image/calendaricon.png" alt="calendar icon" class="menu-icon">
      </a>

      <a href="mystudenttutor.php" class="menu-chosen">
        <span>My Student</span>
        <img src="image/usericon.png" alt="user icon" class="menu-icon">
      </a>

      <a href="feedbacktutor.php" class="menu-item">
        <span>Feedback</span>
        <img src="image/feedbackicon.png" alt="feedback icon" class="menu-icon">
      </a>

      <a href="applicationtutor.php" class="menu-item">
        <span>Subject Tutoring Request</span>
        <img src="image/requesticon.png" alt="request icon" class="menu-icon">
      </a>
    </div>

    <div class="main-content">
    <div class="top-buttons"></div>

      <div class="schedule-tittle">
        <span class="title-size">My Student</span>
        <table class="schedule-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Subject</th>
              <th>Student</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row['subject_name'] . "</td>";
                    echo "<td>" . $row['student_fullName'] . "</td>";
                    echo "<td><button onclick=\"showContactPopup('{$row['student_fullName']}', '{$row['student_phoneNumber']}', '{$row['student_email']}')\">Contact</button></td>";
                    echo "</tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='4'>No students found for your subjects.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Popup for student contact info -->
  <div id="contactPopup" class="contact-popup">
    <div class="popup-content">
      <h3>Contact Information</h3>
      <p><strong>Student Name:</strong> <span id="popupStudentName"></span></p>
      <p><strong>Phone Number:</strong> <span id="popupPhoneNumber"></span></p>
      <p><strong>Email:</strong> <span id="popupEmail"></span></p>
      <button onclick="closePopup()">Close</button>
    </div>
  </div>
</body>
</html>
