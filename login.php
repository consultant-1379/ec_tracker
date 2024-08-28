<?php
ob_start();
session_start(); 
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
?>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<?php include('header.php'); ?>
	<script language="javascript" type="text/javascript">
        window.onload=function()
        {
           document.getElementById("signum").focus();
        }
    </script>
</head>
<body>
	<?php
		function startsWith($haystack, $needle)
		{
			return !strncmp($haystack, $needle, strlen($needle));
		}
		include('dbConnect.php');
		
		/*function connectToLDAP($signum, $password) {		
			
			$ldapconn = ldap_connect('ecd.ericsson.se') or die("Could not connect");
			
			if ($ldapconn) {			
				ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
				$ldapbind = ldap_bind($ldapconn, 'uid='.$signum.',ou=Users,ou=Internal,o=ericsson', $password);
				if ($ldapbind) {
					ldap_unbind($ldapconn);
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		*/
		
			function connectToLDAP($signum, $password) {		
			
			$ldapconn = ldap_connect('ecd.ericsson.se') or die("Could not connect");
			
			if ($ldapconn) {
				if((startsWith($signum,'e') || startsWith($signum,'E'))){
				ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
				$ldapbind = ldap_bind($ldapconn, 'uid='.$signum.',ou=Users,ou=Internal,o=ericsson', $password);
				if ($ldapbind) {
					ldap_unbind($ldapconn);
					return true;
				} else {
					return false;
				}
				}
				if((startsWith($signum,'x')) || startsWith($signum,'X')){
				ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
				$ldapbind = ldap_bind($ldapconn, 'uid='.$signum.',ou=Partners,ou=External,o=ericsson', $password);
				if ($ldapbind) {
					ldap_unbind($ldapconn);
					return true;
				} else {
					return false;
				}
				}
			} else {
				return false;
			}
		}
		
		
		
		function checkUserRole($signum) {
			$con = connect();
			if (!$con) {
				trigger_error(mysql_error($con), E_USER_ERROR);
				die('Could not connect: ' . mysql_error($con));
			}
			$signum = mysql_real_escape_string($signum);

			$users = mysql_query("SELECT * FROM users WHERE USER_SIGNUM='$signum';");
			if (mysql_num_rows($users) == 1) {
				$user = mysql_fetch_array($users);
				$role = (string) $user['USER_ROLE'];
				echo $role;
			} else {
				$role = "user";
			}

			mysql_close($con);
			return $role;
		}
		
		
		
		if (isset($_POST['log_in']) && isset($_POST['signum']) && isset($_POST['password'])) {
			$signum = $_POST['signum'];
			$password = $_POST['password'];
			if ($signum == '') {
				$error_message = 'Please enter your signum.';
			} elseif ($password == '') {
				$error_message = 'Please enter the password.';
			} else {
				if (connectToLDAP($signum, $password)) {
					$role = checkUserRole($signum);
					$_SESSION['user_signum'] = $signum;
					$_SESSION['user_role'] = $role;
					header( 'Location: http://eniqdmt.lmera.ericsson.se/ectracker/' ); 
				} else {
					$error_message = 'Signum or password is incorrect!';
				}
			}
		}
	?>
	
	
	<div id = 'imfContainer' style='background-color:#F7F7F7;'>
		<div id='stylized' class='loginform' onsubmit='return validateLoginForm()'>
			<div id='loginContainer'>
				<form name='loginForm' action='login.php' method='post'>
					<table>
						<tr><td colspan='2'><h1>Log In</h1></td></tr>
						<tr>
							<th><label for='signum'>Signum</label></th>
							<td>
								<input type='text' id='signum' name='signum' maxlength='60'><BR CLEAR=LEFT>
							</td>
						</tr>
						<tr>
							<th><label for='password'>Password</label></th>
							<td>
								<input type='password' id='password' name='password' maxlength='60'><BR CLEAR=LEFT>
							</td>
						</tr>
						<tr>
							<th></th>
							<td>
								<span id='login_error' class='error_message'><?php if(isset($error_message)) {echo $error_message;}?></span>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<button type='submit' style='float:left; margin-left:0;'>Log In</button>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<a href='index.php' style='float:right;'>Home page</a>
							</td>
						</tr>
					</table>
					<input type='hidden' name='log_in' id='log_in' value='true' />
				</form>
			</div>
		</div>
	</div>
	<script type='text/javascript' src='js/login.js'></script>
	<noscript><meta http-equiv='refresh' content='0; url=noscript.php'></noscript>
</body>
</html>