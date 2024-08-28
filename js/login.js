function validateLoginForm() {
	
	if	($.trim(document.getElementById('signum').value) == "") {
		document.getElementById('login_error').innerHTML = "Please enter your signum.";
		document.getElementById('signum').focus();
		return false;
	} else if	($.trim(document.getElementById('password').value) == "") {
		document.getElementById('login_error').innerHTML = "Please enter the password.";
		document.getElementById('password').focus();
		return false;
	} else {
		document.getElementById('login_error').innerHTML = "";
		return true;
	}
	
}