<?php
session_start();
include 'connection.php';  // Sambung ke pangkalan data

if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.html");
    exit();
}

$tutorID = $_SESSION['tutor_id'];

if (isset($_POST['delete_schedule'])) {
    $scheduleID = $_POST['schedule_id'];

    // Hapuskan jadual tutor daripada tutor_schedule
    $deleteScheduleQuery = "DELETE FROM tutor_schedule WHERE scheduleID = ?";
    $deleteScheduleStmt = $conn->prepare($deleteScheduleQuery);
    $deleteScheduleStmt->bind_param("i", $scheduleID);
    $deleteScheduleStmt->execute();

    // Redirect balik ke halaman scheduletutor.php
    header("Location: scheduletutor.php");
}
?>
