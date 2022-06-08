<?
class ControllerApiBitrixBot extends Controller { 

	private $array_colors = array(
		'#ecafa9',
		'#d4ffaa',
		'#abce8e',
		'#dbd5a2',
		'#e0edd5',
		'#cccccc',
		'#aaff56',
		'#ff5656',
		'#007fff',
		'#999999',
		'#ffff00',
		'#7fff00',
		'#ffaa56',
		'#f990c3',
		'#ff7f00',
		'#ffd4aa',
		'#a2daf2'
	);

	public function index(){

		$bitrixLog = new Log('bitrix24bot.txt');

		if ($this->request->request['event'] == 'ONIMBOTDELETE') {
			if (!$this->Bitrix24->checkAuth()){
				die();
			}		

			$this->Bitrix24->saveParams($appsConfig);

	} 
	elseif ($this->request->request['event'] == 'ONAPPINSTALL') {
		// handler for events
		$handlerBackUrl = HTTP_CATALOG . 'api/bitrixbot';
		
		$result = $this->Bitrix24->restCommand('imbot.register', array(
		'CODE'                  => 'IIBot',
		'TYPE'                  => 'B',
		'EVENT_MESSAGE_ADD'     => $handlerBackUrl,
		'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
		'EVENT_BOT_DELETE'      => $handlerBackUrl,
		'PROPERTIES'            => array(
		'NAME'              => 'Хоботун',
		'LAST_NAME'         => '',
		'COLOR'             => 'AQUA',
		'EMAIL'             => $this->config->get('config_email'),		
		'PERSONAL_BIRTHDAY' => '2016-03-23',	// День рождения в формате YYYY-mm-dd
		'WORK_POSITION'     => 'Я - самый добрый бот Хоботун',	// Занимаемая должность, используется как описание бота
		'PERSONAL_WWW'      => '',							// Ссылка на сайт					
		'PERSONAL_GENDER'   => 'M',               // Пол бота, допустимые значения M -  мужской, F - женский, пусто если не требуется указывать					
		'PERSONAL_PHOTO'    => base64_encode(file_get_contents( DIR_CATALOG . 'icon/bitrixbotlogo.png' )), // Аватар бота - base64						
	),
	),
		$this->request->request["auth"]);
		
		$appsConfig[$this->request->request['auth']['application_token']] = array(
			'BOT_ID'      => $result['result'],
			'LANGUAGE_ID' => $this->request->request['data']['LANGUAGE_ID'],
			'AUTH'        => $this->request->request['auth']
		);
		$this->Bitrix24->saveParams($appsConfig);
		
		
		$botId = $result['result'];
		
		//UPDATE
		$result = $this->Bitrix24->restCommand('imbot.update', Array(

		'BOT_ID' => $botId, // Идентификатор чат-бота, которого нужно изменить (обяз.)
		'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
		'FIELDS' => Array( // Данные для обновления (обяз.)			            
		'PROPERTIES' => Array( // Обязательное при обновлении данных бота
		'NAME' => 'Хоботун', // Имя чат-бота 
		'WORK_POSITION'     => 'Я - самый добрый бот Хоботун',
		'PERSONAL_BIRTHDAY' => '2016-03-12', // День рождения в формате YYYY-mm-dd	
		'PERSONAL_PHOTO' => base64_encode(file_get_contents( __DIR__ .'/botlogo.png')), // Аватар бота - base64		
	)
	)
		
	), $this->request->request["auth"]);

		$result = $this->Bitrix24->restCommand('imbot.command.register', Array(				
		'BOT_ID' => $botId, // Идентификатор чат-бота владельца команды
		'COMMAND' => 'нал', // Текст команды, которую пользователь будет вводить в чатах
		'COMMON' => 'Y', // Если указан Y, то команда доступна во всех чатах, если N - то доступна только в тех, где присутствует чат-бот
		'HIDDEN' => 'N', // Скрытая команда или нет - по умолчанию N
		'EXTRANET_SUPPORT' => 'N', // Доступна ли команда пользователям Экстранет, по умолчанию N
		'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
		'LANG' => Array( // Массив переводов, желательно указывать, как минимум, для RU и EN
			Array(
				'LANGUAGE_ID' => 'ru',
				'TITLE' => 'Получить наличие по складам на товар', 
		'PARAMS' => 'Код/артикул/штрихкод товара'), // Язык, описание команды, какие данные после команды нужно вводить.
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl, // Ссылка на обработчик для команд
		
	), $this->request->request["auth"]);

	/*
		$result = restCommand('imbot.command.update', Array(
		
		'COMMAND_ID' => 39, 
		'FIELDS' => Array(	
		'LANG' => Array( // Массив переводов, желательно указывать, как минимум, для RU и EN
		Array(
		'LANGUAGE_ID' => 'ru',
		'TITLE' => 'Получить наличие по складам на товар', 
		'PARAMS' => 'Код/артикул/штрихкод товара'), // Язык, описание команды, какие данные после команды нужно вводить.
		),
		)
		), $_REQUEST["auth"]);
	*/
		
		$result = $this->Bitrix24->restCommand('imbot.command.register', Array(				
		'BOT_ID' => $botId, // Идентификатор чат-бота владельца команды
		'COMMAND' => 'гдезаказ', // Текст команды, которую пользователь будет вводить в чатах
		'COMMON' => 'Y', // Если указан Y, то команда доступна во всех чатах, если N - то доступна только в тех, где присутствует чат-бот
		'HIDDEN' => 'N', // Скрытая команда или нет - по умолчанию N
		'EXTRANET_SUPPORT' => 'N', // Доступна ли команда пользователям Экстранет, по умолчанию N
		'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
		'LANG' => Array( // Массив переводов, желательно указывать, как минимум, для RU и EN
			Array(
				'LANGUAGE_ID' => 'ru',
				'TITLE' => 'Трекинг заказа', 
		'PARAMS' => 'Код заказа в админке'), // Язык, описание команды, какие данные после команды нужно вводить.
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl, // Ссылка на обработчик для команд
		
	), $this->request->request["auth"]);
		
		$result = $this->Bitrix24->restCommand('imbot.command.register', Array(				
		'BOT_ID' => $botId, // Идентификатор чат-бота владельца команды
		'COMMAND' => 'курс', // Текст команды, которую пользователь будет вводить в чатах
		'COMMON' => 'Y', // Если указан Y, то команда доступна во всех чатах, если N - то доступна только в тех, где присутствует чат-бот
		'HIDDEN' => 'N', // Скрытая команда или нет - по умолчанию N
		'EXTRANET_SUPPORT' => 'N', // Доступна ли команда пользователям Экстранет, по умолчанию N
		'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
		'LANG' => Array( // Массив переводов, желательно указывать, как минимум, для RU и EN
			Array(
				'LANGUAGE_ID' => 'ru',
				'TITLE' => 'получить курсы валют и посмотреть, правильно ли они заданы', 
		'PARAMS' => ''), // Язык, описание команды, какие данные после команды нужно вводить.
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl, // Ссылка на обработчик для команд
		
	), $this->request->request["auth"]);
		
		$result = $this->Bitrix24->restCommand('imbot.command.register', Array(				
		'BOT_ID' => $botId, // Идентификатор чат-бота владельца команды
		'COMMAND' => 'ссылка', // Текст команды, которую пользователь будет вводить в чатах
		'COMMON' => 'Y', // Если указан Y, то команда доступна во всех чатах, если N - то доступна только в тех, где присутствует чат-бот
		'HIDDEN' => 'N', // Скрытая команда или нет - по умолчанию N
		'EXTRANET_SUPPORT' => 'N', // Доступна ли команда пользователям Экстранет, по умолчанию N
		'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
		'LANG' => Array( // Массив переводов, желательно указывать, как минимум, для RU и EN
			Array(
				'LANGUAGE_ID' => 'ru',
				'TITLE' => 'укоротить ссылку', 
		'PARAMS' => 'Ссылка, которую необходимо укоротить'), // Язык, описание команды, какие данные после команды нужно вводить.
		),
		'EVENT_COMMAND_ADD' => $handlerBackUrl, // Ссылка на обработчик для команд
		
	), $this->request->request["auth"]);
		
		$bitrixLog->write($result);		
		
	} 	
	// receive event "open private dialog with bot" or "join bot to group chat"
	elseif ($this->request->request['event'] == 'ONIMBOTJOINCHAT') {		
		if (!$this->Bitrix24->checkAuth()){
			die();
		}
		
		if (isset($this->request->request['data']['USER'])) {
			$this->addToChat($bitrix_user = $this->request->request['data']['USER']);			
		}
		
	}
	elseif ($this->request->request['event'] == 'ONIMBOTMESSAGEADD') {	
		if (!$this->Bitrix24->checkAuth()){
			die();
		}

		$this->parseMessage($message = $this->request->request['data']['PARAMS']['MESSAGE']);				
	} 
	elseif ($this->request->request['event'] == 'ONIMCOMMANDADD')
	{
		if (!$this->Bitrix24->checkAuth()){
			die();
		}
		
		$bitrixLog->write($this->request->request['data']);
		$this->parseCommand($command = $this->request->request['data']['COMMAND']);			
	} 



}

private function checkWorkTime(){
	$current_hour = (int)date('H');
	$current_day = (int)date('w');

	if ($current_day == 6 || $current_day == 0){
		return false;
	} else {
		return ($current_hour >= 9 && $current_hour <= 18);
	}									
}

public function parseMessage($message){
	$this->load->model('kp/bitrixBot');

	strtolower($message);

			//$arReport = getAnswer($this->request->request['data']['PARAMS']['MESSAGE'], $this->request->request['data']['PARAMS']['FROM_USER_ID']);
	$arReport['attach'][] = array("MESSAGE" => ' :?: я типа не сильно понимаю, чего вам надо, обратитесь к моему создателю');

	$this->model_kp_bitrixBot->sendMessage('Шо-то тут не то', $arReport['attach']);
}	

public function parseCommand($commands = array(), $param = ''){
	$this->load->model('kp/bitrixBot');
	$this->load->model('sale/order');
	$this->load->model('catalog/product');
	$this->load->model('localisation/country');

	foreach ($commands as $command)
	{

		$attach = array();
		$reply = '';

		if ($command['COMMAND'] == 'нал')
		{

			$attach = array();

					//попытаемся угадать, это заказ или товары
			$params = trim($command['COMMAND_PARAMS']);
			$params_array = explode(' ', $params);

			if (!$params || !$params_array){
				$reply = 'Кажется, вы ошиблись :(';
				$attach[] = array(
					'MESSAGE' => 'Надо указать номер заказа, либо кода/артикулы/штрихкоды товаров через пробел'
				);
			} elseif (count($params_array) == 1){
						//проверим, заказ ли это
				if (false){
					$reply = 'Заказ ' . $order['order_id'];						
					$products = $this->model_sale_order->getOrderProducts($order['order_id']);						
					$countries = $this->model_localisation_country->getCountries();

					foreach ($products as $product){
						$real_product = $this->model_catalog_product->getProduct($product['product_id']);

						$stock_msg = '';
						$count_in_stock = 0;
						foreach ($countries as $country){
							if ($country['iso_code_2'] == 'DE' || $country['country_id'] == $order['shipping_country_id']) {
								if ($real_product[$country['warehouse_identifier']] > 0){
									$count_in_stock += 1;

									$partly = false;
									if ($real_product[$country['warehouse_identifier']] < $product['quantity']){
										$partly = true;
									}

									if (!$stock_msg){
										$stock_msg = $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
										if ($partly) {
											$stock_msg = ' из ' . $product['quantity'];
										}
										$stock_msg .= ' шт.';
									} else {
										$stock_msg .= ' и в ' . $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
										if ($partly) {
											$stock_msg = ' из ' . $product['quantity'];
										}
										$stock_msg .= ' шт.';
									}
								}																
							}																
						}

						if (!$stock_msg){
							$stock_msg = 'На складах в наличии нет';
						}

						if ($stock_msg){
							$attach[] = array(
								'MESSAGE' => $product['model'] . ' ' .$stock_msg
							);
						}													
					}

					if ($count_in_stock == count($products)){
						$reply .= ' - ' . 'все есть в наличии';
					} elseif ($count_in_stock == 0) {
						$reply .= ' - ' . 'ничего нет в наличии';
					} elseif ($count_in_stock < count($products)) {
						$reply .= ' - ' . 'часть есть в наличии';
					} 


				} else {

					$data = array('filter_name' => trim($params_array[0]), 'limit' => 1, 'start' => 0, 'filter_status' => 1, 'exact_model' => 1);
					$results = $this->model_catalog_product->getProducts($data);

					if ($results && isset($results[0])){
						$real_product = $results[0];

						$reply = 'Проверка наличия по товару ' . $real_product['model'] .' ([I]'. $real_product['name'].'[/I])';

						$stock_msg = '';

						$countries = $this->model_localisation_country->getCountries();
						foreach ($countries as $country){							
							if ($real_product[$country['warehouse_identifier']] > 0){																																		
								if (!$stock_msg){
									$stock_msg = $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
									$stock_msg .= ' шт.';
								} else {
									$stock_msg .= ' и в ' . $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
									$stock_msg .= ' шт.';
								}
							}																																
						}

						if (!$stock_msg){
							$stock_msg = 'На наших складах в наличии нет';
						}

						if ($stock_msg){
							$attach[] = array(
								'MESSAGE' => $stock_msg,
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							);

							$attach[] = array(
								"DELIMITER" => Array(
									'SIZE' => 200,
									'COLOR' => "#c6c6c6"
								));
						}	

								//локальные поставщики
						$this->load->model('sale/supplier');
						$local_suppliers = $this->model_sale_supplier->getSupplierStocks($real_product['product_id']);

						if ($local_suppliers){
							$local_supplier_msg = "";
							foreach ($local_suppliers as $local_supplier){
								if ($local_supplier['stock']){
									$local_supplier_msg .= " :idea: ";
								}
								$local_supplier_msg .= ' [B]' . $local_supplier['supplier_name'] . '[/B]: ';	
								$local_supplier_msg .= ' Закупка: [B]' . $this->currency->format($local_supplier['price'], $local_supplier['currency'], 1). '[/B]';
								$local_supplier_msg .= ' РРЦ: [B]' . $this->currency->format($local_supplier['price_recommend'], $local_supplier['currency'], 1). '[/B]';
								$local_supplier_msg .= ', нал: ' . $local_supplier['stock'];										
							}																		
						}

						if (isset($local_supplier_msg) && $local_supplier_msg){
							$attach[] = array(
								'MESSAGE' => $local_supplier_msg,
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							);

							$attach[] = array(
								"DELIMITER" => Array(
									'SIZE' => 200,
									'COLOR' => "#c6c6c6"
								));
						}	

								//поставщики
						$this->load->model('kp/price');
						$r = $this->model_kp_price->getProductPriceFromSources($real_product['product_id']);
						$suppliers = $r['result'];

						if ($suppliers) {
							foreach ($suppliers as $supplier){
								$supplier_msg = '[B]' . $supplier['supplier_name'] . '[/B]: ';									
								if( isset($supplier['fail']) && $supplier['fail'] ){
									$supplier_msg .= ' ошибка получения';
								} else {
									$supplier_msg .= ' цена: ' . $this->currency->format($supplier['price'], 'EUR', 1);
									if ((float)number_format($supplier['special'], 1, '.', ' ')){
										$supplier_msg .= ', скидка: [B]' . $this->currency->format($supplier['special'], 'EUR', 1) . '[/B]';
									}
									$supplier_msg .= ', нал: ' . $supplier['stock'];
								}

								$attach[] = array(
									'MESSAGE' => $supplier_msg,
									'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
								);
							}
						} else {
							$attach[] = array(
								'MESSAGE' => 'Поставщики не настроены, либо нет доступа',
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							);

						}



					} else {
						$reply = 'Я не смог найти этот товар';
					}

				}
			} elseif (count($params_array) > 1) {
				foreach ($params_array as $code){
					$reply = 'Проверка наличия по товарам';


					$attach[] = array(
						'MESSAGE' => 'Множественная проверка пока в разработке'
					);
				}				
			} else {

				$reply = 'Что-то я не могу разобрать :(';
				$attach[] = array(
					'MESSAGE' => 'Надо указать товар, либо кода/артикулы/штрихкоды товаров через пробел'
				);

			}


			$this->model_kp_bitrixBot->answerCommand($reply, $attach, $command['COMMAND_ID'], $command['MESSAGE_ID']);							
		} elseif ($command['COMMAND'] == 'гдезаказ'){

			try {

				$attach = array();

				$params = trim($command['COMMAND_PARAMS']);
				$params_array = explode(' ', $params);

				if (!$params || !$params_array){
					$reply = ':!: Кажется, вы ошиблись :(';
					$attach[] = array(
						'MESSAGE' => 'Надо указать номер заказа.'
					);
				} elseif (count($params_array) == 1){

					$this->load->model('kp/info1c');
					$order1c = $this->model_kp_info1c->getOrderTrackerXML(trim($params_array[0]));

					if (!$order1c){

						$order = $this->model_sale_order->getOrder(trim($params_array[0]));

						if ($order) {

							$reply = ":!: Кажется, вы не угадали с номером заказа...";
							$attach[] = array(
								'MESSAGE' => 'Заказа нет в 1Ске, но он есть в админке. Ждите, пока загрузится.'
							);

						} else {

							$reply = ":!: Кажется, вы не угадали с номером заказа...";
							$attach[] = array(
								'MESSAGE' => 'Проверьте, пожалуйста, я не могу найти этот заказ в 1С, да и в админке тоже не вижу..'
							);

						}

					} else {

						$attach = array();
						$this->load->model('sale/order');
						$this->load->model('user/user');
						$this->load->model('catalog/product');
						$this->load->model('localisation/order_status');

						$order = $this->model_sale_order->getOrder(trim($params_array[0]));
						$order_status = $this->model_localisation_order_status->getOrderStatusName($order['order_status_id']);
						$order_manager_bitrix_id = $this->model_user_user->getUserBitrixIDByID($order['manager_id']);

						$order1c = $order1c["Документ"];			
						$reply = "Заказ [B]#". $order1c['ИД'] . "[/B], от ". date('Y.m.d', strtotime($order1c['ДатаЗаказа']));		
						$reply .= "[BR]";
						$reply .= "Сейчас в статусе: [B]" . $order_status . "[/B]";

						if ($order_manager_bitrix_id){
							$reply .= "[BR]";
							$reply .= "Ответственный: [USER=$order_manager_bitrix_id]" . $this->model_user_user->getRealUserNameById($order['manager_id']) . "[/USER]";
						}

						if ($order['wait_full']){
							$reply .= "[BR]";
							$reply .= " :!: Клиент готов ждать полной комплектации этого заказа";
						}

						$date_promiced = strtotime("+1 day");
						if ($order['date_delivery_actual'] != '0000-00-00'){
							$reply .= "[BR]";
							$reply .= 'Договорились о доставке на [B]' . date('Y.m.d', strtotime($order['date_delivery_actual'])). "[/B]";
							$date_promiced = strtotime($order['date_delivery_actual']);
						} elseif ($order['date_delivery_to'] != '0000-00-00'){
							$reply .= "[BR]";
							$reply .= 'Пообещали доставить до [B]' . date('Y.m.d', strtotime($order['date_delivery_to'])) . "[/B]";	
							$date_promiced = strtotime($order['date_delivery_to']);
						}

						if ($date_promiced < strtotime("-1 day") && !$order['closed']){
									//	$reply .= "[BR]";
							$reply .= ', и срок доставки [B]эпично[/B] зафейлен :facepalm:';	
						}

						$grid = array();

						if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])){
							$_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
							unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
							$order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
						}

						foreach ($order1c['ОбщееСостояниеЗаказа']['Товар'] as $product){

							$msg = '';

							if (!$product['Наименование']){
								$real_product = $this->model_catalog_product->getProduct($product['Код']);
								if ($real_product){
									$product['Наименование'] = $real_product['name'];
								}
							}

									/*
										$order_product = $this->model_sale_order->findOrderProductsLine($order['order_id'], trim($product['Код']), trim($product['ЗаказаноПокупателем']));
										if ($order_product){
										if ($order_product['distinct']){
										if ($order_product['row']['part_num']){
										$msg = ' :like: Товар в партии: [B]' . $order_product['row']['part_num'].'[/B]';
										}
										}
										}
									*/

										if ($msg != ''){$msg .= "[BR]";}

										$is_closed = ($order['order_status_id'] == $this->config->get('config_complete_status_id') || $order['order_status_id'] == $this->config->get('config_cancelled_status_id'));

									//уже доставлено, строка частично у нас доставлена быть не может
										if ($product['ДоставленоПокупателю'] > 0) {

											if ($product['ДоставленоПокупателю'] == $product['ЗаказаноПокупателем']){
												$msg .= ":idea: Уже все доставили покупателю";
												$is_closed = true;
											} else {
												$msg .= ":idea: Доставили покупателю " . $product['ДоставленоПокупателю'] . " из " . $product['ЗаказаноПокупателем'];
											}
										//пока ничего не доставили	
										}

									//часть товара уже лежит на складе
										$is_from_free = false;
										if ($product['ОжидаютОтгрузкиПокупателю'] > 0) {

											$msg .= ":idea: " . $product['ОжидаютОтгрузкиПокупателю'] . " шт. ждет доставки покупателю";

											if (isset($product['ОжидаютОтгрузкиПокупателюДетально'])){
												$_mmsg = array();
												$free_counter = 0;
												foreach ($product['ОжидаютОтгрузкиПокупателюДетально'] as $partie){

													if (!isset($partie["Номер"]) && isset($partie[0]) && is_array($partie[0])){
														foreach ($partie as $subpartie){
															$_mmsg[] = "[I][B]" . $subpartie['Номер'] . "[/B]: " . $subpartie['Количество'] . " шт.[/I]";
														}
													}

													if (isset($partie["ЭтоСвободныеОстатки"]) && $partie["ЭтоСвободныеОстатки"]){
														$free_counter += $partie['Количество'];
													}

													if (isset($partie["Номер"]) && isset($partie['Количество'])){												
														$_mmsg[] = "[I][B]" . $partie['Номер'] . "[/B]: " . $partie['Количество'] . " шт.[/I]";
													}


													if (count($_mmsg)){
														$msg .= " (".implode(', ', $_mmsg).")";
													}

												}
											}

											$is_from_free = ($product['ОжидаютОтгрузкиПокупателю'] == $free_counter);

										} 

									//часть товара уже едет
										if ($product['ВПути'] > 0) {

											if ($msg != ''){$msg .= "[BR]";}
											$msg .= ":) " . $product['ВПути'] . " шт. уже едет из Германии";

											if (isset($product['ВПутиДетально'])){
												$_mmsg = array();

												foreach ($product['ВПутиДетально'] as $partie){

													if (!isset($partie["Номер"]) && isset($partie[0]) && is_array($partie[0])){
														foreach ($partie as $subpartie){
															$_mmsg[] = "[I][B]" . $subpartie['Номер'] . "[/B]: " . $subpartie['Количество'] . " шт.[/I]";
														}
													}

													if (isset($partie["ЭтоСвободныеОстатки"]) && $partie["ЭтоСвободныеОстатки"]){
														$free_counter += $partie['Количество'];
													}

													if (isset($partie["Номер"]) && isset($partie['Количество'])){												
														$_mmsg[] = "[I][B]" . $partie['Номер'] . "[/B]: " . $partie['Количество'] . " шт.[/I]";
													}


													if (count($_mmsg)){
														$msg .= " (".implode(', ', $_mmsg).")";
													}

												}
											}

										} 

									//часть товара приехало в Германию
									//часть товара ожидает отгрузки из Германии
										if ($product['ОжидаютОтгрузкиИзГермании'] > 0) {

											if ($msg != ''){$msg .= "[BR]";}
											$msg .= ":) " . $product['ОжидаютОтгрузкиИзГермании'] . " шт. лежат на складе Германии, ждет отправки";

											if ($product['ОсталосьОтПоставщика'] > 0){
												if ($msg != ''){$msg .= "[BR]";}
												$msg .= ":!:  Еще осталось получить от поставщика " . $product['ОсталосьОтПоставщика'];
											}


										} 		

										if ($product['ОсталосьОтПоставщика'] > 0) { 

											if ($msg != ''){$msg .= "[BR]";}
											$msg .= "8) " . $product['ОсталосьОтПоставщика'] . " шт. заказано у поставщика, ждем поступления";

										}

										if (!$is_closed && ($product['ВПути'] + $product['ОжидаютОтгрузкиИзГермании'] + $product['ПоступилоОтПоставщика'] + $product['ОжидаютОтгрузкиПокупателю'] == 0)){
											if ($product['ЗаказаноУПоставщика'] > 0) { 

												if ($msg != ''){$msg .= "[BR]";}
												$msg .= "8) " . $product['ЗаказаноУПоставщика'] . " шт. заказано у поставщика, ждем поступления";

											}
										}

										if (!$is_closed && !$is_from_free) {
										//пока закупщик даже не садился за работу:)
											if ($product['ЗаказаноУПоставщика'] == 0 && $product['ОжидаютОтгрузкиПокупателю'] == 0){

												if ($msg != ''){$msg .= "[BR]";}
												$msg .= ":?: пока что не заказано у поставщика";									

											}														
										}


										$grid[] = Array(
											"NAME" => "" . $product["Наименование"] . " (" . trim($product["Артикул"]) . ") " . $product["ЗаказаноПокупателем"] .' шт. '. $this->currency->format(str_replace(',','.',$product["Цена"]), $order['currency_code'],'1'),
											"VALUE" => $msg,
											"DISPLAY" => "BLOCK",									
										);

									}

									$attach[] = Array(
										"GRID" => $grid,
										'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
									);

								}
							}

							$this->model_kp_bitrixBot->answerCommand($reply, $attach, $command['COMMAND_ID'], $command['MESSAGE_ID']);

						}  catch (Exception $e){	

							$reply = ":cry: Хоботун приболел немножко";

							$attach[] = array(
								'MESSAGE' => $e->getMessage(),
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							);

							$this->model_kp_bitrixBot->answerCommand($reply, $attach, $command['COMMAND_ID'], $command['MESSAGE_ID']);
						}			

					} elseif ($command['COMMAND'] == 'курс'){
						$this->load->model('localisation/currency');

						$result = $this->model_localisation_currency->updateCurrencies(true, false, true, true);

						$result['reply'] = " :idea: Курсы валют на сегодня, ". date('d.m.Y');

				//	$result['reply'] = 'Курс, блять, хочешь? Хуй тебе а не курсы валют, я спать хочу блять, а не курсы какие-то ебаные смотреть.';

						$this->model_kp_bitrixBot->answerCommand($result['reply'], $result['attach'], $command['COMMAND_ID'], $command['MESSAGE_ID']);

					} elseif ($command['COMMAND'] == 'чотамзасегодня'){



					}  elseif ($command['COMMAND'] == 'ссылка'){

						$params = trim($command['COMMAND_PARAMS']);
						$params_array = explode(' ', $params);

						if (!$params || !is_array($params_array)){
							$result['reply'] = " :!: Это что-то ваще ниразу не похоже на ссылку. Сдается мне, ты толкаешь мне какую-то дичь.";	
						} else {

							$result['reply'] = '';
							foreach ($params_array as $_url){

								if (!$this->shortAlias->shortenURL(trim($_url))){
									$result['reply'] .= "[BR] :!: ". $_url ." => Это что-то ваще ниразу не похоже на ссылку. Сдается мне, ты толкаешь мне какую-то дичь.";	

								} else {

									$alias = $this->shortAlias->shortenURL($_url);
									$result['reply'] .= "[BR] :idea: " . $_url . " => [URL=". $alias ."]" . $alias . "[/URL]";												
								}
							}

							if ($result['reply']){
								$result['reply'] = ':idea: Так, ну что-то мы тут сократили..[BR]' . $result['reply'];
							}

						}

						$this->model_kp_bitrixBot->answerCommand($result['reply'], $result['attach'], $command['COMMAND_ID'], $command['MESSAGE_ID']);
					}
				}
			}	

