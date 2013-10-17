<?php
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
?>