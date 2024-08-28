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
//Send mail
if (isset($_POST['updateComment'])) {	
	
	$sql_ec_id = $_POST['sql_ec_id'];
	$comment = $_POST['comment'];
	

	//Update comment
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
		
	$sql_update_comment = "UPDATE `ectracker`.`ecs` SET `COMMENT` 
	= '" . $comment. "' WHERE EC_ID = '" . $sql_ec_id . "';";
	
	$result_update_comment = mysql_query($sql_update_comment);

	
	if($result_update_comment) {
		mysql_query("COMMIT");
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql_query("ROLLBACK");
	}
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
	echo "Comment updated!";	
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
	
	 echo "<form name='myForm' action='updateComment.php' method='post'>";
	 echo "<input type='hidden' name='sql_ec_id' id='sql_ec_id' value='" . $sqlEcID . "' />";
	 echo "	<textarea type='text' id='comment' name='comment' maxlength='1000' style='width:400px; height:240px'>". $comment ."</textarea><BR CLEAR=LEFT>";
	 echo "<input type='hidden' name='updateComment' id='updateComment' value='true' />";
	 echo "<p><button type='submit'>Submit</button></p>";
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
