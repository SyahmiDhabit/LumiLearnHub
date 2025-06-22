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

    // Dapatkan maklumat subjectID dari jadual tutor_schedule untuk delete dari tutor_subject
    $query = "SELECT subject FROM tutor_schedule WHERE scheduleID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $scheduleID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $subject = $row['subject'];

    // Dapatkan subjectID berdasarkan subject name
    $subjectQuery = "SELECT subjectID FROM subject WHERE subject_name = ?";
    $subjectStmt = $conn->prepare($subjectQuery);
    $subjectStmt->bind_param("s", $subject);
    $subjectStmt->execute();
    $subjectResult = $subjectStmt->get_result();
    $subjectData = $subjectResult->fetch_assoc();
    $subjectID = $subjectData['subjectID'];

    // Hapuskan rekod dari tutor_subject berdasarkan tutorID dan subjectID
    $deleteTutorSubjectQuery = "DELETE FROM tutor_subject WHERE tutorID = ? AND subjectID = ?";
    $deleteTutorSubjectStmt = $conn->prepare($deleteTutorSubjectQuery);
    $deleteTutorSubjectStmt->bind_param("ii", $tutorID, $subjectID);
    $deleteTutorSubjectStmt->execute();

    // Hapuskan jadual tutor daripada tutor_schedule
    $deleteScheduleQuery = "DELETE FROM tutor_schedule WHERE scheduleID = ?";
    $deleteScheduleStmt = $conn->prepare($deleteScheduleQuery);
    $deleteScheduleStmt->bind_param("i", $scheduleID);
    $deleteScheduleStmt->execute();

    // Redirect balik ke halaman scheduletutor.php
    header("Location: scheduletutor.php");
}
?>
