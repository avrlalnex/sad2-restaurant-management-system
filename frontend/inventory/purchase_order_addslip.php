<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_order.php');
  $inv_tbl = new purchase_order();
  $result="";
  $_SESSION['invoice_search'] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Submit"){
      $result = $inv_tbl->add_orderslip($_POST['orderslip-num'],$_POST['orderslip-desc'],$_POST['orderslip-date'],$_POST['delivery-date'],$_POST['orderslip-price']);
      $result = $inv_tbl->get_last_orderslip();
      $_SESSION['invoice-id'] = $result;
      $i=0;
      while($i < $_POST['invoice-numofitems']) {
        $result = $inv_tbl->add_order_item($_SESSION['invoice-id'],$_POST['invoiceItem_id_'.$i],$_POST['invoiceItem_quant_'.$i],$_POST['invoiceItem_price_'.$i]);
        $i++;
      }
      header("Location: purchase_orders_page.php");
      exit();
    }else if($_POST['submit'] == "Return"){
      header("Location: purchase_orders_page.php");
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
  <div class="text">Add Order Slip</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div style="margin-left:10%">
  <div class="add-item-container" style="float:left;width:30%">
    <div class="add-item">
      <input type="submit" name="submit" value="Submit" class="add-button">
      <div class="input-container">
      <label for="orderslip-num">Order Number:</label>
      <input type="text" id="orderslip-num" name="orderslip-num"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-desc">Supplier:</label>
      <input type="text" id="orderslip-desc" name="orderslip-desc"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-date">Order Date:</label>
      <input type="date" id="orderslip-date"  name="orderslip-date" value="<?php $currentDate = date('Y-m-d'); echo $currentDate?>"><br>
      </div>
      <div class="input-container">
      <label for="delivery-date">Delivery Date:</label>
      <input type="date" id="delivery-date"  name="delivery-date" value="<?php $currentDate = date('Y-m-d'); echo $currentDate?>"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-price">Other Fees:</label>
      <input type="text" id="orderslip-price-fees" oninput="check_num(this.value,this.id);orderslip_calc_total()" name="orderslip-price-fees" value=0 style="length:50%;"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-price">Total Price: (Items Total + Fees)</label>
      <input type="text" id="orderslip-price" oninput="check_num(this.value,this.id)" name="orderslip-price" value=0 style="length:50%;"><br>
      </div>
    </div>
  </div>
  <div class="add-item-container" style="float:left;margin-left:20px;width:20%">
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
    <input class="add-button" type="button" name="submit" value="Add Item" onclick='invoice_addnewItem()'>
    <input class="add-button" type="button" name="submit" value="Remove Last Item" onclick='invoice_undonewItem()'>
    <input type="hidden" id="invoice-numofitems" name="invoice-numofitems" value=0 readonly><br>
    </div>
    
  </div>
  <div class="inventory-table" style="float:left;width:60%">
  <table class="item-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Unit Price</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
           
      </tbody>
    </table>
  </div>
  </form>
  </div>
  </section>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  