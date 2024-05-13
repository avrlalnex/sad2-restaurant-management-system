<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_order.php');
  $inv_tbl = new purchase_order();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Edit"){
      header("Location: purchase_order_view_edit.php");
      exit();
    }else if($_POST['submit'] == "Cancel Order"){
      $result = $inv_tbl->delete_orderslip($_SESSION['purchase_orderView_id']);
      header("Location: purchase_orders_page.php");
      exit();
    }else if($_POST['submit'] == "Invoice Received"){
      header("Location: purchase_order_invoice.php");
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
  <div class="text">View Order Slip</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div style="margin-left:10%">
  <div class="add-item-container" style="float:left;width:30%">
    <div class="add-item">
      <input type="submit" name="submit" value="Edit" class="add-button">
      <input type="submit" name="submit" value="Cancel Order" class="add-button" onclick="return confirm('Are you sure you want to Cancel this Order?')">
      <div class="input-container">
      <label for="orderslip-num">Order Number:</label>
      <input type="text" id="orderslip-num" name="orderslip-num" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_no')?>" readonly><br>
      </div>
      <div class="input-container">
      <label for="orderslip-desc">Supplier:</label>
      <input type="text" id="orderslip-desc" name="orderslip-desc" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_desc')?>" readonly><br>
      </div>
      <div class="input-container">
      <label for="orderslip-date">Order Date:</label>
      <input type="date" id="orderslip-date"  name="orderslip-date" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_date')?>" readonly><br>
      </div>
      <div class="input-container">
      <label for="delivery-date">Delivery Date:</label>
      <input type="date" id="delivery-date"  name="delivery-date" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'delivery_date')?>" readonly><br>
      </div>
      <div class="input-container">
      <label for="orderslip-price">Total Price:</label>
      <input type="number" id="orderslip-price" oninput="check_num(this.value,this.id)" name="orderslip-price" style="length:50%;" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'total_price')?>" readonly><br>
      </div>
    </div>
  </div>
 
  <div class="inventory-table" style="float:left;width:60%">
  <table class="item-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_orderslip_items($_SESSION['purchase_orderView_id'])?>
      </tbody>
    </table>
  </div>
  </form>
  </div>
  </section>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  