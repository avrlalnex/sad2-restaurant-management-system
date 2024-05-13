<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['stock_search'] = "";
  $_SESSION['stock_filter'] = "Goods";
  $_SESSION['stock_filter_INOUT'] = "STOCK IN";
  $_SESSION['stock_filter_date'] ="";
  $_SESSION['stockout_samedate_alert'] = false;
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Search"){
      $_SESSION['stock_search'] = $_POST['search_input'];
      $_SESSION['stock_filter'] = $_POST['filter_input'];
      $_SESSION['stock_filter_INOUT'] = $_POST['filter_input_INOUT'];
      $_SESSION['stock_filter_date'] = $_POST['date_input'];
    }else if($_POST['submit'] == "delete"){
      $result = $inv_tbl->delete_supply_stock($_POST['delete_id'],$_POST['delete_type']);
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
  <div class="text">Stock In/Out</div>

  <div class="header-container">
  <a href="inventory_page.php" class="return-icon"><ion-icon name="arrow-back-outline"></ion-icon></a> 
    <a href="purchase_orders_page.php"><button class="add-btn">Purchase Orders</button></a>
    <a href="stock_invoice_page.php"><button class="add-btn">Receiving Reports</button></a>
    <a href="stock_stockout_forms.php"><button class="add-btn">Stock OUT Forms</button></a>
    <a href="stock_page_history.php"><button class="add-btn">Item Stock Comparison</button></a>
    <div class="search-bar">
      <form method="POST">
      <input type="date" id="dateInput" name="date_input">
      <select id="filterInput" name="filter_input">
        <option value="<?php echo $_SESSION['stock_filter']?>" selected hidden><?php echo $_SESSION['stock_filter']?></option> 
        <option value="Goods">Goods</option>
        <option value="Supply">Supply</option>
      </select>
      <select id="filterInput" name="filter_input_INOUT">
        <option value="<?php echo $_SESSION['stock_filter_INOUT']?>" selected hidden><?php echo $_SESSION['stock_filter_INOUT']?></option> 
        <option value="STOCK IN">STOCK IN</option>
        <option value="STOCK OUT">STOCK OUT</option>
      </select>
      <input type="text" id="searchInput" name="search_input" placeholder="Search Item Name">
      <input type="submit" name="submit" value="Search" class="search-button">
      </form>
    </div>
  </div>

  <div class="inventory-table">
    <table class="item-table">
      <thead>
        <tr>
          <?php $inv_tbl->show_stocks_thead($_SESSION['stock_filter'],$_SESSION['stock_filter_INOUT']);?>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_stocks($_SESSION['stock_search'],$_SESSION['stock_filter'],$_SESSION['stock_filter_INOUT'],$_SESSION['stock_filter_date']);?>       
      </tbody>
    </table>

  </div>
<style>
#filterInput {
  padding: 7px;
  border: 1px solid #ccc;
  border-radius: 30px;
  background-color: rgb(196, 71, 71);
  color: white;
  cursor: pointer;
  margin-right: 10px;
}
.return-icon {
  font-size: 30px; 
  margin-bottom: -20px;
 
}
.filter-dropdown:hover {
  background-color: rgb(218, 55, 55);
  color: lightgray;
}</style>
  <script src="backend_func/JSfunctions.js"></script>
</body>
  