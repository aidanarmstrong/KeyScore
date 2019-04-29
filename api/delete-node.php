<?php 

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/functions.php';
$db = new Functions();

$output = "";

if(isset($_POST['nodeId'])){

    $node_id = $_POST['nodeId'];
    $user_id = $_SESSION['unique_id'];

    

    if($db->deleteNodes($user_id, $node_id)){
        $output .= "success/nodes-deleted";
    }else{
        $output .= "error/nodes-not-deleted";
    }

}else{
    $output .= "error/nodes-not-deleted";
}

echo $output;