<?php
include 'connection.php';

if (isset($_POST['tutorsubjectid'])) {
    $id = $_POST['tutorsubjectid'];

    $sql = "UPDATE tutor_subject SET status='Approved' WHERE tutorsubjectid=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: applicationadministration.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
