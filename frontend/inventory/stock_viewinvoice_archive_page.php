<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['invoice_search'] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "un-Archive"){
      $result = $inv_tbl->unarchive_invoice($_SESSION['invoiceView_id']);
      header("Location: stock_invoice_archive_page.php");
      exit();
    }if($_POST['submit'] == "delete"){
      $result = $inv_tbl->delete_invoice_entirely($_SESSION['invoiceView_id']);
      header("Location: stock_invoice_archive_page.php");
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
<section class="home-section">
  <div class="text">View Invoice</div>
  <div class="header-container">
    <a href="stock_invoice_archive_page.php"><button class="add-button">Return</button></a>
  </div>
  <div class="add-item-container">
  <div class="add-item">
  <div class="input-container">
    <form method="POST" onSubmit="return confirm('Are you sure?');">
    <input type="submit" name="submit" value="delete" class="add-button">
    </form>
    <form method="POST" onSubmit="return confirm('Are you sure?');">
    <input type="submit" name="submit" value="un-Archive" class="add-button">
    <?php $inv_tbl->show_invoice_info($_SESSION['invoiceView_id']);?>
  </div>
  </div>
  </div>
  <div class="inventory-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Stock ID</th>
          <th>Item Name</th>
          <th>Stock Quantity</th>
          <th>Stock Price</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_invoice_stocks($_SESSION['invoiceView_id']);?>
      </tbody>
    </table>

  </div>
  </form>
  <script src="backend_func/JSfunctions.js"></script>
</body>
  