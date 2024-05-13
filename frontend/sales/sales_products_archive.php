<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/sales.php');
  $inv_tbl = new sales();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Save Changes"){
      $i=0;
      while($i < $_POST['emp_max']) {
        #$result = $inv_tbl->edit_employee_otherdeduc($_POST['emp_id_'.$i],$_POST['emp_total_deduc_'.$i],$_POST['emp_deduc_per_sal_'.$i]);
        $i++;
      }
      header("Location: sales.php");
      exit();
    }else if($_POST['submit'] == "Un-Archive"){
      $result = $inv_tbl->unarchive_product($_POST['product_id_archive']);
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
  <title> Sales </title>
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
  <div class="text">Sales - Products</div>
    <div class="add-profile-button">
    <a href="sales_products.php"><button class="add-button">Return</button></a>
    
    <!-- <div class="search-bar">
      <form method="POST">
      <input type="text" id="searchInput" name="search_input" placeholder="Search Employee">
      <input type="submit" name="submit" value="Search" class="search-button">
      </form>
    </div> -->
  </div>
  <form method="POST">
  <div class="inventory-table">
    <table class="item-table" style="width:45%;float:left">
    <thead>
      <tr>
        <th colspan="3">Unlimited</th>
      </tr>
      <tr>
        <th>Name</th>
        <th>Price per Pax</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $inv_tbl->show_sales_products_archive(0);?> 
    </tbody>
  </table>
  <table class="item-table" style="width:45%;float:left;margin-left:5%">
    <thead>
      <tr>
        <th colspan="3">Add-Ons</th>
      </tr>
      <tr>
        <th>Name</th>
        <th>Item Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $inv_tbl->show_sales_products_archive(1);?> 
    </tbody>
  </table>
  </form>
  <script src="backend_func/JSfunctions.js"></script>
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

  function check_num(input_val,input_id,input_origin){
    if(isNaN(input_val)){
      alert("Error! Numbers Values Only Please.")
      document.getElementById(input_id).value = input_origin;
    }
  }

  function deduc_check_greaterthantotal(total_orig,per_salary_orig,i){
    var total = parseFloat(document.getElementById("emp_total_deduc_"+i).value);
    var per_sal = parseFloat(document.getElementById("emp_deduc_per_sal_"+i).value);
    if(total<per_sal){
      alert("Deduction per Salary cannot be greater than Total Deductions!");
      document.getElementById("emp_total_deduc_"+i).value = total_orig;
      document.getElementById("emp_deduc_per_sal_"+i).value = per_salary_orig;
    }
  }

  function deduc_check_greaterthansalary(salary_posttax,per_salary_orig,i){
    var per_sal = parseFloat(document.getElementById("emp_deduc_per_sal_"+i).value);
    if(salary_posttax<per_sal){
      alert("Deduction per Salary cannot be greater than Total Salary after rate Deductions!");
      document.getElementById("emp_deduc_per_sal_"+i).value = per_salary_orig;
    }
  }
</script>
</body>

</html>
