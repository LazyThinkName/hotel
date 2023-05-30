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

$server = "localhost";
$serverUser = "root";
$password = "";
$dbname = "hotel_management";
$conn = new mysqli("$server", "$serverUser", "$password", "$dbname");
if($conn->connect_error){
	die("Connection Failed: ".$conn->connect_error);
}

$query = "SELECT * FROM user Where username = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userDetail = $result->fetch_assoc(); // Fetch the user details

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body {
		  font-family: Arial, Helvetica, sans-serif;
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
		  width: 200px;
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
		.profile-area {
			margin: auto;
			width: 25%;
			border: 3px solid green;
			display: none;  /* Hide the profile area by default */
			background-color: #f1f1f1;
			padding: 20px;
			margin-top: 20px;
		}
		.profile-area form button{
			width: auto;
		}
		.button-container{
			display: flex;
			justify-content: space-between;

		}
		@media screen and (max-width: 1200px){
			.profile-area {
				width: 50%;
			}
		}
		@media screen and (max-width: 650px){
			.profile-area {
				width: 85%;
			}
		}
	</style>
	<title>Hotel Management System</title>
</head>
<body>
		<div class = "topnav">
			<a href="homepageU.php"><img src="../src/homepage.png" class="icon">Homepage</a>
			<a href="../bookRoom/bookingPage.php"><img src="../src/addBooking.png" class="icon">Book Room</a>
			<a href="../bookRoom/manageBooking.php"><img src="../src/manageBooking.png" class="icon">Manage My Booking</a>
			<a href="logout.php" style="float: right;"><img src="../src/logOut.png" class="icon">Log Out</a>   
			<a href="#" style="float:right;" onclick="toggleProfileArea();"><img src="../src/userProfile.png" class="icon">View Profile</a>
			<a href="../issue/reportIssueForm.php"><img src="../src/report.png" class="icon" >Report Issue</a>
		</div>

		<div class="profile-area" id="profileArea">
		<!-- Profile information goes here -->
		<h2>Profile Information</h2>
		<form action="updateProfile.php" method="POST" onsubmit="return validateForm()">
			<input class="form-control" type="hidden" name="userID" value = "<?php echo $userDetail["userID"] ?>">
			<p>Username: <input class="form-control" type="text" name="username" value = "<?php echo $userDetail["username"] ?>"></p>
			<p>Full Name: <input class="form-control" type="text" name="fullname" value = "<?php echo $userDetail["fullname"] ?>"></p>
			<p>Email : <input class="form-control" type="email" name="email" value="<?php echo $userDetail["email"] ?>"></p>
			<p>Phone Number : <input class="form-control" type="tel" name="phoneno" value="<?php echo $userDetail["phoneNo"] ?>"></p>
			<!-- Add more profile information as needed -->
			<div class="button-container">				
				<button class="form-control btn btn-danger" onclick="toggleProfileArea()">Cancel</button>
				<button class="form-control btn btn-primary" type="submit">Save</button>
			</div>
		</form>
	</div>
	<script>
        function toggleProfileArea() {
			var profileArea = document.getElementById('profileArea');
			if (profileArea.style.display === 'none') {
				profileArea.style.display = 'block';
			} else {
				profileArea.style.display = 'none';
			}
		}

		function validateForm() {
        // Retrieve the form fields
	        var usernameField = document.forms[0].username;
	        var fullnameField = document.forms[0].fullname;
	        var emailField = document.forms[0].email;
	        var phonenoField = document.forms[0].phoneno;

	        // Validate each field
	        if (usernameField.value.trim() === "") {
	            alert("Please enter a username.");
	            usernameField.focus();
	            return false;
	        }

	        if (fullnameField.value.trim() === "") {
	            alert("Please enter your full name.");
	            fullnameField.focus();
	            return false;
	        }

	        if (emailField.value.trim() === "") {
	            alert("Please enter an email.");
	            emailField.focus();
	            return false;
	        }

	        // Perform additional validation for email format, if needed

	        if (phonenoField.value.trim() === "") {
	            alert("Please enter a phone number.");
	            phonenoField.focus();
	            return false;
	        }
	        // If all validations pass, return true to submit the form
	        return true;
    	}
    </script>
</body>
</html>
