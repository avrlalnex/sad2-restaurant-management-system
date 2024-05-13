<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  $_SESSION['stockoutform_search'] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == 'Add New Form'){
      $samedateresult = $inv_tbl->get_is_samedate_dailystockout_exists($_POST['stockout-date']);
      if($_POST['stockout-desc'] == 'Daily Stock-Out' && $samedateresult == true){
        $_SESSION['stockout_samedate_alert'] = true;
      }else{
        $result = $inv_tbl->add_stockoutform($_POST['stockout-reporter'],$_POST['stockout-desc'],$_POST['stockout-date']);
        $result = $inv_tbl->get_laststockoutform();
        $_SESSION['stockoutform-id'] = $result;
        $i=0;
        while($i < $_POST['Goods_stockoutform-numofitems']) {
          $result = $inv_tbl->add_form_stockout($_SESSION['stockoutform-id'],$_POST['Goods_stock-id_'.$i],$_POST['Goods_stock-quant_'.$i]);
          $i++;
        }
        $j=0;
        while($j < $_POST['Supply_stockoutform-numofitems']) {
          $result = $inv_tbl->add_form_stockout($_SESSION['stockoutform-id'],$_POST['Supply_stock-id_'.$j],$_POST['Supply_stock-quant_'.$j]);
          $j++;
        }
        header("Location: stock_stockout_forms.php");
        exit();
      }  
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
  <title> Stockout Forms </title>
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
  <img src onerror="samedate_alert(<?php $istrue = $_SESSION['stockout_samedate_alert'];echo $istrue;$_SESSION['stockout_samedate_alert']=false;?>)">
  <div class="text">Stock-OUT Forms</div>
  
  <form method="POST">
  <div class="add-item-container">
  <div class="add-item">

    <div class="input-container">
    <a href="stock_stockout_forms.php" class="return-icon"><ion-icon name="arrow-back-outline"></ion-icon></a> 
    <label for="stockout-reporter">Stock-OUT Reporter:</label>
    <select id="stockout-reporter" name="stockout-reporter">
      <?php $inv_tbl->show_employees();?>
    </select><br>
    </div>
    <div class="input-container">
    <label for="stockout-desc">Description:</label>
    <select id="stock-desc-select" name="stock-desc-select" onchange="stockout_selectdesc()">
      <option value="Daily Stock-Out" selected>Daily Stock-Out</option>
      <option value="Other">Other:</option>  
    <input type="text" id="stockout-desc" name="stockout-desc" value="Daily Stock-Out" hidden><br>
    </div>
    <div class="input-container">
    <label for="stockout-date">Date:</label>
    <input type="date" id="stockout-date"  name="stockout-date" value="<?php $currentDate = date('Y-m-d'); echo $currentDate?>"><br>
    <input class="add-button" type="submit" name="submit" value="Add New Form">
    </div>
    <input type="hidden" id="Goods_stockoutform-numofitems" name="Goods_stockoutform-numofitems" value='<?php $inv_tbl->get_goodsstockoutinv_maxnum("Goods");?>' readonly><br>
    <input type="hidden" id="Supply_stockoutform-numofitems" name="Supply_stockoutform-numofitems" value='<?php $inv_tbl->get_goodsstockoutinv_maxnum("Supply");?>' readonly><br>
  </div>
  </div>
  <div class="inventory-table">
  <table class="item-table">
  <div class="add-item">
    <select id="stock-id-select" name="stock-id-select" onchange="invoice_selectItemType()">
      <option value="Goods">Goods</option>
      <option value="Supply">Supply</option>  
    </select>
  <table class="item-table">
      <thead>
        <tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>New Quantity</th>
        </tr>
      </thead>
      <tbody id="stockout_goods_inv">
        <?php $inv_tbl->show_goodsstockoutinv('Goods');?>      
      </tbody>
      <tbody id="stockout_supply_inv" hidden>
        <?php $inv_tbl->show_goodsstockoutinv('Supply');?>      
      </tbody>
    </table>
  </div>
  </form>
  <style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
.return-icon {
  font-size: 30px; 
  margin-left: -90px; 
}
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 78px;
  background: #11101d;
  padding: 6px 14px;
  z-index: 99;
  transition: all 0.5s ease;
}
.sidebar.open {
  width: 250px;
}
.sidebar .logo-details {
  height: 60px;
  display: flex;
  align-items: center;
  position: relative;
}
.sidebar .logo-details .icon {
  opacity: 0;
  transition: all 0.5s ease;
}
.sidebar .logo-details .logo_name {
  color: #fff;
  font-size: 15px;
  font-weight: 600;
  opacity: 0;
  transition: all 0.5s ease;
}
.sidebar.open .logo-details .icon,
.sidebar.open .logo-details .logo_name {
  opacity: 1;
}
.sidebar .logo-details #btn {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  font-size: 22px;
  transition: all 0.4s ease;
  font-size: 23px;
  text-align: center;
  cursor: pointer;
  transition: all 0.5s ease;
}
.sidebar.open .logo-details #btn {
  text-align: right;
}
.sidebar i {
  color: #fff;
  height: 60px;
  min-width: 50px;
  font-size: 28px;
  text-align: center;
  line-height: 60px;
}
.sidebar .nav-list {
  margin-top: 20px;
  height: 100%;
}
.sidebar li {
  position: relative;
  margin: 8px 0;
  list-style: none;
}
.sidebar li .tooltip {
  position: absolute;
  top: -20px;
  left: calc(100% + 15px);
  z-index: 3;
  background: #fff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 15px;
  font-weight: 400;
  opacity: 0;
  white-space: nowrap;
  pointer-events: none;
  transition: 0s;
}
.sidebar li:hover .tooltip {
  opacity: 1;
  pointer-events: auto;
  transition: all 0.4s ease;
  top: 50%;
  transform: translateY(-50%);
}
.sidebar.open li .tooltip {
  display: none;
}
.sidebar input {
  font-size: 15px;
  color: #fff;
  font-weight: 400;
  outline: none;
  height: 50px;
  width: 100%;
  width: 50px;
  border: none;
  border-radius: 12px;
  transition: all 0.5s ease;
  background: #1d1b31;
}
.sidebar.open input {
  padding: 0 20px 0 50px;
  width: 100%;
}
.sidebar .bx-search {
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  font-size: 22px;
  background: #1d1b31;
  color: #fff;
}
.sidebar.open .bx-search:hover {
  background: #1d1b31;
  color: #fff;
}
.sidebar .bx-search:hover {
  background: #fff;
  color: #11101d;
}
.sidebar li a {
  display: flex;
  height: 100%;
  width: 100%;
  border-radius: 12px;
  align-items: center;
  text-decoration: none;
  transition: all 0.4s ease;
  background: #11101d;
}
.sidebar li a:hover {
  background: #fff;
}
.sidebar li a .links_name {
  color: #fff;
  font-size: 15px;
  font-weight: 400;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: 0.4s;
}
.sidebar.open li a .links_name {
  opacity: 1;
  pointer-events: auto;
}
.sidebar li a:hover .links_name,
.sidebar li a:hover i {
  transition: all 0.5s ease;
  color: #11101d;
}
.sidebar li i {
  height: 50px;
  line-height: 50px;
  font-size: 18px;
  border-radius: 12px;
}
.sidebar li.profile {
  position: fixed;
  height: 60px;
  width: 78px;
  left: 0;
  bottom: -8px;
  padding: 10px 14px;
  background: #1d1b31;
  transition: all 0.5s ease;
  overflow: hidden;
}
.sidebar.open li.profile {
  width: 250px;
}
.sidebar li .profile-details {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
}
.sidebar li img {
  height: 45px;
  width: 45px;
  object-fit: cover;
  border-radius: 6px;
  margin-right: 10px;
}
.sidebar li.profile .name,
.sidebar li.profile .job {
  font-size: 15px;
  font-weight: 400;
  color: #fff;
  white-space: nowrap;
}
.sidebar li.profile .job {
  font-size: 12px;
}
.sidebar .profile #log_out {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  background: #1d1b31;
  width: 100%;
  height: 60px;
  line-height: 60px;
  border-radius: 0px;
  transition: all 0.5s ease;
}
.sidebar.open .profile #log_out {
  width: 50px;
  background: none;
}
.home-section {
  position: relative;
  background: rgb(239, 205, 133);;
  min-height: 100vh;
  top: 0;
  left: 78px;
  width: calc(100% - 78px);
  transition: all 0.5s ease;
  z-index: 2;
}
.sidebar.open ~ .home-section {
  left: 250px;
  width: calc(100% - 250px);
}
.home-section .text {
  display: inline-block;
  color: #11101d;
  font-size: 25px;
  font-weight: 500;
  margin: 18px;
}
.header-container {
  display: flex;
  justify-content: flex-start; 
  align-items: center;
  padding: 10px;
}

