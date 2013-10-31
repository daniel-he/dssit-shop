<?php 
class ControllerShippingOfficeDelivery extends Controller {
  	public function index() {	
		$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_building'] = $this->language->get('entry_building');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
					
		if (isset($this->session->data['shippingInfo']['First Name'])) {
			$this->data['firstname'] = $this->session->data['shippingInfo']['First Name'];
		} else {
		  $this->data['firstname'] = $this->customer->getFirstName();
		}

		if (isset($this->session->data['shippingInfo']['Last Name'])) {
			$this->data['lastname'] = $this->session->data['shippingInfo']['Last Name'];
		} else {
		  $this->data['lastname'] = $this->customer->getLastName();
		}
		
		if (isset($this->session->data['shippingInfo']['Email'])) {
			$this->data['email'] = $this->session->data['shippingInfo']['Email'];
		} else {
		  $this->data['email'] = $this->customer->getEmail();
		}
		
		if (isset($this->session->data['shippingInfo']['company'])) {
			$this->data['company'] = $this->session->data['shippingInfo']['company'];			
		} else {
			$this->data['company'] = '';
		}
		
		if (isset($this->session->data['shippingInfo']['Room Number'])) {
			$this->data['address_1'] = $this->session->data['shippingInfo']['Room Number'];			
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->session->data['shippingInfo']['Building'])) {
			$this->data['Building'] = $this->session->data['shippingInfo']['Building'];		
		} else {
			$this->data['Building'] = '';
		}
					
		$this->data['buildings'] = $this->config->get('sysaid_config_buildings');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/office_delivery.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/shipping/office_delivery.tpl';
		} else {
			$this->template = 'default/template/shipping/office_delivery.tpl';
		}		
		
		$this->response->setOutput($this->render());
	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');		
		} 
		
		if (!$json) {		
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
	
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
			
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			  $json['error']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['address_1']) < 1) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
			
			$building_info = $this->config->get('sysaid_config_buildings');
	
			if ($this->request->post['building'] == '') {
				$json['error']['building'] = $this->language->get('error_building');
			}
		}
		
		if (!$json) {
			$this->session->data['shippingInfo']['First Name'] = trim($this->request->post['firstname']);
			$this->session->data['shippingInfo']['Last Name'] = trim($this->request->post['lastname']);
			$this->session->data['shippingInfo']['Email'] = trim($this->request->post['email']);
			$this->session->data['shippingInfo']['Room Number'] = $this->request->post['address_1'];
			$this->session->data['shippingInfo']['Building'] = $this->request->post['building'];
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}
?>