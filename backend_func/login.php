<?php
    class loginn extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        function login($user,$pass){
            $sql = "SELECT * FROM admin_account";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $verify_user = password_verify($user, $row['acc_user']); 
                    $verify_pass = password_verify($pass, $row['acc_pass']);
                    if ($verify_user && $verify_pass) { 
                        return $row['acc_access'];
                    }
                }
            }
            return 0;
        }

        function edit_account($user,$pass,$access){
            $hashuser = password_hash($user,PASSWORD_BCRYPT); 
            $hashpass = password_hash($pass,PASSWORD_BCRYPT);
            if($access=="Admin"){
                $sql = "UPDATE admin_account SET acc_user='$hashuser',acc_pass='$hashpass' WHERE acc_id=1";
                $this->connect()->query($sql);
            }else if($access== "Regular"){
                $sql = "UPDATE admin_account SET acc_user='$hashuser',acc_pass='$hashpass' WHERE acc_id=2";
                $this->connect()->query($sql);
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

        function get_if_items_expiring(){
            $sql = "SELECT * FROM goods_stock_in WHERE active_quantity>0 AND is_archived = 0";
            $result = $this->connect()->query($sql);
            $needresup = "hidden";
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $daydiff = strtotime(date($row['expiry_date'])) - strtotime(date('Y-m-d')); 
                    $days = round($daydiff / 86400); 
                    if ($row['expiry_date'] != '0000-00-00' && $days<=3){
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

        function get_if_orders_overdue(){
            $sql = "SELECT * FROM order_slip WHERE is_archived = 0";
            $result = $this->connect()->query($sql);
            $needresup = "hidden";
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $daydiff = strtotime(date($row['delivery_date'])) - strtotime(date('Y-m-d')); 
                    $days = round($daydiff / 86400); 
                    if ($row['delivery_date'] != '0000-00-00' && $days<0){
                        $needresup = "";
                    }
                }
            }
            echo $needresup;
            if($result){return "get if overdue successful!";}else{return "get if overdue failed.";}
        }

        function show_orders_overdue(){
            $sql = "SELECT * FROM order_slip WHERE is_archived = 0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $daydiff = strtotime(date($row['delivery_date'])) - strtotime(date('Y-m-d')); 
                    $days = round($daydiff / 86400);
                    if ($days<0){
                        $item_status = "Overdue by ".abs($days)." days";
                    }

                    if ($row['delivery_date'] != '0000-00-00' && $days<0){
                        echo '
                        <b>Order Number:</b> '.$row['order_no'].' |  
                        <b>Supplier:</b> '.$row['order_desc'].' |  
                        <b>Delivery Date:</b> '.$row['delivery_date'].' |  
                        <b>Status:</b> '.$item_status.'<br>';
                    }
                }
            }
        }

        function show_items_expiring(){
            $sql = "SELECT * FROM goods_stock_in LEFT JOIN inventory ON goods_stock_in.item_id=inventory.item_id WHERE goods_stock_in.is_archived = 0";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $daydiff = strtotime(date($row['expiry_date'])) - strtotime(date('Y-m-d')); 
                    $days = round($daydiff / 86400);
                    if ($days<=0){
                        $item_status = "Expired";
                    }elseif ($days <= 3){
                        $item_status = "Close to Expiring";
                    }

                    if ($row['expiry_date'] != '0000-00-00' && $days<= 3){
                        echo '
                        <b>Name:</b> '.$row['item_name'].' |  
                        <b>Quantity:</b> '.$row['active_quantity'].' |  
                        <b>Expiry Date:</b> '.$row['expiry_date'].' |  
                        <b>Status:</b> '.$item_status.'<br>';
                    }
                }
            }
        }
        
    }
