<?php
// Initialize the session
session_start();
$server = "localhost";
$serverUser = "root";
$password = "";
$dbname = "hotel_management";
$conn = new mysqli("$server", "$serverUser", "$password", "$dbname");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

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

$checkInDate = $_POST['checkInDate'];
$checkOutDate = $_POST['checkOutDate'];
$roomID = $_POST['roomID'];
$price = $_POST['price'];
$checkInTimestamp = strtotime($checkInDate);
$checkOutTimestamp = strtotime($checkOutDate);
$durationInSeconds = $checkOutTimestamp - $checkInTimestamp;
$durationInDays = floor($durationInSeconds / 86400);

$payment = $durationInDays * $price;

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

$maxID = "SELECT bookingId FROM booking WHERE bookingId = (SELECT max(bookingId) FROM booking)";
$result = $conn->query($maxID);
if ($result){
$maxID = $result->fetch_assoc();
$maxBookingID = $maxID['bookingId'];

$id = $maxBookingID + 1;
}


$query = "INSERT INTO booking VALUES (?,?,?,?,?,?)";
$stmtPay = $conn->prepare($query);
if ($stmtPay){
    $stmtPay->bind_param("ssssss",$id,$checkInDate,$checkOutDate,$payment,$roomID,$userID);
    $stmtPay->execute();
    $stmtPay->close();
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