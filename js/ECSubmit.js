var trNum = 1;
var tmp = 0;

var pkgNum = 1;
var tmp2 = 0;

function checkShipment(objs){
		if(objs.indexOf('EU') != -1){
		$('#mws_ec').hide();
		$('#mws_radio_error').html('A MWS EC cannot </br> be submitted to a EU');
		//alert($('#mws_radio_error').innerHTML);
		}else{
		$('#mws_ec').show();
		$('#mws_radio_error').html('');
		}
	}

$(document).ready(function(){


	//hide the add tr button div place
	//$(".addtr_div_place").hide();

	function hideFmForm() {
		if (!$('#fm_form').is(':hidden')) {
			$('#fm_form').hide();
		}
		$('#fm_alarm_code').val('');
		$('#fac_error').text('');
	}
	
	function showFmForm() {
		if ($('#fm_form').is(':hidden')) {
			$('#fm_form').show();
			$('#fm_alarm_code').focus();
		}
	}
	
	function checkECType() {
		if ($('#mws_ec').attr('checked') == true) {
			hideFmForm();
		} else if ($('#hot_ec').attr('checked') == true) {
			showFmForm();
		} else {
			$('#mws_ec').attr('checked', true);
			hideFmForm();
		}
	}
		
	checkECType();
	$('#mws_ec').click(hideFmForm);
	$('#hot_ec').click(showFmForm);	
	
});


	function addPKG(pkg_id) {
		var pkgStr = '';
		var latestPkgID = pkgNum + 1;
		
		pkgStr+="<tr id ='pkg" + pkgNum +"_name_tr'>";
		pkgStr+="<th><label for='package_name'>Package Name (" + latestPkgID + ") <span style='color:red;'>*</span></label></th>";
		pkgStr+="<td>";
		pkgStr+="<input type='text' id='package" + latestPkgID + "_name' name='package" + latestPkgID + "_name' maxlength='150' /><BR CLEAR=LEFT>";
		pkgStr+="<span id='package" + latestPkgID + "name_error' class='error_message'></span>";	
		pkgStr+="</td><td><table><tr>";		
		pkgStr+="<td class='PKG_td'><div class = 'addpkg_div_place' id = 'addpkg_div_place'><div class='removepkg_button' id='removepkg_button_";
		pkgStr+=pkgNum;
		pkgStr+="' onclick='removePKG(this);'>Remove</div></div></td>";
		pkgStr+="<td class='comment'></td>";
		pkgStr+="</tr></table></td></tr>";	
		
		pkgStr+="<tr id='pkg" + pkgNum + "_gasklink_tr'>";
		pkgStr+="<th><label for='package_gasklink'>Package Gask Link(" + latestPkgID + ") <span style='color:red;'>*</span></label></th>";
		pkgStr+="<td colspan=2 class='input_td'>";
		pkgStr+="<input type='text' id='package" + latestPkgID + "_gasklink' name='package" + latestPkgID + "_gasklink' maxlength='250' style='width:765px;'/><BR CLEAR=LEFT>";
		pkgStr+="<span id='package"+latestPkgID+"gasklink_error' class='error_message'></span>";	
		pkgStr+="</td></tr>";
		
			
		
		
		
		
		var tmpNum = pkgNum - 1;
		//alert(pkgStr);
		//alert(tmpNum);
		$(pkgStr).insertAfter("#pkg" + tmpNum + "_gasklink_tr");
		//alert("#pkg" + tmpNum + "_gasklink_tr");
		//alert(pkg_id);
		//$(pkgStr).insertAfter("#"+pkg_id);
		
		
		//Hide last PKG's removebotton
		if(pkgNum > 1){
		$("#removepkg_button_" + tmpNum).hide();
		}
		pkgNum++;
	}
	
	function removePKG(pkgID){
	//alert("werwer");
	var tmp = --pkgNum;
	$("#pkg" + tmp + "_name_tr").remove();
	$("#pkg" + tmp + "_gasklink_tr").remove();
	tmpNum = pkgNum - 1;
	if(pkgNum > 1){
		//$("#removetr_button_" + tmpNum).css({"cursor":"printer","color":"#fff","background":"rgb(51, 102, 153)", "onclick":"none"});
		$("#removepkg_button_" + tmpNum).show();
		}
	}
	
	//$('#removetr_button').click(removeTR);
	//$('#addtr_button').click(addTR);
	
	

	function addTR(tr_id) {
		var trStr = '';
		var latestTrID = trNum + 1;
		trStr+="<tr id='tr_" + trNum + "'>";
		trStr+="<th> <label for='tr_number'>TR Number (" + latestTrID + ") <span style='color:red;'>*</span></label></th>";
		trStr+="<td colspan=2>";
		trStr+="<table class='TRtable'>";
		trStr+="<td class='TR_td_top'>";
		trStr+="<input type='text' id='tr" + latestTrID + "_number' class='tr_number' name='tr" + latestTrID + "_number' maxlength='60'";
		trStr+="/><BR CLEAR=LEFT>";
		trStr+="<span id='tr" + latestTrID + "number_error' class='error_message'></span>";
		
		trStr+="</td>";
		trStr+="<th><label for='tr_number'>Slogan <span style='color:red;'>*</span></label></th>";
		trStr+="<td class='TR_td'>";
		trStr+="<input type='text' id='tr" + latestTrID + "_slogan' class='tr_slogan' name='tr" + latestTrID + "_slogan' maxlength='60'";	
		trStr+="/><BR CLEAR=LEFT>";
		trStr+="<span id='tr" + latestTrID + "slogan_error' class='error_message'></span>";
		trStr+="</td>";
		
		trStr+="<th><label for='tr_number'>Origination  <span style='color:red;'>*</span></label></th>";
		trStr+="<td class='TR_td'>";
		trStr+="<input type='text' id='tr" + latestTrID + "_origination' name='tr" + latestTrID + "_origination' maxlength='200'";
		trStr+="/><BR CLEAR=LEFT>";
		trStr+="<span id='tr" + latestTrID + "origination_error' class='error_message'></span>";
		trStr+="</td>";
		trStr+="<td class='TR_td'><div class = 'addtr_div_place' id = 'addtr_div_place'><div class='removetr_button' id='removetr_button_";
		trStr+=trNum;
		trStr+="' onclick='removeTR(this);'>Remove</div></div></td>";
		trStr+="</table>";
		trStr+="</td>";
		trStr+="</tr>";
		
		//$("#tr_1").insertAfter(trStr);
		var tmpNum = trNum - 1;
		$(trStr).insertAfter("#tr_" + tmpNum);
		
		
		//Hide last TR's removebotton
		if(trNum > 1){
		$("#removetr_button_" + tmpNum).hide();
		//myBottonHide("#removetr_button_" + tmpNum);
		}
		//alert(trStr + trNum);
		trNum++;
	}
	
