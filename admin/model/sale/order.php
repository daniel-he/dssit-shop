<?php
class ModelSaleOrder extends Model {
	public function addOrder($data) {
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('setting/setting');
		
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
      	$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . 
"', store_id = '" . (int)$data['store_id'] . 
"', store_name = '" . $this->db->escape($store_name) . 
"', store_url = '" . $this->db->escape($store_url) . 
"', customer_id = '" . (int)$data['customer_id'] . 
"', customer_group_id = '" . (int)$data['customer_group_id'] . 
"', firstname = '" . $this->db->escape($data['firstname']) . 
"', lastname = '" . $this->db->escape($data['lastname']) . 
"', email = '" . $this->db->escape($data['email']) . 
"', telephone = '" . $this->db->escape($data['telephone']) . 
"', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . 
"', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . 
"', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . 
"', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . 
"', payment_info = '" . $this->db->escape($data['payment_info']) . 
"', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . 
"', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . 
"', shipping_address = '" . $this->db->escape($data['shipping_address']) . 
"', shipping_method = '" . $this->db->escape($data['shipping_method']) . 
"', comment = '" . $this->db->escape($data['comment']) . 
"', order_status_id = '" . (int)$data['order_status_id'] . 
"', affiliate_id  = '" . (int)$data['affiliate_id'] . 
"', language_id = '" . (int)$this->config->get('config_language_id') . 
"', date_added = NOW(), date_modified = NOW()");
      	
      	$order_id = $this->db->getLastId();
		
      	if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . 
"', product_id = '" . (int)$order_product['product_id'] . 
"', name = '" . $this->db->escape($order_product['name']) . 
"', model = '" . $this->db->escape($order_product['model']) . 
"', quantity = '" . (int)$order_product['quantity'] . 
"', price = '" . (float)$order_product['price'] . 
"', total = '" . (float)$order_product['total'] . 
"', tax = '" . (float)$order_product['tax'] . "'");
			
				$order_product_id = $this->db->getLastId();
				
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . 
"', order_product_id = '" . (int)$order_product_id . 
"', product_option_id = '" . (int)$order_option['product_option_id'] . 
"', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . 
"', name = '" . $this->db->escape($order_option['name']) . 
"', `value` = '" . $this->db->escape($order_option['value']) . 
"', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
				
				if (isset($order_product['order_download'])) {
					foreach ($order_product['order_download'] as $order_download) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . 
"', order_product_id = '" . (int)$order_product_id . 
"', name = '" . $this->db->escape($order_download['name']) . 
"', filename = '" . $this->db->escape($order_download['filename']) . 
"', mask = '" . $this->db->escape($order_download['mask']) . 
"', remaining = '" . (int)$order_download['remaining'] . "'");
					}
				}
			}
		}
		
		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . 
"', voucher_id = '" . (int)$order_voucher['voucher_id'] . 
"', description = '" . $this->db->escape($order_voucher['description']) . 
"', code = '" . $this->db->escape($order_voucher['code']) . 
"', from_name = '" . $this->db->escape($order_voucher['from_name']) . 
"', from_email = '" . $this->db->escape($order_voucher['from_email']) . 
"', to_name = '" . $this->db->escape($order_voucher['to_name']) . 
"', to_email = '" . $this->db->escape($order_voucher['to_email']) . 
"', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . 
"', message = '" . $this->db->escape($order_voucher['message']) . 
"', amount = '" . (float)$order_voucher['amount'] . "'");
			
      			$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}

		// Get the total
		$total = 0;
		
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . 
"', code = '" . $this->db->escape($order_total['code']) . 
"', title = '" . $this->db->escape($order_total['title']) . 
"', text = '" . $this->db->escape($order_total['text']) . 
"', `value` = '" . (float)$order_total['value'] . 
"', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		
		if (!empty($this->request->post['affiliate_id'])) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);
			
			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id']; 
			}
		}
		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . 
