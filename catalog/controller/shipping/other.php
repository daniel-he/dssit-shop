<?php 
class ControllerShippingOther extends Controller {
  	public function index() {	
		$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['entry_additional_info'] = $this->language->get('entry_additional_info');
					
		if (isset($this->session->data['shippingInfo']['additional_info'])) {
			$this->data['additional_info'] = $this->session->data['shippingInfo']['additional_info'];
		} else {
		  $this->data['additional_info'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/other.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/shipping/other.tpl';
		} else {
			$this->template = 'default/template/shipping/other.tpl';
		}		
		
		$this->response->setOutput($this->render());
	}
	
	public function validate() {
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
			$this->session->data['shippingInfo']['additional_info'] = trim($this->request->post['additional_info']);
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}
?>