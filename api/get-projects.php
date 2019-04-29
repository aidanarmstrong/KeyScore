<?php 
// session_start();

// include "include/db-connect.php";


// $output = "";

// if(isset($_SESSION['unique_id'])){

//     $user_id = $_SESSION['unique_id'];
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // get rows
//     $qry = "SELECT * FROM projects WHERE user_id = '$user_id' ORDER BY id ASC";

//     $result = mysqli_query($conn, $qry);

//     if ($result != '' ){
//         while ($row =  mysqli_fetch_array($result)){
//             $output .= '
//                 <li id="'.$row['project_id'].'" class="projects-file">
//                     <span class="folder"><i class="fa fa-folder"></i></span>
//                     <label class="filename">'.$row['project_name'].'</label>
//                 </li>
//             ';
//         }
//     }
// }

// echo $output; 

session_start();

include "include/db-connect.php";

$user_id = $_SESSION['unique_id'];

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * from projects where user_id = '$user_id'";
// $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
//     //iterate on results row and create new index array of data
//     while( $row = mysqli_fetch_assoc($res) ) { 
//         $data[] = $row;
//     }
//     $itemsByReference = array();
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
     $sub_data["id"] = $row["project_id"];
     $sub_data["name"] = $row["project_path"];
     $sub_data["text"] = $row["project_name"];
     $sub_data["parent_id"] = $row["parent_id"];
     $sub_data["icon"] = $row["icon"];
     $data[] = $sub_data;
}
$itemsByReference = array();
// Build array of item references:
foreach($data as $key => &$item) {
   $itemsByReference[$item['id']] = &$item;
   // Children array:
   $itemsByReference[$item['id']]['children'] = array();
   // Empty data class (so that json_encode adds "data: {}" ) 
   $itemsByReference[$item['id']]['data'] = new StdClass();
}
// Set items as children of the relevant parent item.
foreach($data as $key => &$item){
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])){
      $itemsByReference [$item['parent_id']]['children'][] = &$item;
      $itemsByReference [$item['icon']]['children'][] = &$item;
   }
}
      
// Remove items that were added to parents elsewhere:
foreach($data as $key => &$item) {
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
      unset($data[$key]);
}
// iterate to make the index in a sequential order
$record = array();
foreach($data as $rec){
    $record[] = $rec;
}
// Encode:
echo json_encode($record);
?>