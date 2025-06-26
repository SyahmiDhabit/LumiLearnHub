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
    body {
      background:rgb(224, 177, 179);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-top: 40px;
    }

    form {
      background-color: #ffffff;
      max-width: 500px;
      margin: 30px auto;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
      margin-top: 10px;
      margin-bottom: 5px;
      font-weight: bold;
      color: #34495e;
    }

    select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    select:focus, textarea:focus {
      border-color: #3498db;
      outline: none;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    input[type="submit"] {
      background-color:rgb(209, 119, 119);
      color: white;
      font-weight: bold;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
      transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }
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
