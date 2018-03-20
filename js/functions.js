function trim(str) 
{
        return str.replace(/^\s+|\s+$/g,"");
}
	
function  validateEmail(address)
{
	address = trim(address);
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(address) == false) 
	 {
		  return false;
	}
	return true;
}

function validateIP (IPvalue) {
IPvalue = trim(IPvalue);
errorString = "";
theName = "IPaddress";

var ipPattern = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
var ipArray = IPvalue.match(ipPattern);

if (IPvalue == "0.0.0.0")
errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
else if (IPvalue == "255.255.255.255")
errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
if (ipArray == null)
errorString = errorString + theName + ': '+IPvalue+' is not a valid IP address.';
else {
for (i = 0; i < 4; i++) {
thisSegment = ipArray[i];
if (thisSegment > 255) {
errorString = errorString + theName + ': '+IPvalue+' is not a valid IP address.';
i = 4;
}
if ((i == 0) && (thisSegment > 255)) {
errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
i = 4;
      }
   }
}
extensionLength = 3;
if (errorString == "")
return true;
else
return false;
}

function validateOID(oid)
{
	oid = trim(oid);
	var reg = /^[0-9]+\.([0-9\.])+\.[0-9]+$/;
	if(reg.test(oid) == false) 
	 {
		  return false;
	}
	return true;
}

function validateNumber(number)
{
	number = trim(number);
	var reg = /^[0-9]+$/;
	if(reg.test(number) == false) 
	 {
		  return false;
	}
	return true;
}

function validateDate(date)
{
	date = trim(date);
	var reg = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
	if(reg.test(date) == false) 
	 {
		  return false;
	}
	return true;
}

function isEmpty(aTextField) {
   if ((aTextField.length==0) ||
   (aTextField==null)) {
      return true;
   }
   else { return false; }
}

function isNotEmpty(aTextField) {
if ((aTextField.length > 0) && (aTextField !=null)) {
      return true;
   }
   else { return false; }
}


function setErrorMessage(id, msg)
{
	document.getElementById('status_'+id).innerHTML ="<font color='red'>"+msg+"</font>";
    document.getElementById('status_'+id+'_img').innerHTML ="<img src='/images/buttons/Delete_16x16.png'>";
}
function cleanErrorMessage(fields)
{
	for(var i=0;i<fields.length;i++)
	{
		document.getElementById('status_'+fields[i]).innerHTML ="&nbsp;";
		document.getElementById('status_'+fields[i]+'_img').innerHTML ="&nbsp;";
	}

}
function submitUserPassword()
{
	cleanErrorMessage(Array("oldPassword", "newPassword", "retypeNewPassword"));
	var status = true;
	var oldPassword = document.forms["userPassword"].elements["oldPassword"].value;
	var newPassword = document.forms["userPassword"].elements["newPassword"].value; 
	var retypeNewPassword = document.forms["userPassword"].elements["retypeNewPassword"].value; 
	if (isEmpty(oldPassword) == true) { setErrorMessage("oldPassword", "Необходимо указать текущий пароль!"); status = false}
	if (isEmpty(newPassword) == true) { setErrorMessage("newPassword", "Необходимо указать новый пароль!"); status = false}
	if (isEmpty(retypeNewPassword) == true) { setErrorMessage("retypeNewPassword", "Необходимо указать подтверждение!"); status = false}
	if (newPassword != retypeNewPassword) {setErrorMessage("retypeNewPassword", "Новый пароль и подтверждение не сопадают!"); status = false }
	return status;
}

function submitUserEdit(userID)
{
	cleanErrorMessage(Array("password", "email", "login", "retypePassword"));
	var status = true;
	var password = document.forms["userEdit"].elements["password"].value;
	var retypePassword = document.forms["userEdit"].elements["retypePassword"].value;
	var email = document.forms["userEdit"].elements["email"].value;
	var login = document.forms["userEdit"].elements["login"].value;
	var role = document.getElementById('role');
	
	 if (typeof(document.forms["userEdit"].elements["expires"]) == 'undefined')
	 {
		var expires = '';
	 }
	 else
	 {
		var expires = document.forms["userEdit"].elements["expires"].value;
	 }
	if (isEmpty(userID) && isEmpty(password)) {setErrorMessage("password", "Необходимо указать пароль!"); setErrorMessage("retypePassword", "Требуется подтверждение пароля!");status = false }
	if (password != retypePassword) {setErrorMessage("retypePassword", "Пароль и подтверждение пароля не совпадают!"); status = false }
	if (isEmpty(login) == true) { setErrorMessage("login", "Необходимо указать логин!"); status = false}
	if (validateEmail(email) == false) { setErrorMessage("email", "Неверный формат e-mail!"); status = false}
	if (isEmpty(expires)== false  &&  validateDate(expires) == false) { setErrorMessage("expires", "Формат даты \'MM/DD/YYYY\'"); status = false}
	return  status;
}


