<?php
session_start();

require_once 'include/functions.php';
$db = new Functions();

// json response array

$output = "";

if (isset($_POST['email']) && isset($_POST['password'])) {
	// receiving the post params
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // get the user by email and password
    $user = $db->getUserByEmailAndPassword($email, $password);

    if ($user != false) {
        //user is found
        $_SESSION['loggedin'] = true;
        $_SESSION["user"] = $user;

        //start session() and redirect to user dashboard.
        $output .= "success/user-auth";
        // header("Location: ../dashboard.php");
    }
    else {
        // user is not found with the credentials
        $output .= "error/no-auth";
    }

    echo $output;
}

ini_set('display_errors', 1);