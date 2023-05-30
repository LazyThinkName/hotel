<?php

session_start();
// Retrieve the username from the query parameters or session variable
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $_SESSION['username'] = $username;
} else if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Username not found in the query parameters or session, handle the case accordingly
    // For example, redirect the user to the login page
    header("location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/homepageDesign.css">
    <title>Hotel Management System</title>
    <style type="text/css">
        body {
/*            display: flex;*/
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        #reportIssueForm {
            max-width: 400px;
            padding: 20px;
            background-color: #f1f1f1;
            border: 3px solid green;
            margin: auto;
        }

        #reportIssueForm textarea {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class = "topnav">
        <a href="../homepage/homepageU.php"><img src="../src/homepage.png" class="icon">Homepage</a>
        <a href="../bookRoom/bookingPage.php"><img src="../src/addBooking.png" class="icon">Book Room</a>
        <a href="../bookRoom/manageBooking.php"><img src="../src/manageBooking.png" class="icon">Manage My Booking</a>
        <a href="../homepage/logout.php" style="float: right;"><img src="../src/logOut.png" class="icon">Log Out</a>   
        <a href="#" style="float:right;" onclick="toggleProfileArea();"><img src="../src/userProfile.png" class="icon">View Profile</a>
        <a href="reportIssueForm.php"><img src="../src/report.png" class="icon" >Report Issue</a>
    </div><br><br>
    <div>
    <form action="submitIssue.php" id="reportIssueForm" method="POST" class="form-group">
        <p>Report Issue:</p>
        <textarea name="issue" rows="4" cols="50" required></textarea>
        <br>
        <a href="../homepage/homepageU.php" class="btn btn-danger">Cancel</a>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
    </div>
</body>
</html>
