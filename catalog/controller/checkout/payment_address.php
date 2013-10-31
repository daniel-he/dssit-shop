<?php 
class ControllerCheckoutPaymentAddress extends Controller {
  public function index() {
    $this->language->load('checkout/checkout');
		
    $this->data['text_address_new'] = $this->language->get('text_address_new');
    $this->data['text_select'] = $this->language->get('text_select');
    $this->data['text_none'] = $this->language->get('text_none');

    $this->data['entry_firstname'] = $this->language->get('entry_firstname');
    $this->data['entry_lastname'] = $this->language->get('entry_lastname');
    $this->data['entry_email'] = $this->language->get('entry_email');
    $this->data['entry_telephone'] = $this->language->get('entry_telephone');
    $this->data['entry_department'] = $this->language->get('entry_department');
    $this->data['entry_company_id'] = $this->language->get('entry_company_id');
    $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');			
    $this->data['entry_address_1'] = $this->language->get('entry_address_1');
    $this->data['entry_building'] = $this->language->get('entry_building');

    $this->billingInfo = (isset($this->session->data['billingInfo']) ? $this->session->data['billingInfo'] : array());

    //Default Values
    if (isset($this->billingInfo['First Name'])) {
      $this->data['customer_firstname'] = $this->billingInfo['First Name'];
    } else {
      $this->data['customer_firstname'] = $this->customer->getFirstName();
    }
    if (isset($this->billingInfo['Last Name'])) {
      $this->data['customer_lastname'] = $this->billingInfo['Last Name'];
    } else {
      $this->data['customer_lastname'] = $this->customer->getLastName();
    }
    if (isset($this->billingInfo['Email'])) {
      $this->data['customer_email'] = $this->billingInfo['Email'];
    } else {
      $this->data['customer_email'] = $this->customer->getEmail();
    }
    if (isset($this->billingInfo['Phone Number'])) {
      $this->data['customer_telephone'] = $this->billingInfo['Phone Number'];
    } else {
      $this->data['customer_telephone'] = $this->customer->getTelephone();
    }
    if (isset($this->billingInfo['Room Number'])) {
      $this->data['address_1'] = $this->billingInfo['Room Number'];
    } else {
      $this->data['address_1'] = '';
    }
    if (isset($this->billingInfo['Building'])) {
      $this->data['building'] = $this->billingInfo['Building'];
    } else {
      $this->data['building'] = '';
    }

    $this->data['button_continue'] = $this->language->get('button_continue');
    
    $this->load->model('account/customer_group');
    
    $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
    
    if ($customer_group_info) {
      $this->data['company_id_display'] = $customer_group_info['company_id_display'];
    } else {
      $this->data['company_id_display'] = '';
    }
		
    if ($customer_group_info) {
      $this->data['company_id_required'] = FALSE;//$customer_group_info['company_id_required'];
    } else {
      $this->data['company_id_required'] = '';
    }
				
    if ($customer_group_info) {
      $this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
    } else {
      $this->data['tax_id_display'] = '';
    }
    
    if ($customer_group_info) {
      $this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
    } else {
      $this->data['tax_id_required'] = '';
    }
		
    $this->data['buildings'] = $this->config->get('sysaid_config_buildings');
	
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_address.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/checkout/payment_address.tpl';
    } else {
      $this->template = 'default/template/checkout/payment_address.tpl';
    }
	
    $this->response->setOutput($this->render());			
  }
	
  public function validate() {
    $this->language->load('checkout/checkout');
    
    $json = array();
    
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
      if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
	$json['error']['firstname'] = $this->language->get('error_firstname');
      }
		
      if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
	$json['error']['lastname'] = $this->language->get('error_lastname');
      }
		
      if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
	$json['error']['email'] = $this->language->get('error_email');
      }
			
			// Customer Group
      $this->load->model('account/customer_group');
				
      $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
					
      if ($customer_group_info) {	
	// Company ID
	if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && empty($this->request->post['company_id'])) {
	  $json['error']['company_id'] = $this->language->get('error_company_id');
	}
					
	// Tax ID
	if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && empty($this->request->post['tax_id'])) {
	  $json['error']['tax_id'] = $this->language->get('error_tax_id');
	}						
      }
					
      if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
	$json['error']['address_1'] = $this->language->get('error_address_1');
      }
					 
      // VAT Validation
      $this->load->helper('vat');
			
      if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
	$json['error']['tax_id'] = $this->language->get('error_vat');
      }						
    }

    // Store values in the order class
    $this->session->data['billingInfo']['First Name'] = $this->request->post['firstname'];
    $this->session->data['billingInfo']['Last Name'] = $this->request->post['lastname'];
    $this->session->data['billingInfo']['Email'] = $this->request->post['email'];
    $this->session->data['billingInfo']['Phone Number'] = $this->request->post['telephone'];
    $this->session->data['billingInfo']['Room Number'] = $this->request->post['address_1'];
    $this->session->data['billingInfo']['Building'] = $this->request->post['building'];
		
    $this->response->setOutput(json_encode($json));
  }
}
?>