<?php

function checkOTP($pin,$otp,$initsecret) {
 $maxperiod = 3*60; // in seconds = +/- 3 minutes
 $time=gmdate("U");
 for($i = $time - $maxperiod; $i <= $time + $maxperiod; $i++) {
    $md5 = substr(md5(substr($i,0,-1).$initsecret.$pin),0,6);
    if($otp == $md5) return(true);
 }
 return(false);
}

function printOTP($pin,$initsecret) {
 $maxperiod = 3*60; // in seconds = +/- 3 minutes
 $time=gmdate("U");
 $j=0;
 for($i = $time - $maxperiod; $i <= $time + $maxperiod; $i++) {
    $md5 = substr(md5(substr($i,0,-1).$initsecret.$pin),0,6);
    if ($old != $md5)
	    echo $j++ . " - " . $md5 . "\n<br/>\n";
    $old=$md5;
 }
}

if (isset($_POST['pwd']))
	if ( checkOTP("12345A", $_POST['pwd'], "1209JMhNbgYjKlsF/a8L10986") )
		die("Password correct");
	else
		die("Password incorrect");
?>

<form action="otp.php" method="post">
<input type="text" name="pwd"/> <input type="submit"/>
</form>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

</head><body>

<h1>MOTP passcode generator</h1>

<script src="md5.js" type="text/javascript"></script>

<script type="text/javascript">

function motp (pin, secret) {
  var time = new Date();
  time = time.getTime() / 1000 / 10;
  time = Math.floor(time);
  var otp = hex_md5( time + secret + pin );
  return otp.substring(0,6);
}

</script>


<table>

  <tbody><tr><th>PIN:</th><td><input id="pin" size="4" 
type="text" value="12345A"></td></tr>
  <tr><th>Secret:</th><td><input id="secret" size="16" 
type="text" value="1209JMhNbgYjKlsF/a8L10986"></td></tr>
  <tr><th></th><td>
	<input onclick="document.getElementById('otp').value = 
	
motp(document.getElementById('pin').value,document.getElementById('secret').value)"
 value="OTP" type="button"></td></tr>
  <tr><th>Passcode:</th><td><input id="otp" size="6" 
type="text"></td></tr>
</tbody></table>

</body></html>


<h2>Claves validas para el periodo</h2>
<?php

printOTP("12345A", "1209JMhNbgYjKlsF/a8L10986");

?>
