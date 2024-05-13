<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class archive extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        function show_archv($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM item_archive INNER JOIN inventory ON item_archive.item_id = inventory.item_id WHERE is_archived = 1 AND item_name LIKE '$searchparam%'";
            }else{
                $sql = "SELECT * FROM item_archive INNER JOIN inventory ON item_archive.item_id = inventory.item_id WHERE is_archived = 1";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <tr>
                    <td>'.$row['item_name'].'</td>
                    <td>'.$row['date_archived'].'</td>
                    <td><form method="POST" onSubmit="return confirm(\'Are you sure?\');">
                    <input type="hidden" name="invEdit_id" value='.$row['item_id'].'>
                    <input class="add-button" type="submit" name="submit" value="reactivate">
                    <input class="add-button" type="submit" name="submit" value="delete"></form></td></tr>'; 
                }
            }
        }
        function unarchive_item($item_id){
            
            $sql = "UPDATE inventory SET is_archived=0 WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Reactivation successful!";}else{return "Reactivation failed.";}
        }
        function delete_item($item_id){
            $sql = "DELETE FROM item_archive WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);
            $sql = "DELETE FROM inventory WHERE item_id=".$item_id;
            $result = $this->connect()->query($sql);

            if($result){return "Delete successful!";}else{return "Delete failed.";}
        }
    }
