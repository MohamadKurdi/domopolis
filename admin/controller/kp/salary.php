<?

class ControllerKPSalary extends Controller
{
	private $dz_orders = array();

	public $complete_cancel_percent_params = array(20, 30, 10000000);
	public $average_confirm_time_params = array(2.5, 3.5, 10000000);
	public $average_process_time_params = array(21, 35, 10000000);

    //Параметры менеджера
	public $percentage_params = array(0.04, 0.03, 0);
	public $fixed_salary = 10000;

	public static $static_percentage_params = array(0.04, 0.03, 0);
	public static $static_fixed_salary = 10000;

    //Параметры руководителя
	public $head_percentage_params = array(0.08, 0.06, 0);
	public $head_fixed_salary = 6000;

	public static $head_static_percentage_params = array(0.08, 0.06, 0);
	public static $head_static_fixed_salary = 6000;

	const default_filter_count_days = 365;
	const default_filter_count_days_problem = 50;

	private function dateDifference($date_1, $date_2, $differenceFormat = '%a')
	{
		$datetime1 = date_create($date_1);
		$datetime2 = date_create($date_2);

		$interval = date_diff($datetime1, $datetime2);

		return $interval->format($differenceFormat);
	}

	private function setCoefficients($manager_id)
	{
        //check if UA, TODO: переназначение из настроек
		$query = $this->db->query("SELECT user_group_id FROM user WHERE user_id = '" . (int)$manager_id . "'");

		if ($query->row['user_group_id'] == 27) {
			$this->percentage_params        = array(0.06, 0.05, 0);
			$this->static_percentage_params = array(0.06, 0.05, 0);
			$this->fixed_salary             = 10000;
		} else {
			$this->percentage_params        = array(0.04, 0.03, 0);
			$this->static_percentage_params = array(0.04, 0.03, 0);
			$this->fixed_salary             = 10000;
		}
	}

	private function getBrandManagerReportQueryBuilder($date_from, $date_to, $brands = false)
	{

        //ALL SQL
		$sql = "SELECT COUNT(order_id) as total FROM `order` WHERE order_id IN (SELECT order_id FROM order_history WHERE order_status_id IN (" . $this->config->get('config_cancelled_status_id') . ", ". (int)$this->config->get('config_complete_status_id') ." AND DATE(date_added) >= '" . $this->db->escape($date_from) . "') AND DATE(date_added) >= '" . $this->db->escape($date_to) . "')";

			if ($brands) {
				$sql .=  " AND order_id IN (SELECT order_id FROM order_product WHERE product_id IN (SELECT product_id FROM product WHERE manufacturer_id IN (" . implode(',', $brands) . ")))";
			}

			$current_all = $this->db->query($sql);


        //Отмены
			$sql = "SELECT COUNT(order_id) as total FROM `order` WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND order_id IN (SELECT order_id FROM order_history WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND DATE(date_added) >= '" . $this->db->escape($date_from) . "' AND DATE(date_added) >= '" . $this->db->escape($date_to) . "')";

			if ($brands) {
				$sql .=  " AND order_id IN (SELECT order_id FROM order_product WHERE product_id IN (SELECT product_id FROM product WHERE manufacturer_id IN (" . implode(',', $brands) . ")))";
			}

			$current_cancelled_all = $this->db->query($sql);


        //CANCELLED BY REASONS
			$sql = "SELECT COUNT(order_id) as total FROM `order` WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND reject_reason_id IN (" . implode(',', $this->config->get('config_brandmanager_fail_order_status_id')) . ") AND order_id IN (SELECT order_id FROM order_history WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND DATE(date_added) >= '" . $this->db->escape($date_from) . "' AND DATE(date_added) >= '" . $this->db->escape($date_to) . "')";

			if ($brands) {
				$sql .=  " AND order_id IN (SELECT order_id FROM order_product WHERE product_id IN (SELECT product_id FROM product WHERE manufacturer_id IN (" . implode(',', $brands) . ")))";
			}

			$current_cancelled_reasons = $this->db->query($sql);


			return array(
				'date_from'                         => $date_from,
				'date_to'                           => $date_to,
				'all'                               => $current_all->row['total'],
				'cancelled_all'                     => $current_cancelled_all->row['total'],
				'cancelled_reasons'                 => $current_cancelled_reasons->row['total'],
        //Процент отмен общий
				'all_to_cancelled_all_percent'      => round(($current_cancelled_all->row['total'] / $current_all->row['total'] * 100), 2),
        //Процент отмен по статусам общий
				'all_to_cancelled_reasons_percent'  => round($current_cancelled_reasons->row['total'] / $current_all->row['total'] * 100, 2),
        //Процент отмен по статусам общий от количества отмен
				'cancelled_all_to_cancelled_reasons_percent'  => round($current_cancelled_reasons->row['total'] / $current_cancelled_all->row['total'] * 100, 2),

			);
		}


		public function getBrandManagerReport($data = array())
		{
			$this->load->model('kp/work');

			$date_from = date('Y-m-d', strtotime($data['date_from']));
			$date_to = date('Y-m-d', strtotime($data['date_to']));
			$days_between = $this->dateDifference($date_from, $date_to);
			$brands = array();

			if (!empty($data['brands'])) {
				$explodedBrands = explode(',', trim($data['brands']));

				foreach ($explodedBrands as $brand) {
					if (!is_numeric($brand)) {
						$query = $this->db->query("SELECT manufacturer_id FROM manufacturer_description WHERE LOWER(name) LIKE '" . $this->db->escape(mb_strtolower(trim($brand))) . "'");
						if ($query->num_rows) {
							$manufacturer_id = (int)$query->row['manufacturer_id'];
						}
					} else {
						$manufacturer_id = (int)$brand;
					}
				}

				if ($manufacturer_id) {
					$brands[] = $manufacturer_id;
				}
			}

			$json = array();
			$json['current_period'] = $this->getBrandManagerReportQueryBuilder($date_from, $date_to, $brands);

			$date_from_1 = date('Y-m-d', strtotime($date_from . "-$days_between days"));
			$date_to_1 = date('Y-m-d', strtotime($date_to . "-$days_between days"));
			$json['previous_period'] = $this->getBrandManagerReportQueryBuilder($date_from_1, $date_to_1, $brands);

			$date_from_2 = date('Y-m-d', strtotime($date_from . "-1 year"));
			$date_to_2 = date('Y-m-d', strtotime($date_to . "-1 year"));
			$json['previous_year_current_period'] = $this->getBrandManagerReportQueryBuilder($date_from_2, $date_to_2, $brands);

			$date_from_2 = date('Y-m-d', strtotime($date_from . "$date_from_1 -1 year"));
			$date_to_2 = date('Y-m-d', strtotime($date_to . "$date_to_1 -1 year"));
			$json['previous_year_previous_period'] = $this->getBrandManagerReportQueryBuilder($date_from_2, $date_to_2, $brands);


			$this->response->setOutput(json_encode($json));
		}


		public function getManagerKPIFor1C($data = array())
		{
			$this->load->model('kp/work');

			if ($data['info_type'] == 'getparams') {
				$response = array();
				$response['Параметры'] = array();

				$response['Параметры']['Параметр1'] = array(
					'Название'  => 'Процент неконверсионных заказов',
					'Ключ'      => 'complete_cancel',
					'Зоны'      => $this->complete_cancel_percent_params
				);

				$response['Параметры']['Параметр2'] = array(
					'Название'  => 'Среднее время подтверждения заказа, дней',
					'Ключ'      => 'average_confirm',
					'Зоны'      => $this->average_confirm_time_params
				);

				$response['Параметры']['Параметр3'] = array(
					'Название'  => 'Среднее время подтверждения заказа, дней',
					'Ключ'      => 'average_process',
					'Зоны'      => $this->average_process_time_params
				);

				$response['ФиксированнаяСтавкаМенеджера'] = $this->fixed_salary;
				$response['ФиксированнаяСтавкаРОП'] = $this->head_fixed_salary;
				$response['ПроцентПремииМенеджераЗаПопаданиеВЗону'] = $this->percentage_params;
				$response['ПроцентПремииРОПЗаПопаданиеВЗону'] = $this->head_percentage_params;


				$this->response->setOutput(json_encode($response));
			}

			if ($data['info_type'] == 'getvalues') {
				if (empty($data['month'])) {
					$filter_month = date('m', strtotime("-1 month"));
				} else {
					$filter_month = (int)$data['month'];
				}

				if (empty($data['year'])) {
					$filter_year = date('Y');
				} else {
					$filter_year = (int)$data['year'];
				}

				$all_managers = $this->model_kp_work->getManagersWhoClosedOrdersForMonth($filter_month, $filter_year, $add_headsales = true);

				$response = array();
				foreach ($all_managers as $one_manager) {
					$this->setCoefficients($one_manager['user_id']);
					$count_data = $this->countManagers($filter_month, $filter_year, $one_manager['user_id'], true, false, false, false);

					$response[] = array(
						'name'      => $one_manager['realname'],
						'username'  => $one_manager['username'],
						'user_id'   => $one_manager['user_id'],
						'kpi_data'  => $count_data['cstats']['kpi_params'],
						'sum_data' => $count_data['cstats']['total_good_sum']
					);
				}

				$this->response->setOutput(json_encode($response));
			}
		}

