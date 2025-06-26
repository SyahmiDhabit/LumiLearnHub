<?php
header('Content-Type: application/json');
include("connection.php");

//check DB connection
if ($conn->connect_error) {
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}

// Correct table name
$sql = "SELECT subjectID, subject_name, subject_description FROM subject";
$result = $conn->query($sql);

$subjects = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $subjects[$row['subject_name']] = [
      'id' => $row['subjectID'],
      'description' => $row['subject_description']
    ];
  }
}

// Output result
echo json_encode($subjects);
$conn->close();
?>
