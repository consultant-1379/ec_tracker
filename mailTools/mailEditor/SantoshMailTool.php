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

if (isset($_POST['send_Mail'])) {	
	$from = $_POST['mail_from'];
	$to = $_POST['mail_to'];
	$subject = $_POST['mail_subject'];
	$message = $_POST['message'];
	$message = stripslashes($message); 
	//echo $message;	
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	$headers .= "From:" . $from . "\r\n";
	$headers .= "Cc:" . $_POST['mail_cc'];
	mail($to,$subject,$message,$headers);
	echo "<h1 style='color: rgb(255, 0, 0);'>Mail sent out!</h1>";	
}
	

	//Mail info
	$from = '';
	$subject = "";
	$to = '';
	$cc = '';
	
	 //compose a release mail with these info
	$message = "";					
	
				

echo"
	<form method='post' action='SantoshMailTool.php'></br>
	<h3>Santosh Mail Tool</h3>
	<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
	<span>From: </span><input type='text' id='mail_from' name='mail_from' maxlength='60' style='width: 500px' value= '" . $from . "'/></br>
	<span>To: </span><input type='text' id='mail_to' name='mail_to' maxlength='200' style='width: 500px' value= '" . $to . "'/></br>
	<span>Cc: </span><input type='text' id='mail_cc' name='mail_cc' maxlength='200' style='width: 500px' value='" . $cc . "' /></br>
	<span>Subject: </span><input type='text' id='mail_subject' name='mail_subject' maxlength='200' style='width: 700px' value='" . $subject . "' /><span> Please note EU number and R-state</span></br></br>
	<textarea id='message' name='message' rows='50' cols='80' style='width: 80%'>
	" .$message . "
	</textarea>
	<br />
	<input type='submit' name='send_Mail' value='Send Mail' />
	<input type='reset' name='reset' value='Reset' />
</form>";
?>

</body>
</html>
