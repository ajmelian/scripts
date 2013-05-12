<?php

if ($_REQUEST['passwd'] == "123456"){
	echo "Password correct. Thank you.";
	system("/path/to/firewall or iptables line here");
	die();
}
			

?>

<html>

<head>
<title>Login</title>
</head>

<body>
<h1>Please, enter password</h1>

<form action="login.php">
<p><input type="password" name="passwd" size="12"></p>
<p><input type="submit"/></p>
</form>

</body>

</html>
