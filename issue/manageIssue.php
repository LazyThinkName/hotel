<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hotel Management System</title>
</head>
<body>
	<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/homepageDesignA.css">
	<style type="text/css">
		body{
			font-family: Arial, sans-serif;
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
	<div id="tableContainer">	
	<table class="table table-striped">
		<tr>
			<th>No.</th>
			<th>Customer</th>
			<th>Email</th>
			<th>PhoneNo</th>
			<th>Report Issue</th>
			<th>Reported Date</th>

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
			$query = "SELECT * FROM issue JOIN user ON issue.userID=user.userID";
			$result = mysqli_query($mysqli, $query);
			$no = 1;
			//display each row of data
			if ($result) {
			    if (mysqli_num_rows($result) > 0) {
			        while ($row = mysqli_fetch_assoc($result)) {
			            echo "<tr><td>" . $no . "</td><td>" . $row["fullname"] . "</td><td>" . $row["email"] ."</td><td>" . $row["phoneNo"] ."</td><td>" . $row["description"] . "</td><td>" . $row["date"] . "</td></tr>";
			            $no = $no +1;
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