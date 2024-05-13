<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class employees extends sad_db{
        function checkdb(){
            if ($this->connect()->connect_error) {
                return "Database Connection Failed: " . $conn->connect_error;
            }else{return "Database Connected Successfully";}
        }
        function show_employees($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM employee INNER JOIN employee_profile ON employee.employee_id = employee_profile.employee_id WHERE emp_status='Active' AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'";
            }else{
                $sql = "SELECT * FROM employee INNER JOIN employee_profile ON employee.employee_id = employee_profile.employee_id WHERE emp_status='Active'";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $dateOfBirth = $row['profile_bday'];
                    $today = date("Y-m-d");
                    $diff = date_diff(date_create($dateOfBirth), date_create($today));
                    $age = $diff->format('%y');
                    echo '<tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>'.$age.'</td>
                    <td>'.$row['position'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="empView_id" value='.$row['employee_id'].'>
                    <input type= "submit" class="add-btn" name="submit" value="View Profile">
                    <input type= "submit" class="add-btn" name="submit" value="View Salary">
                    <input type= "submit" class="add-btn" name="submit" value="Archive" Onclick="return confirm(\'Are you sure?\');">
                    </form></td>
                    </tr>';
                }
            }
        }
        function add_employee($firstname,$lastname,$prof_cnum,$address,$bday,$gender,$position,$prof_type){
            $fullname = $firstname." ".$lastname;
            $sql = "INSERT INTO employee values ('','$lastname','$firstname','$position','Active')";
            $result = $this->connect()->query($sql);
            $sql = "SELECT employee.employee_id FROM employee LEFT JOIN employee_profile ON employee.employee_id=employee_profile.employee_id WHERE employee_profile.employee_id IS NULL";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rowid = $row['employee_id'];
                }
            }
            $sql = "INSERT INTO employee_profile values ($rowid,'$prof_type','$fullname',$prof_cnum,'$address','$bday','$gender',NOW(),NOW())";
            $result = $this->connect()->query($sql);

            if($result){return "Add profile successful!";}else{return "Add profile failed.";}
        }

        function add_employee_salary($salary,$reghours){
            $sql = "SELECT employee.employee_id FROM employee LEFT JOIN salary_details ON employee.employee_id=salary_details.employee_id WHERE salary_details.employee_id IS NULL";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rowid = $row['employee_id'];
                }
            }

            $sql = "INSERT INTO salary_details values ($rowid,$salary,$reghours,0,0,0,0,0)";
            $result = $this->connect()->query($sql);
            if($result){return "Add profile salary successful!";}else{return "Add profile salary failed.";}
        }

        function add_employee_emergency_contact($emrgncy_name,$emrgncy_cnum,$emrgncy_address,$emrgncy_relation){
            $sql = "SELECT employee.employee_id FROM employee LEFT JOIN employee_emergency_contact ON employee.employee_id=employee_emergency_contact.employee_id WHERE employee_emergency_contact.employee_id IS NULL";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rowid = $row['employee_id'];
                }
            }

            $sql = "INSERT INTO employee_emergency_contact values ($rowid,'$emrgncy_name',$emrgncy_cnum,'$emrgncy_address','$emrgncy_relation')";
            $result = $this->connect()->query($sql);
            if($result){return "Add profile salary successful!";}else{return "Add profile salary failed.";}
        }

        function show_employee_info_edit($employee_id){
            $sql = "SELECT * FROM employee INNER JOIN employee_profile ON employee.employee_id = employee_profile.employee_id WHERE employee.employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <div class="input-container">
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first-name" value="'.$row['firstname'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last-name" value="'.$row['lastname'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="phone-number">Phone Number:</label>
                    <input type="text" id="phone-number" name="phone-number" oninput="check_num(this.value,this.id)" value="'.$row['profile_cnum'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="'.$row['profile_fulladdress'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" value="'.$row['profile_bday'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                    <option value="'.$row['profile_gender'].'" selected hidden>'.$row['profile_gender'].'</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    </select><br>
                    </div>
                    <div class="input-container">
                    <label for="position">Position:</label>
                    <input type="text" id="position" name="position" value="'.$row['position'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="prof-type">Profile Type:</label>
                    <select id="prof-type" name="prof-type">
                        <option value="'.$row['profile_type'].'" selected hidden>'.$row['profile_type'].'</option>
                        <option value="Regular">Regular</option>
                        <option value="Admin">Admin</option>
                    </select><br>
                    ';
                }
            }
        }

        function show_employee_info($employee_id){
            $sql = "SELECT * FROM employee INNER JOIN employee_profile ON employee.employee_id = employee_profile.employee_id WHERE employee_profile.employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['profile_fullname'].'</td>
                    <td>'.$row['profile_cnum'].'</td>
                    <td>'.$row['profile_fulladdress'].'</td>
                    <td>'.$row['profile_bday'].'</td>
                    <td>'.$row['profile_gender'].'</td>
                    <td>'.$row['position'].'</td>
                    </tr>';
                }
            }
        }

        function show_employee_info_emergency($employee_id){
            $sql = "SELECT * FROM employee INNER JOIN employee_emergency_contact ON employee.employee_id = employee_emergency_contact.employee_id WHERE employee_emergency_contact.employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['emrgncy_name'].'</td>
                    <td>'.$row['emrgncy_cnum'].'</td>
                    <td>'.$row['emrgncy_address'].'</td>
                    <td>'.$row['emrgncy_relation'].'</td>
                    </tr>';
                }
            }
        }

        function show_employee_info_emergency_edit($employee_id){
            $sql = "SELECT * FROM employee INNER JOIN employee_emergency_contact ON employee.employee_id = employee_emergency_contact.employee_id WHERE employee_emergency_contact.employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <div class="input-container">
                    <label for="emergency-full-name">Full Name:</label>
                    <input type="text" id="emergency-full-name" name="emergency-full-name" value="'.$row['emrgncy_name'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="emergency-phone-number">Phone Number:</label>
                    <input type="text" id="emergency-phone-number" name="emergency-phone-number" value="'.$row['emrgncy_cnum'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="emergency-address">Address:</label>
                    <input type="text" id="emergency-address" name="emergency-address" value="'.$row['emrgncy_address'].'" ><br>
                    </div>
                    <div class="input-container">
                    <label for="emergency-relationship">Relationship:</label>
                    <input type="text" id="emergency-relationship" name="emergency-relationship" value="'.$row['emrgncy_relation'].'" ><br>';
                }
            }
        }

        function show_employee_salary_details($employee_id,$mode){
            $sql = "SELECT * FROM salary_details INNER JOIN employee ON employee.employee_id = salary_details.employee_id WHERE salary_details.employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if ($mode == 'edit'){
                    echo '
                    <input class="add-button" type="submit" name="submit" value="Cancel Edit">
                    <div class="input-container">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" value="'.$row['firstname'].' '.$row['lastname'].'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="salary">Salary:</label>
                        <input type="text" id="salary" name="salary" oninput="check_num(this.value,this.id)" value="₱'.number_format($row['salary'],2).'" ><br>
                    </div>
                    <div class="input-container">
                        <label for="reghours">Regular Hours:</label>
                        <input type="text" id="reghours" name="reghours" oninput="check_num(this.value,this.id)" value="'.$row['regular_hours'].'" ><br>
                    </div>
                    <div class="input-container">
                        <label for="severance">Severance:</label>
                        <input type="text" id="severance" name="severance" oninput="check_num(this.value,this.id)" value="₱'.number_format($row['severance'],2).'" ><br>
                    </div>
                    <input class="add-button" type="submit" name="submit" value="Save">
                    ';
                    }else if ($mode == 'view'){
                    echo '
                    <input class="add-button" type="submit" name="submit" value="Go to Edit Mode">
                    <div class="input-container">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" value="'.$row['firstname'].' '.$row['lastname'].'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="salary">Salary:</label>
                        <input type="text" id="salary" name="salary" oninput="check_num(this.value,this.id)" value="₱'.number_format($row['salary'],2).'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="reghours">Regular Hours:</label>
                        <input type="text" id="reghours" name="reghours" oninput="check_num(this.value,this.id)" value="'.$row['regular_hours'].'" readonly><br>
                    </div>
                    <div class="input-container">
                        <label for="severance">Severance:</label>
                        <input type="text" id="severance" name="severance" oninput="check_num(this.value,this.id)" value="₱'.number_format($row['severance'],2).'" readonly><br>
                    </div>
                    ';
                    }
                }
            }

        }

        function edit_employee_salary($emp_id,$salary,$reghours,$severance){
            $sql = "UPDATE salary_details SET salary=$salary,regular_hours=$reghours,severance=$severance WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);
            if($result){return "Edit salary successful!";}else{return "Edit salary failed.";}
        }

        function edit_employee($emp_id,$firstname,$lastname,$prof_cnum,$address,$bday,$gender,$position,$prof_type){
            $fullname = $firstname." ".$lastname;
            $sql = "UPDATE employee SET lastname='$lastname',firstname='$firstname',position='$position' WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);

            $sql = "UPDATE employee_profile SET profile_type='$prof_type',profile_fullname='$fullname',profile_cnum=$prof_cnum
            ,profile_fulladdress='$address',profile_bday='$bday',profile_gender='$gender',last_updated=NOW() 
            WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);

            if($result){return "edit profile successful!";}else{return "edit profile failed.";}
        }

        function edit_employee_emergency_contact($emp_id,$emrgncy_name,$emrgncy_cnum,$emrgncy_address,$emrgncy_relation){
            $sql = "UPDATE employee_emergency_contact SET emrgncy_name='$emrgncy_name',emrgncy_cnum=$emrgncy_cnum,
            emrgncy_address='$emrgncy_address',emrgncy_relation='$emrgncy_relation' WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);
            if($result){return "edit emergency contact successful!";}else{return "edit emergency contact failed.";}
        }

        function show_employee_archive($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM employee INNER JOIN employee_archive ON employee.employee_id = employee_archive.employee_id WHERE emp_status='Inactive' AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'";
            }else{
                $sql = "SELECT * FROM employee INNER JOIN employee_archive ON employee.employee_id = employee_archive.employee_id WHERE emp_status='Inactive'";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>'.$row['date_archived'].'</td>
                    <td>'.$row['date_reactivated'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="empArchv_id" value='.$row['employee_id'].'>
                    <input type= "submit" class="add-btn" name="submit" value="Un-Archive"></form></td>
                    </tr>';
                }
            }
        }

        function set_employee_archive($emp_id){
            $sql = "UPDATE employee SET emp_status='Inactive' WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM employee_archive INNER JOIN employee ON employee_archive.employee_id=employee.employee_id WHERE employee_archive.employee_id=$emp_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){   
                $msql = "UPDATE employee_archive SET date_archived=NOW() WHERE employee_id=".$emp_id;
                $result = $this->connect()->query($msql); 
            }else{
                $sql = "INSERT INTO employee_archive values ('',$emp_id,NOW(),'')";
                $result = $this->connect()->query($sql);
            }
            if($result){return "archive successful!";}else{return "archive failed.";}
        }

        function set_employee_unarchive($emp_id){
            $sql = "UPDATE employee SET emp_status='Active' WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);

            $sql = "UPDATE employee_archive SET date_reactivated=NOW() WHERE employee_id=".$emp_id;
            $result = $this->connect()->query($sql);
            if($result){return "unarchive successful!";}else{return "unarchive failed.";}
        }
        
    }
