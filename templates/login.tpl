<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xml:lang="ru" xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Такси "ГК ПеревозчикЪ" Краснодар 1.3</title>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">

  <link href="images/styles.css" rel="stylesheet" type="text/css">
  <body>
<div id="center">
<div id="top" style="background: rgb(31, 51, 78) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">
<table width="100%" cellpadding="0" cellspacing="0">
<tbody><tr>
<td align="right" valign="middle">Not logged in &nbsp; </td><td align="right" width="18px" valign="middle"><a href="http://www.proxicast.com"><img src="images/buttons/Help_16x16.png"></a></td></tr>
</tbody></table>
</div>
<table width="100%">
<tr><td align="left">{$SystemLogoLeft}</td><td align="right">&nbsp;</td></tr></table>

<hr colour=#444444 />
<div class=clearer></div>

  <center><br />
<form action="{$smarty.server.PHP_SELF}?page=dashboard" method="post"> 
<table  border='0' cellpadding='20' cellspacing='0' background='images/login.png' width="479" height="212">
    <tr>
      <td width=128></td>
      <td valign="middle">
	  <!-- <form action="{$smarty.server.PHP_SELF}?{if $cleanURL}page=dashboard{else}{$smarty.server.QUERY_STRING}{/if}" method=post> -->
	     <table border='0'  >
           <tr>
			  <td colspan="2" align="center"><b>Регистрация в системе:</b><br><br></td>
			  </tr> 
			  <tr>
              <td align="left">Логин: </td>
              <td align="right"><input type='text' class="login" name='userLogin'></td>
            </tr>
            <tr>
              <td align="left">Пароль: </td>
              <td align="right"><input type='password' class="login" name='userPassword'></td>
            </tr>
			<tr >
				  <td>&nbsp;</td>
              <td align='right'><span class="buttons" ><button type="submit" class="regular" name="sblogin"><img src="images/buttons/Check_16x16.png" alt=""/>OK</button></span></td>
            </tr>
				<tr align='right'>
					<td colspan='2' align='right'>
					{if $authFailed eq 'badPassword'}<span style='font-weight: bold; color: #cc0000;' align="right">Неверный пароль</span>
					{elseif $authFailed eq 'expired'}<span style='font-weight: bold; color: #cc0000;' align="right">Учетная запись устарела</span>
					{else}&nbsp;{/if}</td>
				</tr>
			</table>
     </td>
    </tr>
  </table> </form>
  <div class="clearer"></div>
  <font size="-2">{$CopyrightText}</font></div>
</center>
</body>
</html>