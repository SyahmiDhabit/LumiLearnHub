<?php
session_start();
include("connection.php");

if (!isset($_SESSION['studentID'])) {
    echo "UNAUTHORIZED";
    exit();
}

$studentID = $_SESSION['studentID'];
$oldSubject = $_POST['oldSubject'] ?? '';
$newDay = $_POST['newDay'] ?? '';
$newTime = $_POST['newTime'] ?? '';

if (empty($oldSubject) || empty($newDay) || empty($newTime)) {
    echo "MISSING_DATA";
    exit();
}

// Check if subject exists in current schedule
$check = $conn->prepare("SELECT * FROM schedule WHERE studentID = ? AND subject = ?");
$check->bind_param("is", $studentID, $oldSubject);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo "NOT_FOUND";
    exit();
}

// Update the subject's day and time_slot
$update = $conn->prepare("UPDATE schedule SET day = ?, time_slot = ? WHERE studentID = ? AND subject = ?");
$update->bind_param("ssis", $newDay, $newTime, $studentID, $oldSubject);

if ($update->execute()) {
    echo "UPDATED";
} else {
    echo "ERROR";
}
?>
