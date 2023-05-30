<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
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

// Retrieve updated profile information from the POST data
$userID = $_POST['userID'];
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$phoneno = $_POST['phoneno'];

// Prepare and execute the UPDATE query
$query = "UPDATE user SET username = ?, fullname = ?, email = ?, phoneNo = ? WHERE userID = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("sssss",$username, $fullname, $email, $phoneno,$userID);
    $stmt->execute();
    $stmt->close();
    echo "hello";
    $_SESSION['username'] = $username;
}

$getUserQuery = "SELECT * FROM user WHERE userID = ?";
$stmtUser = $conn->prepare($getUserQuery);
if ($stmtUser){
    $stmtUser->bind_param("s",$userID);
    $stmtUser->execute();
    $result = $stmtUser->get_result();
    $userDetail = $result->fetch_assoc();
    $stmtUser->close();
}
$conn->close();

// Redirect the user back to the profile page after the update
header("location: homepageU.php?username=".urldecode($username));
exit();
?>