			public function addToChat($bitrix_user){
				$this->load->model('kp/bitrixBot');

				$this->model_kp_bitrixBot->assignBitrixUserID($bitrix_user);

				$attach = array(
					array(),		
				);

				$this->model_kp_bitrixBot->sendMessage('Здравствуйте, коллеги!', $attach);
			}


			public function testazaza(){

				$this->load->model('kp/bitrixBot');

				$this->model_kp_bitrixBot->sendMessage($message = 'Тестовое сообщение',  $attach = array(), 'chat9667');
			}

			private function morph($n, $f1, $f2, $f5) {
				$n = abs(intval($n)) % 100;
				if ($n>10 && $n<20) return $f5;
				$n = $n % 10;
				if ($n>1 && $n<5) return $f2;
				if ($n==1) return $f1;
				return $f5;
			}		

			public function countMissedCallsCron(){

				$name = explode('|',$this->config->get('config_name'));
				$name = trim($name[0]);

				$phrases = array(
					'Эй, ребята, шо происходит? В магазине ' . $name . ' [B]%s[/B] %s %s!',
					'Дамы и господа, обратите внимание, пожалуйста. В ' . $name . ' [B]%s[/B] %s %s!',
					'Ой, ой, ой. В ' . $name . ' [B]%s[/B] %s %s!',
					'Так, быстро собрались и прозвонили [B]%s[/B] %s %s на ' . $name . '!',
				);

				$this->load->model('sale/callback');
				$this->load->model('kp/bitrixBot');

				$count = $this->model_sale_callback->getOpenedCallBacksForGroupID(12);								

				if ($count > 0 && $this->checkWorkTime()){
				//	$this->model_kp_bitrixBot->sendMessage($message = sprintf($phrases[mt_rand(0, count($phrases) - 1)], $count, $this->morph($count, 'пропущенный', 'пропущенных', 'пропущенных'), $this->morph($count, 'звонок', 'звонка', 'звонков'),  $attach = array(), 'chat9667'));
					$this->load->model('tool/image');								

					$this->model_kp_bitrixBot->sendMessage(
						$message = sprintf($phrases[mt_rand(0, count($phrases) - 1)], $count, $this->morph($count, 'пропущенный', 'пропущенных', 'пропущенных'), $this->morph($count, 'звонок', 'звонка', 'звонков')),  
						$attach = Array(
				/*	Array("IMAGE" => Array(
					Array(								
					"LINK" => $this->model_tool_image->resize($this->config->get('config_logo'), 100, 50),							
					)
					)),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
				*/
					Array(
						'MESSAGE' =>  '[B]Пропущенные звонки[/B]',
						'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
					)
				), 'chat9667');

				}
			}

