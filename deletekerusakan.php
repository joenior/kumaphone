<?php
include('koneksi.php');
$query="DELETE from kerusakan where idkerusakan='".$_GET['id']."'";
mysqli_query($konek_db, $query);
header("location:daftarkerusakan.php");
?>