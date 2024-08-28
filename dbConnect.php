<?php
function connect(){
	//$host = "159.107.173.47"; //host name
	//$username = "ectracker"; //username
	//$password = "shroot"; //password
	$databasename = "ectracker"; //db name
	
	$host = "selid1a011.lmera.ericsson.se"; //host name
	$username = "ectracker"; //username
	$password = "UwQdtz4RPajK4F8X"; //password
	
	//connect to database
	$con = mysql_connect("$host", "$username", "$password");
	//echo $con;
	mysql_select_db("$databasename") or die("Cannot select DB");
	mysql_set_charset('utf8');
	return $con;
}

function connectTo1089DB(){
	$host = "159.107.173.47"; //host name
	$username = "ectracker"; //username
	$password = "shroot"; //password
	//$databasename = "ectracker"; //db name
	
	//$host = "selid1a011.lmera.ericsson.se"; //host name
	//$username = "ectracker"; //username
	//$password = "UwQdtz4RPajK4F8X"; //password
	
	//connect to database
	$con = mysql_connect("$host", "$username", "$password");
	//echo $con;
	//mysql_select_db("$databasename") or die("Cannot select DB");
	mysql_set_charset('utf8');
	return $con;
}
?>