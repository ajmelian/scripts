<?php

//Example of invocation:
$theurl="http://long url";
$thetext="We did not find any bla bla bla";
echo "Checking tickets for something... ";
checkTickets($theurl, $thetext);

//Change the values (username, password...) of this function to fit your needs.
function warnMe($lineas) {
	include("class.phpmailer.php");
	include("class.smtp.php");

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.domain.tld";
	$mail->Port = 465;
	$mail->Username = "username@domain.tld";
	$mail->Password = "password";

	$mail->From = "username@domain.tld";
	$mail->FromName = "From Name";
	$mail->Subject = "Eureka! " . date('d-m-Y');

	$texto="";
	foreach ($lineas as $num => $linea)
		$texto=$texto.$linea;

	$mail->AltBody = $texto;
	$mail->MsgHTML("<pre>".$texto."</pre>");
	$mail->AddAddress("from@domain.tld", "Eureka!");
	$mail->IsHTML(true);

	$mail->Send();
}

function checkTickets($theurl, $messagetocheck) {
	$lineas = file($theurl);

	$detectada=false;
	foreach ($lineas as $num => $linea)
		if (isChunk($messagetocheck, $linea))
			$detectada=true;

	if (!$detectada) {
		echo "[found] Mailing results...\n";
		warnMe($lineas);
	} else
		echo "[none]\n";
}


//No comments. Problems with PHP functions on the shared hosting.
//Too long to explain. This function was easier
function isChunk($aguja, $pajar) {
	$sizeaguja=mb_strlen($aguja);
	$sizepajar=mb_strlen($pajar);
	for($i=0; $sizepajar >= $i+$sizeaguja; $i++)
		if ( strcmp(strtolower(substr($pajar, $i, $sizeaguja)),strtolower($aguja)) == 0)
			return true;
	return false;
}

?>
