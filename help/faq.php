<?php session_start(); ?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<?php include('helpHeader.php'); ?>
</head>
<body>
	<div id='imfHelpContainer'>
		<?php include('helpMenu.php'); ?>
		<div id='imfHelpContent'>
			<h1>FAQ</h1>
			<?php
				$faq_xml = @simplexml_load_file("faqs.xml");
				if ($faq_xml) {
					$faqs = $faq_xml->xpath('/faqs/faq');
					if (count($faqs)>0) {
						echo "<ol id='content'>";
						for($i=0; $i<count($faqs); $i++) {
							echo "<li><a href='#faq$i'>" . (string)$faqs[$i]->question . "</a></li>";
						}
						echo "</ol>";
						for($i=0; $i<count($faqs); $i++) {
							echo 
							"<h2 id='faq$i'>" . (string)$faqs[$i]->question . "</h2><p>" . (string)$faqs[$i]->answer . "</p>";
						}
					} else {
						echo "<p style='color:red;'>FAQ is not available!</p>";
					}
				} else {
					echo "<p style='color:red;'>FAQ is not available!</p>";
				}
			?>
		</div>
	</div>
	<noscript><meta http-equiv='refresh' content='0; url=noscript.php'></noscript>
</body>
</html>