		public function index()
		{
			$this->language->load('user/user');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('user/user');
			$this->load->model('user/user_group');

			$this->getList();
		}

		private function secToHR($seconds)
		{
			$hours = floor($seconds / 3600);
			$minutes = floor(($seconds / 60) % 60);
			$seconds = $seconds % 60;
			return $hours > 0 ? "$hours ч., $minutes мин." : ($minutes > 0 ? "$minutes мин., $seconds сек. " : "$seconds сек.");
		}

		private function dateDiff($date1, $date2)
		{

			$d = (strtotime($date2)-strtotime($date1))/(60*60*24);
			if (!round($d)) {
				$d = 1;
			} else {
				$d = round($d);
			}

			return $d;
		}

		private function countPercentByParam($num, $param_array, $percent_array)
		{

			if ($num <= $param_array[0]) {
				return $percent_array[0];
			}

			if ($num <= $param_array[1]) {
				return $percent_array[1];
			}

			return $percent_array[2];
		}

		private function countValueByParam($num, $param_value, $param_array, $percent_array)
		{

			if ($param_value <= $param_array[0]) {
				return ($num / 100) * $percent_array[0];
			}

			if ($param_value <= $param_array[1]) {
				return ($num / 100) * $percent_array[1];
			}

			return ($num / 100) * $percent_array[2];
		}

		private function tdClass($num, $param1, $param2, $param3)
		{

			if ($num <= $param1) {
				return '_red';
			}

			if ($num <= $param2) {
				return '_orange';
			}

			if ($num <= $param3) {
				return '_green';
			}

			return '_green';
		}

		private function tdClassRev($num, $param1, $param2, $param3)
		{

			if ($num <= $param1) {
				return '_green';
			}

			if ($num <= $param2) {
				return '_orange';
			}

			if ($num <= $param3) {
				return '_red';
			}

			return '_green';
		}

		public function getOrderTotalTotal($order_id)
		{

			$this->load->model('sale/order');
			$products = $this->model_sale_order->getOrderProducts($order_id, $with_returns = true, $order_by = 'op.delivery_num, op.name', $delivery_num = false, $no_certificate = true);

			$sum = 0;
			foreach ($products as $product) {
				$sum += $product['totalwd_national'];
			}

			return $sum;

			return false;
		}

		public function processTimes($order_id)
		{
			$this->load->model('sale/order');
			$order = $this->model_sale_order->getOrder($order_id);
			$order_histories = $this->model_sale_order->getOrderHistories($order_id, 0, 200);

			$date_accepted = false;
			$date_closed = false;
			foreach ($order_histories as $history) {
				if (!$date_accepted  && in_array($history['order_status_id'], array($this->config->get('config_confirmed_order_status_id'), $this->config->get('config_prepayment_paid_order_status_id'), $this->config->get('config_total_paid_order_status_id')))) {
					$date_accepted = $history['date_added'];
				}

				if ($history['order_status_id'] == $this->config->get('config_complete_status_id')) {
					$date_closed = $history['date_added'];
				}
			}

			return array(
				'date_added'    => $order['date_added'],
				'date_accepted' => $date_accepted?$date_accepted:$order['date_added'],
				'date_closed'   => $date_closed
			);
		}

		public function alreadyPaidFor($order_id, $return_parties = false, $filter_dzioev_bug = false, $filter_count_time = false, $filter_count_time_days = false)
		{

			if (SITE_NAMESPACE == 'KITCHEN') {
				$parties = array(
					'220' => 'U1816',
					'176' => 'R1807',
					'109' => 'R1807',
					'20'  => 'B1807'
				);

				$parties_by_store_id = array(
					'1' => '220',
					'0' => '176',
					'2' => '109',
					'5'  => '20'
				);
			}

			if (SITE_NAMESPACE == 'HAUSGARTEN') {
				$parties = array(
					'220' => 'U1801',
					'176' => 'R1801',
					'109' => 'R1801',
					'20'  => 'B1807'
				);
			}

			$this->load->model('sale/order');
			$order_products = $this->model_sale_order->getOrderProducts($order_id);
			$order = $this->model_sale_order->getOrder($order_id);

			$total_paid_for = 0;
			$used_parties = array();
			$unused_parties = array();
			foreach ($order_products as $order_product) {
				if (!$order['shipping_country_id']) {
					$order['shipping_country_id'] = $order['payment_country_id'];
				}

            //Если партия задана и номер партии меньше либо равен
				if (!isset($parties[$order['shipping_country_id']])) {
					if (isset($parties_by_store_id[$order['store_id']])) {
						$order['shipping_country_id'] = $parties_by_store_id[$order['store_id']];
					} else {
						$order['shipping_country_id'] = 220;
					}
				}

				if ((mb_strlen($order_product['part_num']) > 0) && (trim($order_product['part_num']) <= $parties[$order['shipping_country_id']])) {
					$used_parties[] = $order_product['part_num'];
					$total_paid_for += $order_product['totalwd_national'];
				} else {
					$unused_parties[] = $order_product['part_num'];
				}
			}

			if ($return_parties) {
				return array(
					'used_parties' => $used_parties,
					'unused_parties' => $unused_parties
				);
			}

			if ($filter_count_time) {
				$times = $this->processTimes($order_id);
				if ($this->dateDiff($times['date_accepted'], $times['date_closed']) > $filter_count_time_days) {
                //если по заказу уже были оплаты
					if ((float)$total_paid_for) {
						return $this->getOrderTotalTotal($order_id);
					} else {
						return $this->getOrderTotalTotal($order_id);
					}
				}
			}

        /*
                if ($filter_dzioev_bug) {
                if (in_array($order_id, $this->dz_orders)){
                if ((int)$this->getOrderTotalTotal($order_id) == (int)$total_paid_for){
                return (float)$total_paid_for;
                } else {
                //return $this->getOrderTotalTotal($order_id);
                return (float)$this->getOrderTotalTotal($order_id) - ((float)$this->getOrderTotalTotal($order_id) - (float)$total_paid_for) / 2;
                }
                }
                }
        */

                return (float)$total_paid_for;
            }


