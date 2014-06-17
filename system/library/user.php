<?php
class User {
	private $user_id;
	private $username;
  	private $permission = array();

  	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->load = $registry->get('load');
		$this->user_id = $registry->get('user_id');
		$this->username = $this->user_id;
		$this->permission = array();

		$this->load->model("roles/roles");
		$this->model_roles_roles = $registry->get('model_roles_roles');
		$tokens = $this->model_roles_roles->getRoles($this->user_id);

		//permissions are the greatest amount allowed by
		//all groups user is in. i.e. if user is in group A
		//and in group B and group B can modify page C, bug group
		//A cannot, user will be able to modify page C.
		
		//check to make sure $token is valid
		if (empty($tokens)) {
			// if list is empty
			print("ERROR: Unable to connect to Roles Management\n");
		}
		else {
			foreach ($tokens as $token) {
				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE token = '" . $token . "'");
			
				if ($user_group_query->rows) {		  
					$permissions = unserialize($user_group_query->row['permission']);
			  
					if (is_array($permissions)) {
					  foreach ($permissions as $key => $value) {
						if (!array_key_exists($key, $this->permission)) {
						  $this->permission[$key] = $value;
						} else {
						  foreach ($this->permission[$key] as $_i => $page) {
						$this->permission[$key][] = $page;
						  } 
						}
					  }
					}
				}
			}
		}
  	}

  	public function logout() {
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->username = '';
		
		session_destroy();
  	}

  	public function hasPermission($key, $value) {
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
	} else {
	  		return false;
	}
  	}
  
  	public function isLogged() {
 	  return (isset($this->user_id) && $this->user_id != '');
  	}
  
  	public function getId() {
    	  return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}	
}
?>