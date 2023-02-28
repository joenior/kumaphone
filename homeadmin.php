<?php
include "session.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Dasbor Admin | Kumaphone</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <style>
      body {
       font-family: 'Inter', sans-serif !important;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      
    </style>

    
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><b>Kumaphone Admin</b></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<h5 class="text-white text-center w-100">Selamat siang</h5>
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
        <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
        <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z"/>
      </svg> Kembali ke halaman utama</a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="homeadmin.php">
              <span data-feather="home"></span>
             Beranda
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="daftarkerusakan.php"">
              <span data-feather="smartphone"></span>
              Kerusakan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gejala.php">
              <span data-feather="list"></span>
              Ciri Kerusakan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="basispengetahuan.php">
              <span data-feather="database"></span>
              Basis Pengetahuan
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Beranda</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="return checkDelete()"><b class="bi bi-person-x-fill">   Hapus semua data</b></button>
          </div>
        </div>
      </div>

                <table id="example1" class="table table-bordered table-striped caption-top">
  <caption>Tabel histori customer</caption>              
   <thead>
                        <tr>
                            <th>NO</th>
                            <th>Nama customer</th>
							              <th>Brand & Model</th>
                            <th>IMEI</th>
                            <th>Sistem Operasi</th>
                            <th>ID Kerusakan</th>
                        </tr>
                    </thead>

                    <?php
      $tampil="select * from smartphones";
			$query= mysqli_query($konek_db,$tampil);
      $id=0;
                while($hasil=mysqli_fetch_array($query)){
                  $id++;
					echo "
          <tr>
          <td>".$hasil[0]."</td>
          <td>".$hasil[1]."</td>
          <td>".$hasil[2]."</td>
          <td>".$hasil[3]."</td>
          <td>".$hasil[4]."</td>
          <td>".$hasil[5]."</td>";

                   }
                  
					?>
    </div>

    <script src="bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>

    <script language="JavaScript" type="text/javascript">
    function checkDelete(){
      if (confirm("Yakin ingin menghapus semua data histori?")) {
    window.location.href = "deletehistori.php";
  }
}
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  </body>
</html>
