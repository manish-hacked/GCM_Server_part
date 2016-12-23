<?php
$sql_host = 'localhost';
$sql_user = 'root';
$sql_password = '';
$db_name = 'mygcmdb';
$cnn = new PDO('mysql:dbname='.$db_name.';host='.$sql_host, $sql_user, $sql_password) or die(mysql_error());
?>