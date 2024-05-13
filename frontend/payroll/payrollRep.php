<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/payroll.php');
  $inv_tbl = new payroll();
  $result="";
  if($_SERVER['REQUEST_METHOD']=="POST"){

  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Payroll Report</title>
  <style>
        .table-container {
            display: flex;
            justify-content: space-around;
        }
        table {
            border-collapse: collapse;
            width: 30%;
            margin: 10px;
        }
        th, td {
            border: 1px solid rgb(7, 7, 7);
            padding: 8px;
            text-align: center;
        }
        .signatureLine {
            border-bottom: 1px solid rgb(80, 79, 79); 
            width: 200px; 
            margin-top: 25px; 
             margin-left: 440px;
        }
    </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
</head>
<body>
<button class="add-button" id="download"> download pdf</button>
    <div class="center" id="payrollPDF">
        <img src="logo.png" alt="Logo" class="logo-image" id="logoPDF" style="width:175px; height:175px; filter: grayscale(100%);">
        <div class="center">
            <h3 style="font-size: 25px; margin-bottom: 0; margin-top: 0;">SAMGYUPSALAMAT</h3>
            <h4 style="font-size: 20px; margin-top: 0; margin-bottom: 0;">JP Laurel Avenue Corner V.Maps Street, Davao City</h4>
            <div style="margin-top: 0;">
                <h4 style="color: rgb(80, 79, 79); margin-bottom: 0;">0917 116 4832</h4>
                <h4 style="color: rgb(80, 79, 79); margin-top: 0;">hr.samgyupsalamatdvo@gmail.com</h4>
            </div>
            <div>
                <h1 style="font-size: 22px; margin-top:50px; margin-bottom: 5px;">PAYROLL REPORT</h1>

                <?php $inv_tbl->viewpayroll_salarydown($_SESSION['id_payrollview']);?>  

            </div>
        </div>

        <div class="table-container">
        <table>
            <tr>
                <th colspan="2">Salary</th>
            </tr>

            <?php $inv_tbl->viewpayroll_salarydetails_down($_SESSION['id_payrollview']);?> 

        </table>
        
        <table> 
            <tr>
                <th colspan="2">Deductions</th>
            </tr>

            <?php $inv_tbl->viewpayroll_deductions_down($_SESSION['id_payrollview']);?> 

        </table>
        
        <table> 
            <tr>
                <th colspan="2">Payroll</th>
            </tr>

            <?php $inv_tbl->viewpayroll_payrollsummary_down($_SESSION['id_payrollview']);?>

        </table>
        </div>
        <div style="margin-top: 100px;">
            <div class="signatureLine"></div>
            <p style="margin-left: 440px;">Signature over Printed Name</p>
            <div class="signatureLine" style="margin-top: 50px"></div>
            <p style="margin-left: 440px;">Date Issued</p>
        </div>
    </div>
    
<script>
    window.onload = function () {
    document.getElementById("download")
        .addEventListener("click", () => {
            const salesPDF = this.document.getElementById("payrollPDF");
            console.log(payrollPDF);
            console.log(window);
            var opt = {
                margin: 1,
                filename: 'payrollreport.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 1 },
                jsPDF: { unit: 'in', format: 'legal', orientation: 'portrait' }
            };
            html2pdf().from(payrollPDF).set(opt).save();
        })
    }
</script>
</body>
</html>