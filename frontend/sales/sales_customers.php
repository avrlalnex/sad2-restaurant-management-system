<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/sales.php');
  $inv_tbl = new sales();
  $result="";
  $_SESSION['customer_search']="";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if($_POST['submit'] == "Search"){
      $_SESSION['customer_search'] = $_POST['search_input'];
    }else if($_POST['submit'] == "Set Loyalty Threshold"){
      $result = $inv_tbl->edit_customer_threshold($_POST['loy-thresh']);
    }else if($_POST['submit'] == "archive"){
      $result = $inv_tbl->archive_customer($_POST['customer_id']);
    }else if($_POST['submit'] == "edit"){
      $_SESSION['edit_customer_id']=$_POST['customer_id'];
      header("Location: sales_edit_customer.php");
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
<div class="sidebar">
  <div class="logo-details">
    <i class='bx bx-food-menu icon'></i>
    <div class="logo_name">Samgyupsalamat</div>
    <i class='bx bx-menu' id="btn"></i>
  </div>
  <ul class="nav-list">
    <li>
      <a href="lg.html">
        <i class='bx bx-grid-alt'></i>
        <span class="links_name">Dashboard</span>
      </a>
      <span class="tooltip">Dashboard</span>
    </li>
    <li>
      <a href="Inventory_page.php">
        <i class='bx bx-objects-vertical-center'></i>
        <span class="links_name">Inventory</span>

      <span class="tooltip">Inventory</span>
      </a>
    </li>
    <li>
      <a href="stock_page.php">
        <i class='bx bx-basket'></i>
        <span class="links_name">Stocks</span>
      </a>
      <span class="tooltip">Stocks</span>
    </li>
    <li>
      <a href="sales.php">
        <i class='bx bx-chat'></i>
        <span class="links_name">Sales</span>
      </a>
      <span class="tooltip">Sales</span>
    </li>
    <li>
      <a href="payroll.php">
        <i class='bx bx-wallet-alt'></i>
        <span class="links_name">Payroll</span>
      </a>
      <span class="tooltip">Payroll</span>
    </li>
    <li>
      <a href="empro.php">
        <i class='bx bx-user'></i>
        <span class="links_name">Employee Profile</span>
      </a>
      <span class="tooltip">Employee Profile</span>
    </li>
      
    <li class="profile">
      <div class="profile-details">
        <img src="https://drive.google.com/uc?export=view&id=1ETZYgPpWbbBtpJnhi42_IR3vOwSOpR4z" alt="profileImg">
        <div class="name_job">
          <div class="name">Samgyupsalamat</div>
          <div class="job">admin</div>
        </div>
      </div>
      <a href="#">
      <i class='bx bx-log-out' id="log_out"></i>
    </a>
    </li>
  </ul>
</div>
<section class="home-section">
  <div class="text">Sales</div>

  <div class="header-container">
    <a href="sales.php"><button class="add-button">Return</button></a>
    <a href="sales_customers_archive.php"><button class="add-button">Customer Archive</button></a>
    <div class="search-bar">
    <form method="POST" onSubmit="return confirm(\'Are you sure?\');">
      <input type="number" id="loy-thresh" name="loy-thresh" value="<?php echo $inv_tbl->get_customer_threshold();?>">
      <input type="submit" name="submit" value="Set Loyalty Threshold" class="search-button">
    </form>
    </div>
    <div class="search-bar">
    <form method="POST">
      <input type="text" id="searchInput" name="search_input" placeholder="Search Customer">
      <input type="submit" name="submit" value="Search" class="search-button">
      </form>
    </div>
  </div>
  
  <div class="sales-table"><div class="inventory-table">
  <table class="item-table">
      <thead>
        <tr>
          <th>NAME</th>
          <th>TYPE</th>
          <th>LOYALTY</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <?php $inv_tbl->show_sales_customers($_SESSION['customer_search']);?>    
      </tbody>
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
      menuBtnChange(); //calling the function(optional)
    });
    searchBtn.addEventListener("click", () => {
      // Sidebar open when you click on the search iocn
      sidebar.classList.toggle("open");
      menuBtnChange(); //calling the function(optional)
    });
    // following are the code to change sidebar button(optional)
    function menuBtnChange() {
      if (sidebar.classList.contains("open")) {
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
      } else {
        closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
      }
    }
  </script>
</section>
</body>
</html>