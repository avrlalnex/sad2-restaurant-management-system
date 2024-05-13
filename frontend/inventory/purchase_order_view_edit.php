<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/purchase_order.php');
  $inv_tbl = new purchase_order();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Save Changes"){
      $result = $inv_tbl->edit_orderslip($_SESSION['purchase_orderView_id'],$_POST['orderslip-num'],$_POST['orderslip-desc'],$_POST['orderslip-date'],$_POST['delivery-date'],$_POST['orderslip-price']);
      $i=0;
      while($i < $_POST['orderslip-numofitems']) {
        $result = $inv_tbl->edit_order_item($_POST['orderitem-id_'.$i],$_POST['item-id_'.$i],$_POST['orderitem-quant_'.$i],$_POST['orderitem-price_'.$i]);
        $i++;
      }
      header("Location: purchase_order_view.php");
      exit();
    }else if($_POST["submit"] == "Add Item"){
      if($_POST['stock-id-select']=='Goods'){
        $result = $inv_tbl->add_order_item($_SESSION['purchase_orderView_id'],$_POST['stock-id-goods'],0,0);
      }else if($_POST['stock-id-select']=='Supply'){
        $result = $inv_tbl->add_order_item($_SESSION['purchase_orderView_id'],$_POST['stock-id-supply'],0,0);
      }
    }else if($_POST["submit"] == "delete"){
      $result = $inv_tbl->delete_order_item($_POST['orderitemDelete_id']);
    }else if($_POST['submit'] == "Return"){
      $result = $inv_tbl->delete_order_item_empty($_SESSION['purchase_orderView_id']);
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
  <div class="text">Edit Order Slip</div>
  <div class="header-container">
    <input type="submit" name="submit" value="Return" class="add-button">
  </div>
  <div style="margin-left:10%">
  <div class="add-item-container" style="float:left;width:30%">
    <div class="add-item">
      <input type="submit" name="submit" value="Save Changes" class="add-button" onclick="return confirm('Save Changes?')">
      <div class="input-container">
      <label for="orderslip-num">Order Number:</label>
      <input type="text" id="orderslip-num" name="orderslip-num" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_no')?>"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-desc">Supplier:</label>
      <input type="text" id="orderslip-desc" name="orderslip-desc" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_desc')?>"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-date">Order Date:</label>
      <input type="date" id="orderslip-date"  name="orderslip-date" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'order_date')?>"><br>
      </div>
      <div class="input-container">
      <label for="delivery-date">Delivery Date:</label>
      <input type="date" id="delivery-date"  name="delivery-date" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'delivery_date')?>"><br>
      </div>
      <div class="input-container">
      <label for="orderslip-price">Total Price:</label>
      <input type="text" id="orderslip-price" oninput="check_num(this.value,this.id)" name="orderslip-price" style="length:50%;" value="<?php echo $info = $inv_tbl->get_orderslip_info($_SESSION['purchase_orderView_id'],'total_price')?>"><br>
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
    <input type="submit" name="submit" value="Add Item" class="add-button">
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
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_orderslip_items_edit($_SESSION['purchase_orderView_id'])?>
      </tbody>
    </table>
  </div>
  </form>
  </div>
  </section>
  <script src="backend_func/JSfunctions.js"></script>

  
</body>
  