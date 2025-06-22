<?php
include("connection.php");

if ($conn->connect_error) {
  http_response_code(500);
  die(json_encode(["error" => "Database connection failed."]));
}

$sql = "
  SELECT
    t.tutorID,
    t.tutor_fullName,
    ts.subjectID,
    s.subject_name,
    ts.duration,
    ts.qualification,
    ts.level
  FROM tutor t
  LEFT JOIN tutor_subject ts ON t.tutorID = ts.tutorID
  LEFT JOIN subject s ON ts.subjectID = s.subjectID
  ORDER BY t.tutor_fullName
";

$result = $conn->query($sql);
if (!$result) {
  http_response_code(500);
  die(json_encode(["error" => "Query failed: " . $conn->error]));
}

$tutors = [];
while ($row = $result->fetch_assoc()) {
  $row['subjectID']      = $row['subjectID']      ?? null;
  $row['subject_name']   = $row['subject_name']   ?? null;
  $row['duration']       = $row['duration']       ?? null;
  $row['qualification']  = $row['qualification']  ?? null;
  $row['level']          = $row['level']          ?? null;
  $tutors[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tutors);

$conn->close();