			public function sendMessageToGroup($data = array()){
				$this->load->model('user/user');
				$this->load->model('kp/bitrixBot');	

				switch ($data['message_type']){
					case 'alert':
					$pre = ':!:';
					break;

					case 'question':
					$pre = ':?:';
					break;

					case 'info':
					$pre = ':idea:';
					break;

					default: 
					$pre = ':idea:';
					break;
				}

				$result = $this->model_kp_bitrixBot->sendMessage(
					$message = $data['message'],  
					$attach = Array(			
						Array(
							'MESSAGE' =>  $pre . ' [B]обратите внимание![/B]',
							'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
						)
					), $data['group_id']);

				die(json_encode($result));

			}

			public function sendMessageToUser($data = array()){

				$this->load->model('user/user');
				$this->load->model('kp/bitrixBot');

				if ($bitrix_id = $this->model_user_user->getUserBitrixIDByUserName($data['user_id'])) {

					$name = $this->model_user_user->getRealUserFirstNameByUsername($data['user_id']);

					switch ($data['message_type']){
						case 'alert':
						$pre = ':!:';
						break;

						case 'question':
						$pre = ':?:';
						break;

						case 'info':
						$pre = ':idea:';
						break;

						default: 
						$pre = ':idea:';
						break;
					}

					$result = $this->model_kp_bitrixBot->sendMessageToUser(
						$message = $data['message'],  
						$attach = Array(			
							Array(
								'MESSAGE' =>  $pre . ' [B]' . $name . ', обрати внимание![/B]',
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							)
						), $bitrix_id);

					die(json_encode($result));

				} else {

					die('no_user_with_user_id');

				}
			}


