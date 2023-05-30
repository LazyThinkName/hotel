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
$month = date("m");
$year = date("Y");

$query = "SELECT COUNT(*) AS count FROM room";
$result = $conn->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $totalRooms = $row['count'];
} else {
    $totalRooms = 0;
}

$queryDayIncome = "SELECT SUM(paymentPrice) AS dayPaymentPrice FROM booking WHERE checkInDate = '$date'";
$result = $conn->query($queryDayIncome);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dayIncome = $row['dayPaymentPrice'];
    if ($dayIncome === null){
        $dayIncome = 0;
    }
} else {
    $dayIncome = 0;
}

$queryMonthIncome = "SELECT SUM(paymentPrice) AS monthPaymentPrice FROM booking WHERE MONTH(checkInDate) = '$month'";
$result = $conn->query($queryMonthIncome);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $monthIncome = $row['monthPaymentPrice'];
    if ($monthIncome === null){
        $monthIncome = 0;
    }
} else {
    $monthIncome = 0;
}

$queryYearIncome = "SELECT SUM(paymentPrice) AS yearPaymentPrice FROM booking WHERE Year(checkInDate) = '$year'";
$result = $conn->query($queryYearIncome);
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $yearIncome = $row['yearPaymentPrice'];
    if ($yearIncome === null){
        $yearIncome = 0;
    }
} else {
    $yearIncome = 0;
}

$querySelect = "SELECT * FROM profit WHERE MONTH(month) = '$month'";
$stmtMonth = $conn->query($querySelect);
if ($stmtMonth){
    $rowMonth = $stmtMonth->fetch_assoc();
    if ($rowMonth == null){            
        $maxID = "SELECT profitID FROM profit WHERE profitID = (SELECT max(profitID) FROM profit)";
        $resultID = $conn->query($maxID);
        if ($resultID){
            $maxID = $resultID->fetch_assoc();
            if ($maxID === null){
                $id = 1;
            }
            else{
                $maxProfitID = $maxID['profitID'];
                $id = $maxProfitID + 1;
            }

        }
        $queryInsert = "INSERT INTO profit VAlUES (?,?,?,?,?)";
        $stmtInsert = $conn->prepare($queryInsert);
        if ($stmtInsert){
            $stmtInsert->bind_param("sssss",$id,$monthIncome,$yearIncome,$date,$year);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
    }
    else {
        $queryUpdateMonth = "UPDATE profit SET monthProfit = ?, yearProfit = ? WHERE MONTH(month) = ?";
        $stmtUpdateMonth = $conn->prepare($queryUpdateMonth);
        if ($stmtUpdateMonth) {
            $stmtUpdateMonth->bind_param("sss",$monthIncome, $yearIncome, $month);
            $stmtUpdateMonth->execute();
            $stmtUpdateMonth->close();
        }
    }
}

$querySelectYear = "SELECT * FROM profit WHERE year = '$year'";
$stmtYear = $conn->query($querySelectYear);
if ($stmtYear){
    $stmtYear = $stmtYear->fetch_all(MYSQLI_ASSOC);
    if ($stmtYear == null){            
        $maxID = "SELECT profitID FROM profit WHERE profitID = (SELECT max(profitID) FROM profit)";
        $resultID = $conn->query($maxID);
        if ($resultID){
            $maxID = $resultID->fetch_assoc();
            if ($maxID === null){
                $id = 1;
            }
            else{
                $maxProfitID = $maxID['profitID'];
                $id = $maxProfitID + 1;
            }

        }
        $queryInsert = "INSERT INTO profit VAlUES (?,?,?,?,?)";
        $stmtInsert = $conn->prepare($queryInsert);
        if ($stmtInsert){
            $stmtInsert->bind_param("sssss",$id,$monthIncome,$yearIncome,$date,$year);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
    }
    else {
    $queryUpdateYear = "UPDATE profit SET monthProfit = ?, yearProfit = ? WHERE year = ?";
    $queryUpdateYear = $conn->prepare($queryUpdateMonth);
        if ($queryUpdateYear) {
            $queryUpdateYear->bind_param("sss",$monthIncome, $yearIncome, $month);
            $queryUpdateYear->execute();
            $queryUpdateYear->close();
        }
    }
}

// generate privous 6 years until current year
$xValues2 = [];
$currentYear = (int)$year;
for ($i = 5; $i >= 0; $i--) {
    $prevYear = $currentYear - $i;
    $queryYear = "SELECT yearProfit FROM profit WHERE year = '$prevYear' LIMIT 1";
    $result = $conn->query($queryYear);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $yValues2[] = $row['yearProfit'];
        if ($yValues2 === null){
            $yValues2[] = 0;
        }
    } else {
        $yValues2[] = 0;
    }
    $xValues2[] = $prevYear;
}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <style type="text/css">
        body{
            background-image: url(../src/hotel.jpg);
            font-family: Arial, sans-serif;
            background-repeat: no-repeat;
            background-size: 100%;
            justify-content: center;
            align-items: center;
        }

        label{
            margin-left: 25px;
            margin-right: 25px;
        }

        #income{
            background-color: whitesmoke;
            font-family: Arial, sans-serif;
            font-size: 25px;
            max-width: 1200px;
            margin: auto;
            margin-right: 123.5px;
            text-align: center;
        }

        .chart-container {
            display: flex;
            max-width: 600px;

        }

        .chart-container canvas {
            flex: 1;
            background-color: whitesmoke;
        }

        #myChart {
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
    <div id ="income">
        <label>Day's Income : RM<?php echo "$dayIncome" ?></label>
        <label>Month's Income : RM<?php echo "$monthIncome" ?></label>
        <label>Year's Income : RM<?php echo "$yearIncome" ?></label>
    </div>
    <div class="container" id="reportPage">
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        const xValues2 = <?php echo json_encode($xValues2); ?>;
        const yValues2 = <?php echo json_encode($yValues2); ?>;

        new Chart("myChart", {
          type: "line",
          data: {
            labels: xValues2,
            datasets: [{
              label: "Year's Profit",
              backgroundColor:"rgba(0,0,255,1.0)",
              borderColor: "rgba(0,0,255,0.1)",
              data: yValues2
            }]
          },
            options: {
            title: {
              display: true,
              text: "Privious 6 Year's Profit"
            }
          }
        });
    </script>

</body>
</html>