<?php
session_start();
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<?php include('header.php'); ?>
</head>
<body>
<?php 
include('dbConnect.php'); 
if(!(isset($_SESSION['user_signum']) && isset($_SESSION['user_role']))) {
header( 'Location: http://eniqdmt.lmera.ericsson.se/ectracker/login.php' ); 
}
?>
		

		<div id = 'imfContainer'>
		<?php include('menu.php'); ?>
		<div id='imfContentWrapper'>
			<div id='imfContent'>
				<div id='stylized' class='myform'>
					<form name='myForm' action='ecSubmit.php' method='post' onSubmit='return validateECForm()'> 
					<!-- <form name='myForm' action='ecSubmit.php' method='post' onSubmit='return false'>-->
						<h1>Deliver EC</h1>
						<p>Deliver an EC. Fill out this form and submit it. </br></br>
						<a href="http://atrcx1089.athtem.eei.ericsson.se/webpages/ECTrackerDemo/ECTrackerDesigner/ECTrackerForDesigner.html" target="_blank"><img src="images/video.png"  style="width:30px; height:30px" alt="Video"></a> <font color="red">showing how to fill out this page.
						   Need more help? Write a <a href="http://jira-oss.lmera.ericsson.se/browse/ENC">JIRA</font></a>.
			
						<span id='message'>
					
						<?php
						//if(isset($_POST['tr3_number'])){
						//echo "true";
						//}else{
						//echo "false";
						//}
						if (isset($_POST['submit_EC'])) {		
							//Submit ec info except TRs and packages, TRs and packages need to be submitted seperately
							$fullShipmentName = $_POST['shipment_name'];
							$shipmentDetails = explode('/', $fullShipmentName);
							$shipment_name =  $shipmentDetails[0];
							$release_name =  $shipmentDetails[1];
							$packagesName = '';
							$pkg_id = 1;
							while(isset($_POST["package".$pkg_id."_name"])){
							$package_withoutspace = trim($_POST["package".$pkg_id."_name"]);
							if($pkg_id == 1){
							$packagesName .= $package_withoutspace;
							}else{
							$packagesName .= '_'.$package_withoutspace;
							}							
							$pkg_id++;
							}
							//echo $packagesName;
							$submit_result = submitEC($shipment_name, $release_name, $packagesName, $_POST['requester'],
							$_POST['ec_type'], $_POST['eu_number'], $_POST['pkg_tested'], $_POST['readme_link'], $_POST['readme_reviewed'], 
							$_POST['comment'], $_POST['signum']);
							
							//Submit Packages
							$ecId = $submit_result[2];
							//echo($ecId);
							$pkg_id = 1;
							while(isset($_POST["package".$pkg_id."_name"])){
							$pkg_submit_result = submitPackage($_POST["package".$pkg_id."_name"], $_POST["package".$pkg_id."_gasklink"], $ecId);
							if ($pkg_submit_result[0] == "success") {
								//echo "
								//<span style='color:green; display:block; padding-top:15px;'>
								//	This package <b>$pkg_submit_result[1] with its EC ID </b>_<b>$pkg_submit_result[2]</b> is successfully submitted. 
								//</span>";
							} elseif ($tr_submit_result[0] == "fail") {
								echo "
								Insert package <span style='color:red; display:block; padding-top:15px;'>$tr_submit_result[1]</span> failed.";
							}
							
							$pkg_id++;
							}

							//Submit TRs
							//echo($ecId);
							$tr_id = 1;
							while(isset($_POST["tr".$tr_id."_number"])){
							//echo($_POST["tr".$tr_id."_number"]);
							//echo($_POST["tr".$tr_id."_slogan"]);
							//echo($_POST["tr".$tr_id."_origination"]);
							$tr_submit_result = submitTR($_POST["tr".$tr_id."_number"], $_POST["tr".$tr_id."_slogan"], $_POST["tr".$tr_id."_origination"], $ecId);
							if ($tr_submit_result[0] == "success") {
								//echo "
								//<span style='color:green; display:block; padding-top:15px;'>
								//	This TR <b>$tr_submit_result[1] with its EC ID </b>_<b>$tr_submit_result[2]</b> is successfully submitted. 
								//</span>";
							} elseif ($tr_submit_result[0] == "fail") {
								echo "
								Insert TR <span style='color:red; display:block; padding-top:15px;'>$tr_submit_result[1]</span> failed.";
							}
							
							$tr_id++;
							}
							
							
							if ($submit_result[0] == "success") {
								//Send mails to notify CI execution
								$to = "PDLASSUREC@ex1.eemea.ericsson.se; peter.doran@ericsson.com; martina.brady@ericsson.com; alan.donnelly@ericsson.com";
								//$to = "PDLASSUREC@ex1.eemea.ericsson.se;";
								$subject = "EC ".$packagesName." is submited to EC Tracker for ".$_POST['shipment_name'].$_POST['release_name'];
								$message = "EC: ".$packagesName." is submited to ".$_POST['shipment_name'].$_POST['release_name']."</br> Please see <a href='http://eniqdmt.lmera.ericsson.se/ectracker/'>EC Tracker</a> for details";
								$from = "PDLASSUREC@ex1.eemea.ericsson.se";
								$headers = "MIME-Version: 1.0\r\n"; 
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
								$headers .= "From:" . $from;
								mail($to,$subject,$message,$headers);

								echo "
								<span style='color:green; display:block; padding-top:15px;'>
									This EC <b>$submit_result[1]</b> is successfully submited. 
									</br>A notification mail has been sent to CI Execution.
								</span>";
							} elseif ($submit_result[0] == "fail") {
								echo "
								<span style='color:red; display:block; padding-top:15px;'>$submit_result[1]</span>";
							}
							
						}
						?>
						</span>
						</p>
						<table id='EC_submit_form'>
							
							<tr>
								<th><label for='shipment_name'>Shipment Name <span style='color:red;'>*</span></label></th>
								<td>
									<select id='shipment_name' name='shipment_name' onchange='checkShipment(this.value)'>
									<option value=""></option>
							<?php
							//$con = connect();
							//if (!$con) {
							//echo "failed";
							//	trigger_error(mysql_error($con), E_USER_ERROR);
							//	die('Could not connect: ' . mysql_error($con));
							//}
							
							$con1 = connectTo1089DB();
							if (!$con1) {
							echo "failed";
								trigger_error(mysql_error($con1), E_USER_ERROR);
								die('Could not connect: ' . mysql_error($con1));
							}
							
							
							//Get shipment names from mike's database (NMI baseline database)...
							$sql_shipments_result = "SELECT DISTINCT shipment from nmi_baseline.document_details WHERE  `shipment` LIKE  '%ENIQ_E%' ORDER BY shipment DESC;";
							echo $sql_shipments_result;

							//echo $sql_ec_result;			
							$result_shipments = mysql_query($sql_shipments_result, $con1);
							//***************TEMP Shipment name here**********************//
							echo "<option value=ENIQ_E13.2/3.2.7.EU4(PLM)>ENIQ_E13.2/3.2.7.EU4(PLM)</option>";
                            				echo "<option value=ENIQ_E14.0/4.0.6_SV.EU1>ENIQ_E14.0/4.0.6_SV.EU1</option>";
							echo "<option value=ENIQ_E13.2/3.2.7_EU3>ENIQ_E13.2/3.2.7_EU3</option>";
						    	echo "<option value=ENIQ_E13.2/3.2.7_ES>ENIQ_E13.2/3.2.7_ES</option>";	
							echo "<option value=ENIQ_E13.0/3.0.14_EU4>ENIQ_E13.0/3.0.14_EU4</option>";
							echo "<option value=ENIQ_E13.0/3.0.14_EU3>ENIQ_E13.0/3.0.14_EU3</option>";
							echo "<option value=ENIQ_E13.0/3.0.14_EU1>ENIQ_E13.0/3.0.14_EU1</option>";
							echo "<option value=ENIQ_E13.0/3.0.5_SON_EU2>ENIQ_E13.0/3.0.5_SON_EU2</option>";
							echo "<option value=ENIQ_E13.0/3.0.5_SON_EU3>ENIQ_E13.0/3.0.5_SON_EU3</option>";
							while($shipment_result_row = mysql_fetch_array($result_shipments)){								
								$shipment_name = $shipment_result_row['shipment'];
								echo "<option value=" . $shipment_name . ">" . $shipment_name . "</option>";
							 }
							 
							 mysql_query("END");
							 // TRANSACTION END 
							// mysql_close($con);
							 mysql_close($con1);
							?>
																
							</select>
							<BR CLEAR=LEFT>
									<span id='shipmentname_error' class='error_message'></span>
								</td>
								<td class='comment'></td>
							</tr>
														
							<tr id ='pkg0_name_tr'>
								<th></a><label for='package_name'><a href="http://atrclin2.athtem.eei.ericsson.se/wiki/index.php/EC_tracker_Instruction_Videos" target="_blank"><img src="images/questionmark.jpg"  style="width:16px; height:16px" alt="What's this?"></a> Package Name(with R-state)<span style='color:red;'>*</span></label></th>
								<td>
									<input type='text' id='package1_name' name='package1_name' maxlength='150' /><BR CLEAR=LEFT>
									<span id='package1name_error' class='error_message'></span>
								</td>
								<td>
								<table>
								<tr>
								<td class="PKG_td"><div class = "addpkg_div_place" id = "addpkg_div_place"><div class="addpkg_button" id="addpkg_button" onclick="addPKG(this);">Add</div></div></td>
								<td class="comment">Please add package if this EC contains multiple packages</td>
								</tr>
								</table>
								</td>
							</tr>
							
							<tr id='pkg0_gasklink_tr'>
								<th><label for='package_gasklink'><a href="http://atrclin2.athtem.eei.ericsson.se/wiki/index.php/EC_tracker_Instruction_Videos" target="_blank"><img src="images/questionmark.jpg"  style="width:16px; height:16px" alt="What's this?"></a> Package Gask Link <span style='color:red;'>*</span></label></th>
								<td colspan=2 class="input_td">
									<input type='text' id='package1_gasklink' name='package1_gasklink' maxlength='250' style="width:765px;"/><BR CLEAR=LEFT>
									<span id='package1gasklink_error' class='error_message'></span>
								</td>
							</tr>
							
							
							
							<tr>
								<th><label for='resquster'>Requester <span style='color:red;'>*</span></label></th>
								<td class="input_td">
									<select id='requester' name='requester'>
									<option value=""></option>
									<option value="FT">FT</option>
									<option value="ST">ST</option>
									<option value="FOA">FOA</option>
									<option value="Customer">Customer</option>
									</select>
									<BR CLEAR=LEFT>
									<span id='requester_error' class='error_message'></span>
								</td>
								<td class='comment'></td>
							</tr>
							
												
							<tr>
								<th>
									<input type='radio' id='mws_ec' name='ec_type' value='mws_ec'  style='width:20px; border:0px; position:relative; left:95%;'/>
								</th>
								<td>This is a MWS EC</td>
								<td class='comment' rowspan='2'>
									<span id='mws_radio_error' class='error_message'></span>
									<span></span>
								</td>					
							</tr>

							<tr>
								<th>
									<input type='radio' id='hot_ec' name='ec_type' value='hot_ec' checked='checked' style='width:20px; border:0px; position:relative; left:95%;'/>
								</th>
								<td>This is a HOT EC</td>
							</tr>
							<tr id='fm_form'>
								<th><label for='eu_number'>EU number <span style='color:red;'> </span></label></th>
								<td class="input_td">
									<input type='text' id='eu_number' name='eu_number' maxlength='10'
									/><BR CLEAR=LEFT>
									<span id='eunumber_error' class='error_message'></span>
								</td>
								<td class='comment'>e.g. 2</td>
							</tr>
							

							<tr>
								<th><label for='package_tested'>Is this package tested? <span style='color:red;'>*</span></label></th>
								<td class="input_td">
								<table>
								<tr>
								<th style='height:24px;'><input type='radio' id='pkg_tested_no' name='pkg_tested' value='pkg_tested_no'  checked='checked'  style='width:20px; border:0px; position:relative; left:0%;'/> <span style='position:relative; left:-60%;'>No</span>
							
								</th>	
								<th style='height:0px;'><input type='radio' id='pkg_tested_yes' name='pkg_tested' value='pkg_tested_yes'  style='width:20px; border:0px; position:relative; left:-40%;'/><span style='position:relative; left:-95%;'>Yes</span>
								</th>
								</tr>								
								</table>
								<span id='packagetested_error' class='error_message'></span>
								</td>
								<td class='comment'> </td>
							</tr>

							<tr>
								<th><label for='readme_link'><a href="http://atrclin2.athtem.eei.ericsson.se/wiki/index.php/EC_tracker_Instruction_Videos" target="_blank"><img src="images/questionmark.jpg"  style="width:16px; height:16px" alt="What's this?"></a> Gask Readme Link <span style='color:red;'>*</span></label></th>								
								<td colspan=2 class="input_td">
									<input type='text' id='readme_link' name='readme_link' maxlength='250' style="width:765px;"/><BR CLEAR=LEFT>
									<span id='readmelink_error' class='error_message'></span>
								</td>
							</tr>
							
							<tr>
								<th><label for='package_tested'>Is this readme reviewed? <span style='color:red;'>*</span></label></th>
								<td class="input_td">
								
								<table>
								<tr>
								<th style='height:24px;'><input type='radio' id='readme_reviewed_no' name='readme_reviewed' value='readme_reviewed_no'  checked='checked'  style='width:20px; border:0px; position:relative; left:0%;'/> <span style='position:relative; left:-60%;'>No</span>
								</th>
								<th style='height:0px;'><input type='radio' id='readme_reviewed_yes' name='readme_reviewed' value='readme_reviewed_yes'  style='width:20px; border:0px; position:relative; left:-40%;'/><span style='position:relative; left:-95%;'>Yes</span>
								</th>
								</tr>
								</table>
								<span id='readmereviewed_error' class='error_message'></span>
								</td>
								<td class='comment'> </td>
							</tr>


							<tr id="tr_0">
							<th> <label for='tr_number'>TR or JIRA Number <span style='color:red;'>*</span></label></th>
							<td colspan=2>
							<table class="TRtable">						
								<td class="TR_td_top">
									<input type='text' id='tr1_number' class="tr_number" name='tr1_number' maxlength='60' /><BR CLEAR=LEFT>
									<span id='tr1number_error' class='error_message'></span>
								</td>

							<th><label for='tr_slogan'>Slogan <span style='color:red;'>*</span></label></th>
								<td class="TR_td">
									<input type='text' id='tr1_slogan' name='tr1_slogan' maxlength='200'/><BR CLEAR=LEFT>
									<span id='tr1slogan_error' class='error_message'></span>
								</td>

							<th><label for='tr_origination'>Origination <span style='color:red;'>*</span></label></th>
								<td class="TR_td">
									<input type='text' id='tr1_origination' name='tr1_origination' maxlength='60'/><BR CLEAR=LEFT>
									<span id='tr1origination_error' class='error_message'></span>
								</td>
							<td class="TR_td"><div class = "addtr_div_place" id = "addtr_div_place"><div class="addtr_button" id="addtr_button" onclick="addTR(this);">Add</div></div></td>
							</table>
							</td>
							</tr>
							
							<tr>
								<th><label for='comment'>Comment<span style='color:red;'>&nbsp&nbsp</span></label></th>
								<td colspan=2 class="input_td">
									<textarea type='text' id='comment' name='comment' maxlength='250'></textarea><BR CLEAR=LEFT>
									<span id='comment_error' class='error_message'></span>
								</td>
							</tr>
							
							<tr>
								<th><label for='signum'>Signum(s) <span style='color:red;'>*</span></label></th>
								<td>
									<input type='text' id='signum' name='signum' maxlength='100' /><BR CLEAR=LEFT>
									<span id='signum_error' class='error_message'></span></td>
								<td class='comment'>e.g. EWANLIZ</td>
							</tr>

							
						</table>
						

						<input type='hidden' name='submit_EC' id='submit_EC' value='true' />
						<p>
							 <button type='submit'>Submit</button>
						</p>
					</form>	
				</div>
			</div>
		</div>	
		<?php include('footer.php'); ?>	
	</div>	
	<script type='text/javascript' src='js/ECSubmit.js'></script>
	<noscript><meta http-equiv='refresh' content='0; url=noscript.php'></noscript>
