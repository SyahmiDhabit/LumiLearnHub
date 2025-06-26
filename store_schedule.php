<?php
session_start();
include("connection.php");

if (!isset($_SESSION['studentID'])) {
    echo "NOT_LOGGED_IN";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_SESSION['studentID'];
    $subject = trim(strtolower($_POST['subject']));
    $day = $_POST['day'];
    $timeSlot = $_POST['time_slot'];

    if (!empty($subject) && !empty($day) && !empty($timeSlot)) {
        $check = $conn->prepare("SELECT * FROM schedule WHERE studentID = ? AND LOWER(subject) = ?");
        $check->bind_param("is", $studentID, $subject);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "DUPLICATE";
        } else {
            $stmt = $conn->prepare("INSERT INTO schedule (studentID, subject, day, time_slot) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $studentID, $_POST['subject'], $day, $timeSlot);
            $stmt->execute();
            echo "SUCCESS";
        }
    } else {
        echo "ERROR";
    }
}
?>
