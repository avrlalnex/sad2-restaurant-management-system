<?php
    if($_SESSION["access"]!="Admin" && $_SESSION["access"]!="Regular"){
        header("Location: login_page.php");
        exit();
    }else if($_SESSION["access"]=="Regular"){
        header("Location: lg.php");
        exit();
    }
    class payroll extends sad_db{
        function show_employee_name($employee_id){
            $sql = "SELECT * FROM employee WHERE employee_id = $employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo ''.$row['firstname'].' '.$row['lastname'].''; 
                }
            }
        }

        function show_employees_addpayroll($searchparam){
            if (!empty($searchparam)){
                $sql = "SELECT * FROM
                INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                WHERE emp_status='Active' AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'";
            }else{
                $sql = "SELECT * FROM employee 
                INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                WHERE emp_status='Active'";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    
                    echo '<tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>'.$row['position'].'</td>
                    <td>₱'.number_format($row['salary'],2).'</td>
                    <td>'.$row['regular_hours'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="addpayroll_empid" value='.$row['employee_id'].'>
                    <input type= "submit" class="add-btn" name="submit" value="Select"></form></td>
                    </tr>';
                }
            }
        }

        function show_employees_generate(){
            $sql = "SELECT * FROM employee INNER JOIN employee_profile ON employee.employee_id = employee_profile.employee_id WHERE emp_status='Active'";            
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                $i=0;
                while($row = $result->fetch_assoc()){
                    echo '<tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'<input type="hidden" name="emp_id_'.$i.'" value='.$row['employee_id'].'></td>
                    <td>'.$row['position'].'</td>
                    <td><input type="checkbox" id="emp_generate_'.$i.'" name="emp_generate_'.$i.'" value="1" checked> </td>
                    </tr>';
                    $i++;
                }
                echo '<input type="hidden" name="max_emp" value='.$i.'>';
            }
        }
        
        function generate_payroll($start_date,$end_date,$emp_id){
            $msql = "SELECT * FROM payroll_deduction_rates";
            $result = $this->connect()->query($msql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row['deduc_rate_id']==1){
                        $Philhealth_rate = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']==2){
                        $SSS_rate = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']==3){
                        $PAGIBIG_rate = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']==4){
                        $tax_rate = $row['deduc_rate'];
                    }
                }
            }

            $msql = "SELECT * FROM employee 
            INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
            WHERE emp_status='Active' AND employee.employee_id=$emp_id";
            
            $result = $this->connect()->query($msql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){ 
                    $employee_id = $row['employee_id'];
                    $payroll_salary = $row['salary'];
                    $regular_hours = $row['regular_hours'];
                    $det_tot_deduc = $row['total_deduction'];
                    $det_sal_deduc = $row['deduction_per_sal'];
                    $det_bon_pay = $row['bonus_pay'];
                    $det_bon_hrs = $row['bonus_hours'];

                    $sql = "INSERT INTO payroll values ('',$employee_id,'$start_date','$end_date',0)";
                    $this->connect()->query($sql);

                    $sql = "SELECT payroll.payroll_id FROM payroll LEFT JOIN payroll_details ON payroll.payroll_id=payroll_details.payroll_id WHERE payroll_details.payroll_id IS NULL";
                    $nres = $this->connect()->query($sql);
                    if($result->num_rows > 0){
                        while($nrow = $nres->fetch_assoc()){
                            $rowid = $nrow['payroll_id'];
                        }
                    }

                    $daydiff = strtotime(date($start_date)) - strtotime(date($end_date)); 
                    $days = abs(round($daydiff / 86400)); 
                    $total_hours = ($regular_hours*$days);
            
                    $sql = "INSERT INTO payroll_details values ($rowid,$payroll_salary,$total_hours,$det_bon_pay,$det_bon_hrs)";
                    $this->connect()->query($sql);

                    $sql = "INSERT INTO deductions values ($rowid,$payroll_salary*$Philhealth_rate,$payroll_salary*$SSS_rate,$payroll_salary*$PAGIBIG_rate,$payroll_salary*$tax_rate,$det_sal_deduc)";
                    $this->connect()->query($sql);

                    if($det_tot_deduc<$det_sal_deduc){
                        $tot_deduc = 0;
                        $sal_deduc = 0;
                    }else{
                        $tot_deduc = $det_tot_deduc-$det_sal_deduc;
                        if($tot_deduc<$det_sal_deduc){
                            $sal_deduc = $tot_deduc;
                        }else{
                            $sal_deduc = $det_sal_deduc;
                        }
                    }
        
                    $ssql = "UPDATE salary_details SET total_deduction=$tot_deduc,deduction_per_sal=$sal_deduc,bonus_pay=0,bonus_hours=0 WHERE employee_id=$employee_id";
                    $this->connect()->query($ssql);
                }
            }
                
            if($result){return "Generate payrolls successful!";}else{return "Generate payrolls failed.";}
        }
        function add_new_payroll($employee_id,$payroll_salary,$regular_hours,$overtime_hours,$payroll_bonus,$Philhealth,$SSS,$PAGIBIG,$taxes,$total_salary,$start_date,$end_date,$other_deduc){
            $sql = "INSERT INTO payroll values ('',$employee_id,'$start_date','$end_date',0)";
            $result = $this->connect()->query($sql);

            $sql = "SELECT payroll.payroll_id FROM payroll LEFT JOIN payroll_details ON payroll.payroll_id=payroll_details.payroll_id WHERE payroll_details.payroll_id IS NULL";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $rowid = $row['payroll_id'];
                }
            }
            
            $sql = "INSERT INTO payroll_details values ($rowid,$payroll_salary,$regular_hours,$payroll_bonus,$overtime_hours)";
            $result = $this->connect()->query($sql);

            $sql = "INSERT INTO deductions values ($rowid,$Philhealth,$SSS,$PAGIBIG,$taxes,$other_deduc)";
            $result = $this->connect()->query($sql);

            $sql = "SELECT * FROM salary_details WHERE employee_id=$employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $det_tot_deduc = $row['total_deduction'];
                    $det_sal_deduc = $row['deduction_per_sal'];
                    $det_bon_pay = $row['bonus_pay'];
                    $det_bon_hrs = $row['bonus_hours'];
                }
            }

            if($det_tot_deduc<$other_deduc){
                $tot_deduc = 0;
                $sal_deduc = 0;
            }else{
                $tot_deduc = $det_tot_deduc-$other_deduc;
                if($tot_deduc<$det_sal_deduc){
                    $sal_deduc = $tot_deduc;
                }else{
                    $sal_deduc = $det_sal_deduc;
                }
            }

            if($det_bon_pay<$payroll_bonus){
                $bon_pay = 0;
            }else{
                $bon_pay = $det_bon_pay-$payroll_bonus;
            }

            if($det_bon_hrs<$overtime_hours){
                $bon_hrs = 0;
            }else{
                $bon_hrs = $det_bon_hrs-$overtime_hours;
            }

            $sql = "UPDATE salary_details SET total_deduction=$tot_deduc,deduction_per_sal=$sal_deduc,bonus_pay=$bon_pay,bonus_hours=$bon_hrs WHERE employee_id=$employee_id";
            $result = $this->connect()->query($sql);

            if($result){return "Add payroll successful!";}else{return "Add payroll failed.";}
        }

        function edit_payroll($payroll_id,$payroll_salary,$regular_hours,$payroll_bonus,$overtime_hours,$Philhealth,$SSS,$PAGIBIG,$taxes,$date,$start_date,$end_date,$other_deduc){
            $sql = "UPDATE payroll_details SET payroll_salary=$payroll_salary, regular_hours=$regular_hours, payroll_bonus=$payroll_bonus, overtime_hours=$overtime_hours WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE deductions SET Philhealth=$Philhealth, SSS=$SSS, PAGIBIG=$PAGIBIG, taxes=$taxes, other_deduc=$other_deduc WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);

            $sql = "UPDATE payroll SET payroll_start_date='$start_date',payroll_end_date='$end_date' WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);

            if($result){return "Edit payroll successful!";}else{return "Edit payroll failed.";}
        }

        function show_payroll_list($searchparam,$start_date,$end_date){
            if(!empty($searchparam) && !empty($start_date) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll_end_date <= '$end_date' AND payroll.is_archived=0
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam) && !empty($start_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll.is_archived=0
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_end_date <= '$end_date' AND payroll.is_archived=0
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($start_date) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll_end_date <= '$end_date' AND payroll.is_archived=0
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=0
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($start_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=0
                AND payroll_start_date >= '$start_date'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=0
                AND payroll_end_date <= '$end_date'
                ORDER BY payroll_start_date DESC";
            }else{
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=0
                ORDER BY payroll_start_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    $total_amount = $total_salary - $total_deduc;
                    echo '
                    <tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>₱'.number_format($total_salary,2).'</td>
                    <td>₱'.number_format($total_deduc,2).'</td>
                    <td>₱'.number_format($total_amount,2).'</td>
                    <td>'.$row['payroll_start_date'].'</td>
                    <td>'.$row['payroll_end_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="viewpayroll_id" value='.$row['payroll_id'].'>
                    <input type="hidden" name="viewpayroll_empid" value='.$row['employee_id'].'>
                    <input type="submit" class="add-button" name="submit" value="View"></form></td>
                    </tr>'; 
                }
            }
        }

        function show_payroll_list_archive($searchparam,$start_date,$end_date){
            if(!empty($searchparam) && !empty($start_date) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll_end_date <= '$end_date' AND payroll.is_archived=1
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam) && !empty($start_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll.is_archived=1
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_end_date <= '$end_date' AND payroll.is_archived=1
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($start_date) && !empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$start_date' AND payroll_end_date <= '$end_date' AND payroll.is_archived=1
                ORDER BY payroll_start_date DESC";
            }else if(!empty($searchparam)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=1
                AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($start_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=1
                AND payroll_start_date >= '$start_date'
                ORDER BY payroll_start_date DESC";
            }else if(!empty($end_date)){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=1
                AND payroll_end_date <= '$end_date'
                ORDER BY payroll_start_date DESC";
            }else{
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' AND payroll.is_archived=1
                ORDER BY payroll_start_date DESC";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    $total_amount = $total_salary - $total_deduc;
                    echo '
                    <tr>
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>₱'.number_format($total_salary,2).'</td>
                    <td>₱'.number_format($total_deduc,2).'</td>
                    <td>₱'.number_format($total_amount,2).'</td>
                    <td>'.$row['payroll_start_date'].'</td>
                    <td>'.$row['payroll_end_date'].'</td>
                    <td><form method="POST">
                    <input type="hidden" name="viewpayroll_id" value='.$row['payroll_id'].'>
                    <input type="hidden" name="viewpayroll_empid" value='.$row['employee_id'].'>
                    <input type="submit" class="add-button" name="submit" value="View"></form></td>
                    </tr>'; 
                }
            }
        }

        function viewpayroll_salarydetails($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
            INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                    <td>₱ '.number_format($row['payroll_salary'],2).'</td>
                    <td>'.$row['regular_hours'].'</td>
                    <td>₱ '.number_format($row['payroll_bonus'],2).'</td>
                    <td>'.$row['overtime_hours'].'</td>'; 
                }
            }
        }

        function viewpayroll_salarydetails_down($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
            INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id 
            INNER JOIN salary_details ON payroll.employee_id = salary_details.employee_id 
            WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <tr>
                        <td>Name</td>
                        <td>'.$row['firstname'].' '.$row['lastname'].'</td> 
                    </tr>
                    <tr>
                        <td>Regular</td>
                        <td>'.$row['regular_hours'].'</td> 
                    </tr>
                    <tr>
                        <td>Overtime Hours</td>
                        <td>'.$row['overtime_hours'].'</td> 
                    </tr>
                    <tr>
                        <td>Severance</td>
                        <td>'.$row['severance'].'</td> 
                    </tr>'; 
                }
            }
        }

        function viewpayroll_deductions($payroll_id){
            $sql = "SELECT * FROM deductions WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    echo '
                    <td>₱'.number_format($row['Philhealth'],2).'</td>
                    <td>₱'.number_format($row['SSS'],2).'</td>
                    <td>₱'.number_format($row['PAGIBIG'],2).'</td>
                    <td>₱'.number_format($row['taxes'],2).'</td>
                    <td>₱'.number_format($row['other_deduc'],2).'</td>
                    <td>₱'.number_format($total_deduc,2).'</td>'; 
                }
            }
        }

        function viewpayroll_deductions_down($payroll_id){
            $sql = "SELECT * FROM deductions WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    echo '
                    <tr>
                        <td>Philhealth</td>
                        <td>'.$row['Philhealth'].'</td>
                    </tr>
                    <tr>
                        <td>SSS</td>
                        <td>'.$row['SSS'].'</td>
                    </tr>
                    <tr>
                        <td>Pagibig</td>
                        <td>'.$row['PAGIBIG'].'</td>
                    </tr>
                    <tr>
                        <td>Taxes</td>
                        <td>'.$row['taxes'].'</td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td>'.$row['other_deduc'].'</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>'.$total_deduc.'</td>
                    </tr>'; 
                }
            }
        }

        function viewpayroll_payrollsummary($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
            INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] +$row['other_deduc'];
                    $total_amount = $total_salary - $total_deduc;
                    echo '
                    <td>₱'.number_format($total_salary,2).'</td>
                    <td>₱'.number_format($total_deduc,2).'</td>
                    <td>₱'.number_format($total_amount,2).'</td>
                    <td>'.$row['payroll_start_date'].'</td>
                    <td>'.$row['payroll_end_date'].'</td>'; 
                }
            }
        }

        function viewpayroll_payrollsummary_down($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
            INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'];
                    $total_amount = $total_salary - $total_deduc;
                    echo '
                    <tr>
                        <td>Total Salary</td>
                        <td>₱'.number_format($total_salary,2).'</td>
                    </tr>
                    <tr>
                        <td>Total Deduction</td>
                        <td>₱'.number_format($total_deduc,2).'</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td>₱'.number_format($total_amount,2).'</td>
                    </tr>'; 
                }
            }
        }

        function editpayroll_salarydetails($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
            INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo '
                    <td>
                        <b style="width:30%;" id="employee-id" name="emp_name">'.$row['firstname'].' '.$row['lastname'].'</b>
                    </td>
                    <td>
                        ₱<input type="text" style="width:30%;" id="input_value_paysalary" value='.number_format($row['payroll_salary'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['payroll_salary'].');total_payroll()" name="payroll-salary" >
                    </td>
                    <td>
                        <input type="text" style="width:30%;" id="input_value_reghrs" value='.$row['regular_hours'].' oninput="check_num(this.value,this.id)" name="regular-hours" >
                    </td>
                    <td>
                        ₱<input type="text" style="width:30%;" id="input_value_paybonus" value='.$row['payroll_bonus'].' oninput="check_num_payroll(this.value,this.id,'.$row['payroll_bonus'].');total_payroll()" name="payroll-bonus" >
                    </td>
                    <td>
                        <input type="text" style="width:30%;" id="input_value_overhrs" value='.$row['overtime_hours'].' oninput="check_num(this.value,this.id)" name="overtime-hours" >
                    </td>
                    ';    
                }
            }
        }

        function editpayroll_deductions($payroll_id){
            $sql = "SELECT * FROM deductions WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    echo '
    
                    <td>
                       <input type="text" style="width:30%;" id="input_value_phlhlt" value=₱'.number_format($row['Philhealth'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['Philhealth'].');total_payroll()" name="philhealth" >
                    </td>
                    <td>
                      <input type="text" style="width:30%;" id="input_value_sss" value=₱'.number_format($row['SSS'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['SSS'].');total_payroll()" name="sss" >
                    </td>
                    <td>
                      <input type="text" style="width:30%;" id="input_value_pgibg" value=₱'.number_format($row['PAGIBIG'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['PAGIBIG'].');total_payroll()" name="pagibig" >
                   </td>
                   <td>
                     <input type="text" style="width:30%;" id="input_value_txs" value=₱'.number_format($row['taxes'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['taxes'].');total_payroll()" name="taxes" >
                    </td>
                    <td>
                     <input type="text" style="width:30%;" id="input_value_otherdeduc" value=₱'.number_format($row['other_deduc'],2).' oninput="check_num_payroll(this.value,this.id,'.$row['other_deduc'].');total_payroll()" name="other_deduc" >
                    </td>
                    <td>
                     <b style="width:30%;" id="input_value_ttldeduc" name="total-deduc">₱'.number_format($total_deduc,2).'</b>
                   </td>'; 
                }
            }
        }

        function editpayroll_payrollsummary($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
            INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                    $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'] + $row['other_deduc'];
                    $total_amount = $total_salary - $total_deduc;
                    
                    echo '
                    <td>
                        <b style="width:30%;" id="input_value_ttlsalary" name="total-salary">₱'.number_format($total_salary,2).'</b>
                    </td>
                    <td>
                        <b style="width:30%;" id="input_value_ttldeduc2" name="total-deduc">₱'.number_format($total_deduc,2).'</b>
                    </td>
                    <td>  
                        <b style="width:30%;" id="input_value_ttlamt" name="total-amount">₱'.number_format($total_amount,2).'</b>
                    </td>
                    <td>
                        <input type="date" style="width:30%;" id="payroll_start_date"  name="payroll-start-date" value='.$row['payroll_start_date'].' onchange="payroll_checkInclusiveDate_valid()" >
                    </td>
                    <td>
                        <input type="date" style="width:30%;" id="payroll_end_date"  name="payroll-end-date" value='.$row['payroll_end_date'].' onchange="payroll_checkInclusiveDate_valid()" >
                    </td>'; 
                }
            }
        }

        function delete_payroll($payroll_id){
            $sql = "DELETE FROM payroll WHERE payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result){return "delete successful!";}else{return "delete failed.";}
        }

        function get_employee_payroll_detail($employee_id,$type){
            $sql = "SELECT * FROM salary_details WHERE employee_id = $employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $salary = $row['salary']; 
                    $hours = $row['regular_hours'];
                    $other_deduc = $row['deduction_per_sal'];
                    $bonus_pay = $row['bonus_pay'];
                    $bonus_hours = $row['bonus_hours'];
                }
            }
            if($type == 'salary'){
                return $salary;
            }else if($type == 'hours'){
                $daydiff = strtotime(date('Y-m-01')) - strtotime(date('Y-m-t')); 
                $days = abs(round($daydiff / 86400)); 
                return ($hours*$days);
            }else if($type == 'other_deduc'){
                return $other_deduc;
            }else if($type == 'bonus_pay'){
                return $bonus_pay;
            }else if($type == 'bonus_hours'){
                return $bonus_hours;
            }else if($type == 'hours_per_day'){
                return $hours;
            }
        }

        function get_employee_deduc_detail($employee_id,$type){
            $sql = "SELECT * FROM salary_details WHERE employee_id = $employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $salary = $row['salary']; 
                }
            }

            if($type=="philhealth"){
                $sql = "SELECT * FROM payroll_deduction_rates WHERE deduc_rate_id = 1";
            }else if($type=="sss"){
                $sql = "SELECT * FROM payroll_deduction_rates WHERE deduc_rate_id = 2";
            }else if($type=="pagibig"){
                $sql = "SELECT * FROM payroll_deduction_rates WHERE deduc_rate_id = 3";
            }else if($type=="tax"){
                $sql = "SELECT * FROM payroll_deduction_rates WHERE deduc_rate_id = 4";
            }
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){ 
                    $rate = $row['deduc_rate'];             
                }
            }
            return ($salary*$rate);
        }

        function get_employee_total_detail($employee_id,$type){
            $sql = "SELECT * FROM salary_details WHERE employee_id = $employee_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $salary = $row['salary'];
                    $other_deduc = $row['deduction_per_sal'];
                    $bonus_pay = $row['bonus_pay'];  
                }
            }

            $sql = "SELECT * FROM payroll_deduction_rates";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){ 
                    if($row['deduc_rate_id']==1){
                        $philhealth = $salary*$row['deduc_rate'];
                    }else if($row['deduc_rate_id']==2){
                        $sss = $salary*$row['deduc_rate'];
                    }else if($row['deduc_rate_id']==3){
                        $pagibig = $salary*$row['deduc_rate'];
                    }else if($row['deduc_rate_id']==4){
                        $tax = $salary*$row['deduc_rate'];
                    }           
                }
            }

            if($type=="totDeduc"){
                $total=$philhealth+$sss+$pagibig+$tax+$other_deduc;
            }else if($type=="totAmount"){
                $total=($salary+$bonus_pay)-($philhealth+$sss+$pagibig+$tax+$other_deduc);
            }else if($type=="totSal"){
                $total=$salary+$bonus_pay;
            }
            return $total;
        }

        function viewpayroll_salarydown($payroll_id){
            $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
            INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id 
            INNER JOIN salary_details ON payroll.employee_id = salary_details.employee_id 
            WHERE payroll.payroll_id=$payroll_id";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $date = date_create($row['payroll_start_date']);
                    echo '
                    <h6 style="font-size: 12px; margin-top: 0; margin-bottom: 5px; color: rgb(80, 79, 79);">'.$row['firstname'].' '.$row['lastname'].'</h6> 
                    <h6 style="font-size: 12px; margin-top: 0; margin-bottom: 100px; color: rgb(80, 79, 79);">Payroll for '.date_format($date,"F/j/Y").'</h5> '; 
                }
            }
        }

        function show_deduction_rates(){
            $sql = "SELECT * FROM payroll_deduction_rates";
            $result = $this->connect()->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row['deduc_rate_id']==1){
                        $philhealth = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']== 2){
                        $sss = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']== 3){
                        $pagibig = $row['deduc_rate'];
                    }else if($row['deduc_rate_id']== 4){
                        $tax = $row['deduc_rate'];
                    }
                }
            }
            echo '
                    <td>
                       <input type="text" style="width:30%;" id="input_value_phlhlt" value='.$philhealth.' oninput="check_num(this.value,this.id);total_payroll()" name="philhealth" >
                    </td>
                    <td>
                      <input type="text" style="width:30%;" id="input_value_sss" value='.$sss.' oninput="check_num(this.value,this.id);total_payroll()" name="sss" >
                    </td>
                    <td>
                      <input type="text" style="width:30%;" id="input_value_pgibg" value='.$pagibig.' oninput="check_num(this.value,this.id);total_payroll()" name="pagibig" >
                   </td>
                   <td>
                     <input type="text" style="width:30%;" id="input_value_txs" value='.$tax.' oninput="check_num(this.value,this.id);total_payroll()" name="taxes" >
                    </td>
                    '; 
            }

            function edit_deduction_rates($philhealth,$sss,$pagibig,$tax){
                $sql = "UPDATE payroll_deduction_rates SET deduc_rate=$philhealth WHERE deduc_rate_id=1";
                $result = $this->connect()->query($sql);

                $sql = "UPDATE payroll_deduction_rates SET deduc_rate=$sss WHERE deduc_rate_id=2";
                $result = $this->connect()->query($sql);

                $sql = "UPDATE payroll_deduction_rates SET deduc_rate=$pagibig WHERE deduc_rate_id=3";
                $result = $this->connect()->query($sql);

                $sql = "UPDATE payroll_deduction_rates SET deduc_rate=$tax WHERE deduc_rate_id=4";
                $result = $this->connect()->query($sql);

                if($result){return "Add edit deduc rates successful!";}else{return "Add deduc rates failed.";}
            }

            function show_payroll_reports($searchparam,$start_date,$end_date,$is_archived){
                if(!empty($searchparam) && !empty($start_date) && !empty($end_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_floor_date >= '$start_date' AND report_ceiling_date <= '$end_date'
                    AND payroll_report_title LIKE '$searchparam%' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($searchparam) && !empty($start_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_floor_date >= '$start_date'
                    AND payroll_report_title LIKE '$searchparam%' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($searchparam) && !empty($end_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_ceiling_date <= '$end_date'
                    AND payroll_report_title LIKE '$searchparam%' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($start_date) && !empty($end_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_floor_date >= '$start_date' AND report_ceiling_date <= '$end_date' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($searchparam)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE payroll_report_title LIKE '$searchparam%' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($start_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_floor_date >= '$start_date' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else if(!empty($end_date)){
                    $sql = "SELECT * FROM payroll_reports 
                    WHERE report_ceiling_date <= '$end_date' AND is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }else{
                    $sql = "SELECT * FROM payroll_reports WHERE is_archived = $is_archived
                    ORDER BY report_floor_date DESC";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '
                        <tr>
                        <td>'.$row['payroll_report_title'].'</td>
                        <td>₱'.number_format($row['report_total_amount'],2).'</td>
                        <td>'.$row['report_floor_date'].'</td>
                        <td>'.$row['report_ceiling_date'].'</td>
                        <td><form method="POST">
                        <input type="hidden" name="payroll_rep_id" value='.$row['payroll_report_id'].'>
                        <input type="submit" class="add-button" name="submit" value="View"></form></td>
                        </tr>'; 
                    }
                }
            }

            function get_payroll_report_totalAmt($floordate,$ceilingdate,$display){
                $totAmt=0;
                $sql = "SELECT * FROM payroll
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id 
                WHERE payroll_start_date >= '$floordate' AND payroll_end_date <= '$ceilingdate'";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                        $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'];
                        $total_amount = $total_salary - $total_deduc;
                        $totAmt += $total_amount;
                    }
                }
                if ($display==1){
                    return "₱ ".number_format($totAmt,2);
                }else{
                    return round($totAmt,2);
                }
            }

            function show_report_payrolls($floordate,$ceilingdate){
                $sql = "SELECT * FROM payroll INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id WHERE emp_status='Active' 
                AND payroll_start_date >= '$floordate' AND payroll_end_date <= '$ceilingdate'
                ORDER BY payroll_start_date DESC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                        $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'];
                        $total_amount = $total_salary - $total_deduc;
                        echo '
                        <tr>
                        <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                        <td>₱'.number_format($total_salary,2).'</td>
                        <td>₱'.number_format($total_deduc,2).'</td>
                        <td>₱'.number_format($total_amount,2).'</td>
                        <td>'.$row['payroll_start_date'].'</td>
                        <td>'.$row['payroll_end_date'].'</td>
                        </tr>'; 
                    }
                }
            }

            function show_report_viewpayrolls($rep_id){
                $sql = "SELECT * FROM payroll_report_salaries 
                INNER JOIN payroll ON payroll_report_salaries.payroll_id=payroll.payroll_id
                INNER JOIN employee ON payroll.employee_id = employee.employee_id 
                INNER JOIN payroll_details ON payroll.payroll_id = payroll_details.payroll_id
                INNER JOIN deductions ON payroll.payroll_id = deductions.payroll_id
                WHERE payroll_report_id=$rep_id 
                ORDER BY payroll_start_date DESC";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $total_salary = $row['payroll_salary'] + $row['payroll_bonus'];
                        $total_deduc = $row['Philhealth'] + $row['SSS'] + $row['PAGIBIG'] + $row['taxes'];
                        $total_amount = $total_salary - $total_deduc;
                        echo '
                        <tr>
                        <td>'.$row['firstname'].' '.$row['lastname'].'</td>
                        <td>₱'.number_format($total_salary,2).'</td>
                        <td>₱'.number_format($total_deduc,2).'</td>
                        <td>₱'.number_format($total_amount,2).'</td>
                        <td>'.$row['payroll_start_date'].'</td>
                        <td>'.$row['payroll_end_date'].'</td>
                        </tr>'; 
                    }
                }
            }

            function add_payroll_report($title,$totAmt,$floordate,$ceilingdate){
                $sql = "INSERT INTO payroll_reports values ('','$title',$totAmt,'$floordate','$ceilingdate',0)";
                $result = $this->connect()->query($sql);

                $sql = "SELECT * FROM payroll_reports";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $rep_id=$row['payroll_report_id'];
                    }
                }

                $sql = "SELECT * FROM payroll WHERE payroll_start_date >= '$floordate' AND payroll_end_date <= '$ceilingdate'";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $rec_id=$row['payroll_id'];
                        $msql = "INSERT INTO payroll_report_salaries values ('',$rep_id,$rec_id)";
                        $this->connect()->query($msql);
                    }
                }

                if($result){return "Add report successful!";}else{return "Add report failed.";}
            
            }

            function delete_payroll_report($rep_id){
                $sql = "DELETE FROM payroll_reports WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);
                if($result){return "delete report successful!";}else{return "delete report failed.";}
            }

            function get_payroll_report_detail($rep_id,$type){
                $sql = "SELECT * FROM payroll_reports WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($type=='title'){
                            $value=$row['payroll_report_title'];
                        }else if($type=='totAmt'){
                            $value=$row['report_total_amount'];
                        }else if($type=='floor'){
                            $value=$row['report_floor_date'];
                        }else if($type=='ceiling'){
                            $value=$row['report_ceiling_date'];
                        }
                    }
                }
                return $value;
            }

            function get_payroll_report_detail_totamt($rep_id,$display){
                $sql = "SELECT * FROM payroll_reports WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $totAmt=$row['report_total_amount'];
                    }
                }
                if ($display=1){
                    return number_format($totAmt,2);
                }else{
                    return round($totAmt,2);
                }
            }

            function edit_payroll_report($rep_id,$title,$totAmt,$floordate,$ceilingdate){
                $sql = "UPDATE payroll_reports SET payroll_report_title='$title',report_total_amount=$totAmt,report_floor_date='$floordate',report_ceiling_date='$ceilingdate' WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);

                $sql = "DELETE FROM payroll_report_salaries WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);

                $sql = "SELECT * FROM payroll WHERE payroll_start_date >= '$floordate' AND payroll_end_date <= '$ceilingdate'";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $rec_id=$row['payroll_id'];
                        $msql = "INSERT INTO payroll_report_salaries values ('',$rep_id,$rec_id)";
                        $this->connect()->query($msql);
                    }
                }

                if($result){return "edit report successful!";}else{return "edit report failed.";}
            
            }

            function archive_payroll_report($rep_id){
                $sql = "UPDATE payroll_reports SET is_archived=1 WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);

                if($result){return "edit report successful!";}else{return "edit report failed.";}
            
            }

            function unarchive_payroll_report($rep_id){
                $sql = "UPDATE payroll_reports SET is_archived=0 WHERE payroll_report_id=$rep_id";
                $result = $this->connect()->query($sql);

                if($result){return "edit report successful!";}else{return "edit report failed.";}
            
            }

            function show_employees_otherdeducts($searchparam){
                $msql = "SELECT * FROM payroll_deduction_rates";
                $result = $this->connect()->query($msql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($row['deduc_rate_id']==1){
                            $Philhealth_rate = $row['deduc_rate'];
                        }else if($row['deduc_rate_id']==2){
                            $SSS_rate = $row['deduc_rate'];
                        }else if($row['deduc_rate_id']==3){
                            $PAGIBIG_rate = $row['deduc_rate'];
                        }else if($row['deduc_rate_id']==4){
                            $tax_rate = $row['deduc_rate'];
                        }
                    }
                }

                if (!empty($searchparam)){
                    $sql = "SELECT * FROM
                    INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                    WHERE emp_status='Active' AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'";
                }else{
                    $sql = "SELECT * FROM employee 
                    INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                    WHERE emp_status='Active'";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    $i=0;
                    while($row = $result->fetch_assoc()){
                        $salary_posttax=$row['salary']-($row['salary']*$Philhealth_rate+$row['salary']*$SSS_rate+$row['salary']*$PAGIBIG_rate+$row['salary']*$tax_rate);
                        echo '<tr>
                        <td>'.$row['firstname'].' '.$row['lastname'].' <input type="hidden" id="emp_id_'.$i.'" name="emp_id_'.$i.'" value='.$row['employee_id'].'></td>
                        <td>'.$row['position'].'</td>
                        <td>₱'.number_format($row['salary'],2).' (₱'.number_format($salary_posttax,2).')</td>
                        <td>₱<input type="text" id="emp_total_deduc_'.$i.'" name="emp_total_deduc_'.$i.'" value='.$row['total_deduction'].' 
                        oninput="check_num(this.value,this.id,'.$row['total_deduction'].');deduc_check_greaterthantotal('.$row['total_deduction'].','.$row['deduction_per_sal'].','.$i.')"></td>
                        <td>₱<input type="text" id="emp_deduc_per_sal_'.$i.'" name="emp_deduc_per_sal_'.$i.'" value='.$row['deduction_per_sal'].' 
                        oninput="check_num(this.value,this.id,'.$row['deduction_per_sal'].');
                        deduc_check_greaterthantotal('.$row['total_deduction'].','.$row['deduction_per_sal'].','.$i.');
                        deduc_check_greaterthansalary('.$salary_posttax.','.$row['deduction_per_sal'].','.$i.')"></td>
                        </tr>';
                        $i++;
                    }
                    echo '<input type="hidden" id="emp_max" name="emp_max" value='.$i.'>';
                }
            }

            function show_employees_bonuses($searchparam){
                if (!empty($searchparam)){
                    $sql = "SELECT * FROM
                    INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                    WHERE emp_status='Active' AND firstname LIKE '$searchparam%' OR lastname LIKE '$searchparam%'";
                }else{
                    $sql = "SELECT * FROM employee 
                    INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                    WHERE emp_status='Active'";
                }
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    $i=0;
                    while($row = $result->fetch_assoc()){
                        echo '<tr>
                        <td>'.$row['firstname'].' '.$row['lastname'].' <input type="hidden" id="emp_id_'.$i.'" name="emp_id_'.$i.'" value='.$row['employee_id'].'></td>
                        <td>'.$row['position'].'</td>
                        <td>₱'.number_format($row['salary'],2).'</td>
                        <td>₱<input type="text" id="emp_bonus_pay_'.$i.'" name="emp_bonus_pay_'.$i.'" value='.$row['bonus_pay'].' onchange="check_num(this.value,this.id)"></td>
                        <td><input type="text" id="emp_overtime_hours_'.$i.'" name="emp_overtime_hours_'.$i.'" value='.$row['bonus_hours'].' onchange="check_num(this.value,this.id)"></td>
                        <td><form method="POST"><input type="hidden" id="emp_id_overtime" name="emp_id_overtime" value='.$row['employee_id'].'>
                        <input type="submit" name="submit" value="Add Overtime" class="add-button"></form></td>
                        </tr>';
                        $i++;
                    }
                    echo '<input type="hidden" id="emp_max" name="emp_max" value='.$i.'>';
                }
            }

            function get_employees_overtime_detail($emp_id,$type){
                $sql = "SELECT * FROM employee 
                INNER JOIN salary_details ON employee.employee_id = salary_details.employee_id 
                WHERE employee.employee_id =$emp_id";
                $result = $this->connect()->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if($type=='Name'){
                            $detail = $row['firstname'].' '.$row['lastname'];
                        }else if($type=='Hourly'){
                            $detail = $row['salary']/($row['regular_hours']*30);
                            $detail = round($detail,2);
                        }
                    }
                }
                return $detail;
            }

            function edit_employee_otherdeduc($employee_id,$total,$per_salary){
                $sql = "UPDATE salary_details SET total_deduction=$total,deduction_per_sal=$per_salary WHERE employee_id=$employee_id";
                $result = $this->connect()->query($sql);

                if($result){return "edit deduc successful!";}else{return "edit deduc failed.";}
            }

            function edit_employee_bonus($employee_id,$bonus_pay,$overtime_hours){
                $sql = "UPDATE salary_details SET bonus_pay=$bonus_pay,bonus_hours=$overtime_hours WHERE employee_id=$employee_id";
                $result = $this->connect()->query($sql);

                if($result){return "edit bonus successful!";}else{return "edit bonus failed.";}
            }

            function add_employee_overtime($employee_id,$bonus,$hours){
                $sql = "UPDATE salary_details SET bonus_pay=bonus_pay+$bonus,bonus_hours=bonus_hours+$hours WHERE employee_id=$employee_id";
                $result = $this->connect()->query($sql);

                if($result){return "add overtime successful!";}else{return "add overtime failed.";}
            }

            function get_is_samedate($floordate,$ceilingdate){
                $sql = "SELECT * FROM payroll_reports WHERE report_floor_date = '$floordate' AND report_ceiling_date='$ceilingdate' AND is_archived=0";
                $result = $this->connect()->query($sql);
                $exists = false;
                if($result->num_rows > 0){
                    $exists = true;
                }
                return $exists;
            }

            function archive_payroll($payroll_id){
                $sql = "UPDATE payroll SET is_archived=1 WHERE payroll_id=$payroll_id";
                $result = $this->connect()->query($sql);
    
                if($result){return "Edit payroll successful!";}else{return "Edit payroll failed.";}
            }

            function unarchive_payroll($payroll_id){
                $sql = "UPDATE payroll SET is_archived=0 WHERE payroll_id=$payroll_id";
                $result = $this->connect()->query($sql);
    
                if($result){return "Edit payroll successful!";}else{return "Edit payroll failed.";}
            }
    }
