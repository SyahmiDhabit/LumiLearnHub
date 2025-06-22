<?php
session_start();
include 'connection.php';  

if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.html");
    exit();
}

$tutorID = $_SESSION['tutor_id'];

if (isset($_POST['add_schedule'])) {
    $day = $_POST['day'];
    $time_slot = $_POST['time_slot'];
    $subject = $_POST['subject'];
    $student_name = $_POST['student_name'];

    $query = "INSERT INTO tutor_schedule (tutorID, day, time_slot, subject, student_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $tutorID, $day, $time_slot, $subject, $student_name);
    $stmt->execute();

    header("Location: scheduletutor.php");
}
?>
