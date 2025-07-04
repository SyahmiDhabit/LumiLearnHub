<?php
include 'connection.php';

// Check if a studentID is selected (clicked)
$selectedStudentID = isset($_GET['studentID']) ? intval($_GET['studentID']) : 0;

// 1) Fetch list of students who gave feedback with average rating and count
$studentListQuery = "
    SELECT 
        f.studentID, 
        s.student_fullName AS studentName,
        COUNT(f.feedbackID) AS feedbackCount
    FROM feedback f
    JOIN student s ON f.studentID = s.studentID
    GROUP BY f.studentID, s.student_fullName
    ORDER BY s.student_fullName ASC
";

$studentListResult = mysqli_query($conn, $studentListQuery);

// Check for query error
if (!$studentListResult) {
    die("Database query failed: " . mysqli_error($conn));
}

// Fetch all students into an array for reuse
$studentsArray = [];
while ($row = mysqli_fetch_assoc($studentListResult)) {
    $studentsArray[] = $row;
}

// 2) If a student is selected, fetch their detailed feedbacks
$studentFeedbacks = [];
if ($selectedStudentID > 0) {
    $feedbackDetailsQuery = "
    SELECT 
        f.comment, 
        f.rate, 
        f.date,
        sub.subject_name AS subjectName,
        t.tutor_fullName AS tutorName
    FROM feedback f
    JOIN subject sub ON f.subjectID = sub.subjectID
    LEFT JOIN tutor_subject ts ON f.subjectID = ts.subjectID
    LEFT JOIN tutor t ON ts.tutorID = t.tutorID
    WHERE f.studentID = $selectedStudentID
    ORDER BY f.date DESC
";

    $feedbackResult = mysqli_query($conn, $feedbackDetailsQuery);
    if ($feedbackResult) {
        while ($row = mysqli_fetch_assoc($feedbackResult)) {
            $studentFeedbacks[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="feedbackadministration.css" type="text/css" />
<title>Feedback Administration</title>
<style>
/* Add quick styling */
.feedback-wrapper {
    display: flex;
    gap: 2rem;
    padding: 1rem;
    border: 3px solid #000;
}
.student-list {
    flex: 1;
    border: 3px solid #000;
    padding: 1rem;
    max-height: 600px;
    overflow-y: auto;
}
.student-list a {
    display: block;
    padding: 0.5rem 0;
    color: rgb(223, 226, 56);
    text-decoration: none;
    border-bottom: 1px solid #eee;
}
.student-list a:hover {
    background:rgb(224, 125, 217);
}
.student-list .selected {
    font-weight: bold;
    color: #003366;
}
.student-list .feedback-meta {
    font-size: 0.9em;
    color: white;
}
.feedback-details {
    flex: 2;
    border: 3px solid #000;
    padding: 1rem;
    max-height: 600px;
    overflow-y: auto;
}
.feedback-details h2 {
    margin-top: 0;
}
.feedback-entry {
    border-bottom: 1px solid #ddd;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
}
.feedback-entry:last-child {
    border-bottom: none;
}
.feedback-rate {
    font-weight: bold;
    color: rgb(233, 197, 101);
}
.feedback-date {
    font-size: 0.85rem;
    color: white;
}
</style>
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
            </a>
        </div>
    </nav>
</div>

<div class="firstMenu">
    <ul>
        <li><a href="applicationadministration.php">TUTORING <br />SUBJECT <br /> APPLICATION</a></li>
    </ul>
</div>

<div class="Menu">
    <ul>
        <li><a href="feedbackadministration.php" id="feedback">FEEDBACK</a></li>
        <li><a href="reportadministration.php">REPORT</a></li>
        <li><a href="administrationlist.php">LIST</a></li>
        <li><a href="mainpage.php" onclick="return confirmLogout()">LOGOUT</a></li>
    </ul>
</div>

<div class="feedback-wrapper">
    <div class="student-list">
        <h2>Students Who Gave Feedback</h2>
        <?php foreach ($studentsArray as $student) : ?>
            <a href="?studentID=<?= $student['studentID'] ?>" 
               class="<?= $selectedStudentID == $student['studentID'] ? 'selected' : '' ?>">
               <?= htmlspecialchars($student['studentName']) ?><br>
               <span class="feedback-meta">Feedbacks: <?= $student['feedbackCount'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="feedback-details">
        <?php if ($selectedStudentID > 0) : ?>
            <h2>Feedback Details for 
                <?php 
                    $studentName = "";
                    foreach ($studentsArray as $s) {
                        if ($s['studentID'] == $selectedStudentID) {
                            $studentName = $s['studentName'];
                            break;
                        }
                    }
                    echo htmlspecialchars($studentName);
                ?>
            </h2>
            <?php if (count($studentFeedbacks) > 0) : ?>
                <?php foreach ($studentFeedbacks as $feedback) : ?>
                    <div class="feedback-entry">
                        <p><strong>Subject:</strong> <?= htmlspecialchars($feedback['subjectName']) ?></p>
                        <p><strong>Tutor:</strong> <?= $feedback['tutorName'] ? htmlspecialchars($feedback['tutorName']) : 'Not Assigned' ?></p>
                        <p><strong>Rating:</strong> <span class="feedback-rate"><?= $feedback['rate'] ?>/10</span></p>
                        <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($feedback['comment'])) ?></p>

                        <p class="feedback-date"><em>Date: <?= htmlspecialchars($feedback['date']) ?></em></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No feedback found for this student.</p>
            <?php endif; ?>
        <?php else : ?>
            <h2>Please select a student to view feedback details.</h2>
        <?php endif; ?>
    </div>
</div>

<script>
  function confirmLogout() {
  return confirm("Are you sure you want to logout?");
}</script>
</body>
</html>  