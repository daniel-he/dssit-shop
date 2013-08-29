<?php
class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
   */
	public function makeTicket($data) {
	  $ticket_no = '';

	  $sysaidApi = new SoapClient(SOAP_WSDL);

	  if ($ticket_no) {
	    return $ticket_no;
	  } else {
	    return FALSE;
	  }
	}
	
	public function editCountry($country_id, $data) {
		$this->cache->delete('country');
	}
	
	public function deleteCountry($country_id) {
		$this->cache->delete('country');
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}
		
	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "country";
			

		}	
	}
	
	public function getTotalCountries() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");
		
		return $query->row['total'];
	}	
}
?>