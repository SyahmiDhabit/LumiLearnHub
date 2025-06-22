<?php
session_start();
include("connection.php");

if (!isset($_SESSION['studentID'])) {
    header("Location: studentlogin.html");
    exit();
}

$studentID = $_SESSION['studentID'];
$subjectID = $_GET['subjectID'] ?? '';
$tutorName = $_GET['tutor'] ?? ''; // Corrected from tutor_fullName

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'] ?? 0; // Corrected field name
    $comment = trim($_POST['comment'] ?? '');

    // Prevent duplicate feedback
    $checkSql = "SELECT * FROM feedback WHERE studentID = ? AND subjectID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $studentID, $subjectID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Already rated
        echo "<script>alert('You have already rated this subject.'); window.location.href='feedbackstudent.php';</script>";
        exit();
    }
    $checkStmt->close();

    // Insert new feedback
    $stmt = $conn->prepare("INSERT INTO feedback (studentID, subjectID, comment, rate, date) VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("iisi", $studentID, $subjectID, $comment, $rating);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: feedbackstudent.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Give Feedback</title>
  <style>
    form { max-width: 500px; margin: 40px auto; font-family: Arial; }
    label, textarea, select, input[type=submit] { display: block; width: 100%; margin-bottom: 10px; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Give Feedback for <?= htmlspecialchars($tutorName) ?></h2>
  <form method="post">
    <label for="rating">Rating (1 to 10):</label>
    <select name="rating" id="rating" required>
      <?php for ($i = 1; $i <= 10; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>

    <label for="comment">Comment:</label>
    <textarea name="comment" id="comment" rows="5" placeholder="Your feedback..." required></textarea>

    <input type="submit" value="Submit Feedback">
  </form>
</body>
</html>
