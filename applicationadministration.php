<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Tutor Applications</title>
    <link rel="stylesheet" href="applicationadministration.css" type="text/css">
    <style>
        .feedback-wrapper {
            max-height: 600px;
            overflow-y: auto;
            padding: 20px;
        }

        .feedback-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        } 

        .feedback-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            gap: 20px;
            max-width: 800px;
            width: 100%;
        }

        .feedback-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .feedback-details {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-family: Arial, sans-serif;
            flex: 1;
        }

        .feedback-name {
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .action-buttons form button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .reject-btn {
            background-color: #dc3545;
        }

        .status-text {
            font-weight: bold;
            color: gray;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="bar">
        <nav class="navbar">
            <div class="navleft">
                <h1 class="Lumilearn">LumiLearnHub</h1>
            </div>
            <div class="navcenter">
                <h1 class="admin">ADMINISTRATION</h1>
            </div>
            <div class="navright">
                </a>
            </div>
        </nav>
    </div>

    <div class="firstMenu">
        <ul>
            <li><a href="applicationadministration.php" id="appadmin">TUTORING <br>SUBJECT <br> APPLICATION</a></li>
        </ul>
    </div>

    <div class="Menu">
        <ul>
            <li><a href="feedbackadministration.php">FEEDBACK</a></li>
            <li><a href="reportadministration.php">REPORT</a></li>
            <li><a href="administrationlist.php">LIST</a></li>
           <li><a href="mainpage.php" onclick="return confirmLogout()">LOGOUT</a></li>
        </ul>
    </div>

    <div class="feedback-wrapper">
        <div class="feedback-column">
            <?php
            include 'connection.php';

            $sql = "SELECT ts.*, t.tutor_fullName, s.subject_name
                 FROM tutor_subject ts
                 JOIN tutor t ON ts.tutorID = t.tutorID
                 JOIN subject s ON ts.subjectID = s.subjectID
                  ORDER BY ts.tutorsubjectid DESC";


            $result = $conn->query($sql);

            if (!$result) {
                echo "<p style='color:red;'>Query failed: " . $conn->error . "</p>";
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="feedback-card">';
                    echo '<img src="image/PpLogo.jpg" alt="User">';
                    echo '<div class="feedback-details">';
                    echo '<span class="feedback-name"><strong>Tutor:</strong> ' . htmlspecialchars($row["tutor_fullName"]) . '</span>';
                    echo '<span class="feedback-name"><strong>Subject:</strong> ' . htmlspecialchars($row["subject_name"]) . '</span>';
                    echo '<span class="feedback-name"><strong>Duration:</strong> ' . htmlspecialchars($row["duration"]) . '</span>';
                    echo '<span class="feedback-name"><strong>Qualification:</strong> ' . htmlspecialchars($row["qualification"]) . '</span>';
                    echo '<span class="feedback-name"><strong>Level:</strong> ' . htmlspecialchars($row["level"]) . '</span>';
                    echo '<span class="feedback-name"><strong>Status:</strong> ' . htmlspecialchars($row["status"]) . '</span>';
                    echo '</div>';

                    echo '<div class="action-buttons">';
                    if (strtolower($row["status"]) === 'pending') {
                        echo '<form method="post" action="approve_tutor.php" onsubmit="return confirm(\'Are you sure you want to approve this tutor?\')">';
                        echo '<input type="hidden" name="tutorsubjectid" value="' . $row["tutorsubjectid"] . '">';
                        echo '<button type="submit" class="approve-btn">Approve</button>';
                        echo '</form>';
                        echo '<form method="post" action="reject_tutor.php" onsubmit="return confirm(\'Are you sure you want to reject this tutor?\')">';
                        echo '<input type="hidden" name="tutorsubjectid" value="' . $row["tutorsubjectid"] . '">';
                        echo '<button type="submit" class="reject-btn">Reject</button>';
                        echo '</form>';
                    } else {
                        echo '<div class="status-text">Already ' . htmlspecialchars($row["status"]) . '</div>';
                    }
                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo "<p>No tutor subject applications found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
    <script>
  function confirmLogout() {
  return confirm("Are you sure you want to logout?");
}</script>
</body>
</html>
