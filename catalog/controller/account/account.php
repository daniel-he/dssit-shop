<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
	$this->language->load('account/account');

	$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
	if (isset($this->session->data['success'])) {
    	$this->data['success'] = $this->session->data['success'];
		
	unset($this->session->data['success']);
	} else {
		$this->data['success'] = '';
	}
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
    	$this->data['text_order'] = $this->language->get('text_order');
    	$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_transaction'] = $this->language->get('text_transaction');
		$this->data['text_recurring'] = $this->language->get('text_recurring');

		$this->data['wishlist'] = $this->url->link('account/wishlist');
    	$this->data['order'] = $this->url->link('account/order', '', 'SSL');
    	$this->data['download'] = $this->url->link('account/download', '', 'SSL');
		$this->data['return'] = $this->url->link('account/return', '', 'SSL');
		$this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$this->data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'		
		);
				
		$this->response->setOutput($this->render());
  	}
}
?>