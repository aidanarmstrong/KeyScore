<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/functions.php';
$db = new Functions();

$output = "";

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password2'])) {

    // receiving the post params
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $output .= "error/email-used";
    } else {
        // create a new user
        $user = $db->storeUser($name, $email, $password);
        if ($user) {
            // user stored successfully
            
            $output .= "success/account-created";
        } else {
            // user failed to store
            $output .= "error/unknown";
        }
    }
} else {
	$output .= "error/form-empty";
}

echo $output;