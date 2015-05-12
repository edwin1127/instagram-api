<?php

//Configuration for our PHP Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);

session_start();

//Make Constant using define.
define('clientID', '8304ffa83f3048fe917dd57743a2f47a');
define('clientSecret', '5a583912807c43c4a56b19a3d3469739');
define('redirectURI', 'http://localhost:8888/appacademyapi/index.php');
define('ImageDirectory', 'pics/');

if (isset($_GET['code'])) {
	$code = ($_GET['code']);
	$url = "https://api.instagram.com/oauth/access_token";
	$access_token_settings = array('client_id' => clientID,
		                           'client_secret' => clientSecret,
		                           'grant_type' => 'authorization_code',
		                           'redirect_uri' => redirectURI,
		                           'code' => $code
		                           );
}   

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width-device-width, initial-scale=1">
	<title>EDWIN</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="author" href="humans.txt">
</head>
<body>
    
	<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	<script src="js/main.js"></script>
</body>
</html>
