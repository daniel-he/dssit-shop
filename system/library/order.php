<?php
class Order { 
  private $data;
  private $billingInfo;
  private $deliveryInfo;
  private $deliveryMethod;

  public function setBillingInfo($key, $value) {
    $this->billingInfo[$key] = $value;
  }

  public function setDeliveryInfo($key, $value) {
    $this->deliveryInfo[$key] = $value;
  }

  public function setDeliveryMethod($value) {
    $this->deliveryMethod = $value;
  }

  public function setData($key, $value) {
    //Miscellaneous Data
    $this->data[$key] = $value;
  }

  public function getBillingInfo($key) {
    return $this->getBillingInfo[$key];
  }

  public function getDeliveryInfo($key) {
    return $this->deliveryInfo[$key];
  }

  public function getDeliveryMethod() {
    return $this->deliveryMethod;
  }

  public function getData($key) {
    return $this->data[$key];
  }
}
?>