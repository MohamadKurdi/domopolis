<?php
class ControllerCronjobCronjob extends Controller {
	private $_url = false;

	private $setting = array();
	private $tab_review = array('default' => '#tab-review', 'shoppica' => '#product_reviews');

	private $subject = '';
	private $message = '';

	private $products = array();
	private $owner_email = '';
	private $sender = '';
	private $shortcode = array();
	private $coupon = array();
	private $template_name = '';

	const IMAGE_WIDTH  = 120;
	const IMAGE_HEIGHT = 120;

	public function index() {
    	$this->load->model('catalog/cronjob');

		$settings = $this->model_catalog_cronjob->getSetting();

		$orders = array();
		$test = false;

		if (isset($this->request->get['test']) && $this->request->get['test'] == 1 && isset($this->request->get['email'])) {
			$this->request->get['store_id'] = (isset($this->request->get['store_id']) && preg_match('/[0-9]+/i', $this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;

			$result = $this->model_catalog_cronjob->getOrders(null, $this->request->get['store_id']);

			if ($result) {
				$orders = $result;

				$test = true;
			} else {
				$this->response->setOutput('error');
			}
		} else {
			$orders = $this->model_catalog_cronjob->getOrders($settings);	
		}

		if (!$test && $settings) {
			foreach ($settings as $store_id => $setting) {
				if ($setting['review_booster_approve_review']) {
					$this->db->query("UPDATE `review` r LEFT JOIN `product_to_store` p2s ON (r.product_id = p2s.product_id) SET r.status = '1' WHERE r.rating >= '" . (int)$setting['review_booster_review_rating'] . "' AND p2s.store_id = '" . (int)$store_id . "'");
				}
			}
		}

		if ($orders) {
			$this->load->model('tool/image');

			$coupons = $this->model_catalog_cronjob->getCoupons();

			foreach ($orders as $order) {
				if (!isset($settings[$order['store_id']])) {
					continue;
				}

				if (!$order['store_url']) {
					$order['store_url'] = HTTP_SERVER;
				}

				if (substr($order['store_url'], -1) != '/') {
					$order['store_url'] .= '/';
				}

				if (!preg_match('/^http/i', $order['store_url'])) {
					$order['store_url'] = 'http://' . $order['store_url'];
				}

				$this->_url = new Url($order['store_url'], $order['store_url']);

				$this->setSetting($settings, $order['store_id']);

				$results = $this->model_catalog_cronjob->getProducts($order['order_id']);

				$this->resetData();

				if ($results){
					if ($test) {
						$order['email'] = $this->request->get['email'];
					}

					$this->setShortcode($order);
					$this->setTemplate($order['config_template']);

					if ($this->setting['review_booster_discount_status']) {
						if (isset($coupons[$this->setting['review_booster_discount']])) {
							$this->setCoupon($coupons[$this->setting['review_booster_discount']]);
						}
					}

					foreach ($results as $product) {
						$this->setProduct($product); 
					}

					$this->setOwnerEmail($order['owner_email']);
					$this->setSender($order['store_name']);

					$this->generateView();
					$this->send();

					if (!$test) {
						$this->db->query("UPDATE `order` SET review_alert = '1' WHERE order_id = '" . (int)$order['order_id'] . "'");
					}
				}
			}

			$this->response->setOutput('ok');
		}
	}

	private function setSetting($data, $store_id = '') {
		$this->setting = $data[$store_id];
	}

	private function setProduct($product = array()) {
		if (!isset($this->products[$product['product_id']])) {
			$image = false;
			$price = false;

			if ($this->setting['review_booster_product_image']) {
				if ($product['image']) {
					$image = str_replace(' ', '%20', $this->model_tool_image->resize($product['image'], self::IMAGE_WIDTH, self::IMAGE_HEIGHT));
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
				}
			}

			if ($this->setting['review_booster_price_status']) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));

				if ($product['special']) {
					$price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
				}
			}

			$this->products[$product['product_id']] = array(
				'product_id'  => $product['product_id'],
				'name'        => $product['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['review_booster_product_description_limit']) . '...',
				'image'       => $image,
				'price'       => $price,
				'href'        => $this->_url->link('product/product', 'product_id=' . (int)$product['product_id'] . '&secumt=' . (isset($this->tab_review[$this->template_name]) ? base64_encode($this->tab_review[$this->template_name]) : base64_encode($this->tab_review['default'])), 'NONSSL')
			);
		}
	}

