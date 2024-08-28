<?php 
session_start(); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>EC release Mail</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- TinyMCE -->
<script type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script><script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

</head>
<body>
						
<?php
$sqlEcID = '';
include('../../dbConnect.php');
//Send mail
if (isset($_POST['send_Mail'])) {	
	//echo $_POST['mail_cc'];
	//echo $_POST['mail_to'];
	//echo $_POST['mail_subject'];
	$to = $_POST['mail_to'];
	$subject = $_POST['mail_subject'];
	$message = $_POST['message'];
	$message = stripslashes($message); 
	//echo $message;	
	$from = "PDLENIQDEL@ex1.eemea.ericsson.se";								
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	$headers .= "From:" . $from . "\r\n";
	$headers .= "Cc:" . $_POST['mail_cc'];
	mail($to,$subject,$message,$headers);
	echo "<h1 style='color: rgb(255, 0, 0);'>Mail sent out to FOA!</h1>";
	$sql_ec_id = $_POST['sql_ec_id'];
	//echo $sql_ec_id;

	//Update date of delivering to FOA
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	date_default_timezone_set("Europe/Dublin");
	$deliverToFOADate = date("Y-m-d H:i:s");
	
	$sql_update_FOAdelivery_date = "UPDATE `ectracker`.`ecs` SET `DELIVERED_TO_FOA` 
	= '" . $deliverToFOADate. "' WHERE EC_ID = '" . $sql_ec_id . "';";
	//echo $sql_update_FOAdelivery_date;
	//UPDATE  `ectracker`.`ecs` SET  `DELIVERED_TO_FOA` =  '2012-11-19 12:20:22' WHERE EC_ID =  'M_E_CTRS r3b_b442 20121119 10:15:06'
	
	$result_update_FOAdelivery_date = mysql_query($sql_update_FOAdelivery_date);

	
	if($result_update_FOAdelivery_date) {
		mysql("COMMIT");
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql("ROLLBACK");
	}
	mysql("END");
	// TRANSACTION END 
	mysql_close($con);

	
	//create a html file to save a ec release mail
	$Solarisable_EcId = str_replace(" ", "_", $sql_ec_id);  //I like this varible name....Solarisable..
	$ec_mail_path = "../../mails/FOA_".$Solarisable_EcId.".html";
	$handle = fopen($ec_mail_path, 'w') or die('Cannot open file:  '.$ec_mail_path);
	$html_mail = "<html><body>" .$message. "</body></html>";
	fwrite($handle, $html_mail);
	fclose($handle);		
}
	

