<?php
	session_start();
	$server = "localhost";
	$username = "root";
	$password = "";
	$dbname = "hotel_management";
	$conn = new mysqli("$server", "$username", "$password", "$dbname");
	if($conn->connect_error){
		die("Connection Failed: ".$conn->connect_error);
	}
	$fullname = $username = $email = $phoneno = $password = $repeatpassword = "";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$fullname = ($_POST["fullname"]);
		$username = ($_POST["username"]);
		$email = ($_POST["email"]);
		$phoneno = ($_POST["phoneno"]);
		$password = ($_POST["password"]);
		$repeatpassword = ($_POST["repeatpassword"]);
		$signup_err = "";
		if($fullname != "" && $username != "" && $email != "" && $phoneno != "" && $password != "" && $repeatpassword != ""){
			if(!preg_match("/^[a-zA-Z' -]+$/", $fullname)){
				$signup_err = "Invalid name";
			}
			else if(preg_match('/^\w{,5}$/', $username)) { 
				$signup_err = "Invalid username";
			}
			else if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
				$signup_err = "Invalid email";
			}
			else if($_POST["password"] != $_POST["repeatpassword"]){
				$signup_err = "Password Not Match";
			}
			else if($_POST["password"] == $_POST["repeatpassword"]){
				if(strlen($_POST["password"]) < 8){
					$signup_err = "Your password must contain at least 8 characters";
				}
				else if(!preg_match("#[A-Z]+#",$password)){
					$signup_err = "Your password must contain at least 1 capital letter";
				}
				else if(!preg_match("#[a-z]+#",$password)){
					$signup_err = "Your password must contain at least 1 small letter";
				}
				else if(!preg_match("#[0-9]+#",$password)){
					$signup_err = "Your password must contain at least 1 number";
				}
				else{
					$sql = "SELECT userId FROM user WHERE username = ?";
			        if($stmt = mysqli_prepare($conn, $sql)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($stmt, "s", $param_username);
			            
			            // Set parameters
			            $param_username = trim($_POST["username"]);
			            
			            // Attempt to execute the prepared statement
			            if(mysqli_stmt_execute($stmt)){
			                /* store result */
			                mysqli_stmt_store_result($stmt);
			                
			                if(mysqli_stmt_num_rows($stmt) == 1){
			                	$signup_err = "This username is already taken";
			                } 
			                else{
			                    $maxID = "SELECT userId FROM user Where userId = (SELECT max(userId) FROM user)";
			                    $result = $conn->query($maxID);
			                    if ($result){
			                    	$maxID = $result->fetch_assoc();
			                    	$maxUserID = $maxID['userId'];

			                    	$id = $maxUserID + 1;
			                    }
			                    $_SESSION['username'] = $username;
								$role = 1;
								$query = "INSERT INTO user(userId,username,fullname,email,phoneNo,password,role) VALUES (?,?,?,?,?,?,?)";
								$data = $conn->prepare($query);
								$data->bind_param("sssssss",$id,$username,$fullname,$email,$phoneno,$password,$role);
								$data->execute();
								header('Location: ../homepage/homepageU.php');
								exit;
			                }
			            } 
			            else{
			                echo "Oops! Something went wrong. Please try again later.";
			            }
			        }
				}
			}
		}
		else{
				$signup_err = "All fields are required!";
		}
	}
	$conn->close();
?>

<!DOCTYPE html>
<link href="style.css" rel="stylesheet" type="text/css">
<style type="text/css">
	form{ 
	 padding: 15px;
	 padding-top: 2px;
	 border-radius: 5px; 
	}

	body{
	  margin: 0 auto;
	  height: 50vh;
	  width: 100vw;
	  text-align: center;
	  display: flex;
	  justify-content: center;
	  align-items: center;
      font-family: Arial, sans-serif;
	}

	.container{
	  position: relative;
	  border-radius: 5px; 
	  width: 350px;
	  height: 100px;
	}
</style>
<body>
<div class="container">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<h1>Sign Up</h1>
		<?php 
		if (!empty($signup_err)){
			echo '<div class="alert alert-danger">' . $signup_err . '</div>';
		}
	?>
	<table >
		<td>
			<tr>
				<input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
			</tr><br><br>
			<tr>
				<input type="text" id="username" name="username" placeholder="Username" required>
			</tr><br><br>
			<tr>
				<input type="tel" id ="phoneno" name="phoneno" placeholder="Phone No" required>
			</tr><br><br>
			<tr>
				<input type="email" id ="email" name="email" placeholder="Email" required>
			</tr><br><br>
			<tr>
				<input type="password" id ="password" name="password" placeholder="Password" required>
			</tr><br><br>
			<tr>
				<input type="password" id ="repeatpassword" name="repeatpassword" placeholder="Repeat Password" required>
			</tr><br>
			<tr>
				<input type="checkbox" id="termsandcondition" name="termsandcondition" value="tandc" checked>
				<label for="termsandcondition">I agree to the <a href="termsandcondition.html">Terms and Condition</a></label><br>
			</tr>
			<tr>
				<button class="btn btn-primary">Sign Up</button>
			</tr>
		</td>
	</table>
	<label for="login">Already have an account? <a href="../index.php" class="btn btn-link">Login</a></label>
</form>
</div>
</body>
</html>