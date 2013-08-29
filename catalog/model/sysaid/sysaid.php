<?php
class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
   */
  public function makeTicket($data) {
    $ticket_no = '';

    $sysaidApi = new SoapClient(SYSAID_WSDL);
    $sysaidSessId = $sysaidApi->login(array(SYSAID_ACCOUNT, "AD3\\japorito", "Abithiw2itb.Boafft.Lbyl!"));

    $sysaidApi->logout($sysaddSessId);

    if ($ticket_no) {
      return $ticket_no;
    } else {
      return FALSE;
    }
  }	
}
?>