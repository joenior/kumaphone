<?php
if (isset($_GET['checkbox'])) {
  echo "<script>
  alert('Checkbox dipilih');
  window.location.href = 'https://www.example.com';
  </script>";
}
?>