"', affiliate_id = '" . (int)$affiliate_id . "' WHERE order_id = '" . (int)$order_id . "'"); 	
	}
	
	public function editOrder($order_id, $data) {
		// Restock products before subtracting the stock later on
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

      	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($data['firstname']) . 
"', lastname = '" . $this->db->escape($data['lastname']) . 
"', email = '" . $this->db->escape($data['email']) . 
"', telephone = '" . $this->db->escape($data['telephone']) . 
"', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . 
"', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . 
"', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . 
"', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . 
"', payment_info = '" . $this->db->escape($data['payment_info']) . 
"', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . 
"', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . 
"', shipping_address = '" . $this->db->escape($data['shipping_address']) . 
"', shipping_method = '" . $this->db->escape($data['shipping_method']) . 
"', comment = '" . $this->db->escape($data['comment']) . 
"', order_status_id = '" . (int)$data['order_status_id'] . 
"', affiliate_id  = '" . (int)$data['affiliate_id'] . 
"', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
		
      	if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_product_id = '" . (int)$order_product['order_product_id'] . 
"', order_id = '" . (int)$order_id . 
"', product_id = '" . (int)$order_product['product_id'] . 
"', name = '" . $this->db->escape($order_product['name']) . 
"', model = '" . $this->db->escape($order_product['model']) . 
"', quantity = '" . (int)$order_product['quantity'] . 
"', price = '" . (float)$order_product['price'] . 
"', total = '" . (float)$order_product['total'] . 
"', tax = '" . (float)$order_product['tax'] . "'");
			
				$order_product_id = $this->db->getLastId();

				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
	
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_option_id = '" . (int)$order_option['order_option_id'] . 
"', order_id = '" . (int)$order_id . 
"', order_product_id = '" . (int)$order_product_id . 
"', product_option_id = '" . (int)$order_option['product_option_id'] . 
"', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . 
"', name = '" . $this->db->escape($order_option['name']) . 
"', `value` = '" . $this->db->escape($order_option['value']) . 
"', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
				
				if (isset($order_product['order_download'])) {
					foreach ($order_product['order_download'] as $order_download) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_download_id = '" . (int)$order_download['order_download_id'] . 
"', order_id = '" . (int)$order_id . 
"', order_product_id = '" . (int)$order_product_id . 
"', name = '" . $this->db->escape($order_download['name']) . 
"', filename = '" . $this->db->escape($order_download['filename']) . 
"', mask = '" . $this->db->escape($order_download['mask']) . 
"', remaining = '" . (int)$order_download['remaining'] . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 
		
		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . 
"', order_id = '" . (int)$order_id . 
"', voucher_id = '" . (int)$order_voucher['voucher_id'] . 
"', description = '" . $this->db->escape($order_voucher['description']) . 
"', code = '" . $this->db->escape($order_voucher['code']) . 
"', from_name = '" . $this->db->escape($order_voucher['from_name']) . 
"', from_email = '" . $this->db->escape($order_voucher['from_email']) . 
"', to_name = '" . $this->db->escape($order_voucher['to_name']) . 
"', to_email = '" . $this->db->escape($order_voucher['to_email']) . 
"', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . 
"', message = '" . $this->db->escape($order_voucher['message']) . 
"', amount = '" . (float)$order_voucher['amount'] . "'");
			
				$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}
		
		// Get the total
		$total = 0;
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_total_id = '" . (int)$order_total['order_total_id'] . 
"', order_id = '" . (int)$order_id . 
"', code = '" . $this->db->escape($order_total['code']) . 
"', title = '" . $this->db->escape($order_total['title']) . 
"', text = '" . $this->db->escape($order_total['text']) . 
"', `value` = '" . (float)$order_total['value'] . 
"', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
		
		// Affiliate
		$affiliate_id = 0;
		
		if (!empty($this->request->post['affiliate_id'])) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);
			
			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id']; 
			}
		}
				 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . 
"', affiliate_id = '" . (int)$affiliate_id . "' WHERE order_id = '" . (int)$order_id . "'"); 
	}
	
	public function deleteOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_fraud WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM " . DB_PREFIX . "order_recurring `or`, " . DB_PREFIX . "order_recurring_transaction ort WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				
				
			$this->load->model('sale/affiliate');
				
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
				
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'sysaid_no'               => $order_query->row['sysaid_no'],
				'opp_no'                  => $order_query->row['opp_no'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_info'          => $order_query->row['payment_info'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_address'      => $order_query->row['shipping_address'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],					
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return false;
		}
	}
	
	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
	
	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderDownloads($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
	
	public function getOrderVoucherByVoucherId($voucher_id) {
      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}
				
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}
	
	public function getTotalSales() {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "'");

		return $query->row['total'];
	}

	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->getOrder($order_id);

		// Send out any gift voucher mails
		if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
			$this->load->model('sale/voucher');

			$results = $this->getOrderVouchers($order_id);
			
			foreach ($results as $result) {
				$this->model_sale_voucher->sendVoucher($result['voucher_id']);
			}
		}
	}
		
	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 10;
		}	
				
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalOrderHistories($order_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}	
		
	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}	
	
	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();
		
		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . $product_id . "'";
		}
		
		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");
	
		return $query->rows;
	}
	
	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();
		
		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . $product_id . "'";
		}
				
		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . $start . "," . $end);	
		
		return $query->row['total'];
	}	
}
?>