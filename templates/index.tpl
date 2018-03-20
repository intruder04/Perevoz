<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"><head>

  <title>Такси "ГК ПеревозчикЪ" Краснодар v1.3</title>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
  <meta http-equiv="content-language" content="en-us">
  {if $page eq "dashboard"}
  <meta http-equiv="refresh" content="{$dashboardRefresh}">
  {/if}


   <link href="images/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>

 
<link rel="stylesheet" type="text/css" href="js/datepick/css/redmond.datepick.css">
<script type="text/javascript" src="js/datepick/js/jquery.plugin.js"></script> 
<script type="text/javascript" src="js/datepick/js/jquery.datepick.min.js"></script>
<script type="text/javascript" src="js/datepick/js/jquery.datepick-ru.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/functions.js"></script>




</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">

<div id="center">
<div id="top" style="background: rgb(31, 51, 78) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">
<table width="100%" cellpadding="0" cellspacing="0">
<tbody><tr>
<td><span style="font: italic 8pt arial; color: #ffffff">Server Time: {$server_time}</span></td><td align="right" valign="middle"> <b><span style="font: 8pt arial; color: #ffffff">{$session_login}{$session_info}</b> (<a  style="font: 8pt arial; " href="{$smarty.server.PHP_SELF}?page=logOut">Выход</a>)</span></td><td align="right" width="18px" valign="middle">&nbsp;</td></tr>
</tbody></table>
</div>
<table width="100%">
<tr><td align="left"><br>{$SystemLogoLeft}<br><br></td><td align="right"><font color="#3964B3" style="font: 15px arial, sans-serif">{$SystemLogoRight}</td></tr></table>
<div class="menu2" style="float: right;">
<ul>
	<li><a class="menu2four" href="{$smarty.server.PHP_SELF}?page=dashboard"><img src="/images/buttons/home_16x16.png" align="absmiddle" border="0"> Главная</a></li>
</ul>
{section name=i loop=$mainmenu}
<ul>
	<li><a><img src="images/buttons/{$mainmenu[i].name}_16x16.png" align="absmiddle" border="0"> {$mainmenu[i].translation} </a>
	      {$mainmenu[i].submenu}
	 </li>
</ul>
{/section}
<ul>
	<li><a class="menu2four" href="{$smarty.server.PHP_SELF}?page=dashboardTranspData&arch=1"><img src="/images/16/report_disk.png" align="absmiddle" border="0"> Данные по поездкам</a></li>
</ul>
<ul>
	<li><a class="menu2four" href="{$smarty.server.PHP_SELF}?page=dashboard&arch=1"><img src="/images/16/page_save.png" align="absmiddle" border="0"> Архив</a></li>
</ul>
<!-- <ul> -->
	<!-- <li><a class="menu2four" href="#" onclick = "window.open('drivers.php', 'Водители','width=100px,height=600px,resizable=yes,scrollbars=yes,location=no, status=no, menubar=no')"><img src="/images/16/group_gear.png" align="absmiddle" border="0"> Водители</a></li> -->
<!-- </ul> -->
<ul>
	<li><a class="menu2four" href="{$smarty.server.PHP_SELF}?page=report"><img src="/images/16/report_disk.png" align="absmiddle" border="0"> Отчеты</a></li>
</ul>

</div><br> {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }<br>{/if}
<table width="100%" border='0'>
<tr valign='middle'>
	<td rowspan="2" width="40px" valign='top'><img src="{$image}"></td>
	<td  valign="middle" align="left"><span id="main" class="main">{$main_header}</span></td>
