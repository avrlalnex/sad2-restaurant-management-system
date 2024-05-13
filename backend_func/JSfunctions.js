function check_num(input_val,input_id){
  if(isNaN(input_val)){
    alert("Error! Numbers Values Only Please.")
    document.getElementById(input_id).value = 0;
  }
}

function check_num_minimum(input_val,input_id){
  if(isNaN(input_val) || input_val==0){
    alert("Error! Number Values Above Zero Only.")
    document.getElementById(input_id).value = 1;
  }
}

function check_stockoutvalid(input_val,input_id,currentquant){
  if(Number(input_val)>Number(currentquant)){
    alert("Error! Stock out quantity cannot be greater than current quantity!")
    document.getElementById(input_id).value = currentquant;
  }else if(isNaN(input_val)){
    alert("Error! Numbers Values Only Please!")
    document.getElementById(input_id).value = currentquant;
  }
}

function check_num_payroll(input_val,input_id,original_val){
  salary = parseFloat(document.getElementById("input_value_paysalary").value);
  bonus = parseFloat(document.getElementById("input_value_paybonus").value);
  phl = parseFloat(document.getElementById("input_value_phlhlt").value);
  sss = parseFloat(document.getElementById("input_value_sss").value);
  pgbg = parseFloat(document.getElementById("input_value_pgibg").value);
  txs = parseFloat(document.getElementById("input_value_txs").value);
  otherdeduc = parseFloat(document.getElementById("input_value_otherdeduc").value);
  if(isNaN(input_val)){
    alert("Error! Numbers Values Only Please.")
    document.getElementById(input_id).value = original_val;
  }else if(0 > (salary+bonus) - (phl+sss+pgbg+txs+otherdeduc)){
    alert("Error! Invalid Payroll!")
    document.getElementById(input_id).value = original_val;
  }
}

function total_payroll(){
  salary = parseFloat(document.getElementById("input_value_paysalary").value);
  bonus = parseFloat(document.getElementById("input_value_paybonus").value);
  phl = parseFloat(document.getElementById("input_value_phlhlt").value);
  sss = parseFloat(document.getElementById("input_value_sss").value);
  pgbg = parseFloat(document.getElementById("input_value_pgibg").value);
  txs = parseFloat(document.getElementById("input_value_txs").value);
  otherdeduc = parseFloat(document.getElementById("input_value_otherdeduc").value);
  document.getElementById("input_value_ttldeduc").textContent = phl+sss+pgbg+txs+otherdeduc;
  document.getElementById("input_value_ttldeduc2").textContent = phl+sss+pgbg+txs+otherdeduc;
  document.getElementById("input_value_ttlsalary").textContent = salary+bonus;
  document.getElementById("input_value_ttlamt").textContent = (salary+bonus) - (phl+sss+pgbg+txs+otherdeduc);
}

function invoice_addnewItem() {
  var length = document.getElementById("invoice_items").rows.length-1;
  var idname = "invoiceItem_id_"+length;
  var quantname = "invoiceItem_quant_"+length;
  var unitpricename = "invoiceItemUnit_price_"+length;
  var pricename = "invoiceItem_price_"+length;
  var rowselect = document.getElementById("stock-id-select").value;
  var rowid;
  var rowname;
  if(rowselect=="Goods"){
    rowid = document.getElementById("stock-id-goods").value;
    rowname = document.getElementById("stock-id-goods").options[document.getElementById("stock-id-goods").selectedIndex].text;
  }else if(rowselect=="Supply"){
    rowid = document.getElementById("stock-id-supply").value;
    rowname = document.getElementById("stock-id-supply").options[document.getElementById("stock-id-supply").selectedIndex].text;
  }

  var table = document.getElementById("invoice_items").getElementsByTagName('tbody')[0];
  var row = table.insertRow(length);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  cell1.innerHTML = rowname+"<input type='hidden' id='"+idname+"' name='"+idname+"' value='"+rowid+"'>";
  cell2.innerHTML = "<input type='text' oninput=\"check_num_minimum(this.value,this.id);orderslip_calc_unit_total("+length+");orderslip_calc_total()\" id='"+quantname+"' name='"+quantname+"' value='"+1+"'>";
  cell3.innerHTML = "<input type='text' oninput=\"check_num(this.value,this.id);orderslip_calc_unit_total("+length+");orderslip_calc_total()\" id='"+unitpricename+"'name='"+unitpricename+"' value='"+0+"'>";
  cell4.innerHTML = "<input type='text' oninput=\"check_num(this.value,this.id);orderslip_calc_total()\" id='"+pricename+"'name='"+pricename+"' value='"+0+"'>";
  document.getElementById("invoice-numofitems").value = length+1;
}