            public function countManagers($param_filter_month = false, $param_filter_year = false, $param_filter_manager = false, $param_return_data = false, $param_filter_dzioev_bug = false, $param_filter_count_time = false, $param_filter_count_time_days = false)
            {

        //если это не консольный доступ
            	if (php_sapi_name()!=="cli" && is_object($this->user) && $this->user->getID()) {
            		if ($this->user->getUserGroup() != 1 && $this->user->getUserGroup() != 23 && !$this->user->canUnlockOrders()) {
            			$this->redirect($this->url->link('user/work', 'token=' . $this->session->data['token'], 'SSL'));
            		}
            	}

            	if (!isset($this->session->data['token'])) {
            		$this->session->data['token'] = '';
            	}

            	$this->document->setTitle('Отчет по менеджерам-продажникам за месяц');
            	$this->data['heading_title'] = 'Отчет по менеджерам-продажникам за месяц';
            	$this->data['token'] = isset($this->session->data['token'])?$this->session->data['token']:'';

            	$this->load->model('user/user');
            	$this->load->model('kp/work');
            	$this->load->model('user/user_group');
            	$this->load->model('catalog/actiontemplate');
            	$this->load->model('sale/coupon');
            	$this->load->model('sale/order');
            	$this->load->model('localisation/country');
            	$this->load->model('setting/setting');


            	$this->data['breadcrumbs'] = array();

            	$this->data['breadcrumbs'][] = array(
            		'text'      => $this->language->get('text_home'),
            		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            		'separator' => false
            	);

            	$this->data['breadcrumbs'][] = array(
            		'text'      => $this->language->get('heading_title'),
            		'href'      => $this->url->link('kp/salary', 'token=' . $this->session->data['token'], 'SSL'),
            		'separator' => ' :: '
            	);

            	if (empty($this->request->get['filter_month'])) {
            		$filter_month = date('m', strtotime("-1 month"));
            	} else {
            		$filter_month = (int)$this->request->get['filter_month'];
            	}

            	if (empty($this->request->get['filter_year'])) {
            		if ($filter_month == 12) {
            			$filter_year = date('Y') - 1;
            		} else {
            			$filter_year = date('Y');
            		}
            	} else {
            		$filter_year = (int)$this->request->get['filter_year'];
            	}

            	$allow_to_view_sums = false;
            //АВ
            	if ($this->user->getIsAV()) {
            		$allow_to_view_sums = true;
            	}

            //СуперАдминистраторы
            	if ($this->user->getUserGroup() == 1) {
            		$allow_to_view_sums = true;
            	}

            	$this->data['allow_to_view_sums'] = $allow_to_view_sums;

            //если зайдет кто-то из менеджеров
            	if (!$this->user->getIsAV() && !$this->user->canUnlockOrders() && in_array($this->user->getUserGroup(), array(12))) {
            		$filter_manager = (int)$this->user->getID();
            	}

            	if ($param_filter_month) {
            		$filter_month = $param_filter_month;
            	}

            	if ($param_filter_year) {
            		$filter_year = $param_filter_year;
            	}

            	$this->data['managers'] = $this->model_kp_work->getManagersWhoClosedOrdersForMonth($filter_month, $filter_year);

            	if (empty($this->request->get['filter_manager'])) {
            		$filter_manager = $this->data['managers'][0]['user_id'];
            	} else {
            		$filter_manager = (int)$this->request->get['filter_manager'];
            	}

            	if ($param_filter_manager) {
            		$filter_manager = $param_filter_manager;
            	}

            	$this->setCoefficients($filter_manager);

            	if (empty($this->request->get['filter_dzioev_bug'])) {
            		$filter_dzioev_bug = false;
            	} else {
            		$filter_dzioev_bug = true;
            	}

            	if ($param_filter_dzioev_bug) {
            		$filter_dzioev_bug = $param_filter_dzioev_bug;
            	}

            	if (empty($this->request->get['filter_count_time'])) {
            		$filter_count_time = false;
            	} else {
            		$filter_count_time = true;
            	}

            	if ($param_filter_count_time) {
            		$filter_count_time = $param_filter_count_time;
            	}

            	if (empty($this->request->get['filter_count_time_days'])) {
            		$filter_count_time_days = self::default_filter_count_days;
            	} else {
            		$filter_count_time_days = $this->request->get['filter_count_time_days'];
            	}

            	if ($param_filter_count_time_days) {
            		$filter_count_time_days = $param_filter_count_time_days;
            	}

            	$form_log = new Log('price_fails_for_us.txt');
            	$form_log->write($this->user->getID() . ' : FOR ' . $filter_manager . ', LIMIT ' . $filter_count_time_days . ', INCLUDE ' . $filter_count_time);

            //получаем начальника ОП
            	$headsales_id = $this->model_user_user->getHeadSalesUserID();
            	$this->data['is_headsales'] = $is_headsales = ($filter_manager == $headsales_id);

            	if ($is_headsales) {
            		$_filter_link = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_date_added=' . $filter_year . '-' . $filter_month . '-01' . '&filter_date_added_to=' . $filter_year . '-' . $filter_month . '-' . date('t', strtotime($filter_year . '-' . $filter_month . '-01')));
            	} else {
            		$_filter_link = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_manager_id=' . $filter_manager . '&filter_date_added=' . $filter_year . '-' . $filter_month . '-01' . '&filter_date_added_to=' . $filter_year . '-' . $filter_month . '-' . date('t', strtotime($filter_year . '-' . $filter_month . '-01')));
            	}

            	$this->data['cstats'] = array(
            		'total_days' => $this->model_kp_work->countDays($filter_manager, $filter_month, $filter_year),
            		'total_time' => $this->secToHR($this->model_kp_work->countTimeDiffSum($filter_manager, $filter_month, $filter_year)),
            		'total_actions' => $this->model_kp_work->countFieldSum($filter_manager, 'daily_actions', $filter_month, $filter_year),

            		'total_emails' => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'sent_mail_count', $filter_month, $filter_year),
            		'total_edit_order_count'    => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'edit_order_count', $filter_month, $filter_year),
            		'total_edit_customer_count' => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'edit_customer_count', $filter_month, $filter_year),

            		'total_inbound' => $this->model_kp_work->countFieldSum($filter_manager, 'inbound_call_count', $filter_month, $filter_year),
            		'total_inbound_duration' => $this->secToHR($this->model_kp_work->countFieldSum($filter_manager, 'inbound_call_duration', $filter_month, $filter_year)),
            		'total_outbound' => $this->model_kp_work->countFieldSum($filter_manager, 'outbound_call_count', $filter_month, $filter_year),
            		'total_outbound_duration' => $this->secToHR($this->model_kp_work->countFieldSum($filter_manager, 'outbound_call_duration', $filter_month, $filter_year)),

            //1 параметр
            		'total_owned_order_count'   => $this->model_kp_work->getTotalManagerOrdersForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),
            		'total_owned_order_filter'  => $_filter_link,

            //2 параметр
            		'total_confirmed_to_process_order_count' => $this->model_kp_work->getCountConfirmedToProcessOrdersForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),

            		'total_success_order_count'   => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'success_order_count', $filter_month, $filter_year),
            		'total_success_order_count2'   => $this->model_kp_work->getCountClosedOrdersForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),

            		'total_cancel_order_count'   => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'cancel_order_count', $filter_month, $filter_year),
            		'total_cancel_order_count2'   => $this->model_kp_work->getCountCancelledOrdersForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),


            		'avg_csi_by_orders'   => $this->model_kp_work->getAVGCSIByOrdersForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),
            		'avg_csi_by_manager'   => $this->model_kp_work->getAVGCSIByManagerForMonth(($is_headsales)?false:$filter_manager, $filter_month, $filter_year),

            		'total_confirmed_order_count'   => $this->model_kp_work->countFieldSum(($is_headsales)?false:$filter_manager, 'confirmed_order_count', $filter_month, $filter_year),


            	);

            	$orders = array();

            	if ($is_headsales) {
            		$_rorders = $this->model_kp_work->getClosedOrdersForMonth(false, $filter_month, $filter_year);
            	} else {
            		$_rorders = $this->model_kp_work->getClosedOrdersForMonth($filter_manager, $filter_month, $filter_year);
            	}

            	$total_confirm_time = 0;
            	$total_process_time = 0;

            	if ($_rorders) {
            		foreach ($_rorders as $_rorder_id) {
            			$problems = array();
            			$alerts = array();
            			unset($used_unused_parties);
            			unset($already_paid);

            			$_rorder = $this->model_sale_order->getOrder($_rorder_id['order_id']);

            			if (!isset($orders[$_rorder['store_id']])) {
            				$orders[$_rorder['store_id']] = array();
            				$orders[$_rorder['store_id']]['orders'] = array();
            				$orders[$_rorder['store_id']]['total'] = 0;
            				$orders[$_rorder['store_id']]['total_text'] = '';
            				$orders[$_rorder['store_id']]['country_name'] = $this->model_localisation_country->getCountryName($this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $_rorder['store_id']));
            			}

            			$ototal = $this->getOrderTotalTotal($_rorder['order_id']);

            			if (date('Y.m.d', strtotime($_rorder['date_added'])) >= date('Y.m.d', strtotime($_rorder_id['date_added']))) {
            				$problems[] = 'Быстрое закрытие';
            			}

            			$check_status_count = $this->model_sale_order->getOrderClosingStatusCount($_rorder_id['order_id']);
            			if ($check_status_count['parties_count'] != $check_status_count['status_count']) {
                        //$problems[] = 'Партий: '.$check_status_count['parties_count'].', статусов: ' . $check_status_count['status_count'] . '';
            			}

            			if (!(int)$_rorder['closed']) {
            				$problems[] = 'Заказ разблокирован';
            			}

            			if ((int)$_rorder['salary_paid']) {
            				$problems[] = 'Зарплата уже выплачена';
            			}

            			if ($_rorder['csi_average'] > 0 && $_rorder['csi_average'] <= 3) {
            				$problems[] = 'CSI ' . round($_rorder['csi_average'], 2);
            			}

                    /*
                        if ($filter_dzioev_bug){
                        if (in_array($_rorder['order_id'], $this->dz_orders)){
                        $problems[] = 'Заказ Джиоева';
                        }
                        }
                    */

                        $already_paid = $this->alreadyPaidFor($_rorder['order_id'], false, $filter_dzioev_bug, $filter_count_time, $filter_count_time_days);
                        $used_unused_parties = $this->alreadyPaidFor($_rorder['order_id'], true);

                        $times = $this->processTimes($_rorder['order_id']);

                    //оформлен - принят
                        if ($this->dateDiff($times['date_added'], $times['date_accepted']) > 10) {
                        	$problems[] = 'Подтверждение более 10 дней (' . $this->dateDiff($times['date_added'], $times['date_accepted']) . ')';
                        }
                        $total_confirm_time += $this->dateDiff($times['date_added'], $times['date_accepted']);

                    //подтвержден - выполнен
                        if ($this->dateDiff($times['date_accepted'], $times['date_closed']) > self::default_filter_count_days_problem) {
                        	$problems[] = 'Выполнение более '. self::default_filter_count_days_problem .' дн. (' . $this->dateDiff($times['date_accepted'], $times['date_closed']) . ')';
                        }
                        $total_process_time += $this->dateDiff($times['date_accepted'], $times['date_closed']);

                    //подтвержден - выполнен
                    /*
                        if ($this->dateDiff($_rorder['date_added'], $_rorder_id['date_added']) > 42){
                        $problems[] = 'Общее выполнение более 42 дней (' . $this->dateDiff($_rorder['date_added'], $_rorder_id['date_added']) . ')';
                        }
                    */

                        if (isset($this->session->data['token'])) {
                        	$_url = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $_rorder['order_id'], 'SSL');
                        } else {
                        	$_url = false;
                        }

                        $orders[$_rorder['store_id']]['orders'][] = array(
                        	'order_id'          => $_rorder['order_id'],
                        	'salary_paid'       => (int)$_rorder['salary_paid'],
                        	'is_closed'         => (int)$_rorder['closed'],
                        	'manager_id'        => (int)$_rorder['manager_id'],
                        	'manager_name'      => $this->model_user_user->getRealUserNameById((int)$_rorder['manager_id']),
                        	'currency_code'     => $_rorder['currency_code'],
                        	'date_added'        => date('Y.m.d', strtotime($times['date_added'])),
                        	'date_aссepted'     => date('Y.m.d', strtotime($times['date_accepted'])),
                        	'date_accepted_diff' => $this->dateDiff($times['date_added'], $times['date_accepted']),
                        	'date_aссepted_class' => $this->tdClassRev($this->dateDiff($times['date_added'], $times['date_accepted']), 10, $filter_count_time_days, 42),
                        	'date_closed'       => date('Y.m.d', strtotime($times['date_closed'])),
                        	'date_closed_diff'  => $this->dateDiff($times['date_added'], $times['date_closed']),
                        	'date_closed_class' => $this->tdClassRev($this->dateDiff($times['date_added'], $times['date_closed']), 10, $filter_count_time_days, 42),
                        	'is_time_bad'       => $filter_count_time?($this->dateDiff($times['date_accepted'], $times['date_closed']) > $filter_count_time_days):false,
                        	'problems'          => $problems,
                        	'alerts'            => $alerts,
                        	'ppaid'             => $used_unused_parties['used_parties']?'Партии оплаченные: ' . implode(', ', array_unique($used_unused_parties['used_parties'])):'',
                        	'punpaid'           => $used_unused_parties['unused_parties']?'Партии неоплаченные: ' . implode(', ', array_unique($used_unused_parties['unused_parties'])):'',
                        	'url'               => $_url,
                        	'total'             => $ototal,
                        	'total_text'        => $this->currency->format($ototal, $_rorder['currency_code'], 1),
                        	'already_paid'      => $already_paid,
                        	'already_paid_text' => $this->currency->format($already_paid, $_rorder['currency_code'], 1),
                        	'left_to_pay'       => ($ototal - $already_paid),
                        	'left_to_pay_text'  => $this->currency->format(($ototal - $already_paid), $_rorder['currency_code'], 1),
                        );


                    //AV FIX
                    if (true /*!(int)$_rorder['salary_paid']*/) {
                    	$orders[$_rorder['store_id']]['total'] += $ototal;
                    	$orders[$_rorder['store_id']]['total_text'] = $this->currency->format($orders[$_rorder['store_id']]['total'], $_rorder['currency_code'], 1);
                    }
                }
            }
            
            $this->data['country_orders'] = $orders;
            
            $this->load->model('localisation/country');
            $__countries = $this->model_localisation_country->getCountries();
            
            $total_good_sum = array();
            $this->data['das_total_in_uah'] = 0;
            foreach ($__countries as $__country) {
            	if ($__country['country_id'] != 81) {
            		if (!isset($total_good_sum[$__country['name']])) {
            			$total_good_sum[$__country['name']] = array();
            			$total_good_sum[$__country['name']]['total'] = 0;
            			$total_good_sum[$__country['name']]['text'] = 0;
            			$total_good_sum[$__country['name']]['already_paid'] = 0;
            			$total_good_sum[$__country['name']]['left_to_pay'] = 0;
            			$total_good_sum[$__country['name']]['currency'] = '';
            			$total_good_sum[$__country['name']]['total_text'] = 0;
            			$total_good_sum[$__country['name']]['already_paid_text'] = 0;
            			$total_good_sum[$__country['name']]['left_to_pay_text'] = 0;
            			$total_good_sum[$__country['name']]['course'] = 0;
            			$total_good_sum[$__country['name']]['course_real'] = 0;
            			$total_good_sum[$__country['name']]['left_to_pay_text_uah'] = 0;
            		}
            	}
            }
            
            
            foreach ($this->data['country_orders'] as $store_id => $country) {
            	if (!isset($total_good_sum[$country['country_name']])) {
            		$total_good_sum[$country['country_name']] = array();
            		$total_good_sum[$country['country_name']]['total'] = 0;
            		$total_good_sum[$country['country_name']]['text'] = 0;
            		$total_good_sum[$country['country_name']]['already_paid'] = 0;
            		$total_good_sum[$country['country_name']]['left_to_pay'] = 0;
            		$total_good_sum[$country['country_name']]['course'] = 0;
            		$total_good_sum[$country['country_name']]['course_real'] = 0;
            		$total_good_sum[$country['country_name']]['left_to_pay_text_uah'] = 0;
            		$total_good_sum[$country['country_name']]['left_to_pay_uah'] = 0;
            	}

            	foreach ($country['orders'] as $_o) {
                    //Фикс для АВ FIX
            	if (true /*!(int)$_o['salary_paid'] */) {
                        //Общая сумма закрытых заказов
            		$total_good_sum[$country['country_name']]['total'] += $_o['total'];
            		$total_good_sum[$country['country_name']]['total_text'] =
            		$this->currency->format($total_good_sum[$country['country_name']]['total'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id), 1);

                        //ЗП, пока не выводится
            		$total_good_sum[$country['country_name']]['total_salary'] = ($total_good_sum[$country['country_name']]['total']/100)*0.6;
            		$total_good_sum[$country['country_name']]['total_salary_text'] =
            		$this->currency->format($total_good_sum[$country['country_name']]['total_salary'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id), 1);


                        //Уже заплачено
            		$total_good_sum[$country['country_name']]['already_paid'] += $_o['already_paid'];
            		$total_good_sum[$country['country_name']]['already_paid_text'] =
            		$this->currency->format($total_good_sum[$country['country_name']]['already_paid'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id), 1);

                        //Осталось заплатить
            		$total_good_sum[$country['country_name']]['left_to_pay'] += $_o['left_to_pay'];
            		$total_good_sum[$country['country_name']]['left_to_pay_text'] =
            		$this->currency->format($total_good_sum[$country['country_name']]['left_to_pay'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id), 1);

                        //Валюта
            		$total_good_sum[$country['country_name']]['currency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id);

                        //курс к гривне
            		$total_good_sum[$country['country_name']]['course'] = number_format($this->currency->real_convert(1, $total_good_sum[$country['country_name']]['currency'], 'UAH', true), 2, '.', ' ');

                        //курс к гривне реальный
            		$total_good_sum[$country['country_name']]['course_real'] = number_format($this->currency->getUAHValueUnreal($total_good_sum[$country['country_name']]['currency']), 2, '.', ' ');

                        //тотал пересчитанный
            		$total_good_sum[$country['country_name']]['left_to_pay_uah'] = $this->currency->shit_convert_to_uah($total_good_sum[$country['country_name']]['left_to_pay'], $total_good_sum[$country['country_name']]['currency'], 'UAH');
            		$total_good_sum[$country['country_name']]['left_to_pay_text_uah'] =
            		$this->currency->format($this->currency->shit_convert_to_uah($total_good_sum[$country['country_name']]['left_to_pay'], $total_good_sum[$country['country_name']]['currency'], 'UAH'), 'UAH', 1);
            	}
            }

            $this->data['das_total_in_uah'] += !empty($total_good_sum[$country['country_name']]['left_to_pay_uah'])?$total_good_sum[$country['country_name']]['left_to_pay_uah']:0;
        }

        $this->data['das_total_in_uah_num'] = $this->data['das_total_in_uah'];
        $das_total_in_uah = $this->data['das_total_in_uah'];
        $this->data['das_total_in_uah'] = $this->currency->format($this->data['das_total_in_uah'], 'UAH', 1);

        $ccp = $this->data['cstats']['total_owned_order_count']?(100 - round($this->data['cstats']['total_confirmed_to_process_order_count'] / $this->data['cstats']['total_owned_order_count'], 2) * 100):0;
        $act = $_rorders?round($total_confirm_time / count($_rorders), 2):0;
        $apt = $_rorders?round($total_process_time / count($_rorders), 2):0;
        $kpi_params = array(
        	'complete_cancel_percent'           => $ccp . '%',
        	'complete_cancel_percent_clr'       => $this->tdClassRev($ccp, $this->complete_cancel_percent_params[0], $this->complete_cancel_percent_params[1], $this->complete_cancel_percent_params[2]),
        	'complete_cancel_percent_params'    => $this->complete_cancel_percent_params,
        	'complete_cancel_percent_percent'   => $this->countPercentByParam($ccp, $this->complete_cancel_percent_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'complete_cancel_percent_value'     => $this->countValueByParam($das_total_in_uah, $ccp, $this->complete_cancel_percent_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'complete_cancel_percent_value_txt' => $this->currency->format($this->countValueByParam($das_total_in_uah, $ccp, $this->complete_cancel_percent_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params), 'UAH', 1),

        	'average_confirm_time'        => $act,
        	'average_confirm_time_clr'    => $this->tdClassRev($act, $this->average_confirm_time_params[0], $this->average_confirm_time_params[1], $this->average_confirm_time_params[2]),
        	'average_confirm_time_params' => $this->average_confirm_time_params,
        	'average_confirm_time_percent'  => $this->countPercentByParam($act, $this->average_confirm_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'average_confirm_time_value'    => $this->countValueByParam($das_total_in_uah, $act, $this->average_confirm_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'average_confirm_time_value_txt' => $this->currency->format($this->countValueByParam($das_total_in_uah, $act, $this->average_confirm_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params), 'UAH', 1),

        	'average_process_time'        => $apt,
        	'average_process_time_clr'    => $this->tdClassRev($apt, $this->average_process_time_params[0], $this->average_process_time_params[1], $this->average_process_time_params[2]),
        	'average_process_time_params' => $this->average_process_time_params,
        	'average_process_time_percent'  => $this->countPercentByParam($apt, $this->average_process_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'average_process_time_value'    => $this->countValueByParam($das_total_in_uah, $apt, $this->average_process_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params),
        	'average_process_time_value_txt' => $this->currency->format($this->countValueByParam($das_total_in_uah, $apt, $this->average_process_time_params, ($is_headsales)?$this->head_percentage_params:$this->percentage_params), 'UAH', 1),
        );

        $this->data['cstats']['kpi_params'] = $kpi_params;

        $this->data['fixed_salary']      = ($is_headsales)?$this->head_fixed_salary:$this->fixed_salary;
        $this->data['fixed_salary_txt']  = $this->currency->format(($is_headsales)?$this->head_fixed_salary:$this->fixed_salary, 'UAH', 1);
        $this->data['percentage_params'] = ($is_headsales)?$this->head_percentage_params:$this->percentage_params;

        $this->data['full_salary'] = (($is_headsales)?$this->head_fixed_salary:$this->fixed_salary) + $kpi_params['complete_cancel_percent_value'] + $kpi_params['average_confirm_time_value'] + $kpi_params['average_process_time_value'];
        $this->data['full_salary_txt'] = $this->currency->format($this->data['full_salary'], 'UAH', 1);

        $this->data['filter_year'] = $filter_year;
        $this->data['filter_month'] = $filter_month;
        $this->data['filter_manager'] = $filter_manager;
        $this->data['filter_dzioev_bug'] = $filter_dzioev_bug;
        $this->data['filter_count_time'] = $filter_count_time;
        $this->data['filter_count_time_days'] = $filter_count_time_days;

        $this->data['cstats']['total_good_sum'] = $total_good_sum;

        $this->data['has_rights'] = $this->user->getISAV() || $this->user->canUnlockOrders();

        $this->data['token'] = $this->session->data['token'];
        $this->data['month'] = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь','Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');

        if ($param_return_data) {
        	return $this->data;
        }

        $do_pdf = isset($this->request->get['do_pdf']) && $this->request->get['do_pdf'] && $allow_to_view_sums;

        $this->data['do_not_print_details'] = isset($this->request->get['do_not_print_details']) && $this->request->get['do_not_print_details'] && $allow_to_view_sums;

        if (!$do_pdf) {
        	$this->template = 'user/salary_manager.tpl';
        	$this->children = array(
        		'common/header',
        		'common/footer'
        	);

        	$this->response->setOutput($this->render());
        } else {
        	$user_name = $this->model_user_user->getRealUserNameById($filter_manager);
        	$this->data['user_name'] = $user_name;

        	if ($filter_month == 1) {
        		$_f = 12;
        	} else {
        		$_f = ($filter_month - 1);
        	}
        	if ($filter_month < 10) {
        		$filter_month = '0'.$filter_month;
        	}
        	$dataname = $filter_month.'.'.$filter_year;
        	$this->data['dataname'] = $dataname;

        	$this->data['dataname2'] = $filter_month.'.'.$filter_year;

        	$this->template = 'user/salary_manager_pdf.tpl';
        	$html = $this->render();

        	$mpdf = new \Mpdf\Mpdf([
        		'mode' => 'utf-8',
        		'format' => 'A4'
        	]);

        	$html = str_replace(' !important', '', $html);
        	$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);

        	$filename = prepareFileName('Ведомость '. $user_name .' за '. $dataname .' '. SITE_NAMESPACE . '.pdf');

        	$mpdf->Output($filename, 'D');
        }
    }

    public function recalculateManagerAjax()
    {
    	if (empty($this->request->get['filter_month'])) {
    		$filter_month = date('m', strtotime("-1 month"));
    	} else {
    		$filter_month = (int)$this->request->get['filter_month'];
    	}

    	if (empty($this->request->get['filter_year'])) {
    		$filter_year = date('Y');
    	} else {
    		$filter_year = (int)$this->request->get['filter_year'];
    	}

    	if (empty($this->request->get['filter_manager'])) {
    		$filter_manager = $this->data['managers'][0]['user_id'];
    	} else {
    		$filter_manager = (int)$this->request->get['filter_manager'];
    	}

    	if (empty($this->request->get['filter_dzioev_bug'])) {
    		$filter_dzioev_bug = false;
    	} else {
    		$filter_dzioev_bug = true;
    	}

    	if (empty($this->request->get['filter_count_time'])) {
    		$filter_count_time = false;
    	} else {
    		$filter_count_time = true;
    	}

    	if (empty($this->request->get['filter_count_time_days'])) {
    		$filter_count_time_days = self::default_filter_count_days;
    	} else {
    		$filter_count_time_days = $this->request->get['filter_count_time_days'];
    	}

    	$data = $this->countManagers(
    		$param_filter_month = $filter_month,
    		$param_filter_year = $filter_year,
    		$param_filter_manager = $filter_manager,
    		$param_return_data = true,
    		$param_filter_dzioev_bug = $filter_dzioev_bug,
    		$param_filter_count_time = $filter_count_time,
    		$param_filter_count_time_days = $filter_count_time_days
    	);

    	$return_data = array();

    	foreach ($data['cstats']['total_good_sum'] as $country => $sum) {
    		$return_data[$sum['currency'] . '_left_to_pay_text_uah'] = $sum['left_to_pay_text_uah'];
    	}

    	$return_data['das_total_in_uah'] = $data['das_total_in_uah'];

    	$return_data['complete_cancel_percent_value_txt'] = $data['cstats']['kpi_params']['complete_cancel_percent_value_txt'];
    	$return_data['average_confirm_time_value_txt'] = $data['cstats']['kpi_params']['average_confirm_time_value_txt'];
    	$return_data['average_process_time_value_txt'] = $data['cstats']['kpi_params']['average_process_time_value_txt'];
    	$return_data['full_salary_txt'] = $data['full_salary_txt'];

    	$this->response->setOutput(json_encode($return_data));
    }


    public function consolidateCountManagers()
    {

        //если это не консольный доступ
    	if (php_sapi_name()!=="cli" && is_object($this->user)) {
    		if ($this->user->getUserGroup() != 1) {
    			$this->redirect($this->url->link('user/work', 'token=' . $this->session->data['token'], 'SSL'));
    		}
    	}

    	$this->load->model('user/user');
    	$this->load->model('kp/work');

    	if (empty($this->request->get['filter_month'])) {
    		$filter_month = date('m', strtotime("-1 month"));
    	} else {
    		$filter_month = (int)$this->request->get['filter_month'];
    	}

    	if (empty($this->request->get['filter_year'])) {
    		$filter_year = date('Y');
    	} else {
    		$filter_year = (int)$this->request->get['filter_year'];
    	}

    	if (empty($this->request->get['filter_dzioev_bug'])) {
    		$filter_dzioev_bug = false;
    	} else {
    		$filter_dzioev_bug = true;
    	}

    	if (empty($this->request->get['filter_count_time'])) {
    		$filter_count_time = false;
    	} else {
    		$filter_count_time = true;
    	}

    	if (empty($this->request->get['filter_count_time_days'])) {
    		$filter_count_time_days = self::default_filter_count_days;
    	} else {
    		$filter_count_time_days = $this->request->get['filter_count_time_days'];
    	}

    	$simple = false;
    	if (!empty($this->request->get['simple'])) {
    		$simple = true;
    	}

    	$all_managers = $this->model_kp_work->getManagersWhoClosedOrdersForMonth($filter_month, $filter_year, $add_headsales = false);

    	$data['data_by_managers'] = array();
    	$data['data_by_countries'] = array();
    	foreach ($all_managers as $one_manager) {
    		$data['data_by_managers'][] = array(
    			'name' => $one_manager['realname'],
    			'user_id' => $one_manager['user_id'],
    			'count_data' => $this->countManagers($filter_month, $filter_year, $one_manager['user_id'], true, $filter_dzioev_bug, $filter_count_time, $filter_count_time_days)
    		);
    	}

    	$data['manager_count'] = count($all_managers);
    	$data['total_cols'] = ($data['manager_count'] * 3) + 2;
    	$data['general_totals_by_countries'] = array();

    	foreach ($data['data_by_managers'] as $_m1) {
    		$_m2 = $_m1['count_data'];
    		$_m3 = $_m2['country_orders'];

    		foreach ($_m3 as $_m4) {
    			if (!isset($data['data_by_countries'][$_m4['country_name']])) {
    				$data['data_by_countries'][$_m4['country_name']] = array();
    			}

    			if (!isset($data['general_totals_by_countries'][$_m4['country_name']])) {
    				$data['general_totals_by_countries'][$_m4['country_name']] = array();

    				$data['general_totals_by_countries'][$_m4['country_name']]['total'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['already_paid'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['course'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['course_real'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay_uah'] = 0;
    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay_text_uah'] = 0;
    			}


    			foreach ($_m4['orders'] as $_m5) {
    				$data['data_by_countries'][$_m4['country_name']][] = $_m5;

    				$data['general_totals_by_countries'][$_m4['country_name']]['total'] += $_m5['total'];
    				$data['general_totals_by_countries'][$_m4['country_name']]['already_paid'] += $_m5['already_paid'];
    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay'] += $_m5['left_to_pay'];

    				$data['general_totals_by_countries'][$_m4['country_name']]['course_real'] = $_m2['cstats']['total_good_sum'][$_m4['country_name']]['course_real'];

    				$data['general_totals_by_countries'][$_m4['country_name']]['total_text'] = $this->currency->format($data['general_totals_by_countries'][$_m4['country_name']]['total'], $_m5['currency_code'], 1);
    				$data['general_totals_by_countries'][$_m4['country_name']]['total_text_uah'] = $this->currency->shit_convert_to_uah($data['general_totals_by_countries'][$_m4['country_name']]['total'], $_m5['currency_code'], 'UAH');

    				$data['general_totals_by_countries'][$_m4['country_name']]['already_paid_text'] = $this->currency->format($data['general_totals_by_countries'][$_m4['country_name']]['already_paid'], $_m5['currency_code'], 1);

    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay_text'] = $this->currency->format($data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay'], $_m5['currency_code'], 1);

    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay_uah'] = $this->currency->shit_convert_to_uah($data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay'], $_m5['currency_code'], 'UAH');
    				$data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay_text_uah'] = $this->currency->shit_convert_to_uah($data['general_totals_by_countries'][$_m4['country_name']]['left_to_pay'], $_m5['currency_code'], 'UAH');
    			}
    		}
    	}


    	$data['fucken_big_total_pay'] = 0;
    	foreach ($data['general_totals_by_countries'] as $_fucken_country) {
    		$data['fucken_big_total_pay'] += isset($_fucken_country['left_to_pay_uah'])?$_fucken_country['left_to_pay_uah']:0;
    	}

    	$data['fucken_big_total_pay_1p'] = $data['fucken_big_total_pay'] / 100;
    	$data['fucken_big_total_pay_06p'] = $data['fucken_big_total_pay'] / 100 * 0.6;

    	$this->data = $data;


    	$objPHPExcel     = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
+       $excelHTMLReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Html');
        $excelHTMLReader->setInputEncoding("UTF-8");

    	$this->template = 'user/salary_managers_xls.tpl';

    	if ($simple) {
    		$this->template = 'user/salary_managers_xls_simple.tpl';
    	}

    	$html = $this->render();

    	$this->response->setOutput($this->render());

    	$tmpfile = DIR_SYSTEM . 'temp/'.md5(time()).'.html';
    	file_put_contents($tmpfile, $html);

    	$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
    	$objPHPExcel->getActiveSheet()->setTitle('Отчет по заказам');
    	$objPHPExcel->getProperties()->setCreator("KITCHEN-PROFI")
    	->setLastModifiedBy("KITCHEN-PROFI")
    	->setTitle("Office 2007 XLSX")
    	->setSubject("Office 2007 XLSX")
    	->setDescription("Document for Office 2007 XLSX")
    	->setKeywords("")
    	->setCategory("KITCHEN-PROFI");

    	$columns = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE');

    	foreach ($columns as $col) {
    		$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    	}

    	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Сводный отчет по выполненным заказам - менеджера');
    	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    	$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

    	if ($simple) {
    		$objPHPExcel->getActiveSheet()->setCellValue($columns[$data['manager_count']-2].'3', 'Учетный месяц');
    		$objPHPExcel->getActiveSheet()->getStyle($columns[$data['manager_count']-2].'3')->getFont()->setBold(true);
    		$objPHPExcel->getActiveSheet()->getStyle('A5:'. $columns[$data['manager_count']+7] .'5')->getFont()->setBold(true);

    		if ($filter_month < 10) {
    			$filter_month = '0'.$filter_month;
    		}
    		$objPHPExcel->getActiveSheet()->getStyle($columns[$data['manager_count']+1].'3:'.$columns[$data['manager_count']*3+1].'3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
    		$objPHPExcel->getActiveSheet()->setCellValue($columns[$data['manager_count']+1].'3', $filter_month . ' . ' . $filter_year);

    		$objPHPExcel->getActiveSheet()->getStyle('A6:'.$columns[$data['manager_count']+2].'6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    	} else {
    		$objPHPExcel->getActiveSheet()->setCellValue($columns[$data['manager_count']*3-2].'3', 'Учетный месяц');
    		$objPHPExcel->getActiveSheet()->getStyle($columns[$data['manager_count']*3-2].'3')->getFont()->setBold(true);
    		$objPHPExcel->getActiveSheet()->getStyle('A5:'.$columns[$data['manager_count']*3+5].'5')->getFont()->setBold(true);

    		if ($filter_month < 10) {
    			$filter_month = '0'.$filter_month;
    		}
    		$objPHPExcel->getActiveSheet()->getStyle($columns[$data['manager_count']*3+1].'3:'.$columns[$data['manager_count']*3+1].'3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
    		$objPHPExcel->getActiveSheet()->setCellValue($columns[$data['manager_count']*3+1].'3', $filter_month . ' . ' . $filter_year);

    		$objPHPExcel->getActiveSheet()->getStyle('A6:'.$columns[$data['manager_count']*3+2].'6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    	}


    	$cntr = 0;
    	unset($one_manager);
    	foreach ($all_managers as $one_manager) {
    		$objPHPExcel->getActiveSheet()->getStyle($columns[2+$cntr*3].'5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    		$objPHPExcel->getActiveSheet()->getStyle($columns[2+$cntr*3].'5')->getFont()->setBold(true);

    		if (!$simple) {
    			$objPHPExcel->getActiveSheet()->mergeCells($columns[2+$cntr*3].'5:'.$columns[4+$cntr*3].'5');
    		}
    		$cntr++;
    	}

    	$cntr = 0;
    	$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
    	$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);

    	if (!$simple) {
    		$objPHPExcel->getActiveSheet()->mergeCells('A7:' . $columns[$data['manager_count']+2] .'7');
    	}

    	if ($simple) {
    		$tcounter = 0;
    		foreach ($data['data_by_countries'] as $one_country) {
                //итоги
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+8) . ':' . $columns[$data['manager_count']+4] . (string)($tcounter+8) . '')->getFont()->setBold(false);
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+8) . ':' . $columns[$data['manager_count']+4] . (string)($tcounter+8) . '')->getFont()->setSize(12);

                //страна
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']+4] . (string)($tcounter+10) . '')->getFont()->setBold(true);
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']+4] . (string)($tcounter+10) . '')->getFont()->setSize(12);
    			$objPHPExcel->getActiveSheet()->mergeCells('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']+4] . (string)($tcounter+10) . '');

    			$tcounter += 3;
    		}
    	} else {
    		$tcounter = 0;
    		foreach ($data['data_by_countries'] as $one_country) {
    			$tcounter += count($one_country);

                //итоги
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+8) . ':' . $columns[$data['manager_count']*3+4] . (string)($tcounter+8) . '')->getFont()->setBold(true);
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+8) . ':' . $columns[$data['manager_count']*3+4] . (string)($tcounter+8) . '')->getFont()->setSize(12);

                //страна
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']*3+4] . (string)($tcounter+10) . '')->getFont()->setBold(true);
    			$objPHPExcel->getActiveSheet()->getStyle('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']*3+4] . (string)($tcounter+10) . '')->getFont()->setSize(12);
    			$objPHPExcel->getActiveSheet()->mergeCells('A'. (string)($tcounter+10) . ':' . $columns[$data['manager_count']*3+4] . (string)($tcounter+10) . '');

    			$tcounter += 3;
    		}
    	}

    	unset($col);
    	foreach ($columns as $col) {
    		if ($col != 'A') {
    			for ($c = 1; $c <= $tcounter+10; $c++) {
    				$val = $objPHPExcel->getActiveSheet()->getCell($col.''.$c)->getValue();
    				if (is_numeric($val)) {
    					$objPHPExcel->getActiveSheet()->getStyle($col.''.$c)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
    				}
    			}
    		}
    	}

    	$objPHPExcel->getActiveSheet()->freezePane('A7');
    	unlink($tmpfile);


    	$dataname = $filter_month.'.'.$filter_year;
    	$filename = 'Otchet_po_zakrytym_zakazam_po_menegeram_'. $dataname . "_" . SITE_NAMESPACE .".xlsx";

    	if ($simple) {
    		$filename = 'Obshiy_oborot_po_zakazam_'. $dataname . "_" . SITE_NAMESPACE .".xlsx";
    	}


    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition: attachment;filename='.$filename);
    	header('Cache-Control: max-age=0');

    	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    	$writer->save('php://output');
    	exit();
    }

    public function countCustomerService($param_filter_month = false, $param_filter_year = false, $param_filter_manager = false, $param_return_data = false)
    {
    	$this->document->setTitle('Отчет по работе клиент-менеджмента за месяц');
    	$this->data['heading_title'] = 'Отчет по работе клиент-менеджмента за месяц';
    	$this->data['token'] = $this->session->data['token'];

    	$this->load->model('user/user');
    	$this->load->model('kp/work');
    	$this->load->model('user/user_group');
    	$this->load->model('catalog/actiontemplate');
    	$this->load->model('sale/coupon');
    	$this->load->model('sale/order');
    	$this->load->model('localisation/country');
    	$this->load->model('setting/setting');

    	$this->data['breadcrumbs'] = array();

    	$this->data['breadcrumbs'][] = array(
    		'text'      => $this->language->get('text_home'),
    		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
    	);

    	$this->data['breadcrumbs'][] = array(
    		'text'      => $this->language->get('heading_title'),
    		'href'      => $this->url->link('kp/salary', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
    	);

    	if (empty($this->request->get['filter_month'])) {
    		$filter_month = date('m', strtotime("-1 month"));
    	} else {
    		$filter_month = (int)$this->request->get['filter_month'];
    	}

    	if (empty($this->request->get['filter_year'])) {
    		$filter_year = date('Y');
    	} else {
    		$filter_year = (int)$this->request->get['filter_year'];
    	}

    	$this->data['managers'] = $this->model_user_user->getUsersByGroup(14);

    	if (empty($this->request->get['filter_manager'])) {
    		$filter_manager = $this->data['managers'][0]['user_id'];
    	} else {
    		$filter_manager = (int)$this->request->get['filter_manager'];
    	}

    	if ($param_filter_month) {
    		$filter_month = $param_filter_month;
    	}

    	if ($param_filter_year) {
    		$filter_year = $param_filter_year;
    	}

    	if ($param_filter_manager) {
    		$filter_manager = $param_filter_manager;
    	}


    	$this->data['cstats'] = array(
    		'total_days' => $this->model_kp_work->countDays($filter_manager, $filter_month, $filter_year),
    		'total_time' => $this->secToHR($this->model_kp_work->countTimeDiffSum($filter_manager, $filter_month, $filter_year)),
    		'total_actions' => $this->model_kp_work->countFieldSum($filter_manager, 'daily_actions', $filter_month, $filter_year),

    		'total_emails' => $this->model_kp_work->countFieldSum($filter_manager, 'sent_mail_count', $filter_month, $filter_year),
    		'total_bdays' => $this->model_kp_work->countFieldSum($filter_manager, 'edit_birthday_count', $filter_month, $filter_year),
    		'total_csi' => $this->model_kp_work->countFieldSum($filter_manager, 'edit_csi_count', $filter_month, $filter_year),

    		'total_inbound' => $this->model_kp_work->countFieldSum($filter_manager, 'inbound_call_count', $filter_month, $filter_year),
    		'total_inbound_duration' => $this->secToHR($this->model_kp_work->countFieldSum($filter_manager, 'inbound_call_duration', $filter_month, $filter_year)),
    		'total_outbound' => $this->model_kp_work->countFieldSum($filter_manager, 'outbound_call_count', $filter_month, $filter_year),
    		'total_outbound_duration' => $this->secToHR($this->model_kp_work->countFieldSum($filter_manager, 'outbound_call_duration', $filter_month, $filter_year)),
    	);

    	$this->data['promocodes'] = array();

    	$results = $this->model_kp_work->getPromoCodes('clientmanager', $filter_manager);


    	$total_good_orders = 0;
    	$total_bad_orders = 0;
    	$total_good_sum = array();
    	$orders_by_country = array();
    	foreach ($results as $result) {
    		$actiontemplate = $this->model_catalog_actiontemplate->getActionTemplateName($result['actiontemplate_id']);
    		$actiontemplate_count_total = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateSendCountByUser($result['actiontemplate_id'], $filter_manager):'';
    		$actiontemplate_count = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateSendCountByMonth($result['actiontemplate_id'], $filter_manager, $filter_month, $filter_year):'';
    		$actiontemplate_opened = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateSendParamsCountByMonth($result['actiontemplate_id'], $filter_manager, $filter_month, $filter_year, 'mail_opened'):'';
    		$actiontemplate_clicked = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateSendParamsCountByMonth($result['actiontemplate_id'], $filter_manager, $filter_month, $filter_year, 'mail_clicked'):'';
    		$actiontemplate_dates = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateDatesCount($result['actiontemplate_id']):0;

    		$usage_good = $this->model_sale_coupon->getGoodCouponUsage($result['code']);
    		$usage_bad = $this->model_sale_coupon->getBadCouponUsage($result['code']);

    		$usage_good_month = $this->model_sale_coupon->getGoodCouponUsageForMonth($result['code'], $filter_month, $filter_year);
    		$usage_bad_month = $this->model_sale_coupon->getBadCouponUsageForMonth($result['code'], $filter_month, $filter_year);


    		$orders = array();

    		$_rorders = $this->model_sale_coupon->getGoodCouponOrdersForMonth($result['code'], $filter_month, $filter_year);

    		if ($_rorders) {
    			foreach ($_rorders as $_rorder_id) {
    				$_rorder = $this->model_sale_order->getOrder($_rorder_id['order_id']);

    				if (!isset($orders[$_rorder['store_id']])) {
    					$orders[$_rorder['store_id']] = array();
    					$orders[$_rorder['store_id']]['orders'] = array();
    					$orders[$_rorder['store_id']]['total'] = 0;
    					$orders[$_rorder['store_id']]['total_text'] = '';
    					$orders[$_rorder['store_id']]['country_name'] = $this->model_localisation_country->getCountryName($this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $_rorder['store_id']));
    				}

    				$ototal = $this->getOrderTotalTotal($_rorder['order_id']);

    				$orders[$_rorder['store_id']]['orders'][] = array(
    					'order_id' => $_rorder['order_id'],
    					'date_added' => date('Y.m.d', strtotime($_rorder['date_added'])),
    					'date_closed' => date('Y.m.d', strtotime($_rorder_id['date_added'])),
    					'salary_paid' => (int)$_rorder['salary_paid'],
    					'url'    => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $_rorder['order_id'], 'SSL'),
    					'total'    => $ototal,
    					'total_text'  => $this->currency->format($ototal, $_rorder['currency_code'], 1)
    				);

    				$orders[$_rorder['store_id']]['total'] += $ototal;
    				$orders[$_rorder['store_id']]['total_text'] = $this->currency->format($orders[$_rorder['store_id']]['total'], $_rorder['currency_code'], 1);
    			}
    		}

    		$this->data['promocodes'][] = array(
    			'code'       => $result['code'],

    			'actiontemplate'   => $actiontemplate,
    			'actiontemplate_count'   => $actiontemplate_count,
    			'actiontemplate_date_from' => date('Y.m.d', strtotime($actiontemplate_dates['min_date'])),

                //конверсия за месяц
    			'actiontemplate_opened'   => $actiontemplate_opened,
    			'conv_actiontemplate_opened' => $actiontemplate_count?min(round(($actiontemplate_opened / $actiontemplate_count) * 100), 100):0,
    			'cao_class' => $actiontemplate_count?$this->tdClass(min(round(($actiontemplate_opened / $actiontemplate_count) * 100), 100), 40, 60, 100):'',

    			'actiontemplate_clicked'   => $actiontemplate_clicked,
    			'conv_actiontemplate_clicked' => $actiontemplate_count?min(round(($actiontemplate_clicked / $actiontemplate_count) * 100), 100):0,
    			'cac_class' => $actiontemplate_count?$this->tdClass(min(round(($actiontemplate_clicked / $actiontemplate_count) * 100), 100), 40, 60, 100):'',


                //конверсия за все время
    			'usage_good'      => $usage_good,
    			'conv_usage_good' => $actiontemplate_count?min(round(($usage_good / $actiontemplate_count_total) * 100, 2), 100):0,
    			'ug_class' => $actiontemplate_count_total?$this->tdClass(min(round(($usage_good / $actiontemplate_count_total) * 100, 2), 100), 1, 5, 10):'',

    			'usage_bad'      => $usage_bad,
    			'conv_usage_bad' => $actiontemplate_count?min(round(($usage_bad / $actiontemplate_count_total) * 100, 2), 100):0,
    			'ub_class' => $actiontemplate_count_total?$this->tdClass(min(round(($usage_bad / $actiontemplate_count_total) * 100, 2), 100), 1, 5, 10):'',


    			'usage_good_month'      => $usage_good_month,

                //конверсия за все месяц
    			'usage_bad_month'      => $usage_bad_month,
    			'conv_usage_bad_month' => $actiontemplate_count?min(round(($usage_bad_month / $actiontemplate_count) * 100, 2), 100):0,
    			'ubm_class' => $actiontemplate_count?$this->tdClass(min(round(($usage_bad_month / $actiontemplate_count) * 100, 2), 100), 1, 5, 10):'',

    			'orders' => $orders,

    		);

    		$total_good_orders = 0;
    		$total_bad_orders = 0;
    		$total_good_sum = array();
    		$orders_by_country = array();
    		foreach ($this->data['promocodes'] as $promocode) {
    			$total_good_orders += $promocode['usage_good_month'];
    			$total_bad_orders += $promocode['usage_bad_month'];

    			foreach ($promocode['orders'] as $store_id => $country) {
    				if (!isset($total_good_sum[$country['country_name']])) {
    					$total_good_sum[$country['country_name']] = array();
    					$total_good_sum[$country['country_name']]['total'] = 0;
    					$total_good_sum[$country['country_name']]['text'] = 0;
    				}

    				if (!isset($orders_by_country[$country['country_name']])) {
    					$orders_by_country[$country['country_name']] = array();
    				}

    				foreach ($country['orders'] as &$_o) {
    					$_o['promocode'] = $promocode['code'];
    					$orders_by_country[$country['country_name']][] = $_o;
    					$total_good_sum[$country['country_name']]['total'] += $_o['total'];
    					$total_good_sum[$country['country_name']]['text'] =
    					$this->currency->format($total_good_sum[$country['country_name']]['total'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id), 1);
    				}
    			}
    		}
    	}

    	$this->data['cstats']['total_good_orders'] = $total_good_orders;
    	$this->data['cstats']['total_bad_orders'] = $total_bad_orders;
    	$this->data['cstats']['total_good_sum'] = $total_good_sum;
    	$this->data['orders_by_country'] = $orders_by_country;

    	$this->data['has_rights'] = $this->user->getISAV() || $this->user->canUnlockOrders();


    	$this->data['filter_year'] = $filter_year;
    	$this->data['filter_month'] = $filter_month;
    	$this->data['filter_manager'] = $filter_manager;


    	$this->data['token'] = $this->session->data['token'];
    	$this->data['month'] = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь','Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');

    	$do_pdf = isset($this->request->get['do_pdf']) && $this->request->get['do_pdf'];

    	if (!$do_pdf) {
    		$this->template = 'user/salary_cm.tpl';
    		$this->children = array(
    			'common/header',
    			'common/footer'
    		);

    		$this->response->setOutput($this->render());
    	} else {
    		$user_name = $this->model_user_user->getRealUserNameById($filter_manager);
    		$this->data['user_name'] = $user_name;

    		if ($filter_month == 1) {
    			$_f = 12;
    		} else {
    			$_f = ($filter_month - 1);
    		}
    		if ($filter_month < 10) {
    			$filter_month = '0'.$filter_month;
    		}

                //$dataname = $this->data['month'][$_f].' '.$filter_year;
    		$dataname = $filter_month.$filter_year;

    		$this->data['dataname']     =   $filter_month . ' месяц ' . $filter_year . ' год';
    		$this->data['dataname2']    =   $filter_month .' месяц '.$filter_year . ' год';

    		$this->template = 'user/salary_cm_pdf.tpl';
    		$html = $this->render();

    		$Mpdf = new \Mpdf\Mpdf([
    			'mode' => 'utf-8',
    			'format' => 'A4'
    		]);

    		$Mpdf->charset_in = 'utf-8';

    		$html = str_replace(' !important', '', $html);
    		$Mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);


    		$filename = prepareFileName($user_name .' '. SITE_NAMESPACE .'.pdf');

    		$Mpdf->Output($filename, 'D');
    	}

    	$this->response->setOutput($this->render());
    }

    public function updateCurrencyForRecounts()
    {
    	$currency = $this->request->post['currency'];
    	$value = $this->request->post['value'];

    	if ($currency && $value) {
    		$this->db->query("UPDATE currency SET value_uah_unreal = '" . (float)$value . "' WHERE code = '" . $this->db->escape(trim($currency)) . "'");
    		$this->cache->flush();
    	}
    }

    public function kpicounterCron()
    {

    	echoLine('kpCounter');

    	$this->load->model('kp/work');
    	$this->session->data['token'] = 'token-chpoken-compat';

    	$this->db->query("UPDATE `order` SET closed = 1 WHERE order_status_id IN (". $this->config->get('config_complete_status_id') .", " . $this->config->get('config_cancelled_status_id') . ")");

    		$managers = $this->model_kp_work->getManagersWhoClosedOrdersForMonth(date('m'), date('Y'));

    		foreach ($managers as $manager) {
    			$data = $this->countManagers($param_filter_month = date('m'), $param_filter_year = date('Y'), $param_filter_manager = $manager['user_id'], $param_return_data = true, $param_filter_dzioev_bug = false, $param_filter_count_time = false, $param_filter_count_time_days = false);

    			$this->model_kp_work->addManagerKPIHistory($manager['user_id'], $data['cstats']['kpi_params']);
    		}
    	}

    	private function getList()
    	{
    	}
    }
