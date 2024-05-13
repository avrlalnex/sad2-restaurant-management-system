<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class inventory extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        function add_item($item_name,$item_type,$item_unit,$item_value){
            $sql = "INSERT INTO inventory values ('','$item_name','$item_type',0,'$item_unit','$item_value',0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add item successful!";}else{return "Add item failed.";}
        }
        function show_inv($searchparam,$filterparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM inventory WHERE item_name LIKE '$searchparam%' AND is_archived = 0 AND item_type='$filterparam'";
            }else{
                $sql = "SELECT * FROM inventory WHERE is_archived = 0 AND item_type='$filterparam'";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if ($row['item_quantity']<=0){
                        $item_status = "Out of Stock";
                    }elseif ($row['item_quantity'] <= $row['item_restock']){
                        $item_status = "Needs Resupply";
                    }else{
                        $item_status = "In Stock";
                    }
                    echo '<tr>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['item_type'].'</td>
                    <td>'.$row['item_quantity'].'</td>
                    <td>'.$row['item_unit'].'</td>
                    <td>'.$row['item_restock'].'</td>
                    <td>'.$item_status.'</td>
                    <td><form method="POST">
                    <input type="hidden" name="invEdit_id" value='.$row['item_id'].'>
                    <input type="submit" class="add-button" name="submit" value="edit">
                    <input type="submit" class="add-button" name="submit" value="archive" onclick="return confirm(\'Are you sure?\');"></form></td></tr>'; 
                }
            }
        }
        function edit_item($item_id,$item_name,$item_type,$item_unit,$item_value){
            $sql = "UPDATE inventory SET item_name='$item_name',item_type='$item_type',item_unit='$item_unit',item_restock=$item_value WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit item successful!";}else{return "Edit item failed.";}
        }
        function archive_item($item_id){
            $sql = "SELECT * FROM item_archive WHERE item_id = $item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $sql = "UPDATE item_archive SET date_archived=NOW() WHERE item_id=".$item_id;
                $result = $this->connect()->query($sql);
            }else{
                $sql = "INSERT INTO item_archive values ('',$item_id,NOW())";
                $result = $this->connect()->query($sql);
            }
            $sql = "UPDATE inventory SET is_archived=1 WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Archive item successful!";}else{return "Archive item failed.";}
        }

        function show_edit($item_id){
            $sql = "SELECT * FROM inventory WHERE item_id = $item_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <form method="POST">
                    <label for="item-name">Item Name:</label>
                    <input type="text" id="item-name" name="item-name" value="'.$row['item_name'].'" ><br>
                    <label for="item-type">Item Type:</label>
                    <select id="item-type" name="item-type">
                        <option value="'.$row['item_type'].'" selected hidden>'.$row['item_type'].'</option>
                        <option value="Goods">Goods</option>
                        <option value="Supply">Supply</option>
                    </select><br>
                    <label for="item-unit">Unit of Measure:</label>
                    <input type="text" id="item-unit" name="item-unit" value='.$row['item_unit'].' ><br>
                    <label for="item-value">Restock Point:</label>
                    <input id="input_value" type="text" name="item-value" value='.$row['item_restock'].' oninput="check_num(this.value,this.id)" ><br>
                    <input class="add-button" type="submit" name="submit" value="Save">
                    </form>
                    '; 
                }
            }
        }

        function get_if_items_need_resup(){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0";
            $result = $this->connect()->query($sql);
            $needresup = "hidden";
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if ($row['item_quantity'] <= $row['item_restock']){
                        $needresup = "";
                    }
                }
            }
            echo $needresup;
            if($result){return "get if need resup successful!";}else{return "get if need resup failed.";}
        }

        function show_items_need_resup(){
            $sql = "SELECT * FROM inventory WHERE is_archived = 0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if ($row['item_quantity']<=0){
                        $item_status = "Out of Stock";
                    }elseif ($row['item_quantity'] <= $row['item_restock']){
                        $item_status = "Needs Resupply";
                    }

                    if ($row['item_quantity'] <= $row['item_restock']){
                        echo '
                        <b>Name:</b> '.$row['item_name'].' |  
                        <b>Quantity:</b> '.$row['item_quantity'].' |  
                        <b>Restock Point:</b> '.$row['item_restock'].' |  
                        <b>Status:</b> '.$item_status.'<br>';
                    }
                }
            }
        }
    }
