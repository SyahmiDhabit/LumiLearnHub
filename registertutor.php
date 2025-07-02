<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Registration Result</title>
</head>
<body>

<?php 
include 'connection.php';

echo "<h1>Tutor Registration Result</h1>";

// Check if all required fields are present
if (
    empty($_POST['tutor_fullName']) || empty($_POST['tutor_username']) || empty($_POST['tutor_password']) ||
    empty($_POST['tutor_age']) || empty($_POST['tutor_dob']) || !isset($_POST['tutor_gender']) ||
    empty($_POST['tutor_phoneNumber']) || empty($_POST['tutor_country']) ||
    empty($_POST['tutor_email']) || empty($_POST['tutor_bio'])
) {
    echo "<p>You have not entered all the required details.</p>";
    echo "<p>Please go back and try again.</p>";
} else {
    // Get and sanitize form input
    $tutor_fullName     = trim($_POST['tutor_fullName']);
    $tutor_username     = trim($_POST['tutor_username']);
    $tutor_password_raw = $_POST['tutor_password'];
    $tutor_password     = password_hash($tutor_password_raw, PASSWORD_DEFAULT);
    $tutor_age          = intval($_POST['tutor_age']);
    $tutor_dob          = $_POST['tutor_dob'];
    $tutor_gender       = $_POST['tutor_gender'];
    $tutor_phoneNumber  = trim($_POST['tutor_phoneNumber']);
    $tutor_country      = trim($_POST['tutor_country']);
    $tutor_email        = trim($_POST['tutor_email']);
    $tutor_bio          = trim($_POST['tutor_bio']);

    // Check if username already exists
    $check = $conn->query("SELECT * FROM tutor WHERE tutor_username = '$tutor_username'");
    
    if ($check->num_rows >= 1) {
        echo "<p>The username already exists. Please use a different username.</p>";
        echo "<p>You will be redirected to the login page in 5 seconds...</p>";
        echo "<meta http-equiv='refresh' content='5;url=registertutor.html'>";
    } else {
        // Insert new tutor record
        $sql = "INSERT INTO tutor (
            tutor_fullName, tutor_username, tutor_password, tutor_age, tutor_dob, tutor_gender, 
            tutor_phoneNumber, tutor_country, tutor_email, tutor_bio
        ) VALUES (
            '$tutor_fullName', '$tutor_username', '$tutor_password', $tutor_age, '$tutor_dob', '$tutor_gender',
            '$tutor_phoneNumber', '$tutor_country', '$tutor_email', '$tutor_bio'
        )";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Tutor successfully registered!</p>";
            echo "<p>You will be redirected to the login page in 5 seconds...</p>";
            echo "<meta http-equiv='refresh' content='5;url=tutorlogin.html'>";
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Field Name</th><th>Value</th></tr>";
            echo "<tr><td>tutor_fullName</td><td>$tutor_fullName</td></tr>";
            echo "<tr><td>tutor_username</td><td>$tutor_username</td></tr>";
            echo "<tr><td>tutor_password</td><td>(Hashed and secured)</td></tr>";
            echo "<tr><td>tutor_age</td><td>$tutor_age</td></tr>";
            echo "<tr><td>tutor_dob</td><td>$tutor_dob</td></tr>";
            echo "<tr><td>tutor_gender</td><td>$tutor_gender</td></tr>";
            echo "<tr><td>tutor_phoneNumber</td><td>$tutor_phoneNumber</td></tr>";
            echo "<tr><td>tutor_country</td><td>$tutor_country</td></tr>";
            echo "<tr><td>tutor_email</td><td>$tutor_email</td></tr>";
            echo "<tr><td>tutor_bio</td><td>$tutor_bio</td></tr>";
            echo "</table>";

        } else {
            echo "<p>Error inserting into database: " . $conn->error . "</p>";
        }
    }
}
?>

</body>
</html>
