<?php
session_start();
include 'connection.php';

// Check if student is logged in
if (!isset($_SESSION['student_username'])) {
    header("Location: studentlogin.php");
    exit;
}

$student_username = $_SESSION['student_username'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form inputs
    $fullName = trim($_POST['fullName']);
    $age      = intval($_POST['age']);
    $dob      = $_POST['dob'];
    $phone    = trim($_POST['phone']);
    $bio      = trim($_POST['bio']);
    $email    = trim($_POST['email']);

    // Update query based on student_username
    $stmt = $conn->prepare("UPDATE student 
        SET student_fullName = ?, student_age = ?, student_dob = ?, student_phoneNumber = ?, student_bio = ?, student_email = ?
        WHERE student_username = ?");
    $stmt->bind_param("sisssss", $fullName, $age, $dob, $phone, $bio, $email, $student_username);

    if ($stmt->execute()) {
    // Redirect to profile page after successful update
    header("Location: profilestudent.php");
    exit;
} else {
    $error = "Update failed: " . $conn->error;
}

}

// Fetch current data to prefill form
$stmt = $conn->prepare("SELECT student_fullName, student_age, student_dob, student_phoneNumber, student_bio, student_email FROM student WHERE student_username = ?");
$stmt->bind_param("s", $student_username);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <link rel="stylesheet" href="updatestudentprofile.css" type="text/css">
  <title>Update Profile</title>
</head>
<body>

<h2>Update Student Profile</h2>

<?php if (isset($success)): ?>
  <div class="message success"><?= $success ?></div>
<?php elseif (isset($error)): ?>
  <div class="message error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
  <label for="fullName">Full Name:</label>
  <input type="text" name="fullName" id="fullName" value="<?= htmlspecialchars($student['student_fullName']) ?>" required>

  <label for="age">Age:</label>
  <input type="number" name="age" id="age" value="<?= htmlspecialchars($student['student_age']) ?>" required>

  <label for="dob">Date of Birth:</label>
  <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($student['student_dob']) ?>" required>

  <label for="phone">Phone Number:</label>
  <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($student['student_phoneNumber']) ?>" required>

  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?= htmlspecialchars($student['student_email']) ?>" required>

  <label for="bio">Bio:</label>
  <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($student['student_bio']) ?></textarea>

  <button type="submit">Update Profile</button>
</form>

</body>
</html>
