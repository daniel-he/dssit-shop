<?php 
class ControllerCheckoutConfirm extends Controller { 
  public function index() {
    $redirect = '';
    // This initializes at least the mandatory ticket creation fields.
    $ticket = array(
		    'category' => 'Purchasing',
		    'subCategory' => 'Other',
		    'title' => 'Purchase for ' . $this->customer->getFirstName() . ' ' . $this->customer->getLastName() . ': ',
		    'requestUser' => $this->customer->getUid(),
		    'submitUser' => $this->customer->getUid(),
		    'custList2' => 2,
		    'assignedTo' => 'none',
		    'description' => 'Error: purchasing information not available.',
		    'status' => 1
    );

        // Validate if payment address has been set.
    if ($this->customer->isLogged() && isset($this->session->data['billingInfo'])) {
      $payment_address = $this->session->data['billingInfo'];
    }	
    
    if (empty($payment_address)) {
      $redirect = $this->url->link('checkout/checkout', '', 'SSL');
    }

    $ticket['description'] = 'Account Holder\'s Information:
';
    foreach($payment_address as $key => $value) {
      $ticket['description'] .= '     ';
      $ticket['description'] .= ($key . ': ');
      $ticket['description'] .= ($value . '
');
    }
    $ticket['description'] .= '
';
    
    // Validate if account has been set.	
    if (!isset($this->session->data['billingInfo']['account'])) {
      $redirect = $this->url->link('checkout/checkout', '', 'SSL');
    }

    if ($this->cart->hasShipping()) {
      // Validate if shipping address has been set.
      if ($this->customer->isLogged() && isset($this->session->data['shippingInfo'])) {					
	$shipping_address = $this->session->data['shippingInfo'];		
      }
      
      if (empty($shipping_address)) {								
	$redirect = $this->url->link('checkout/checkout', '', 'SSL');
      }
      
      // Validate if shipping method has been set.	
      if (!isset($this->session->data['shipping_method'])) {
	$redirect = $this->url->link('checkout/checkout', '', 'SSL');
      }

      $ticket['description'] .= 'Delivery Information:
';
      $ticket['description'] .= ('Delivery Method: ' . $this->session->data['shipping_method']['title'] . '
');
      foreach($shipping_address as $key => $value) {
	$ticket['description'] .= '     ';
	$ticket['description'] .= ($key . ': ');
	$ticket['description'] .= ($value . '
');
      }
      $ticket['description'] .= '
';
    } else {
      unset($this->session->data['shipping_method']);
      unset($this->session->data['shipping_methods']);
    }
    
    // Validate cart has products and has stock.	
    if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
      $redirect = $this->url->link('checkout/cart');				
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
	$redirect = $this->url->link('checkout/cart');
	
	break;
      }				
    }

    $maxPriceItem = $this->cart->getMaxPriceItem();
    $ticket['subCategory'] = $maxPriceItem['sysaid_category'];
    $ticket['title'] .= $maxPriceItem['name'];
    
    if (!$redirect) {
      $total_data = array();
      $total = 0;
      $taxes = $this->cart->getTaxes();
      
      $this->load->model('setting/extension');
      
      $sort_order = array(); 
      
      $results = $this->model_setting_extension->getExtensions('total');
      
      foreach ($results as $key => $value) {
	$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
      }
      
      array_multisort($sort_order, SORT_ASC, $results);
      
      foreach ($results as $result) {
	if ($this->config->get($result['code'] . '_status')) {
	  $this->load->model('total/' . $result['code']);
	  
	  $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
	}
      }
      
      $sort_order = array(); 
      
      foreach ($total_data as $key => $value) {
	$sort_order[$key] = $value['sort_order'];
      }
      
      array_multisort($sort_order, SORT_ASC, $total_data);
      
      $this->language->load('checkout/checkout');
      
      $data = array();
      
      $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
      $data['store_id'] = $this->config->get('config_store_id');
      $data['store_name'] = $this->config->get('config_name');
      
      if ($data['store_id']) {
	$data['store_url'] = $this->config->get('config_url');		
      } else {
	$data['store_url'] = HTTP_SERVER;	
      }
      
      if ($this->customer->isLogged()) {
	$data['customer_id'] = $this->customer->getId();
	$data['customer_group_id'] = $this->customer->getCustomerGroupId();
	$data['firstname'] = $this->customer->getFirstName();
	$data['lastname'] = $this->customer->getLastName();
	$data['email'] = $this->customer->getEmail();
	$data['telephone'] = $this->customer->getTelephone();
	$data['fax'] = $this->customer->getFax();
	
	$payment_address = $this->session->data['billingInfo'];
      }
      
      foreach ($payment_address as $key => $val) {
        $data['payment_' . $key] = $val;
      }

      if ($this->cart->hasShipping()) {
	if ($this->customer->isLogged()) {
	  $shipping_address = $this->session->data['shippingInfo'];
	}			
      
	foreach ($shipping_address as $key => $val) {
	  $data['shipping_' . $key] = $val;
	}
      
	if (isset($this->session->data['shipping_method']['title'])) {
	  $data['shipping_method'] = $this->session->data['shipping_method']['title'];
	} else {
	  $data['shipping_method'] = '';
	}
      
	if (isset($this->session->data['shipping_method']['code'])) {
	  $data['shipping_code'] = $this->session->data['shipping_method']['code'];
	} else {
	  $data['shipping_code'] = '';
	}
      }

      $product_data = array();
      
      foreach ($this->cart->getProducts() as $product) {


	$option_data = array();
	
	foreach ($product['option'] as $option) {
	  if ($option['type'] != 'file') {
	    $value = $option['option_value'];	
	  } else {
	    $value = $this->encryption->decrypt($option['option_value']);
	  }	
	  
	  $option_data[] = array(
				 'product_option_id'       => $option['product_option_id'],
				 'product_option_value_id' => $option['product_option_value_id'],
				 'option_id'               => $option['option_id'],
				 'option_value_id'         => $option['option_value_id'],
				 'name'                    => $option['name'],
				 'value'                   => $value,
				 'type'                    => $option['type']
				 );					
	}
	 
	$product_data[] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
				'reward'     => $product['reward']
				'supplier'   => $product['supplier']
				); 

      }


      $supplier_totals = array();
      foreach($product_data as $theprod) {
        //Construct the ticket description.
	$ticket['description'] .= '
';
	$ticket['description'] .= ($theprod['name'] . ' (');
	$ticket['description'] .= ('Quantity: ' . $theprod['quantity']);
	$ticket['description'] .= (' @ $' .$theprod['price'] . ' each.)
');
	$ticket['description'] .= ('Supplier: ' . $theprod['supplier'] . '
')
	$ticket['description'] .= ('Total Price: $' . $theprod['total'] . '
');
	$ticket['description'] .= ('Model: ' . $theprod['model'] . '
');
	foreach($theprod['option'] as $theopt) {
	  $ticket['description'] .= ('     ' . $theopt['name'] . ': ' . $theopt['value'] . '
');
	}

	//Calculate total cost for each supplier.
	if(isset($supplier_totals[$theprod['supplier'])) {
	  $supplier_totals[$theprod['supplier']] += $theprod['total'];
	} else {
	  $supplier_totals[$theprod['supplier']] = $theprod['total'];	  
	}
      }

      //Add Supplier Subtotals to Ticket Description
      $ticket['description'] .= '
Total for each supplier:
';
      foreach($supplier_totals as $supplier => $subtotal) {
        $ticket['description'] .= $supplier . ': ' . $subtotal . '
';
      }

      // Gift Voucher
      $voucher_data = array();
    
      if (!empty($this->session->data['vouchers'])) {
	foreach ($this->session->data['vouchers'] as $voucher) {
	  $voucher_data[] = array(
				  'description'      => $voucher['description'],
				  'code'             => substr(md5(mt_rand()), 0, 10),
				  'to_name'          => $voucher['to_name'],
				  'to_email'         => $voucher['to_email'],
				  'from_name'        => $voucher['from_name'],
				  'from_email'       => $voucher['from_email'],
				  'voucher_theme_id' => $voucher['voucher_theme_id'],
				  'message'          => $voucher['message'],
				  'amount'           => $voucher['amount']
				  );
	}
      }  
    
      $data['products'] = $product_data;
      $data['vouchers'] = $voucher_data;
      $data['totals'] = $total_data;
      $data['comment'] = (isset($this->session->data['shippingInfo']['comment']) ? $this->session->data['shippingInfo']['comment'] : '');
      $data['total'] = $total;
    
      if (isset($this->request->cookie['tracking'])) {
	$this->load->model('affiliate/affiliate');
      
	$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
	$subtotal = $this->cart->getSubTotal();
      
	if ($affiliate_info) {
	  $data['affiliate_id'] = $affiliate_info['affiliate_id']; 
	  $data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
	} else {
	  $data['affiliate_id'] = 0;
	  $data['commission'] = 0;
	}
      } else {
	$data['affiliate_id'] = 0;
	$data['commission'] = 0;
      }
    
      $data['language_id'] = $this->config->get('config_language_id');
      $data['currency_id'] = $this->currency->getId();
      $data['currency_code'] = $this->currency->getCode();
      $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
      $data['ip'] = $this->request->server['REMOTE_ADDR'];
    
      if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
	$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
      } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
	$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
      } else {
	$data['forwarded_ip'] = '';
      }
    
      if (isset($this->request->server['HTTP_USER_AGENT'])) {
	$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
      } else {
	$data['user_agent'] = '';
      }
    
      if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
	$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
      } else {
	$data['accept_language'] = '';
      }
    
      /*$this->load->model('checkout/order');
    
	$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);*/
    
      $this->data['column_name'] = $this->language->get('column_name');
      $this->data['column_model'] = $this->language->get('column_model');
      $this->data['column_quantity'] = $this->language->get('column_quantity');
      $this->data['column_price'] = $this->language->get('column_price');
      $this->data['column_total'] = $this->language->get('column_total');
    
      $this->data['text_recurring_item'] = $this->language->get('text_recurring_item');
      $this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
    
      $this->data['products'] = array();
    
      foreach ($this->cart->getProducts() as $product) {
	$option_data = array();
      
	foreach ($product['option'] as $option) {
	  if ($option['type'] != 'file') {
	    $value = $option['option_value'];
	  } else {
	    $filename = $this->encryption->decrypt($option['option_value']);
	  
	    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
	  }
	
	  $option_data[] = array(
				 'name'  => $option['name'],
				 'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				 );
	}
      
      
	$profile_description = '';
      
	if ($product['recurring']) {
	  $frequencies = array(
			       'day' => $this->language->get('text_day'),
			       'week' => $this->language->get('text_week'),
			       'semi_month' => $this->language->get('text_semi_month'),
			       'month' => $this->language->get('text_month'),
			       'year' => $this->language->get('text_year'),
			       );

	  if ($product['recurring_trial']) {
	    $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
	    $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
	  }
	
	  $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
	
	  if ($product['recurring_duration']) {
	    $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
	  } else {
	    $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
	  }
	}
      
	$this->data['products'][] = array(
					  'key'                 => $product['key'],
					  'product_id'          => $product['product_id'],
					  'name'                => $product['name'],
					  'model'               => $product['model'],
					  'option'              => $option_data,
					  'quantity'            => $product['quantity'],
					  'subtract'            => $product['subtract'],
					  'price'               => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					  'total'               => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					  'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					  'recurring'           => $product['recurring'],
					  'profile_name'        => $product['profile_name'],
					  'profile_description' => $profile_description,
					  );
      }
            
      // Gift Voucher
      $this->data['vouchers'] = array();
    
      if (!empty($this->session->data['vouchers'])) {
	foreach ($this->session->data['vouchers'] as $voucher) {
	  $this->data['vouchers'][] = array(
					    'description' => $voucher['description'],
					    'amount'      => $this->currency->format($voucher['amount'])
					    );
	}
      }  
    
      $this->data['totals'] = $total_data;
    
      //$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
    } else {
      $this->data['redirect'] = $redirect;
    }			
  
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/checkout/confirm.tpl';
    } else {
      $this->template = 'default/template/checkout/confirm.tpl';
    }
  
    $this->load->model('sysaid/sysaid');
    $this->data['ticket_no'] = $this->model_sysaid_sysaid->makeTicket($ticket);

    //Clean Up Our Mess
    $this->cart->clear();
    unset($this->session->data['shippingInfo']);
    unset($this->session->data['billingInfo']);
    unset($this->session->data['shipping_method']);
    unset($this->session->data['payment_method']);

    $this->response->setOutput($this->render());
  }
}
?>