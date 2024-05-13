<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/payroll.php');
  $inv_tbl = new payroll();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Archive"){
      $result = $inv_tbl->archive_payroll($_SESSION['id_payrollview']);
      header("Location: payroll.php");
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>

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
  <div class="text">Payroll</div>
    <div class="add-profile-button">
    <a href="payroll.php"><button class="add-button">Back</button></a>
    <a href="salary_edit.php"><button class="add-button">Edit</button></a>
    <form method="POST">
    <input type="submit" class="add-button" name="submit" value="Archive" onclick="return confirm('Are you sure?')">
    </form>
    <a href="payrollRep.php" target="new"><button class="add-button" button id="download-pdf-button">Download PDF</button></a>
  </div>
<div id="pay">
  <div class="salary-table">
    <h2>Salary Details</h2>
    <table class="employee-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Salary</th>
          <th>Regular Hours</th>
          <th>Bonus</th>
          <th>Overtime Hours</th>
        </tr>
      </thead>
      <tbody>
        <tr>
        <?php $inv_tbl->viewpayroll_salarydetails($_SESSION['id_payrollview']);?> 
      </tbody>
    </table>
  </div>
  
  <div class="deductions-table">
    <h2>Deductions</h2>
    <table class="employee-table">
      <thead>
        <tr>
          <th>Philhealth</th>
          <th>SSS</th>
          <th>PAGIBIG</th>
          <th>Taxes</th>
          <th>Other Deductions</th>
          <th>Total Deduction</th>
        </tr>
      </thead>
      <tbody>
        <tbody>
            <tr>
              <?php $inv_tbl->viewpayroll_deductions($_SESSION['id_payrollview']);?> 
      </tbody>
    </table>
  </div>
  
  <div class="payroll-table">
    <h2>Payroll Summary</h2>
    <table class="employee-table">
      <thead>
        <tr>
          <th>Total Salary</th>
          <th>Total Deduction</th>
          <th>Total Amount</th>
          <th>From</th>
          <th>To</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php $inv_tbl->viewpayroll_payrollsummary($_SESSION['id_payrollview']);?> 
      </tbody>
    </table>
  </div>
</div>

  <script>
    document.getElementById('download-pdf-button').addEventListener('click', function() {
      const pdf = new jsPDF();
      const element = document.getElementById('pay'); 
      pdf.fromHTML(element, 15, 15, {
        width: 180
      }, function() {
        pdf.save('salary.pdf');
      });
    });
  </script>
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
#pay .employee-table th{
 
 color: white;
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
</body>
</html>