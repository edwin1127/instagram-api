<?php

//Configuration for our PHP Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make Constant using define.
define('clientID', '8304ffa83f3048fe917dd57743a2f47a');
define('clientSecret', '5a583912807c43c4a56b19a3d3469739');
define('redirectURI', 'http://localhost/appacademyapi/index.php');
define('ImageDirectory', 'pics/');

//fucntion that is going to connect to instagram.
function connectToinstagram($url){
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;

}
//function to get user id cause username doesnt allow us to get pictures
function getUserID($userName){
	$url = 'http://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToinstagram($url);
	$results = json_decode($instagramInfo, true);

    echo $results['data']['0']['id'];
}

if (isset($_GET['code'])) {
	$code = ($_GET['code']);
	$url = "https://api.instagram.com/oauth/access_token";
	$access_token_settings = array('client_id' => clientID,
		                           'client_secret' => clientSecret,
		                           'grant_type' => 'authorization_code',
		                           'redirect_uri' => redirectURI,
		                           'code' => $code
		                           );
//curl is what we use in php, its a library calls to other api's

$curl = curl_init($url); //setting a curl session and we put in $url because thats where we are gettig the data from.
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); //setting the post fields to the arrays setup that we created.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //setting it equal to 1 because wea re getting strings back.
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //but in live work-production we want to set this to true

 
$result = curl_exec($curl);
curl_close($curl);

$results = json_decode($result, true);
getUserID($results['user']['username']);
}
else{
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
<?php
}
?>
