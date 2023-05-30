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
	<link rel="stylesheet" type="text/css" href="../css/homepageDesign.css">
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
		<a href="../homepage/homepageU.php"><img src="../src/homepage.png" class="icon">Homepage</a>
		<a href="bookingPage.php"><img src="../src/addBooking.png" class="icon">Book Room</a>
		<a href="manageBooking.php"><img src="../src/manageBooking.png" class="icon">Manage My Booking</a>
		<a href="../homepage/logout.php" style="float: right;"><img src="../src/logOut.png" class="icon">Log Out</a>   
		<a href="#" style="float:right;" onclick="toggleProfileArea();"><img src="../src/userProfile.png" class="icon">View Profile</a>
		<a href="../issue/reportIssueForm.php"><img src="../src/report.png" class="icon" >Report Issue</a>
	</div><br>
	<div id="tableContainer">	
	<table class="table table-striped">
		<tr>
			<th>No.</th>
			<th>Booking Id</th>
			<th>Room Type</th>
			<th>Room Number</th>
			<th>Check-In Date</th>
			<th>Check-Out Date</th>
			<th></th>
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

            if (isset($_GET['delete'])) {
			    $bookingId = $_GET['delete'];
			    $deleteQuery = "DELETE FROM booking WHERE bookingID = '$bookingId'";
			    if (mysqli_query($mysqli, $deleteQuery)) {
        			header("location: manageBooking.php");
        			exit();
			    } else {
			        echo "Error deleting record: " . mysqli_error($mysqli);
			    }
            }

            $user = "SELECT userID FROM user Where username = ?";
			$stmtUser = $mysqli->prepare($user);
			if ($stmtUser){
			    $stmtUser->bind_param("s",$username);
			    $stmtUser->execute();
			    $resultUser = $stmtUser->get_result();
			    $rowUser = $resultUser->fetch_assoc();
			    if ($rowUser){
			        $userID = $rowUser['userID'];
			    }
			    $stmtUser->close();
			}

			//get all data from add_room table
			$query = "SELECT * FROM booking JOIN user ON booking.userID=user.userID JOIN room ON booking.roomID=room.roomID WHERE booking.userID = $userID";
			$result = mysqli_query($mysqli, $query);
			$no = 1;

			//display each row of data
			if ($result) {
			    if (mysqli_num_rows($result) > 0) {
			        while ($row = mysqli_fetch_assoc($result)) {
			            echo "<tr><td>" . $no . "</td><td>" . $row["bookingId"] . "</td><td>" . $row["roomType"] . "</td><td>" . $row["roomNumber"] . "</td><td>" . $row["checkInDate"] . "</td><td>" . $row["checkOutDate"] . "</td><td>" . '<a href="#" onclick="confirmDelete(' . $row["bookingId"] . ')">Delete</a></td>';
			            $no = $no + 1;
			        }
			    } else {
			        echo "You don't have any booking now";
			    }
			} else {
			    echo "Error executing the query: " . mysqli_error($mysqli);
			}
			//close connection
			$mysqli->close();
		?>

	</table>
	</div>
	<script>
	function confirmDelete(bookingId) {
	    Swal.fire({
	        title: 'Are you sure?',
	        text: 'This action cannot be undone!',
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#d33',
	        cancelButtonColor: '#3085d6',
	        confirmButtonText: 'Yes, delete it!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            window.location.href = '?delete=' + bookingId;
	        }
	    });
	}
    </script>
</body>
</html>