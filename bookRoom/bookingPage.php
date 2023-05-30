<?php
session_start();

// Retrieve the username from the query parameters or session variable
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $_SESSION['username'] = $username;
} elseif (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Username not found in the query parameters or session, handle the case accordingly
    // For example, redirect the user to the login page
    header("location: ../index.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$checkInDate = $_POST['checkInDate'];
	$checkOutDate = $_POST['checkOutDate'];
	$roomType = $_POST['roomType'];
	$unit = $_POST['unit'];
	if ($checkOutDate < $checkInDate){
		echo '<script>alert("The check-out date must greater than check-in date")</script>';
		header("location: bookingPage.php");
	}
}

$Date = date("Y-m-d");
$nextDate = date("Y-m-d",strtotime('+1 days',strtotime($Date)));
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
		  font-family: Arial, Helvetica, sans-serif;
		  background-image: url(../src/hotel.jpg);
		  background-repeat: no-repeat;
		  background-size: 100%;
		}

		button{
			float: inherit;
			background-color: #000000;
			color: #Ffffff;
			padding-left: 5px;
			width: 70px;
			font-size: 17px;
			cursor: pointer;
		}

		.select {
			margin: auto;
			border: 3px solid green;
			background-color: lightyellow;
			padding: 20px;
			margin-top: 20px;
			display: flex;
			justify-content: center;
			align-items: center;
			width: 30%;
		}

		.select div{
			margin-top: 30px;
			margin-bottom: 30px;
		}

		label {
			margin-left: 10px;
		}

		#submit-btn{
			text-align: center;
		}
	</style>
</head>
<body>
	<div class = "topnav">
		<a href="../homepage/homepageU.php"><img src="../src/homepage.png" class="icon">Homepage</a>
		<a href="bookingPage.php"><img src="../src/addBooking.png" class="icon">Book Room</a>
		<a href="manageBooking.php"><img src="../src/manageBooking.png" class="icon">Manage My Booking</a>
		<a href="../homepage/logout.php" style="float: right;"><img src="../src/logOut.png" class="icon">Log Out</a>   
		<a href="#" style="float:right;" onclick="toggleProfileArea();"><img src="../src/userProfile.png" class="icon">View Profile</a>
		<a href="../issue/reportIssueForm.php"><img src="../src/report.png" class="icon" >Report Issue</a>
	</div>
	<div class="select">
		<form action="selectRoom.php" method="POST" onsubmit="return validateForm()">

			<div>
				<label>Check-In Date:</label>
				<input type="date" name="checkInDate">
			</div>
			<div>
				<label>Check-Out Date:</label>
				<input type="date" name="checkOutDate">
			</div>
			<div>
				<label>Room Type:</label>
				<select name = "roomType">
					<option value = "Standard">Standard</option>
					<option value = "Deluxe">Deluxe</option>
				</select>
			</div>
			<div>
				<label>Room Unit:</label>
				<input type="number" name="unit">
			</div>
			<div id="submit-btn">
				<button type ="submit" class="btn btn-success">Search</button>
			</div>
		</form>
	</div>


	<script>
		function validateForm(){
			var checkInDate = document.getElementById('checkInDate').value;
			var checkOutDate = document.getElementById('checkOutDate').value;
			var unit = document.getElementById('unit').value;

			// Check if the check-in date is less than the check-out date
			if (checkInDate > checkOutDate) {
				alert("Check-in date must be before the check-out date.");
				return false;
			}

			// Check if the room unit is within the valid range
			if (unit < 1 || unit > 10 || unit == "") {
				alert("Room unit must be between 1 and 10.");
				return false;
			}

			// All validations passed, form can be submitted
			return true;
		}
	</script>
</body>
</html>