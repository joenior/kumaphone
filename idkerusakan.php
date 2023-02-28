   <?php     
if(isset($_POST['submit']))
            $gejala = $_POST['gejala'];
            $jumlah_dipilih = count($gejala);
           for($x=0;$x<$jumlah_dipilih;$x++)
                       $tampil ="select DISTINCT p.idkerusakan, p.namakerusakan, p.jenishp from basispengetahuan b, kerusakan p where b.gejala='$gejala[$x]' and p.namakerusakan=b.namakerusakan group by namakerusakan";
                       $result = mysqli_query($konek_db, $tampil);
                       $hasil  = mysqli_fetch_array($result);
                                  