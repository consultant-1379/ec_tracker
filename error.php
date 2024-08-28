<?php 
session_start(); 
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<meta http-equiv='Content-Language' content='en' />
	<meta http-equiv='X-UA-Compatible' content='IE=100' />
	<title>IMF Model Management Tool</title>
	<link rel='stylesheet' type='text/css' media='screen' href='css/reset.css' />
	<link rel='stylesheet' type='text/css' media='screen' href='css/imfStyle.css' />
	<link rel='stylesheet' type='text/css' media='screen' href='css/fonts.css' />
	<link rel='shortcut icon' href='images/favicon.ico' />	
	<script type='text/javascript' src='js/jquery-1.2.6.pack.js'></script>
</head>
<body>
	<div id = 'imfContainer'>
		<?php include('menu.php'); ?>
		
		<div id='imfContentWrapper'>
			<div id='imfContent'>
				<div id='stylized'>
					<h1>Error</h1>
					<p>
						An error been logged and will be dealt with. We are sorry for the inconvenience.
					</p>
				</div>
			</div>
		</div>

		<?php include('footer.php'); ?>
	</div>
	<noscript><meta http-equiv='refresh' content='0; url=noscript.php'></noscript>
</body>
</html>
