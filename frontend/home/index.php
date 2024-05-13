<?php
  include('backend_func/db.php');
  include('backend_func/inventory.php');
  $inv_tbl = new inventory();
  $notif = $inv_tbl->checkdb();
  echo "<div style='text-align:center;'>".$notif."</div>";
?>