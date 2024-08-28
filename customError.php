<?php

//error handler function
function customError($errno, $errstr, $error_file, $error_line, $error_context)
{
	if (error_reporting() === 0)
    {
        return;
    }
	$error = "Date:".date('d-m-Y h:i')." Error: [$errno] $errstr Error File: $error_file [line $error_line] \n";
	
	// Write into Error Log
	// fileName: need to be updated with server absolute path
	$filename ="C:/xampp/htdocs/imftool/log/errorLog.log";
	if (file_exists($filename)) {
		$file=fopen($filename,"a");
		fwrite($file, $error);
		fclose($file);
	} else {
		$file = fopen($filename, 'w');
		fwrite($file, $error);
		fclose($file);
	}
	
	// Email Administrator with the Error Info
	$subject = "IMF Tool Error Log";
	$mail_to = "lizhou.wang@ericsson.com";
	$message = "IMF Tool Error:<br /><br />" . $error;
	// In case any lines are larger than 70 characters, use wordwrap()
	$message = wordwrap($message, 70);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: IMF Tool";
	date_default_timezone_set('UTC');
	mail($mail_to, $subject, $message, $headers);
	
	if ($errno!= E_USER_ERROR){ 
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=error.php">'; 
		die();
		/*
			other methods of redirecting
			printf("<script>location.href='error.php'</script>");
			header( 'Location: error.php' ); 
		*/
	}
}

//set error handler
set_error_handler("customError");

?>