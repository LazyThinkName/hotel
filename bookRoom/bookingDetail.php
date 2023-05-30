<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hotel Management System</title>
	<link rel="stylesheet" type="text/css" href="../css/homepageDesignA.css">
	<style type="text/css">
		body{
			background-image: url(../src/hotel.jpg);
			font-family: Arial, sans-serif;
			background-repeat: no-repeat;
			background-size: 100%;
		}

		#tableContainer{
			width: 80%;
			margin: auto;
		}

		table{
			width: 80%;
			background-color: whitesmoke;
		}

	</style>
</head>
<body>
	<div class = "topnav">
		<a href="../homepage/homepageA.php"><img src="../src/homepage.png" class="icon">Home Page</a>
		<a href="../add_delete/add_and_delete_room/index.php"> <img src = "../src/addBooking.png" class="icon">Room Details</a>
		<a href="../chart/roomAvailable.php"><img src = "../src/list.png" class="icon">Room Check</a>
		<a href="../bookRoom/bookingDetail.php"><img src = "../src/manageBooking.png" class="icon">Booking Details</a>
		<a href="../chart/profit.php"><img src = "../src/profit.png" class="icon">Profit</a>
		<a href="../issue/manageIssue.php"><img src = "../src/report.png" class="icon">Reported Issue</a>
		<a href="../homepage/logout.php"><img src = "../src/logOut.png" class="icon">Log Out</a>
	</div><br>
	<div id = "tableContainer">	
	<table class="table table-striped">
		<tr>
			<th>Booking Id.</th>
			<th>Customer</th>
			<th>Email</th>
			<th>PhoneNo</th>
			<th>Room Type</th>
			<th>Room Number</th>
			<th>Payment Price</th>
			<th>Check-In Date</th>
			<th>Check-Out Date</th>
		</tr>
		<?php
			//connect to database
			$server = "localhost";
			$serverUser = "root";
			$password = "";
			$dbname = "hotel_management";

			//create connection
			$mysqli = mysqli_connect($server, $serverUser, $password, $dbname);

			//check connection
			if (!$mysqli) {
			  die("Connection failed: " . mysqli_connect_error());
			}

			//get all data from add_room table
			$query = "SELECT * FROM booking JOIN user ON booking.userID=user.userID JOIN room ON booking.roomID=room.roomID ORDER BY booking.bookingID";
			$result = mysqli_query($mysqli, $query);
			//display each row of data
			if ($result) {
			    if (mysqli_num_rows($result) > 0) {
			        while ($row = mysqli_fetch_assoc($result)) {
			            echo "<tr><td>" . $row["bookingId"] . "</td><td>" . $row["fullname"] . "</td><td>" . $row["email"] ."</td><td>" . $row["phoneNo"] ."</td><td>" . $row["roomType"] . "</td><td>" . $row["roomNumber"] . "</td><td>" . $row["paymentPrice"] ."</td><td>" . $row["checkInDate"] . "</td><td>" . $row["checkOutDate"]  . "</td></tr>";
			        }
			    } else {
			        echo "No rows found in the result set.";
			    }
			} else {
			    echo "Error executing the query: " . mysqli_error($mysqli);
			}
			//close connection
			$mysqli->close();
		?>
	</table>
	</div>
</body>
</html>