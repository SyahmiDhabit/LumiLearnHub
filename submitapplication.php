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

// Jika "Custom" dipilih, gantikan dengan durasi yang ditetapkan
if (in_array("Custom", $durations) && !empty($custom_duration)) {
    $durations = array_diff($durations, ["Custom"]);
    $durations[] = $custom_duration;
}

// Gabungkan semua durasi yang dipilih menjadi satu string
$duration_string = implode(", ", $durations);

// Semak jika semua data borang lengkap
if (empty($subjectID) || empty($tutorID) || empty($level) || empty($qualification) || empty($duration_string)) {
    die("<div class='alert alert-danger'>Error: Some fields are missing. Please complete the form.</div>");
}

// Sambung ke pangkalan data
include "connection.php"; 

// Semak jika subjectID wujud dalam jadual subject
$query = "SELECT subjectID, subject_name FROM subject WHERE subjectID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subjectID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("<div class='alert alert-danger'>Error: Selected subject does not exist in the database.</div>");
}

// Ambil nama subjek
$subject = $result->fetch_assoc();

// Masukkan data ke dalam jadual tutor_subject (tanpa subject)
$stmt = $conn->prepare("INSERT INTO tutor_subject (subjectID, tutorID, duration, qualification, level, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("iisss", $subjectID, $tutorID, $duration_string, $qualification, $level);

if ($stmt->execute()) {
    // Success message with data in a vertical layout
    echo "
        <div class='alert alert-success'>
            <h4>Application Submitted Successfully!</h4>
            <p>You will be redirected in <span id='countdown'>5</span> seconds.</p>
            <div class='application-details'>
                <p><strong>Subject:</strong> " . htmlspecialchars($subject['subject_name']) . "</p>
                <p><strong>Level:</strong> " . htmlspecialchars($level) . "</p>
                <p><strong>Qualification:</strong> " . htmlspecialchars($qualification) . "</p>
                <p><strong>Duration:</strong> " . htmlspecialchars($duration_string) . "</p>
            </div>
            <p class='text-muted'>Please note that the application is pending approval.</p>
        </div>
        <script>
            var countdown = document.getElementById('countdown');
            var timeLeft = 5;
            var timer = setInterval(function() {
                timeLeft--;
                countdown.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.href = 'applicationtutor.php';
                }
            }, 1000);
        </script>
    ";
} else {
    echo "<div class='alert alert-danger'>Error submitting application: " . $stmt->error . "</div>";
}

$stmt->close();
$conn->close();
?>
