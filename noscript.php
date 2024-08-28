<?php 
session_start(); 
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<?php include('header.php'); ?>
</head>
<body>
	<div id = 'imfContainer'>
		<?php include('menu.php'); ?>
		
		<div id='imfContentWrapper'>
			<div id='imfContent'>
				<div id='stylized'>
					<h1>JavaScript Required</h1>
					<p>
						JavaScript must be enabled in order for you to use ENIQ Events EC Tracker. 
						However, it seems JavaScript is either disabled or not supported by your browser. 
						Please enable JavaScript by changing your browser options, then <a href='index.php'>try again</a>
					</p>
				</div>
			</div>
		</div>

		<?php include('footer.php'); ?>
	</div>
</body>
</html>