<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }
    class stock extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        function show_items($itemtype){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type = '$itemtype'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value='.$row['item_id'].'>'.$row['item_name'].'</option>'; 
                }
            }
        }

        function show_items_invoice($itemtype){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type = '$itemtype'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $optionstring="";
                while($row = $result->fetch_assoc()){
                    $optionstring=$optionstring.'<option value='.$row['item_id'].'>'.$row['item_name'].'</option>'; 
                }
            }
            return $optionstring;
        }

        function show_items_editstockout($itemtype,$stockoutform_id){
            $sql = "SELECT * FROM inventory 
            WHERE is_archived = 0 
            AND item_type = '$itemtype' 
            AND NOT EXISTS (
                SELECT * FROM goods_stock_out
                WHERE goods_stock_out.goods_stockout_form_id = $stockoutform_id
                AND goods_stock_out.item_id = inventory.item_id
            )";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row['item_id']){
                        echo '<option value='.$row['item_id'].'>'.$row['item_name'].'</option>'; 
                    }
                    
                }
            }
        }
    
        function show_stocks($searchparam,$filterparam,$filterINOUTparam,$filterdate){
            if($filterparam == "Goods" AND $filterINOUTparam == "STOCK IN"){
                if (!empty($searchparam) && !empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Goods' AND invoice_date='$filterdate' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Goods' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_type ='Goods' AND invoice_date='$filterdate' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
                    WHERE inventory.item_type ='Goods' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['invoice_no'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['active_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['invoice_date'].'</td>
                        <td>'.$row['expiry_date'].'</td>
                        </tr>';
                    }
                }
            }else if($filterparam == 'Goods' AND $filterINOUTparam == 'STOCK OUT'){
                if (!empty($searchparam) && !empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Goods' AND stock_date='$filterdate' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Goods' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE stock_date='$filterdate' AND inventory.item_type ='Goods' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='Goods' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['stock_desc'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['stock_date'].'</td>
                        </tr>';
                    }
                }
            }else if($filterparam == 'Supply'AND $filterINOUTparam == 'STOCK IN'){
                if (!empty($searchparam) && !empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Supply' AND invoice_date='$filterdate' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Supply' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_type ='Supply' AND invoice_date='$filterdate' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
                    WHERE inventory.item_type ='Supply' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['invoice_no'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['active_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['invoice_date'].'</td>
                        </tr>';
                    }
                }
            }else if($filterparam == 'Supply'AND $filterINOUTparam == 'STOCK OUT'){
                if (!empty($searchparam) && !empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Supply' AND stock_date='$filterdate' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='Supply' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($filterdate)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE stock_date='$filterdate' AND inventory.item_type ='Supply' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='Supply' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['stock_desc'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['stock_date'].'</td>
                        </tr>';
                    }
                }
            }
        }

        function show_stocks_history($searchparam,$filterparam,$filterINOUTparam,$filterdatefloor,$filterdateceiling){
            if($filterINOUTparam == "STOCK IN"){
                if (!empty($searchparam) && !empty($filterdatefloor) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND invoice_date>='$filterdatefloor' AND invoice_date<='$filterdateceiling' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($filterdatefloor) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_type ='$filterparam' AND invoice_date>='$filterdatefloor' AND invoice_date<='$filterdateceiling' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($searchparam) && !empty($filterdatefloor)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND invoice_date>='$filterdatefloor' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($searchparam) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND invoice_date<='$filterdateceiling' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($filterdatefloor)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_type ='$filterparam' AND invoice_date>='$filterdatefloor' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else if (!empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id 
                    WHERE inventory.item_type ='$filterparam' AND invoice_date<='$filterdateceiling' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_in 
                    INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id 
                    INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
                    WHERE inventory.item_type ='$filterparam' AND goods_stock_in.is_archived=0
                    ORDER BY invoice_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['invoice_no'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['invoice_date'].'</td>
                        </tr>';
                    }
                }
            }else if($filterINOUTparam == 'STOCK OUT'){
                if (!empty($searchparam) && !empty($filterdatefloor) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND stock_date>='$filterdatefloor' AND stock_date<='$filterdateceiling' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($filterdatefloor) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='$filterparam' AND stock_date>='$filterdatefloor' AND stock_date <='$filterdateceiling' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($searchparam) && !empty($filterdatefloor)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND stock_date >='$filterdatefloor'AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($searchparam) && !empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND stock_date <='$filterdateceiling'AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($searchparam)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_name LIKE '$searchparam%' AND inventory.item_type ='$filterparam' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($filterdatefloor)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='$filterparam' AND stock_date >='$filterdatefloor'AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else if (!empty($filterdateceiling)){
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='$filterparam' AND stock_date <='$filterdateceiling'AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }else{
                    $sql = "SELECT * FROM goods_stock_out 
                    INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
                    INNER JOIN goods_stockout_forms ON goods_stock_out.goods_stockout_form_id = goods_stockout_forms.goods_stockout_form_id
                    WHERE inventory.item_type ='$filterparam' AND goods_stock_out.is_archived=0
                    ORDER BY stock_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['stock_desc'].'</td>
                        <td>'.$row['item_name'].'</td>
                        <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                        <td>'.$row['stock_date'].'</td>
                        </tr>';
                    }
                }
            }
        }

        function delete_supply_stock($supply_id,$stock_type){

            if($stock_type == 'stockout'){
                $sql = "SELECT * FROM supply_stockout WHERE supply_stockout_id = $supply_id";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $temp_item_id = $row['item_id'];
                        $temp_stock_quant = $row['stock_quantity'];
                    }
                }
            }else if($stock_type == 'stockin'){
                $sql = "SELECT * FROM supply_stockin WHERE supply_stockin_id = $supply_id";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $temp_item_id = $row['item_id'];
                        $temp_stock_quant = $row['stock_quantity'];
                    }
                }
            }

            $sql = "SELECT * FROM inventory WHERE item_id = $temp_item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                }
            }

            if($stock_type == 'stockout'){
                $sql = "UPDATE inventory SET item_quantity=$temp_quant+$temp_stock_quant WHERE item_id=".$temp_item_id;
                $result = $this->connect()->query($sql);

                $sql = "DELETE FROM supply_stockout WHERE supply_stockout_id=".$supply_id;
                $result = $this->connect()->query($sql);
            }else if($stock_type == 'stockin'){
                $sql = "UPDATE inventory SET item_quantity=$temp_quant-$temp_stock_quant WHERE item_id=".$temp_item_id;
                $result = $this->connect()->query($sql);

                $sql = "DELETE FROM supply_stockin WHERE supply_stockin_id=".$supply_id;
                $result = $this->connect()->query($sql);
            }

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }
        function show_stocks_thead($filterparam,$filterINOUTparam){
            if($filterparam == "Goods" AND $filterINOUTparam == "STOCK IN"){
                echo '
                <th>Receiving Report No.</th>
                <th>Item Name</th>
                <th>Stock Quantity</th>
                <th>Current Quantity</th>
                <th>Stock Date</th>
                <th>Expiry Date</th>';
            }else if($filterparam == 'Goods'AND $filterINOUTparam == 'STOCK OUT'){
                echo '
                <th>Form Description</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Stock Date</th>';
            }else if($filterparam == 'Supply'AND $filterINOUTparam == 'STOCK IN'){
                echo '
                <th>Receiving Report No.</th>
                <th>Item Name</th>
                <th>Stock Quantity</th>
                <th>Current Quantity</th>
                <th>Stock Date</th>';
            }else if($filterparam == 'Supply'AND $filterINOUTparam == 'STOCK OUT'){
                echo '
                <th>Form Description</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Stock Date</th>';
            }
        }

        function show_stockoutinv($item_type){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type = '$item_type'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    $currentquant = $row['item_quantity'];
                    echo '<tr>
                    <td>'.$row['item_id'].'</td>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['item_quantity'].'</td>
                    <td>'.$row['item_unit'].'</td>
                    <td>
                    <input type="hidden" name="stock-id" value='.$row['item_id'].'>
                    <input type="text" style="width:15%;" id="input_value_'.$row['item_id'].'" oninput="check_stockoutvalid(this.value,this.id,'.$currentquant.')" name="stock-quant">
                    </td></tr>'; 
                    $i++;
                }
            }
        }
        
        function show_invoices($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_no LIKE '$searchparam%' AND invoice_date = '$datefilter' AND is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_no LIKE '$searchparam%' AND is_archived=0 ORDER BY invoice_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_date = '$datefilter' AND is_archived=0";
            }else{
                $sql = "SELECT * FROM goods_invoice WHERE is_archived=0 ORDER BY invoice_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['goods_invoice_id'].'</td>
                    <td>'.$row['invoice_no'].'</td>
                    <td>₱ '.$row['invoice_total_price'].'</td>
                    <td>'.$row['invoice_desc'].'</td>
                    <td>'.$row['invoice_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="invoiceView_id" value='.$row['goods_invoice_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_invoices_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_no LIKE '$searchparam%' AND invoice_date = '$datefilter' AND is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_no LIKE '$searchparam%' AND is_archived=1 ORDER BY invoice_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice WHERE invoice_date = '$datefilter' AND is_archived=1";
            }else{
                $sql = "SELECT * FROM goods_invoice WHERE is_archived=1 ORDER BY invoice_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['goods_invoice_id'].'</td>
                    <td>'.$row['invoice_no'].'</td>
                    <td>₱ '.$row['invoice_total_price'].'</td>
                    <td>'.$row['invoice_desc'].'</td>
                    <td>'.$row['invoice_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="invoiceView_id" value='.$row['goods_invoice_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function add_invoice($invoice_no,$total_price,$desc,$invoicedate){
            $supplier = str_replace('\'', '', $desc);
            $sql = "INSERT INTO goods_invoice values ('','$invoice_no',$total_price,'$supplier','$invoicedate',0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add invoice successful!";}else{return "Add invoice failed.";}
        }

        function get_lastinvoice(){
            $sql = "SELECT * FROM goods_invoice";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $invoiceid = $row["goods_invoice_id"];
                }
            }
            return $invoiceid;
        }
        function add_invoice_stockin($invoice_id,$item_id,$stock_quant,$stock_price){
            $sql = "INSERT INTO goods_stock_in values ('','$invoice_id',$item_id,$stock_quant,$stock_price,0)";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM inventory WHERE item_id = $item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                }
            }

            $sql = "UPDATE inventory SET item_quantity=$temp_quant+$stock_quant WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Add Stock IN successful!";}else{return "Add Stock IN failed.";}
        }

        function show_invoice_stocks($invoice_id){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['goods_stockin_id'].'</td>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['stock_quantity'].'</td>
                    <td>₱ '.$row['stock_price'].'</td></tr>'; 
                }
            }
        }

        function show_invoice_info($invoice_id){
            $sql = "SELECT * FROM goods_invoice
            WHERE goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <label for="invoice-num">Invoice Number:</label>
                    <input type="text" id="invoice-num" name="invoice-num" value="'.$row['invoice_no'].'" readonly><br>
                    <label for="total-price">Total Price:</label>
                    <input type="text" id="total-price" oninput="check_num(this.value,this.id)" name="total-price" style="length:50%;" value="'.$row['invoice_total_price'].'" readonly><br>
                    <label for="invoice-desc">Supplier:</label>
                    <input type="text" id="invoice-desc" name="invoice-desc" value="'.$row['invoice_desc'].'" readonly><br>
                    <label for="stock-quant">Invoice Date:</label>
                    <input type="date" id="input_value"  name="invoice-date" value="'.$row['invoice_date'].'" readonly><br>'; 
                }
            }
        }

        function show_invoice_edit($invoice_id){
            $sql = "SELECT * FROM goods_invoice
            WHERE goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <label for="invoice-num">Invoice Number:</label>
                    <input type="text" id="invoice-num" name="invoice-num" value="'.$row['invoice_no'].'" ><br>
                    <label for="total-price">Total Price:</label>
                    <input type="text" id="total-price" oninput="check_num(this.value,this.id)" name="total-price" style="length:50%;" value="'.$row['invoice_total_price'].'"><br>
                    <label for="invoice-desc">Supplier:</label>
                    <input type="text" id="invoice-desc" name="invoice-desc" value="'.$row['invoice_desc'].'"><br>
                    <label for="stock-quant">Invoice Date:</label>
                    <input type="date" id="input_value"  name="invoice-date" value="'.$row['invoice_date'].'"><br>'; 
                }
            }
        }

        function show_invoice_stocks_edit($invoice_id){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['goods_stockin_id'].'<input type=\'hidden\' name="stock-id_'.$i.'" value='.$row['goods_stockin_id'].'></td>
                    <td><select id="item-id_'.$i.'" name="item-id_'.$i.'">
                    <option value="'.$row['item_id'].'" selected hidden>'.$row['item_name'].'</option> 
                    '.$this->show_items_invoice('Goods').'  
                    </select>
                    </td>
                    <td><input type="text" oninput="check_num(this.value,this.id)" name="stock-quant_'.$i.'" value='.$row['stock_quantity'].'></td>
                    <td><input type="text" oninput="check_num(this.value,this.id)" name="stock-price_'.$i.'" value='.$row['stock_price'].'></td>
                    <td><form method="POST" onSubmit="return confirm(\'Are you sure?\');">
                    <input type="hidden" name="invoiceDelete_id" value='.$row['goods_stockin_id'].'>
                    <input type="submit" class="add-button" name="submit" value="delete"></form></td></tr>'; 
                    $i++;
                }
            }
        }

        function get_invoice_stocks_NumofItems($invoice_id){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    $i++;
                }
            }
            echo $i;
        }
        function delete_invoice_stockin($stock_id){
            $sql = "SELECT * FROM goods_stock_in WHERE goods_stockin_id = $stock_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_item_id = $row['item_id'];
                    $temp_stock_quant = $row['stock_quantity'];
                }
            }

            $sql = "SELECT * FROM inventory WHERE item_id = $temp_item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                }
            }

            $sql = "UPDATE inventory SET item_quantity=$temp_quant-$temp_stock_quant WHERE item_id=".$temp_item_id;
            $result = $this->connect()->query($sql);

            $sql = "DELETE FROM goods_stock_in WHERE goods_stockin_id=".$stock_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function edit_invoice($invoice_id,$invoice_no,$total_price,$desc,$date){
            $sql = "UPDATE goods_invoice SET invoice_no='$invoice_no',invoice_total_price=$total_price,invoice_desc='$desc',invoice_date='$date' WHERE goods_invoice_id=".$invoice_id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit item successful!";}else{return "Edit item failed.";}
        }
        function edit_invoice_stockin($stock_id,$item_id,$quant,$price){
            $sql = "SELECT * FROM goods_stock_in WHERE goods_stockin_id = $stock_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_item_id = $row['item_id'];
                    $temp_stock_quant = $row['stock_quantity'];
                }
            }

            $sql = "SELECT * FROM inventory WHERE item_id = $temp_item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                }
            }

            if($temp_item_id != $item_id){
                $sql = "UPDATE inventory SET item_quantity=$temp_quant-$temp_stock_quant WHERE item_id=".$temp_item_id;
                $result = $this->connect()->query($sql);
                $sql = "UPDATE inventory SET item_quantity=item_quantity+$quant WHERE item_id=".$item_id;
                $result = $this->connect()->query($sql);
            }else{
                $sql = "UPDATE inventory SET item_quantity=($temp_quant-$temp_stock_quant)+$quant WHERE item_id=".$item_id;
                $result = $this->connect()->query($sql);
            }

            $sql = "UPDATE goods_stock_in SET item_id=$item_id,stock_quantity=$quant,stock_price=$price WHERE goods_stockin_id=".$stock_id;
            $result = $this->connect()->query($sql);

            if($result){return "Edit item successful!";}else{return "Edit item failed.";}
        }

        function archive_invoice($invoice_id){
            $sql = "UPDATE goods_invoice SET is_archived=1 WHERE goods_invoice_id=".$invoice_id;
            $this->connect()->query($sql);
            
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $msql = "UPDATE goods_stock_in SET is_archived=1 WHERE goods_invoice_id=".$invoice_id;
                    $this->connect()->query($msql);
                }
            }

            if($result){return "Archive successful!";}else{return "Archive failed.";}
        }

        function unarchive_invoice($invoice_id){
            $sql = "UPDATE goods_invoice SET is_archived=0 WHERE goods_invoice_id=".$invoice_id;
            $this->connect()->query($sql);
            
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_stock_in.goods_invoice_id = goods_invoice.goods_invoice_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $msql = "UPDATE goods_stock_in SET is_archived=0 WHERE goods_invoice_id=".$invoice_id;
                    $this->connect()->query($msql);
                }
            }

            if($result){return "unArchive successful!";}else{return "unArchive failed.";}
        }

        function delete_invoice_entirely($invoice_id){
            $sql = "SELECT * FROM goods_stock_in INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id WHERE goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                    $temp_item_id = $row['item_id'];
                    $temp_stock_quant = $row['stock_quantity'];
                    $msql = "UPDATE inventory SET item_quantity=$temp_quant-$temp_stock_quant WHERE item_id=".$temp_item_id;
                    $this->connect()->query($msql);
                }
            }

            $sql = "DELETE FROM goods_stock_in WHERE goods_invoice_id=".$invoice_id;
            $result = $this->connect()->query($sql);

            $sql = "DELETE FROM goods_invoice WHERE goods_invoice_id=".$invoice_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function show_stockoutforms($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_reporter LIKE '$searchparam%' AND stock_date = '$datefilter' AND is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_reporter LIKE '$searchparam%'  AND is_archived=0 ORDER BY stock_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_date = '$datefilter' AND is_archived=0";
            }else{
                $sql = "SELECT * FROM goods_stockout_forms WHERE is_archived=0 ORDER BY stock_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['stock_reporter'].'</td>
                    <td>'.$row['stock_desc'].'</td>
                    <td>'.$row['stock_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="stockoutFormview_id" value='.$row['goods_stockout_form_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_stockoutforms_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_reporter LIKE '$searchparam%' AND stock_date = '$datefilter' AND is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_reporter LIKE '$searchparam%'  AND is_archived=1 ORDER BY stock_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_stockout_forms WHERE stock_date = '$datefilter' AND is_archived=1";
            }else{
                $sql = "SELECT * FROM goods_stockout_forms WHERE is_archived=1 ORDER BY stock_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['stock_reporter'].'</td>
                    <td>'.$row['stock_desc'].'</td>
                    <td>'.$row['stock_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="stockoutFormview_id" value='.$row['goods_stockout_form_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function add_stockoutform($reporter,$desc,$date){
            $sql = "INSERT INTO goods_stockout_forms values ('','$reporter','$desc','$date',0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add invoice successful!";}else{return "Add invoice failed.";}
        }

        function edit_stockoutform($id,$reporter,$desc,$date){
            $sql = "UPDATE goods_stockout_forms SET stock_reporter='$reporter',stock_desc='$desc',stock_date='$date' WHERE goods_stockout_form_id=".$id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit successful!";}else{return "Edit failed.";}
        }

        function get_laststockoutform(){
            $sql = "SELECT * FROM goods_stockout_forms";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $stockoutformid = $row["goods_stockout_form_id"];
                }
            }
            return $stockoutformid;
        }

        function show_goodsstockoutinv($item_type){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type = '$item_type'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    $currentquant = $row['item_quantity'];
                    echo '<tr>
                    <td>'.$row['item_id'].'</td>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['item_quantity'].'</td>
                    <td>'.$row['item_unit'].'</td>
                    <td>
                    <input type="hidden" name="'.$item_type.'_stock-id_'.$i.'" value='.$row['item_id'].'>
                    <input type="hidden" name="'.$item_type.'_current-quant_'.$i.'" value='.$row['item_quantity'].'>
                    <input type="text" style="width:15%;" id="'.$item_type.'_stock-quant_'.$i.'" oninput="check_stockoutvalid(this.value,this.id,'.$currentquant.')" name="'.$item_type.'_stock-quant_'.$i.'" value='.$row['item_quantity'].'>
                    </td></tr>'; 
                    $i++;
                }
            }
        }

        function get_goodsstockoutinv_maxnum($item_type){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type = '$item_type'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    $i++;
                }
            }
            echo $i;
        }

        function add_form_stockout($stockoutforms_id,$item_id,$item_quant){
            $sql = "SELECT * FROM inventory WHERE item_id = $item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                }
            }
            $stock_quant = $temp_quant - $item_quant;

            if($stock_quant != 0){
            $sql = "UPDATE inventory SET item_quantity=$item_quant WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            $sql = "INSERT INTO goods_stock_out values ('',$stockoutforms_id,$item_id,$stock_quant,0)";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM goods_stock_in WHERE item_id = $item_id AND active_quantity > 0 ORDER BY goods_stock_in.expiry_date ASC";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $active_quant = $row['active_quantity'];
                    if($stock_quant>$active_quant){
                        $stock_quant -= $active_quant;
                        $active_quant=0;
                    }else{
                        $active_quant -= $stock_quant;
                        $stock_quant=0;
                    }
                    $asql = "UPDATE goods_stock_in SET active_quantity=$active_quant WHERE goods_stockin_id=".$row['goods_stockin_id'];
                    $this->connect()->query($asql);
                    if($stock_quant==0){
                        break;
                    }
                }  
            }          

            }

            if($result){return "Add Stock OUT successful!";}else{return "Add Stock OUT failed.";}
        }

        function edit_form_stockout($stock_id,$item_id,$item_quant){
            $sql = "SELECT * FROM goods_stock_out WHERE goods_stockout_id = $stock_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $old_item_id = $row['item_id']; 
                    $old_quant = $row['stock_quantity'];
                }
            }

            $sql = "UPDATE inventory SET item_quantity=item_quantity+$old_quant WHERE item_id=".$old_item_id;
            $result = $this->connect()->query($sql);

            $sql = "UPDATE inventory SET item_quantity=item_quantity-$item_quant WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            $sql = "UPDATE goods_stock_out SET item_id=$item_id, stock_quantity=$item_quant WHERE goods_stockout_id=".$stock_id;
            $result = $this->connect()->query($sql);

            if($old_item_id != $item_id){
                $sql = "SELECT * FROM goods_stock_in WHERE item_id = $item_id AND active_quantity > 0 ORDER BY goods_stock_in.expiry_date ASC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $active_quant = $row['active_quantity'];
                        if($item_quant>$active_quant){
                            $item_quant -= $active_quant;
                            $active_quant=0;
                        }else{
                            $active_quant -= $item_quant;
                            $item_quant=0;
                        }
                        $asql = "UPDATE goods_stock_in SET active_quantity=$active_quant WHERE goods_stockin_id=".$row['goods_stockin_id'];
                        $this->connect()->query($asql);
                        if($item_quant==0){
                            break;
                        }
                    }  
                }
                $sql = "SELECT * FROM goods_stock_in WHERE item_id = $item_id AND active_quantity < stock_quantity ORDER BY goods_stock_in.expiry_date DESC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $stock_quant = $row['stock_quantity'];
                        $active_quant = $row['active_quantity'];
                        if(($old_quant+$active_quant)>$stock_quant){
                            $old_quant -= ($stock_quant-$active_quant);
                            $active_quant=$stock_quant;
                        }else{
                            $active_quant += $old_quant;
                            $old_quant=0;
                        }
                        $asql = "UPDATE goods_stock_in SET active_quantity=$active_quant WHERE goods_stockin_id=".$row['goods_stockin_id'];
                        $this->connect()->query($asql);
                        if($old_quant==0){
                            break;
                        }
                    }  
                } 
            }else if($old_quant>$item_quant){
                $diff_quant = $old_quant - $item_quant;
                $sql = "SELECT * FROM goods_stock_in WHERE item_id = $item_id AND active_quantity < stock_quantity ORDER BY goods_stock_in.expiry_date DESC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $stock_quant = $row['stock_quantity'];
                        $active_quant = $row['active_quantity'];
                        if(($diff_quant+$active_quant)>$stock_quant){
                            $diff_quant -= ($stock_quant-$active_quant);
                            $active_quant=$stock_quant;
                        }else{
                            $active_quant += $diff_quant;
                            $diff_quant=0;
                        }
                        $asql = "UPDATE goods_stock_in SET active_quantity=$active_quant WHERE goods_stockin_id=".$row['goods_stockin_id'];
                        $this->connect()->query($asql);
                        if($diff_quant==0){
                            break;
                        }
                    }  
                } 
            }else if($old_quant<$item_quant){
                $diff_quant = $item_quant - $old_quant;
                $sql = "SELECT * FROM goods_stock_in WHERE item_id = $item_id AND active_quantity > 0 ORDER BY goods_stock_in.expiry_date ASC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $active_quant = $row['active_quantity'];
                        if($diff_quant>$active_quant){
                            $diff_quant -= $active_quant;
                            $active_quant=0;
                        }else{
                            $active_quant -= $diff_quant;
                            $diff_quant=0;
                        }
                        $asql = "UPDATE goods_stock_in SET active_quantity=$active_quant WHERE goods_stockin_id=".$row['goods_stockin_id'];
                        $this->connect()->query($asql);
                        if($diff_quant==0){
                            break;
                        }
                    }  
                }
            }

            if($result){return "Edit Stock OUT successful!";}else{return "Edit Stock OUT failed.";}
        }

        function show_stockoutform_info($form_id){
            $sql = "SELECT * FROM goods_stockout_forms
            WHERE goods_stockout_form_id = $form_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <div class="input-container">
                        <label for="stockout-reporter">Stock-OUT Reporter:</label>
                        <input type="text" id="stockout-reporter" name="stockout-reporter" value="'.$row['stock_reporter'].'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="stockout-desc">Description:</label>
                        <input type="text" id="stockout-desc" name="stockout-desc" value="'.$row['stock_desc'].'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="stockout-date">Date:</label>
                        <input type="date" id="stockout-date"  name="stockout-date" value="'.$row['stock_date'].'" readonly><br>
                    </div>'; 
                }
            }
        }

        function show_stockoutform_edit($form_id){
            $sql = "SELECT * FROM goods_stockout_forms
            WHERE goods_stockout_form_id = $form_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <div class="input-container">
                        <label for="stockout-reporter">Stock-OUT Reporter:</label>
                        <select id="stockout-reporter" name="stockout-reporter">
                            <option value="'.$row['stock_reporter'].'">'.$row['stock_reporter'].'</option>
                            '.$this->show_employees_edit().'  
                        </select><br>
                    </div>
                    <div class="input-container">
                        <label for="stockout-desc">Description:</label>
                        <input type="text" id="stockout-desc" name="stockout-desc" value="'.$row['stock_desc'].'" ><br>
                    </div>
                    <div class="input-container">
                        <label for="stockout-date">Date:</label>
                        <input type="date" id="stockout-date"  name="stockout-date" value="'.$row['stock_date'].'"><br>
                    </div>'; 
                }
            }
        }

        function show_stockoutform_stocks($form_id){
            $sql = "SELECT * FROM goods_stock_out 
            INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
            WHERE goods_stockout_form_id=$form_id";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['stock_quantity'].' '.$row['item_unit'].'</td>
                    </tr>';
                }
            }
        }

        function show_stockoutform_stocks_edit($form_id){
            $sql = "SELECT * FROM goods_stock_out 
            INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id 
            WHERE goods_stockout_form_id=$form_id";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>
                    <input type="hidden" id="selected_item_'.$i.'" name="selected_item_'.$i.'" value="'.$row['item_id'].'" >
                    <select id="item-id_'.$i.'" name="item-id_'.$i.'" onchange="stockout_edit_onchange('.$i.',this.value,this.id)">
                        <option value="'.$row['item_id'].'" selected hidden>'.$row['item_name'].'</option> 
                        '.$this->show_items_invoice('Goods').'
                        '.$this->show_items_invoice('Supply').'    
                        </select>
                    </td>
                    <td><input type="text" id="stockout-iquant_'.$i.'" name="stockout-iquant_'.$i.'" value="'.$row['stock_quantity'].'" 
                    oninput="stockout_edit_onchangequant(this.id,'.$row['item_id'].',get_item_id('.$i.'),get_item_quant(get_item_id('.$i.')),'.$row['stock_quantity'].',this.value,'.$i.')"></td>
                    <input type="hidden" id="old-iquant_'.$i.'" name="old-iquant_'.$i.'" value="'.$row['stock_quantity'].'">
                    <input type="hidden" id="stockout-iid_'.$i.'" name="stockout-iid_'.$i.'" value="'.$row['goods_stockout_id'].'" >
                    </tr>';
                    $i++;
                }
            }
        }

        function get_inventory_quant(){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<input type="hidden" id="inventory_quant_'.$row['item_id'].'" name="inventory_quant_'.$row['item_id'].'" value='.$row['item_quantity'].'>';
                }
            }
        }

        function get_stockoutform_stocks_maxnum($form_id){
            $sql = "SELECT * FROM goods_stock_out WHERE goods_stockout_form_id=$form_id";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    $i++;
                }
            }
            echo $i;
        }

        function archive_stockoutform($id){
            $sql = "UPDATE goods_stockout_forms SET is_archived=1 WHERE goods_stockout_form_id=$id";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM goods_stock_out 
            WHERE goods_stockout_form_id=$id";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    while($row = $result->fetch_assoc()){
                        $msql = "UPDATE goods_stock_out SET is_archived=1 WHERE goods_stockout_form_id=$id";
                        $this->connect()->query($msql);
                    }
                }
            }

            if($result){return "Archive successful!";}else{return "Archive failed.";}
        }

        function unarchive_stockoutform($id){
            $sql = "UPDATE goods_stockout_forms SET is_archived=0 WHERE goods_stockout_form_id=$id";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM goods_stock_out 
            WHERE goods_stockout_form_id=$id";  
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    while($row = $result->fetch_assoc()){
                        $msql = "UPDATE goods_stock_out SET is_archived=0 WHERE goods_stockout_form_id=$id";
                        $this->connect()->query($msql);
                    }
                }
            }

            if($result){return "unArchive successful!";}else{return "unArchive failed.";}
        }
        function delete_stockoutform_entirely($form_id){
            $sql = "SELECT * FROM goods_stock_out INNER JOIN inventory ON goods_stock_out.item_id = inventory.item_id WHERE goods_stockout_form_id = $form_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $temp_quant = $row['item_quantity']; 
                    $temp_item_id = $row['item_id'];
                    $temp_stock_quant = $row['stock_quantity'];
                    $msql = "UPDATE inventory SET item_quantity=$temp_quant+$temp_stock_quant WHERE item_id=".$temp_item_id;
                    $this->connect()->query($msql);
                }
            }

            $sql = "DELETE FROM goods_stock_out WHERE goods_stockout_form_id=".$form_id;
            $result = $this->connect()->query($sql);

            $sql = "DELETE FROM goods_stockout_forms WHERE goods_stockout_form_id=".$form_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function add_emptystockout($stockoutforms_id,$item_id){
            $sql = "INSERT INTO goods_stock_out values ('',$stockoutforms_id,$item_id,0,0)";
            $result = $this->connect()->query($sql);
         
            if($result){return "Add Stock OUT successful!";}else{return "Add Stock OUT failed.";}
        }

        function delete_emptystockout(){
            $sql = "DELETE FROM goods_stock_out WHERE stock_quantity=0";
            $result = $this->connect()->query($sql);
         
            if($result){return "Delete Empty Stock OUT successful!";}else{return "Delete Empty Stock OUT failed.";}
        }

        function get_is_samedate_dailystockout_exists($stockout_date){
            $sql = "SELECT * FROM goods_stockout_forms WHERE stock_desc = 'Daily Stock-Out' AND stock_date='$stockout_date'";
            $result = $this->connect()->query($sql);
            $exists = false;
            if($result->num_rows > 0){
                $exists = true;
            }
            return $exists;
        }

        function show_employees(){
            $sql = "SELECT * FROM employee_profile INNER JOIN employee ON employee_profile.employee_id = employee.employee_id WHERE emp_status = 'Active'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<option value="'.$row['profile_fullname'].'">'.$row['profile_fullname'].'</option>'; 
                }
            }
        }

        function show_employees_edit(){
            $sql = "SELECT * FROM employee_profile INNER JOIN employee ON employee_profile.employee_id = employee.employee_id WHERE emp_status = 'Active'";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $optionstring="";
                while($row = $result->fetch_assoc()){
                    $optionstring=$optionstring.'<option value="'.$row['profile_fullname'].'">'.$row['profile_fullname'].'</option>'; 
                }
            }
            return $optionstring;
        }
    }