</body>
</html>

<?php

function submitEC($shipmentName, $releaseName, $packagesName, $requester, $ecType, $euNumber, $pkgTested, $readmeLink, $readmeReviewed, $comment, $signum) {
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	$error_message = "";
	$validate_result = true;
	
	$shipmentName = trim($shipmentName);
	$releaseName = trim($releaseName);
	$requester = trim($requester);
	$ecType = trim($ecType);
	$euNumber = trim($euNumber);
	$pkgTested = trim($pkgTested);
	$readmeLink = trim($readmeLink);
	$readmeReviewed = trim($readmeReviewed);
	$comment = trim($comment);
	$signum = trim($signum);

		
		//chage varible values into data table format.
		if($ecType == "mws_ec"){
		$ecType = "MWS EC";
		$euNumber = "";  //there is no EU number for a MWS EC
		}else if($ecType == "hot_ec"){
		$ecType = "HOT EC";
		}
		
		if($pkgTested == "pkg_tested_no"){
		$pkgTested = "NO";
		}
		else if($pkgTested == "pkg_tested_yes"){
		$pkgTested = "YES";
		}
		
		if($readmeReviewed == "readme_reviewed_no"){
		$readmeReviewed = "NO";
		}
		else if($readmeReviewed == "readme_reviewed_yes"){
		$readmeReviewed = "YES";
		}
		
		//echo("<br>");
		//echo($ecType);
		//echo($pkgTested);
		//echo($readmeReviewed);
		$submit_result = insertECIntoDatabase($shipmentName, $releaseName, $packagesName, $requester, $ecType, $euNumber, $pkgTested, $readmeLink, $readmeReviewed,$comment, $signum);

		//$submit_result = array("success", "cep", "testtt");
		//mysql_close($con);
	    return $submit_result;
}

