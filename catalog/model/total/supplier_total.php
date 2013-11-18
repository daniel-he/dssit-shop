<?php
class ModelTotalSupplierTotal extends Model {
	public function getTotal(&$total_data, &$total=NULL, &$taxes=NULL) {
		$this->language->load('total/supplier_total');
		
		$supplier_totals = array();
		foreach($this->cart->getProducts() as $product) {
		        if(isset($supplier_totals[$product['supplier']])) {
			        $supplier_totals[$product['supplier]] += $product['total'] + $product['tax'];
			} else {
			        $supplier_totals[$product['supplier]] = $product['total'] + $product['tax'];
			}
		}
		
		foreach($supplier_totals as $supplier => $supplier_total) {
		        $total_data[] = array( 
			        'code'       => 'supplier_total',
			        'title'      => $this->language->get('text_supplier_total') . $supplier,
			        'text'       => $this->currency->format($supplier_total),
			        'value'      => $supplier_total,
			        'sort_order' => $this->config->get('supplier_total_sort_order')
		);
		}
	}
}
?>