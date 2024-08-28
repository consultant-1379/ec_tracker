<?php 
session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('header.php'); ?>
</head>
<body>

		<div id='stylized' class='loginform'>
			<div id='loginContainer'>

<?php
$sqlEcID = '';
include('dbConnect.php');
if (isset($_POST['verifiedByST'])) {	
	
	$sql_ec_id = $_POST['sql_ec_id'];
	$sql_verified_by_signum = $_POST['sql_verified_by_signum'];
	

	//Update VERIFIED_BY_ST and VERIFIED_BY_SIGNUM
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	date_default_timezone_set("Europe/Dublin");
	 $verifiedBySTDate = date("Y-m-d H:i:s");
		
	$sql_update_verified = "UPDATE `ectracker`.`ecs` SET `VERIFIED_BY_ST` 
	= '" . $verifiedBySTDate. "' , `VERIFIED_BY_SIGNUM` = '". $sql_verified_by_signum ." ' WHERE EC_ID = '" . $sql_ec_id . "';";
	
	//echo $sql_update_verified;
	
	$result_update_verified = mysql_query($sql_update_verified);

	
	if($result_update_verified) {
		mysql_query("COMMIT");
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql_query("ROLLBACK");
	}
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
		//Send mails to notify CI execution
	$to = "PDLASSUREC@ex1.eemea.ericsson.se";
	//$to = "lizhou.wang@ericsson.com;";
	$subject = "An EC has been verified by ST";
	$message = "An EC " . $sql_ec_id . " has been verified by ST </br> Please see <a href='http://eniqdmt.lmera.ericsson.se/ectracker/'>EC Tracker</a> for details";
	$from = "PDLASSUREC@ex1.eemea.ericsson.se";
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	$headers .= "From:" . $from;
	mail($to,$subject,$message,$headers);

	echo "
	<span style='color:green; display:block; padding-top:15px;'>
		Done! 
		</br>A notification mail has been sent to CI Execution.
	</span>";
	
}
	

if (isset($_REQUEST['ecId'])) {
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	$ec_id = trim($_REQUEST['ecId']);
	$sqlEcID = mysql_real_escape_string($ec_id);

	//Get all the release names, and them generate tabs for each release
	$sql_comment_result = "SELECT e.COMMENT
					FROM ecs e
					WHERE EC_ID = '" . $sqlEcID . "';";
	//echo $sql_comment_result;			
	$result_comment_info = mysql_query($sql_comment_result);

	if($result_comment_info){
	 $comment_result_row = mysql_fetch_array($result_comment_info);
	 $comment = $comment_result_row['COMMENT'];	 
	
	 echo "<form name='myForm' action='verifiedByST.php' method='post'>";
	 echo "<input type='hidden' name='sql_ec_id' id='sql_ec_id' value='" . $sqlEcID . "' />";
	 echo "<input type='hidden' name='sql_verified_by_signum' id='sql_verified_by_signum' value='" . $_SESSION['user_signum'] . "' />";
	 date_default_timezone_set("Europe/Dublin");
	 $verifiedBySTDate = date("Y-m-d H:i:s");
	
	
	//UPDATE  `ectracker`.`ecs` SET  `DELIVERED_TO_ST` =  '2012-11-19 12:20:22' WHERE EC_ID =  'M_E_CTRS r3b_b442 20121119 10:15:06'
	
	$result_update_STdelivery_date = mysql_query($sql_update_STdelivery_date);
	 echo "	<textarea type='text' maxlength='250' style='width:400px; height:240px' readonly> Confirmed by " . $_SESSION['user_signum'] . " at ". $verifiedBySTDate." </textarea><BR CLEAR=LEFT>";
	 echo "<input type='hidden' name='verifiedByST' id='verifiedByST' value='true' />";
	 echo "<p><button type='submit'>Confirm</button></p>";
	 echo "</form>";
	
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
	
	
}
}
?>
</div></div>
</body>
</html>
