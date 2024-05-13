<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_invoice.php');
  include('backend_func/purchase_order.php');
  $inv_tbl = new purchase_invoice();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Add Invoice"){
      $result = $inv_tbl->add_invoice($_SESSION['purchase_orderView_id'],$_POST['orderslip-num'],$_POST['delivery-date']);
      $result = $inv_tbl->get_last_invoice();
      $_SESSION['add-invoice-id'] = $result;
      $i=0;
      while($i < $_POST['orderslip-numofitems']) {
        $result = $inv_tbl->add_stock_in($_SESSION['add-invoice-id'],$_POST['orderitem-id_'.$i],$_POST['invoice-expiry_'.$i]);
        $i++;
      }
      header("Location: stock_invoice_page.php");
      exit();
    }else if($_POST['submit'] == "Return"){
      header("Location: purchase_order_view.php");
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
  <div style="margin-left:10%">
  <div class="add-item-container" style="float:left;width:20%">
    <div class="add-item">
      <input type="submit" name="submit" value="Add Invoice" class="add-button" onclick="return confirm('Add Invoice?')">
      <div class="input-container">
      <label for="orderslip-num">Invoice Number:</label>
      <input type="text" id="orderslip-num" name="orderslip-num" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_no')?>"><br>
      </div>
      <div class="input-container">
      <label for="delivery-date">Delivery Date:</label>
      <input type="date" id="delivery-date"  name="delivery-date" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'delivery_date')?>"><br>
      </div>
    </div>
  </div>
  <div class="inventory-table" style="float:left">
  <table class="item-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Expiry Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_orderslip_items_invoice($_SESSION['purchase_orderView_id'])?>
      </tbody>
    </table>
  </div>
  </form>
  </div>
  </section>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  