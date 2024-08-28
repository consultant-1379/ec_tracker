<?php 
session_start(); 
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<?php include('header.php'); ?>
	<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	
	<link rel='stylesheet' href='css/popbox.css' type='text/css'>
	<script type='text/javascript' charset='utf-8' src='js/popbox.js'></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".various3").fancybox({
				'width'			: '75%',
				'height'			: '75%',
				'autoScale'			: false,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic',
				'type'				: 'iframe'
			});
			
			$(".various4").fancybox({
				'width'			: '65%',
				'height'			: '65%',
				'autoScale'			: false,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic',
				'type'				: 'iframe'
			});
		});
	</script>
</head>
<body onload="javascript:showFirstRelease()">
	<div id = 'imfContainer'>
		<?php include('menu.php'); ?>
		
		<div id='imfContentWrapper'>
			<div id='imfContent'>
				<div id='stylized' class='myform'>
					<input id ='packagefFilter' type='text' /></br>
					</br><p>EC Filter</p>
				</div>
				<div id = 'ecListsWrapper'>	
		
		<!-- This following code dose nothing...They are here just for some dynamic layout issue, will be removed when I figure out a better solution-->
		<!-- It is a bug of this popup box plugin, The width and position of the popup box depend on the first popup box...A fix is needed..-->
		<div class='popbox'>
          <a class='open' href=''></a>
          <div class='collapse'>
            <div class='box'>
              <div class='arrow'></div>
              <div class='arrow-border'></div>                                            
            </div>
          </div>
        </div>	
		<!--Ends here-->
		
				<?php modelResults(); ?>
				</div>
			</div>
		</div>
		<?php include('footer.php'); ?>
	</div>
	
		</body>
</html>

