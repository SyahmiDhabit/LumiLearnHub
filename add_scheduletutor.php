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
    $student_subject = $_POST['student_subject'];

    // Split the selected value into student name and subject
    list($student_name, $subject_name) = explode(" - ", $student_subject);

    // Insert the new schedule
    $query = "INSERT INTO tutor_schedule (tutorID, day, time_slot, subject, student_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $tutorID, $day, $time_slot, $subject_name, $student_name);
    $stmt->execute();

    // Redirect to the schedule page
    header("Location: scheduletutor.php");
}
?>
