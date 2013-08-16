<?php
class ModelShippingOfficeDelivery extends Model {
  function getQuote($address) {
    $this->language->load('shipping/office_delivery');
    
    $method_data = array();
    
    if ($status) {
      $quote_data = array();
      
      $quote_data['office_delivery'] = array(
	'code'         => 'office_delivery.office_delivery',
	'title'        => $this->language->get('text_description'),
	'cost'         => $this->config->get('office_delivery_cost'),
	'tax_class_id' => $this->config->get('office_delivery_tax_class_id'),
	'text'         => $this->currency->format(0)
      );

      $method_data = array(
        'code'       => 'office_delivery',
	'title'      => $this->language->get('text_title'),
	'quote'      => $quote_data,
	'sort_order' => $this->config->get('office_delivery_sort_order'),
	'error'      => false
      );
    }
	
    return $method_data;
  }
}
?>