<?php
function modelResults(){
	include('dbConnect.php');
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	//Get all the release names, and them generate tabs for each release
	$sql_prim = "SELECT DISTINCT RELEASE_NAME, SHIPMENT_NAME
			FROM ecs
			ORDER BY RELEASE_NAME DESC;";
				
	$result_prim = mysql_query($sql_prim);
	
	$cnt = 0;
	if ($result_prim) {
		while($row_prim = mysql_fetch_array($result_prim)) {
		$cnt++;									
				echo " 
				<div id = 'tableTitle' class = 'titleID'>" . $row_prim['SHIPMENT_NAME'] ." " . $row_prim['RELEASE_NAME'] . "</div>
				<div id='result' class='myform'>		
				<table class='outerTable'>
					<tr class='outerTable'>
					<td class='outerTable'>
						<table class='innerTable'>
							<tr class='innerTable'>
								<th class='innerTable' style='width:3%;'></th>
								<th class='innerTable'>Package</th>
								<th class='innerTable' style='width:10%;'>Requester</th>
								<th class='innerTable' style='width:10%;'>EC Type</th>
								<th class='innerTable' style='width:10%;'>TRs</th>
								<th class='innerTable'>Readme Link</th>
								<th class='innerTable' style='width:20%;'>Comment</th>
								<th class='innerTable' style='width:10%;'>Signum</th>
								<th class='innerTable' style='width:10%;'>Delivered to DM</th>
								<th class='innerTable' style='width:10%;'>Delivered to ST</th>
								<th class='innerTable' style='width:10%;'>Delivered to FOA</th>";
								if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='ADMIN') {
									echo "<th class='innerTable' style='width:10%;'>Update</th>";
								}
							echo "
							</tr>";
							
							$sql_ec_result = "SELECT EC_ID, SHIPMENT_NAME, RELEASE_NAME, REQUESTER, EC_TYPE, EU_NUMBER, PACKAGE_TESTED, README_LINK, README_REVIEWED, COMMENT, SIGNUM, DELIVERED_TO_DM, DELIVERED_TO_ST, DELIVERED_TO_FOA 
								FROM ecs
								WHERE SHIPMENT_NAME = '" . $row_prim['SHIPMENT_NAME']. "' AND RELEASE_NAME = '" . $row_prim['RELEASE_NAME'] . "' ".
								"ORDER BY DELIVERED_TO_DM DESC, EC_ID;";
							
							//$sql_ec_result = "SELECT EC_ID, SHIPMENT_NAME, RELEASE_NAME, REQUESTER, EC_TYPE, EU_NUMBER, PACKAGE_TESTED, README_LINK, README_REVIEWED, COMMENT, SIGNUM, DELIVERED_TO_DM, DELIVERED_TO_ST, DELIVERED_TO_FOA 
								//FROM ecs
								//WHERE SHIPMENT_NAME = '" . $row_prim['SHIPMENT_NAME']. "' AND RELEASE_NAME = '" . $row_prim['RELEASE_NAME'] . "' AND COMMENT NOT LIKE '%superseded%' 
								//ORDER BY DELIVERED_TO_DM DESC;";
																
								//echo $sql_ec_result;
							
							$ec_result = mysql_query($sql_ec_result);
							if($ec_result){
							while($ec_result_row = mysql_fetch_array($ec_result))
							{								
								if(strstr($ec_result_row['COMMENT'], 'superseded') != false){
								echo "<tr class='innerTable' bgcolor='#E8EAEC'>"; 
								}
								else{
								echo "<tr class='innerTable'>"; 
								}	
								
								
								////////////////////////////////
								//echo "<td class='innerTable packageName'>";
								//echo "<div class='popbox'>";
								//echo "<span class='open'>";
								//$sql_package_result = "SELECT PACKAGE_NAME, PACKAGE_GASKLINK FROM packages WHERE EC_ID='" . $ec_result_row['EC_ID'] . "' ORDER BY PACKAGE_ID;";
								//$package_result = mysql_query($sql_package_result);
								//if($package_result){
								//while($package_result_row = mysql_fetch_array($package_result)){
								
								//echo "<a class='reportDownload' href='".  $package_result_row['PACKAGE_GASKLINK'] ."' target='_blank 'resizable=1,height=640,width=640,scrollbars=1''>". $package_result_row['PACKAGE_NAME'] ."</a>  </br>";

								//}
								//}
								//echo "</span>";
								//echo "<div class='collapse'>";
								//echo "<div class='box' style='width:1000px; margin-left:450px;'>";
								//echo "<div class='arrow1' ></div>";
								//echo "<div class='arrow-border1'></div>";
								//echo "tetsetsetst";		
								//echo "<a href='' class='close'>close</a>";
								//echo "</div></div></div>";
								//echo "</td>";
								//////////////////////////////////////////
								
								if(strstr($ec_result_row['COMMENT'], 'superseded') != false){
									echo "<td class='innerTable expand'></td>";
								}
								else{
									echo "<td class='innerTable expand'><div class='expandImg'><img src='images/expand.png' alt='Show superseded packages'></div></td>";
								}	
								
								
							
								
								echo "<td class='innerTable packageName'>";
								$sql_package_result = "SELECT PACKAGE_NAME, PACKAGE_GASKLINK FROM packages WHERE EC_ID='" . $ec_result_row['EC_ID'] . "' ORDER BY PACKAGE_ID;";
								$package_result = mysql_query($sql_package_result);
								if($package_result){
								while($package_result_row = mysql_fetch_array($package_result)){
								
								echo "<a class='reportDownload' href='".  $package_result_row['PACKAGE_GASKLINK'] ."' target='_blank 'resizable=1,height=640,width=640,scrollbars=1''>". $package_result_row['PACKAGE_NAME'] ."</a>  </br>";

								}
								}
								echo "</td>";
								
								echo "<td class='innerTable'>" . $ec_result_row['REQUESTER'] ."</td>";
								echo "<td class='innerTable'>" . $ec_result_row['EC_TYPE'] ."</td>";
								//print TRs 
								echo "<td class='innerTable'>";
								$sql_tr_result = "SELECT TR_NUMBER FROM trs WHERE EC_ID='" . $ec_result_row['EC_ID'] . "' ORDER BY TR_ID;";
								$tr_result = mysql_query($sql_tr_result);
								if($tr_result){
								while($tr_result_row = mysql_fetch_array($tr_result)){
								
								
								//echo "<a href='viewTR.php?trlId=" . $tr_result_row['TR_NUMBER'] . "' target='_blank'>". $tr_result_row['TR_NUMBER'] ."</a>  ";
								echo "<a href='https://mhweb.ericsson.se/TREditWeb/faces/tredit/tredit.xhtml?eriref=" . $tr_result_row['TR_NUMBER'] . "' target='_blank 'resizable=1,height=640,width=640,scrollbars=1''>". $tr_result_row['TR_NUMBER'] ."</a>  ";

								}
								}
								echo "</td>";
								//echo "<td class='innerTable'>" . $ec_result_row['PACKAGE_GASKLINK'] ."</td>";
								echo "<td class='innerTable'><a class='reportDownload' href='". $ec_result_row['README_LINK']."' target='_blank'>View</a></td>";

							    echo "<td class='innerTable commentColumn'>";
								$comment_summary = substr($ec_result_row['COMMENT'],0,40);
								$comment_summary = str_replace("\r\n","<br>",$comment_summary);
								//If there are comments
								if($ec_result_row['COMMENT'] != ''){
								//echo $comment_summary ."...";
								echo "<div class='popbox'>";
								echo "<span class='open'>".$comment_summary."...  (hover to view all)</span>";
								echo "<a class='various4 viewCommentButton' href='updateComment.php?ecId=". $ec_result_row['EC_ID'] ."'>Update</a>";
								echo "<div class='collapse'>";
								echo "<div class='box'>";
								echo "<div class='arrow'></div>";
								echo "<div class='arrow-border'></div>";
								
								$comment = $ec_result_row['COMMENT'];
								$comment_summary = substr($comment,0,20);
								$comment = str_replace("\r\n","<br>",$comment);
								$comment_summary = str_replace("\r\n","<br>",$comment_summary);
								echo $comment;						
								//echo "<a href='' class='close'>close</a>";
								echo "</div></div></div>";
								echo "</td>";								
								//echo "<td class='innerTable'>" . $ec_result_row['COMMENT'] ."</td>";
								}if ($ec_result_row['COMMENT'] == ''){
								echo "<a class='various4 viewCommentButton' href='updateComment.php?ecId=". $ec_result_row['EC_ID'] ."'>Update</a>";												
								echo "</td>";		
								}
								
								
								echo "<td class='innerTable'>" . $ec_result_row['SIGNUM'] ."</td>";
								
								//Delivered to DM
								if(empty($ec_result_row['DELIVERED_TO_DM'])){
								echo "<td class='innerTable' style='background:#FF1818;'>Database error</td>";
								}else{
								echo "<td class='innerTable' style='background:#04B45F;'>" . $ec_result_row['DELIVERED_TO_DM'] ."</td>";
								}
								
								
								//If delivered to System Test
								if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='ADMIN') {
									if(empty($ec_result_row['DELIVERED_TO_ST'])){
									echo "<td class='innerTable'><a href='<td class='innerTable'><a href='mailTools/mailEditor/toST.php?ecId=" . $ec_result_row['EC_ID'] . "' target='_blank'>Deliver to ST</a></td>";
									}else{
									$Solarisable_EcId = str_replace(" ", "_", $ec_result_row['EC_ID']);  //I like this varible name....Solarisable..
									$ec_mail_path = "mails/ST_".$Solarisable_EcId.".html";
									echo "<td class='innerTable' style='background:#04B45F;'><a class='various3' href = '". $ec_mail_path ."' title='Release mail'>" . $ec_result_row['DELIVERED_TO_ST'] ."</a></td>";
									}
								}else{
									if(empty($ec_result_row['DELIVERED_TO_ST'])){
									echo "<td class='innerTable' style='background:#FF1818;'>NO</td>";
									}else{
									$Solarisable_EcId = str_replace(" ", "_", $ec_result_row['EC_ID']);  //I like this varible name....Solarisable..
									$ec_mail_path = "mails/ST_".$Solarisable_EcId.".html";
									echo "<td class='innerTable' style='background:#04B45F;'><a class='various3' href = '". $ec_mail_path ."' title='Release mail'>" . $ec_result_row['DELIVERED_TO_ST'] ."</a></td>";
									}
								}
								
								
								//If delivered to FOA
								if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='ADMIN') {
									if(empty($ec_result_row['DELIVERED_TO_FOA'])){
									echo "<td class='innerTable'><a href='mailTools/mailEditor/toFOA.php?ecId=" . $ec_result_row['EC_ID'] . "' target='_blank'>Deliver to FOA</a></td>";
									}else{
									$Solarisable_EcId = str_replace(" ", "_", $ec_result_row['EC_ID']);  //I like this varible name....Solarisable..
									$ec_mail_path = "mails/FOA_".$Solarisable_EcId.".html";
									echo "<td class='innerTable' style='background:#04B45F;'><a class='various3' href = '". $ec_mail_path ."' title='Release mail'>" . $ec_result_row['DELIVERED_TO_FOA'] ."</a></td>";
									}
								}else{
									if(empty($ec_result_row['DELIVERED_TO_FOA'])){
									echo "<td class='innerTable' style='background:#FF1818;'>NO</td>";
									}else{
									$Solarisable_EcId = str_replace(" ", "_", $ec_result_row['EC_ID']);  //I like this varible name....Solarisable..
									$ec_mail_path = "mails/FOA_".$Solarisable_EcId.".html";
									echo "<td class='innerTable' style='background:#04B45F;'><a class='various3' href = '". $ec_mail_path ."' title='Release mail'>" . $ec_result_row['DELIVERED_TO_FOA'] ."</a></td>";
									}
								}
								
									if(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']) && $_SESSION['user_role']=='ADMIN') {
									echo "<td class='innerTable'><a class='reportDownload' href='http://atrcx1089.athtem.eei.ericsson.se/phpMyAdmin-2.11.11.3-english/index.php?db=ectracker' target='_blank'>Update</a></td>";
								}
								echo "</tr>";
								}
								}
							
						echo "
						</table>
					</td>
					</tr>
				</table>
				</div>";
		}
	} else {
		echo "No result";
	}
	mysql_close($con);
}
?>

