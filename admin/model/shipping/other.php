<?php
class ModelShippingOther extends Model {
  function getQuote($address) {
    $this->language->load('shipping/other');
    
    $method_data = array();
    
    $quote_data = array();
    
    $quote_data['other'] = array(
      'code'         => 'other.other',
      'description'  => $this->language->get('text_description'),
      'title'        => $this->language->get('text_title'),
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