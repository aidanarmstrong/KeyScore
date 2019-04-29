<?php
date_default_timezone_set('Europe/Dublin');
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Functions {

	private $conn;
	
	// constructor
	function __construct() {
		require'conn.php';
		// connecting to database
		$db = new __construct();
		$this->conn = $db->connect();
	}
	
	// destructor
	function __destruct() {
		
	}
	
	/**
	 * Storing new user
	 * returns user details
	 */

    public function storeUser($name, $email, $password) {

        $uuid = rand().microtime().$_SERVER['REMOTE_ADDR'];
        $uuid = str_replace(':','',$uuid);
        $uuid = str_replace('.','', $uuid);
        $uuid = substr($uuid, 0, 18);

        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $resetLink = '';

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);

        if($result = $stmt->execute()){
            $stmt->close();

            // check for successful store
            if ($result) {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                return $user;
            } else {
                return false;
            }
        }else{
            echo $stmt->error;
        }

    }
	
	/**
	 * Get user by email and password
	 */
    public function getUserByEmailAndPassword($email, $password) {

        $sql = "SELECT id, unique_id, name, email, encrypted_password, salt, created_at FROM users WHERE email = ?";

        if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("s", $email);

            if($stmt->execute()){
                $stmt->store_result();

                $stmt->num_rows;

                $stmt->bind_result($id, $unique_id, $name, $email, $encrypted_password, $salt, $created_at);
                while ($user = $stmt->fetch()) {
                    $_SESSION['id'] = $id ;
                    $_SESSION['unique_id'] = $unique_id;
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['encrypted_password'] = $encrypted_password;
                    $_SESSION['salt'] = $salt ;
                    $_SESSION['created_at'] = $created_at;


                    // verifying user password
                    $hash = $this->checkhashSSHA($salt, $password);
                    // check for password equality
                    if ($encrypted_password == $hash) {
                        // user authentication details are correct
                        return $user;
                    }
                }
                $stmt->close();
            }

        } else {
            return NULL;
        }
    }
	
	/**
	 * Check user is existed or not
	 */
	public function isUserExisted($email) {
		$stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
		
		$stmt->bind_param("s", $email);
		
		$stmt->execute();
		
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) {
			// user existed
			$stmt->close();
			return true;
		} else {
			// user not existed
			$stmt->close();
			return false;
		}
    }
    
	/**
	 * Encrypting password
	 * @param password
	 * returns salt and encrypted password
	 */
	public function hashSSHA($password) {
		
		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}
	
