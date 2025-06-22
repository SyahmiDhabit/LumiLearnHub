<?php
header('Content-Type: application/json');

include("connection.php");

if ($conn->connect_error) {
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}

$sql = "SELECT subjectID, subject_name, subject_description FROM $table";
$result = $conn->query($sql);

$subjects = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $subjects[$row['subject_name']] = [
      'id' => $row['subjectID'],
      'description' => $row['subject_description']
    ];
  }
}

echo json_encode($subjects);
$conn->close();
?>
