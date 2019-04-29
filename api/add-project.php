<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/functions.php';
$db = new Functions();

$output = "";
if(!isset($_POST['projectName'])){
    $output .= "error/no-project-name";
}else{
    $project_name = filter_var($_POST['projectName'], FILTER_SANITIZE_STRING);

    $project_path = "../files/$project_name";
    $user_id = $_SESSION['unique_id'];

    // check if project already exists
    if ($db->checkProjectExisted($user_id, $project_name)) {
        $output .= "error/project-name-used";
    }else{
        // add project to database
        $Project_added = $db->addProject($user_id, $project_name, $project_path);

        if ($Project_added !== false) {
            // project stored successfully
            mkdir($project_path, 0777, true);
            $output .= "success/project-added";
        } else{
            // project failed to store
            $output .= "error/unknown";
        }
    }
}

echo $output;