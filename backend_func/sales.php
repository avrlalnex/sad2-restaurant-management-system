<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }
    class sales extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }

        function show_sales_reports($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_title LIKE '$searchparam%' AND sales_report_date = '$datefilter' AND is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_title LIKE '$searchparam%' AND is_archived=0 ORDER BY sales_report_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_date = '$datefilter' AND is_archived=0";
            }else{
                $sql = "SELECT * FROM sales_reports WHERE is_archived=0 ORDER BY sales_report_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['sales_report_title'].'</td>
                    <td>₱ '.number_format($row['sales_report_total'],2).'</td>
                    <td>'.$row['sales_report_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="repView_id" value='.$row['sales_report_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_sales_reports_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_title LIKE '$searchparam%' AND sales_report_date = '$datefilter' AND is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_title LIKE '$searchparam%' AND is_archived=1 ORDER BY sales_report_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM sales_reports WHERE sales_report_date = '$datefilter' AND is_archived=1";
            }else{
                $sql = "SELECT * FROM sales_reports WHERE is_archived=1 ORDER BY sales_report_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['sales_report_title'].'</td>
                    <td>₱ '.number_format($row['sales_report_total'],2).'</td>
                    <td>'.$row['sales_report_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="repView_id" value='.$row['sales_report_id'].'>
                    <input type="submit" class="add-button" name="submit" value="View"></form></td></tr>'; 
                }
            }
        }

        function show_sales_receipts($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_no LIKE '$searchparam%' OR customer_name LIKE '$searchparam%' 
                AND receipt_date = '$datefilter' AND sales_receipts.is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_no LIKE '$searchparam%' OR customer_name LIKE '$searchparam%' AND sales_receipts.is_archived=0
                ORDER BY receipt_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_date = '$datefilter' AND sales_receipts.is_archived=0";
            }else{
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id WHERE sales_receipts.is_archived=0 ORDER BY receipt_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['receipt_no'].'</td>
                    <td>'.$row['table_no'].'</td>
                    <td>'.$row['customer_name'].'</td>
                    <td>₱ '.number_format($row['price_per_pax'],2).'</td>
                    <td>'.$row['receipt_pax'].'</td>
                    <td>₱ '.number_format($row['total_amount'],2).'</td>
                    <td>'.$row['discount_type'].'</td>
                    <td>'.$row['senior_pax'].'</td>
                    <td>'.$row['receipt_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="Actreceipt_id" value='.$row['receipt_id'].'>
                    <input type="submit" class="add-button" name="submit" value="View"></form></td></tr>'; 
                }
            }
        }

        function show_sales_receipts_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_no LIKE '$searchparam%' OR customer_name LIKE '$searchparam%' 
                AND receipt_date = '$datefilter' AND sales_receipts.is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_no LIKE '$searchparam%' OR customer_name LIKE '$searchparam%' AND sales_receipts.is_archived=1
                ORDER BY receipt_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
                WHERE receipt_date = '$datefilter' AND sales_receipts.is_archived=1";
            }else{
                $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id WHERE sales_receipts.is_archived=1 ORDER BY receipt_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['receipt_id'].'</td>
                    <td>'.$row['receipt_no'].'</td>
                    <td>'.$row['table_no'].'</td>
                    <td>'.$row['customer_name'].'</td>
                    <td>₱ '.number_format($row['price_per_pax'],2).'</td>
                    <td>'.$row['receipt_pax'].'</td>
                    <td>₱ '.number_format($row['total_amount'],2).'</td>
                    <td>'.$row['discount_type'].'</td>
                    <td>'.$row['senior_pax'].'</td>
                    <td>'.$row['receipt_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="Actreceipt_id" value='.$row['receipt_id'].'>
                    <input type="submit" class="add-button" name="submit" value="Un-Archive" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                }
            }
        }

        function show_sales_customers($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_customers WHERE customer_name LIKE '$searchparam%' AND is_archived=0";
            }else{
                $sql = "SELECT * FROM sales_customers WHERE is_archived=0";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['customer_name'].'</td>
                    <td>'.$row['customer_type'].'</td>
                    <td>'.$row['customer_loyalty'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="customer_id" value='.$row['customer_id'].'>
                    <input type="submit" class="add-button" name="submit" value="edit">
                    <input type="submit" class="add-button" name="submit" value="archive" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                }
            }
        }

        function show_sales_customers_archive($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM sales_customers WHERE customer_name LIKE '$searchparam%' AND is_archived=1";
            }else{
                $sql = "SELECT * FROM sales_customers WHERE is_archived=1";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['customer_id'].'</td>
                    <td>'.$row['customer_name'].'</td>
                    <td>'.$row['customer_type'].'</td>
                    <td>'.$row['customer_loyalty'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="customer_id" value='.$row['customer_id'].'>
                    <input type="submit" class="add-button" name="submit" value="un-Archive" onclick="return confirm(\'Are you sure?\');">
                    <input type="submit" class="add-button" name="submit" value="delete" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                }
            }
        }

        function get_receipt_customer_options(){
            $sql = "SELECT * FROM sales_customers";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value="'.$row['customer_name'].'">'.$row['customer_name'].'</option>'; 
                }
            }
        }

        function add_receipt($receipt_no,$customer_name,$pax,$total_amount,$discount,$date,$seniorpax,$price_per_pax,$table_no){
            $sql = "SELECT * FROM sales_customer_loyalty_upgrade";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $threshold = $row["threshold"];
                }
            }
            $sql = "SELECT * FROM sales_customers WHERE customer_name = '$customer_name'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $customer_id = $row['customer_id'];
                    $new_loyalty = $row['customer_loyalty']+1;
                    $msql = "UPDATE sales_customers SET customer_loyalty=$new_loyalty WHERE customer_id=".$customer_id;
                    $this->connect()->query($msql);
                    if($new_loyalty >= $threshold AND $row['customer_type']!='Regular'){
                        $msql = "UPDATE sales_customers SET customer_type='Regular' WHERE customer_id=".$customer_id;
                        $this->connect()->query($msql);
                    }
                }
            }else{
                $sql = "INSERT INTO sales_customers values ('','$customer_name','Not Regular',1,0)";
                $result = $this->connect()->query($sql);

                $sql = "SELECT * FROM sales_customers";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $customer_id = $row['customer_id'];
                    }
                }
            }

            $sql = "INSERT INTO sales_receipts values ('','$receipt_no',$customer_id,$pax,$total_amount,'$discount','$date',$seniorpax,$price_per_pax,$table_no,0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add receipt in successful!";}else{return "Add receipt in failed.";}
        }

        function delete_receipt($receipt_id){
            $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id  WHERE receipt_id = $receipt_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $customer_id = $row['customer_id'];
                    $new_loyalty = $row['customer_loyalty']-1;
                }
            }

            $sql = "UPDATE sales_customers SET customer_loyalty=$new_loyalty WHERE customer_id=".$customer_id;
            $this->connect()->query($sql);

            $sql = "DELETE FROM sales_receipts WHERE receipt_id=".$receipt_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function show_receipt_edit($receipt_id){
            $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
            WHERE receipt_id=$receipt_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row['discount_type']=='Senior'){
                        $seniorhidden="";
                        $seniorlabel="Senior Pax:";
                    }else{
                        $seniorhidden="hidden";
                        $seniorlabel="";
                    }
                    echo '
                    <div class="input-container">
                        <label for="receiptNumber">Receipt Number:</label>
                        <input type="text" id="receiptNumber" name="receiptNumber" value="'.$row['receipt_no'].'">
                    </div>
                    <div class="input-container">
                        <label for="receiptTable">Table Number:</label>
                        <input type="text" id="receiptTable" name="receiptTable" value="'.$row['table_no'].'" oninput="check_num(this.value,this.id)">
                    </div>
                    <div class="input-container">
                        <label for="custName">Customer:</label>
                        <select id="custType" name="custType" onchange="receipt_selectCustomerType()">
                            <option value="Repeat">Repeat Customer</option>
                            <option value="New">New Customer</option>
                        </select>
                        <input type="text" id="custName" name="custName" value="'.$row['customer_name'].'">
                        <select id="custReg" name="custReg" hidden onchange="receipt_selectCustomer(this.value)">
                        <option value="'.$row['customer_name'].'" selected>'.$row['customer_name'].'</option>
                        <?php $inv_tbl->get_receipt_customer_options();?> 
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="payPax">Price per Pax:</label>
                        <input type="number" id="payPax" name="payPax" value="'.$row['price_per_pax'].'" oninput="check_num(this.value,this.id);receipt_calculateTotal()">
                    </div>
                    <div class="input-container">
                        <label for="numPax">Number of Pax:</label>
                        <input type="number" id="numPax" name="numPax" value="'.$row['receipt_pax'].'" oninput="check_paxValid(this.value);receipt_calculateTotal()">
                    </div>
                    <div class="input-container">
                        <label for="discType">Discount Type:</label>
                        <select id="discType" name="discType" onchange="receipt_selectSenior()">
                            <option value="'.$row['discount_type'].'" selected hidden>'.$row['discount_type'].'</option>
                            <option value="Not Discounted">Not Discounted</option> 
                            <option value="Senior">Senior</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="seniorPax" id="seniorPaxLabel">'.$seniorlabel.'</label>
                        <input type="number" id="seniorPax" name="seniorPax" value="'.$row['senior_pax'].'" '.$seniorhidden.' oninput="check_seniorValid(this.value);receipt_calculateTotal()">
                    </div>
                    <div class="input-container">
                        <label for="totAmount">Total Amount:</label>
                        <input type="text" id="totAmount" name="totAmount" value="'.$row['total_amount'].'" oninput="check_num(this.value,this.id)">
                    </div>
                    <div class="input-container">
                        <label for="receiptDate">Date:</label>
                        <input type="date" id="receiptDate" name="receiptDate" value="'.$row['receipt_date'].'">
                    </div>
                    <div class="input-container">
                        <input type="submit" class="add-button" name="submit" value="Save">
                    </div>'; 
                }
            }
        }

        function edit_receipt($receipt_id,$receipt_no,$customer_name,$pax,$total_amount,$discount,$date,$seniorpax,$price_per_pax,$table_no,$payrate){
            $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id WHERE receipt_id=$receipt_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $customer_id = $row['customer_id'];
                    $loyalty = $row['customer_loyalty'];
                }
            }

            $sql = "SELECT * FROM sales_customers WHERE customer_name = '$customer_name'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $edit_customer_id = $row['customer_id'];
                    $edit_loyalty = $row['customer_loyalty'];
                }
                if($customer_id!=$edit_customer_id){
                    $msql = "UPDATE sales_customers SET customer_loyalty=($loyalty-1) WHERE customer_id=".$customer_id;
                    $this->connect()->query($msql); 

                    $msql = "UPDATE sales_customers SET customer_loyalty=($edit_loyalty+1) WHERE customer_id=".$edit_customer_id;
                    $this->connect()->query($msql);
                }
                
            }else{
                $sql = "INSERT INTO sales_customers values ('','$customer_name','Not Regular',1)";
                $result = $this->connect()->query($sql);

                $sql = "SELECT * FROM sales_customers";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $customer_id = $row['customer_id'];
                    }
                }
            }

            $sql = "SELECT * FROM sales_receipt_products LEFT JOIN sales_products ON sales_receipt_products.product_id = sales_products.product_id  WHERE receipt_id=$receipt_id AND is_addon=0";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $rec_prod_id = $row['receipt_product_id'];
                    }
                }

            $sql = "UPDATE sales_receipt_products SET product_id=$payrate WHERE receipt_product_id=$rec_prod_id";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE sales_receipts SET receipt_no='$receipt_no',customer_id=$edit_customer_id,receipt_pax=$pax,total_amount=$total_amount,discount_type='$discount',receipt_date='$date',price_per_pax=$price_per_pax,senior_pax=$seniorpax,table_no=$table_no 
            WHERE receipt_id=".$receipt_id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit item successful!";}else{return "Edit item failed.";}
        }

        function archive_receipt($receipt_id){
            $sql = "UPDATE sales_receipts SET is_archived=1 
            WHERE receipt_id=".$receipt_id;
            $result = $this->connect()->query($sql);
            if($result){return "Archive successful!";}else{return "Archivefailed.";}
        }

        function unarchive_receipt($receipt_id){
            $sql = "UPDATE sales_receipts SET is_archived=0 
            WHERE receipt_id=".$receipt_id;
            $result = $this->connect()->query($sql);
            if($result){return "Archive successful!";}else{return "Archivefailed.";}
        }

        function show_sales_report_receipts($date){
            $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
            WHERE receipt_date ='$date' AND sales_receipts.is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $receipt_id=$row['receipt_id'];
                    $asql = "SELECT * FROM sales_receipt_products INNER JOIN sales_products ON sales_receipt_products.product_id=sales_products.product_id 
                    WHERE receipt_id=$receipt_id AND is_addon=0";
                    $aresult = $this->connect()->query($asql);
                    if($aresult->num_rows > 0){
                        while($arow = $aresult->fetch_assoc()){
                                $prod_name=$arow['product_name'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['receipt_no'].'</td>
                    <td>'.$row['customer_name'].'</td>
                    <td>'.$prod_name.'</td>
                    <td>'.$row['receipt_pax'].'</td>
                    <td>₱ '.number_format($row['total_amount'],2).'</td>
                    <td>'.$row['discount_type'].'</td>
                    <td>'.$row['receipt_date'].'</td></tr>'; 
                }
            }
        }

        function get_sales_report_totalAmt($date){
            $sql = "SELECT * FROM sales_receipts WHERE receipt_date ='$date'";
            $result = $this->connect()->query($sql);
            $totAmt = 0;
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $totAmt+=$row['total_amount'];
                }
            }

            return $totAmt;
        }

        function add_sales_report($title,$total_amount,$date){
            $sql = "INSERT INTO sales_reports values ('','$title',$total_amount,'$date',0)";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM sales_reports";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rep_id=$row['sales_report_id'];
                }
            }

            $sql = "SELECT * FROM sales_receipts WHERE receipt_date ='$date'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rec_id=$row['receipt_id'];
                    $msql = "INSERT INTO sales_report_receipts values ('',$rep_id,$rec_id)";
                    $this->connect()->query($msql);
                }
            }

            if($result){return "Add report successful!";}else{return "Add report failed.";}
        }

        function delete_report($rep_id){
            $sql = "DELETE FROM sales_reports WHERE sales_report_id=".$rep_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function archive_report($rep_id){
            $sql = "UPDATE sales_reports SET is_archived=1 WHERE sales_report_id=".$rep_id;
            $result = $this->connect()->query($sql);

            if($result){return "archive successful!";}else{return "archive failed.";}
        }

        function unarchive_report($rep_id){
            $sql = "UPDATE sales_reports SET is_archived=0 WHERE sales_report_id=".$rep_id;
            $result = $this->connect()->query($sql);

            if($result){return "archive successful!";}else{return "archive failed.";}
        }

        function show_sales_report_view($rep_id){
  
            $sql = "SELECT * FROM sales_reports WHERE sales_report_id=$rep_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo'
                    <div class="sales-entry">

                    <div class="input-container">
                        <label for="reportTitle">Report Title:</label>
                        <input type="text" id="reportTitle" name="reportTitle" value="'.$row['sales_report_title'].'" readonly>
                    </div>
                    <div class="input-container">
                        <label for="totAmount">Total Amount:</label>
                        <input type="text" id="totAmount" name="totAmount" value="₱'.number_format($row['sales_report_total'],2).'" readonly>
                    </div>
                    <div class="input-container">
                        <label for="repDate">Date:</label>
                        <input type="date" id="repDate" name="repDate" value="'.$row['sales_report_date'].'" readonly>
                    </div>
                    ';
                }
            }
            if($result){return "show report successful!";}else{return "show report failed.";}
        }

        function show_sales_report_receipts_view($rep_id){
            $sql = "SELECT * FROM sales_receipts 
            INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
            INNER JOIN sales_report_receipts ON sales_receipts.receipt_id=sales_report_receipts.receipt_id 
            WHERE sales_report_id = $rep_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $receipt_id=$row['receipt_id'];
                    $asql = "SELECT * FROM sales_receipt_products INNER JOIN sales_products ON sales_receipt_products.product_id=sales_products.product_id 
                    WHERE receipt_id=$receipt_id AND is_addon=0";
                    $aresult = $this->connect()->query($asql);
                    if($aresult->num_rows > 0){
                        while($arow = $aresult->fetch_assoc()){
                                $prod_name=$arow['product_name'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['receipt_no'].'</td>
                    <td>'.$row['customer_name'].'</td>
                    <td>'.$prod_name.'</td>
                    <td>'.$row['receipt_pax'].'</td>
                    <td>₱ '.number_format($row['total_amount'],2).'</td>
                    <td>'.$row['discount_type'].'</td>
                    <td>'.$row['receipt_date'].'</td></tr>'; 
                }
            }
        }

        function get_customer_threshold(){
            
            $sql = "SELECT * FROM sales_customer_loyalty_upgrade";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $threshold=$row['threshold'];
                }
            }
            return $threshold;
        }

        function get_senior_discount(){
            
            $sql = "SELECT * FROM sales_senior_discount";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $discount_value	=$row['discount_value']*100;
                }
            }
            return $discount_value;
        }
        function edit_customer_threshold($threshold){
            $sql = "UPDATE sales_customer_loyalty_upgrade SET threshold=$threshold WHERE loyalty_id=1";
            $result = $this->connect()->query($sql);
            if($result){return "Edit threshold successful!";}else{return "Edit threshold failed.";}
        }

        function edit_senior_discount($discountPerc){
            $sql = "UPDATE sales_senior_discount SET discount_value=($discountPerc/100) WHERE discount_id=1";
            $result = $this->connect()->query($sql);
            if($result){return "Edit discount successful!";}else{return "Edit discount failed.";}
        }

        function get_sales_report_detail($rep_id,$type){
            $sql = "SELECT * FROM sales_reports WHERE sales_report_id=$rep_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($type == 'title'){
                        $value = $row['sales_report_title'];
                    }else if($type == 'totamt'){
                        $value = $row['sales_report_total'];
                    }if($type == 'date'){
                        $value = $row['sales_report_date'];
                    }
                }
            }

            return $value;
        }

        function edit_sales_report($rep_id,$title,$total_amount,$date){
            $sql = "UPDATE sales_reports SET sales_report_title='$title',sales_report_total=$total_amount,sales_report_date='$date' WHERE sales_report_id=$rep_id";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM sales_report_receipts WHERE sales_report_id =$rep_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rec_id=$row['receipt_id'];
                    $msql = "DELETE FROM sales_report_receipts WHERE sales_report_id=".$rep_id;
                    $this->connect()->query($msql);
                }
            }

            $sql = "SELECT * FROM sales_receipts WHERE receipt_date ='$date'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rec_id=$row['receipt_id'];
                    $msql = "INSERT INTO sales_report_receipts values ('',$rep_id,$rec_id)";
                    $this->connect()->query($msql);
                }
            }

            if($result){return "Edit report successful!";}else{return "Edit report failed.";}
        }

        function edit_customer($customer_id,$name,$type,$loyalty){
            $sql = "UPDATE sales_customers SET customer_name='$name',customer_type='$type',customer_loyalty=$loyalty WHERE customer_id=$customer_id";
            $result = $this->connect()->query($sql);
            if($result){return "Edit customer successful!";}else{return "Edit customer failed.";}
        }

        function delete_customer($customer_id){
            $sql = "DELETE FROM sales_customers WHERE customer_id=$customer_id";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function archive_customer($customer_id){
            $sql = "UPDATE sales_customers  SET is_archived=1 WHERE customer_id=$customer_id";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function unarchive_customer($customer_id){
            $sql = "UPDATE sales_customers  SET is_archived=0 WHERE customer_id=$customer_id";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function get_customer_detail($customer_id,$type){
            $sql = "SELECT * FROM sales_customers WHERE customer_id=$customer_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($type == 'name'){
                        $value = $row['customer_name'];
                    }else if($type == 'type'){
                        $value = $row['customer_type'];
                    }if($type == 'loyalty'){
                        $value = $row['customer_loyalty'];
                    }
                }
            }
            return $value;
        }

        function show_sales_products($is_addon){
            $sql = "SELECT * FROM sales_products WHERE is_addon=$is_addon AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td><input type="hidden" id="'.$is_addon.'_product_id_'.$i.'" name="'.$is_addon.'_product_id_'.$i.'" value='.$row['product_id'].'>
                    <input type="text" id="'.$is_addon.'_product_name_'.$i.'" name="'.$is_addon.'_product_name_'.$i.'" value="'.$row['product_name'].'"></td>
                    <td><input type="text" id="'.$is_addon.'_product_price_'.$i.'" name="'.$is_addon.'_product_price_'.$i.'" value='.$row['product_price'].' oninput="check_num(this.value,this.id,'.$row['product_price'].')"></td>
                    <td><form method="POST">
                    <input type="hidden" id="product_id_archive" name="product_id_archive" value='.$row['product_id'].'>
                    <input type="submit" class="add-button" name="submit" value="Archive" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                    $i++;
                }
                echo '<input type="hidden" id="'.$is_addon.'_max_num" name="'.$is_addon.'_max_num" value='.$i.'>';
            }
        }

        function show_sales_products_report($receipt_date){
            $sql = "SELECT * FROM sales_products WHERE is_addon=0 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $prod_id=$row['product_id'];
                    $qsql = "SELECT * FROM sales_receipt_products 
                    INNER JOIN sales_receipts ON sales_receipt_products.receipt_id=sales_receipts.receipt_id WHERE receipt_date='$receipt_date' AND product_id=$prod_id AND is_archived=0";
                    $qresult = $this->connect()->query($qsql);
                    $quantotal=0;
                    $saletotal=0;
                    if($qresult->num_rows > 0){
                        while($qrow = $qresult->fetch_assoc()){
                            $quantotal+=$qrow['product_quantity']; 
                            $saletotal+=$qrow['product_total_price'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$quantotal.'</td>
                    <td>₱ '.$row['product_price'].'</td>
                    <td>₱ '.number_format($saletotal,2).'</td></tr>'; 
                }
            }
            $sql = "SELECT * FROM sales_products WHERE is_addon=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $prod_id=$row['product_id'];
                    $qsql = "SELECT * FROM sales_receipt_products 
                    INNER JOIN sales_receipts ON sales_receipt_products.receipt_id=sales_receipts.receipt_id WHERE receipt_date='$receipt_date' AND product_id=$prod_id AND is_archived=0";
                    $qresult = $this->connect()->query($qsql);
                    $quantotal=0;
                    $saletotal=0;
                    if($qresult->num_rows > 0){
                        while($qrow = $qresult->fetch_assoc()){
                            $quantotal+=$qrow['product_quantity']; 
                            $saletotal+=$qrow['product_total_price'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$quantotal.'</td>
                    <td>₱ '.$row['product_price'].'</td>
                    <td>₱ '.number_format($saletotal,2).'</td></tr>'; 
                }
            }
        }

        function show_sales_products_report_view($rep_id){
            $sql = "SELECT * FROM sales_products WHERE is_addon=0 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $prod_id=$row['product_id'];
                    $qsql = "SELECT * FROM sales_receipt_products 
                    INNER JOIN sales_report_receipts ON sales_receipt_products.receipt_id=sales_report_receipts.receipt_id 
                    WHERE product_id=$prod_id AND sales_report_id=$rep_id";
                    $qresult = $this->connect()->query($qsql);
                    $quantotal=0;
                    $saletotal=0;
                    if($qresult->num_rows > 0){
                        while($qrow = $qresult->fetch_assoc()){
                            $quantotal+=$qrow['product_quantity']; 
                            $saletotal+=$qrow['product_total_price'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$quantotal.'</td>
                    <td>₱ '.$row['product_price'].'</td>
                    <td>₱ '.number_format($saletotal,2).'</td></tr>'; 
                }
            }
            $sql = "SELECT * FROM sales_products WHERE is_addon=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $prod_id=$row['product_id'];
                    $qsql = "SELECT * FROM sales_receipt_products 
                    INNER JOIN sales_report_receipts ON sales_receipt_products.receipt_id=sales_report_receipts.receipt_id 
                    WHERE product_id=$prod_id AND sales_report_id=$rep_id";
                    $qresult = $this->connect()->query($qsql);
                    $quantotal=0;
                    $saletotal=0;
                    if($qresult->num_rows > 0){
                        while($qrow = $qresult->fetch_assoc()){
                            $quantotal+=$qrow['product_quantity']; 
                            $saletotal+=$qrow['product_total_price'];
                        }
                    }
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$quantotal.'</td>
                    <td>₱ '.$row['product_price'].'</td>
                    <td>₱ '.number_format($saletotal,2).'</td></tr>'; 
                }
            }
        }

        function show_sales_products_archive($is_addon){
            $sql = "SELECT * FROM sales_products WHERE is_addon=$is_addon AND is_archived=1";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$row['product_price'].'</td>
                    <td><form method="POST">
                    <input type="hidden" id="product_id_archive" name="product_id_archive" value='.$row['product_id'].'>
                    <input type="submit" class="add-button" name="submit" value="Un-Archive" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                }
            }
        }

        function get_sales_product_default(){
            $sql = "SELECT * FROM sales_products WHERE is_default=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value='.$row['product_id'].' selected hidden>'.$row['product_name'].'</option>'; 
                }
            }
        }

        function get_sales_product_unli(){
            $sql = "SELECT * FROM sales_products WHERE is_addon=0 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value='.$row['product_id'].'>'.$row['product_name'].'</option>'; 
                }
            }
        }

        function get_sales_product_addon(){
            $sql = "SELECT * FROM sales_products WHERE is_addon=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value='.$row['product_id'].'>'.$row['product_name'].'</option>'; 
                }
            }
        }

        function get_sales_product_addon_edit(){
            $sql = "SELECT * FROM sales_products WHERE is_addon=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $optionstring="";
                while($row = $result->fetch_assoc()){
                    $optionstring=$optionstring.'<option value='.$row['product_id'].'>'.$row['product_name'].'</option>'; 
                }
            }
            return $optionstring;
        }

        function get_sales_product_default_price(){
            $sql = "SELECT * FROM sales_products WHERE is_default=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            $price=0;
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $price=$row['product_price']; 
                }
            }
            return $price;
        }

        function get_sales_product_unli_price(){
            $sql = "SELECT * FROM sales_products WHERE is_addon=0 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<input type="hidden" id="product_price_'.$row['product_id'].'" name="product_price_'.$row['product_id'].'" value='.$row['product_price'].'>';
                }
            }
        }

        function get_sales_product_addon_price(){
            $sql = "SELECT * FROM sales_products WHERE is_addon=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <input type="hidden" id="product_price_'.$row['product_id'].'" name="product_price_'.$row['product_id'].'" value='.$row['product_price'].'>
                    <input type="hidden" id="product_name_'.$row['product_id'].'" name="product_name_'.$row['product_id'].'" value="'.$row['product_name'].'">';
                }
            }
        }

        function archive_product($product_id){
            $sql = "UPDATE sales_products SET is_archived=1,is_default=0 WHERE product_id=$product_id";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM sales_products WHERE is_default=1 AND is_archived=0";
            $result = $this->connect()->query($sql);
            if($result->num_rows == 0){
                $asql = "UPDATE sales_products SET is_default=1 WHERE is_addon=0 AND is_archived=0 LIMIT 1";
                $this->connect()->query($asql);
            }

            if($result){return "Archive successful!";}else{return "Archivefailed.";}
        }

        function unarchive_product($product_id){
            $sql = "UPDATE sales_products  SET is_archived=0 WHERE product_id=$product_id";
            $result = $this->connect()->query($sql);

            if($result){return "unArchive successful!";}else{return "unArchive failed.";}
        }

        function add_product($is_addon,$product_name,$product_price){
            $sql = "INSERT INTO sales_products values ('',$is_addon,'$product_name',$product_price,0,0)";
            $result = $this->connect()->query($sql);

            if($result){return "Add product successful!";}else{return "Add product failed.";}
        }

        function edit_product($product_id,$product_name,$product_price){
            $sql = "UPDATE sales_products SET product_name='$product_name',product_price=$product_price WHERE product_id=$product_id";
            $result = $this->connect()->query($sql);

            if($result){return "edit successful!";}else{return "edit failed.";}
        }

        function edit_product_default($product_id){
            $sql = "UPDATE sales_products SET is_default=0 WHERE is_addon=0";
            $result = $this->connect()->query($sql);
            $sql = "UPDATE sales_products SET is_default=1 WHERE product_id=$product_id";
            $result = $this->connect()->query($sql);

            if($result){return "edit successful!";}else{return "edit failed.";}
        }

        function get_next_receipt_no(){
            $sql = "SELECT * FROM sales_receipts WHERE is_archived=0 ORDER BY receipt_no DESC LIMIT 1";
            $result = $this->connect()->query($sql);
            $receipt_id=0;
            if($result->num_rows > 0){
                $receipt_id=0;
                while($row = $result->fetch_assoc()){
                    $receipt_id=$row['receipt_no']+1; 
                }
            }

            if ($receipt_id<99999){
                $receipt_id="0".$receipt_id;
            }
            return $receipt_id;
        }

        function get_last_receipt(){
            $sql = "SELECT * FROM sales_receipts ORDER BY receipt_id DESC LIMIT 1";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $receipt_id=0;
                while($row = $result->fetch_assoc()){
                    $receipt_id=$row['receipt_id']; 
                }
            }
            
            return $receipt_id;
        }

        function add_receipt_product($receipt_id,$product_id,$product_qtty,$product_total){
            $sql = "INSERT INTO sales_receipt_products values ('',$receipt_id,$product_id,$product_qtty,$product_total)";
            $result = $this->connect()->query($sql);

            if($result){return "Add product successful!";}else{return "Add product failed.";}
        }

        function edit_receipt_product($receipt_product_id,$product_id,$product_qtty,$product_total){
            $sql = "UPDATE sales_receipt_products SET product_id=$product_id,product_quantity=$product_qtty,product_total_price=$product_total WHERE receipt_product_id=$receipt_product_id";
            $result = $this->connect()->query($sql);

            if($result){return "edit successful!";}else{return "edit failed.";}
        }

        function delete_receipt_product($receipt_product_id){
            $sql = "DELETE FROM sales_receipt_products WHERE receipt_product_id=$receipt_product_id";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function delete_receipt_product_empty($receipt_product_id){
            $sql = "DELETE FROM sales_receipt_products WHERE receipt_product_id=$receipt_product_id AND product_quantity=0";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function get_sales_receipt_detail($receipt_id,$detail_type){
            $sql = "SELECT * FROM sales_receipts INNER JOIN sales_customers ON sales_receipts.customer_id=sales_customers.customer_id 
            LEFT JOIN sales_receipt_products ON sales_receipts.receipt_id=sales_receipt_products.receipt_id
            LEFT JOIN sales_products ON sales_receipt_products.product_id=sales_products.product_id
            WHERE sales_receipts.receipt_id=$receipt_id AND is_addon=0";         
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($detail_type=="receipt number"){
                        $detail=$row['receipt_no'];
                    }else if($detail_type=="table number"){
                        $detail=$row['table_no'];
                    }else if($detail_type=="customer name"){
                        $detail=$row['customer_name'];
                    }else if($detail_type=="customer id"){
                        $detail=$row['customer_id'];
                    }else if($detail_type=="product name"){
                        $detail=$row['product_name'];
                    }else if($detail_type=="product id"){
                        $detail=$row['product_id'];
                    }else if($detail_type=="pax price"){
                        $detail=$row['price_per_pax'];
                    }else if($detail_type=="total amount"){
                        $detail=$row['total_amount'];
                    }else if($detail_type=="discount type"){
                        $detail=$row['discount_type'];
                    }else if($detail_type=="pax"){
                        $detail=$row['receipt_pax'];
                    }else if($detail_type=="senior pax"){
                        $detail=$row['senior_pax'];
                    }else if($detail_type=="receipt date"){
                        $detail=$row['receipt_date'];
                    }else{
                        $detail="invalid detail type";
                    }
                }
            }

            return $detail;
        }

        function show_sales_receipt_addons($receipt_id){
            $sql = "SELECT * FROM sales_receipt_products INNER JOIN sales_products ON sales_receipt_products.product_id = sales_products.product_id 
            WHERE receipt_id=$receipt_id AND is_addon=1";         
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['product_name'].'</td>
                    <td>'.$row['product_quantity'].'</td>
                    <td>₱ '.$row['product_total_price'].'</td></tr>'; 
                }
            }

            if($result){return "show successful!";}else{return "show failed.";}
        }

        function show_sales_receipt_addons_edit($receipt_id){
            $sql = "SELECT * FROM sales_receipt_products INNER JOIN sales_products ON sales_receipt_products.product_id = sales_products.product_id 
            WHERE receipt_id=$receipt_id AND is_addon=1";         
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td><select id="item-id_'.$i.'" name="item-id_'.$i.'">
                    <option value="'.$row['product_id'].'" selected hidden>'.$row['product_name'].'</option> 
                    '.$this->get_sales_product_addon_edit().'
                    </select>
                    </td>
                    <td><input type="text" oninput="check_num_minimum(this.value,this.id)" id="orderitem-quant_'.$i.'" name="orderitem-quant_'.$i.'" value='.$row['product_quantity'].'></td>
                    <td><input type="text" oninput="check_num(this.value,this.id)" id="orderitem-price_'.$i.'" name="orderitem-price_'.$i.'" value='.$row['product_total_price'].'></td>
                    <td><input type="hidden" name="orderitem-id_'.$i.'" value='.$row['receipt_product_id'].'><form method="POST" onSubmit="return confirm(\'Are you sure you want to delete?\');">
                    <input type="hidden" name="orderitemDelete_id" value='.$row['receipt_product_id'].'>
                    <input type="submit" class="add-button" name="submit" value="delete"></form></td></tr>'; 
                    $i++;
                }
                echo '<input type="hidden" id="orderslip-numofitems" name="orderslip-numofitems" value='.($i).' readonly>';
            }

            if($result){return "show successful!";}else{return "show failed.";}
        }

        function get_is_samedate($date){
            $sql = "SELECT * FROM sales_reports WHERE sales_report_date = '$date' AND is_archived=0";
            $result = $this->connect()->query($sql);
            $exists = false;
            if($result->num_rows > 0){
                $exists = true;
            }
            return $exists;
        }
    }

