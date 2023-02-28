<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sbp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nama_user = $_POST['nama_user'];
$brand = $_POST['brand'];
$imei = $_POST['imei'];
$date_log = date('Y-m-d H:i:s');

$sql = "INSERT INTO diagnosa (nama_user, date_log, brand, imei)
VALUES ('$nama_user', '$date_log', '$brand', '$imei')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>