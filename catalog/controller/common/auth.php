<?php  
class ControllerCommonAuth extends Controller {
	public function index() {		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/auth.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/auth.tpl';
		} else {
			$this->template = 'default/template/common/auth.tpl';
		}
										
		$this->response->setOutput($this->render());
	}
}
?>