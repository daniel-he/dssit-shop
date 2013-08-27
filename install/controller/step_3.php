<?php
class ControllerStep3 extends Controller {
	private $error = array();
	
	public function index() {		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('install');
			
			$this->model_install->mysql($this->request->post);
			
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n\n";
			
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n\n";
									
			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART. 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n\n";
		
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_host']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_user']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['db_password']) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_name']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n\n";

			$output .= '// CAS' . "\n";
			$output .= 'define(\'CAS_SERVER_CA_CERT_PATH\', \'' . DIR_OPENCART . 'cas.pem\');' . "\n";
			$output .= 'define(\'CAS_HOST\', \'' . addslashes($this->request->post['cas_host']) . '\');' . "\n";
			$output .= 'define(\'CAS_CONTEXT\', \'' . addslashes($this->request->post['cas_context']) . '\');' . "\n";
			$output .= 'define(\'CAS_PORT\', ' . addslashes($this->request->post['cas_port']) . ');' . "\n\n";

			$output .= '// LDAP' . "\n";
			$output .= 'define(\'LDAP_HOST\', \'' . addslashes($this->request->post['ldap_host']) . '\');' . "\n";
			$output .= 'define(\'LDAP_SEARCH_BASE\', \'' . addslashes($this->request->post['ldap_search_base']) . '\');' . "\n";
			$output .= '?>';				
		
			$file = fopen(DIR_OPENCART . 'config.php', 'w');
		
			fwrite($file, $output);

			fclose($file);
	 
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";
			
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTPS_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";
			
			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'admin/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'admin/view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', \'' . DIR_OPENCART . 'catalog/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_host']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_user']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['db_password']) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_name']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n\n";

			$output .= '// CAS' . "\n";
			$output .= 'define(\'CAS_SERVER_CA_CERT_PATH\', \'' . DIR_OPENCART . 'cas.pem\');' . "\n";
			$output .= 'define(\'CAS_HOST\', \'' . addslashes($this->request->post['cas_host']) . '\');' . "\n";
			$output .= 'define(\'CAS_CONTEXT\', \'' . addslashes($this->request->post['cas_context']) . '\');' . "\n";
			$output .= 'define(\'CAS_PORT\', ' . addslashes($this->request->post['cas_port']) . ');' . "\n\n";

			$output .= '// LDAP' . "\n";
			$output .= 'define(\'LDAP_HOST\', \'' . addslashes($this->request->post['ldap_host']) . '\');' . "\n";
			$output .= 'define(\'LDAP_SEARCH_BASE\', \'' . addslashes($this->request->post['ldap_search_base']) . '\');' . "\n";
			$output .= '?>';				
				
			$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');
		
			fwrite($file, $output);

			fclose($file);
			
			$this->redirect($this->url->link('step_4'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['db_host'])) {
			$this->data['error_db_host'] = $this->error['db_host'];
		} else {
			$this->data['error_db_host'] = '';
		}
		
		if (isset($this->error['db_user'])) {
			$this->data['error_db_user'] = $this->error['db_user'];
		} else {
			$this->data['error_db_user'] = '';
		}
		
		if (isset($this->error['db_name'])) {
			$this->data['error_db_name'] = $this->error['db_name'];
		} else {
			$this->data['error_db_name'] = '';
		}
		
		if (isset($this->error['db_prefix'])) {
			$this->data['error_db_prefix'] = $this->error['db_prefix'];
		} else {
			$this->data['error_db_prefix'] = '';
		}
		
		if (isset($this->error['cas_host'])) {
			$this->data['error_cas_host'] = $this->error['cas_host'];
		} else {
			$this->data['error_cas_host'] = '';
		}
		
		if (isset($this->error['cas_port'])) {
			$this->data['error_cas_port'] = $this->error['cas_port'];
		} else {
			$this->data['error_cas_port'] = '';
		}
		
		if (isset($this->error['ldap_host'])) {
			$this->data['error_ldap_host'] = $this->error['ldap_host'];
		} else {
			$this->data['error_ldap_host'] = '';
		}
		
		if (isset($this->error['ldap_search_base'])) {
			$this->data['error_ldap_search_base'] = $this->error['ldap_search_base'];
		} else {
			$this->data['error_ldap_search_base'] = '';
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}		
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}	
		
		$this->data['action'] = $this->url->link('step_3');
		
		if (isset($this->request->post['db_driver'])) {
			$this->data['db_driver'] = $this->request->post['db_driver'];
		} else {
			$this->data['db_driver'] = 'mysql';
		}
		
		if (isset($this->request->post['db_host'])) {
			$this->data['db_host'] = $this->request->post['db_host'];
		} else {
			$this->data['db_host'] = 'localhost';
		}
		
		if (isset($this->request->post['db_user'])) {
			$this->data['db_user'] = html_entity_decode($this->request->post['db_user']);
		} else {
			$this->data['db_user'] = '';
		}
		
		if (isset($this->request->post['db_password'])) {
			$this->data['db_password'] = html_entity_decode($this->request->post['db_password']);
		} else {
			$this->data['db_password'] = '';
		}

		if (isset($this->request->post['db_name'])) {
			$this->data['db_name'] = html_entity_decode($this->request->post['db_name']);
		} else {
			$this->data['db_name'] = '';
		}
		
		if (isset($this->request->post['db_prefix'])) {
			$this->data['db_prefix'] = html_entity_decode($this->request->post['db_prefix']);
		} else {
			$this->data['db_prefix'] = 'oc_';
		}
		
		if (isset($this->request->post['cas_host'])) {
			$this->data['cas_host'] = html_entity_decode($this->request->post['cas_host']);
		} else {
			$this->data['cas_host'] = '';
		}
		
		if (isset($this->request->post['cas_context'])) {
			$this->data['cas_context'] = html_entity_decode($this->request->post['cas_context']);
		} else {
			$this->data['cas_context'] = '';
		}
		
		if (isset($this->request->post['cas_port'])) {
			$this->data['cas_port'] = html_entity_decode($this->request->post['cas_port']);
		} else {
			$this->data['cas_port'] = '';
		}
		
		if (isset($this->request->post['ldap_host'])) {
			$this->data['ldap_host'] = html_entity_decode($this->request->post['ldap_host']);
		} else {
			$this->data['ldap_host'] = '';
		}
		
		if (isset($this->request->post['ldap_search_base'])) {
			$this->data['ldap_search_base'] = html_entity_decode($this->request->post['ldap_search_base']);
		} else {
			$this->data['ldap_search_base'] = '';
		}
		
		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = 'admin';
		}

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		$this->data['back'] = $this->url->link('step_2');
		
		$this->template = 'step_3.tpl';
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->response->setOutput($this->render());		
	}
	
	private function validate() {
		if (!$this->request->post['db_host']) {
			$this->error['db_host'] = 'Host required!';
		}

		if (!$this->request->post['db_user']) {
			$this->error['db_user'] = 'User required!';
		}

		if (!$this->request->post['db_name']) {
			$this->error['db_name'] = 'Database Name required!';
		}
		
		if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
			$this->error['db_prefix'] = 'DB Prefix can only contain lowercase characters in the a-z range, 0-9 and "_"!';
		}
				
		if ($this->request->post['db_driver'] == 'mysql') {
			if (!$connection = @mysql_connect($this->request->post['db_host'], $this->request->post['db_user'], $this->request->post['db_password'])) {
				$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!';
			} else {
				if (!@mysql_select_db($this->request->post['db_name'], $connection)) {
					$this->error['warning'] = 'Error: Database does not exist!';
				}
				
				mysql_close($connection);
			}
		}

		if(!$this->request->post['cas_host']) {
		  $this->error['cas_host'] = "Error: CAS host required!";
		}

		if(!$this->request->post['cas_port']) {
		  $this->error['cas_port'] = "Error: please specify the port to use with CAS.";
		}

		if(!$this->request->post['ldap_host']) {
		  $this->error['ldap_host'] = "Error: LDAP host required!";
		} else {
		  $cntn = ldap_connect($this->request->post['ldap_host']);
		  if(!$cntn) {
		    $this->error['ldap_host'] = "Error: Could not connect to specified LDAP server.";
		  } else {
		    ldap_close($cntn);
		  }
		}

		if(!$this->request->post['ldap_search_base']) {
		  $this->error['ldap_search_base'] = "Error: Please specify the LDAP search base!";
		}
				
		if (!$this->request->post['username']) {
			$this->error['username'] = 'Username required!';
		}

		if (!$this->request->post['password']) {
			$this->error['password'] = 'Password required!';
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = 'Invalid E-Mail!';
		}
		
		if (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'config.php!';
		}
	
		if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'admin/config.php!';
		}	
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}		
	}
}
?>
