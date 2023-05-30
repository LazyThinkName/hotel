<?php
// Initialize the session
session_start();
$server = "localhost";
$serverUser = "root";
$password = "";
$dbname = "hotel_management";
$link = new mysqli("$server", "$serverUser", "$password", "$dbname");
if($link->connect_error){
    die("Connection Failed: ".$link->connect_error);
}
 
// Check if the user is already logged in, if yes then redirect him to homepage
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $query = "SELECT role FROM user Where username = ?";
        $stmtQry = $link->prepare($query);
        $stmtQry->bind_param("s",$username);
        $stmtQry->execute();
        $result = $stmtQry->get_result();
        $userRole = $result->fetch_assoc();
        $Role = $userRole['role']; 

        // Redirect user to homepage
        if ($Role == 1){
            header("location: homepage/homepageU.php?username=" . urlencode($username));
        }
        else{
            header("location: homepage/homepageA.php?username=" . urlencode($username));
        }
        exit;
    }
    
}
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT userId, username, password FROM user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $hashed_password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userId"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            $query = "SELECT role FROM user Where username = ?";
                            $stmtQry = $link->prepare($query);
                            $stmtQry->bind_param("s",$username);
                            $stmtQry->execute();
                            $result = $stmtQry->get_result();
                            $userRole = $result->fetch_assoc();
                            $Role = $userRole['role']; 

                            // Redirect user to homepage
                            if ($Role == 1){
                                header("location: homepage/homepageU.php?username=" . urlencode($username));
                            }
                            else{
                                header("location: homepage/homepageA.php?username=" . urlencode($username));
                            }

                            exit();
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-image: linear-gradient(to right, #b6fbff, #83a4d4);
            font-family: Arial, sans-serif;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #cccccc;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin: 10% auto;
            max-width: 400px;
            padding: 20px;
            text-align: center;
        }

        .button{
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            max-width: 200px;
            padding: 12px 20px;
            margin: 10% auto;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        input[type=text], input[type=password] {
            border: 2px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 20px;
            padding: 10px;
            width: 100%;
        }

        button {
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            padding: 12px 20px;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        span {
            color: #666666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <img src="src/userProfile.png" alt="Login" width="100" height="100">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter Username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter Password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Login">
            </div>
            <p>Don't have an account? <a href="signup/store.php">Sign up</a>.</p>
        </form>
    </div>
</body>
</html>