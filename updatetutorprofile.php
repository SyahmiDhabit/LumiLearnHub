<?php
session_start();
include 'connection.php';

// Check if tutor is logged in
if (!isset($_SESSION['tutor_username'])) {
    header("Location: tutorlogin.php");
    exit;
}

$tutor_username = $_SESSION['tutor_username'];

// Fetch tutor ID and existing profile info
$stmt = $conn->prepare("SELECT * FROM tutor WHERE tutor_username = ?");
$stmt->bind_param("s", $tutor_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Tutor not found.";
    exit;
}

$tutor = $result->fetch_assoc();
$tutorID = $tutor['tutorID'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName']);
    $age = intval($_POST['age']);
    $dob = $_POST['dob'];
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);
    $email = trim($_POST['email']);

    $update = $conn->prepare("UPDATE tutor 
        SET tutor_fullName = ?, tutor_age = ?, tutor_dob = ?, tutor_phoneNumber = ?, tutor_bio = ?, tutor_email = ?
        WHERE tutorID = ?");
    $update->bind_param("sissssi", $fullName, $age, $dob, $phone, $bio, $email, $tutorID);

    if ($update->execute()) {
        $_SESSION['tutor_fullname'] = $fullName; // Optional: update session too
        header("Location: profiletutor.php"); // Redirect after update
        exit;
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="updatestudentprofile.css" type="text/css">
  <title>Update Tutor Profile</title>
</head>
<body>

<h2>Update Tutor Profile</h2>

<?php if (isset($error)): ?>
  <div class="message error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
  <label for="fullName">Full Name:</label>
  <input type="text" name="fullName" id="fullName" value="<?= htmlspecialchars($tutor['tutor_fullName']) ?>" required>

  <label for="age">Age:</label>
  <input type="number" name="age" id="age" value="<?= htmlspecialchars($tutor['tutor_age']) ?>" required>

  <label for="dob">Date of Birth:</label>
  <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($tutor['tutor_dob']) ?>" required>

  <label for="phone">Phone Number:</label>
  <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($tutor['tutor_phoneNumber']) ?>" required>

  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?= htmlspecialchars($tutor['tutor_email']) ?>" required>

  <label for="bio">Bio:</label>
  <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($tutor['tutor_bio']) ?></textarea>

  <button type="submit">Update Profile</button>
</form>

</body>
</html>
