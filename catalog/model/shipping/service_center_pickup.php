<?php
class ModelShippingServiceCenterPickup extends Model {
  function getQuote($address) {
    $this->language->load('shipping/service_center_pickup');
    
    $method_data = array();
    
    $quote_data = array();
    
    $quote_data['service_center_pickup'] = array(
      'code'         => 'service_center_pickup.service_center_pickup',
      'title'        => $this->language->get('text_description'),
      'cost'         => $this->config->get('service_center_pickup_cost'),
      'tax_class_id' => $this->config->get('service_center_pickup_tax_class_id'),
      'text'         => $this->currency->format(0)
    );

    $method_data = array(
      'code'       => 'service_center_pickup',
      'title'      => $this->language->get('text_title'),
      'quote'      => $quote_data,
      'sort_order' => $this->config->get('service_center_pickup_sort_order'),
      'error'      => false
    );
	
    return $method_data;
  }
}
?>