<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/sales.php');
  $inv_tbl = new sales();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Save Changes"){
      $result = $inv_tbl->edit_receipt($_SESSION['receiptView_id'],$_POST['receiptNumber'],$_POST['custName'],$_POST['numPax'],$_POST['totAmount'],$_POST['discType'],$_POST['receiptDate'],$_POST['seniorPax'],$_POST['payPax'],$_POST['receiptTable'],$_POST['payRate']);
      $i=0;
      while($i < $_POST['orderslip-numofitems']) {
        $result = $inv_tbl->edit_receipt_product($_POST['orderitem-id_'.$i],$_POST['item-id_'.$i],$_POST['orderitem-quant_'.$i],$_POST['orderitem-price_'.$i]);
        $i++;
      }
      header("Location: sales_view_receipt.php");
      exit();
    }else if($_POST["submit"] == "Add Item"){
      $result = $inv_tbl->add_receipt_product($_SESSION['receiptView_id'],$_POST['stock-id-goods'],1,0);
    }else if($_POST["submit"] == "delete"){
      $result = $inv_tbl->delete_receipt_product($_POST['orderitemDelete_id']);
    }else if($_POST['submit'] == "Return"){
      $result = $inv_tbl->delete_receipt_product_empty($_SESSION['receiptView_id']);
      header("Location: sales_view_receipt.php");
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
  <title>Sales</title>
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
  <div class="text">Sales - Edit Receipt</div>
    <div class="add-item-container">
      <div class="employee-info">
      <div class="sales-entry">
  <a href="sales_view_receipt.php" class="return-icon"><ion-icon name="arrow-back-outline"></ion-icon></a></br>
  <h2>Edit Sales</h2>
  <input type="submit" class="add-button" name="submit" value="Save Changes" onclick="return confirm('Are you sure you want to Archive?')">
  <div class="input-container">
    <label for="receiptNumber">Receipt Number:</label>
    <input type="text" id="receiptNumber" name="receiptNumber" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"receipt number");?>">
  </div>
  <div class="input-container">
    <label for="receiptTable">Table Number:</label>
    <input type="text" id="receiptTable" name="receiptTable" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"table number");?>" oninput="check_num(this.value,this.id)">
  </div>
  <div class="input-container">
    <label for="custName">Customer:</label>
    <select id="custName" name="custName" onchange="receipt_selectCustomer(this.value)">
    <option value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"customer name");?>" selected hidden><?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"customer name");?></option>
      <?php $inv_tbl->get_receipt_customer_options();?> 
    </select>
  </div>
  <div class="input-container">
    <label for="payRate">Unlimited Rate:</label>
    <select id="payRate" name="payRate" onchange="receipt_selectRate(this.value);receipt_calculateTotal()">
      <option value=<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"product id");?> selected hidden><?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"product name");?></option>
      <?php $inv_tbl->get_sales_product_unli();?>    
    </select>
    <input type="number" id="payPax" name="payPax" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"pax price");?>" oninput="check_num(this.value,this.id);receipt_calculateTotal() readonly">
    <?php $inv_tbl->get_sales_product_unli_price();$inv_tbl->get_sales_product_addon_price()?>
  </div>
  <div class="input-container">
    <label for="numPax">Number of Pax:</label>
    <input type="number" id="numPax" name="numPax" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"pax");?>" oninput="check_paxValid(this.value);receipt_calculateTotal()">
  </div>
  <div class="input-container">
    <label for="discType">Discount Type:</label>
    <select id="discType" name="discType" onchange="receipt_selectSenior();receipt_calculateTotal()">
        <option value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"discount type");?> selected hidden><?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"discount type");?></option>
        <option value="Not Discounted">Not Discounted</option> 
        <option value="Senior">Senior</option>
        <option value="Other">Other</option>
    </select>
    <label for="seniorPax" id="seniorPaxLabel">Senior Pax</label>
    <input type="number" id="seniorPax" name="seniorPax" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"senior pax");?>" oninput="check_seniorValid(this.value);receipt_calculateTotal()">
  </div>
  <div class="input-container">
    <label for="totAmount">Total Amount:</label>
    <input type="text" id="totAmount" name="totAmount" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"total amount");?>" oninput="check_num(this.value,this.id)">
  </div>
  <div class="input-container">
    <label for="receiptDate">Date:</label>
    <input type="date" id="receiptDate" name="receiptDate" value="<?php echo $result=$inv_tbl->get_sales_receipt_detail($_SESSION['receiptView_id'],"receipt date");?>">
  </div>
