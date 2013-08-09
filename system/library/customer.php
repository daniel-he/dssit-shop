<?php
class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $customer_group_id;
	private $address_id;
	private $uid;
	
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->load = $registry->get('load');
				
		if (phpCAS::isAuthenticated()) { 
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE uid = '" . phpCAS::getUser() . "' AND status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->uid = $customer_query->row['uid'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
							
      			$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
			
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . $this->customer_id . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
				
				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . $this->customer_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->login();
			}
  		} else {
		      $this->login();
		}
	}
		
  	public function login() {
	       //authenticate with CAS
	       phpCAS::forceAuthentication();

	       //ldap connection code
   	       $ldap_server = ldap_connect(LDAP_HOST);
	       ldap_bind($ldap_server);
	       $ldsearch = ldap_search($ldap_server, LDAP_SEARCH_BASE, "uid=" . phpCAS::getUser());
   	       $usr = ldap_get_entries($ldap_server, $ldsearch);
	       
	       $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer where LOWER(uid) = '" . $this->db->escape(utf8_strtolower($usr[0]['uid'][0])) . "' AND status = '1'");

	       $this->load->model('account/customer');
		
		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];	
		    
			if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}

			if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}
								
				$wishlist = unserialize($customer_query->row['wishlist']);
			
				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->data['wishlist'])) {
						$this->session->data['wishlist'][] = $product_id;
					}
				}			
			}
									
			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->uid = $customer_query->row['uid'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id = $customer_query->row['address_id'];

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

			$this->validate();
			
	  		return true;
    	} else {
		$this->firstname = $usr[0]['givenname'][0];
-		$this->lastname = $usr[0]['sn'][0];
-		$this->uid = $usr[0]['uid'][0];
-		$this->email = $usr[0]['mail'][0];
-		$this->telephone = (isset($usr[0]['telephonenumber'][0]) ? $usr[0]['telephonenumber'][0] : "");
		$this->fax = '0';

		$this->load->model_account_customer->addCustomer(array(
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'email' => $this->email,
			'telephone' => $this->telephone,
			'fax' => $this->fax,
			'uid' => $this->uid));
          	
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
      		return true;
    	}
  	}

	public function validate() {
	/* Function keeps customer table in database 
	 * consistent with LDAP server. Called on every login, 
	 * except the one where the user is first registered.
	 */
		$modified = FALSE;
	 
	        //ldap connection code
   	        $ldap_server = ldap_connect(LDAP_HOST);
	        ldap_bind($ldap_server);
	        $ldsearch = ldap_search($ldap_server, LDAP_SEARCH_BASE, "uid=" . phpCAS::getUser());
   	        $usr = ldap_get_entries($ldap_server, $ldsearch);
	       	
		$query_string = "UPDATE" . DB_PREFIX . "customer SET ";
		if ($this->firstname != $usr[0]['givenname'][0]) {
		   $query_string .= "firstname = '" . $usr[0]['givenname'][0] . "', ";
		   $modified = TRUE;
		}
		if ($this->lastname != $usr[0]['sn'][0]) {
		   $query_string .= "lastname = '" . $usr[0]['sn'][0] . "', ";
		   $modified = TRUE;
		}
		/* Doesn't need validated. */
		/*if ($this->uid != $usr[0]['uid'][0]) {
		   $query_string .= "uid = '" . $usr[0]['uid'][0] . "', ";
		   $modified = TRUE;
		}*/
		if ($this->email != $usr[0]['mail'][0]) {
		   $query_string .= "email = '" . $usr[0]['mail'][0] . "', ";
		   $modified = TRUE;
		}
		if ($this->telephone != (isset($usr[0]['telephonenumber'][0]) ? $usr[0]['telephonenumber'][0] : "")) {
		   $query_string .= "telephone = '" . (isset($usr[0]['telephonenumber'][0]) ? $usr[0]['telephonenumber'][0] : "") . ", ";
		   $modified = TRUE;
		}

		//remove trailing comma (and space)
		$query_string = rtrim($query_string, " ,");
		
		$query_string .= " WHERE uid = '" . phpCAS::getUser() . "';";

		if ($modified) {
		   $this->db->query($this->db->escape($query_string));
		}
	}
  	
	public function logout() {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
		
		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->customer_group_id = '';
		$this->address_id = '';
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}

  	public function getId() {
    	return $this->customer_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
  
  	public function getFax() {
		return $this->fax;
  	}
	
  	public function getCustomerGroupId() {
		return $this->customer_group_id;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
	
  	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");
	
		return $query->row['total'];
  	}	
		
  	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");
	
		return $query->row['total'];	
  	}
}
?>