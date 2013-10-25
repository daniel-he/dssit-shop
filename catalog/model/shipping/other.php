<?php
class ModelShippingOther extends Model {
  function getQuote($address) {
    $this->language->load('shipping/other_pickup');
    
    $method_data = array();
    
    $quote_data = array();
    
    $quote_data['service_center_pickup'] = array(
      'code'         => 'other.other',
      'title'        => $this->language->get('text_description'),
      'cost'         => $this->config->get('other_cost'),
      'tax_class_id' => $this->config->get('other_tax_class_id'),
      'text'         => $this->currency->format(0)
    );

    $method_data = array(
      'code'       => 'other',
      'title'      => $this->language->get('text_title'),
      'quote'      => $quote_data,
      'sort_order' => $this->config->get('other_sort_order'),
      'error'      => false
    );
	
    return $method_data;
  }
}
?>