<?php
include 'PDOConnect.php';
if($_POST['action']=="add"){
	insertToken($cnn,$_POST['tokenid']);
}
function insertToken($cnn,$token){
	if(isExistToken($cnn,$token)){
		echo "Token is Exits";
		return;
	}
	$query = "INSERT INTO DEVICEINFO(TOKENID) VALUES(?)";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$token);
	$stmt->execute();
	echo "Insert Success";
}
function isExistToken($cnn,$token){
	$query = "SELECT * FROM DEVICEINFO WHERE TOKENID = ?";
	$stmt = $cnn->prepare($query);
	$stmt->bindParam(1,$token);
	$stmt->execute();
	$rowcount = $stmt->rowCount();
	return $rowcount;
}
?>