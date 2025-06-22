<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration Result</title>
</head>
<body>

<?php 
include 'connection.php';

echo "<h1>Student Registration Result</h1>";

if (
    empty($_POST['student_fullName']) || empty($_POST['student_username']) || empty($_POST['student_password']) ||
    empty($_POST['student_age']) || empty($_POST['student_dob']) || !isset($_POST['student_gender']) ||
    empty($_POST['student_phoneNumber']) || empty($_POST['student_country']) ||
    empty($_POST['student_email']) || empty($_POST['student_bio'])
) {
    echo "<p>You have not entered all the required details.</p><p>Please go back and try again.</p>";
} else {
    $student_fullName    = trim($_POST['student_fullName']);
    $student_username    = trim($_POST['student_username']);
    $student_passwordRaw = $_POST['student_password'];
    $student_password    = password_hash($student_passwordRaw, PASSWORD_DEFAULT);
    $student_age         = intval($_POST['student_age']);
    $student_dob         = $_POST['student_dob'];
    $student_gender      = $_POST['student_gender'];
    $student_phoneNumber = trim($_POST['student_phoneNumber']);
    $student_country     = trim($_POST['student_country']);
    $student_email       = trim($_POST['student_email']);
    $student_bio         = trim($_POST['student_bio']);

    $check = $conn->query("SELECT * FROM student WHERE student_username = '$student_username'");

    if ($check->num_rows >= 1) {
        echo "<p>The username already exists. Please use a different username.</p>";
        echo "<p>You will be redirected to the login page in 5 seconds...</p>";
        echo "<meta http-equiv='refresh' content='5;url=registerstudent.html'>";
    } else {
        $sql = "INSERT INTO student (
            student_fullName, student_username, student_password, student_age, student_dob, student_gender, 
            student_phoneNumber, student_country, student_email, student_bio
        ) VALUES (
            '$student_fullName', '$student_username', '$student_password', $student_age, '$student_dob', '$student_gender',
            '$student_phoneNumber', '$student_country', '$student_email', '$student_bio'
        )";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Student successfully registered!</p>";
            echo "<p>You will be redirected to the login page in 5 seconds...</p>";
            echo "<meta http-equiv='refresh' content='5;url=studentlogin.html'>";
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Field Name</th><th>Value</th></tr>";
            echo "<tr><td>student_fullName</td><td>$student_fullName</td></tr>";
            echo "<tr><td>student_username</td><td>$student_username</td></tr>";
            echo "<tr><td>student_password</td><td>(Hashed and secured)</td></tr>";
            echo "<tr><td>student_age</td><td>$student_age</td></tr>";
            echo "<tr><td>student_dob</td><td>$student_dob</td></tr>";
            echo "<tr><td>student_gender</td><td>$student_gender</td></tr>";
            echo "<tr><td>student_phoneNumber</td><td>$student_phoneNumber</td></tr>";
            echo "<tr><td>student_country</td><td>$student_country</td></tr>";
            echo "<tr><td>student_email</td><td>$student_email</td></tr>";
            echo "<tr><td>student_bio</td><td>$student_bio</td></tr>";
            echo "</table>";
        } else {
            echo "<p>Error inserting into database: " . $conn->error . "</p>";
        }
    }
}
?>

</body>
</html>
