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
	<link rel="stylesheet" type="text/css" href="../../css/homepageDesignA.css">
	<style type="text/css">
		body{
		  font-family: Arial, Helvetica, sans-serif;
		  background-image: url(../../src/hotel.jpg);
		  background-repeat: no-repeat;
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
		<a href="../../homepage/homepageA.php"><img src="../../src/homepage.png" class="icon">Home Page</a>
		<a href="index.php?=<?php echo urlencode($username); ?>"> <img src = "../../src/addBooking.png" class="icon">Room Details</a>
		<a href="../../chart/roomAvailable.php"><img src = "../../src/list.png" class="icon">Room Check</a>
		<a href="../../bookRoom/bookingDetail.php"><img src = "../../src/manageBooking.png" class="icon">Booking Details</a>
		<a href="../../chart/profit.php"><img src = "../../src/profit.png" class="icon">Profit</a>
		<a href="../../issue/manageIssue.php"><img src = "../../src/report.png" class="icon">Reported Issue</a>
		<a href="../../homepage/logout.php"><img src = "../../src/logOut.png" class="icon">Log Out</a>
	</div>
	<br>
	<a href="addRoom.php"><button class="btn btn-primary">
		Add Room
	</button></a><br>
	<div id = "tableContainer">		
	<table class="table table-striped">

		<tr>
			<th>No.</th>
			<th>Room Type</th>
			<th>Room Number</th>
			<th>Details</th>
			<th>Price</th>
			<th>Units</th>
			<th>Status</th>
			<th></th>
			<th></th>
		</tr>
		<?php
			//connect to database
			$server = "localhost";
			$username = "root";
			$password = "";
			$dbname = "hotel_management";

			//create connection
			$mysqli = mysqli_connect($server, $username, $password, $dbname);

			//check connection
			if (!$mysqli) {
			  die("Connection failed: " . mysqli_connect_error());
			}

            if (isset($_GET['delete'])) {
			    $roomId = $_GET['delete'];
			    $deleteQuery = "DELETE FROM room WHERE roomID = '$roomId'";
			    if (mysqli_query($mysqli, $deleteQuery)) {
			        header("location: index.php");
        			exit();
			    } else {
			        echo "Error deleting record: " . mysqli_error($mysqli);
			    }
            }

			//get all data from add_room table
			$query = "SELECT * FROM room JOIN status ON room.statusID=status.statusID ORDER BY room.roomNumber";
			$result = mysqli_query($mysqli, $query);
			$no = 1;

			//display each row of data
			if (mysqli_num_rows($result) > 0) {
			  while($row = mysqli_fetch_assoc($result)) {
			    echo "<tr><td>" . $no . "</td><td>" . $row["roomType"] . "</td><td>" . $row["roomNumber"] . "</td><td>" . $row["detail"] . "</td><td>" . $row["price"] . "</td><td>" . $row["unit"] . "</td><td>" . $row["description"] . "</td><td>" . "<a href=\"edit.php?roomID=" . $row["roomID"] . "\">Edit</a>". "</td><td>". '<a href="#" onclick="confirmDelete(' . $row["roomID"] . ')">Delete</a></td>';
			    $no = $no + 1;
			  }
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