<?php
	session_start(); 
	if (isset($_SESSION['user_signum'])) {
		unset($_SESSION['user_signum']);
	}
	if (isset($_SESSION['user_role'])) {
		unset($_SESSION['user_role']);
	}
	session_destroy();
	header( 'Location: index.php' ); 
?>