function myBottonHide(b_id){
$(b_id).css({"cursor":"default","color":"fff","background":"#fff", "onclick":"addTR(this)"});
}

	

function removeTR(trID){
	//alert("werwer");
	$("#tr_" + --trNum).remove();
	tmpNum = trNum - 1;
	if(trNum > 1){
		//$("#removetr_button_" + tmpNum).css({"cursor":"printer","color":"#fff","background":"rgb(51, 102, 153)", "onclick":"none"});
		$("#removetr_button_" + tmpNum).show();
		}
	}
	
	//$('#removetr_button').click(removeTR);
	//$('#addtr_button').click(addTR);


function validateECForm() {

	var flag = 0;
	
	if	($.trim(document.getElementById('shipment_name').value) == "") {
		document.getElementById('shipmentname_error').innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById('shipmentname_error').innerHTML = "";
	}
		
	
	if	($.trim(document.getElementById('requester').value) == "") {
		document.getElementById('requester_error').innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById('requester_error').innerHTML = "";
	}
	
	if	($.trim(document.getElementById('readme_link').value) == ""){
		document.getElementById('readmelink_error').innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else if(($.trim(document.getElementById('readme_link').value).indexOf("gask2web")) == -1){
		document.getElementById('readmelink_error').innerHTML = "The readme link must be a gask link";
	}else {
		document.getElementById('readmelink_error').innerHTML = "";
	}
	
	if	($.trim(document.getElementById('signum').value) == "") {
		document.getElementById('signum_error').innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById('signum_error').innerHTML = "";
	}
	
	
	
	for(var i=1; i<=trNum; i++){
	if	($.trim(document.getElementById("tr" + i +"_number").value) == "") {
		document.getElementById("tr" + i +"number_error").innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById("tr" + i +"number_error").innerHTML = "";
	}
	}
	
	for(var i=1; i<=trNum; i++){
	if	($.trim(document.getElementById("tr" + i +"_slogan").value) == "") {
		document.getElementById("tr" + i +"slogan_error").innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById("tr" + i +"slogan_error").innerHTML = "";
	}
	}
	
	for(var i=1; i<=trNum; i++){
	if	($.trim(document.getElementById("tr" + i +"_origination").value) == "") {
		document.getElementById("tr" + i +"origination_error").innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById("tr" + i +"origination_error").innerHTML = "";
	}
	}
	
	for(var i=1; i<=pkgNum; i++){
	if	($.trim(document.getElementById("package" + i +"_name").value) == "") {
		document.getElementById("package" + i +"name_error").innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else {
		document.getElementById("package" + i +"name_error").innerHTML = "";
	}
	}
	
	for(var i=1; i<=pkgNum; i++){
	if	($.trim(document.getElementById("package" + i +"_gasklink").value) == "") {
		document.getElementById("package" + i +"gasklink_error").innerHTML = "Required field cannot be left blank";
		flag = 1;
	} else if (($.trim(document.getElementById("package" + i +"_gasklink").value).indexOf("http://gask2web")) == -1){
		document.getElementById('package1gasklink_error').innerHTML = "The link must begin with: http://gask2web";
		flag = 1;
	} else {
		document.getElementById("package" + i +"gasklink_error").innerHTML = "";
	}
	}
	
	if (document.getElementById('pkg_tested_no').checked == true) {
			document.getElementById('packagetested_error').innerHTML = "Please test package before submitting";
			flag = 1;
	} else {
		document.getElementById('packagetested_error').innerHTML = "";
	}
	
	if (document.getElementById('readme_reviewed_no').checked == true) {
			document.getElementById('readmereviewed_error').innerHTML = "Please review readme before submitting";
			flag = 1;
	} else {
		document.getElementById('readmereviewed_error').innerHTML = "";
	}
	
	//if (document.getElementById('shipment_name').value.indexOf("EU") != 0) {
	//		document.getElementById('mws_ec').hide();
	//		flag = 1;
	//} else {
	//	document.getElementById('readmereviewed_error').innerHTML = "";
	//}
	
	
	//if (document.getElementById('hot_ec').checked == true) {
	//	if	($.trim(document.getElementById('eu_number').value) == "") {
	//		document.getElementById('eunumber_error').innerHTML = "Required field cannot be left blank<br/>";
	//		flag = 1;
	//	} 
	//	//else if (!isFmAlarmCode(document.getElementById('eu_number').value)) {
	//	//	document.getElementById('eunumber_error').innerHTML = "FM Alarm Code should be an Integer from 0 to 65535";
	//	//	flag = 1;
	//	//} 
	//	else {
	//		document.getElementById('eunumber_error').innerHTML = "";
	//	}
	//} else {
	//	document.getElementById('eunumber_error').innerHTML = "";
	//}
	
	if (flag == 1) {
		document.getElementById('message').innerHTML = "";
		return false;
	} else {
		return true;
	}
}

function isNumber( str ){
	var regu = /^([0-9]|[1-9][0-9]{0,4})$/;
	if(regu.test(str)) {
		if (str >=0 && str < 65536) {
			return true;
		}
	}
	return false;
}