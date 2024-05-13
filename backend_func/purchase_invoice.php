<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class purchase_invoice extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        
        function get_orderslip_info($order_id,$type){
            $orderslip_info = "";
            $sql = "SELECT * FROM order_slip
            WHERE order_id = $order_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($type=='order_no'){
                        $orderslip_info = $row['order_no'];
                    }else if($type=='order_desc'){
                        $orderslip_info = $row['order_desc'];
                    }else if($type=='order_date'){
                        $orderslip_info = $row['order_date'];
                    }else if($type=='delivery_date'){
                        $orderslip_info = $row['delivery_date'];
                    }else if($type=='total_price'){
                        $orderslip_info = $row['total_price'];
                    }else{
                        $orderslip_info = 'error';
                    }
                }
            }
            return $orderslip_info;
        }

        function get_invoice_info($invoice_id,$type){
            $invoice_info = "";
            $sql = "SELECT * FROM goods_invoice
            WHERE goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($type=='invoice_no'){
                        $invoice_info = $row['invoice_no'];
                    }else if($type=='invoice_date'){
                        $invoice_info = $row['invoice_date'];
                    }else{
                        $invoice_info = 'error';
                    }
                }
            }
            return $invoice_info;
        }

        function show_orderslip_items_invoice($order_id){
            $sql = "SELECT * FROM order_items
            INNER JOIN order_slip ON order_items.order_id = order_slip.order_id
            INNER JOIN inventory ON order_items.item_id = inventory.item_id
            WHERE order_items.order_id = $order_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td><input type="text" oninput="check_num_minimum(this.value,this.id)" id="orderitem-quant_'.$i.'" name="orderitem-quant_'.$i.'" value='.$row['quantity'].'></td>
                    <td><input type="date" id="invoice-expiry_'.$i.'" name="invoice-expiry_'.$i.'" value='.null.'>
                    <input type="hidden" name="orderitem-id_'.$i.'" value='.$row['order_item_id'].'></td>
                    </tr>'; 
                    $i++;
                }
                echo '<input type="hidden" id="orderslip-numofitems" name="orderslip-numofitems" value='.$i.' readonly>';
            }
        }

        function show_invoice_items($invoice_id){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_invoice.goods_invoice_id = goods_stock_in.goods_invoice_id
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['stock_quantity'].'</td>
                    <td>'.$row['active_quantity'].'</td>
                    <td>'.$row['expiry_date'].'</td>
                    </tr>'; 
                }
            }
        }

        function show_invoice_items_edit($invoice_id){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN goods_invoice ON goods_invoice.goods_invoice_id = goods_stock_in.goods_invoice_id
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_invoice_id = $invoice_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td><input type="text" oninput="check_num_minimum(this.value,this.id);invoice_check_stockactive('.$row['stock_quantity'].','.$row['active_quantity'].','.$i.')" id="invoiceitem-quant_'.$i.'" name="invoiceitem-quant_'.$i.'" value='.$row['stock_quantity'].'></td>
                    <td><input type="text" oninput="check_num(this.value,this.id);invoice_check_stockactive('.$row['stock_quantity'].','.$row['active_quantity'].','.$i.')" id="activeitem-quant_'.$i.'" name="activeitem-quant_'.$i.'" value='.$row['active_quantity'].'></td>
                    <td><input type="date" id="invoice-expiry_'.$i.'" name="invoice-expiry_'.$i.'" value='.$row['expiry_date'].'>
                    <input type="hidden" name="invoiceitem-id_'.$i.'" value='.$row['goods_stockin_id'].'></td>
                    </tr>'; 
                    $i++;
                }
                echo '<input type="hidden" id="orderslip-numofitems" name="orderslip-numofitems" value='.$i.' readonly>';
            }
        }
        function add_invoice($order_id,$invoice_no,$invoice_date){
            $sql = "INSERT INTO goods_invoice values ('',$order_id,'$invoice_no','$invoice_date',0)";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE order_slip SET is_archived=1 WHERE order_id=$order_id";
            $result = $this->connect()->query($sql);
            if($result){return "Add Invoice successful!";}else{return "Add Invoice failed.";}
        }

        function archive_orderslip($order_id){
            $sql = "UPDATE goods_invoice SET invoice_no='$invoice_no',invoice_date='$invoice_date' WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            if($result){return "Edit Invoice successful!";}else{return "Edit Invoice failed.";}
        }

        function edit_invoice($goods_invoice_id,$invoice_no,$invoice_date){
            $sql = "UPDATE goods_invoice SET invoice_no='$invoice_no',invoice_date='$invoice_date' WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            if($result){return "Edit Invoice successful!";}else{return "Edit Invoice failed.";}
        }

        function archive_invoice($goods_invoice_id){
            $sql = "UPDATE goods_invoice SET is_archived=1 WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            $sql = "UPDATE goods_stock_in SET is_archived=1 WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            if($result){return "Archive Invoice successful!";}else{return "Archive Invoice failed.";}
        }

        function unarchive_invoice($goods_invoice_id){
            $sql = "UPDATE goods_invoice SET is_archived=0 WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            $sql = "UPDATE goods_stock_in SET is_archived=0 WHERE goods_invoice_id=$goods_invoice_id";
            $result = $this->connect()->query($sql);
            if($result){return "Archive Invoice successful!";}else{return "Archive Invoice failed.";}
        }

        function get_last_invoice(){
            $sql = "SELECT * FROM goods_invoice";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $goods_invoice_id = $row["goods_invoice_id"];
                }
            }
            return $goods_invoice_id;
        }

        function add_stock_in($goods_invoice_id,$order_item_id,$invoice_quant,$expiry_date){
            $sql = "SELECT * FROM order_items
            INNER JOIN inventory ON order_items.item_id = inventory.item_id
            WHERE order_items.order_item_id = $order_item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $item_id = $row['item_id'];
                    $temp_quant = $row['item_quantity'];
                }
            }
            $sql = "INSERT INTO goods_stock_in values ('',$goods_invoice_id,$item_id,$invoice_quant,$invoice_quant,'$expiry_date',0)";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE inventory SET item_quantity=$temp_quant+$invoice_quant WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Add Stock In successful!";}else{return "Add Stock In failed.";}
        }

        function edit_stock_in($stockin_id,$stock_quant,$active_quant,$expiry_date){
            $sql = "SELECT * FROM goods_stock_in
            INNER JOIN inventory ON goods_stock_in.item_id = inventory.item_id
            WHERE goods_stock_in.goods_stockin_id = $stockin_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $item_id = $row['item_id'];
                    $old_active = $row['active_quantity'];
                    $temp_quant = $row['item_quantity'];
                }
            }

            $sql = "UPDATE goods_stock_in SET stock_quantity=$stock_quant,active_quantity=$active_quant,expiry_date='$expiry_date' WHERE goods_stockin_id=$stockin_id";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE inventory SET item_quantity=$temp_quant-($old_active-$active_quant) WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Edit Stock In successful!";}else{return "Edit Stock In failed.";}
        }

        function show_invoices($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_no LIKE '$searchparam%' AND invoice_date = '$datefilter' AND goods_invoice.is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_no LIKE '$searchparam%' AND goods_invoice.is_archived=0 ORDER BY invoice_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_date = '$datefilter' AND goods_invoice.is_archived=0";
            }else{
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE goods_invoice.is_archived=0 ORDER BY invoice_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['order_no'].'</td>
                    <td>'.$row['invoice_no'].'</td>
                    <td>'.$row['order_desc'].'</td>
                    <td>'.$row['invoice_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="invoiceView_id" value='.$row['goods_invoice_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_invoices_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_no LIKE '$searchparam%' AND invoice_date = '$datefilter' AND goods_invoice.is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_no LIKE '$searchparam%' AND goods_invoice.is_archived=1 ORDER BY invoice_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE invoice_date = '$datefilter' AND goods_invoice.is_archived=1";
            }else{
                $sql = "SELECT * FROM goods_invoice LEFT JOIN order_slip ON goods_invoice.order_id = order_slip.order_id
                WHERE goods_invoice.is_archived=1 ORDER BY invoice_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['order_no'].'</td>
                    <td>'.$row['invoice_no'].'</td>
                    <td>'.$row['order_desc'].'</td>
                    <td>'.$row['invoice_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="invoiceView_id" value='.$row['goods_invoice_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_purchase_orders_select($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM order_slip WHERE order_no LIKE '$searchparam%' AND order_date = '$datefilter' AND is_archived=0";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM order_slip WHERE order_no LIKE '$searchparam%' AND is_archived=0 ORDER BY order_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM order_slip WHERE order_date = '$datefilter' AND is_archived=0";
            }else{
                $sql = "SELECT * FROM order_slip WHERE is_archived=0 ORDER BY order_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['order_no'].'</td>
                    <td>'.$row['order_desc'].'</td>
                    <td>'.$row['order_date'].'</td>
                    <td>'.$row['delivery_date'].'</td>
                    <td>₱'.number_format($row['total_price'],2).'</td>
                    <td><form method="POST">
                    <input type="hidden" name="purchase_orderView_id" value='.$row['order_id'].'>
                    <input type="submit" class="add-button" name="submit" value="select"></form></td></tr>'; 
                }
            }
        }
    }