	private function setTemplate($name) {
		$this->template_name = $name;
	}

	private function setOwnerEmail($email) {
		$this->email = $email;
	}

	private function setSender($sender) {
		$this->sender = $sender;
	}

	private function setShortcode($data = array()) {
		$this->shortcode = array(
			'client'      => $data['client'],
			'email'       => $data['email'],
			'order_id'    => $data['order_id'],
			'language_id' => $data['language_id'],
			'date_order'  => date($this->language->get('date_format_short'), strtotime($data['date_added']))
		);
	}

	private function setCoupon($coupon = array()) {
		$code = substr(md5(uniqid(time(), true)), 0, 8);
		$coupon['code'] = $code;

		$this->coupon = $coupon;

		$this->db->query("INSERT INTO `coupon_review` SET coupon_id = '" . (int)$this->coupon['coupon_id'] . "', code = '" . $this->db->escape($code) . "'");
	}

	private function generateView() {
		$title = 'Your order {order_id}';
		$view = '{list}';

		if (isset($this->setting['review_booster_description'][$this->shortcode['language_id']]) && $this->setting['review_booster_description'][$this->shortcode['language_id']]) {
			$title = $this->setting['review_booster_title'][$this->shortcode['language_id']];
			$view = $this->setting['review_booster_description'][$this->shortcode['language_id']];
		} elseif ($this->setting['review_booster_title'][(int)$this->config->get('config_language_id')] && $this->setting['review_booster_title'][(int)$this->config->get('config_language_id')]) {
			$title = $this->setting['review_booster_title'][(int)$this->config->get('config_language_id')];
			$view = $this->setting['review_booster_description'][(int)$this->config->get('config_language_id')];
		}

		if ($this->products) {
			if ($this->setting['review_booster_product_layout'] == 'vertical') {
				foreach ($this->products as $product) {
				$str = '<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:100% !important; margin:0; padding:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="100%">
							<tbody>
								<tr>
									<td>
										<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<tr>
													<td colspan="2" height="15"><font size="1">&nbsp;</font></td>
												</tr>
												<tr>
													<td style="text-align: left; vertical-align: top;" valign="top" width="' . (($product['image']) ? (self::IMAGE_WIDTH + 20) : 0) . 'px">';
						$str .= ($product['image']) ? '<table align="left" border="0" cellpadding="0" cellspacing="0" width="' . (self::IMAGE_WIDTH + 20) . 'px">
															<tbody>
																<tr>
																	<td width="20">&nbsp;</td>
																	<td align="left" valign="top" width="' . self::IMAGE_WIDTH . 'px"><a href="' . $product['href'] . '" target="_blank"><img alt="" border="0" height="' . self::IMAGE_HEIGHT . 'px" src="' . $product['image'] . '" style="display:block; border:1px solid #dddddd; outline:none; text-decoration:none;" width="' . self::IMAGE_WIDTH . 'px" /></a></td>
																</tr>
															</tbody>
														</table>' : '';
										   $str .= '</td>
													<td style="text-align: left; vertical-align: top;" valign="top">
														<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
															<tbody>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="left" style="text-align: left; vertical-align: top;" valign="top"><a href="' . $product['href'] . '" style="font-size: ' . $this->setting['review_booster_product_name_size'] . '; color: #' . $this->setting['review_booster_product_name_color'] . '; font-weight: ' . $this->setting['review_booster_product_name_weight'] . '; font-style:' . $this->setting['review_booster_product_name_style'] . '; text-transform:' . $this->setting['review_booster_product_name_transform'] . '; text-decoration:none; text-align: left; font-family: Helvetica, Arial, sans-serif;" target="_blank">' . $product['name'] . '</a></td>
																	<td width="20">&nbsp;</td>
																</tr>';
$str .= ($this->setting['review_booster_product_description_limit'] != 0) ? '<tr>
																	<td colspan="3" height="10"><font size="1">&nbsp;</font></td>
																</tr>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="left" style="font-size: ' . $this->setting['review_booster_product_description_size'] . '; color: #' . $this->setting['review_booster_product_description_color'] . '; font-weight: ' . $this->setting['review_booster_product_description_weight'] . '; font-style:' . $this->setting['review_booster_product_description_style'] . '; text-transform:' . $this->setting['review_booster_product_description_transform'] . '; text-align: left; font-family: Helvetica, Arial, sans-serif; vertical-align: top;" valign="top">' . $product['description'] . '</td>
																	<td width="20">&nbsp;</td>
																</tr>' : '';
								 $str .= ($product['price']) ? '<tr>
																	<td colspan="3" height="15"><font size="1">&nbsp;</font></td>
																</tr>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="right" style="font-size: ' . $this->setting['review_booster_product_price_size'] . '; color: #' . $this->setting['review_booster_product_price_color'] . '; font-weight: ' . $this->setting['review_booster_product_price_weight'] . '; font-style:' . $this->setting['review_booster_product_price_style'] . '; text-align:' . $this->setting['review_booster_product_price_align'] . '; font-family: Helvetica, Arial, sans-serif;">' . $product['price'] . '</td>
																	<td width="20">&nbsp;</td>
																</tr>' : '';
													$str .= '</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td colspan="2" height="15"><font size="1">&nbsp;</font></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>';

					$t[] = $str;
				}
			} else {
				foreach ($this->products as $product) {
					$str = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<tr>
													<td height="15"><font size="1">&nbsp;</font></td>
												</tr>
												<tr>
													<td style="text-align: left; vertical-align: top;" valign="top" width="' . (($product['image']) ? (self::IMAGE_WIDTH + 20) : 0) . 'px">';
						$str .= ($product['image']) ? '<table align="left" border="0" cellpadding="0" cellspacing="0" width="' . (self::IMAGE_WIDTH + 20) . 'px">
															<tbody>
																<tr>
																	<td width="20">&nbsp;</td>
																	<td align="left" valign="top" width="' . self::IMAGE_WIDTH . 'px"><a href="' . $product['href'] . '" target="_blank"><img alt="" border="0" height="' . self::IMAGE_HEIGHT . 'px" src="' . $product['image'] . '" style="display:block; border:1px solid #dddddd; outline:none; text-decoration:none;" width="' . self::IMAGE_WIDTH . 'px" /></a></td>
																</tr>
																<tr>
																	<td height="5"><font size="1">&nbsp;</font></td>
																</tr>
															</tbody>
														</table>' : '';
										   $str .= '</td>
												</tr>
												<tr>
													<td style="text-align: left; vertical-align: top;" valign="top">
														<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
															<tbody>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="left" style="text-align: left; vertical-align: top;" valign="top"><a href="' . $product['href'] . '" style="font-size: ' . $this->setting['review_booster_product_name_size'] . '; color: #' . $this->setting['review_booster_product_name_color'] . '; font-weight: ' . $this->setting['review_booster_product_name_weight'] . '; font-style:' . $this->setting['review_booster_product_name_style'] . '; text-transform:' . $this->setting['review_booster_product_name_transform'] . '; text-decoration:none; text-align: left; font-family: Helvetica, Arial, sans-serif;" target="_blank">' . $product['name'] . '</a></td>
																	<td width="20">&nbsp;</td>
																</tr>';
$str .= ($this->setting['review_booster_product_description_limit'] != 0) ? '<tr>
																	<td colspan="3" height="10"><font size="1">&nbsp;</font></td>
																</tr>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="left" style="font-size: ' . $this->setting['review_booster_product_description_size'] . '; color: #' . $this->setting['review_booster_product_description_color'] . '; font-weight: ' . $this->setting['review_booster_product_description_weight'] . '; font-style:' . $this->setting['review_booster_product_description_style'] . '; text-transform:' . $this->setting['review_booster_product_description_transform'] . '; text-align: left; font-family: Helvetica, Arial, sans-serif; vertical-align: top;" valign="top">' . $product['description'] . '</td>
																	<td width="20">&nbsp;</td>
																</tr>' : '';
								 $str .= ($product['price']) ? '<tr>
																	<td colspan="3" height="15"><font size="1">&nbsp;</font></td>
																</tr>
																<tr>
																	<td width="15">&nbsp;</td>
																	<td align="right" style="font-size: ' . $this->setting['review_booster_product_price_size'] . '; color: #' . $this->setting['review_booster_product_price_color'] . '; font-weight: ' . $this->setting['review_booster_product_price_weight'] . '; font-style:' . $this->setting['review_booster_product_price_style'] . '; text-align:' . $this->setting['review_booster_product_price_align'] . '; font-family: Helvetica, Arial, sans-serif;">' . $product['price'] . '</td>
																	<td width="20">&nbsp;</td>
																</tr>' : '';
													$str .= '</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td height="15"><font size="1">&nbsp;</font></td>
												</tr>
											</tbody>
										</table>';

					$_t[] = $str;
				}

				$chunk = array_chunk($_t, (($this->setting['review_booster_product_column']) ? $this->setting['review_booster_product_column'] : 2));

				$str = '<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:100% !important; margin:0; padding:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="100%">
							<tbody>';

				foreach ($chunk as $items) {
					$str .= '<tr>';

					foreach ($items as $item) {
						$str .= '<td style="text-align: left; vertical-align: top;" valign="top">' . $item . '</td>';
					}

					$str .= '</tr>';
				}

				$str .= '</tbody>
						</table>';

				$t[] = $str;
			}
		}

		$find = array(
			'{client}',
			'{order_id}',
			'{email}',
			'{date_order}',
			'{coupon_code}',
			'{list}'
		);

		$replace = array(
			'client'      => $this->shortcode['client'],
			'order_id'    => $this->shortcode['order_id'],
			'email'       => $this->shortcode['email'],
			'date_order'  => date($this->language->get('date_format_short'), strtotime($this->shortcode['date_order'])),
			'coupon_code' => ($this->coupon) ? $this->coupon['code'] : '',
			'list'        => implode("", $t)
		);

		$this->message = '<html dir="ltr" lang="en">' . "\n";
		$this->message .= '<head>' . "\n";

		$this->message .= '<title></title>' . "\n";
		$this->message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$this->message .= '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport" />' . "\n";
		$this->message .= '<style type="text/css">body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}body {margin:0; padding:0;}table {border-spacing:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;}table td {border-collapse:collapse;}img { border: 0; }html {width: 100%; height: 100%;}a {color:#' . $this->setting['review_booster_color_link'] . '; text-decoration:none;} a:link {color:#' . $this->setting['review_booster_color_link'] . '; text-decoration:none;} a:visited {color:#' . $this->setting['review_booster_color_link'] . '; text-decoration:none;} a:focus {color:#' . $this->setting['review_booster_color_link_hover'] . ' !important;} a:hover {color:#' . $this->setting['review_booster_color_link_hover'] . ' !important;}</style>' . "\n";
		$this->message .= '</head>' . "\n";
		$this->message .= "<body>" . str_replace($find, $replace, html_entity_decode($view, ENT_QUOTES, 'UTF-8')) . '</body>' . "\n";
		$this->message .= '</html>' . "\n";

		$find = array(
			'{client}',
			'{order_id}',
			'{email}'
		);

		$replace = array(
			'client'      => $this->shortcode['client'],
			'order_id'    => $this->shortcode['order_id'],
			'email'       => $this->shortcode['email']
		);

		$this->suject = str_replace($find, $replace, $title);
	}

	private function resetData() {
		$this->products = array();
	}

	private function send() {
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($this->shortcode['email']);
		$mail->setFrom($this->email);
		$mail->setSender($this->sender);
		$mail->setSubject($this->suject);
		$mail->setHtml($this->message);
		$mail->send();

		$handle = fopen(DIR_LOGS . 'review_booster.txt', 'a+');

		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $this->sender . ' - ' . $this->email . "\n");

		fclose($handle);
	}
}
?>