.add-button, .add-button:nth-child(2), .add-button:nth-child(3) {
  margin-left: 10px;
}

.search-bar {
  display: flex;
  align-items: center;
  margin-left: auto; 
  margin-right: 10px;
}

.search-button {
  margin-left: 10px;
}
#searchInput {
  width: 200px;
  padding: 8px;
  margin-left: 10px;
  border: 1px solid #ccc;
  border-radius: 30px;
}
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
}

.search-button {
  background-color: rgb(196, 71, 71);
  border: none;
  color: white;
  cursor: pointer;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 14px;
  padding: 8px 20px;
  border-radius: 30px;
  transition: background-color 0.3s ease, color 0.3s ease;
}
.reorder-popup {
  top: 0;
  position: absolute;
  right: 10px;
  background-color: #f44336;
  color: #fff;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  z-index: 999;
}
@media (max-width: 420px) {
  .sidebar li .tooltip {
    display: none;
  }
}
</style>
<script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  let searchBtn = document.querySelector(".bx-search");
  closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });
  searchBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange(); 
  });
  function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
      closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); 
    } else {
      closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); 
    }
  }

  function check_stockoutvalid(input_val,input_id,currentquant){
  if(Number(input_val)>Number(currentquant)){
    alert("Error! Stock out quantity cannot be greater than current quantity!")
    document.getElementById(input_id).value = currentquant;
  }else if(isNaN(input_val)){
    alert("Error! Numbers Values Only Please!")
    document.getElementById(input_id).value = currentquant;
  }
  }

  function invoice_selectItemType(){
  var rowselect = document.getElementById("stock-id-select").value;
  var rowgoods = document.getElementById("stockout_goods_inv");
  var rowsupply = document.getElementById("stockout_supply_inv");
    if (rowselect=="Goods") {
        
        rowgoods.removeAttribute("hidden");
        
        rowsupply.setAttribute("hidden", "hidden");
    } else if(rowselect=="Supply"){
        
          rowsupply.removeAttribute("hidden");
        
        rowgoods.setAttribute("hidden", "hidden");
    }
  }

  function stockout_selectdesc(){
    var descselect = document.getElementById("stock-desc-select").value;
    var desc = document.getElementById("stockout-desc");
    if (descselect=="Daily Stock-Out") {
        desc.value = "Daily Stock-Out";
        desc.setAttribute("hidden", "hidden");
    } else {
        desc.value = "";
        desc.removeAttribute("hidden");
    }
  }

  function samedate_alert(istrue){
    if(istrue==true){
      alert("Daily Stock-Out for that date already exists!")
    }else if(istrue==1){
      alert("Daily Stock-Out for that date already exists!")
    }
  }

</script>
</section>
</body>
</html>