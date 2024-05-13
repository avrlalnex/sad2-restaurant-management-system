<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/stock.php');
  $inv_tbl = new stock();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == 'Save Edit'){
      $result = $inv_tbl->edit_stockoutform($_SESSION['stockoutFormview_id'],$_POST['stockout-reporter'],$_POST['stockout-desc'],$_POST['stockout-date']);
      $i=0;
      while($i < $_POST['stockoutform-numofitems']) {
        $result = $inv_tbl->edit_form_stockout($_POST['stockout-iid_'.$i],$_POST['item-id_'.$i],$_POST['stockout-iquant_'.$i]);
        $i++;
      }
      $result = $inv_tbl->delete_emptystockout();
      header("Location: stock_stockout_forms_view.php");
      exit();
    }else if($_POST['submit'] == 'Add Item'){
      if($_POST['stock-id-select']=='Goods'){
        $result = $inv_tbl->add_emptystockout($_SESSION['stockoutFormview_id'],$_POST['stock-id-goods']);
      }else if($_POST['stock-id-select']=='Supply'){
        $result = $inv_tbl->add_emptystockout($_SESSION['stockoutFormview_id'],$_POST['stock-id-supply']);
      }
    }else if($_POST['submit'] == 'Return'){
      $result = $inv_tbl->delete_emptystockout();
      header("Location: stock_stockout_forms_view.php");
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
  <div class="text">Stock-OUT Forms</div>
  <div class="header-container">
  <form method="POST">
  <input class="add-button" type="submit" name="submit" value="Return"></a>
  </form>
  </div>
  
  <form method="POST" onSubmit="return confirm('Are you sure?');">
  <div class="add-item-container">
  <div class="add-item">
    <input class="add-button" type="submit" name="submit" value="Save Edit">
    <?php $inv_tbl->show_stockoutform_edit($_SESSION['stockoutFormview_id']);?>
    <div class="input-container">
      <label for="stock-name">Add Item:
        <select id="stock-id-select" name="stock-id-select" onchange="invoice_selectItemType()">
          <option value="Goods">Goods</option>
          <option value="Supply">Supply</option>  
        </select>
      </label>
      <select id="stock-id-goods" name="stock-id-goods">
        <?php $inv_tbl->show_items_editstockout('Goods',$_SESSION['stockoutFormview_id']);?>   
      </select>
      <select id="stock-id-supply" name="stock-id-supply" hidden>
        <?php $inv_tbl->show_items_editstockout('Supply',$_SESSION['stockoutFormview_id']);?>   
      </select>
      <input class="add-button" type="submit" name="submit" value="Add Item">
      <br>(Stock-OUT with 0 Quantity will be Deleted)
    </div>
    <input type="hidden" id="stockoutform-numofitems" name="stockoutform-numofitems" value='<?php $inv_tbl->get_stockoutform_stocks_maxnum($_SESSION['stockoutFormview_id']);?>' readonly>
    <?php $inv_tbl->get_inventory_quant();?><br>
  </div>
  </div>

  <div class="add-item">
  <table class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_stockoutform_stocks_edit($_SESSION['stockoutFormview_id']);?>       
      </tbody>
    </table>
  </div>
  </form>
  <style>
  /* Google Font Link */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
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

  function stockout_edit_onchange(i,select_value,select_id){
    document.getElementById("stockout-iquant_"+i).value = 0;
    j=0;
      while(j < document.getElementById("stockoutform-numofitems").value) {
        if(document.getElementById("item-id_"+j).value == select_value && i!=j){
          alert("Error! Can't have two of the same item in stock out!")
          document.getElementById(select_id).value = document.getElementById("selected_item_"+i).value;
          document.getElementById("stockout-iquant_"+i).value = document.getElementById("old-iquant_"+i).value;
          break;
        }
        j++;
      }
  }

  function stockout_edit_onchangequant(input_id,old_item_id,item_id,item_quant,old_stock_quant,input_quant,i){
    if(old_item_id == item_id && 0 > (item_quant-(input_quant-old_stock_quant))){
      alert("Error! Stock out quantity will produce negative inventory!")
      document.getElementById('item-id_'+i).value = document.getElementById("selected_item_"+i).value;
      document.getElementById("stockout-iquant_"+i).value = document.getElementById("old-iquant_"+i).value;
    }else if(old_item_id != item_id && input_quant > item_quant){
      alert("Error! Stock out quantity will produce negative inventory!")
      document.getElementById('item-id_'+i).value = document.getElementById("selected_item_"+i).value;
      document.getElementById("stockout-iquant_"+i).value = document.getElementById("old-iquant_"+i).value;
    }
  }

  function get_item_id(i){
    item_id = document.getElementById("item-id_"+i).value;
    return item_id;
  }

  function get_item_quant(item_id){
    item_quant = document.getElementById("inventory_quant_"+item_id).value;
    return item_quant;
  }

  function invoice_selectItemType(){
  var rowselect = document.getElementById("stock-id-select").value;
  var rowgoods = document.getElementById("stock-id-goods");
  var rowsupply = document.getElementById("stock-id-supply");
  if (rowselect=="Goods") {
    
    rowgoods.removeAttribute("hidden");
    
    rowsupply.setAttribute("hidden", "hidden");
  }else if(rowselect=="Supply"){
      
    rowsupply.removeAttribute("hidden");
      
    rowgoods.setAttribute("hidden", "hidden");
  }
}
</script>
</section>
</body>
</html>