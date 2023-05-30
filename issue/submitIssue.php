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

$server = "localhost";
$serverUser = "root";
$password = "";
$dbname = "hotel_management";
$conn = new mysqli("$server", "$serverUser", "$password", "$dbname");
if($conn->connect_error){
	die("Connection Failed: ".$conn->connect_error);
}

$issue = $_POST['issue'];
$date = date('Y-m-d');

$queryID = "SELECT issueID FROM issue WHERE issueID = (SELECT max(issueID) FROM issue)";
$result = $conn->query($queryID);
if ($result){
    $maxID = $result->fetch_assoc();
    $maxIssueID = $maxID['issueID'];

    $id = $maxIssueID + 1;
}

$user = "SELECT userID FROM user Where username = ?";
$stmtUser = $conn->prepare($user);
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

$query = "INSERT INTO issue VALUES (?,?,?,?)";
$stmtIssue = $conn->prepare($query);
if ($stmtIssue){
	$stmtIssue->bind_param("ssss",$id,$issue,$date,$userID);
	$stmtIssue->execute();
	$stmtIssue->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Management System</title>
    <style type="text/css">
        body {
          font-family: Arial, Helvetica, sans-serif;
          background-image: url(../src/hotel.jpg);
          background-repeat: no-repeat;
          background-size: 100%;
        }
    </style>
</head>
<body>
</body>
</html>