			public function countNewOrdersCron(){

				$name = explode('|',$this->config->get('config_name'));
				$name = trim($name[0]);

				$phrases = array(
					'Ничего себе! В магазине ' . $name . ' [B]%s[/B] %s %s!',
					'Закупщики, обратите внимание, пожалуйста. В ' . $name . ' [B]%s[/B] %s %s!',
					'Вот это заказов наоформляли. В ' . $name . ' [B]%s[/B] %s %s!',
					'Закупщики, возмите себя в руки, [B]%s[/B] %s %s на ' . $name . '!',
				);

				$this->load->model('sale/order');
				$this->load->model('kp/bitrixBot');

				$data = array('filter_order_status_id' => $this->config->get('config_order_status_id'));
				$count = $this->model_sale_order->getTotalOrders($data);			

				if ($count > 2 && $this->checkWorkTime()){		
					$this->load->model('tool/image');								

					$this->model_kp_bitrixBot->sendMessage(
						$message = sprintf($phrases[mt_rand(0, count($phrases) - 1)], $count, $this->morph($count, 'необработанный', 'необработанных', 'необработанных'), $this->morph($count, 'заказ', 'заказа', 'заказов')),  
						$attach = Array(
				/*	Array("IMAGE" => Array(
					Array(								
					"LINK" => $this->model_tool_image->resize($this->config->get('config_logo'), 100, 50),							
					)
					)),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
				*/
					Array(
						'MESSAGE' =>  ' :!: [B]Есть необработанные заказы[/B]',
						'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
					)
				), 'chat9815');

				}
			}

