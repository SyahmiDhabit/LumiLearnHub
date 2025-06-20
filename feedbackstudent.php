<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LumiLearnHub - FeebackStudent</title>
  <link rel="stylesheet" href="feedbackstudent.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
  <div class="header-bar">
    <button class="back-button">BACK TO HOME</button>
    <img class="user-top-icon" src="image/LoginUser.png" alt="User Icon">
  </div>

  <div class="welcome-section">
    <h1 class="welcome-title">WELCOME STUDENT!</h1>

    <p class="description">We’re excited to have you here. This is your space to explore, learn, and grow at your own pace. You can find the right tutors, book sessions that suit your schedule, and keep track of your learning progress all in one convenient place. Whether you're brushing up on a subject or aiming for top scores, LumiLearnHub is here to support every step of your journey. Let’s make the most of your time here and reach your goals together!</p>
  </div>

    <div class="button-group">
      <button>
        <img src="image/subject.png" alt="Subject" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Explore Subject
      </button>
      <button>
        <img src="image/findtutor.png" alt="Add Tutor" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Find a Tutor
      </button>
      <button>
        <img src="image/toptutor.png" alt="Top Tutor" style="width: 20px; vertical-align: middle; margin-right: 8px;" />
        Top Tutors
      </button>
    </div>

  <div class="main-content">


  <div class="table-section">
    <table class="subject-table">
      <tr>
        <th>#</th>
        <th>Subject</th>
        <th>Tutor</th>
        <th>Status</th>
      </tr>
      <tr>
        <td>1</td>
        <td>Event Driven Programming</td>
        <td>Abdul Razak</td>
        <td>Rated</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Web Programming</td>
        <td>Norazlin</td>
        <td>Rated</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Math</td>
        <td>Atiqah Lazim</td>
        <td>Unrated</td>
      </tr>
      <tr>
        <td>4</td>
        <td>English</td>
        <td>Fathiha Ariffin</td>
        <td>Unrated</td>
      </tr>
      <tr>
        <td>5</td>
        <td>Computer Science</td>
        <td>Zanariah Omar</td>
        <td>Unrated</td>
      </tr>
    </table>
  </div>


  <div class="side-buttons">
      <div class="side-button">
        <img src="image/calendaricon.png" alt="My Schedule">
        <span>My Schedule</span>
      </div>
      <div class="side-button">
        <img src="image/subject.png" alt="My Subject">
        <span>My Subject</span>
      </div>
      <div class="side-button">
        <img src="image/feedbackicon.png" alt="Feedback">
        <span>Feedback</span>
      </div>
  </div>
</div>



<footer>
    2025 LumiLearnHub. All rights reserved
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Back to home button
    document.querySelector(".back-button").addEventListener("click", function () {
        window.location.href = "studentinterface.php";
    });

    // User profile icon
    document.querySelector(".user-top-icon").addEventListener("click", function () {
        window.location.href = "profile2.html";
    });

    // Explore Subject Button
    document.querySelector(".button-group button:nth-child(1)").addEventListener("click", function () {
        window.location.href = "searchsubject.php";
    });

    // Find a Tutor Button
    document.querySelector(".button-group button:nth-child(2)").addEventListener("click", function () {
        window.location.href = "findtutor.php";
    });

    // Top Tutors Button
    document.querySelector(".button-group button:nth-child(3)").addEventListener("click", function () {
        window.location.href = "toptutors.html";
    });

    // My Schedule Button
    document.querySelector(".side-button:nth-child(1)").addEventListener("click", function () {
        window.location.href = "schedulestudent.html";
    });

    // My Subject Button
    document.querySelector(".side-button:nth-child(2)").addEventListener("click", function () {
        window.location.href = "subjectstudent.php";
    });

    document.querySelectorAll(".subject-table td:last-child").forEach(cell => {
        if (cell.textContent.trim() === "Rated") {
            cell.style.color = "green";
            cell.style.textDecoration = "underline";
        } else if (cell.textContent.trim() === "Unrated") {
            cell.style.color = "blue";
            cell.style.textDecoration = "underline";
            cell.style.cursor = "pointer";

            cell.addEventListener("click", function () {
                let comment = prompt("Please enter your comment for the tutor:");
                let score = prompt("Rate the tutor (1-10):");

                if (comment && score >= 1 && score <= 10) {
                    alert(`Thank you for your feedback!\nComment: ${comment}\nScore: ${score}`);
                    cell.textContent = "Rated";
                    cell.style.color = "green";
                    cell.style.textDecoration = "underline";
                    cell.style.cursor = "default"; // Disable further clicks
                    cell.replaceWith(cell.cloneNode(true)); // Remove event listener
                } else {
                    alert("Invalid input. Please enter a valid comment and score between 1-10.");
                }
            });
        }
    });
    
  });


  </script>

</body>
</html>
