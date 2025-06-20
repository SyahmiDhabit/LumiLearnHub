<?php
// sambung ke database
include "connection.php"; 

// 1️⃣ Ambil nilai dari POST dengan selamat
$subjectID = $_POST['subject'] ?? "";           // Sekarang ini patut ID integer
$level = $_POST['level'] ?? "";                // Level ialah teks
$qualification = $_POST['qualification'] ?? ""; // Kelayakan ialah teks
$custom_duration = $_POST['custom_duration'] ?? ""; // Custom duration
$durations = $_POST['duration'] ?? [];        // Array (contohnya ["3 Months","6 Months","Custom"])

// 2️⃣ Jika "Custom" dicheck, gantikan dengan nilai dari custom_duration
if (in_array("Custom", $durations) && !empty($custom_duration)) {
    $durations = array_diff($durations, ["Custom"]);
    $durations[] = $custom_duration;
}

// 3️⃣ Gabungkan array durations menjadi string
$duration_string = implode(", ", $durations);

// 4️⃣ Check nilai wajib
if (empty($subjectID) || empty($level) || empty($qualification) || empty($duration_string)) {
    die("Error: Ada maklumat yang tidak lengkap. Sila isi semua medan dengan betul.");
}

// 5️⃣ Sediakan statement INSERT
$stmt = $conn->prepare("INSERT INTO tutor_subject (subjectID, level, duration, qualification) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// 6️⃣ Bind parameter
// subjectID = integer -> "i"
// level = string -> "s"
// duration = string -> "s"
// qualification = string -> "s"
$stmt->bind_param("isss", $subjectID, $level, $duration_string, $qualification);

// 7️⃣ Execute
if ($stmt->execute()) {
    echo "Application submitted successfully.";
} else {
    echo "Error submitting application: " . $stmt->error;
}

// 8️⃣ Tutup statement dan sambungan
$stmt->close();
$conn->close();
?>