function submitCarEdit(carID)
{
	cleanErrorMessage(Array("model", "regnum"));
	var status = true;
	var model = document.forms["carEdit"].elements["model"].value;
	var regnum = document.forms["carEdit"].elements["regnum"].value;
	
	if (isEmpty(model) == true) { setErrorMessage("model", "Необходимо указать модель!"); status = false}
	if (isEmpty(regnum) == true) { setErrorMessage("regnum", "Необходимо указать регистрационный номер!"); status = false}
	return  status;
}
function submitLocationEdit(locationID)
{
	cleanErrorMessage(Array("name"));
	var status = true;
	var name = document.forms["locationEdit"].elements["name"].value;
	
	if (isEmpty(name) == true) { setErrorMessage("name", "Необходимо указать населенный пункт!"); status = false}
	return  status;
}

function submitCreditEdit(creditID)
{
	cleanErrorMessage(Array("name"));
	var status = true;
	var name = document.forms["creditEdit"].elements["name"].value;
	
	if (isEmpty(name) == true) { setErrorMessage("name", "Необходимо указать номер кредита!"); status = false}
	return  status;
}

function submitDriverEdit(driverID)
{
	cleanErrorMessage(Array("fullname", "nick"));
	var status = true;
	var model = document.forms["driverEdit"].elements["fullname"].value;
	var regnum = document.forms["driverEdit"].elements["nick"].value;
	
	if (isEmpty(fullname) == true) { setErrorMessage("fullname", "Необходимо указать имя!"); status = false}
	if (isEmpty(nick) == true) { setErrorMessage("nick", "Необходимо указать позывной!"); status = false}
	return  status;
}