function insertECIntoDatabase($shipmentName, $releaseName, $packagesName, $requester, $ecType, $euNumber, $pkgTested, $readmeLink, $readmeReviewed, $comment, $signum)
{
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	// TRANSACTION START
	mysql_query("BEGIN");
	
	date_default_timezone_set("Europe/Dublin");
	$submitDate = date("Y-m-d H:i:s");
	$ecId = $packagesName.timetostring($submitDate);
		
	$sqlEcID = mysql_real_escape_string($ecId);
    $sqlShipmentName = mysql_real_escape_string($shipmentName);
	$sqlReleaseName = mysql_real_escape_string($releaseName);
	$sqlPackageName = mysql_real_escape_string(packagesName);
	$sqlRequester = mysql_real_escape_string($requester);
	$sqlEcType = mysql_real_escape_string($ecType);
	$sqlEuNumber = mysql_real_escape_string($euNumber);
	$sqlPkgTested = mysql_real_escape_string($pkgTested);
	$sqlReadmeLink = mysql_real_escape_string($readmeLink);
	$sqlReadmeReviewed = mysql_real_escape_string($readmeReviewed);
	$sqlComment = mysql_real_escape_string($comment);
	$sqlSignum = mysql_real_escape_string($signum);
	//$sqlSubmitDate = mysql_real_escape_string($submitDate);

	
	
	$sql_submit_ec = "INSERT INTO `ectracker`.`ecs` (`EC_ID`, `SHIPMENT_NAME`, `RELEASE_NAME`, `REQUESTER`, `EC_TYPE`, `EU_NUMBER`, `PACKAGE_TESTED`, `README_LINK`, `README_REVIEWED`, `COMMENT`, `SIGNUM`, `DELIVERED_TO_DM`, `DELIVERED_TO_ST`, `DELIVERED_TO_FOA`) 
	VALUES ('$sqlEcID', '$sqlShipmentName', '$sqlReleaseName', '$sqlRequester', '$sqlEcType', '$sqlEuNumber', '$sqlPkgTested', '$sqlReadmeLink', '$sqlReadmeReviewed', '$sqlComment', '$sqlSignum', '$submitDate', NULL, NULL);";
	
	
	//echo($sql_submit_ec);
	
	$result_submit_ec = mysql_query($sql_submit_ec);

	
	$insert_result = array("fail", "Database Unknown Error, please contact ENIQ Delivery Mgmt (LMI).");
	if($result_submit_ec) {
		mysql_query("COMMIT");
		$insert_result = array("success", $sqlPackagesName, $sqlEcID);
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql_query("ROLLBACK");
		$insert_result = array("fail", "Database Failure: InsertComponentToDatabase Transaction Error.");
	}
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
	return $insert_result;
	
}

