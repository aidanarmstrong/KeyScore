<?php

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != true) {
	$_SESSION['Error'] = "<label class='text-danger error'>Please log in! </label>";
	header("Location: ../login.html");	
}
?>