			public function askHobotunAboutProductIDAjax(){			
				$this->load->model('kp/bitrixBot');		
				$this->load->model('sale/order');
				$this->load->model('catalog/product');
				$this->load->model('localisation/country');

				$product_id = $this->request->get['product_id'];
				$quantity = $this->request->get['quantity'];		
				$product = $this->model_catalog_product->getProduct($product_id);

				if (!$product){
					die();
				}

				if ($product){
					$real_product = $product;

					$reply = $this->user->getUserFullName() . ', ты просил посмотреть по наличию ' . $real_product['model'] .' ([I]'. $real_product['name'].'[/I])';

					$stock_msg = '';

					$countries = $this->model_localisation_country->getCountries();
					foreach ($countries as $country){							
						if ($real_product[$country['warehouse_identifier']] > 0){																																		
							if (!$stock_msg){
								$stock_msg = $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
								$stock_msg .= ' шт.';
							} else {
								$stock_msg .= ' и в ' . $country['name'] . ' - ' . $real_product[$country['warehouse_identifier']];
								$stock_msg .= ' шт.';
							}
						}																																
					}

					if (!$stock_msg){
						$stock_msg = 'На складах в наличии нет';
					}

					if ($stock_msg){
						$attach[] = array(
							'MESSAGE' => $stock_msg,
							'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
						);
					}		

				//поставщики
					$this->load->model('kp/price');
					$r = $this->model_kp_price->getProductPriceFromSources($real_product['product_id']);
					$suppliers = $r['result'];

					if ($suppliers) {
						foreach ($suppliers as $supplier){
							$supplier_msg = '[B]' . $supplier['supplier_name'] . '[/B]: ';									
							if( isset($supplier['fail']) && $supplier['fail'] ){
								$supplier_msg .= ' ошибка получения';
							} else {
								$supplier_msg .= ' цена: ' . number_format($supplier['price'], 1, '.', ' ');
								if ((float)number_format($supplier['special'], 1, '.', ' ')){
									$supplier_msg .= ', скидка: [B]' . number_format($supplier['special'], 1, '.', ' ') . '[/B]';
								}
								$supplier_msg .= ', нал: ' . $supplier['stock'];
							}

							$attach[] = array(
								'MESSAGE' => $supplier_msg,
								'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
							);
						}
					} else {
						$attach[] = array(
							'MESSAGE' => 'Поставщики не настроены, либо нет доступа',
							'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
						);

					}



				} else {
					$reply = 'Я не смог найти этот товар';
				}

				$this->model_kp_bitrixBot->sendMessage(
					$message = $reply,
					$attach = $attach, 'chat9815');

			}

