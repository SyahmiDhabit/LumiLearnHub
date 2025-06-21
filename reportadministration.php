<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reportadministration.css" type="text/css">
    <title>Document</title>
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
                 <a class="userIcon">
                <img src="image/PpLogo.jpg" alt="User Icon" class="social-icon">
            </a>
            </div>
        </nav>
    </div>

    <div class = "firstMenu">
        <ul>
            <li><a href="applicationadministration.php">TUTORING <br>SUBJECT <br> APPLICATION</a></li>
        </ul>
    </div>

    <div class="Menu">
         <ul>
                <li><a href="feedbackadministration.php">FEEDBACK</a></li>
                <li><a href="reportadministration.php" id="report">REPORT</a></li>
                <li><a href="administrationlist.php">LIST</a></li>
                <li><a href="login.html">LOGOUT</a></li>
         </ul>
    </div>
    <div class="searchbar">
        <input type="text" id="search" class="searchUser" placeholder="Search User">
        <button class="tutor">TUTOR</button>
        <button class="student">STUDENT</button>
    </div>
     <div class="result-box">
      <p></p>
    </div>
  </div>
</body>
</html>