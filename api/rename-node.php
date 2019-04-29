<?php 

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/functions.php';
$db = new Functions();

$output = "";

if(isset($_POST['nodeName']) && isset($_POST['nodeId']) && isset($_POST['nodeParent'])){

    $nodeName = filter_var($_POST['nodeName'], FILTER_SANITIZE_STRING);
    $nodeId = filter_var($_POST['nodeId'], FILTER_SANITIZE_STRING);
    $nodeParent = filter_var($_POST['nodeParent'], FILTER_SANITIZE_STRING);
    $userId = $_SESSION['unique_id'];

    $parentId = $nodeId;
    // check folder has no parent id 
    $project_data = $db->checkProjectData($userId, $parentId);

    if($project_data != false){

        $nodeId = $parentId;
        $parentId = $_SESSION['parent_id']; 

        $project_parent_data = $db->checkParentData($userId, $parentId);
        $path = $_SESSION['project_path'];

        if($project_parent_data != false){
            $parent_name =  $_SESSION['parent_name'];
            $project_path = "../files/".$parent_name."/".$nodeName; 

            if($db->renameNode($userId, $nodeId, $nodeName, $project_path) !== false){
                rename($path, $project_path);
                $output .= $path;
            }else{
                $output .= "error/failed-rename 1";
            }
        }else{
            
            $project_path = "../files/".$nodeName; 

            if($db->renameNode($userId, $nodeId, $nodeName, $project_path) !== false){
                rename($path, $project_path);
                $output .= $path;
            }else{
                $output .= "error/failed-rename 2";
            }
        }
        
    }else{
       $output .= "error/failed-rename 3";
    }

    
}else{
    $output .= "fields not set";
}
echo $output;