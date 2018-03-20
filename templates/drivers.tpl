<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Такси "ГК Перевозчик" ver. 1.2</title>
  
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
  <meta http-equiv="content-language" content="en-us">
  <meta http-equiv="refresh" content="60"> 

  <link href="images/styles.css" rel="stylesheet" type="text/css" />

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<form id="userList" method="post">
<div style="font: 8pt verdana; background-color: #eeeeee"> 

<table>
<tr><th>Позывной</th></tr>
{section name=i loop=$driver}
<tr {$driver[i].driverclass}><td>{$driver[i].nick}</td></tr>
{/section}
</table>
</form>
</div>
</body></html>
