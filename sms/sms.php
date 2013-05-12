<?php

//Fields for OVH (Spain):
$SMS_ACCOUNT ="SMS_ACCOUNT";
$SMS_PASSWORD = "SMS_PASSWORD";
$SENDER="+34000000000";

//Fields for betamax clones:
$BETASERVER="freevoipdeal.com";
$BETAUSER="userfree";
$BETAPASS="freepass";
$BETASENDER="+34000000000";

?>
<html>

<head>
<title>SMS</title>

<script type="text/javascript">
function contar(form, name) {
  n = document.forms[form][name].value.length;
  document.forms[form]['result'].value = n;
}
function cnumber(form, number) {
  document.forms[form]['cellnumber'].value = number;
}
</script>


<style type="text/css">
body {
	font-family: sans;
	font-size: 1em;
	background: #fcfcfc;
}
input {
	border: 1px #000 solid;
}
div.operaciones {
	font-family: sans;
	font-size: 1.2em;
	border: 0.2em #ccc dashed;
	padding: 1em;
}
form{
	border: 0.2em #ccc dashed;
	background: #fff;
	width: 20em;
	text-align: center;
	padding: 1em;
}
form.nospace{
	border: 0em;
	background: #fcfcfc;
	width: 5em;
	text-align: center;
	padding: 0em;
}
table {
	margin: 0 auto;
}
a:link, a:visited{
	color: #5A5A5A;
	text-decoration: none;
}
a:hover{
	color: #000;
	text-decoration: underline;
}
</style>

</head>

<body>

<h1>SMS</h1>

<div class="operaciones">
[ Envío de SMS por varios servicios ]
</div>

<br/>

<form action="sms.php" method="post" name="smsform">
<table>
<tr><td>Numero</td><td>
<?php
if ($_REQUEST['cellnumber'] != "")
  echo "<input type=\"text\" name=\"cellnumber\" size=\"22\" value=\"".$_REQUEST['cellnumber']."\"/></td></tr>";
else
  echo "<input type=\"text\" name=\"cellnumber\" size=\"22\" value=\"+346\"/></td></tr>";

?>
<tr><td>Mensaje</td><td><textarea name="message" cols="27" rows="5" onkeydown="contar('smsform','message')" 
onkeyup="contar('smsform','message')"></textarea>
</td></tr>
<tr>
<td>Servicio</td>
<td>
 <select name="service">
  <option value="betamax">betamax</option>
  <option value="OVH">OVH</option>
 </select>
</td>
</tr>
<tr>
<td>Caracteres:</td>
<td align="right">
<input name="result" value="0" size="4" readonly="readonly"/>
<input type="hidden" name="op" value="sms"/>
<input type="submit" value="Enviar"/>
</td>
</tr>
</table>
<input type="hidden" name="confirm" value="yes"/>
</form>

<?php

if ($_REQUEST['confirm'] != "yes")
	die("</body></html>");

if ($_REQUEST['service'] == "OVH") {
try {

 $soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.8.wsdl");
 $session = $soap->login($SMS_ACCOUNT, $SMS_PASSWORD, "es", false);
 $message=utf8_encode($_REQUEST['message']);
 $resultS = $soap->telephonySmsSend($session, $SMS_ACCOUNT, $SENDER, $_REQUEST['cellnumber'], $message, "1440", "1", "0", "3");
 $resultC = $soap->telephonySmsCreditLeft($session, $SMS_ACCOUNT);

 echo "Resultado de la operacion (servicio: OVH): ";
 print_r($resultS);
 echo "<br/>Credito de mensajes restantes: ";
 print_r($resultC);

 $soap->logout($session);

} catch(SoapFault $fault) {
 echo $fault;
}
}

if ($_REQUEST['service'] == "betamax") {
 echo "Servidor usado: Betamax. Resultado:<br/><pre>";
 $message=utf8_encode(urlencode($_REQUEST['message']));
 $urltocall="https://www.".$BETASERVER."/myaccount/sendsms.php?username=".$BETAUSER."&password=".$BETAPASS."&from=".$BETASENDER."&to=".$_REQUEST['cellnumber']."&text=".$message;
 $lines=file($urltocall);
 foreach ($lines as $num => $linea) {
  echo htmlentities($linea);
 }

 echo "</pre>";
}

?>



</body>

</html>

