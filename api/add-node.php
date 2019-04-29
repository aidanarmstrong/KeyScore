<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/functions.php';
$db = new Functions();

$output = "";
if(isset($_POST['parentId']) && isset($_POST['nodeType'])){

    $parentId = filter_var($_POST['parentId'], FILTER_SANITIZE_STRING);
    $nodeType = filter_var($_POST['nodeType'], FILTER_SANITIZE_STRING);
    $userId = $_SESSION['unique_id'];

    if($nodeType == "default"){
        $nodeType = "fa fa-folder";
        $nodeName = "New Folder";
    }else if($nodeType == "file"){
        $nodeType = "fa fa-file";
        $nodeName = "New File";
    }

    $project_data = $db->checkProjectData($userId, $parentId);

    if($project_data != false) {

        $project_path = $_SESSION['project_path']."/".$nodeName;

        $node_added = $db->addNode($userId, $parentId, $nodeName, $project_path, $nodeType);

        if ($node_added !== false) {
            // project stored successfully

            if($nodeType == "fa fa-folder"){
                mkdir($project_path, 0777, true);
                $output .= "node/folder-created";
            }else if($nodeType == "fa fa-file"){
                $file = fopen($project_path, "w");
                fwrite($file, 'This file was created at KeyScore by '.$_SESSION['email'].' at '.date("Y/m/d").'.');
                fclose($file);
                $output .= "node/file-created";
            }
        } else{
            // project failed to store
            $output .= "error/unknown";
        }
    }else{
        $output .= "false"; 
    }

}else{
    $output .= "no fields set"; 
}
echo $output;