</div>
</section>
<div class="add-item-container">
    <div class="input-container">
      <label for="stock-name">Add-Ons:
      <select id="stock-id-goods" name="stock-id-goods">
        <?php $inv_tbl->get_sales_product_addon();?>   
      </select>
        <input class="add-button" type="submit" name="submit" value="Add Item">
    </div>
  </div>
<div class="inventory-table">
    <table id="invoice_items" class="item-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Item Price</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_sales_receipt_addons_edit($_SESSION['receiptView_id'])?>
      </tbody>
    </table>
  </div>
</form>


<script>
  let salesData = [];
  
  function addSale() {
    const receiptNumber = document.getElementById('receiptNumber').value;
    const numPax = parseInt(document.getElementById('numPax').value);
    const amountPerPax = parseFloat(document.getElementById('amountPerPax').value);
    const totalAmount = numPax * amountPerPax;

    salesData.push({
      receiptNumber,
      numPax,
      amountPerPax,
      totalAmount
    });

    displaySalesData();

    updateTotalAmount();

    document.getElementById('salesForm').reset();
  }

  function displaySalesData() {
    const tableBody = document.getElementById('salesTableBody');

    tableBody.innerHTML = '';

    salesData.forEach((sale) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${sale.receiptNumber}</td>
        <td>${sale.numPax}</td>
        <td>${sale.amountPerPax.toFixed(2)}</td>
        <td>${sale.totalAmount.toFixed(2)}</td>
      `;
      tableBody.appendChild(row);
    });
  }

  function updateTotalAmount() {
    const totalAmountSpan = document.getElementById('totalAmount');
    const totalAmount = salesData.reduce((acc, sale) => acc + sale.totalAmount, 0);
    totalAmountSpan.textContent = totalAmount.toFixed(2);
  }

  function invoice_addnewItem() {
  var length = document.getElementById("invoice_items").rows.length-1;
  var idname = "invoiceItem_id_"+length;
  var quantname = "invoiceItem_quant_"+length;
  var unitpricename = "invoiceItemUnit_price_"+length;
  var pricename = "invoiceItem_price_"+length;
  var rowid = document.getElementById("stock-id-goods").value;
  var rowname = document.getElementById("product_name_"+rowid).value;
  var rowprice = document.getElementById("product_price_"+rowid).value;

  var table = document.getElementById("invoice_items").getElementsByTagName('tbody')[0];
  var row = table.insertRow(length);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  cell1.innerHTML = rowname+"<input type='hidden' id='"+idname+"' name='"+idname+"' value='"+rowid+"'>";
  cell2.innerHTML = "<input type='text' oninput=\"check_num_minimum(this.value,this.id);orderslip_calc_unit_total("+length+");receipt_calculateTotal()\" id='"+quantname+"' name='"+quantname+"' value='"+1+"'>";
  cell3.innerHTML = "<input type='text' oninput=\"check_num(this.value,this.id);orderslip_calc_unit_total("+length+");receipt_calculateTotal()\" id='"+unitpricename+"'name='"+unitpricename+"' value='"+rowprice+"'>";
  cell4.innerHTML = "<input type='text' oninput=\"check_num(this.value,this.id);receipt_calculateTotal()\" id='"+pricename+"'name='"+pricename+"' value='"+rowprice+"'>";
  document.getElementById("invoice-numofitems").value = length+1;
  receipt_calculateTotal();
}

function invoice_undonewItem(){
  var length = document.getElementById("invoice_items").rows.length-1;
  var table = document.getElementById("invoice_items").getElementsByTagName('tbody')[0];
  table.deleteRow(length-1);
  document.getElementById("invoice-numofitems").value = length-1;
  receipt_calculateTotal()
}

function orderslip_calc_total(){
  var numofitems = document.getElementById("invoice-numofitems").value;
  var total_price = parseFloat(document.getElementById("orderslip-price-fees").value);
  i=0;
    while(i < numofitems) {
      total_price += parseFloat(document.getElementById("invoiceItem_price_"+i).value);
      i++;
    }
  document.getElementById("orderslip-price").value = total_price;
}

function orderslip_calc_unit_total(i){
  var quant = document.getElementById("invoiceItem_quant_"+i).value;
  var unit_price = parseFloat(document.getElementById("invoiceItemUnit_price_"+i).value);
  total_price = quant*unit_price;
  document.getElementById("invoiceItem_price_"+i).value = total_price;
}

  function receipt_selectCustomerType(){
      var rowselect = document.getElementById("custType").value;
      var rownew = document.getElementById("custName");
      var rowreg = document.getElementById("custReg");
    if (rowselect=="Repeat") {
      
      rowreg.removeAttribute("hidden");
        
      rownew.setAttribute("hidden", "hidden");
    } else if(rowselect=="New"){
      
      rownew.removeAttribute("hidden");
          
      rowreg.setAttribute("hidden", "hidden");
    }
  }

  function receipt_selectCustomer($custName){
      var rownew = document.getElementById("custName");
      rownew.value =$custName;
  }

  function receipt_selectRate(rateID){
      var ratePrice = document.getElementById("product_price_"+rateID).value;
      document.getElementById("payPax").value = ratePrice;
  }

  function receipt_selectSenior(){
    var rowselect = document.getElementById("discType").value;
    var rowsenior = document.getElementById("seniorPax");
    var rowseniorlabel = document.getElementById("seniorPaxLabel");
    if(rowselect=="Senior") {
      rowsenior.value=1;
      rowsenior.removeAttribute("hidden");
      rowseniorlabel.innerHTML="Senior Pax:";
        
    }else if(rowselect=="Other") {
      rowsenior.value=0;
      rowsenior.removeAttribute("hidden");
      rowseniorlabel.innerHTML="Senior Pax:";
        
    }else{
      rowsenior.value=0;
      rowsenior.setAttribute("hidden", "hidden");
      rowseniorlabel.innerHTML="";
    }
  }

  function receipt_calculateTotal(){
      var paypax = document.getElementById("payPax").value;
      var seniorpax = document.getElementById("seniorPax").value;
      var pax = document.getElementById("numPax").value;
      var numofitems = document.getElementById("invoice-numofitems").value;
      var total_addon = 0;
      i=0;
        while(i < numofitems) {
          total_addon += parseFloat(document.getElementById("invoiceItem_price_"+i).value);
          i++;
        }
      var vat_exempt=((pax*paypax+total_addon)/pax)/1.12;
      var senior_disc=vat_exempt-(vat_exempt*0.2);
      var total = (((pax*paypax+total_addon)/pax)*(pax-seniorpax))+(senior_disc*seniorpax);
      document.getElementById("totAmount").value = total.toFixed(2);
      //document.getElementById("totAmount_calc").innerHTML = pax+"*"+paypax+" - "+seniorpax+"*"+((paypax/1.12)*0.2);
  }

  function check_seniorValid(input_val){
    var currentquant = document.getElementById("numPax").value;
    if(Number(input_val)>Number(currentquant)){
      alert("Error! Senior pax cannot be greater than total pax")
      document.getElementById("seniorPax").value=1;
    }else if(isNaN(input_val)){
      alert("Error! Numbers Values Only Please.")
      document.getElementById("seniorPax").value=1;
    }else if(0>=Number(input_val)){
      alert("Error! Invalid value!")
      document.getElementById("seniorPax").value=1;
    }
  }

  function check_paxValid(input_val){
    var currentquant = document.getElementById("seniorPax").value;
    if(Number(input_val)<Number(currentquant)){
      alert("Error! Total pax cannot be less than senior pax!")
      document.getElementById("numPax").value=currentquant;
    }else if(isNaN(input_val)){
      alert("Error! Numbers Values Only Please.")
      document.getElementById("numPax").value=1;
    }else if(0>=Number(input_val)){
      alert("Error! Invalid value!")
      document.getElementById("numPax").value=1;
    }
  }

  function check_num(input_val,input_id){
    if(isNaN(input_val)){
      alert("Error! Numbers Values Only Please.")
      document.getElementById(input_id).value = 0;
    }else if(Number(input_val)<0){
      alert("Error! Positive Values Only Please.")
      document.getElementById(input_id).value = 0;
    }
  }

  function check_num_minimum(input_val,input_id){
  if(isNaN(input_val) || input_val==0){
    alert("Error! Number Values Above Zero Only.")
    document.getElementById(input_id).value = 1;
  }
}

</script>

  <style>
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
  .input-container {
  margin-bottom: 15px;
}

.input-container label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.input-container select,
.input-container input[type="text"],
.input-container input[type="date"],
.input-container input[type="number"] {
  width: 100%;
  padding: 8px;
  border: 1px solid #ff0000; 
  border-radius: 5px;
  box-sizing: border-box;
  margin-top: 5px;
}
  @media (max-width: 420px) {
    .sidebar li .tooltip {
      display: none;
    }
  }
  .sales-entry {
  position: relative;
}



.return-icon {
  position: absolute;
  top: -30px; 
  left: -90px;
  font-size: 24px;
  color: #11101d;
  z-index: 2; 
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

  </script>
</section>
</body>
</html>