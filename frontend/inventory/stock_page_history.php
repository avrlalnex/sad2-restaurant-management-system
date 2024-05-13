<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['stock_search'] = "";
  $_SESSION['stock_filter'] = "Goods";
  $_SESSION['stock_filter_date_floor'] ="";
  $_SESSION['stock_filter_date_ceiling'] ="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Search"){
      $_SESSION['stock_search'] = $_POST['search_input'];
      $_SESSION['stock_filter'] = $_POST['filter_input'];
      $_SESSION['stock_filter_date_floor'] = $_POST['date_input_floor'];
      $_SESSION['stock_filter_date_ceiling'] = $_POST['date_input_ceiling'];
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
  <div class="text">Item Stock Comparison</div>

  <div class="header-container">
    <a href="stock_page.php"><button class="add-btn">Return</button></a>
    <div class="search-bar">
      <form method="POST">
      <label for="date_input_floor">Floor:</label>
      <input type="date" id="date_input_floor" name="date_input_floor">
      <label for="date_input_ceiling">Ceiling:</label>
      <input type="date" id="date_input_ceiling" name="date_input_ceiling">
      <select id="filterInput" name="filter_input">
        <option value="<?php echo $_SESSION['stock_filter']?>" selected hidden><?php echo $_SESSION['stock_filter']?></option> 
        <option value="Goods">Goods</option>
        <option value="Supply">Supply</option>
      </select>
      <input type="text" id="searchInput" name="search_input" placeholder="Search Item Name">
      <input type="submit" name="submit" value="Search" class="search-button">
      </form>
    </div>
  </div>

  <div class="inventory-table">
    <table class="item-table" style="float: left; width: 45%">
      <thead>
        <tr>
          <th colspan="4">Stock IN</th>
        </tr>
        <tr>
          <th>Receiving Report No.</th>
          <th>Item Name</th>
          <th>Stock Quantity</th>
          <th>Stock Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_stocks_history($_SESSION['stock_search'],$_SESSION['stock_filter'],'STOCK IN',$_SESSION['stock_filter_date_floor'],$_SESSION['stock_filter_date_ceiling']);?>       
      </tbody>
    </table>
    <table class="item-table" style="float: left; width: 45%; margin-left: 5px">
      <thead>
        <tr>
          <th colspan="4">Stock OUT</th>
        </tr>
        <tr>
          <th>Form Description</th>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Stock Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_stocks_history($_SESSION['stock_search'],$_SESSION['stock_filter'],'STOCK OUT',$_SESSION['stock_filter_date_floor'],$_SESSION['stock_filter_date_ceiling']);?>       
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

.filter-dropdown:hover {
  background-color: rgb(218, 55, 55);
  color: lightgray;
}</style>
  <script src="backend_func/JSfunctions.js"></script>
</body>
  