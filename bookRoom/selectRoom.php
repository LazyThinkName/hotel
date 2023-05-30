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
$roomType = $_POST['roomType'];
$unit = $_POST['unit'];
$statusID = 1;
if ($checkOutDate <= $checkInDate){
    echo '<script>alert("The check-out date must be greater than the check-in date"); window.location.href = "bookingPage.php";</script>';
    exit();
}
if ($unit < 1 || $unit > 10){
    echo '<script>alert("Room unit must be between 1 and 10."); window.location.href = "bookingPage.php";</script>';
    exit();
}

$query = "SELECT * FROM room 
          WHERE statusID = ? 
          AND roomType = ? 
          AND unit = ? 
          AND roomID NOT IN (
              SELECT roomID FROM booking 
              WHERE (checkInDate <= ? AND checkOutDate >= ?)
              OR (checkInDate <= ? AND checkOutDate >= ?)
              OR (checkInDate >= ? AND checkOutDate <= ?)
          )";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("sssssssss", $statusID, $roomType, $unit, $checkInDate, $checkInDate, $checkOutDate, $checkOutDate, $checkInDate, $checkOutDate);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result){
        $room = $result->fetch_all(MYSQLI_ASSOC);
    }
    
    $stmt->close();
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

        .room {
            margin: auto;
            width: 45%;
            border: 3px solid green;
            background-color: lightyellow;
            padding: 20px;
            margin-top: 20px;
        }

        a{
            margin: 20px;
        }
    </style>
</head>
<body>
    <?php if ($room) : ?>
        <a href="bookingPage.php" class="btn btn-danger">Back</a>
        <?php foreach ($room as $roomItem) : ?>
            <div class="room">
                <form action="payment.php" method="POST">
                    <input type="hidden" name="roomID" value="<?php echo $roomItem["roomID"] ?>">
                    <input type="hidden" name="checkInDate" value="<?php echo $checkInDate ?>">
                    <input type="hidden" name="checkOutDate" value="<?php echo $checkOutDate ?>">
                    <input type="hidden" name="price" value="<?php echo $roomItem["price"] ?>">
                    <p style="font-weight: bold; text-align: center;">Room Number: <?php echo $roomItem["roomNumber"] ?></p>
                    <p>Price/Day: <?php echo $roomItem["price"] ?></p>
                    <p><?php echo $roomItem["detail"] ?></p>
                    <button type="submit" class="btn btn-success" >Select</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="no-rooms">
            <p>No Room is available</p>
        </div>
    <?php endif; ?>
</body>
</html>