			public function sendDailyStats(){
				$this->load->model('catalog/product');
				$this->load->model('sale/order');
				$this->load->model('kp/bitrixBot');
				$this->load->model('kp/calls');	


				$phrases = array(
					'Ну что, ущепенцы.. Хотите знать кто сегодня больше всех проебал?'			
				);

				$data = array(
					'filter_date_added' => date('Y-m-d'),
					'filter_date_added_to' => date('Y-m-d')
				);

				$today_orders = $this->model_sale_order->getTotalOrders($data);
				$today_manager_missedcalls = $this->model_kp_calls->countMissedCallsForToday(array('queue' => 901));

				$message = $phrases[mt_rand(0, count($phrases) - 1)];

				$attach = array();
				$attach[] = array(
					'MESSAGE' => ':like: За сегодня было оформлено ' . $today_orders . ' заказов',
					'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
				);

				$attach[] = array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
				));

				$attach[] = array(
					'MESSAGE' => ':facepalm: Какой-то пидорас умудрился так качественно работать, что проебал целых [B]' . $today_manager_missedcalls . '[/B] входящих звонков. Я б сказал, что это [S]пиздец как дохуя[/S] очень много для такой серьезной компании.',
					'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
				);

				$attach[] = array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
				));

				$attach[] = array(
					'MESSAGE' => 'Ну и тут короче дальше завтра еще статистику какую-то залепим...',
					'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
				);

				$this->model_kp_bitrixBot->sendMessage($message, $attach, 'chat9789');

			}

			public function tellSalesToCallByOrderAjax(){
				$this->load->model('sale/order');	
				$this->load->model('kp/bitrixBot');			

				$order_id = $this->request->get['order_id'];

				$order = $this->model_sale_order->getOrder($order_id);

				if (!$order){
					die();
				}

				$phrases = array(
					'просит пообщать заказ {order_id}',
					'говорит, что по заказу {order_id} хорошо бы позвонить',
					'требует связи с покупателем по заказу {order_id}',
					'желает знать, а чего это до сих пор не связались по заказу {order_id}',
				);

				$username = $this->user->getUserFullName();			
				if ($this->user->getBitrixID()){
					$_message = "[USER=".$this->user->getBitrixID()."]".$username."[/USER]";
				} else {
					$_message = $username;
				}

				$this->model_kp_bitrixBot->sendMessage(
					$message = $_message . ' ' . str_replace('{order_id}', '#'.$order['order_id'], $phrases[mt_rand(0, count($phrases) - 1)]) . '[BR][B]Заказ #' . $order['order_id'] . '[/B]' . '',
					$attach = Array(			
						Array(
							'MESSAGE' =>  ' :!: [B]Надо связаться по заказу[/B]',
							'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
						)
					), 'chat9667');

			}

			public function tellPriceManagerPriceNotActualAjax(){
				$this->load->model('catalog/product');	
				$this->load->model('kp/bitrixBot');			

				$product_id = $this->request->get['product_id'];
				$quantity = $this->request->get['quantity'];		
				$product = $this->model_catalog_product->getProduct($product_id);

				if (!$product){
					die();
				}

				$phrases = array(
					'говорит, что у этого товара совсем неактуальная цена',
					'уведомляет что цена товара вообще неактуальна!',
					'сообщает о том, что цена как-то ниразу не актуальна',
				);

				$username = $this->user->getUserFullName();			
				if ($this->user->getBitrixID()){
					$_message = "[USER=".$this->user->getBitrixID()."]".$username."[/USER]";
				} else {
					$_message = $username;
				}

				$this->model_kp_bitrixBot->sendMessage(
					$message = $_message . ' ' . $phrases[mt_rand(0, count($phrases) - 1)] . '[BR][B]' . $product['model'] . '[/B]' . ' [I](' . $product['name'] . ')[/I]',
					$attach = Array(			
						Array(
							'MESSAGE' =>  ' :!: [B]Уведомление о неактуальной цене[/B]',
							'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
						)
					), 'chat29305');
			}

			public function askBuyersAboutProductIDAjax(){
				$this->load->model('catalog/product');	
				$this->load->model('kp/bitrixBot');			

				$product_id = $this->request->get['product_id'];
				$quantity = $this->request->get['quantity'];		
				$product = $this->model_catalog_product->getProduct($product_id);

				if (!$product){
					die();
				}

				$phrases = array(
					'желает знать, есть ли в наличии товар',
					'спрашивает, что там по наличию товара',
					'очень просит подсказать по наличию товара',
				);

				$username = $this->user->getUserFullName();			
				if ($this->user->getBitrixID()){
					$_message = "[USER=".$this->user->getBitrixID()."]".$username."[/USER]";
				} else {
					$_message = $username;
				}

				$this->model_kp_bitrixBot->sendMessage(
					$message = $_message . ' ' . $phrases[mt_rand(0, count($phrases) - 1)] . '[BR][B]' . $product['model'] . '[/B]' . ' [I](' . $product['name'] . ')[/I] в количестве [B]'.$quantity .'[/B] штук.',
					$attach = Array(
			/*	Array("IMAGE" => Array(
				Array(								
				"LINK" => $this->model_tool_image->resize($this->config->get('config_logo'), 100, 50),							
				)
				)),
				Array("DELIMITER" => Array(
				'SIZE' => 200,
				'COLOR' => "#c6c6c6"
				)),
			*/
				Array(
					'MESSAGE' =>  ' :?: [B]Вопрос по наличию[/B]',
					'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
				)
			), 'chat9815');
			}



			public function askBrandManagersAboutWaitListAjax(){
				$this->load->model('catalog/product');	
				$this->load->model('kp/bitrixBot');	

				$product_id = $this->request->get['product_id'];		
				$product = $this->model_catalog_product->getProduct($product_id);

				if (!$product){
					die();
				}

				$phrases = array(
					'желает знать, появится ли когда-нибудь товар',
					'спрашивает, стоит ли покупателю ожидать товар',
					'просит узнать, можно ли будет закупить товар под заказ',
				);

				$username = $this->user->getUserFullName();			
				if ($this->user->getBitrixID()){
					$_message = "[USER=".$this->user->getBitrixID()."]".$username."[/USER]";
				} else {
					$_message = $username;
				}


				$this->model_kp_bitrixBot->sendMessage(
					$message = $_message . ' ' . $phrases[mt_rand(0, count($phrases) - 1)] . '[BR][B]' . $product['model'] . '[/B]' . ' [I](' . $product['name'] . ')[/I]',
					$attach = Array(
			/*	Array("IMAGE" => Array(
				Array(								
				"LINK" => $this->model_tool_image->resize($this->config->get('config_logo'), 100, 50),							
				)
				)),
				Array("DELIMITER" => Array(
				'SIZE' => 200,
				'COLOR' => "#c6c6c6"
				)),
			*/
				Array(
					'MESSAGE' =>  ' :?: [B]Вопрос по листу ожидания[/B]',
					'COLOR' => $this->array_colors[mt_rand(0, count($this->array_colors) - 1)],
				)
			), 'chat65674');

			}



		}																											