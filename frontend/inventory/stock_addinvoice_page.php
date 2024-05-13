<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['invoice_search'] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Submit"){
      $result = $inv_tbl->add_invoice($_POST['invoice-num'],$_POST['total-price'],$_POST['invoice-desc'],$_POST['invoice-date']);
      $result = $inv_tbl->get_lastinvoice();
      $_SESSION['invoice-id'] = $result;
      $i=0;
      while($i < $_POST['invoice-numofitems']) {
        $result = $inv_tbl->add_invoice_stockin($_SESSION['invoice-id'],$_POST['invoiceItem_id_'.$i],$_POST['invoiceItem_quant_'.$i],$_POST['invoiceItem_price_'.$i]);
        $i++;
      }
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
  <div class="text">Add Invoice</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div class="add-item-container">
  <div class="add-item">
    <input type="submit" name="submit" value="Submit" class="add-button">
    <div class="input-container">
    <label for="invoice-num">Invoice Number:</label>
    <input type="text" id="invoice-num" name="invoice-num"><br>
    </div>
    <div class="input-container">
    <label for="total-price">Total Price:</label>
    <input type="number" id="total-price" oninput="check_num(this.value,this.id)" name="total-price" style="length:50%;"><br>
    </div>
    <div class="input-container">
    <label for="invoice-desc">Supplier:</label>
    <input type="text" id="invoice-desc" name="invoice-desc"><br>
    </div>
    <div class="input-container">
    <label for="stock-quant">Invoice Date:</label>
    <input type="date" id="input_value"  name="invoice-date" value="<?php $currentDate = date('Y-m-d'); echo $currentDate?>"><br>
  </div>

  <div class="input-container">
    <label for="stock-name">Add Item: (Select Item and Fill in on the Table Below)
      <select id="stock-id-select" name="stock-id-select" onchange="invoice_selectItemType()">
        <option value="Goods">Goods</option>
        <option value="Supply">Supply</option>  
      </select>
    </label>
    <select id="stock-id-goods" name="stock-id-goods">
      <?php $inv_tbl->show_items('Goods');?>   
    </select>
    <select id="stock-id-supply" name="stock-id-supply" hidden>
      <?php $inv_tbl->show_items('Supply');?>   
    </select><br>
  </div>
    <input class="add-button" type="button" name="submit" value="Add Item" onclick='invoice_addnewItem()'>
    <input class="add-button" type="button" name="submit" value="Remove Last Item" onclick='invoice_undonewItem()'>
    <input type="hidden" id="invoice-numofitems" name="invoice-numofitems" value=0 readonly><br>
  </div>
</section>
  <div class="inventory-table">
  <table class="item-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Add Stock Quantity</th>
          <th>Stock Price</th>
        </tr>
      </thead>
      <tbody>
           
      </tbody>
    </table>

  </div>
  </form>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  