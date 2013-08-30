<?php
class ApiSysaidObject {
}

class ApiServiceRequest extends ApiSysaidObject {
  public $agreement;
  public $archive;
  public $assignCounter;
  public $assignedGroup;
  public $assignedTo;
  public $CIId;
  public $category;
  public $cc;
  public $changeCategory;
  public $closeTime;
  public $closureInformation;
  public $computerID;
  public $currentSupportLevel;
  public $custDate1;
  public $custDate2;
  public $custInt1;
  public $custInt2;
  public $custList1;
  public $custList2;
  public $custNotes;
  public $custText1;
  public $custText2;
  public $customDateFields;
  public $customFields;
  public $description;
  public $dueDate;
  public $emailAccount;
  public $escalation;
  public $followupActualDate;
  public $followupPlannedDate;
  public $followupText;
  public $followupUser;
  public $id;
  public $impact;
  public $insertTime;
  public $location;
  public $maxSupportLevel;
  public $notes;
  public $parentLink;
  public $priority;
  public $projectID;
  public $reopenCounter;
  public $requestUser;
  public $resolution;
  public $responsibleManager;
  public $solution;
  public $source;
  public $srSubType;
  public $srType;
  public $status;
  public $subCategory;
  public $submitUser;
  public $successRating;
  public $taskID;
  public $thirdLevelCategory;
  public $title;
  public $updateTime;
  public $updateUser;
  public $urgency;
  public $userManager;
  public $version;
  public $workaround;

  public function ApiServiceRequest() {
    $this->customDateFields = new stdClass();
    $this->customDateFields->entry = new stdClass();
    $this->customDateFields->entry->key = new stdClass();
    $this->customDateFields->entry->value = new stdClass();
  
    $this->customFields = new stdClass();
    $this->customFields->entry = new stdClass();
    $this->customFields->entry->key = new stdClass();
    $this->customFields->entry->value = new stdClass();
  }
}

class ModelSysaidSysaid extends Model {
  /*
   * Not a model that interacts with the database.
   * Instead it deals with Sysaid.
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

    $sysaidApi = new SoapClient(SYSAID_WSDL, array('classmap' => array('apiServiceRequest' => 'ApiServiceRequest'));

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
}
?>