<?php
session_start();
$server = "localhost";
$serverUser = "root";
$password = "";
$dbname = "hotel_management";
$conn = new mysqli("$server", "$serverUser", "$password", "$dbname");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

$date = date("Y-m-d");

$query = "SELECT COUNT(*) AS count FROM room";
$result = $conn->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $totalRooms = $row['count'];
} else {
    $totalRooms = 0;
}

$queryCount = "SELECT COUNT(*) AS count FROM room WHERE statusID = 1";
$result = $conn->query($queryCount);
if ($result) {
    $row = $result->fetch_assoc();
    $countAvailable = $row['count'];
    if ($countAvailable === null){
    	$countAvailable = 0;
    }
} else {
    $count = 0;
}

$countUnavailble = $totalRooms - $countAvailable;


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hotel Management System</title>
	<link rel="stylesheet" type="text/css" href="../css/homepageDesignA.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
	<style type="text/css">
		body{
			background-image: url(../src/hotel.jpg);
			font-family: Arial, sans-serif;
			background-repeat: no-repeat;
			background-size: 100%;
			justify-content: center;
			align-items: center;
		}

        .chart-container {
        	display: flex;
            max-width: 600px;

        }

        .chart-container canvas {
            flex: 1;
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
	<div class="container" id="reportPage">
		<div class="chart-container">
			<canvas id="myChart"></canvas>
		</div>
	</div>


	<script>
		var xValues = ["Available", "Unavailable"];
		var yValues = [<?php echo "$countAvailable";?>, <?php echo "$countUnavailble";?>];
		var barColors = [
		  "#3498DB",
		  "#E74C3C"
		];

		new Chart("myChart", {
		  type: "pie",
		  data: {
		    labels: xValues,
		    datasets: [{
		      backgroundColor: barColors,
		      data: yValues
		    }]
		  },
		  options: {
		    title: {
		      display: true,
		      text: "Availability of Room"
		    }
		  }
		});
	</script>

</body>
</html>