<?php
include('koneksi.php');
$query="DELETE from basispengetahuan where namakerusakan='".$_GET['id']."'";
mysqli_query($konek_db, $query);
header("location:basispengetahuan.php");
?>