if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='ADMIN') {
if (isset($_REQUEST['ecId'])) {
		//compose a release mail with info from database
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	$ec_id = trim($_REQUEST['ecId']);
	$sqlEcID = mysql_real_escape_string($ec_id);

	//Get all the release names, and them generate tabs for each release
	$sql_ec_result = "SELECT e.EC_ID, e.SHIPMENT_NAME, e.RELEASE_NAME, e.REQUESTER, e.EC_TYPE, e.EU_NUMBER, e.PACKAGE_TESTED, e.README_LINK, e.README_REVIEWED, e.SIGNUM, e.DELIVERED_TO_DM, e.DELIVERED_TO_ST, e.DELIVERED_TO_FOA 
					FROM ecs e
					WHERE EC_ID = '" . $sqlEcID . "';";
	//echo $sql_ec_result;			
	$result_ec_info = mysql_query($sql_ec_result);

	if($result_ec_info){
	 $ec_result_row = mysql_fetch_array($result_ec_info);
	 $shipment_name = $ec_result_row['SHIPMENT_NAME'];
	 $release_name = $ec_result_row['RELEASE_NAME'];
	 //Remove "(PLM)" in delivey mail subject if it's a PLM shipment
	 //$release_name = str_replace("(PLM)","",$release_name);
	 $requester = $ec_result_row['REQUESTER'];
	 $ec_type = $ec_result_row['EC_TYPE'];
	 $eu_number = $ec_result_row['EU_NUMBER'];
	 $readme_link = $ec_result_row['README_LINK'];
	 
	$sql_package_result = "SELECT PACKAGE_NAME, PACKAGE_GASKLINK FROM packages WHERE EC_ID = '". $sqlEcID ."';";
	$package_result = mysql_query($sql_package_result);

	$sql_tr_result = "SELECT TR_NUMBER, SLOGAN, ORIGINATION FROM trs WHERE EC_ID = '". $sqlEcID ."';";
	$tr_result = mysql_query($sql_tr_result);

	
	//mysql("END");
	// TRANSACTION END 
	mysql_close($con);

	//Mail info
	$subject = "NIV Release Mail: ". $ec_type ." ENIQ Package for ENIQ Events ". $shipment_name ." ".$release_name." Release";
	$to = 'PDLNEO09EX@ex1.eemea.ericsson.se';
	$cc = 'PDLASSUREC@ex1.eemea.ericsson.se; PDLENIQDEL@ex1.eemea.ericsson.se';
	
	//Get Packages name and info
	$packages_name = '';
	$packages_info = '';
	if($package_result){
	$cnt = 1;
		while($package_result_row = mysql_fetch_array($package_result))
		{
			if($cnt > 1){
			$packages_name .= ', ' . $package_result_row['PACKAGE_NAME'];
			}else{
			$packages_name .= $package_result_row['PACKAGE_NAME'];
			}
			$cnt++;
			$packages_info .= "<div><span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'><a href='". $package_result_row['PACKAGE_GASKLINK'] ."'>". $package_result_row['PACKAGE_GASKLINK'] ."</a></span></span></div>";	
		}
			}

	//Get tr numbers and TR info
	$tr_numbers = '';
	$tr_info = '';
	if($tr_result){
	$cnt = 1;
		while($tr_result_row = mysql_fetch_array($tr_result))
		{
			if($cnt > 1){
			$tr_numbers .= ', '.$tr_result_row['TR_NUMBER'];
			}else{
			$tr_numbers .= $tr_result_row['TR_NUMBER'];
			}
			$cnt++;
			$tr_info .= "<div>
				<span style='font-size:12px;'><span style='color: rgb(128, 0, 0);'><span style='font-family: tahoma, geneva, sans-serif;'>". $tr_result_row['TR_NUMBER'] ."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ". $tr_result_row['SLOGAN'] ." &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;". $tr_result_row['ORIGINATION'] ."</span></span></span></div>";
	
		}
			}


	 
	 //compose a release mail with these info
	$message = "	&nbsp;
			<div>
				<span style='font-size:12px;'><strong><span style='color: rgb(255, 0, 0);'><span style='font-family: tahoma, geneva, sans-serif;'>". $subject ."</span></span></strong></span></div>
			<div>
				&nbsp;</div>
			<div dir='ltr' style='margin-left: 40px;'>
				<span style='font-size:12px;'><span style='color: rgb(128, 0, 0);'><span style='font-family: tahoma, geneva, sans-serif;'><strong>Product Delivery information: </strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".  $packages_name ."</span></span></span></div>
			<div>
				&nbsp;</div>
			<div style='margin-left: 40px;'>
				<span style='font-size:12px;'><span style='color: rgb(128, 0, 128);'><span style='font-family: tahoma, geneva, sans-serif;'><strong>Reason: &nbsp;</strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				For TR fixes  ".
				$tr_numbers
				."</span></span></span></div>
			<div>
				&nbsp;</div>
			<div style='margin-left: 40px;'>
				<span style='font-size:12px;'><span style='color: rgb(128, 0, 128);'><span style='font-family: tahoma, geneva, sans-serif;'><strong>Requester: &nbsp; &nbsp;</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ". $requester ."&nbsp;</span></span></span></div>
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>1. Download Information &nbsp;</span></span></div>
			".$packages_info."
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>2. Readme:&nbsp;</span></span></div>
			<div>
				<span style='font-size:12px;'><a href='". $readme_link ."'><span style='font-family: tahoma, geneva, sans-serif;'>". $readme_link ."</span></a></span></div>
			<div>
				&nbsp;</div>
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>3. Included TR Fixes:</span></span></div>
			<div>
				<span style='font-size:12px;'><strong><span style='color: rgb(128, 0, 0);'><span style='font-family: tahoma, geneva, sans-serif;'>TR number &nbsp; &nbsp; &nbsp;Slogan &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; TR Origination&nbsp;</span></span></strong></span></div>
			".$tr_info."
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>External EC Releases for ENIQ can be found at the link below:&nbsp;</span></span></div>
			<div>
				<!--<span style='font-size:12px;'><a href='http://eniqdmt.lmera.ericsson.se/ectracker/index.php'><span style='font-family: tahoma, geneva, sans-serif;'>ENIQ Events EC Tracker Page</span></a></span></div>-->
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>http://eniqdmt.lmera.ericsson.se/ectracker/</span></span></div>
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><strong><span style='font-family: tahoma, geneva, sans-serif;'>ENIQ Delivery Mgmt (LMI)&nbsp;</span></strong></span></div>
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>Ericsson LMI&nbsp;</span></span></div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>Delivery Management&nbsp;</span></span></div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>Athlone, Westmeath,&nbsp;</span></span></div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>Ireland</span></span></div>
			<div>
				&nbsp;</div>
			<div>
				<span style='font-size:12px;'><span style='font-family: tahoma, geneva, sans-serif;'>http://www.ericsson.com</span></span></div>
			";					
	}
				



		} 
echo"
	<form method='post' action='toFOA.php'></br>
	<h3>EC Release Mail</h3>
	<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
	<span>To: </span><input type='text' id='mail_to' name='mail_to' maxlength='200' style='width: 500px' value= '" . $to . "'/></br>
	<span>Cc: </span><input type='text' id='mail_cc' name='mail_cc' maxlength='250' style='width: 500px' value='" . $cc . "' /></br>
	<input type='hidden' name='sql_ec_id' id='sql_ec_id' value='" . $sqlEcID . "' />
	<span>Subject: </span><input type='text' id='mail_subject' name='mail_subject' maxlength='200' style='width: 700px' value='" . $subject . "' /><span> Please note EU number and R-state</span></br></br>
	<textarea id='message' name='message' rows='50' cols='80' style='width: 80%'>
	" .$message . "
	</textarea>
	<br />
	<input type='submit' name='send_Mail' value='Send Mail' />
	<input type='reset' name='reset' value='Reset' />
</form>";

}else{
echo "Permissions denied! Please login.";
}
?>
<script type="text/javascript">
if (document.location.protocol == 'file:') {
	alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
}
</script>
</body>
</html>

<?php
function composeMail(){
}

function timetostring($str){
     $cliptime=explode("-",$str);
     $result="";
     for($i=0;$i<count($cliptime);$i++){
       $result=$result.$cliptime[$i];
     }
  return $result;
 }
?>
