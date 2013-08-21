<?php  
class ControllerCheckoutCheckout extends Controller { 
	public function index() {
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->url->link('checkout/cart'));
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
				$this->redirect($this->url->link('checkout/cart'));
			}				
		}

		// Shipping Methods
		$quote_data = array();
    
		$this->load->model('setting/extension');
    
		$results = $this->model_setting_extension->getExtensions('shipping');
    
		foreach ($results as $result) {
		  if ($this->config->get($result['code'] . '_status')) {
		    $this->load->model('shipping/' . $result['code']);
		    
		    $quote = $this->{'model_shipping_' . $result['code']}->getQuote(array()); 
		    
		    if ($quote) {
		      $quote_data[$result['code']] = array( 
			'title'      => $quote['title'],
	                'quote'      => $quote['quote'], 
       	                'sort_order' => $quote['sort_order'],
			'error'      => $quote['error']
		      );
		    }
		  }
		}
    
		$sort_order = array();
    
		foreach ($quote_data as $key => $value) {
		  $sort_order[$key] = $value['sort_order'];
		}
		
		array_multisort($sort_order, SORT_ASC, $quote_data);
    
		$this->data['shipping_methods'] = $quote_data;
	
		$this->language->load('checkout/checkout');
		
		$this->document->setTitle($this->language->get('heading_title')); 
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
					
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_cart'),
			'href'      => $this->url->link('checkout/cart'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
					
	    $this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');		
		$this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
		$this->data['text_modify'] = $this->language->get('text_modify');
		
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = $this->cart->hasShipping();	
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/checkout.tpl';
		} else {
			$this->template = 'default/template/checkout/checkout.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
        
        if (isset($this->request->get['quickconfirm'])) {
            $this->data['quickconfirm'] = $this->request->get['quickconfirm'];
        }
				
		$this->response->setOutput($this->render());
  	}
}
?>