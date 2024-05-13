<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/payroll.php');
  $inv_tbl = new payroll();
  $result="";
  $_SESSION['payrep_title'] = "";
  $_SESSION['rep_floor_dateInput'] = date('Y-m-01');
  $_SESSION['rep_ceiling_dateInput'] = date('Y-m-t');
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Set Date"){
      $_SESSION['payrep_title'] = $_POST['reportTitle'];
      $_SESSION['rep_floor_dateInput'] = $_POST['rep-floorDate'];
      $_SESSION['rep_ceiling_dateInput'] = $_POST['rep-ceilingDate'];
    }else if($_POST['submit'] == "Submit"){
      $_SESSION['payrep_title'] = $_POST['reportTitle'];
      $_SESSION['rep_floor_dateInput'] = $_POST['rep-floorDate'];
      $_SESSION['rep_ceiling_dateInput'] = $_POST['rep-ceilingDate'];
      $samedateresult = $inv_tbl->get_is_samedate($_SESSION['rep_floor_dateInput'],$_SESSION['rep_ceiling_dateInput']);
      if($samedateresult == true){
        $_SESSION['stockout_samedate_alert'] = true;
      }else{
        $result = $inv_tbl->add_payroll_report($_POST['reportTitle'],$_POST['totAmount'],$_POST['rep-floorDate'],$_POST['rep-ceilingDate']);
        header("Location: payroll_reports.php");
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
  <title>Payroll</title>
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
  <div class="text">Payroll</div>

<form method="POST">
  <div class="add-item-container">
  <div class="employee-info">
  <a href="payroll_reports.php" class="return-icon"><ion-icon name="arrow-back-outline"></ion-icon></a> 
<div class="sales-entry">
  <h2>Enter Payroll Report</h2>
  <div class="input-container">
    <label for="reportTitle">Report Title:</label>
    <input type="text" id="reportTitle" name="reportTitle" value="<?php echo $_SESSION['payrep_title']?>">
  </div>
  <div class="input-container">
    <label for="totAmount">Total Amount:</label>
    <input type="text" id="totAmount_display" name="totAmount_display" value="<?php echo $inv_tbl->get_payroll_report_totalAmt($_SESSION['rep_floor_dateInput'],$_SESSION['rep_ceiling_dateInput'],1);?>" readonly>
    <input type="hidden" id="totAmount" name="totAmount" value="<?php echo $inv_tbl->get_payroll_report_totalAmt($_SESSION['rep_floor_dateInput'],$_SESSION['rep_ceiling_dateInput'],0);?>" readonly>
  </div>
  <div class="input-container">
    <label>Dates: <input type="submit" class="add-button" name="submit" value="Set Date"></label>
    <label for="rep-floorDate">From: </label>
    <input type="date" id="rep-floorDate" name="rep-floorDate" onchange="report_checkInclusiveDate_valid()" value="<?php echo $_SESSION['rep_floor_dateInput']?>">
    <label for="rep-ceilingDate">To: </label>
    <input type="date" id="rep-ceilingDate" name="rep-ceilingDate" onchange="report_checkInclusiveDate_valid()" value="<?php echo $_SESSION['rep_ceiling_dateInput']?>">
  </div>
  <div class="input-container">
    <input type="submit" class="add-button" name="submit" value="Submit">
  </div>
</div>
</div>
</div>
<div class="sales-table"><div class="inventory-table">
  <table class="item-table">
      <thead>
        <tr>
          <th>EMPLOYEE NAME</th>
          <th>TOTAL SALARY</th>
          <th>TOTAL DEDUCTION</th>
          <th>TOTAL AMOUNT</th>
          <th>FROM</th>
          <th>TO</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_report_payrolls($_SESSION['rep_floor_dateInput'],$_SESSION['rep_ceiling_dateInput']);?>    
      </tbody>
  </table>
</div>
</form>
</section>

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

  function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }

  function report_checkInclusiveDate_valid(){
    var floordate = document.getElementById("rep-floorDate");
    var ceilingdate = document.getElementById("rep-ceilingDate");
    if(floordate.value > ceilingdate.value){
      var date = new Date(), y = date.getFullYear(), m = date.getMonth();
      var fd = new Date(y, m, 1);
      var ld = new Date(y, m + 1, 0);
      floordate.value = formatDate(fd);
      ceilingdate.value = formatDate(ld);
      alert("Invalid span of dates! Make sure the start date does not exceed the end date!");
    }
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

  function samedate_alert(istrue){
      if(istrue==true){
        alert("A Report for these dates already exists!")
      }else if(istrue==1){
        alert("A Report for these dates already exists!")
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