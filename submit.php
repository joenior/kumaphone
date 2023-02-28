<?php
$conn = mysqli_connect('localhost', 'root','', 'sbp');
$brand = $_POST['brand'];
$model = $_POST['model'];
$imei = $_POST['imei'];

$sql = "INSERT INTO smartphones (brand, model, imei) VALUES ('$brand', '$model', '$imei')";

if (mysqli_query($conn, $sql)) {
    echo "alNew record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);