<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/instagram.css">
	<title></title>
</head>
<body>


</body>
</html>

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
	$url = 'https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToinstagram($url);
	$results = json_decode($instagramInfo, true);

    return $results['data']['0']['id'];
}

//function to print out images onto screen
function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=5';
	$instagramInfo = connectToinstagram($url);
	$results = json_decode($instagramInfo, true);
	//parse through the informaition one by one.
	foreach ($results['data'] as $items) {
		$image_url = $items['images']['low_resolution']['url'];
		echo '<img src=" '.$image_url.' "/><br/>';
		//calling a fucntion to save that image url
		savePictures($image_url);
	}
}
//function to save image to server
function savePictures($image_url){
	return $image_url.'<br>'; 
	$filename = basename($image_url); //the file name is what we are storing, basename is the php built method that we are using to store image url
	echo $filename .'<br>';

	$destination = ImageDirectory . $filename; //making sure the image doesnt exist in the storage
	file_put_contents($destination, file_get_contents($image_url)); //goes and grabs an image file and stores it into our server
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

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);

}
else{
?>

<!DOCTYPE html>
<html>
<head>
	<title>EDWIN</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width-device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,600,500,900,800,700' rel='stylesheet' type='text/css'>

<div id="navbar">
  <div class="container">
    <div class="logo">EDWIN</div>
    <div id="nav-colapse">
       <li><a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a></li>
    </div>
    <nav class="nope">
      <ul>
        <li><a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a></li>
      </ul>
    </nav>

  </div>
</div>

<div class="hero"></div>
	<script src="js/main.js"></script>
</body>
</html>
<?php
}
?>
