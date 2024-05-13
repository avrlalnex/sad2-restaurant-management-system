
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>SALES</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
</head>
<body>
    <button class="btn btn-primary" id="download"> download pdf</button>
    <div class="center" id="salesPDF">
        <img src="logo.png" alt="Logo" class="logo-image" id="logoPDF">
        <h3>SAMGYUPSALAMAT</h3>
        <h4>JP Laurel Avenue Corner V.Maps Street, Davao City</h4>
        <div>
            <h4>0917 116 4832</h4>
            <h4>hr.samgyupsalamatdvo@gmail.com</h4>
        </div>
        <div>
            <h1>1st Quarterly Profit Report 2023</h1>
            <h3>Date Coverage:</h3>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Date Created:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01-01-23</td>
                            <td>03-31-23</td>
                            <td>04-01-23</td>
                        </tr>
                    </tbody>
                </table>    
        </div>
        <div>
            <table>
                <tr>
                    <td>Sales Total: </td>
                    <td>Php 309,510.00</td>
                </tr>
                <tr>
                    <td>Cost of Goods Sold Total: </td>
                    <td>Php 55,010.00</td>
                </tr>
                <tr>
                    <td>Gross Profit/Loss: </td>
                    <td>Php 254,500.00</td>
                </tr>
                <tr>
                    <td>Expense Total: </td>
                    <td>Php 21,050.00</td>
                </tr>
                <tr>
                    <td>Operating Profit/Loss: </td>
                    <td>Php 233,450.00</td>
                </tr>
                <tr>
                    <td>Tax Expense: </td>
                    <td>Php 11,100.00</td>
                </tr>
                <tr>
                    <td>Net Profit/Loss: </td>
                    <td>Php 222,350.00</td>
                </tr>
            </table>

        </div>
        
    </div>


  <script>
    window.onload = function () {
    document.getElementById("download")
        .addEventListener("click", () => {
            const salesPDF = this.document.getElementById("salesPDF");
            console.log(salesPDF);
            console.log(window);
            var opt = {
                margin: 1,
                filename: 'myfile.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(salesPDF).set(opt).save();
        })
}
  </script>
</body>
</html>