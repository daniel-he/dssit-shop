<?php
class ModelTotalSupplierTotal extends Model {
	public function getTotal(&$total_data, &$total=NULL, &$taxes=NULL) {
	        //gets an array of the cost of the items from each supplier
		//takes into account coupons. Does NOT take into account vouchers
		//or shipping. The shipping logic for coupons is written, but needs
		//uncommented, and shipping cost needs added here.

		$this->language->load('total/supplier_total');
		
		$supplier_totals = $this->cart->getSupplierSubtotals();

		$this->load->model('total/coupon');
		$discount_totals = $this->model_total_coupon->getSupplierDiscount();
		
		foreach($discount_totals as $supplier => $discount_total) {
		        $supplier_totals[$supplier] = $supplier_totals[$supplier] - $discount_total;
 		}

		foreach($supplier_totals as $supplier => $supplier_total) {
		        $total_data[$supplier] = array( 
			        'code'       => 'supplier_total',
			        'title'      => $this->language->get('text_supplier_total') . $supplier,
			        'text'       => $this->currency->format($supplier_total),
			        'value'      => $supplier_total,
			        'sort_order' => (!is_null($this->config->get('supplier_total_sort_order')) ? $this->config->get('supplier_total_sort_order') : 0)
		);
		}
	}
}
?>