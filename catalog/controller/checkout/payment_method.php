<?php  
class ControllerCheckoutPaymentMethod extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');

		$this->data['entry_dafis'] = $this->language->get('entry_dafis');
		
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_comments'] = $this->language->get('text_comments');

		$this->data['button_continue'] = $this->language->get('button_continue');
	  
		if (isset($this->session->data['billingInfo']['account'])) {
			$this->data['account'] = $this->session->data['billingInfo']['account'];
		} else {
			$this->data['account'] = '';
		}
		
		if (isset($this->session->data['billingInfo']['comment'])) {
			$this->data['comment'] = $this->session->data['billingInfo']['comment'];
		} else {
			$this->data['comment'] = '';
		}
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment_method.tpl';
		} else {
			$this->template = 'default/template/checkout/payment_method.tpl';
		}
		
		$this->response->setOutput($this->render());
  	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		// Validate if payment address has been set.
		if ($this->customer->isLogged() && isset($this->session->data['billingInfo'])) {
			$payment_address = $this->session->data['billingInfo'];		
		}	
				
		if (empty($payment_address)) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}		
		
		// Validate cart has products and has stock.			
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');
				
				break;
			}				
		}
											
		if (!$json) {
			if ($this->request->post['account'] == '') {
				$json['error']['warning'] = $this->language->get('error_payment');
			}
			
			if (!$json) {
				$this->session->data['billingInfo']['account'] = $this->request->post['account'];
			  
				$this->session->data['billingInfo']['comment'] = strip_tags($this->request->post['comment']);
			}				
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
?>