<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_invoice.php');
  include('backend_func/purchase_order.php');
  $inv_tbl = new purchase_invoice();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Edit"){
      header("Location: stock_invoice_view_edit.php");
      exit();
    }else if($_POST['submit'] == "Archive"){
      $result = $inv_tbl->archive_invoice($_SESSION['invoiceView_id']);
      header("Location: stock_invoice_page.php");
      exit();
    }else if($_POST['submit'] == "Return"){
      header("Location: stock_invoice_page.php");
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
  <title> Inventory </title>
</head>
<body>
<?php 
  if($_SESSION["access"]!="Admin"){
    include 'include_sidebar_reg.php';
  }else{
    include 'include_sidebar.php';
  };
?>
<form method="POST">
<section class="home-section">
  <div class="text">Receiving Report: View</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div style="margin-left:10%">
  <div class="add-item-container" style="float:left;width:30%">
    <div class="add-item">
      <input type="submit" name="submit" value="Edit" class="add-button" onclick="return confirm('Edit Receiving Report?')">
      <input type="submit" name="submit" value="Archive" class="add-button" onclick="return confirm('Archive Receiving Report?')">
      <div class="input-container">
      <label for="orderslip-num">Receiving Report Number:</label>
      <input type="text" id="orderslip-num" name="orderslip-num" value="<?php echo $info = $inv_tbl->get_invoice_info($_SESSION['invoiceView_id'],'invoice_no')?>" readonly><br>
      </div>
      <div class="input-container">
      <label for="delivery-date">Delivery Date:</label>
      <input type="date" id="delivery-date"  name="delivery-date" value="<?php echo $info = $inv_tbl->get_invoice_info($_SESSION['invoiceView_id'],'invoice_date')?>" readonly><br>
      </div>
    </div>
  </div>
  <div class="inventory-table" style="float:left">
  <table class="item-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Stock Quantity</th>
          <th>Active Quantity</th>
          <th>Expiry Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_invoice_items($_SESSION['invoiceView_id'])?>
      </tbody>
    </table>
  </div>
  </form>
  </div>
  </section>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  