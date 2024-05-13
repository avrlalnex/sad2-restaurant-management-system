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
    if($_POST['submit'] == "Add Overtime"){
      $result = $inv_tbl->add_employee_overtime($_SESSION['emp_bonus_select'],$_POST['overtime-bonus'],$_POST['overtime-hours']);
      header("Location: payroll_overtime.php");
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
  <div class="text">Payroll - Add Overtime</div>

  <div class="header-container">
  <a href="payroll_overtime.php"><button class="add-button">Return</button></a>
  </div>
<form method="POST">
  <div class="add-item-container">
  <div class="employee-info">
<div class="sales-entry">
  <h2>Overtime Details</h2>
  <div class="input-container">
    <label for="overtime-name">Name: </label>
    <input type="text" id="overtime-name" name="overtime-name" value="<?php echo $result = $inv_tbl->get_employees_overtime_detail($_SESSION['emp_bonus_select'],'Name')?>" readonly>
    <label for="overtime-salary">Hourly Rate: </label>
    <input type="text" id="overtime-salary" name="overtime-salary" oninput=""
      value="<?php echo $result = $inv_tbl->get_employees_overtime_detail($_SESSION['emp_bonus_select'],'Hourly')?>" readonly>
    <label for="overtime-type">Overtime Type: </label>
    <select id="overtime-type" name="overtime-type" onchange="calculate_rate()">
      <option value=0.25 selected>Regular(25%)</option>
      <option value=0.3>Holiday(30%)</option>
    </select>  
    <label for="overtime-rate">Bonus per Hour: </label>
    <input type="text" id="overtime-rate" name="overtime-rate" oninput="calculate_bonus()" 
      value="<?php $oresult = $inv_tbl->get_employees_overtime_detail($_SESSION['emp_bonus_select'],'Hourly'); echo round($oresult*0.25,2);?>" >
    <label for="overtime-hours">Overtime Hours: </label>
    <input type="text" id="overtime-hours" name="overtime-hours" oninput="calculate_bonus()" value="0" >
    <label for="overtime-bonus">Overtime Total Bonus: </label>
    <input type="text" id="overtime-bonus" name="overtime-bonus" oninput="" value="0" >
  </div>
  <div class="input-container">
    <input type="submit" class="add-button" name="submit" value="Add Overtime">
  </div>
</div>
</div>
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

    function calculate_rate() {
      var salary = document.getElementById("overtime-salary").value;
      var percent = parseFloat(document.getElementById("overtime-type").value);
      rate = salary*percent;
      document.getElementById("overtime-rate").value = rate.toFixed(2);
    }

    function calculate_bonus() {
      var hours = document.getElementById("overtime-hours").value;
      var rate = parseFloat(document.getElementById("overtime-rate").value);
      bonus = hours*rate
      document.getElementById("overtime-bonus").value = bonus.toFixed(2);
    }
  </script>
</section>
</body>
</html>