<?php
include('koneksi.php');

if(isset($_SESSION['login_user'])){
header("location: about.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Kumaphone</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="img/2.png">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
</head>

    <style>
body {
  font-family: 'Inter', sans-serif !important;
}
</style>
</head>
<body style="background-color:#101927;">
<header>
    <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="index.html" class="flex items-center">
                <img src="img/logo.png" class="mr-3 h-6 sm:h-9" alt="Flowbite Logo" />
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Kumaphone <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Beta</span></span>
            </a>
            <div class="flex items-center lg:order-2">
                <a href="login.html" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Admin</a>
                <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">

                    <li>
                        <a href="/"    class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Halaman Utama</a>
                    </li>
                    <li>
                        <a href="kerusakan.php" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Ciri-ciri Kerusakan</a>
                    </li>
                    <li>
                        <a href="diagnosa.php"  class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-white lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Diagnosa Kerusakan</a>
                    </li>
                    <li>
                        <a href="about.php"     class="block py-2 pr-4 pl-3 rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 page dark:text-gray-400" aria-current="">Tentang Kami</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- Breadcrumb -->
<nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center">
      <a href="index.html" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        Halaman Utama
      </a>
    </li>
    <li>
      <div class="flex items-center">
        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        <a href="diagnosa.php" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Diagnosa Kerusakan</a>
      </div>
    </li>
    <li aria-current="page">
     
    </li>
  </ol>
</nav>
<section class="bg-white dark:bg-gray-900 ">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
      <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <form id="form2" name="form2" method="post" action="diagnosa.php">
        <div class="w-full p-4 sm:p-6 md:p-8 lg:p-10 bg-white border rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
        <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Form Smartphone</h5>
  <div class="mb-6">
    <label for="text" class="block mb-2 text-sm text-left font-medium text-gray-900 dark:text-white">Nama customer</label>
    <input type="text" id="brand" name="brand" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
  </div>
  <div class="mb-6">
    <label for="text" class="block mb-2 text-sm text-left font-medium text-gray-900 dark:text-white">Brand & model smartphone</label>
    <input type="text" id="model" name="model" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
  </div>
  <div class="mb-6">
    <label for="text" class="block mb-2 text-sm text-left font-medium text-gray-900 dark:text-white">IMEI smartphone</label>
    <input type="text" id="imei" name="imei" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
  </div>
  <label for="text" class="block mb-2 text-sm text-left font-medium text-gray-900 dark:text-white">Sistem Operasi</label>
				<select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="submit" name="jenishp" id="jenishp">
				<option>Pilih</option>
                <option>Android</option>
  		</select>
      
      <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded md:my-10 dark:bg-gray-700">
  
      <?php 
              global $hasil;
if(isset($_POST['submit'])){
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $imei = $_POST['imei'];
    $jenishp = $_POST['jenishp'];
    $gejala = $_POST['gejala'];

    // Initialize an empty array to store idkerusakan
    $idkerusakanList = [];

    // Loop through each selected symptom
    foreach($gejala as $selectedGejala) {
        // Query to find idkerusakan based on the selected symptoms
        $query = "SELECT DISTINCT p.idkerusakan FROM basispengetahuan b, kerusakan p WHERE b.gejala='$selectedGejala' AND p.namakerusakan=b.namakerusakan";
        $result = mysqli_query($konek_db, $query);

        // Fetch the result and store idkerusakan
        while($row = mysqli_fetch_array($result)) {
            $idkerusakanList[] = $row['idkerusakan'];
        }
    }

    // Use the first idkerusakan found (or handle multiple if needed)
    $idkerusakan = !empty($idkerusakanList) ? $idkerusakanList[0] : null;

    // Check if idkerusakan is valid before inserting
    if ($idkerusakan) {
        $sql = "INSERT INTO smartphones (brand, model, imei, jenishp, idkerusakan) VALUES ('$brand', '$model', '$imei', '$jenishp', '$idkerusakan')";
        if (mysqli_query($konek_db, $sql)) {
          echo '<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
          <span class="font-medium">Data berhasil dicatat!</span> Silahkan cek diagnosa pada tabel dibawah.
        </div>';
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($konek_db);
        }
    } else {
        echo "No valid idkerusakan found for the selected symptoms.";
    }
}
      ?>
      	<?php
                echo  "<label class='mb-2 text-2xl text-left font-bold text-gray-900 dark:text-white'>Ciri Kerusakan<br/><br/></label>";
      $hp = "Android";
       $tampil="select * from gejala where jenishp= \"".$hp."\"";
			$query= mysqli_query($konek_db,$tampil);
                while($hasil=mysqli_fetch_array($query)){
					echo "<div class='text-left rtl:text-right'><input type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'
          value='".$hasil['gejala']."' name='gejala[]' />   
                    <label for='default-checkbox' class='ml-2 text-sm font-medium text-gray-900 dark:text-gray-300'>".$hasil['gejala']."</label> <br></div>";
			// }
                   }
					?>

                 
      <br/>
      <center>
        <button type="submit" name ="submit" onclick="return checkDiagnosa()" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cek Kerusakan</button>
                  </center>
                  <!--   Akhir bagian Menu pada Header -->
                  </div>
                  </div>
                  </div>
                  </div>
<section class="bg-white dark:bg-gray-900 ">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
      <div class="mx-auto max-w-screen-sm mb-8 lg:mb-16">
     <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table id="example1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            Diagnosa Kerusakan
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Berikut adalah sparepart yang harus diganti atau tindakan perbaikan yang harus dilakukan untuk memperbaiki kerusakan</p>
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3">ID Kerusakan</th>
                <th scope="col" class="px-6 py-3">Nama Kerusakan</th>
                <th scope="col" class="px-6 py-3">Sistem Operasi</th>
                <th scope="col" class="px-6 py-3">Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($_POST['submit'])){
                $gejala = $_POST['gejala'];
                $jumlah_dipilih = count($gejala);
                for($x=0; $x<$jumlah_dipilih; $x++){
                    $tampil = "SELECT DISTINCT p.idkerusakan, p.namakerusakan, p.jenishp FROM basispengetahuan b, kerusakan p WHERE b.gejala='$gejala[$x]' AND p.namakerusakan=b.namakerusakan GROUP BY namakerusakan";
                    $result = mysqli_query($konek_db, $tampil);
                    $hasil = mysqli_fetch_array($result);

                    echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>
                            <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>".($x+1)."</th>
                            <td class='px-6 py-4'>".$hasil['idkerusakan']."</td>
                            <td class='px-6 py-4'>".$hasil['namakerusakan']."</td>
                            <td class='px-6 py-4'>".$hasil['jenishp']."</td>
                            <td class='px-6 py-4 text-right'><a href='hasildiagnosa.php?id=".$hasil['idkerusakan']."'><i class='bi bi-search'></i></a></td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
            </div>
                    </div>
                </div>
        </form>
    </div>
  </div>
</div>
</div>


</body>

<footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://kumaphone.my.id" class="hover:underline">Kumaphone™</a>. All Rights Reserved.
    </span>
    <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
        <li>
            <a href="#" class="mr-4 hover:underline md:mr-6 ">About</a>
        </li>
        <li>
            <a href="#" class="mr-4 hover:underline md:mr-6">Privacy Policy</a>
        </li>
        <li>
            <a href="#" class="mr-4 hover:underline md:mr-6">Licensing</a>
        </li>
        <li>
            <a href="#" class="hover:underline">Contact</a>
        </li>
    </ul>
</footer>
<script>
  var select = document.querySelector("select");
select.addEventListener("change", function() {
  if (this.value === "") {
    alert("Semua opsi dipilih!");
  }
});
</script>
<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

</html>

