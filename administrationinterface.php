<?php 
include 'connection.php';

$tutors = $conn->query("SELECT * FROM tutor");
$students = $conn->query("SELECT * FROM student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="administrationinterface.css" type="text/css" />

</head>
<body>
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
<!-- Navigation -->
 <div class = "firstMenu">
        <ul>
            <li><a href="login.html">TUTORING <br>SUBJECT <br> APPLICATION</a></li>
        </ul>
    </div>

    <div class="Menu">
         <ul>
                <li><a href="signup.html">FEEDBACK</a></li>
                <li><a href="login.html">USER</a></li>
                <li><a href="login.html" id="home">HOME</a></li>
                <li><a href="mainpage.php">LOGOUT</a></li>
         </ul>
        </div> 



<div class="card-box-tutor">
<!-- Tutors Section -->
<h2>List of Tutors</h2>
<input type="text" id="searchT" placeholder="Search tutor..." onkeyup="filterList('searchT', 'tutor-list')">
<div id="tutor-list" class="tutor-list">
<?php while ($tutor = $tutors->fetch_assoc()) { ?>
  <div class="user-card" onclick="showPopup(
      '<?php echo htmlspecialchars($tutor['tutor_fullName']); ?>',
      '<?php echo htmlspecialchars($tutor['tutor_username']); ?>',
      '<?php echo htmlspecialchars($tutor['tutor_email']); ?>',
      '<?php echo htmlspecialchars($tutor['tutor_phoneNumber']); ?>',
      '<?php echo htmlspecialchars($tutor['tutor_country']); ?>',
      '<?php echo htmlspecialchars($tutor['tutor_bio']); ?>'
  )">
    <p><?php echo $tutor['tutor_username']; ?></p>
  </div>
<?php } ?>
</div>
</div>



<div class="card-box-student">
<!-- Students Section -->
<h2>List of Students</h2>
<input type="text" id="searchS" placeholder="Search student..." onkeyup="filterList('searchS', 'student-list')">
<div id="student-list" class="student-list">
<?php while ($student = $students->fetch_assoc()) { ?>
  <div class="user-card" onclick="showPopup(
      '<?php echo htmlspecialchars($student['student_fullName']); ?>',
      '<?php echo htmlspecialchars($student['student_username']); ?>',
      '<?php echo htmlspecialchars($student['student_email']); ?>',
      '<?php echo htmlspecialchars($student['student_phoneNumber']); ?>',
      '<?php echo htmlspecialchars($student['student_country']); ?>',
      '<?php echo htmlspecialchars($student['student_bio']); ?>'
  )">
    <p><?php echo $student['student_username']; ?></p>
  </div>
<?php } ?>
</div>
</div>


<!-- Popup Profile -->
<div id="popup"  >
  <div>
    <button onclick="closePopup()">Close</button>
    <h2>Full Name : <span id="popup-name"></span></h2>
    <p><strong>Username:</strong> <span id="popup-username"></span></p>
    <p><strong>Email:</strong> <span id="popup-email"></span></p>
    <p><strong>Phone:</strong> <span id="popup-phone"></span></p>
    <p><strong>Country:</strong> <span id="popup-country"></span></p>
    <p><strong>Bio:</strong> <span id="popup-bio"></span></p>
  </div>
</div>

<!-- JavaScript -->
<script>
function showPopup(name, username, email, phone, country, bio) {
  document.getElementById("popup-name").innerText = name;
  document.getElementById("popup-username").innerText = username;
  document.getElementById("popup-email").innerText = email;
  document.getElementById("popup-phone").innerText = phone;
  document.getElementById("popup-country").innerText = country;
  document.getElementById("popup-bio").innerText = bio;
  document.getElementById("popup").style.display = "block";
}

function closePopup() {
  document.getElementById("popup").style.display = "none";
}

function filterList(inputId, listId) {
  let input = document.getElementById(inputId).value.toLowerCase();
  let list = document.getElementById(listId).getElementsByClassName('user-card');

  for (let i = 0; i < list.length; i++) {
    let name = list[i].innerText.toLowerCase();
    list[i].style.display = name.includes(input) ? "block" : "none";
  }
}
</script>

</body>
</html>
