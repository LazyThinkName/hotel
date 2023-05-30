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
    header("location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hotel Management System</title>
	<style type="text/css">
		body {
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
		  background-image: url(../src/hotel.jpg);
		  background-repeat: no-repeat;
		  background-size: 100%;
		}


		.topnav {
		  overflow: hidden;
		  background-color: #333;
		}

		.topnav a {
		  float: left;
		  color: #f2f2f2;
		  text-align: center;
		  padding: 14px 16px;
		  text-decoration: none;
		  font-size: 17px;
		  width: 120px;
		}

		.topnav a:hover {
		  background-color: rgba(240, 255, 255, 0.4);
		}

		.topnav a.active {
		  background-color: #04AA6D;
		  color: white;
		}

		.icon {
		  margin-left: auto;
		  margin-right: auto;
		  margin-bottom: 5px;
		  height: 25px;
		  width: 25px;
		  display: block;
		}
	</style>
</head>
<body>
		<div class = "topnav">
			<a href="homepageA.php"><img src="../src/homepage.png" class="icon">Home Page</a>
			<a href="../add_delete/add_and_delete_room/index.php?=<?php echo urlencode($username); ?>"> <img src = "../src/addBooking.png" class="icon">Room Details</a>
			<a href="../chart/roomAvailable.php"><img src = "../src/list.png" class="icon">Room Check</a>
			<a href="../bookRoom/bookingDetail.php"><img src = "../src/manageBooking.png" class="icon">Booking Details</a>
			<a href="../chart/profit.php"><img src = "../src/profit.png" class="icon">Profit</a>
			<a href="../issue/manageIssue.php"><img src = "../src/report.png" class="icon">Reported Issue</a>
			<a href="logout.php"><img src = "../src/logOut.png" class="icon">Log Out</a>
		</div>
	<script>
        // Retrieve the username from the query parameters
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const username = urlParams.get('username');

        // Use the username as needed
        console.log('Username:', username);
        // You can update the UI or perform other actions based on the username
    </script>
</body>
</html>