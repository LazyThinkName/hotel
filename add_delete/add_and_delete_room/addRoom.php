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


<!DOCTYPE HTML>
<head>
<link href="addRoom.css" rel="stylesheet" type="text/css">
<style type="text/css">
  body{
  text-align: left;
  align-items: left;
  font-family: Arial, sans-serif;
  }

  legend{
  font-size: 25px;
  color: #239483;
  }

  fieldset{
  border-color: darkgrey;
  border-width: 15px;
  display: block;
  width: 30%;
  background-color: floralwhite;
  margin: auto;
  }

  input[type=radio]{
    margin: 10px;
  }

  label span{
    display: block;
  }

  input[type=text]{
    width: 60%;
    height: 20px;
  }

  textarea{
    width: 65%;
  }

  input[type=submit]{
    float: right;
    background-color: #000000;
    color: #Ffffff;
    padding-left: 5px;
    width: 70px;
    font-size: 17px;
  }

  button{
    float: inherit;
    background-color: #000000;
    color: #Ffffff;
    padding-left: 5px;
    width: 70px;
    font-size: 17px;
    cursor: pointer;
  }

</style>
</head>
<html>
  <body>
     <fieldset>
      <legend>Add Room</legend>
        <form id ="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <label for="room_type">Room Type:</label><br>
          <!--radio button goes here--> 
          <input type="radio" id="deluxe" name="roomtype" value="Deluxe" checked>
          <label for="deluxe">Deluxe</label>
          <input type="radio" id="standard" name="roomtype" value="Standard">
          <label for="standard">Standard</label><br><br>

          <label for="roomnumber"><span>Room Number:</span></label>
          <input type="text" id="roomnumber" name="roomnumber" value="<?php echo isset($_POST['roomnumber']) ? $_POST['roomnumber'] : ''; ?>"><br><br>

          <label for="roomdetail"><span>Details:</span></label>
          <textarea id="detail" name="detail"
          rows="7" cols="25" required><?php echo isset($_POST['detail']) ? $_POST['detail'] : ''; ?></textarea> <br><br>

          <label for="roomprice"><span>Price:</span></label>
          <input type="text" id="roomprice" name="roomprice" value="<?php echo isset($_POST['roomprice']) ? $_POST['roomprice'] : ''; ?>"><br><br>

          <label for="roomunit"><span>Units:</span></label>
          <input type="number" id="roomunit" name="roomunit" value="<?php echo isset($_POST['roomunit']) ? $_POST['roomunit'] : ''; ?>"><br><br>

          <input type="submit" value="Save" style="cursor: pointer;">
        </form>
        <a href="index.php"><button id="back">Cancel</button></a>
     </fieldset>
     <?php
    //connect to the database
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
    //validate add room form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $roomtype = trim($_POST["roomtype"]);
        $roomnumber = trim($_POST["roomnumber"]);
        $details = trim($_POST["detail"]);
        $price = trim($_POST["roomprice"]);
        $unit = trim($_POST["roomunit"]);
        $checkroomnumber = "SELECT * FROM room WHERE roomnumber = '$roomnumber'";
        $result = mysqli_query($mysqli, $checkroomnumber);
        $errorMessage = "";
        if(empty($roomnumber) || empty($details) || empty($price) ||
        empty($unit) || !preg_match("/^[0-9]{3}$/", $roomnumber) || (!preg_match('/^\d+(\.\d{1,2})?$/', $price) && $price < 0)  || !preg_match('/^[1-9]|10$/', $unit) || mysqli_num_rows($result) > 0 || !is_numeric($price)){    
          if(!preg_match("/^[0-9]{3}$/", $roomnumber)){
              $errorMessage = "The roomnumber must be three digits range from 0 to 9. Example: 001";
          }
          if(empty($details)){
            $errorMessage = "Please enter the details regarding the room!";
          }
          if(mysqli_num_rows($result) > 0) {
              $errorMessage = "Room number already exists";
          }
          if (!preg_match('/^\d+(\.\d{1,2})?$/', $price) && $price < 0) {
            $errorMessage = "Please enter the valid room price!";
          }
          if (!preg_match('/^[1-9]|10$/', $unit)) {
            $errorMessage = "Our hotel only have unit range from 1 to 10.";
          }
          if (!is_numeric($price)){
            $errorMessage = "The price must be number.";
          }
          if ($errorMessage != ""){
            echo "$errorMessage";
          }
        }
        //submit when form has no incorrect input
        else{
          $maxID = "SELECT roomID FROM room WHERE roomID = (SELECT max(roomID) FROM room)";
          $result = $mysqli->query($maxID);
          if ($result){
            $maxID = $result->fetch_assoc();
            $maxRoomID = $maxID['roomID'];

            $id = $maxRoomID + 1;
            $status = true;
          }
          $addroom = "INSERT INTO room (roomID, roomNumber, roomType, detail, price, unit,statusID) VALUES('$id','$roomnumber', '$roomtype', '$details', '$price', '$unit', '$status')";

          if(mysqli_query($mysqli, $addroom)){
            echo "The room is added";
          }
          else{
              echo"<p>Failed to Add Room!!!</p>";
          }
        }
      }
    //close connection
    $mysqli->close();
    ?>
  </body>
</html>

