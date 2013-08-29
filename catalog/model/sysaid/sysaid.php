<?php
class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
   */
  public function getTicket($ticket_no) {
    $sysaidApi = new SoapClient(SYSAID_WSDL);
    $params = array("accountId" = SYSAID_ACCOUNT, "userName" => "AD3\\user", "password" => "secret_pass");
    $sysaidSessId = $sysaidApi->login($params);

    $sysaidApi->logout($sysaddSessId);

    if ($ticket_no) {
      return $ticket_no;
    } else {
      return FALSE;
    }
  }	
}
?>