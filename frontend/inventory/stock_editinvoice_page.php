<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['invoice_search'] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Save Changes"){
      $result = $inv_tbl->edit_invoice($_SESSION['invoiceView_id'],$_POST['invoice-num'],$_POST['total-price'],$_POST['invoice-desc'],$_POST['invoice-date']);
      $i=0;
      while($i < $_POST['invoice-numofitems']) {
        $result = $inv_tbl->edit_invoice_stockin($_POST['stock-id_'.$i],$_POST['item-id_'.$i],$_POST['stock-quant_'.$i],$_POST['stock-price_'.$i]);
        $i++;
      }
      header("Location: stock_invoice_page.php");
      exit();
    }else if($_POST["submit"] == "Add Item"){
      $result = $inv_tbl->add_invoice_stockin($_SESSION['invoiceView_id'],$_POST['stock-id'],0,0);
    }else if($_POST["submit"] == "delete"){
      $result = $inv_tbl->delete_invoice_stockin($_POST['invoiceDelete_id']);
    }if($_POST["submit"] == "Return"){
      header("Location: stock_viewinvoice_page.php");
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
  <div class="text">Edit Invoice</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div class="add-item-container">
  <div class="add-item">  
    <input type="submit" name="submit" value="Save Changes" class="add-button">
    <?php $inv_tbl->show_invoice_edit($_SESSION['invoiceView_id']);?>

  <div class="input-container">
    <label for="stock-name">Item Name:</label>
    <select id="stock-id" name="stock-id">
      <?php $inv_tbl->show_items('Goods');?>   
    </select><br>
    <input class="add-button" type="submit" name="submit" value="Add Item">
    <input type="hidden" id="invoice-numofitems" name="invoice-numofitems" value='<?php $inv_tbl->get_invoice_stocks_NumofItems($_SESSION['invoiceView_id']);?>' readonly><br>
  </div>
  </div>
  </div>
  <div class="inventory-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Stock ID</th>
          <th>Item Name</th>
          <th>Add Stock Quantity</th>
          <th>Stock Price</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_invoice_stocks_edit($_SESSION['invoiceView_id']);?>
      </tbody>
    </table>

  </div>
  </form>
  <script src="backend_func/JSfunctions.js"></script>
</body>
  