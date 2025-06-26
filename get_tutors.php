<?php
include 'connection.php';

$sql = "SELECT ts.*, 
               t.tutorID, 
               t.tutor_fullName, 
               t.tutor_email, 
               t.tutor_phoneNumber, 
               s.subject_name
        FROM tutor_subject ts
        JOIN tutor t ON ts.tutorID = t.tutorID
        JOIN subject s ON ts.subjectID = s.subjectID
        WHERE ts.status = 'Approved'
        ORDER BY t.tutor_fullName";

$result = $conn->query($sql);

$tutors = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['tutorID'];

    if (!isset($tutors[$id])) {
        $tutors[$id] = [
            'tutorID' => $row['tutorID'],
            'tutor_fullName' => $row['tutor_fullName'],
            'tutor_email' => $row['tutor_email'],
            'tutor_phoneNumber' => $row['tutor_phoneNumber'],
            'subjects' => []
        ];
    }

    $tutors[$id]['subjects'][] = [
        'subject_name' => $row['subject_name'],
        'duration' => $row['duration'],
        'qualification' => $row['qualification'],
        'level' => $row['level']
    ];
}

echo json_encode(array_values($tutors));
$conn->close();
?>