<script type="text/javascript">
  $(function(){
       $('#packagefFilter').keyup(function(){
	      $('tr.innerTable')
		.hide()
		.filter(":contains('"+( $(this).val() )+"')")
		.show();
	$('tr.innerTable')
		.filter(":contains('Package')")
		.filter(":contains('Comment')")
		.filter(":contains('Signum')")
		.show();
	   }).keyup();
  });
  

  var content;

    $(".expandImg").toggle(
      function () {
	  //alert($(this).html());
	$(this).html("<img src='images/collapse.png' alt='Hide superseded packages'>");
	$('tr.innerTable')
		.filter(":contains('"+( $(this).parent().next("td").text() )+"')")
		.show("slow");
		//$(this).css('color','rgb(51, 102, 153)');
		$('tr.innerTable')
		.filter(":contains('Package')")
		.filter(":contains('Comment')")
		.filter(":contains('Signum')")
		.show("slow");
		 }, 
	
      function () {
	  $(this).html("<img src='images/expand.png' alt='Show superseded packages'>");
       $('tr.innerTable')
		.filter(":contains('superseded')")
		.filter(":contains('"+( $(this).parent().next("td").text() )+"')")
		.hide("slow");
      }
    );
  

/*
  var content;

    $("td.packageName").toggle(
      function () {
	  alert($(this).text());
	$('tr.innerTable')
		.hide()
		.filter(":contains('"+( $(this).text() )+"')")
		.show();
		$(this).css('color','rgb(51, 102, 153)');
		$('tr.innerTable')
		.filter(":contains('Package')")
		.filter(":contains('Comment')")
		.filter(":contains('Signum')")
		.show();	
		 }, 
	

      function () {
       $('tr.innerTable').show();

      }
    );
	
 
 /*
 $(".packageName").hover(
      function () {
	content = $(this).html();
	alert(cotent);
	$(this).css('color','rgb(51, 102, 153)').css('cursor','pointer');
	//$(this).text(content);
		 }, 
      function () {
       $(this).css('color','#000');
	//$(this).text(content);
      }
    );
	*/
function hideSupersededTR(){
$('tr.innerTable')
		.filter(":contains('superseded')")
		.hide();
}

function showFirstRelease(){
$('div#result').first().slideDown("slow");
hideSupersededTR();
//var a=document.getElementsByTagName("td")
//for(var i=0;i<a.length;i++){ if(a[i].className="innerTable" && a[i].innerHTML="NO"){a[i].innerHTML="Rock"} }
}

/*
$('div#tableTitle').toggle(
 function () {
	 $(this).next("div").slideUp("fast");	
		},		
		 function () {
	//$(this).text("Show notes");
	$(this).next("div").slideDown("fast");		
		}		
);
*/

$(".titleID").toggle(
 function () {
	hideSupersededTR();
	if(($(this).next("div")).css("display")=="none"){
	 $(this).next("div").slideDown("fast");
	}else{
	 $(this).next("div").slideUp("fast");
	}
		},		
		 function () {
	//$(this).text("Show notes");
	if(( $(this).next("div")).css("display")=="none"){
	 $(this).next("div").slideDown("fast");
	}else{
	 $(this).next("div").slideUp("fast");
	}			
		}		
);
</script>

<script type='text/javascript'>
           $(document).ready(function(){
             $('.popbox').popbox();
           });
 </script>

