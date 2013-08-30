<?php
class ApiSysaidObject {
  public $CIId;
  public $catalogNumber;
  public $custDate1;
  public $custDate2;
  public $manufacturer;
  public $model;
  public $name;
  public $notes;
  public $supplierID;
  public $version;
}

class ApiServiceRequest extends ApiSysaidObject {
  
}

class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
   */
  private function login() {
    $sysaidApi = new SoapClient(SYSAID_WSDL);
    $params = array("accountId" = SYSAID_ACCOUNT, "userName" => "AD3\\user", "password" => "secret_pass");
    $sessId = get_object_vars($sysaidApi->login($params));
    $sessId = $sessId["return"];

    return $sessId;
  }

  private function logout() {
    $sysaidApi = new SoapClient(SYSAID_WSDL);

    $params = array("sessionId" => $sessId);
    try {
      $sysaidApi->logout($params);
      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function getTicket($ticket_no) {
    $sysaidApi = new SoapClient(SYSAID_WSDL);
    $sessId = $this->login();

    $params = array("sessionId" => $sessId, "apiSysObj" => new ApiSysaidObject(), "id" = $ticket_no);

    $this->logout();

    if ($ticket_no) {
      return $ticket_no;
    } else {
      return FALSE;
    }
  }	
}
?>