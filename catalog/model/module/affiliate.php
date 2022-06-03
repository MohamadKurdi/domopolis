<?php
class ModelModuleAffiliate extends Model {
    public function validate($order_id, $order_info, $order_status_id) {
        $affiliatestatus = (int) $this->config->get('affiliate_order_status_id');
        if (
                ((int) $order_info['affiliate_id'] > 0)
                &
                ($order_status_id == $affiliatestatus)
                &
                ($order_id != 0)
        ) {
            $query_affiliate = $this->db->query("SELECT * FROM `affiliate_transaction` WHERE order_id = '" . (int) $order_id . "'");
            $query_affiliate_bool = $query_affiliate->num_rows;
            if (!$query_affiliate_bool) {
                $this->language->load('account/order');
                $this->addTransaction((int) $order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, (float) $order_info['commission'], $order_id);
            }
        }
    }
    public function addRequestPayment($request_payment) {

        $query = $this->db->query("SELECT * FROM `affiliate` WHERE affiliate_id = '" . (int) $this->affiliate->getId() . "'");
        if ($query->num_rows) {
            $this->language->load('mail/affiliate');

            $subject = sprintf($this->language->get('text_subject_request_payment'), $this->db->escape($query->row['firstname']) . ' ' .
                    $this->db->escape($query->row['lastname']));

            $message = sprintf($this->language->get('text_request_payment'), $this->config->get('config_name'), $this->db->escape($query->row['firstname']) . ' ' .
                    $this->db->escape($query->row['lastname']), $this->currency->format($request_payment)
            );

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

            $message = sprintf($this->language->get('text_payment'), $this->db->escape($query->row['firstname']) . ' ' .
                    $this->db->escape($query->row['lastname']), $this->currency->format($request_payment), $this->config->get('affiliate_days')
            );
            $mail->setTo($query->row['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }
    }

    public function ConvertLocalCurrency($currency) {
        return $this->currency->convert($currency, $this->session->data['currency'], $this->config->get('config_currency'));
    }

    public function ConvertSessionCurrency($currency) {
        return $this->currency->convert($currency, $this->config->get('config_currency'), $this->session->data['currency']);
    }

    public function editOrderPayment($data) {
        $this->load->model('affiliate/transaction');
        $balance = $this->model_affiliate_transaction->getBalance();
        $affiliate_info = $this->getAffiliate((int) $this->affiliate->getId());
        $request_payment_history = $affiliate_info['request_payment'];
        $min_balanse = $this->config->get('affiliate_total');

        $request_payment = $this->ConvertLocalCurrency((double) $this->db->escape($data['request_payment']));


        if ($request_payment >= $balance) {
            $request_payment = $balance;
            $this->addRequestPayment($request_payment);
        } elseif ($request_payment < $min_balanse) {
            if ($request_payment_history > 0) {
                $request_payment = $request_payment_history;
            } else {
                $request_payment = 0.00;
            }
        } else {
            $request_payment = $this->db->escape($request_payment);
            $this->addRequestPayment($request_payment);
        }
        $this->db->query("UPDATE `affiliate` SET request_payment = '" . $request_payment . "' WHERE affiliate_id = '" . (int) $this->affiliate->getId() . "'");
    }

    public function getAffiliate($affiliate_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM `affiliate` WHERE affiliate_id = '" . (int) $affiliate_id . "'");
        return $query->row;
    }

    public function addTransaction($affiliate_id, $description = '', $amount = '', $order_id = 0) {
        $affiliate_info = $this->getAffiliate($affiliate_id);
        if ($affiliate_info) {
            $this->db->query("INSERT INTO `affiliate_transaction` SET affiliate_id = '" . (int) $affiliate_id . "', order_id = '" . (float) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");
            if ((int) $this->db->escape($amount) < 0) {
                $query_request_payment = $this->db->query("SELECT request_payment AS total FROM `affiliate` WHERE affiliate_id = '" . (int) $affiliate_id . "'");
                $request_payment_value = $query_request_payment->row['total'] + $amount;
                if ($request_payment_value < 0) {
                    $request_payment_value = 0.00;
                }
                $this->db->query("UPDATE `affiliate` SET request_payment = '" . $request_payment_value . "' WHERE affiliate_id = '" . (int) $affiliate_id . "'");
            }
			$getlevel = $this->config->get('affiliate_level_commission');
			if(($getlevel) & ($affiliate_id!=0)) {
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				$this->load->model('module/statisticsmyaffiliate');
				$levelcount = count($getlevel);
				$text = $this->model_module_statisticsmyaffiliate->getAffiliateParent((int)$affiliate_id, 0, $levelcount);
				$getaffiliates = $this->model_module_statisticsmyaffiliate->getAffiliateCommission($text, $getlevel, $order_info);
				foreach ($getaffiliates as $parentaffiliate) {
					$this->db->query("INSERT INTO `affiliate_transaction` SET affiliate_id = '" . (int)$parentaffiliate['affiliate_id'] . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description). " (" .$affiliate_info['firstname'] ." ". $affiliate_info['lastname'] . ")', amount = '" . (float)$parentaffiliate['total'] . "', date_added = NOW(), affiliate_children = '" . $affiliate_id . "'");
				} 
			}
		}
	}

    public function getProduct($tracking, $category) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image FROM `product` p LEFT JOIN `product_description` pd ON (p.product_id = pd.product_id) LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id) LEFT JOIN `product_to_category` pc ON (p.product_id = pc.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND pc.category_id = '" . $category . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' order by name");
		$affiliate_sumbol = $this->getSumbol();
        $data = array();
        $this->load->model('tool/image');
        foreach ($query->rows as $result) {
            $data[] = array(
                'product_id' => $result['product_id'],
                'name' => $result['name'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                'href' => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $affiliate_sumbol . 'tracking=' . $tracking)
            );
        }
        return $data;
    }

    public function getCategoriesID() {
        $query = $this->db->query("SELECT c.category_id as category_id FROM `category` c LEFT JOIN `category_description` cd ON (c.category_id = cd.category_id) LEFT JOIN `category_to_store` c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1' ORDER BY c.parent_id, c.sort_order, cd.name");
        return $query->rows;
    }

    public function getPathCategories($id) {
        $querypath = $this->db->query("SELECT parent_id FROM `category` WHERE category_id = '" . $id . "'");
        foreach ($querypath->rows as $row) {
            $pathCategories = $id . '_';
            if ((int) $row['parent_id'] != 0) {
                $pathCategories = $this->getPathCategories((int) $row['parent_id']) . $pathCategories;
            }
            return $pathCategories;
        }
        return null;
    }

    public function getCategories($tracking, $id = 0, $type = 'by_parent') {
        $data = array();

        $query = $this->db->query("SELECT c.category_id as category_id, c.parent_id as parent_id, c.image as thumb, cd.name  as name, cd.description  as description FROM `category` c LEFT JOIN `category_description` cd ON (c.category_id = cd.category_id) LEFT JOIN `category_to_store` c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1' AND c.parent_id = '" . $id . "' ORDER BY c.sort_order, cd.name");

        foreach ($query->rows as $row) {
            $path = $row['category_id'];
            if ($id != 0) {
                $path = $this->getPathCategories($id) . $row['category_id'];
            }
            $data['by_parent'][$row['parent_id']][] = $row +
                    array('href' => $this->url->link('product/category', '&path=' . $path . '&tracking=' . $tracking)) +
                    array('products' => $this->getProduct($tracking, $row['category_id']));
        }

        return ((isset($data[$type]) && isset($data[$type][$id])) ? $data[$type][$id] : array());
    }

    public function isTrackingCoupon($Coupon) {

        $query = $this->db->query("SELECT DISTINCT code FROM `affiliate` WHERE coupon = '" . $Coupon . "' or code = '" . $Coupon . "'");
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                setcookie('tracking', $row['code']);
            }
        }
        return $query->num_rows;
    }
	
	 public function getTrackingCoupon($affiliate_id) {
        $query = $this->db->query("SELECT DISTINCT code FROM `coupon` WHERE affiliate_id = '" . $affiliate_id . "'");
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                return $row['code'];
            }
        }
        return null;
    }
	
	public function getHomeUrl() {
		return HTTP_SERVER . '?tracking=' . $this->affiliate->getCode();
	}
	
	public function getSumbol() {
		$affiliate_sumbol = $this->config->get('config_affiliate_sumbol');
		if (!$this->config->get('config_affiliate_sumbol')) {
			return '&';
		} else if ($affiliate_sumbol == 2) {
			return '?';
		}
		return '&';
	}
}
?>