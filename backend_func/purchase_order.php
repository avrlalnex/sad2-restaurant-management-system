<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class purchase_order extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        
        function add_orderslip($order_no,$desc,$order_date,$delivery_date,$total_price){
            $supplier = str_replace('\'', '', $desc);
            $sql = "INSERT INTO order_slip values ('','$order_no','$supplier','$order_date','$delivery_date',$total_price,0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add order slip successful!";}else{return "Add order slip failed.";}
        }

        function edit_orderslip($order_id,$order_no,$desc,$order_date,$delivery_date,$total_price){
            $supplier = str_replace('\'', '', $desc);
            $sql = "UPDATE order_slip SET order_no='$order_no',order_desc='$supplier',order_date='$order_date',delivery_date='$delivery_date',total_price=$total_price WHERE order_id=".$order_id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit order slip successful!";}else{return "Edit order slip failed.";}
        }

        function delete_orderslip($order_id){
            $sql = "DELETE FROM order_slip WHERE order_id=".$order_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function delete_order_item($order_item_id){
            $sql = "DELETE FROM order_items WHERE order_item_id=".$order_item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function delete_order_item_empty($order_id){
            $sql = "DELETE FROM order_items WHERE order_id=$order_id AND quantity=0";
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }

        function get_last_orderslip(){
            $sql = "SELECT * FROM order_slip";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $order_id = $row["order_id"];
                }
            }
            return $order_id;
        }
        
        function add_order_item($order_id,$item_id,$order_quant,$total_price){
            $sql = "INSERT INTO order_items values ('',$order_id,$item_id,$order_quant,$total_price,0)";
            $result = $this->connect()->query($sql);

            if($result){return "Add Order Slip Item successful!";}else{return "Add Order Slip Item failed.";}
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

        function show_purchase_orders($searchparam,$datefilter){
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
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
        }

        function show_purchase_orders_archive($searchparam,$datefilter){
            if (!empty($searchparam) && !empty($datefilter)){
                $sql = "SELECT * FROM order_slip WHERE order_no LIKE '$searchparam%' AND order_date = '$datefilter' AND is_archived=1";
            }else if (!empty($searchparam)){
                $sql = "SELECT * FROM order_slip WHERE order_no LIKE '$searchparam%' AND is_archived=1 ORDER BY order_date DESC";
            }else if (!empty($datefilter)){
                $sql = "SELECT * FROM order_slip WHERE order_date = '$datefilter' AND is_archived=1";
            }else{
                $sql = "SELECT * FROM order_slip WHERE is_archived=1 ORDER BY order_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['order_no'].'</td>
                    <td>'.$row['order_desc'].'</td>
                    <td>'.$row['order_date'].'</td>
                    <td>'.$row['delivery_date'].'</td>
                    <td>₱ '.$row['total_price'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="purchase_orderView_id" value='.$row['order_id'].'>
                    <input type="submit" class="add-button" name="submit" value="view"></form></td></tr>'; 
                }
            }
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

        function show_orderslip_items($order_id){
            $sql = "SELECT * FROM order_items
            INNER JOIN order_slip ON order_items.order_id = order_slip.order_id
            INNER JOIN inventory ON order_items.item_id = inventory.item_id
            WHERE order_items.order_id = $order_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['quantity'].'</td>
                    <td>₱ '.$row['item_total_price'].'</td></tr>'; 
                }
            }
        }

        function show_orderslip_items_edit($order_id){
            $sql = "SELECT * FROM order_items
            INNER JOIN order_slip ON order_items.order_id = order_slip.order_id
            INNER JOIN inventory ON order_items.item_id = inventory.item_id
            WHERE order_items.order_id = $order_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td><select id="item-id_'.$i.'" name="item-id_'.$i.'">
                    <option value="'.$row['item_id'].'" selected hidden>'.$row['item_name'].'</option> 
                    '.$this->show_items_orderitems('Goods').'
                    '.$this->show_items_orderitems('Supply').'  
                    </select>
                    </td>
                    <td><input type="text" oninput="check_num_minimum(this.value,this.id)" id="orderitem-quant_'.$i.'" name="orderitem-quant_'.$i.'" value='.$row['quantity'].'></td>
                    <td><input type="text" oninput="check_num(this.value,this.id)" id="orderitem-price_'.$i.'" name="orderitem-price_'.$i.'" value='.$row['item_total_price'].'></td>
                    <td><input type="hidden" name="orderitem-id_'.$i.'" value='.$row['order_item_id'].'><form method="POST" onSubmit="return confirm(\'Are you sure you want to delete?\');">
                    <input type="hidden" name="orderitemDelete_id" value='.$row['order_item_id'].'>
                    <input type="submit" class="add-button" name="submit" value="delete"></form></td></tr>'; 
                    $i++;
                }
                echo '<input type="hidden" id="orderslip-numofitems" name="orderslip-numofitems" value='.($i).' readonly>';
            }
        }

        function show_items_orderitems($itemtype){
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
        
        function edit_order_item($order_item_id,$item_id,$order_quant,$total_price){

            $sql = "UPDATE order_items SET item_id=$item_id,quantity=$order_quant,item_total_price=$total_price WHERE order_item_id=".$order_item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Edit item successful!";}else{return "Edit item failed.";}
        }

        

    }
