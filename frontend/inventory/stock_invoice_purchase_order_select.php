<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_invoice.php');
  $inv_tbl = new purchase_invoice();
  $result="";
  $_SESSION['purchase_order_search'] = "";
  $_SESSION['purchase_order_filter_date'] ="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "select"){
      $_SESSION['purchase_orderView_id'] = $_POST['purchase_orderView_id'];
      header("Location: stock_invoice_add.php");
      exit();
    }else if($_POST['submit'] == "Search"){
      $_SESSION['purchase_order_search'] = $_POST['search_input'];
      $_SESSION['purchase_order_filter_date'] = $_POST['date_input'];
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
  <div class="text">Receiving Report: Select Purchase Order</div>

  <div class="header-container">
    <a href="stock_invoice_page.php"><button class="add-button">Return</button></a>
    <div class="search-bar">
      <form method="POST">
      <input type="date" id="dateInput" name="date_input">
      <input type="text" id="searchInput" name="search_input" placeholder="Search purchase_order">
      <input type="submit" name="submit" value="Search" class="search-button">
      </form>
    </div>
  </div>

  <div class="inventory-table">
    <table class="item-table">
      <thead>
        <tr>
          <th>Order Number</th>
          <th>Supplier</th>
          <th>Order Date</th>
          <th>Delivery Date</th>
          <th>Total Price</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_purchase_orders_select($_SESSION['purchase_order_search'],$_SESSION['purchase_order_filter_date']);?>       
      </tbody>
    </table>

  </div>

  <script src="backend_func/JSfunctions.js"></script>
</body>
  