</tr>
<tr><td><span id="main_message"><strong>{$main_message}&nbsp;</strong></span></td><td><span id="sms_message"><strong>{$sms_message}&nbsp;</strong></span></td><td>{if (preg_match("/list/i",$page)) || $page eq 'dashboard' || $page eq 'dashboardTranspData'}<form method="POST" name="filter" action="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}"><div align="right">Найти: <input type="text" name="search_str" value="{$search_str}"><span class="buttons-small" ><button type="submit" class="regular"><img src="images/buttons/Search_16x16.png" alt=""/></button></span></div></form>{else}&nbsp;{/if}</td></tr>
</table><hr>
{if $page eq "userList"}
{if $user}
<form id="userList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Логин</th><th>E-mail</th><th>Роль</th><th>Создание внутренних<br>заявок</th><th>Пользователь в банке</th><th>&nbsp;</th></tr>
{section name=i loop=$user}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delUserIDs[]" value="{$user[i].id}"></td><td>{$user[i].login}</td><td>{$user[i].email}</td><td>{$user[i].role}</td><td align="center">{if $user[i].orderint eq '1'}разрешено{else}-{/if}</td><td>{$user[i].sb_user}</td><td><a href="{$smarty.server.PHP_SELF}?page=userEdit&userID={$user[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("userList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
There are no user accounts in this domain
{/if}
{elseif $page eq "userEdit"}

<form id="userEdit" name="userEdit" method="post" onsubmit="javascript:return submitUserEdit('{$user.id}')">
<table cellpadding="2" cellspacing="1" class="devicetable" border='0'>
	<tr><td colspan = 4><font size="-2">***************** Внимание!!! Все поля заполняются ЛАТИНСКИМИ БУКВАМИ! ******************</font></td></tr>
	<tr>
		<td class="list-bold">Логин<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="login" name="login" value="{$user.login}"></td>
		<td align="left" valign="middle"><span id="status_login_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_login">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">E-Mail<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="email" name="email" value="{$user.email}"></td>
		<td align="left" valign="middle"><span id="status_email_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_email">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Пароль<font size="-2">*</font>{if $user}<font size="-2">*</font>{/if}:</td>
		<td align="right"><input type="password" name="password"></td>
		<td align="left" valign="middle"><span id="status_password_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_password">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Подтверждение пароля<font size="-2">*{if $user}<font size="-2">*</font>{/if}</font>:</td>
		<td align="right"><input type="password" name="retypePassword"></td>
		<td align="left" valign="middle"><span id="status_retypePassword_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_retypePassword">&nbsp;</span></td>
	</tr>
  	<tr>
		<td class="list-bold">Роль:</td>
		<td align="right"><select id='role' name='role' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					{html_options options=$userRoles selected=$user.role}
			</select></td>
		<td align="left">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="list-bold">Телефон:<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="phone" name="phone" value="{$user.phone}"></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Населенный пункт:</td>
		<td align="right"><select id='location_id' name='location_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не выбран</option>
					{html_options options=$locations selected=$user.location_id}
			</select></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Разрешить создание заявок:</td>
		<td align="right"><input style="width: 15px" type="checkbox" name="orderint" value=1 {if $user.orderint eq '1'}checked{/if}></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Видит все заявки:</td>
		<td align="right"><input style="width: 15px" type="checkbox" id="sb_seeall" name="sb_seeall" value=1 {if $user.sb_seeall eq '1'}checked{/if}></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Исполнитель в СБ (видимость):</td>
		<td align="right"><input type="text" id="sb_user" name="sb_user" value="{$user.sb_user}"></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td align="right"><span class="buttons"><button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Save</button></span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<font size="-2">* обязательные поля</font><br>

{if $user}<font size="-2">** Оставьте поле пустым, если не хотите менять пароль</font><br>{/if}
</form>
{elseif $page eq "carList"}
{if $car}
<form id="carList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Регистрационный номер</th><th>Модель</th><th>Цвет</th><th>Местоположение</th><th>&nbsp;</th></tr>
{section name=i loop=$car}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delCarIDs[]" value="{$car[i].id}"></td><td>{$car[i].regnum}</td><td>{$car[i].model}</td><td>{$car[i].color}</td><td>{$car[i].loc_name}</td><td><a href="{$smarty.server.PHP_SELF}?page=carEdit&carID={$car[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("carList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
Нет данных
{/if}
{elseif $page eq "tariffs"}
{if $car}
<form id="carList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Регистрационный номер</th><th>Модель</th><th>Цвет</th><th>&nbsp;</th></tr>
{section name=i loop=$car}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delCarIDs[]" value="{$car[i].id}"></td><td>{$car[i].regnum}</td><td>{$car[i].model}</td><td>{$car[i].color}</td><td><a href="{$smarty.server.PHP_SELF}?page=carEdit&carID={$car[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("carList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
Нет данных
{/if}
{elseif $page eq "locationList"}
{if $location}
<form id="locationList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Населенный пункт</th></tr>
{section name=i loop=$location}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delLocationIDs[]" value="{$location[i].id}"></td><td>{$location[i].name}</td><td><a href="{$smarty.server.PHP_SELF}?page=locationEdit&locationID={$location[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("locationList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
Нет данных
{/if}
{elseif $page eq "creditList"}
{if $credit}
<form id="creditList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Номер кредита</th><th>Комментарий</th></tr>
{section name=i loop=$credit}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delCreditIDs[]" value="{$credit[i].id}"></td><td>{$credit[i].name}</td><td>{$credit[i].descr}</td><td><a href="{$smarty.server.PHP_SELF}?page=creditEdit&creditID={$credit[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("creditList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
Нет данных
{/if}
{elseif $page eq "driverList"}
{if $driver}
<form id="driverList" method="post">
<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>&nbsp;</th><th>Имя</th><th>Позывной</th><th>Телефон</th><th>Населенный пункт</th><th>Автомобиль</th><th>Статус</th><th>&nbsp;</th></tr>
{section name=i loop=$driver}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}"><td><input type="checkbox" class="checkbox" name="delDriverIDs[]" value="{$driver[i].id}"></td><td>{$driver[i].fullname}</td><td>{$driver[i].nick}</td><td>{$driver[i].phone}</td><td>{$driver[i].location}</td><td>{$driver[i].car}</td><td>{$driver[i].status}</td><td><a href="{$smarty.server.PHP_SELF}?page=driverEdit&driverID={$driver[i].id}">Редактирование</td></tr>
{/section}
</table><br>
<div align="right" width="100%"><span class="buttons"><button type="button" class="regular" name="deleteDeviceButton" onclick=submitFormConfirm("driverList")><img src="images/buttons/Delete_16x16.png" alt=""/>Delete</button></span></div>
</form>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>
{else}
Нет данных
{/if}

{elseif $page eq "carEdit"}
<form id="carEdit" name="carEdit" method="post" onsubmit="javascript:return submitCarEdit('{$car.id}')">
<table cellpadding="2" cellspacing="1" class="devicetable" border='0'>
	<tr>
		<td class="list-bold">Регистрационный номер<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="regnum" name="regnum" value="{$car.regnum}"></td>
		<td align="left" valign="middle"><span id="status_regnum_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_regnum">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Модель<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="model" name="model" value="{$car.model}"></td>
		<td align="left" valign="middle"><span id="status_model_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_model">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Цвет</td>
		<td align="right"><input type="text" name="color" value="{$car.color}"></td>
		<td align="left" valign="middle"><span id="status_color_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_color">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Населенный пункт:</td>
		<td align="right"><select id='location_id' name='location_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					{html_options options=$locations selected=$car.location_id}
			</select></td>
		<td align="left" valign="middle">&nbsp;</span></td>
		<td align="left" valign="middle">&nbsp;</span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><span class="buttons">
		<button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button>
		</span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<font size="-2">* Обязательные поля</font><br>
</form>
{elseif $page eq "locationEdit"}
<form id="locationEdit" name="locationEdit" method="post" onsubmit="javascript:return submitLocationEdit('{$location.id}')">
<table cellpadding="2" cellspacing="1" class="devicetable" border='0'>
	<tr>
		<td class="list-bold">Населенный пункт<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="name" name="name" value="{$location.name}"></td>
		<td align="left" valign="middle"><span id="status_name_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_name">&nbsp;</span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><span class="buttons"><button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button></span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<font size="-2">* Обязательные поля</font><br>
</form>
{elseif $page eq "creditEdit"}
<form id="creditEdit" name="creditEdit" method="post" onsubmit="javascript:return submitCreditEdit('{$credit.id}')">
<table cellpadding="2" cellspacing="1" class="devicetable" border='0'>
	<tr>
		<td class="list-bold">Номер кредита<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="name" name="name" value="{$credit.name}"></td>
		<td align="left" valign="middle"><span id="status_name_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_name">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Комментарий:</td>
		<td align="right"><input type="text" id="descr" name="descr" value="{$credit.descr}"></td>
		<td align="left" valign="middle">&nbsp;</td>
		<td align="left" valign="middle">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><span class="buttons"><button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button></span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<font size="-2">* Обязательные поля</font><br>
</form>
{elseif $page eq "driverEdit"}
<form id="driverEdit" name="driverEdit" method="post" onsubmit="javascript:return submitDriverEdit('{$driver.id}')">
<table cellpadding="2" cellspacing="1" class="devicetable" border='0'>
	<tr>
		<td class="list-bold">Имя<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="fullname" name="fullname" value="{$driver.fullname}"></td>
		<td align="left" valign="middle"><span id="status_fullname_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_fullname">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Позывной<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="nick" name="nick" value="{$driver.nick}"></td>
		<td align="left" valign="middle"><span id="status_nick_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_nick">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Телефон<font size="-2">*</font>:</td>
		<td align="right"><input type="text" id="phone" name="phone" value="{$driver.phone}"></td>
		<td align="left" valign="middle"><span id="status_phone_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_phone">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Населенный пункт<font size="-2">*</font>:</td>
		<td align="right"><select id='location_id' name='location_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					{html_options options=$locations selected=$driver.location_id}
			</select></td>
		<td align="left">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="list-bold">Автомобиль:</td>
		<td align="right"><select id='car_id' name='car_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не выбран!!!</option>
					{html_options options=$cars selected=$driver.car_id}
			</select></td>
		<td align="left">&nbsp;</td>
		<td>&nbsp;</td>

	</tr>
	<tr>
		<td class="list-bold">Статус:</td>
		<td align="right"><select id='status' name='status' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					{html_options options=$driverStatuses selected=$driver.status}
			</select></td>
		<td align="left">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td align="right"><span class="buttons"><button type="submit" class="regular" id="editButton" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button></span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<font size="-2">* Обязательные поля</font><br>
</form>
{elseif $page eq "message"}
{$message}
{elseif $page eq "dashboard"}
<form id="dashboard" name="dashboard" method="post" action="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}">
<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr bgcolor='#eeeeee' >
<td  style="padding: 5px;" align="right">Дата выезда:
<input style="width: 90px;" onkeydown="dashboard.submit()" type="text" id="filter_date" name="filter_date" value="{$selectedFilterDate}" ></td>
<td  style="padding: 5px;" align="right">Тип заявки:</td>
<td width='20px' align="left"> <select name='filterType' onChange="dashboard.submit()"><option value='all'>Все</option>{html_options options=$filterTypes selected=$selectedFilterType}</select></td>
<td  style="padding: 5px;" align="right">Статус:</td>
<td width='20px' align="left"> <select name='filterStatus' onChange="dashboard.submit()"><option value='all'>Все</option>{html_options options=$filterStatuses selected=$selectedFilterStatus}</select></td>
<td width='200px' align="right"><nobr>Заявок на странице:</nobr></td>
<td><select name='pageDashboardSize' onChange="dashboard.submit()">
					{html_options values=$pageDashboardSizes output=$pageDashboardSizes selected=$selectedDashboardPageSize}
				</select></td>
<td align = "right" width='250px' style="padding: 5px;">
	<span class="buttons"><button type="submit" class="regular" name="Refresh"><img src="images/buttons/Refresh_16x16.png" alt=""/>Обновить</button>{if $orderint}<button type="button" class="regular" name="neworder" onclick="location.href='/index.php?page=dashboardDetail'"><img src="images/buttons/Download_16x16.png" alt=""/>Создать</button>{/if}</span></form>
</td>
</tr>
</table>
<br>
<table cellpadding="2" cellspacing="1" class="summary" width="100%" >
<tr valign="middle">
<th width="20px">&nbsp;</th>
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=status&dashboardSortDirection={if $dashboardSortBy eq 'status'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Статус</a></u>{if $dashboardSortBy eq 'status'}{$sortImage}{/if}</th>
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=sd_number&dashboardSortDirection={if $dashboardSortBy eq 'sd_number'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Номер СБ</a></u>{if $dashboardSortBy eq 'sd_number'}{$sortImage}{/if}</th>

<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=start_date&dashboardSortDirection={if $dashboardSortBy eq 'start_date'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Дата создания</a></u>{if $dashboardSortBy eq 'start_date'}{$sortImage}{/if}</th>
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=sb_srok&dashboardSortDirection={if $dashboardSortBy eq 'sb_srok'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Срок в СБ</a></u>{if $dashboardSortBy eq 'sb_srok'}{$sortImage}{/if}</th>
<!-- <th>Срок в СБ</th> -->
<th>Заказчик</th>
<!--<th>Направление</th>-->
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=departure_date&dashboardSortDirection={if $dashboardSortBy eq 'departure_date'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Выезд</a></u>{if $dashboardSortBy eq 'departure_date'}{$sortImage}{/if}</th>
<th>Откуда/Куда</th>
<!--<th>Обратно</th>
<th>Откуда/Куда</th>-->
<th>Автомобиль</th>
<th>Водитель</th>
<th>Диспетчер</th>

</tr>
{section name=i loop=$order}
<tr class="{$order[i].order_row_class}">
    <td>{if $order[i].vip}<img src="images/16/flag_red.png">{else}&nbsp;{/if}</td>
	<td valign="middle"><b><nobr><img src="images/16/{if $order[i].status eq '1'}page_lightning.png{elseif $order[i].status eq '2'}page_gear.png{else}page.png{/if}" >&nbsp; <a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$order[i].id}">{$order[i].status_str}</a></nobr></b></td>
	<td><b><nobr><a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$order[i].id}">{$order[i].sd_number}</a></nobr></b></td>

	<td>{$order[i].start_date}</td>
	<td>{$order[i].sb_srok}</td>
	<td>{$order[i].customer}</td>
	<!--<td>{$order[i].direction}</td>-->
	<td>{$order[i].departure_date}</td>
	<td>{if $order[i].address_from}{$order[i].address_from} => <br>{$order[i].address_to}{else}&nbsp;{/if}</td>
	<!--<td>{$order[i].ret_dep_date}<br>{$order[i].ret_dep_time}</td>
	<td>{if $order[i].ret_address_from}{$order[i].ret_address_from} => <br>{$order[i].ret_address_to}{else}&nbsp;{/if}</td>-->
	<td>{$order[i].car}</td>
	<td>{$order[i].driver}</td>

	<td>{$order[i].user}</td>

</tr>
{sectionelse}
<tr style="row_gray"><td colspan='10'>Заявки не найдены</td></tr>
{/section}
</table>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>

{elseif $page eq "dashboardTranspData"}
<form id="dashboard" name="dashboard" method="post" action="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}">
<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr bgcolor='#eeeeee' >
<td  style="padding: 5px;" align="right">Дата выезда:
<input style="width: 90px;" onkeydown="dashboard.submit()" type="text" id="filter_date" name="filter_date" value="{$selectedFilterDate}" ></td>
<td  style="padding: 5px;" align="right">Тип заявки:</td>
<td width='20px' align="left"> <select name='filterType' onChange="dashboard.submit()"><option value='all'>Все</option>{html_options options=$filterTypes selected=$selectedFilterType}</select></td>
<td  style="padding: 5px;" align="right">Статус:</td>
<td width='20px' align="left"> <select name='filterStatus' onChange="dashboard.submit()"><option value='all'>Все</option>{html_options options=$filterStatuses selected=$selectedFilterStatus}</select></td>
<td width='200px' align="right"><nobr>Заявок на странице:</nobr></td>
<td><select name='pageDashboardSize' onChange="dashboard.submit()">
					{html_options values=$pageDashboardSizes output=$pageDashboardSizes selected=$selectedDashboardPageSize}
				</select></td>
<td align = "right" width='250px' style="padding: 5px;">
	<span class="buttons"><button type="submit" class="regular" name="Refresh"><img src="images/buttons/Refresh_16x16.png" alt=""/>Обновить</button>{if $orderint}<button type="button" class="regular" name="neworder" onclick="location.href='/index.php?page=dashboardDetail'"><img src="images/buttons/Download_16x16.png" alt=""/>Создать</button>{/if}</span>
</td>
</tr>
</table>
<br>
<table cellpadding="2" cellspacing="1" class="summary" width="100%" >
<tr valign="middle">
<!--<th width="20px">&nbsp;</th>-->
<!-- <th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=status&dashboardSortDirection={if $dashboardSortBy eq 'status'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Статус</a></u>{if $dashboardSortBy eq 'status'}{$sortImage}{/if}</th> -->
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboardTranspData&dashboardSortBy=sd_number&dashboardSortDirection={if $dashboardSortBy eq 'sd_number'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Номер СБ</a></u>{if $dashboardSortBy eq 'sd_number'}{$sortImage}{/if}</th>

<!--<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboard&dashboardSortBy=start_date&dashboardSortDirection={if $dashboardSortBy eq 'start_date'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Дата создания</a></u>{if $dashboardSortBy eq 'start_date'}{$sortImage}{/if}</th>-->
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboardTranspData&dashboardSortBy=regnum&dashboardSortDirection={if $dashboardSortBy eq 'regnum'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Номер авто</a></u>{if $dashboardSortBy eq 'regnum'}{$sortImage}{/if}</th>
<!--<th>Направление</th>-->
<th><nobr><u><a href="{$smarty.server.PHP_SELF}?page=dashboardTranspData&dashboardSortBy=departure_date&dashboardSortDirection={if $dashboardSortBy eq 'departure_date'}{$dashboardSortDirection}{else}asc{/if}{$arch}">Выезд</a></u>{if $dashboardSortBy eq 'departure_date'}{$sortImage}{/if}</th>
<th>Откуда/Куда</th>
<th>Тариф</th>
<th>Время</th>
<th>Расстояние</th>
<th>Простой</th>
<th>Цена</th>
<!--<th>Водитель</th>
<th>Диспетчер</th>-->

</tr>

{section name=i loop=$order}

<tr class="{$order[i].order_row_class}">

    <!--<td>{if $order[i].vip}<img src="images/16/flag_red.png">{else}&nbsp;{/if}</td>-->
	<!--<td valign="middle"><b><nobr><img src="images/16/{if $order[i].status eq '1'}page_lightning.png{elseif $order[i].status eq '2'}page_gear.png{else}page.png{/if}" >&nbsp; --><!--<a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$order[i].id}">{$order[i].status_str}</a></nobr></b></td>-->
	<td><b><nobr><a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$order[i].id}">{$order[i].sd_number}</a></nobr></b></td>

	<!--<td>{$order[i].start_date}</td>-->
	<!--<td>{$order[i].customer}</td>-->
	<td>{$order[i].regnum}</td>
	<!--<td>{$order[i].direction}</td>-->
	<td>{$order[i].departure_date}</td>
	<td style="width: 300px;">{if $order[i].address_from}{$order[i].address_from} => <br>{$order[i].address_to}{else}&nbsp;{/if}</td>
	<!--<td>{if $order[i].tariff_name}{$order[i].tariff_name}{else}&nbsp;{/if}</td>-->
	<td><select id="tariff_{$order[i].id}" name="tariff_{$order[i].id}">
          <option value=''></option>
          {html_options options=$tariff selected=$order[i].tariff}</select></td>
	<td style="white-space: nowrap;"><select id="timeradius50km_{$order[i].id}" name="timeradius50km_{$order[i].id}">
          <option value=''></option>
          {html_options options=$hours_inf selected=$order[i].timeradius50km}</select>
          <b> : </b>
          <select id="timeradius50km_minute_{$order[i].id}" name="timeradius50km_minute_{$order[i].id}">
        <option value=''></option>
        {html_options options=$minutes_d selected=$order[i].timeradius50km_minute}
        </select></td>
	<td><input placeholder="КМ" style="width: 80px;" type="text" id="km_route_{$order[i].id}" name="km_route_{$order[i].id}" value="{$order[i].km_route}" ></td>
	<td style="white-space: nowrap;"> 
	<select id="lagtime50km_{$order[i].id}" name="lagtime50km_{$order[i].id}">
        <option value=''></option>
	  {html_options options=$hours_inf selected=$order[i].lagtime50km}</select>
	  <b> : </b>
	<select id="lagtime50km_minute_{$order[i].id}" name="lagtime50km_minute_{$order[i].id}">
        <option value=''></option>
        {html_options options=$minutes_d selected=$order[i].lagtime50km_minute}</td>
		
	<td><input style="width:70px" id="price_{$order[i].id}" type="text" name="price{$order[i].id}" value="{$order[i].price}"></td>
	<!--<td><input style="width:70px" id="price_{$order[i].id}" type="text" name="price{$order[i].id}" value="{$order[i].price}" readonly></td>-->
	<!--НЕ УДАЛЯТЬ ORDER-->
	<td><input placeholder="order" style="width: 80px;display:none;" type="text" id="order_{$order[i].id}" name="order_{$order[i].id}" value="{$order[i].id}" ></td>
	<td><span><button type="button" class="regular" name="calcuateButton" onclick="priceCalcList({$order[i].id})">Счет</button></span></td>
	<!--<td><button type="submit" class="regular" name="saveButton">Сохранить</button></td>-->


</tr>

{sectionelse}
<tr style="row_gray"><td colspan='10'>Заявки не найдены</td></tr>
{/section}

</table>
<div align="left">{$pager_info}</div>
<span class="buttons" style="float:right"><button type="submit" style="width:auto" class="regular" name="saveButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить все данные</button></span>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>

</form>


{elseif $page eq "dashboardDetail"}
<ul id="maintab" class="shadetabs">
<li class="{$editClass}">
  <a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$orderID}&view=edit">
    <img src="images/buttons/trap.png" align="absmiddle" border="0"> Заявка
  </a>
</li>
{if $orderID}
<li class="{$logClass}">
  <a href="{$smarty.server.PHP_SELF}?page=dashboardDetail&orderID={$orderID}&view=log">
    <img src="images/buttons/Syslog_16x16.png" align="absmiddle" border="0"> История
  </a>
</li>{/if}
</ul>
<div class="contentstyle">
<table width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr><td width="50%" valign="top"><div style="margin: 5px; padding: 5px; background-color: rgb(238, 238, 238);">
{if $view eq 'edit'}
<form id="orderEdit" name="orderEdit" method="post">
<table cellpadding="2" cellspacing="1" border='0'>
	<tr>
		<td class="list-bold">Заказчик (контактное лицо)</td>
		<td align="right"><input style="width: 250px;" type="text" id="customer" name="customer" value="{$order.customer}" ></td>
		<td rowspan=9 valign="top"><b>Информация:</b>
				<br><textarea name="note" rows="19" cols="60">{$order.note}</textarea></td>

        <!-- <tr> -->

            <!-- </tr> -->
	</tr>
	<tr>
		<td class="list-bold">Телефон заказчика:</td>
		<td align="right"><input type="text" id="customer_phone" name="customer_phone" value="{$order.customer_phone}" ></td>
		</tr>
	<tr valign="top">
		<td class="list-bold">Дополнительная информация/<br>Сведения о пассажире:</td>
		<td align="right"><!-- <input type="text" id="passenger" name="passenger" value="{$order.passenger}" >-->
		<textarea id="passenger" name="passenger" rows="3" class="input">{$order.passenger}</textarea>
		</td>

	</tr>
	<tr>
		<td class="list-bold" style="display:none">Агент:</td>
		<td align="right" style="display:none"><select   id='credit_id' name='credit_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не выбран</option>
					{html_options options=$credits selected=$order.credit_id}</select></td>

	</tr>

	<tr>
		<td class="list-bold">Пункт отправления:</td>
		<td align="right"><input style="width: 250px;" type="text" id="address_from" name="address_from" value="{$order.address_from}" ></td>

	</tr>
	<tr>
		<td class="list-bold">Пункт назначения:</td>
		<td align="right"><input style="width: 250px;" type="text" id="address_to" name="address_to" value="{$order.address_to}" ></td>

	</tr>
	<tr>
		<td class="list-bold">Пункт отправления (обратно):</td>
		<td align="right"><input style="width: 250px;" type="text" name="ret_address_from" value="{$order.ret_address_from}" ></td>

	</tr>
	<tr>
		<td class="list-bold">Пункт назначения (обратно):</td>
		<td align="right"><input style="width: 250px;" type="text" name="ret_address_to" value="{$order.ret_address_to}" ></td>

	</tr>
	<tr>
		<td class="list-bold">Статус заявки:</td>
		<td align="right"><select   id='status' name='status' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					{html_options options=$orderStatuses selected=$order.status}
			</select></td>
	</tr>
	<tr>
		<td class="list-bold">Водитель:</td>
		<td align="right"><span class="buttons-small">
				<button type="button" id="calendarButton" class="regular" onclick="FillSolution()">
					<img src="images/16/car_add.png" alt=""/>
				</button></span>
				<select  id="driver_id"  name='driver_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не назначен</option>
					{$driver_options}
				</select>

		</td>
		
		<td align="right"><b>Тариф:</b><select id="tariff" name="tariff">
          <option value=''></option>
          {html_options options=$tariff selected=$order.tariff}</select></td>

   	</tr>
   <!-- <tr>
      <td rowspan=13><b>Стоимость в рублях:</b>
        <input type="text" id="price" name="price" value="{$order.price}" ></td></td>
    </tr>-->
	<tr>
		<td class="list-bold">Телефон водителя:</td>
		<td align="right"><b><div id="driver_phone">{$order.driver_phone}</div></b></td>

      <td align="right"><b>Время работы (ЧЧ:ММ):</b>
        <!-- <input style="width: 80px;" type="text" id="timeradius50km" name="timeradius50km" value="{$order.timeradius50km}" ></td> -->
        <select id="timeradius50km" name="timeradius50km">
          <option value=''></option>
          {html_options options=$hours_inf selected=$order.timeradius50km}</select>
          <b> : </b>
          <select id="timeradius50km_minute" name="timeradius50km_minute">
        <option value=''></option>
        {html_options options=$minutes_d selected=$order.timeradius50km_minute}
        </select>

   	</tr>
	<tr>
        <td class="list-bold">Автомобиль:</td>
		<td align="right">
				<select  id="car_id"  name='car_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не назначен</option>
					{$cars_options}
				</select>
		</td>
    <td align="right"><b>Пробег (Км):</b>
      <input placeholder="Только число" style="width: 80px;" type="text" id="km_route" name="km_route" value="{$order.km_route}" ></td>
    </tr>
	<tr>
        <td class="list-bold">Город:</td>
		<td align="right">
				<select  id="location_id"  name='location_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>не выбран</option>
					{html_options options=$locations selected=$order.location_id}
				</select>
		</td>
      <td align="right"><b>Простой (ЧЧ:ММ):</b>
        <!-- <input style="width: 80px;" type="text" id="lagtime50km" name="lagtime50km" value="{$order.lagtime50km}" > -->
        <select id="lagtime50km" name="lagtime50km">
          <option value=''></option>
          {html_options options=$hours_inf selected=$order.lagtime50km}</select>
          <b> : </b>
          <select id="lagtime50km_minute" name="lagtime50km_minute">
        <option value=''></option>
        {html_options options=$minutes_d selected=$order.lagtime50km_minute}
        </select>
      </td>
    </tr>
	{if $sessionUserRole eq 'administrator'}
	<tr>
        <td class="list-bold">Диспетчер:</td>
		<td align="right">
				<select  id="user_id" name='user_id'  {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
				    <option value=''>не назначен</option>
					<!-- {html_options options=$users selected=$order.user_id} -->
					{$user_options}
				</select>			
        <td align="right" style="display:true"><button TYPE="BUTTON" onclick="priceCalc()">Посчитать стоимость</button>&nbsp;&nbsp;<b>Стоимость:</b>
          <input placeholder="Только число" style="width: 80px;text-align: center;" type="text" id="price" name="price" value="{$order.price}" ></td> 
	</tr>
	{/if}
	<tr>
	<td align="right" class="list-bold">Выезд:<input type="checkbox" id="show_date_checkbox" style="width: 30px"  /></td>
		<td align="right">
				{html_select_date all_extra='class="show_date"' month_extra='id="start_date_Month"' year_extra='id="start_date_Year"' day_extra='id="start_date_Day"' prefix="start_date_" field_order=DMY field_separator='.' year_empty="" month_empty="" day_empty="" time=$order.departure_date start_year=-1 end_year=+1} <select class = "show_date" id="start_date_Hour" name="start_date_Hour"><option value=''></option>{html_options options=$hours selected=$order.start_date_Hour}</select><b> : </b><select class = "show_date" id="start_date_Minute" name="start_date_Minute">
				<option value=''></option>
				{html_options options=$minutes selected=$order.start_date_Minute}
				</select>
		</td>
<td align="left" id="price_descr">
</td>
	</tr>
	<tr>
		 <td align="right" class="list-bold">Окончание:</td>
		<td align="right">
				{html_select_date month_extra='id="end_date_Month"' year_extra='id="end_date_Year"' day_extra='id="end_date_Day"' prefix="end_date_" field_order=DMY field_separator='.' year_empty="" month_empty="" day_empty="" time=$order.end_date start_year=-1 end_year=+1} <select id="end_date_Hour" name="end_date_Hour"><option value=''></option>{html_options prefix="end_date_" options=$hours selected=$order.end_date_Hour}</select><b> : </b><select id="end_date_Minute" name="end_date_Minute">
				<option value=''></option>
				{html_options values=$end_minutes output=$end_minutes selected=$order.end_date_Minute}
				<!--{html_options options=$end_minutes selected=$order.end_date_Minute}-->
				</select>
		</td>
    <td rowspan=3><b>Решение:</b><span class="buttons-small">
        <button type="button" id="calendarButton" class="regular" onclick="AddUserPhoneToSolution()">
          <img src="images/16/phone_add.png" alt=""/>
        </button></span><input type="hidden" id="user_phone" name="user_phone" value="{$order.user_phone}"><br><textarea name="solution" id="solution" cols="60" rows="4">{$order.solution}</textarea></td>

	</tr>
	<tr>
		<td align="right" class="list-bold">Важный запрос:</td>
		<td align="right"><input style="width: 15px" type="checkbox" name="vip" value=1 {if $order.vip eq '1'}checked{/if}></td>
	</tr>
  <tr>

  </tr>
	<tr>
		<td align="right" colspan=4><span class="buttons">
		<button type="submit" class="regular" name="clientSMS"><img src="images/16/clock.png" alt=""/>SMS Клиенту</button>
		<button type="submit" class="regular" name="driverSMS"><img src="images/16/clock.png" alt=""/>SMS Водителю</button>
		<button type="button" class="regular" name="editButton" onclick=OrderEditCheck("orderEdit")><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button>
		</span></td>

	</tr>
</table>
<font size="-2">* Обязательные поля</font><br>
<input type="hidden" id="currentStatus" value={$order.status}>
<input type="hidden" id="userRole" value={$session_role}>
</form>
{else}

<table cellpadding="2" cellspacing="1" class="list" width="100%">
<tr class="tablehead"><th>Время</th><th>Пользователь</th><th>Событие</th></tr>
{section name=i loop=$log}
<tr valign="top" class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_gray{/if}">
<td>{$log[i].datetime}</td>
<td>{$log[i].user}</td>
<td>{$log[i].event}<br><br></td>
</tr>
{/section}
</table>
<div align="left">{$pager_info}</div>
<div  class='paging' align="center">{$pager_prev_link} {$pager_links} {$pager_next_link}</div>

{/if}
</div></td></tr></tbody></table></div>
{elseif $page eq 'userPassword'}
<form id="userPassword" method='post' action="{$smarty.server.PHP_SELF}?page=userPassword" onsubmit="javascript:return submitUserPassword()">
<table>
	<tr>
		<td class="list-bold">Текущий пароль<font size="-2">*</font>:</td>
		<td align="right"><input type='password' name="oldPassword"></td>
		<td align="left" valign="middle"><span id="status_oldPassword_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_oldPassword">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Новый пароль<font size="-2">*</font>:</td>
		<td align="right"><input type='password' name="newPassword"></td>
		<td align="left" valign="middle"><span id="status_newPassword_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_newPassword">&nbsp;</span></td>
	</tr>
	<tr>
		<td class="list-bold">Подтверждение<font size="-2">*</font>:</td>
		<td align="right"><input type='password' name="retypeNewPassword"></td>
		<td align="left" valign="middle"><span id="status_retypeNewPassword_img">&nbsp;</span></td>
		<td align="left" valign="middle"><span id="status_retypeNewPassword">&nbsp;</span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><span class="buttons"><button type="submit" class="regular" name="changePasswordButton"><img src="images/buttons/Save_16x16.png" alt=""/>Сохранить</button></span></td>
	</tr>
</table></form>
<font size="-2">* Required fields</font>
{elseif $page eq 'settings'}
<form method="post">
<table width=80%>
{section name=i loop=$params}
<tr class="{if $smarty.section.i.index%2 eq 0}row_gray{else}row_white{/if}">
	<td class="list-bold">{$params[i].param_lable}{$params[i].param_name}</td>
	{if $params[i].writable == 1}<td align="right">{if $params[i].value_mappings}<select name="{$params[i].param_name}">{html_options options=$params[i].map selected=$params[i].param_value}</select>{else}<input type="text" name="{$params[i].param_name}" value="{$params[i].param_value}">{/if}</td>{else}<td {if preg_match("/^[0-9]+$/", $params[i].param_value)}"align='right'"{/if}>{$params[i].param_value}</td>{/if}
</tr>
{/section}
<tr><td colspan='2' align="right"><br><span class="buttons"><button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Save</button></span></td></tr>
</table>
</form>
{elseif $page eq 'report'}
<form id="reportselect" method='post' action="makereport.php">
<table border=0>
<tr><td>Отчет:</td><td colspan="3"><select id='report' name='report' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if} onchange = "ReportFilterEnable()">
						{html_options options=$reports}
			</select></td></tr>
<tr><td>Период с </td><td>{html_select_date prefix="report_start_" field_order=DMY field_separator='.'  start_year=-1 end_year=+1}</td><td> по </td><td>{html_select_date prefix="report_end_" field_order=DMY field_separator='.' start_year=-1 end_year=+1}</td></tr>
<tr><td colspan="4">
<fieldset width="100%">
<legend >Фильтр</legend>
<table width="100%">
<tr><td>Вид отчета:</td><td><select id='type' name='type' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
						<option value=''>Все</option>
					{html_options options=$types}
			</select></td></tr>
<tr><td>Статус:</td><td><select id='status' name='status' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
						<option value=''>Все</option>
					{html_options options=$statuses}
			</select></td></tr>
<tr><td>Номер кредита:</td><td><select id='credit_id' name='credit_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
						<option value=''>Все</option>
					{html_options options=$credits}
			</select></td></tr>

<tr><td>Водитель:</td><td><select id='driver_id' name='driver_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>Все</option>
					{html_options options=$drivers}
			</select></td></tr>
<tr><td>Диспетчер:</td><td><select id='user_id' name='user_id' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>Все</option>
					{html_options options=$users}
			</select>

			</td></tr>
<tr><td>Исполнитель:</td><td><select id='sb_user' name='sb_user' {if (preg_match("/chrome/i", $smarty.server.HTTP_USER_AGENT) || preg_match("/firefox/i", $smarty.server.HTTP_USER_AGENT) ) }class="ff" {else}class="ie"{/if}>
					<option value=''>Все</option>
					{html_options options=$locations_otchet}
			</select>

			</td></tr>
</table>
</fieldset>
</td></tr>
<tr><td colspan="4" align="right"><br><nobr><span class="buttons"><button type="submit" class="regular" name="editButton"><img src="images/buttons/Save_16x16.png" alt=""/>Продолжить</button></span><nobr></td></tr>
</table>
</form>

{else}
Error!
{/if}

<!-- my tpl ends here -->
<br><br>
<center><font size="-2">ver 1.3</font></center></div>
</body></html>
