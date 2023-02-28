<?php
include('koneksi.php');
$query="truncate smartphones";
mysqli_query($konek_db, $query);
header("location:homeadmin.php");
?>