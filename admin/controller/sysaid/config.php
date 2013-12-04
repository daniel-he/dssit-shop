<?php
class ControllerSysaidConfig extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('sysaid/config');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		  //sort posted data into building and service center
		  //based on keys
		  $all_buildings = array();
		  $all_service_centers = array();
		  foreach ($this->request->post as $key => $val) {
		    if (preg_match('/^service_center[0-9]+/', $key)) {
		      $all_service_centers[] = $val;
		    } elseif (preg_match('/^building[0-9]+/', $key)) {
		      $all_buildings[] = $val;
		    }
		  }

		  $this->model_setting_setting->editSetting('sysaid_config', array("sysaid_config_service_centers" => $all_service_centers, "sysaid_config_buildings" => $all_buildings));	
					
			$this->data['success'] = $this->language->get('text_success');
		} else {
		  $this->data['success'] = false;
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_building'] = $this->language->get('entry_building');
		$this->data['entry_service_center'] = $this->language->get('entry_service_center');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_service_center'] = $this->language->get('button_add_service_center');
		$this->data['button_add_building'] = $this->language->get('button_add_building');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sysaid/config', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('sysaid/config', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['service_centers'] = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		  $this->data['service_centers'] = $all_service_centers;
		} elseif ($this->config->get('sysaid_config_service_centers')) { 
			$this->data['service_centers'] = $this->config->get('sysaid_config_service_centers');
		}
		
		$this->data['buildings'] = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		  $this->data['buildings'] = $all_buildings;
		} elseif ($this->config->get('sysaid_config_buildings')) { 
			$this->data['buildings'] = $this->config->get('sysaid_config_buildings');
		}
		
		$this->template = 'sysaid/config.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sysaid/config')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>