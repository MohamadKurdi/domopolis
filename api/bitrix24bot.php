<?php
	error_reporting(E_ALL);
	ini_set('memory_limit', '512M');
	define('VERSION', '1.5.6.4');
	require_once(dirname(__FILE__).'/../admin/config.php');
	
	require_once(DIR_SYSTEM . '../vendor/autoload.php');	
	require_once(DIR_SYSTEM . 'startup.php');
	require_once(DIR_SYSTEM . 'library/currency.php');
	require_once(DIR_SYSTEM . 'library/customer.php');
	require_once(DIR_SYSTEM . 'library/user.php');
	require_once(DIR_SYSTEM . 'library/weight.php');
	require_once(DIR_SYSTEM . 'library/length.php');
	require_once(DIR_SYSTEM . 'library/cart.php');
	require_once(DIR_SYSTEM . 'library/smsQueue.php');
	require_once(DIR_SYSTEM . 'library/mAlert.php');
	require_once(DIR_SYSTEM . 'library/pushQueue.php');
	require_once(DIR_SYSTEM . 'library/Bitrix24.php');
	require_once(DIR_SYSTEM . 'library/shortAlias.php');
	require_once(DIR_SYSTEM . 'library/sessionDBHandler.php');
	require_once(DIR_SYSTEM . 'library/PageCache.php');
	require_once(DIR_SYSTEM . 'library/hobotix/EmailBlackList.php');
	require_once(DIR_SYSTEM . 'library/hobotix/RainforestAmazon.php');
	require_once(DIR_SYSTEM . 'library/hobotix/PricevaAdaptor.php');

	
	$registry = new Registry();
	$loader = new Loader($registry);
	$registry->set('load', $loader);
	$config = new Config();
	$registry->set('config', $config);
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$registry->set('db', $db);
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
	foreach ($query->rows as $setting) {
		if (!$setting['serialized']) {
			$config->set($setting['key'], $setting['value']);
			} else {
			$config->set($setting['key'], $setting['value']?unserialize($setting['value']):false);
		}
	}
	$log = new Log('bitrix24_bot.txt');
	$registry->set('log', $log);
	function error_handler($errno, $errstr, $errfile, $errline) {
		global $config, $log;
		
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
			$error = 'Notice';
			break;
			case E_WARNING:
			case E_USER_WARNING:
			$error = 'Warning';
			break;
			case E_ERROR:
			case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
			default:
			$error = 'Unknown';
			break;
		}
		
		if (true) {
			echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
		
		if (true) {
			$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
		}
		
		return TRUE;
	}
	set_error_handler('error_handler');
	$request = new Request();
	$registry->set('request', $request);
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->set('response', $response); 
	$registry->set('session', new Session());
	$cache = new Cache();
	$registry->set('cache', $cache);
	$registry->set('document', new Document());
	$languages = array();
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 
	foreach ($query->rows as $result) {
		$languages[$result['code']] = array(
		'language_id'	=> $result['language_id'],
		'name'		=> $result['name'],
		'code'		=> $result['code'],
		'locale'	=> $result['locale'],
		'directory'	=> $result['directory'],
		'filename'	=> $result['filename']
		);
	}
	$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
	$language = new Language($languages[$config->get('config_admin_language')]['directory']);
	$language->load($languages[$config->get('config_admin_language')]['filename']);	
	$registry->set('language', $language);
	$registry->set('currency', new Currency($registry));
	$registry->set('weight', new Weight($registry));
	$registry->set('length', new Length($registry));
	$registry->set('user', new User($registry));
	$mAlert = new mAlert($registry);
	$registry->set('mAlert', $mAlert);
	$Bitrix24 = new Bitrix24($registry);
	$registry->set('Bitrix24', $Bitrix24);	
	$shortAlias = new shortAlias($registry);
	$registry->set('shortAlias', $shortAlias);
	
	$controller = new Front($registry);
	
	// receive event "delete chat-bot"
	if ($request->request['event'] == 'ONIMBOTDELETE') {
		// check the event - register this application or not
		if (!$Bitrix24->checkAuth()){
			die();
		}		
		
		$Bitrix24->saveParams($appsConfig);
		
	} // receive event "Application install"
	elseif ($request->request['event'] == 'ONAPPINSTALL') {
		// handler for events
		$handlerBackUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'],
		array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];
		
		$result = $Bitrix24->restCommand('imbot.register', array(
		'CODE'                  => 'IIBot', // строковой идентификатор бота, уникальный в рамках вашего приложения (обяз.)					
		'TYPE'                  => 'B', // Тип бота, B - бот, ответы  поступают сразу, H - человек, ответы поступаю с задержкой от 2х до 10 секунд					
		'EVENT_MESSAGE_ADD'     => $handlerBackUrl, // Ссылка на обработчик события отправки сообщения боту (обяз.)					
		'EVENT_WELCOME_MESSAGE' => $handlerBackUrl, // Ссылка на обработчик события открытия диалога с ботом или приглашения его в групповой чат (обяз.)					
		'EVENT_BOT_DELETE'      => $handlerBackUrl, // Ссылка на обработчик события удаление бота со стороны клиента (обяз.)					
		'PROPERTIES'            => array(
		'NAME'              => 'Хоботун', // Имя бота (обязательное одно из полей NAME или LAST_NAME)
		'LAST_NAME'         => '',        // Фамилия бота (обязательное одно из полей NAME или LAST_NAME)					
		'COLOR'             => 'AQUA',    // Цвет бота для мобильного приложения RED,  GREEN, MINT, LIGHT_BLUE, DARK_BLUE, PURPLE, AQUA, PINK, LIME, BROWN,  AZURE, KHAKI, SAND, MARENGO, GRAY, GRAPHITE					
		'EMAIL'             => 'it@ims-group.de', // Цвет бота для мобильного приложения RED,  GREEN, MINT, LIGHT_BLUE, DARK_BLUE, PURPLE, AQUA, PINK, LIME, BROWN,  AZURE, KHAKI, SAND, MARENGO, GRAY, GRAPHITE					
		'PERSONAL_BIRTHDAY' => '2016-03-23',	// День рождения в формате YYYY-mm-dd
		'WORK_POSITION'     => 'Я - самый добрый бот Хоботун',	// Занимаемая должность, используется как описание бота
		'PERSONAL_WWW'      => '',							// Ссылка на сайт					
		'PERSONAL_GENDER'   => 'M',               // Пол бота, допустимые значения M -  мужской, F - женский, пусто если не требуется указывать					
		'PERSONAL_PHOTO'    => base64_encode(file_get_contents( __DIR__ .'/botlogo.png')), // Аватар бота - base64						
		),
		),
		$request->request["auth"]);
		
		$appsConfig[$request->request['auth']['application_token']] = array(
		'BOT_ID'      => $result['result'],
		'LANGUAGE_ID' => $request->request['data']['LANGUAGE_ID'],
		'AUTH'        => $request->request['auth']
		);
		$Bitrix24->saveParams($appsConfig);
		
		
		$botId = $result['result'];
		
		//UPDATE
		$result = $Bitrix24->restCommand('imbot.update', Array(
		
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
		
		), $request->request["auth"]);
							
		$result = $Bitrix24->restCommand('imbot.command.register', Array(				
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
		
		), $request->request["auth"]);
	
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
		
		$result = $Bitrix24->restCommand('imbot.command.register', Array(				
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
		
		), $request->request["auth"]);
		
		$result = $Bitrix24->restCommand('imbot.command.register', Array(				
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
		
		), $request->request["auth"]);
		
		$result = $Bitrix24->restCommand('imbot.command.register', Array(				
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
		
		), $request->request["auth"]);
		
		
		
		
		$log->write($result);
		
		
	} 	
	// receive event "open private dialog with bot" or "join bot to group chat"
	elseif ($request->request['event'] == 'ONIMBOTJOINCHAT') {		
		if (!$Bitrix24->checkAuth()){
			die();
		}
		
		if (isset($request->request['data']['USER'])) {
			$action = new Action('api/bitrixbot/addToChat', array('bitrix_user' => $request->request['data']['USER']));		
		}
		
	}
	elseif ($request->request['event'] == 'ONIMBOTMESSAGEADD') {	
		if (!$Bitrix24->checkAuth()){
			die();
		}
		
		$action = new Action('api/bitrixbot/parseMessage', array('message' => $request->request['data']['PARAMS']['MESSAGE']));
	} 
	elseif ($request->request['event'] == 'ONIMCOMMANDADD')
	{
		if (!$Bitrix24->checkAuth()){
			die();
		}
		
		$log->write($request->request['data']);
		
		
		$action = new Action('api/bitrixbot/parseCommand', array('command' => $request->request['data']['COMMAND']));				
	} 
	
	if (isset($action)) {
		$controller->dispatch($action, new Action('error/not_found'));
		} else {
		die('');	
	}									