function submitDeviceEdit()
{
	var status = true;
	var error_status = document.getElementById("error_status");
	error_status.innerHTML = ""; 
	if (isEmpty(document.forms["deviceEdit"].elements["device_name"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- Device ID is required</font></div>";  status=false} 
	if (isEmpty(document.forms["deviceEdit"].elements["device_ip_address"].value) == false && validateIP(document.forms["deviceEdit"].elements["device_ip_address"].value) == false) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- IP address is incorrect</font></div>";  status=false}
	if (isEmpty(document.forms["deviceEdit"].elements["snmp_write_community"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- SNMP write community is required</font></div>";  status=false}
	if (isEmpty(document.forms["deviceEdit"].elements["snmp_read_community"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- SNMP read community is required</font></div>";  status=false} 
	if (isEmpty(document.forms["deviceEdit"].elements["snmp_port"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- SNMP port is required</font></div>";  status=false}
	if (isEmpty(document.forms["deviceEdit"].elements["snmp_timeout"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- SNMP timeout is required</font></div>";  status=false}
	if (isEmpty(document.forms["deviceEdit"].elements["snmp_retries"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- SNMP retries required</font></div>";  status=false} 
	if (isEmpty(document.forms["deviceEdit"].elements["device_dns"].value) && isEmpty(document.forms["deviceEdit"].elements["device_ip_address"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- DNS or IP address are required</font></div>";  status=false}
	if (isEmpty(document.forms["deviceEdit"].elements["deviceGroupID"].value)) { error_status.innerHTML = error_status.innerHTML + "<div><font color=\"red\">- Device Group is required</font></div>";  status=false}
	if (isEmpty(error_status.innerHTML) == false) error_status.innerHTML = "<font color=\"red\">The following errors occured:</font>" + error_status.innerHTML;
	
	return status; 
}

function varGroupMemberSubmit(varGroupID)
{
	if (isEmpty(varGroupID)==false) listbox_selectall('varGroupMember', true);
	cleanErrorMessage(Array("name"));
	var name = document.forms["varGroupEdit"].elements["name"].value;
	if (isEmpty(name) == true) { setErrorMessage("name", "Group name is required"); return}
	else {document.getElementById("varGroupEdit").submit();}
}

function submitVarEdit()
{
	cleanErrorMessage(Array("label", "oid"));
	var status = true;
	var label = document.forms["varEdit"].elements["label"].value;
	var oid= document.forms["varEdit"].elements["oid"].value;
	if (isEmpty(label) == true) { setErrorMessage("label", "Variable label is required"); status = false}
	if (isEmpty(oid) == true) { setErrorMessage("oid", "Variable OID is required"); status = false}
	if (isEmpty(oid) == false && validateOID(oid) == false) { setErrorMessage("oid", "OID is incorrect"); status = false}
	return  status;
}

function submitGroupEdit()
{
	cleanErrorMessage(Array("name", "max_users", "max_devices"));
	var status = true;
	var name = document.forms["groupEdit"].elements["name"].value;
	var max_users = document.forms["groupEdit"].elements["max_users"].value;
	var max_devices = document.forms["groupEdit"].elements["max_devices"].value;
	if (isEmpty(name) == true) { setErrorMessage("name", "Group name is required"); status = false}
	if (validateNumber(max_users) == false) { setErrorMessage("max_users", "Not a number. Maximum number of users is required"); status = false}
	if (validateNumber(max_devices) == false) { setErrorMessage("max_devices", "Not a number. Maximum number of devices is required"); status = false}
	return status;
	return true;
}

function submitDeviceGroupEdit(role)
{
	cleanErrorMessage(Array("name"));
	var status = true;
	var name = document.forms["deviceGroupEdit"].elements["name"].value;
    var groupID;
    if (role == 'administrator')
    {
        groupID = document.forms["deviceGroupEdit"].elements["groupID"].value;
        if (isEmpty(groupID) == true) { setErrorMessage("groupID", "Domain is required"); status = false}
        
	}
    if (isEmpty(name) == true) { setErrorMessage("name", "Device group name is required"); status = false}
	return  status;
	
}

function submitFormConfirm(id)
{
	var agree=confirm("Вы действительно хотите удалить выбранные записи? Так же будут удалены связанные записи в заказах");
	if (agree == false) return;
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "deleteConfirmation");
	input.setAttribute("value", "987asd9fwe87s9df87");
	document.forms[id].appendChild(input);
	document.forms[id].submit();
	//alert("ok");
}

function submitEditFormConfirm(id)
{
	var agree=confirm("Сохранить изменения?");
	if (agree == false) return;
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "editConfirmation");
	input.setAttribute("value", "987asd9fwe87s9df87");
	document.forms[id].appendChild(input);
	document.forms[id].submit();
	//alert("ok");
}


function submitFormConfirmAll(id)
{
	var agree=confirm("Are you sure you want to delete all records?");
	if (agree == false) return;
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "deleteConfirmationAll");
	input.setAttribute("value", "987asd9f87efefs9df87");
	document.forms[id].appendChild(input);
	document.forms[id].submit();
	
}

function submitFormAck(id)
{
	var agree=confirm("Are you sure you want to acknowledge selected records?");
	if (agree == false) return;
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "ackConfirmation");
	input.setAttribute("value", "987aswdd9f87s9df87");
	document.forms[id].appendChild(input);
	document.forms[id].submit();
	//alert("ok");
}

function setCarAndPhone(elem_car_id, phone)
{
	document.getElementById(elem_car_id).selected = true;
	document.getElementById('driver_phone').innerHTML = phone;
	// Автозаполнение решения при выборе водителя:
	FillSolution();
}

function FillSolution()
{
	document.getElementById('solution').value = "";
	var el = document.getElementById('car_id');
	var car = trim(el.options[el.selectedIndex].text);
	var phone = trim(document.getElementById('driver_phone').innerHTML);
	
	if (isEmpty(car) || car == 'не назначен') { alert('Не назначен автомобиль'); return false }
	if (isEmpty(phone)) { alert('Не назначен водитель либо телефон пустой'); return false }
	
	document.getElementById('solution').value = trim(document.getElementById('solution').value);
	if (isEmpty(document.getElementById('solution').value))
	{
		document.getElementById('solution').value = 'Назначен автомобиль '+car+', номер телефона водителя '+phone;
	}
	else
	{
		document.getElementById('solution').value += '\nНазначен автомобиль '+car+', номер телефона водителя '+phone;
	}
	return true;
}

function OrderEditCheck(id)
{
	var agree=confirm("Сохранить изменения?");
	if (agree == false) return;
	
	var status = document.getElementById('status');
	var status_id = status.value;
	var status_text = status.options[status.selectedIndex].text;
	
	var solution = document.getElementById('solution').value;
	var driver_id = document.getElementById('driver_id').value;
	var credit_id = document.getElementById('credit_id').value;
	var car_id = document.getElementById('car_id').value;
	var address_from = document.getElementById('address_from').value;
	var address_to = document.getElementById('address_to').value;
	
	var currentStatus = document.getElementById('currentStatus').value;
	var userRole = document.getElementById('userRole').value;
	
	var start_date;
	var start_date_Hour = document.getElementById('start_date_Hour').value;
	var start_date_Minute = document.getElementById('start_date_Minute').value;
	var start_date_Month = document.getElementById('start_date_Month').value;
	var start_date_Day = document.getElementById('start_date_Day').value;
	var start_date_Year = document.getElementById('start_date_Year').value;
	
	
	var end_date;
	var end_date_Hour = document.getElementById('end_date_Hour').value;
	var end_date_Minute = document.getElementById('end_date_Minute').value;
	var end_date_Month = document.getElementById('end_date_Month').value;
	var end_date_Day = document.getElementById('end_date_Day').value;
	var end_date_Year = document.getElementById('end_date_Year').value;

	/*if (isNotEmpty(start_date_Hour) || isNotEmpty(start_date_Minute) || isNotEmpty(start_date_Month) || isNotEmpty(start_date_Year) || isNotEmpty(start_date_Day)) 
	{
		if (isEmpty(start_date_Hour) || isEmpty(start_date_Minute) || isEmpty(start_date_Month) || isEmpty(start_date_Year) || isEmpty(start_date_Day))
		{
			alert("Неверный ввод даты или времени выезда!");
			return;
		}
		
	}*/
	
	if (status_id != 6 && (isEmpty(start_date_Hour) || isEmpty(start_date_Minute) || isEmpty(start_date_Month) || isEmpty(start_date_Year) || isEmpty(start_date_Day)))
	{
		alert("Неверный ввод даты или времени выезда!");
		return;
	}
		

	if (isNotEmpty(end_date_Hour) || isNotEmpty(end_date_Minute) || isNotEmpty(end_date_Month) || isNotEmpty(end_date_Year) || isNotEmpty(end_date_Day)) 
	{
		if (isEmpty(end_date_Hour) || isEmpty(end_date_Minute) || isEmpty(end_date_Month) || isEmpty(end_date_Year) || isEmpty(end_date_Day))
		{
			alert("Неверный ввод даты или времени окончания поездки!");
			return;
		}
	}
	
	if (status_id == 5 && (isEmpty(end_date_Hour) || isEmpty(end_date_Minute) || isEmpty(end_date_Month) || isEmpty(end_date_Year) || isEmpty(end_date_Day)))
		{
			alert("Статус \"Выполнен\" требует ввода даты и времени окончания поездки!");
			return;
		}
	
	if (in_array([3,4,5,6], status_id) && isEmpty(solution))
	{
		alert("Статус \""+ status_text+ "\" требует заполненного поля \"Решение\"!");
		return;
	}
	else if (in_array([4,5], status_id) && isEmpty(driver_id))
	{
		alert("Статус \""+ status_text+ "\" требует определить водителя!");
		return;
	}
	else if (in_array([4,5], status_id) && isEmpty(car_id))
	{
		alert("Статус \""+ status_text+ "\" требует определить автомобиль!");
		return;
	}
	else if (userRole != 'administrator' &&  status_id < currentStatus)
	{
		alert("Невозможно изменить статус на более низкий!\nЭто может сделать только администратор")
		return;
	}
	// else if (in_array([3,4,5], status_id) && isEmpty(credit_id))
	// {
		// alert("Укажите номер агента!");
		// return;
	// }
	
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "editConfirmation");
	input.setAttribute("value", "987asd9fwe87s9df87");
	document.forms[id].appendChild(input);
	document.forms[id].submit();
}

function SetUserPhone(phone)
{
	document.getElementById('user_phone').value = phone;
}

function AddUserPhoneToSolution()
{
	document.getElementById('solution').value += document.getElementById('user_phone').value;
}

function in_array(arr, obj) {
    for(var i=0; i<arr.length; i++) {
        if (arr[i] == obj) return true;
    }
}

function ReportFilterEnable()
{
	var rep = document.getElementById('report').value;
	
	var driver_id = document.getElementById('driver_id');
	var credit_id = document.getElementById('credit_id');
	var user_id = document.getElementById('user_id');
	var status = document.getElementById('status');
	var type = document.getElementById('type');
	var sb_user = document.getElementById('sb_user');
	if ((rep == 'rep_01') || (rep == 'rep_03')|| (rep == 'rep_04'))
	{
		driver_id.disabled = false;
		status.disabled = false;
		user_id.disabled = false;
		credit_id.disabled = false;
		type.disabled = false;
		sb_user.disabled = false;
	}
	else 
	{
		driver_id.disabled = true;
		status.disabled = true;
		user_id.disabled = true;
		credit_id.disabled = true;
		type.disabled = true;
		sb_user.disabled = true;
	}
}
	
function convertMinutes(minutes) {
return (minutes/60).toFixed(2);
  // if (minutes == 0) {
    // return 0;
  // }
  // else if (minutes == 15) {
      // return 0.25;
    // }
    // else if (minutes == 30) {
        // return 0.5;
      // }
      // else if (minutes == 45) {
          // return 0.75;
        // }
  // if (minutes >= 0 && minutes < 15) {
  //   return 0;
  // }
  // else if (minutes >= 15 && minutes < 35) {
  //     return 0.25;
  //   }
  // else if (minutes >= 35 && minutes < 40) {
  //       return 0.5;
  //     }
  // else if (minutes >= 40 && minutes < 59) {
  //         return 0.75;
  //       }
      }



function priceCalc() {
    var tariff = document.getElementById("tariff").value;
	if (tariff.length == 0) {
	alert("Заполните тариф!");
	return;
	}
	
	var tmp_kmroute;
    var km_route = document.getElementById("km_route").value;

    var e1 = document.getElementById("timeradius50km");
    var timeradius50km = e1.options[e1.selectedIndex].value;
    var e2 = document.getElementById("timeradius50km_minute");
    var timeradius50km_minute = e2.options[e2.selectedIndex].value;

    var q1 = document.getElementById("lagtime50km");
    var lagtime50km = q1.options[q1.selectedIndex].value;
    var q2 = document.getElementById("lagtime50km_minute");
    var lagtime50km_minute = q2.options[q2.selectedIndex].value;
	
	if ((timeradius50km_minute.length == 0) || (timeradius50km.length == 0)) {
	alert("Заполните время работы!");
	return;
	}
	if ((lagtime50km_minute.length == 0) || (lagtime50km.length == 0)) {
	  alert("Заполните время простоя!");
	  return;
	}


  // if (km_route >= 0 && km_route < 50) {
    // tmp_kmroute=0;
  // }
  // else if (km_route >= 50 && km_route < 100) {
      // tmp_kmroute=1904.8;
    // }
  // else if (km_route >= 100 && km_route < 150) {
        // tmp_kmroute=3025.8;
      // }
  // else if (km_route >= 150 && km_route < 200) {
          // tmp_kmroute=4146.8;
        // }
  // else if (km_route >= 200 && km_route < 250) {
          // tmp_kmroute=5267.8;
        // }
  // else if (km_route >= 250 && km_route < 300) {
          // tmp_kmroute=6388.8;
        // }
  // else if (km_route >= 300 && km_route < 350) {
          // tmp_kmroute=7509.8;
        // }
  // else if (km_route >= 350 && km_route < 400) {
          // tmp_kmroute=8630.8;
        // }
  // else if (km_route >= 400 && km_route < 450) {
          // tmp_kmroute=9751.8;
        // }
  // else if (km_route >= 450 && km_route < 500) {
          // tmp_kmroute=10872.8;
        // }
  // else if (km_route >= 500 && km_route < 1000) {
          // tmp_kmroute=10872.8;
        // }

var timeradius50km_final = parseFloat(timeradius50km) + parseFloat(convertMinutes(timeradius50km_minute));
var lagtime50km_final = parseFloat(lagtime50km) + parseFloat(convertMinutes(lagtime50km_minute));
console.log(timeradius50km_final,lagtime50km_final);

var price = 0;
var price_descr = "";

if (tariff=='T1') {
price = (timeradius50km_final * 464)+(km_route * 17 + lagtime50km_final * 464);
price_descr = '('+timeradius50km_final+' * 464)+('+km_route+' * 17 + '+lagtime50km_final+' * 464) = '+price.toFixed(2);
}
else if (tariff=='T2'){
price = (timeradius50km_final * 424)+(km_route * 20 + lagtime50km_final * 424);
price_descr = '('+timeradius50km_final+' * 424)+('+km_route+' * 20 + '+lagtime50km_final+' * 424) = '+price.toFixed(2);
}
else if (tariff=='T3'){
price = (timeradius50km_final * 448)+(km_route * 16.6 + lagtime50km_final * 240);
price_descr = '('+timeradius50km_final+' * 448)+('+km_route+' * 16.6 + '+lagtime50km_final+' * 240) = '+price.toFixed(2);
}

// if (tmp_kmroute == 0) {
    // price = timeradius50km_final * 453.1;
    // console.log(timeradius50km_final+'*453.1');
	// price_descr = timeradius50km_final+'*453.1 = '+price.toFixed(2);
// }
// else {
    // price  = (lagtime50km_final * 453.1) + parseFloat(tmp_kmroute);
    // console.log(lagtime50km_final+'*453.1 + '+parseFloat(tmp_kmroute));
	// price_descr = lagtime50km_final+'*453.1 + '+parseFloat(tmp_kmroute)+' = ' + price.toFixed(2);
// }

price = (price.toFixed(2)).replace('.',',');
console.log("price - "+price);
document.getElementById("price").value = price;

var td_price = document.getElementById('price_descr');
td_price.textContent = "";
td_price.textContent = price_descr;

}	


function priceCalcList(orderId) {
var t1 = document.getElementById("tariff" + "_" + orderId);
var tariff = t1.options[t1.selectedIndex].value;
console.log(tariff);
	if (tariff.length == 0) {
	alert("Заполните тариф!");
	return;
	}
  var tmp_kmroute;
    var km_route = document.getElementById("km_route" + "_" + orderId).value;

    var e1 = document.getElementById("timeradius50km" + "_" + orderId);
    var timeradius50km = e1.options[e1.selectedIndex].value;
    var e2 = document.getElementById("timeradius50km_minute" + "_" + orderId);
    var timeradius50km_minute = e2.options[e2.selectedIndex].value;

    var q1 = document.getElementById("lagtime50km" + "_" + orderId);
    var lagtime50km = q1.options[q1.selectedIndex].value;
    var q2 = document.getElementById("lagtime50km_minute" + "_" + orderId);
    var lagtime50km_minute = q2.options[q2.selectedIndex].value;

  // if (km_route >= 0 && km_route < 50) {
    // tmp_kmroute=0;
  // }
  // else if (km_route >= 50 && km_route < 100) {
      // tmp_kmroute=1904.8;
    // }
  // else if (km_route >= 100 && km_route < 150) {
        // tmp_kmroute=3025.8;
      // }
  // else if (km_route >= 150 && km_route < 200) {
          // tmp_kmroute=4146.8;
        // }
  // else if (km_route >= 200 && km_route < 250) {
          // tmp_kmroute=5267.8;
        // }
  // else if (km_route >= 250 && km_route < 300) {
          // tmp_kmroute=6388.8;
        // }
  // else if (km_route >= 300 && km_route < 350) {
          // tmp_kmroute=7509.8;
        // }
  // else if (km_route >= 350 && km_route < 400) {
          // tmp_kmroute=8630.8;
        // }
  // else if (km_route >= 400 && km_route < 450) {
          // tmp_kmroute=9751.8;
        // }
  // else if (km_route >= 450 && km_route < 500) {
          // tmp_kmroute=10872.8;
        // }
  // else if (km_route >= 500 && km_route < 1000) {
          // tmp_kmroute=10872.8;
        // }
if ((timeradius50km_minute.length == 0) || (timeradius50km.length == 0)) {
  alert("Заполните время работы в радиусе 50 км!");
  return;
}
if ((lagtime50km_minute.length == 0) || (lagtime50km.length == 0)) {
  alert("Заполните время простоя!");
  return;
}

var timeradius50km_final = parseFloat(timeradius50km) + parseFloat(convertMinutes(timeradius50km_minute));
var lagtime50km_final = parseFloat(lagtime50km) + parseFloat(convertMinutes(lagtime50km_minute));
console.log(timeradius50km_final,lagtime50km_final);

var price = 0;
var price_descr = "";

if (tariff=='T1') {
price = (timeradius50km_final * 464)+(km_route * 17 + lagtime50km_final * 464);
}
else if (tariff=='T2'){
price = (timeradius50km_final * 424)+(km_route * 20 + lagtime50km_final * 424);
}
else if (tariff=='T3'){
price = (timeradius50km_final * 448)+(km_route * 16.6 + lagtime50km_final * 240);
}


// if (tmp_kmroute == 0) {
    // price = timeradius50km_final * 453.1;
    // console.log(timeradius50km_final+'*453.1');
	// price_descr = timeradius50km_final+'*453.1 = '+price.toFixed(2);
// }
// else {
    // price  = (lagtime50km_final * 453.1) + parseFloat(tmp_kmroute);
    // console.log(lagtime50km_final+'*453.1 + '+parseFloat(tmp_kmroute));
	// price_descr = lagtime50km_final+'*453.1 + '+parseFloat(tmp_kmroute)+' = ' + price.toFixed(2);
// }


price = (price.toFixed(2)).replace('.',',');
console.log("price - "+price);
var price_insert = document.getElementById('price' + "_" + orderId);
price_insert.value = "";
price_insert.value = price;
// document.getElementById("price" + "_" + orderId).textContent = price;

// var td_price = document.getElementById('price_descr');
// td_price.textContent = "";
// td_price.textContent = price_descr;

}	
$(document).ready(function() { 

if ($('#sb_seeall').is(':checked')) {
	$('#sb_user').disabled = true;
	$("#sb_user").prop("disabled", 'disabled');
}


$('#sb_seeall').change(function() {
    $('#sb_user').attr('disabled', this.checked)
	
});

$('#filter_date').datepick({
dateFormat: 'dd.mm.yyyy',
onSelect: function() {
dashboard.submit();
}

});

//неактивная дата выезда

$(".show_date").prop('disabled', !$('#show_date_checkbox').is(':checked'));

$('#show_date_checkbox').change(function() {
  $(".show_date").prop('disabled', !$(this).is(':checked'))
});


//Запретить знак "-"
$("input[id^='km_route'], input[id^='price']").keypress(function(event) {
  if ( event.which == 45 || event.which == 189 ) {
      event.preventDefault();
   }
}); 

// Подсветка просрочки
var now = moment().unix(); // время сейчас
var orangeTresh = 24*3600; // часы
var redTresh = 3*3600;
$('.summary tr').each(function() {
	var $trObj = $(this);
	var statusFlag = 0;
	 $(this).find('td:nth-child(2)').each(function(){
        var cell = $(this).text();
		if (cell.includes("Новый")) {
			statusFlag = 1;
			}
		})

	// подсветка только по новым заявкам, игнор решенных
	if (statusFlag == 1) {
		$('td:nth-child(5)',$(this)).each(function() {
			var dateText = $(this).text();
			var srok = moment(dateText, 'DD.M.YYYY HH:mm').unix(); // конвертация контрольного срока в секунды (unix)
			var diff = srok - now; 
			if (diff < redTresh) {
				$($trObj).css("background-color", "#FFD8D8");
			}
			else if (diff < orangeTresh) {
				$($trObj).css("background-color", "#F9FF85");
			}
		});
	}
 });



});

