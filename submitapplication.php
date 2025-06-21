<?php
session_start();  // Pastikan session dimulakan hanya sekali

// Semak jika tutorID wujud dalam sesi
if (!isset($_SESSION['tutor_id'])) {
    header("Location: tutorlogin.html");
    exit();
}

// Ambil data dari borang
$subjectID = $_POST['subject'] ?? "";  // Ambil subjectID yang dihantar dari borang
$tutorID = $_SESSION['tutor_id'];  // Ambil tutorID dari sesi
$level = $_POST['level'] ?? "";
$qualification = $_POST['qualification'] ?? "";
$custom_duration = $_POST['custom_duration'] ?? "";
$durations = $_POST['duration'] ?? [];

// Debugging: Semak nilai yang dihantar
echo "Subject ID: $subjectID<br>";
echo "Tutor ID: $tutorID<br>";
echo "Level: $level<br>";
echo "Qualification: $qualification<br>";
echo "Duration: " . implode(", ", $durations) . "<br>";

// Jika "Custom" dipilih, gantikan dengan durasi yang ditetapkan
if (in_array("Custom", $durations) && !empty($custom_duration)) {
    $durations = array_diff($durations, ["Custom"]);
    $durations[] = $custom_duration;
}

// Gabungkan semua durasi yang dipilih menjadi satu string
$duration_string = implode(", ", $durations);

// Semak jika semua data borang lengkap
if (empty($subjectID) || empty($tutorID) || empty($level) || empty($qualification) || empty($duration_string)) {
    die("Error: Some fields are missing. Please complete the form.");
}

// Sambung ke pangkalan data
include "connection.php"; 

// Semak jika subjectID wujud dalam jadual subject
$query = "SELECT subjectID FROM subject WHERE subjectID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subjectID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Selected subject does not exist in the database.");
}

// Masukkan data ke dalam jadual tutor_subject (tanpa subject)
$stmt = $conn->prepare("INSERT INTO tutor_subject (subjectID, tutorID, duration, qualification, level) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $subjectID, $tutorID, $duration_string, $qualification, $level);

if ($stmt->execute()) {
    // Menambah delay 5 saat sebelum redirect ke applicationtutor.php
    header("refresh:5; url=applicationtutor.php");
    echo "<div class='alert alert-success'>Application submitted successfully. You will be redirected in 5 seconds.</div>";
} else {
    echo "<div class='alert alert-danger'>Error submitting application: " . $stmt->error . "</div>";
}

$stmt->close();
$conn->close();
?>