//	/**
//	 * Decrypting password
//	 * @param salt, password
//	 * returns hash string
//	 */
	public function checkhashSSHA($salt, $password) {
		
		$hash = base64_encode(sha1($password . $salt, true) . $salt);
		
		return $hash;
	}

	public function updatePassword($email, $newPassword){

	    $password = $newPassword;

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();

            $hash = $this->hashSSHA($password);
            $encrypted_password = $hash["encrypted"]; // encrypted password
            $salt = $hash["salt"];

            $stmt = $this->conn->prepare("UPDATE users SET encrypted_password='$encrypted_password', salt='$salt' WHERE email = ? ");
            $stmt->bind_param("s",  $email);

            if($result = $stmt->execute()){
                // check for successful store
                if ($result) {
                    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    return $user;
                } else {
                    return false;
                }
            }

        }else{
            return NULL;
        }


    }

    public function addProject($user_id, $project_name, $project_path){

        $uuid = rand().microtime().$_SERVER['REMOTE_ADDR'];
        $uuid = str_replace(':','',$uuid);
        $uuid = str_replace('.','', $uuid);
        $uuid = substr($uuid, 0, 18);

        $project_id = $uuid;

        $stmt = $this->conn->prepare("INSERT INTO projects(user_id, project_id, project_name, project_path, created_at) VALUES(?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $user_id, $project_id, $project_name, $project_path);

        if($result = $stmt->execute()){
            $stmt->close();

            // check for successful store
            if ($result) {
                $stmt = $this->conn->prepare("SELECT * FROM projects WHERE project_id = ?");
                $stmt->bind_param("s", $project_id);
                $stmt->execute();
                $project_added = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                return $project_added;
            } else {
                return false;
            }
        }else{
            return NULL;
        }

    }

    public function addNode($userId, $parentId, $nodeName, $project_path, $nodeType){

        $uuid = rand().microtime().$_SERVER['REMOTE_ADDR'];
        $uuid = str_replace(':','',$uuid);
        $uuid = str_replace('.','', $uuid);
        $uuid = substr($uuid, 0, 18);

        $project_id = $uuid;

        $stmt = $this->conn->prepare("INSERT INTO projects(user_id, project_id, project_name, project_path, parent_id, icon, created_at) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $userId, $project_id, $nodeName, $project_path, $parentId, $nodeType);

        if($result = $stmt->execute()){
            $stmt->close();

            // check for successful store
            if ($result) {
                $stmt = $this->conn->prepare("SELECT * FROM projects WHERE project_id = ?");
                $stmt->bind_param("s", $project_id);
                $stmt->execute();
                $node_added = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                return $node_added;
            } else {
                return false;
            }
        }else{
            return NULL;
        }
    }

    public function renameNode($userId, $nodeId, $nodeName, $project_path){

        // $project_path = "../files/Hello/";
        $stmt = $this->conn->prepare("UPDATE projects SET project_name = ?, project_path = ? WHERE user_id = ? AND project_id = ?");
		
		$stmt->bind_param("ssss",$nodeName, $project_path, $userId, $nodeId);
		
		$stmt->execute();
		
		$result = $stmt->store_result();
		
		if ($result > 0) {
            // name change
            $stmt->close();
			return true;
		} else {
			// name not changed
			$stmt->close();
			return false;
		}
    }

    public function checkProjectExisted($user_id, $project_name) {
		$stmt = $this->conn->prepare("SELECT user_id, project_name from projects WHERE user_id = ? AND project_name = ?");
		
		$stmt->bind_param("ss", $user_id, $project_name);
		
		$stmt->execute();
		
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) {
            // user existed
            $stmt->store_result();

            $stmt->close();
            
			return true;
		} else {
			// user not existed
			$stmt->close();
			return false;
		}
    }

    public function checkProjectData($userId, $parentId){

        $sql = "SELECT * from projects WHERE user_id = ? AND project_id = ?";

        if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ss", $userId, $parentId);

            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows > 0){

                    $stmt->bind_result($id, $userId, $project_id, $project_name, $project_path, $parent_id, $icon, $created_at);

                    while ($project_data = $stmt->fetch()) {
                        $_SESSION['id'] = $id ;
                        $_SESSION['userId'] = $userId;
                        $_SESSION['project_id'] = $project_id;
                        $_SESSION['project_name'] = $project_name;
                        $_SESSION['project_path'] = $project_path;
                        $_SESSION['parent_id'] = $parent_id ;
                        $_SESSION['$icon'] = $icon;
                        $_SESSION['created_at'] = $created_at;
                        
                        
                        return $project_data;
                    }
                    $stmt->close();
                }else{
                    return false;
                }
            }

        } else {
            return NULL;
        }
    }

    public function checkParentData($userId, $parentId){

        $sql = "SELECT * from projects WHERE user_id = ? AND project_id = ?";

        if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ss", $userId, $parentId);

            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows > 0){

                    $stmt->bind_result($id, $userId, $project_id, $project_name, $project_path, $parent_id, $icon, $created_at);

                    while ($project_data = $stmt->fetch()) {
                        $_SESSION['id'] = $id ;
                        $_SESSION['userId'] = $userId;
                        $_SESSION['parent_id'] = $project_id;
                        $_SESSION['parent_name'] = $project_name;
                        $_SESSION['path'] = $project_path;
                        
                        
                        return $project_data;
                    }
                    $stmt->close();
                }else{
                    return false;
                }
            }

        } else {
            return NULL;
        }
    }

    public function deleteNodes($user_id, $node_id){

        $sql = "DELETE FROM projects WHERE user_id = ? AND project_id = ? OR parent_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $user_id, $node_id, $node_id);
		
		if ($stmt->execute()) {
            // files deleted
            $stmt->close();
            
			return true;
		} else {
			// files not deleted
			$stmt->close();
			return false;
		}
    }

}