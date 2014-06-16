<?php 
class ControllerCheckoutConfirm extends Controller { 
  public function index() {
    $redirect = '';
    $order = array();

    $order['comment'] = "";

    $newline = '
'; //Only way to make sysaid recognize the newline...
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

    $order['customer_id'] = $this->customer->getId();
    $order['customer_group_id'] = $this->customer->getCustomerGroupId();
    $order['firstname'] = $this->customer->getFirstName();
    $order['lastname'] = $this->customer->getLastName();
    $order['email'] = $this->customer->getEmail();
    $order['telephone'] = $this->customer->getTelephone();

        // Validate if payment address has been set.
    if ($this->customer->isLogged() && isset($this->session->data['billingInfo'])) {
      $payment_address = $this->session->data['billingInfo'];
    }	
    
    if (empty($payment_address)) {
      $redirect = $this->url->link('checkout/checkout', '', 'SSL');
    }

    $order['payment_firstname'] = $payment_address['First Name'];
    $order['payment_lastname'] = $payment_address['Last Name'];
    $order['payment_address_1'] = $payment_address['Room Number'];
    $order['payment_address_2'] = $payment_address['Building'];

    $ticket['description'] = 'Account Holder\'s Information:' . $newline;
    foreach($payment_address as $key => $value) {
      $ticket['description'] .= '     ';
      $ticket['description'] .= ($key . ': ');
      $ticket['description'] .= ($value . $newline);
    }
    $ticket['description'] .= $newline;
    
    // Validate if account has been set.	
    if (!isset($this->session->data['billingInfo']['account'])) {
      $redirect = $this->url->link('checkout/checkout', '', 'SSL');
    }

    $order['payment_info'] = $this->session->data['billingInfo']['account'];

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
      
      //Put Delivery Information Into Sysaid Ticket
      $ticket['description'] .= 'Delivery Information:' . $newline;
      $ticket['description'] .= ('Delivery Method: ' . $this->session->data['shipping_method']['title'] . $newline);
      $ship_adr = "";
      foreach($shipping_address as $key => $value) {
	$ship_adr .= '     ';
	$ship_adr .= ($key . ': ');
	$ship_adr .= ($value . $newline);
      }
      $ticket['description'] .= $ship_adr;
      $ticket['description'] .= $newline;
    } else {
      unset($this->session->data['shipping_method']);
      unset($this->session->data['shipping_methods']);
    }

    $order['shipping_firstname'] = (isset($shipping_address['First Name']) ? $shipping_address['First Name'] : $payment_address['First Name']);
    $order['shipping_lastname'] = (isset($shipping_address['Last Name']) ? $shipping_address['Last Name'] : $payment_address['Last Name']);
    $order['shipping_address'] = $ship_adr;
    $order['shipping_method'] = $this->session->data['shipping_method']['title'];
    
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

    //Get the max price item for setting the ticket title and subcategory.
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

      $order['totals'] = $total_data;
      $order['total'] = $total;
      
      $this->language->load('checkout/checkout');
      
      $data = array();
      
      $order['store_id'] = $this->config->get('config_store_id');
      $order['store_name'] = $this->config->get('config_name');
      
      if ($order['store_id']) {
	$order['store_url'] = $this->config->get('config_url');		
      } else {
	$order['store_url'] = HTTP_SERVER;	
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
	 
	$product_data[$product['supplier']][] = array(
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
				'reward'     => $product['reward'],
				'supplier'   => $product['supplier']
				); 

	 $all_products[] = array(
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
				'reward'     => $product['reward'],
				'supplier'   => $product['supplier']
				); 

      }


      foreach(array_keys($product_data) as $thesupp) {
  	$ticket['description'] .= $newline;
	$ticket['description'] .= ('Items from ' . $thesupp);
        foreach($product_data[$thesupp] as $theprod) {
          //Construct the ticket description.
  	  $ticket['description'] .= $newline;
  	  $ticket['description'] .= ($theprod['name'] . ' (');
	  $ticket['description'] .= ('Quantity: ' . $theprod['quantity']);
	  $ticket['description'] .= (' @ $' .$theprod['price'] . ' each plus tax.)' . $newline);
	  $ticket['description'] .= ('Total Price: $' . ($theprod['total'] + $theprod['tax']) . $newline);
	  $ticket['description'] .= ('Model: ' . $theprod['model'] . $newline);
	  foreach($theprod['option'] as $theopt) {
	    $ticket['description'] .= ('     ' . $theopt['name'] . ': ' . $theopt['value'] . $newline);
	  }
        }
      }

      //Add Supplier Subtotals to Ticket Description
      $supplier_totals = array();
      $this->load->model('total/supplier_total');
      $this->model_total_supplier_total->getTotal($supplier_totals);
      $ticket['description'] .= $newline . 'Total for each supplier:' . $newline;
      foreach($supplier_totals as $supplier_total) {
        $ticket['description'] .= $supplier_total['title'] . ': ';
	$ticket['description'] .= $supplier_total['text'] . $newline;
      }
      $ticket['description'] .= $newline;

      //Put total_data into ticket.
      $ticket['description'] .= 'Totals: ' . $newline;
      foreach($total_data as $total) {
        $ticket['description'] .= $total['title'] . ': ';
        $ticket['description'] .= $total['text'] . $newline;
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
      $order['products'] = $all_products;
      $order['vouchers'] = $voucher_data;
      $data['totals'] = $total_data;
      $data['comment'] = (isset($this->session->data['shippingInfo']['comment']) ? $this->session->data['shippingInfo']['comment'] : '');
      $data['total'] = $total;
    
      if (isset($this->request->cookie['tracking'])) {
	$this->load->model('affiliate/affiliate');
      
	$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
	$subtotal = $this->cart->getSubTotal();
      
	if ($affiliate_info) {
	  $order['affiliate_id'] = $affiliate_info['affiliate_id']; 
	} else {
	  $order['affiliate_id'] = 0;
	}
      } else {
	$order['affiliate_id'] = 0;
      }
    
      $order['ip'] = $this->request->server['REMOTE_ADDR'];
    
      if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
	$order['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
      } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
	$order['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
      } else {
	$order['forwarded_ip'] = '';
      }
    
      if (isset($this->request->server['HTTP_USER_AGENT'])) {
	$order['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
      } else {
	$order['user_agent'] = '';
      }
    
      if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
	$order['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
      } else {
	$order['accept_language'] = '';
      }
    
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
    $order['sysaid_no'] = $this->data['ticket_no'];

    $this->load->model('checkout/order');
    
    $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order);

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