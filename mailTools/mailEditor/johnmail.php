
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Lizhou's Mail tool</title>
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

if (isset($_POST['send_Mail'])) {	
	//echo $_POST['mail_cc'];
	//echo $_POST['mail_to'];
	//echo $_POST['mail_subject'];
	$to = $_POST['mail_to'];
	$subject = $_POST['mail_subject'];
	$message = $_POST['message'];
	$message = stripslashes($message); 
	//echo $message;	
	//$from = "john.edward.o.brien@ericsson.com";		
	//$from = "julie.mcnally@ericsson.com";
	//$from = "jiayi.li@ericsson.com";
	$from = "brian.moran@ericsson.com";
	
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	$headers .= "From:" . $from . "\r\n";
	$headers .= "Cc:" . $_POST['mail_cc'];
	mail($to,$subject,$message,$headers);
	echo "<h1 style='color: rgb(255, 0, 0);'>Mail sent out - Lizhou's mail tool!</h1>";
	$sql_ec_id = $_POST['sql_ec_id'];
	//echo $sql_ec_id;

	
	//create a html file to save a ec release mail
	$Solarisable_EcId = str_replace(" ", "_", $sql_ec_id);  //I like this varible name....Solarisable..
	$ec_mail_path = "../../mails/ST_".$Solarisable_EcId.".html";
	$handle = fopen($ec_mail_path, 'w') or die('Cannot open file:  '.$ec_mail_path);
	$html_mail = "<html><body>" .$message. "</body></html>";
	fwrite($handle, $html_mail);
	fclose($handle);
	
	
}
	
	
	//Mail info
	$subject = "Hello from John";
	$to = 'lizhou.wang@ericsson.com';
	//$cc = 'darwin.ncu@gmail.com';

	 
	 //compose a release mail with these info
	$message = "check it out";					
	
				



		
echo"
	<form method='post' action='johnmail.php'></br>
	<h3>EC Release Mail</h3>
	<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
	<span>To: </span><input type='text' id='mail_to' name='mail_to' maxlength='200' style='width: 500px' value= '" . $to . "'/></br>
	<span>Cc: </span><input type='text' id='mail_cc' name='mail_cc' maxlength='200' style='width: 500px' value='" . $cc . "' /></br>
	<input type='hidden' name='sql_ec_id' id='sql_ec_id' value='" . $sqlEcID . "' />
	<span>Subject: </span><input type='text' id='mail_subject' name='mail_subject' maxlength='200' style='width: 700px' value='" . $subject . "' /><p> Please note EU number and R-state</p></br></br>
	<textarea id='message' name='message' rows='50' cols='80' style='width: 80%'>
	" .$message . "
	</textarea>
	<br />
	<input type='submit' name='send_Mail' value='Send Mail' />
	<input type='reset' name='reset' value='Reset' />
</form>";

?>
<script type="text/javascript">
if (document.location.protocol == 'file:') {
	alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
}
</script>
</body>
</html>

<?php

function timetostring($str){
     $cliptime=explode("-",$str);
     $result="";
     for($i=0;$i<count($cliptime);$i++){
       $result=$result.$cliptime[$i];
     }
  return $result;
 }
?>