function timetostring($str){
     $cliptime=explode("-",$str);
     $result="";
     for($i=0;$i<count($cliptime);$i++){
       $result=$result.$cliptime[$i];
     }
  return $result;
 }
 
 
 
 //Insert Packagess
 function submitPackage($packageName, $packageGaskLink, $ecId) {
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	$error_message = "";
	$validate_result = true;
	
	$packageName = trim($packageName);
	$packageGaskLink = trim($packageGaskLink);
	$ecId = trim($ecId);
	
	$submit_result = insertPackageIntoDatabase($packageName, $packageGaskLink, $ecId);

		//$submit_result = array("success", "cep", "testtt");
		//mysql_close($con);
	    return $submit_result;
}


function insertPackageIntoDatabase($packageName, $packageGaskLink, $ecId)
{
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	// TRANSACTION START
	mysql_query("BEGIN");
	
	
	
	$sqlpackageName = mysql_real_escape_string($packageName);
	$sqlpackageGaskLink = mysql_real_escape_string($packageGaskLink);
	$sqlEcID = mysql_real_escape_string($ecId);
	
	//INSERT INTO `ectracker`.`packages` (`PACKAGE_NAME`, `PACKAGE_GASKLINK`, `EC_ID`) VALUES ('HQ12333', 'SSSSS', 'OOOOOO', 'SDDFG  SDF 09123849');		
	$sql_submit_package = "INSERT INTO `ectracker`.`packages` (`PACKAGE_NAME`, `PACKAGE_GASKLINK`, `EC_ID`) 
	VALUES ('$sqlpackageName', '$sqlpackageGaskLink', '$sqlEcID');";
	
	$result_submit_package = mysql_query($sql_submit_package);

	
	$insert_result = array("fail", "Database Unknown Error, please contact CI Team.");
	if($result_submit_package) {
		mysql_query("COMMIT");
		$insert_result = array("success", $sqlpackageName, $sqlEcID);
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql_query("ROLLBACK");
		$insert_result = array("fail", "Database Failure: InsertComponentToDatabase Transaction Error.");
	}
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
	return $insert_result;
	
}

 //Insert TRs
 function submitTR($trNumber, $trSlogan, $trOrigination, $ecId) {
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	$error_message = "";
	$validate_result = true;
	
	$trNumber = trim($trNumber);
	$trSlogan = trim($trSlogan);
	$trOrigination = trim($trOrigination);
	$ecId = trim($ecId);
	
		$submit_result = insertTRIntoDatabase($trNumber, $trSlogan, $trOrigination, $ecId);

		//$submit_result = array("success", "cep", "testtt");
		//mysql_close($con);
	    return $submit_result;
}


