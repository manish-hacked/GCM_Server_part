<?php
include 'PDOConnect.php';
define('GOOGLE_API_KEY','AIzaSyCG2BkYJDtiaCpt2_YxMGEE8OIlQfDMbFk');
$pushStatus = 0;
if(isset($_POST['submit'])){
	$gcmRegIds = array();
	$sql = "SELECT TOKENID FROM DEVICEINFO";
	$result = $cnn->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		array_push($gcmRegIds, $row['TOKENID']);
	}
	$push_message = $_POST['message'];
	if(isset($gcmRegIds)&&isset($push_message)){
		$message = array('message'=>$push_message);
		$pushStatus = sendPushNotification($gcmRegIds,$message);
	}
}
function sendPushNotification($registration_ids,$message){
	$url = 'https://android.googleapis.com/gcm/send';
	$fields = array(
		'registration_ids'=>$registration_ids,
		'data'=>$message
		);
	$headers = array(
		'Authorization: key=' .GOOGLE_API_KEY,
		'Content-Type: application/json'
		);
	//Open Connection
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	//Disabling SSL Certificate Temprially
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

	//Execute Post
	$result = curl_exec($ch);
	if($result===FALSE){
		die('Curl Failed: '.curl_error($ch));
	}
	//Close Connection
	curl_close($ch);
	return $result;
}
?>
<html>
<head>
	<title>GCM Server</title>
</head>
<body style="text-align: center;color: blue">
<h1>Google Cloud Messaging</h1>
<form method="POST" action="">
	<div>
		<textarea rows="6" name="message" cols="50" placeholder="Message to notify"></textarea>
	</div>
	<div style="margin-top: 10px;">
		<input type="submit" name="submit" value="Send Notification"></input>
	</div>
	<p>
		<h3>
		<?php
			if('0'!=$pushStatus){
				$obj = json_decode($pushStatus);
				if($obj!=null){
					echo "<div style='color:green;'>";
					echo "Success:".$obj->success;
					echo "<br>Failure:".$obj->failure;
					echo "</div>";
				}else{
					echo "<div style='color:red;'>".$pushStatus."</div>";
				}
			}
		?>
		</h3> 
	</p>
</form>

</body>
</html>