function invoice_undonewItem(){
  var length = document.getElementById("invoice_items").rows.length-1;
  var table = document.getElementById("invoice_items").getElementsByTagName('tbody')[0];
  table.deleteRow(length-1);
  document.getElementById("invoice-numofitems").value = length-1;
}

function invoice_selectItemType(){
  var rowselect = document.getElementById("stock-id-select").value;
  var rowgoods = document.getElementById("stock-id-goods");
  var rowsupply = document.getElementById("stock-id-supply");
  if (rowselect=="Goods") {
    
    rowgoods.removeAttribute("hidden");
    
    rowsupply.setAttribute("hidden", "hidden");
 } else if(rowselect=="Supply"){
    
      rowsupply.removeAttribute("hidden");
    
    rowgoods.setAttribute("hidden", "hidden");
 }
}

function formatDate(date) {
  var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2) 
      month = '0' + month;
  if (day.length < 2) 
      day = '0' + day;

  return [year, month, day].join('-');
}

function payroll_checkInclusiveDate_valid(hours_per_day){
  var floordate = document.getElementById("payroll_start_date");
  var ceilingdate = document.getElementById("payroll_end_date");
  if(floordate.value > ceilingdate.value){
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var fd = new Date(y, m, 1);
    var ld = new Date(y, m + 1, 0);
    floordate.value = formatDate(fd);
    ceilingdate.value = formatDate(ld);
    alert("Invalid span of dates! Make sure the start date does not exceed the end date!");
  }else{
    let floor = new Date(floordate.value);
    let ceiling = new Date(ceilingdate.value);
    let time = ceiling.getTime() - floor.getTime();
    let days = Math.round(time / (1000 * 3600 * 24));
    document.getElementById("input_value_reghrs").value = (hours_per_day*days);
  }
}

function report_checkInclusiveDate_valid(){
  var floordate = document.getElementById("rep-floorDate");
  var ceilingdate = document.getElementById("rep-ceilingDate");
  if(floordate.value > ceilingdate.value){
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var fd = new Date(y, m, 1);
    var ld = new Date(y, m + 1, 0);
    floordate.value = formatDate(fd);
    ceilingdate.value = formatDate(ld);
    alert("Invalid span of dates! Make sure the start date does not exceed the end date!");
  }
}

function orderslip_calc_total(){
  var numofitems = document.getElementById("invoice-numofitems").value;
  var total_price = parseFloat(document.getElementById("orderslip-price-fees").value);
  i=0;
    while(i < numofitems) {
      total_price += parseFloat(document.getElementById("invoiceItem_price_"+i).value);
      i++;
    }
  document.getElementById("orderslip-price").value = total_price;
}

function orderslip_calc_unit_total(i){
  var quant = document.getElementById("invoiceItem_quant_"+i).value;
  var unit_price = parseFloat(document.getElementById("invoiceItemUnit_price_"+i).value);
  total_price = quant*unit_price;
  document.getElementById("invoiceItem_price_"+i).value = total_price;
}

function invoice_check_stockactive(stock_original,active_original,i){
  var stock = parseFloat(document.getElementById("invoiceitem-quant_"+i).value);
  var active = parseFloat(document.getElementById("activeitem-quant_"+i).value);
  if(stock<active){
    alert("Active Quantity cannot be greater than Stock Quantity!");
    document.getElementById("invoiceitem-quant_"+i).value = stock_original;
    document.getElementById("activeitem-quant_"+i).value =active_original;
  }
}
