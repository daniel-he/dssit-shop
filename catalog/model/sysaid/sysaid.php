<?php
class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
   * Classes associated herewith are in system/library.
   */
  protected function login() {
    $sysaidApi = new SoapClient(SYSAID_WSDL, array('classmap' => array('apiServiceRequest' => 'ApiServiceRequest')));

    $params = array("accountId" => SYSAID_ACCOUNT, "userName" => "your_user", "password" => "your_pass");

    $sessId = get_object_vars($sysaidApi->login($params));
    $sessId = $sessId["return"];

    return $sessId;
  }

  protected function logout($sessId) {
    $sysaidApi = new SoapClient(SYSAID_WSDL);
	
    $params = array("sessionId" => $sessId);
    $sysaidApi->logout($params);
  }

  public function getTicket($ticket_no) {
    $sessId = $this->login(); 

    $sysaidApi = new SoapClient(SYSAID_WSDL, array('classmap' => array('apiServiceRequest' => 'ApiServiceRequest')));

    $params = array("sessionId" => $sessId, "apiSysObj" => new ApiServiceRequest(), "id" => $ticket_no);
    $ticket = get_object_vars($sysaidApi->loadByStringId($params));
    $ticket = $ticket["return"];

    $this->logout($sessId);

    if ($ticket) {
      return $ticket;
    } else {
      return FALSE;
    }
  }

  public function makeTicket($srFields) {
      /*
       * Takes an associative array with keys being the names of the public
       * variables in ApiServiceRequest and values being the values to set
       * in the newly created ticket. Returns the ticket number.
       */
    $sessId = $this->login(); 

    $sysaidApi = new SoapClient(SYSAID_WSDL, array('classmap' => array('apiServiceRequest' => 'ApiServiceRequest')));

    $params['sessionId'] = $sessId;
    $params['apiSysObj'] = new ApiServiceRequest();

    foreach($srFields as $varname => $value) {
      $params['apiSysObj']->{$varname} = $value;
    }
    var_dump($params['apiSysObj']);
    //$ticket = get_object_vars($sysaidApi->loadByStringId($params));
    //$ticket = $ticket["return"];

    $this->logout($sessId);

    if ($ticket) {
      return $ticket;
    } else {
      return FALSE;
    }
  }	
}
?>