function insertTRIntoDatabase($trNumber, $trSlogan, $trOrigination, $ecId)
{
	$con = connect();
	if (!$con) {
		trigger_error(mysql_error($con), E_USER_ERROR);
		die('Could not connect: ' . mysql_error($con));
	}
	
	// TRANSACTION START
	mysql_query("BEGIN");
	
	
	
	$sqlTrNumber = mysql_real_escape_string($trNumber);
	$sqlTrSlogan = mysql_real_escape_string($trSlogan);
	$sqlTrOrigination = mysql_real_escape_string($trOrigination);
	$sqlEcID = mysql_real_escape_string($ecId);
	
	//INSERT INTO `ectracker`.`trs` (`TR_NUMBER`, `SLOGAN`, `ORIGINATION`, `EC_ID`) VALUES ('HQ12333', 'SSSSS', 'OOOOOO', 'SDDFG  SDF 09123849');		
	$sql_submit_tr = "INSERT INTO `ectracker`.`trs` (`TR_NUMBER`, `SLOGAN`, `ORIGINATION`, `EC_ID`) 
	VALUES ('$sqlTrNumber', '$sqlTrSlogan', '$sqlTrOrigination', '$sqlEcID');";
	
	
	$result_submit_tr = mysql_query($sql_submit_tr);

	
	$insert_result = array("fail", "Database Unknown Error, please contact IMF Team.");
	if($result_submit_tr) {
		mysql_query("COMMIT");
		$insert_result = array("success", $sqlTrNumber, $sqlEcID);
	} else {
		trigger_error(mysql_error($con), E_USER_ERROR);
		mysql_query("ROLLBACK");
		$insert_result = array("fail", "Database Failure: InsertComponentToDatabase Transaction Error.");
	}
	mysql_query("END");
	// TRANSACTION END 
	mysql_close($con);
	return $insert_result;
	
}

?>
