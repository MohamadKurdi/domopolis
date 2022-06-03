<?php 
class ModelToolAnyport extends Model {
	protected $now = NULL;
	protected $seed = 0;
	private $fileCount = 0;
	
	public function __construct($register) {
		if (!defined('ANYPORT_ROOT')) define('ANYPORT_ROOT', substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)));
		if (!defined('ANYPORT_SERVER_NAME')) define('ANYPORT_SERVER_NAME', substr((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER), 7, strlen((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER)) - 8));
		if (!defined('ANYPORT_SERVER_NAME_READABLE')) define('ANYPORT_SERVER_NAME_READABLE', str_replace('/', '_', ANYPORT_SERVER_NAME));
		if (!defined('ANYPORT_ADMIN_FOLDER_NAME')) define('ANYPORT_ADMIN_FOLDER_NAME', substr(HTTP_SERVER, strripos(HTTP_SERVER, '/', -2) + 1, strlen(HTTP_SERVER) - 2 - strripos(HTTP_SERVER, '/', -2)));
		parent::__construct($register);
	}
	
	/* DROPBOX FUNCTIONS */
	public function getDropboxAutoBackup() {
		$data = array(
			'Status' => false,
			'Message' => $this->language->get('dropbox_not_logged_in'),
			'Code' => NULL,
			'User' => NULL
		);
		/*$this->data['autoBackup']['Dropbox']['Status'] = false;
		$this->data['autoBackup']['Dropbox']['Code'] = true;
		$this->data['autoBackup']['Dropbox']['User'] = true;	*/
		
		$this->load->model('setting/setting');
		$setting = $this->getSetting('AnyPort');
		
		if (defined('VERSION'))
		switch (VERSION) {
			case '1.5.0' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.0.1' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.0.2' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.0.3' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.0.4' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.0.5' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.1' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.1.1' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
			case '1.5.1.2' : { $data['Message'] = str_replace('{VERSION}', VERSION, $this->language->get('text_version_not_supported')); return $data; } break;
		}
		
		if (empty($setting['AnyPort']['AutoBackup']['Enable']) || $setting['AnyPort']['AutoBackup']['Enable'] == "No") {
			$data['Message'] = $this->language->get('text_auto_backup_not_enabled');
			return $data;	
		}
		
		if (empty($setting['AnyPort']['Settings']['Dropbox']['Enable'])) {
			$data['Message'] = $this->language->get('dropbox_not_enabled');
			return $data;
		}
		
		$dropbox = &$this->initDropbox();
		$authCode = $this->getSetting('AnyPortCron');
		
		$token = !empty($authCode['dropBoxAccessToken']) ? $authCode['dropBoxAccessToken'] : NULL;
		
		if (empty($token) || empty($token['t'])) return $data;
		
		$dropbox->SetAccessToken($token);
		
		$info = $dropbox->GetAccountInfo();
		if (empty($info)) {
			$data['Message'] = $this->language->get('dropbox_access_token_expired');
			return $data;
		}
		
		$data = array(
			'Status' => true,
			'Message' => $this->language->get('dropbox_active'),
			'Code' => PHP_BINDIR.'/php -q '.ANYPORT_ROOT.'/vendors/anyport/dropbox_cron.php',
			'User' => $info->display_name.' ('.$info->email.')'
		);
		
		return $data;
	}
	public function downloadFromDropbox($folder = '', $file = '') {
		if (empty($file)) throw new Exception($this->language->get('anyport_no_file'));
		
		$dropbox = &$this->initDropbox();
		$this->load->model('setting/setting');
		$authCode = $this->getSetting('AnyPortAuth');
		
		$folder = $this->formatDropboxFolder($folder);
		$result = false;
		$source = $folder.'/'.rawurlencode($file);
		$token = $authCode['dropBoxAccessToken'];
		$tempDir = ANYPORT_ROOT.'/temp';
		
		if(empty($token)) throw new Exception($this->language->get('anyport_no_access_token'));
		
		$dropbox->SetAccessToken($token);
		if (!is_dir($tempDir)) if (!mkdir($tempDir, 0755)) throw new Exception($this->language->get('anyport_temp_dir_error'));
		$result = $dropbox->DownloadFile($source, $tempDir.'/'.$file);
		
		return $tempDir.'/'.$file;
	}
	public function listFromDropbox($folder = '') {
		$dropbox = &$this->initDropbox();
		$this->load->model('setting/setting');
		$authCode = $this->getSetting('AnyPortAuth');
		
		$token = !empty($authCode['dropBoxAccessToken']) ? $authCode['dropBoxAccessToken'] : NULL;
		$folder = $this->formatDropboxFolder($folder);
		$result = array();
		
		if(empty($token)) throw new Exception('No access token set.');
		
		$dropbox->SetAccessToken($token);
		set_error_handler(
			create_function(
				'$severity, $message, $file, $line',
				'throw new Exception("Could not locate the folder.");'
			)
		);
		$temp = $dropbox->GetMetadata($folder);
		restore_error_handler();
		if (empty($temp->contents)) throw new Exception('The folder is empty.');
		foreach ($temp->contents as $content) {
			$pos = strripos($content->path, '/') + 1;
			$len = strlen($content->path) - $pos;
			$result[substr($content->path, $pos, $len)] = substr($content->path, $pos, $len);
		}
		
		return $result;
	}
	public function exportToDropbox(&$dataFiles, $rootFolders = array(), $folder = '', $backup = false, $exportType = 'Backup') {
		$folder = $this->formatDropboxFolder($folder, false);
		
		$file = $backup ? $this->exportSQL($dataFiles) : $this->exportXLS($dataFiles);
		$file2 = $this->exportRootFolders($rootFolders);
		$file = $this->createFinalArchive(array($file, $file2), $exportType);
		if ($file === false) throw new Exception($this->language->get('anyport_unable_file'));
		
		if (true || filesize($file) <= $this->returnMaxAllowedMemory()) { 
			$dropbox = &$this->initDropbox();
			$settingName = ($exportType == 'CronBackup') ? 'AnyPortCron' : 'AnyPortAuth';
			$authCode = $this->getSetting($settingName);
			
			$token = !empty($authCode['dropBoxAccessToken']) ? $authCode['dropBoxAccessToken'] : NULL;
			
			$result = false;
			$fileInfo = pathinfo($file);
			
			if(empty($token)) throw new Exception($this->language->get('anyport_no_access_token'));
			
			$dropbox->SetAccessToken($token);
			try {
				$dropbox->UploadFile($file, $folder.'/'.$fileInfo['basename']);
				unlink($file);
				$result = true;
			} catch (Exception $e) {
				unlink($file);
				throw $e;
			}
			
		} else {
			$file = ($this->config->get('config_secure') ? 'https://' : 'http://') . ANYPORT_SERVER_NAME . substr($file, strlen(ANYPORT_ROOT));
			$result = str_replace('{FOLDER_ID}', $folder, $this->language->get('dropbox_redirect')).' You can download the generated file <a target="_blank" href="'.$file.'">HERE</a>';	
		}
		return $result;
	}
	public function &initDropbox() {
		require_once(ANYPORT_ROOT.'/vendors/DropPHP/DropboxClient.php');
		$this->load->model('setting/setting');
		$setting = $this->getSetting('AnyPort');
		
		$dropbox = new DropboxClient(array(
			'app_key' => (empty($setting['AnyPort']['Settings']['Dropbox']['AppKey'])) ? NULL : $setting['AnyPort']['Settings']['Dropbox']['AppKey'], 
			'app_secret' => (empty($setting['AnyPort']['Settings']['Dropbox']['AppSecret'])) ? NULL : $setting['AnyPort']['Settings']['Dropbox']['AppSecret'],
			'app_full_access' => true,
		),'en');
		return $dropbox;
	}
	private function formatDropboxFolder($folder, $leadingSlash = true) {
		if (!is_string($folder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		$folder = $this->clearInvalidEntries(explode('/', $folder));
		foreach ($folder as $index => $item) {
			$folder[$index] = rawurlencode($item);
		}
		if (empty($folder)) $folder = '';
		else $folder = ($leadingSlash ? '/' : '').implode('/', $folder);
		return $folder;
	}
	
	/* GOOGLE DRIVE FUNCTIONS */
	public function downloadFromGoogleDrive($file = '') {
		if (empty($file)) throw new Exception($this->language->get('anyport_no_file'));
		
		$service = &$this->initGoogleDrive();
		$result = false;
	
		$file = $service->files->get($file);
		$tempDir = ANYPORT_ROOT.'/temp';
		if (!is_dir($tempDir)) if (!mkdir($tempDir, 0755)) throw new Exception($this->language->get('anyport_temp_dir_error'));
		
		if ($file['fileSize'] * 2.1 > $this->returnMaxAllowedMemory()) throw new Exception(str_replace('{LINK}', $file['webContentLink'], $this->language->get('anyport_file_too_big_download')));
		
		if (!empty($file['downloadUrl'])) $url = $file['downloadUrl'];
		else if (!empty($file['exportLinks']['application/vnd.ms-excel'])) $url = $file['exportLinks']['application/vnd.ms-excel'];
		else if (!empty($file['exportLinks']['text/plain'])) $url = $file['exportLinks']['text/plain'];
		else $url = NULL;
		
		if (empty($url)) throw new Exception($this->language->get('anyport_no_download_url'));
		
		$path = $tempDir.'/'.trim($file['title']);
		
		$request = new Google_HttpRequest($url, 'GET', null, null);
		
		$httpRequest = Google_Client::$io->authenticatedRequest($request);
		
		if ($httpRequest->getResponseHttpCode() != 200) throw new Exception($this->language->get('anyport_unable_download'));
		
		$headers = $httpRequest->getResponseHeaders();
		preg_match('/filename=\"(.*?)\"/', $headers['content-disposition'], $matches);
		$path = !empty($matches[1]) ? $tempDir.'/'.trim($file['title']).((strrpos($matches[1], '.') !== false) ? substr($matches[1], strrpos($matches[1], '.'), strlen($matches[1]) - strrpos($matches[1], '.')) : '') : $tempDir.'/'.trim($file['title']);
		
		$fh = fopen($path, 'w');
		if ($fh === false) throw new Exception($this->language->get('anyport_no_file'));
		
		if (fwrite($fh, $httpRequest->getResponseBody()) === false) {
			fclose($fh);
			unlink($path);
			throw new Exception($this->language->get('anyport_unable_write_file'));
		}
		
		if (!empty($file['downloadUrl'])) {
			$check = md5_file($path);
			if ($check == $file['md5Checksum']) $result = $path;
			else {
				unlink($path);
				throw new Exception($this->language->get('anyport_mismatch_size'));
			}
		} else {
			$result = $path;	
		}
		
		return $result;
	}
	public function listFromGoogleDrive($folder = '') {
		if (!is_string($folder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		$service = &$this->initGoogleDrive();
		$result = array();
		$folderId = $this->getGoogleFolderId($service, $folder);
		
		if ($folderId === false) throw new Exception('Could not locate the folder.');
			
		$children = $service->files->listFiles(array('q' => "'".$folderId."' in parents"));
		if (empty($children['items'])) throw new Exception('The folder is empty.');
		
		foreach ($children['items'] as $content) {
			if (strripos($content['mimeType'], 'folder') === false) $result[$content['id']] = $content['title'];
		}
		
		return $result;
	}
	public function exportToGoogleDrive(&$dataFiles, $rootFolders = array(), $folder = '', $backup = false, $exportType = 'Backup') {
		if (!is_string($folder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		
		$service = &$this->initGoogleDrive();
		try { 
			$parentFolder = $folder;
			$parentId = $this->getGoogleFolderId($service, $parentFolder);
			if ($parentId === false) throw new Exception('Could not locate the folder.');
		} catch (Exception $e) {
			throw $e;
		}
		
		$file = $backup ? $this->exportSQL($dataFiles) : $this->exportXLS($dataFiles);
		$file2 = $this->exportRootFolders($rootFolders);
		$file = $this->createFinalArchive(array($file, $file2), $exportType);
		if ($file === false) throw new Exception($this->language->get('anyport_unable_file'));
			
		define('APACHE_MIME_TYPES_URL','http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
		
		$filePath = $file;
		$fileParts = pathinfo($filePath);
		$mimeTypes = $this->generateUpToDateMimeArray(APACHE_MIME_TYPES_URL);
		$result = true;
		
		if (filesize($filePath) <= $this->returnMaxAllowedMemory(52428800)) { //size is less or equal than 50 MB
			//Insert a file
			$file = new Google_DriveFile();
			$file->setTitle($fileParts['basename']);
			$file->setDescription('OpenCart Backup for Office 97, 2003 and later, generated using PHPExcel and AnyPort.');
			$file->setMimeType($mimeTypes[$fileParts['extension']]);
		
			$md5 = md5_file($filePath);
			//if ($data === false) throw new Exception($this->language->get('anyport_unable_read_file'));
			
			$createdFile = $service->files->insert($file, array(
				'data' => file_get_contents($filePath),
				'mimeType' => $mimeTypes[$fileParts['extension']],
			));
			
			unlink($filePath);
			
			if ($md5 != $createdFile['md5Checksum']) throw new Exception($this->language->get('anyport_mismatch_size'));
			
			//Set Parent
			if (!empty($folder)) {
				$fileId = $createdFile['id'];
				try {
					$newParent = new Google_ParentReference();
					$newParent->setId($parentId);
					$allParents = $service->parents->listParents($fileId);
					
					foreach ($allParents['items'] as $parent) {
						$service->parents->delete($fileId, $parent['id']);	
					}
					$service->parents->insert($fileId, $newParent);
				} catch (Exception $e) {
					
					$service->files->delete($fileId);
					throw $e;
				}
			}
		} else { //size is bigger than 50 MB
			if (!empty($folder)) {
				$folderId = $this->getGoogleFolderId($service, $folder);
			} else {
				$folderId = 'my-drive';
			}
			
			$filePath = ($this->config->get('config_secure') ? 'https://' : 'http://') . ANYPORT_SERVER_NAME . substr($filePath, strlen(ANYPORT_ROOT));
			
			$result = str_replace('{FOLDER_ID}', ($folderId == 'my-drive') ? $folderId : 'folders/'.urlencode($folderId), $this->language->get('google_drive_redirect')).' You can download the generated file <a target="_blank" href="'.$filePath.'">HERE</a>';
		}
		
		return $result;
	}
	public function &initGoogleDrive($return = 'service', $throw = true) {
		require_once ANYPORT_ROOT.'/vendors/google-api-php-client/src/Google_Client.php';
		require_once ANYPORT_ROOT.'/vendors/google-api-php-client/src/contrib/Google_DriveService.php';
		$this->load->model('setting/setting');
		$setting = $this->getSetting('AnyPort');
		
		$client = new Google_Client();
		$client->setClientId((empty($setting['AnyPort']['Settings']['GoogleDrive']['ClientId'])) ? NULL : $setting['AnyPort']['Settings']['GoogleDrive']['ClientId']);
		$client->setClientSecret((empty($setting['AnyPort']['Settings']['GoogleDrive']['ClientSecret'])) ? NULL : $setting['AnyPort']['Settings']['GoogleDrive']['ClientSecret']);
		$client->setRedirectUri(str_replace('/admin', '', (!empty($_SERVER['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER) . 'vendors/anyport/gdrivecallback.php'));
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));
		$client->setState($this->session->data['token'] . '&adminFolder='.ANYPORT_ADMIN_FOLDER_NAME);
		$client->setAccessType('offline');
		$client->setApprovalPrompt('force');
		
		$service = new Google_DriveService($client);
		
		$token = $this->getSetting('AnyPortAuth');
		
		if (empty($token)) $mode = 'init';
		foreach ($token as $type => $value) {
			if ($type == 'googleDriveRefreshToken' && $return == 'service') { $token = $value; $mode = 'refresh'; }
			else if ($type == 'googleDriveAuthCode' && $return == 'service') { $token = $value; $mode = 'auth'; }
			else { $mode = 'init'; 
				if (!in_array($type, array('googleDriveRefreshToken', 'googleDriveAuthCode')) && $throw) throw new Exception($this->language->get('anyport_need_authorize'));
			}
			
			break;
		}
		
		if ($mode == 'refresh') {
			$client->refreshToken($token);
			$accessToken = $client->getAccessToken();
			$client->setAccessToken($accessToken);
		} else if ($mode == 'auth') {
			$accessToken = $client->authenticate($token);
			$refreshToken = json_decode($accessToken, true);
			$this->model_setting_setting->editSetting('AnyPortAuth', array('googleDriveRefreshToken' => serialize($refreshToken['refresh_token'])));
			$client->setAccessToken($accessToken);
		} else if ($mode == 'init') {
			return $client;	
		}
		
		return $service;
	}
	private function getGoogleFolderId(&$service, &$folders) {
		$files = $service->files->listFiles();
		
		$items = $files['items'];
		
		if (empty($items)) return false;
		
		$folders = explode('/', $folders);
		$folders = $this->clearInvalidEntries($folders);
		if (empty($folders)) {
			//get root id
			foreach ($items as $itemIndex => $item) {
				$parentId = $this->googleParentsHaveRoot($items, $itemIndex);
				if ($parentId !== false) return $parentId;
			}
		} else {
			return $this->getGoogleFolderIdForMany($items, $folders, count($folders) - 1);
		}
		return false;
	}
	private function getGoogleFolderIdForMany(&$items, &$folders, $i, $itemIndex = NULL) { //init with $i = count($folders) - 1
		if ($i == -1 && $this->googleParentsHaveRoot($items, $itemIndex) !== false) return true;
		if ($i >= 0) {
			foreach ($items as $index => $item) {
				if (stripos($item['mimeType'], 'folder') !== false && strcmp(mb_strtolower(($item['title']), 'UTF-8'), mb_strtolower(($folders[$i]), 'UTF-8')) == 0 && $this->getGoogleFolderIdForMany($items, $folders, $i-1, $index) !== false && $this->googleItemIsParent($items, $index, $itemIndex)) return $item['id'];
			}
		}
		return false;
	}
	private function googleItemIsParent(&$items, $parentIndex, $childIndex) {
		if ($childIndex === NULL) return true;
		
		foreach ($items[$childIndex]['parents'] as $childParent) {
			if ($childParent['id'] == $items[$parentIndex]['id']) return true;	
		}
		
		return false;
	}
	private function googleParentsHaveRoot(&$items, $itemIndex) {
		if ($itemIndex === NULL) return false;
		
		foreach ($items[$itemIndex]['parents'] as $parent) {
			if ($parent['isRoot']) return $parent['id'];
		}
		return false;
	}
	
	/* SKYDRIVE FUNCTIONS */
	public function downloadFromSkyDrive($file = '') {
			if (empty($file)) throw new Exception($this->language->get('anyport_no_file'));
			
			// Get access token
			$accessToken = $this->initSkyDrive();
			
			//Get file
			$result = $this->skyDriveRequest('https://apis.live.net/v5.0/'.$file.'?access_token='.$accessToken);
			if (empty($result['id'])) throw new Exception($this->language->get('anyport_unable_download'));
			
			//Download file
			$complete = false;
			$tempDir = ANYPORT_ROOT.'/temp';
			if (!is_dir($tempDir)) if (!mkdir($tempDir, 0755)) throw new Exception($this->language->get('anyport_temp_dir_error'));
			$destPath = $tempDir.'/'.$result['name'];
			$this->downloadFile($result['source'], $destPath);
			$check = filesize($tempDir.'/'.$result['name']);
			if ($check == $result['size']) $complete = $tempDir.'/'.$result['name'];
				
			return $complete;
	}
	public function listFromSkyDrive($folder = '') {
		$accessToken = $this->initSkyDrive();
		
		//GET FOLDER ID
		$folderId = $this->getSkyDriveFolderId($folder, $accessToken);
		if ($folderId === false) throw new Exception('Could not locate the folder.');
		
		//Get files
		$result = $this->skyDriveRequest('https://apis.live.net/v5.0/'.$folderId.'/files?access_token='.$accessToken);
		if (empty($result['data'])) throw new Exception('The folder is empty.');
		$complete = array();
		foreach ($result['data'] as $item) {
			if ($item['type'] != 'file') continue;
			$complete[$item['id']] = $item['name'];
		}
		return $complete;	
	}
	public function exportToSkyDrive(&$dataFiles, $rootFolders = array(), $folder, $backup = false, $exportType = 'Backup') {
		$accessToken = $this->initSkyDrive();
		
		//GET FOLDER ID
		$folderId = $this->getSkyDriveFolderId($folder, $accessToken);
		if ($folderId === false) throw new Exception($this->language->get('anyport_unable_find_folder'));
		
		$file = $backup ? $this->exportSQL($dataFiles) : $this->exportXLS($dataFiles);
		$file2 = $this->exportRootFolders($rootFolders);
		$file = $this->createFinalArchive(array($file, $file2), $exportType);
		if ($file === false) throw new Exception($this->language->get('anyport_unable_file'));
		
		$filePath = $file;
		$fileParts = pathinfo($filePath);
		
		if (filesize($filePath) <= $this->returnMaxAllowedMemory(8388608)) { //size is less than 8 MB
			$fileContents = file_get_contents($filePath);
			if ($fileContents === false) throw new Exception($this->language->get('anyport_unable_read_file'));
			$result = false;
			//Upload the file
			
			$data_string = "--AaB03x\r\nContent-Disposition: form-data; name=\"file\"; filename=\"".$fileParts['basename']."\"\r\nContent-Type: application/octet-stream\r\n\r\n".$fileContents.chr(0).chr(0)."\r\n--AaB03x--";
			$result = $this->skyDriveRequest('https://apis.live.net/v5.0/'.$folderId.'/files?access_token='.$accessToken, "POST", array('Content-Type: multipart/form-data; boundary=AaB03x'), $data_string);
			
			unset($fileContents);
		} else {
			$result = NULL;	
		}
		
		$unlink = true;
		if (!empty($result['id'])) {
			$result = true;
		} else if (!empty($result['error']['code'])) {
			$result = $result['error']['message'];
		} else if (empty($result)) {
			//$this->createDownload($filePath, false);
			$folder = implode('/', $this->clearInvalidEntries(explode('/', $folder)));
			if (empty($folder)) $folderId = '';
			$unlink = false;
			$filePath = ($this->config->get('config_secure') ? 'https://' : 'http://') . ANYPORT_SERVER_NAME . substr($filePath, strlen(ANYPORT_ROOT));
			$result = str_replace('{FOLDER_ID}', substr(urlencode($folderId), strrpos(urlencode($folderId), '.') + 1, strlen(urlencode($folderId)) - strrpos(urlencode($folderId), '.') - 1), $this->language->get('skydrive_redirect')).' You can download the generated file <a target="_blank" href="'.$filePath.'">HERE</a>';
		} else $result = false;
		
		if ($unlink) unlink($filePath);
		
		return $result;
	}
	private function skyDriveRequest($url, $type = "GET", $header = array(), $data = '') {
		if (!extension_loaded("curl")) throw new Exception($this->language->get('anyport_curl_disabled'));
		
		$ch = curl_init($url);
		if ($type != "GET") {                                                                
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);   
			if (!empty($data)) {                                                               
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			}
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$result = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $result;
	}
	private function initSkyDrive() {
		// Get access token 
		$this->load->model('setting/setting');
		$authCode = $this->getSetting('AnyPortAuth');
		
		$authCode = !empty($authCode['skyDriveAuthCode']) ? $authCode['skyDriveAuthCode'] : NULL;
		if (empty($authCode)) throw new Exception($this->language->get('anyport_need_authorize'));
		
		$setting = $this->getSetting('AnyPort');
		
		$clientId = (empty($setting['AnyPort']['Settings']['SkyDrive']['ClientId'])) ? NULL : $setting['AnyPort']['Settings']['SkyDrive']['ClientId'];
		$clientSecret = (empty($setting['AnyPort']['Settings']['SkyDrive']['ClientSecret'])) ? NULL : $setting['AnyPort']['Settings']['SkyDrive']['ClientSecret'];
		$redirectURI = str_replace('/admin', '', (!empty($_SERVER['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER) . 'vendors/anyport/skydrivecallback.php');
		$state = $this->session->data['token'] . '&adminFolder='.ANYPORT_ADMIN_FOLDER_NAME;
		
		
		$data_string = 'client_id='.$clientId.'&redirect_uri='.$redirectURI.'&client_secret='.$clientSecret.'&code='.$authCode.'&grant_type=authorization_code';
		$accessToken = $this->skyDriveRequest('https://login.live.com/oauth20_token.srf', 'POST', array(                                                                          
			'Content-Type: application/x-www-form-urlencoded',                                                                                
			'Content-Length: ' . strlen($data_string)                                                                      
		), $data_string);
		
		return $accessToken['access_token'];
	}
	private function getSkyDriveFolderId($folder, &$accessToken) {
		if (!is_string($folder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		//Find the folder ID
		$folder = $this->clearInvalidEntries(explode('/', $folder));
		//get root ID
		if (empty($folder)) {
			$result = $this->skyDriveRequest('https://apis.live.net/v5.0/me/skydrive?access_token='.$accessToken);
			if (empty($result['id'])) return false;
			$folderId = $result['id'];
		} else {
			$folderId = $this->getSkyDriveFolderIdForMany($folder, $accessToken, 0);
		}
		return $folderId;
	}
	private function getSkyDriveFolderIdForMany(&$folders, &$accessToken, $folderIndex, $url = NULL) { //init $folderIndex = 0
		$id = $url;
		$url = 'https://apis.live.net/v5.0/'.($url === NULL ? 'me/skydrive' : $url).'/files?access_token='.$accessToken;
		$folderId = false;
		if ($folderIndex < count($folders)) {
			$result = $this->skyDriveRequest($url);
			
			if (empty($result['data'])) return false;
			
			foreach ($result['data'] as $data) {
				if ($data['type'] != 'folder') continue;
				if (strcmp(mb_strtolower(($data['name']), 'UTF-8'), mb_strtolower(($folders[$folderIndex]), 'UTF-8')) == 0) $folderId = $this->getSkyDriveFolderIdForMany($folders, $accessToken, $folderIndex + 1, $data['id']);
				else {
					continue;
				}
			}
		} else {
			return $id;
		}
		
		return $folderId;
	}
	
	/* GENERIC FUNCTIONS */
	private function exec_enabled() {
		return function_exists('exec') &&
			!in_array('exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
			!(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0) && strtolower(PHP_OS) == 'linux';
	}
	
	public function getArchiveEngine() {
		$result = false;
		if ($this->exec_enabled()) {
			$output = trim(exec("command -v tar"));
			if (!empty($output)) $result = 'Tar';
			else {
				if (class_exists('ZipArchive')) {
					$result = 'ZipArchive';	
				}	
			}
		} else {
			if (class_exists('ZipArchive')) {
				$result = 'ZipArchive';	
			}
		}
		
		if ($result === false) throw new Exception("Error: Your web server does not have Tar and ZipArchive. Please enable one of them and try again.");
		
		return $result;
	}
	
	public function checkAvailableZipEngine($engine) {
		$result = false;
		$available = array();
		if ($this->exec_enabled()) {
			$output = trim(exec("command -v tar"));
			if (!empty($output)) $available[] = 'Tar';
			
			if (class_exists('ZipArchive')) {
				$available[] = 'ZipArchive';
			}
		} else {
			if (class_exists('ZipArchive')) {
				$available[] = 'ZipArchive';	
			}
		}
		return in_array($engine, $available);
	}
	
	public function exportRootFolders($rootFolders) {
		if ((!is_array($rootFolders) && $this->array_depth($rootFolders) != 1) || empty($rootFolders)) return false;
		$this->now = empty($this->now) ? time() : $this->now;
		$now = $this->now;
		$zipfilename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_Backup_AnyPort_Root';
		$tempdir = ANYPORT_ROOT.'/temp';
		
		$zipEngine = $this->getArchiveEngine();
		
		switch ($zipEngine) {
			case 'ZipArchive' : {
				$zipfilename .= '.zip';
				$zip = new ZipArchive();
				if ($zip->open($tempdir.'/'.$zipfilename, ZIPARCHIVE::OVERWRITE) !== true) {
					return false;
				}
				$rootDir = ANYPORT_ROOT.'/';
				foreach ($rootFolders as $rootFolder) {
					if (strrpos($rootFolder, '/') == strlen($rootFolder) - 1) $rootFolder = substr($rootFolder, 0, strlen($rootFolder) - 1);
					if (!$this->addFolderToZip($zip, $rootDir, $rootFolder, $tempdir.'/'.$zipfilename, ZIPARCHIVE::CHECKCONS)) return false;
				}
				$zip->close();
			} break;
			case 'Tar' : {
				$zipfilename .= '.tar.gz';
				$inlineFiles = array();
				
				$excludeCommands = array("--exclude='image/cache/*'", "--exclude='system/cache/*'", "--exclude='vqmod/vqcache/*'", "--exclude='image/imagecache/*'");
				
				foreach ($rootFolders as $index => $rootFolder) {
					if (strrpos($rootFolder, '/') == strlen($rootFolder) - 1) $inlineFiles[] = $rootFolder . (count(scandir(ANYPORT_ROOT . '/' . $rootFolder)) != 2 ? '*' : '');
					else $inlineFiles[] = "'".$rootFolder."'";
				}
				$inlineFiles = implode(' ', $inlineFiles);
				
				exec('cd ' . ANYPORT_ROOT . '; tar ' . implode(' ', $excludeCommands) . ' -cf ' . $tempdir . '/' . $zipfilename . ' ' . $inlineFiles);
			}
		}
		return $tempdir.'/'.$zipfilename;
	}
	public function createFinalArchive($files, $exportType = 'Backup') {
		$check = false;
		foreach ($files as $file) {
			if ($file !== false) { $check = true; break; }
		}
		if (!$check) return false;
		
		$this->now = empty($this->now) ? time() : $this->now;
		$now = $this->now;
		$zipfilename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_'.$exportType.'_AnyPort';
		$tempDir = ANYPORT_ROOT.'/temp';
		
		$zipEngine = $this->getArchiveEngine();
		
		switch ($zipEngine) {
			case 'ZipArchive' : {
				$zipfilename .= '.zip';
				$zip = new ZipArchive();
				if ($zip->open($tempDir.'/'.$zipfilename, ZIPARCHIVE::OVERWRITE) !== true) {
					return false;
				}
				
				foreach ($files as $file) {
					if ($file === false) continue;
					$fileInfo = pathinfo($file);
					if (!$zip->addFile($file, $fileInfo['basename'])) return false;	
				}
				$zip->close();
			} break;
			case 'Tar' : {
				$zipfilename .= '.tar.gz';
				$inlineFiles = array();
				foreach ($files as $file) {
					if ($file === false) continue;
					$inlineFiles[] = basename($file);
				}
				$inlineFiles = implode(' ', $inlineFiles);
				
				exec('cd ' . $tempDir . '; tar -cf ' . $tempDir.'/'.$zipfilename . ' ' . $inlineFiles);
			}
		}
		
		return $tempDir.'/'.$zipfilename;
	}
	public function getListOfTables($readable = true) {
		$productTables = $this->db->query('SHOW TABLES'); 
		$i = 0;
		$tables = array();
    	foreach($productTables->rows as $k => $table) {
			$tableName = array_values($table);
			$tableName = $tableName[0];
			$tableName = $readable ? ucwords(str_replace('_',' ',$tableName)) : $tableName;
			
			array_push($tables,$tableName);
			$i++; 
		}
		return $tables;
	}
	public function exportCSV(&$dataFiles, $col_sep = ",", $destinationFolder = '', $header_row = true, $row_sep = "\n", $qut = '"') {
		if (!is_string($destinationFolder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		if ((!is_array($dataFiles) && $this->array_depth($dataFiles) != 4) || empty($dataFiles)) return false; //throw new Exception($this->language->get('anyport_invalid_data'));
		$this->now = empty($this->now) ? time() : $this->now;
		$now = $this->now;
		$success = array();
		$destinationFolder = '/'.implode('/', $this->clearInvalidEntries(explode('/', $destinationFolder))).'/';
		
		foreach ($dataFiles as $name => $data) {
			// Add some data
			foreach($data as $sheet => $table) {
				$filename = $name == '' ? '' : '_'.$name;
				$filename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.$filename.'_'.$sheet.'_Backup_AnyPort';
				
				// Create new file
				$path = $destinationFolder.$filename.'.csv';
				$fh = fopen($path, 'a+');
				if ($fh === false) throw new Exception($this->language->get('anyport_no_file'));
				
				foreach ($table as $rowNum => $row) {
					if (!$header_row && $rowNum == 0) continue;
					$colIndex = 0;
					foreach ($row as $col) {
						//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $rowNum + 1, $col);
						$col = str_replace($qut, "$qut$qut", $col);
						$col = nl2br((($colIndex!=0) ? $col_sep : "" )."$qut$col$qut");
						$col = preg_replace('~[\r\n]+~', '', $col);
						
						$this->writeToFile($fh, $col, $path);
						
						$colIndex ++;
					}
					$this->writeToFile($fh, $row_sep, $path);
				}
				fclose($fh);
				$success[] = $filename.'.csv';
				unset($data[$sheet]);
			}
			$dataFiles[$name] = array();
		}
		unset($dataFiles);
		
		if (count($success) > 1) {
			//generate zip filename	
			$filename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_Backup_AnyPort_CSV.zip';
		} else {
			//keep original filename	
			$len = strrpos($success[0], '.');
			$filename = substr($success[0], 0, $len).'.zip';
		}
		
		//zip files here
		if (count($success) > 0) {
			$zipfile = $this->create_zip($success, $destinationFolder.$filename, false, $destinationFolder);
			
			foreach($success as $file) {
				unlink($destinationFolder.$file);
			}
			
			if ($zipfile === false) throw new Exception($this->language->get('anyport_unable_zip_file'));
			
			return $zipfile;
		}
		
		return false;
	}
	public function exportSQL($tables) {
		if ((!is_array($tables) && $this->array_depth($tables) != 1) || empty($tables)) return false; //throw new Exception($this->language->get('anyport_invalid_data'));
		$this->now = empty($this->now) ? time() : $this->now;
		$now = $this->now;
		$filename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_Backup_AnyPort_Database.sql';
		$tempDir = ANYPORT_ROOT.'/temp';
		$path = $tempDir.'/'.$filename;
		$fh = fopen($path, 'a');
		if ($fh === false) throw new Exception($this->language->get('anyport_no_file'));
		
		foreach ($tables as $table) {
			$oldtable = $table;
			$create = $this->db->query("SHOW CREATE TABLE `" . $table . "`");
			$output = str_replace(array("\r", "\r\n", "\n"), '', str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $create->row['Create Table']) . ";")."\n\n";
			
			if (DB_PREFIX) {
				if (strpos($table, DB_PREFIX) !== false) {
					$output = str_replace("IF NOT EXISTS `" . $table . "`", "IF NOT EXISTS `" . "{ANYPORT_DB_PREFIX}".substr($table, strlen(DB_PREFIX)) . "`", $output);
					$table = "{ANYPORT_DB_PREFIX}".substr($table, strlen(DB_PREFIX));
				}
			} else {
				$output = str_replace("IF NOT EXISTS `" . $table . "`", "IF NOT EXISTS `" . "{ANYPORT_DB_PREFIX}".substr($table, strlen(DB_PREFIX)) . "`", $output);
				$table = "{ANYPORT_DB_PREFIX}".$table;
			}
			
			$output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
			$this->writeToFile($fh, $output, $path);
			$offset = 0;
			do {
				$query = $this->db->query("SELECT * FROM `" . $oldtable . "` LIMIT ".$offset.",200");
				$offset += 200;
				foreach ($query->rows as $result) {
					$fields = '';
					
					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}
					
					$values = '';
					
					foreach (array_values($result) as $value) {
						$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\',	$value);
						$value = str_replace('\'', '\\\'',	$value);
						$value = str_replace('\\\n', '\n',	$value);
						$value = str_replace('\\\r', '\r',	$value);
						$value = str_replace('\\\t', '\t',	$value);			
						
						$values .= '\'' . $value . '\', ';
					}
					
					$output = 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
					$this->writeToFile($fh, $output, $path);
				}
				
				$output = "\n\n";
				$this->writeToFile($fh, $output, $path);
			} while($query->num_rows > 0);
		}
		fclose($fh);
		$success[] = $filename;
		
		//generate zip filename	
		$zipfilename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_Backup_AnyPort_Database.zip';
		
		//zip files here
		if (count($success) > 0) {
			$zipfile = $this->create_zip($success, $tempDir.'/'.$zipfilename, false, $tempDir.'/');
			
			foreach($success as $file) {
				unlink($tempDir.'/'.$file);
			}
			
			if ($zipfile === false) throw new Exception($this->language->get('anyport_unable_zip_file'));
			
			return $zipfile;
		}
		
		return false;
	}
	public function restoreDatabaseFromFile($file) {
		if ($file === false) throw new Exception($this->language->get('anyport_invalid_file'));
		
		$info = pathinfo($file);
		$tempDir = ANYPORT_ROOT.'/temp';
		$success = array();
		
		if ($info['extension'] == 'zip' || $info['extension'] == 'gz') {
			//decompression
			$this->seed += 123;
			mt_srand(time() + $this->seed);
			$decompressFolder = $tempDir.'/'.mt_rand();
			$tempDir = $decompressFolder;
			if (!mkdir($decompressFolder)) throw new Exception($this->language->get('anyport_temp_dir_error'));
			
			if ($info['extension'] == 'zip') {
				if (!$this->checkAvailableZipEngine('ZipArchive')) throw new Exception('ZipArchive is not supported by your server. Please enable it.');
				$zip = new ZipArchive();
				if($zip->open($file, ZIPARCHIVE::CREATE) !== true) throw new Exception($this->language->get('anyport_unable_zip_file_open'));
				if (!$zip->extractTo($decompressFolder)) throw new Exception($this->language->get('anyport_unable_zip_file_extract'));
				$zip->close();
			} else {
				if (!$this->checkAvailableZipEngine('Tar')) throw new Exception('Tar is not supported by your server. Please enable it.');
				exec('cd ' . $decompressFolder . '; tar -xf ' . $file);
			}
			
			//check the files
			$files = scandir($decompressFolder);
			
			foreach ($files as $tempFile) {
				if (in_array($tempFile, array('.', '..'))) continue;
				
				$tempInfo = pathinfo($tempFile);
				
				if ($tempInfo['extension'] != 'sql') throw new Exception($this->language->get('anyport_invalid_file'));
				$success[] = $tempFile;
			}
		} else if ($info['extension'] != 'sql') {
			throw new Exception($this->language->get('anyport_invalid_file'));
		} else {
			$success[] = $file;	
		}
		
		if (count($success) == 0) throw new Exception($this->language->get('anyport_invalid_file'));
		$result = array();
		
		set_error_handler(
			create_function(
				'$severity, $message, $file, $line',
				'throw new Exception($message . " in file " . $file . " on line " . $line);'
			)
		);
		
		foreach ($success as $sqlFile) {
			$path = $tempDir.'/'.$sqlFile;
			$fh = fopen($path, "r");
			if ($fh === false) throw new Exception($this->language->get('anyport_no_file'));
			
			while($read = fgets($fh)) {
				$read = trim($read);
				if ($read == '') continue;
				try {
					$query = $this->db->query(str_replace('`{ANYPORT_DB_PREFIX}', '`' . DB_PREFIX, $read));
				} catch (Exception $e) {
					$result[] = $e->getMessage();
				}
				/*if (stripos($read, 'CREATE TABLE IF NOT EXISTS') === FALSE) {
					$warningResult = $this->db->query("SHOW WARNINGS;");
					if ($warningResult->num_rows > 0) {
						if (is_bool($result)) $result = '';
						
						$result.=$warningResult->row['Message']." QUERY: ".$read."<br>";
						
					}
				}*/
			}
		}
		
		restore_error_handler();
		$result = empty($result) ? true : $result;
		
		return $result;
	}
	public function createDownload($file, $die = true) {
		return ($this->config->get('config_secure') ? 'https://' : 'http://') . ANYPORT_SERVER_NAME . '/temp/' . basename($file);
		
		/*
		$attachment_location = $file;
		if (file_exists($attachment_location)) {
			$attachment_info = pathinfo($attachment_location);
			header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
			header("Cache-Control: public"); // needed for i.e.
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: Binary");
			header("Content-Length:".filesize($attachment_location));
			header("Content-Disposition: attachment; filename=".$attachment_info['basename']);
			readfile($attachment_location);
			if ($die) die();        
		} else {
			die("Error: File not found.");
		} */
	}
	public function cleanTemp($tempDir = '../temp') {
		$files = scandir($tempDir);
		foreach ($files as $file) {
			if (!in_array($file, array('.', '..', 'index.html'))) {
				if (is_file($tempDir.'/'.$file)) unlink ($tempDir.'/'.$file);
				if (is_dir($tempDir.'/'.$file)) {
					$this->cleanTemp($tempDir.'/'.$file);	
					rmdir($tempDir.'/'.$file);
				}
			}
		}
	}
	public function restoreSystemFromFile($file, $type) {
		if ($file === false) throw new Exception($this->language->get('anyport_invalid_file'));
		
		$info = pathinfo($file);
		$tempDir = ANYPORT_ROOT.'/temp';
		$success = array();
		
		if ($info['extension'] != 'zip' && $info['extension'] != 'gz') throw new Exception($this->language->get('anyport_invalid_file'));
		//decompression
		$this->seed += 123;
		mt_srand(time() + $this->seed);
		$decompressFolder = $tempDir.'/'.mt_rand();
		$tempDir = $decompressFolder;
		if (!mkdir($decompressFolder)) throw new Exception($this->language->get('anyport_temp_dir_error'));
		
		if ($info['extension'] == 'zip') {
			if (!$this->checkAvailableZipEngine('ZipArchive')) throw new Exception('ZipArchive is not supported by your server. Please enable it.');
			$zip = new ZipArchive();
			if($zip->open($file, ZIPARCHIVE::CREATE) !== true) throw new Exception($this->language->get('anyport_unable_zip_file_open'));
			
			if (!$zip->extractTo($decompressFolder)) throw new Exception($this->language->get('anyport_unable_zip_file_extract'));
			$zip->close();
		} else {
			if (!$this->checkAvailableZipEngine('Tar')) throw new Exception('Tar is not supported by your server. Please enable it.');
			exec('cd ' . $decompressFolder . '; tar -xf ' . $file);
		}
		
		//check the files
		$files = scandir($decompressFolder);
		
		foreach ($files as $tempFile) {
			if (in_array($tempFile, array('.', '..'))) continue;
			
			$tempInfo = pathinfo($tempFile);
			
			if ($tempInfo['extension'] != 'zip' && $tempInfo['extension'] != 'gz') throw new Exception($this->language->get('anyport_invalid_file'));
			$success[] = $decompressFolder.'/'.$tempFile;
		}
		
		if (count($success) == 0) throw new Exception($this->language->get('anyport_invalid_file'));
		
		$result = array();
		$match = false; 
		foreach ($success as $index => $myfile) {
			if (stripos($myfile, 'Backup_AnyPort_Root') !== FALSE && in_array($type, array('FullRestore', 'FilesRestore'))) { $result[$index] = $this->model_tool_anyport->restoreRootFromFile($myfile); $match = true; }
			if (stripos($myfile, 'Backup_AnyPort_Database') !== FALSE && in_array($type, array('FullRestore', 'DatabaseRestore'))) { $result[$index] = $this->model_tool_anyport->restoreDatabaseFromFile($myfile); $match = true; }
		}
		
		if ($match) return $result;
		else throw new Exception($this->language->get('anyport_error_while_restoring'));
	}
	public function restoreRootFromFile($file) {
		if ($file === false) throw new Exception($this->language->get('anyport_invalid_file'));
		
		$info = pathinfo($file);
		
		if ($info['extension'] != 'zip' && $info['extension'] != 'gz') throw new Exception($this->language->get('anyport_invalid_file'));
		//decompression
		$decompressFolder = ANYPORT_ROOT.'/';
		
		if ($info['extension'] == 'zip') {
			if (!$this->checkAvailableZipEngine('ZipArchive')) throw new Exception('ZipArchive is not supported by your server. Please enable it.');
			$zip = new ZipArchive();
			if($zip->open($file, ZIPARCHIVE::CREATE) !== true) throw new Exception($this->language->get('anyport_unable_zip_file_open'));
			
			if (!$zip->extractTo($decompressFolder)) throw new Exception($this->language->get('anyport_unable_zip_file_extract'));
			$zip->close();
		} else {
			if (!$this->checkAvailableZipEngine('Tar')) throw new Exception('Tar is not supported by your server. Please enable it.');
			exec('cd ' . $decompressFolder . '; tar -xf ' . $file);
		}

		return true;
	}
	public function getTables(&$data, $tables = array()) {
		if (!is_array($tables) && $this->array_depth($tables) != 1) throw new Exception($this->language->get('anyport_invalid_data'));
		if (empty($tables)) $tables = $this->db->query('SHOW TABLES'); 
		
		foreach ($tables as $table) {
			$fields = $this->db->query('SELECT * FROM `'.$table.'`');
			$fieldNames = $this->db->query('SHOW COLUMNS FROM `'.$table.'`');
			
			foreach ($fieldNames->rows as $name) {
				$data[$table][$table][0][$name['Field']] = $name['Field'];
			}
			
			foreach ($fields->rows as $index => $row) {
				$data[$table][$table][$index+1] = $row;
			}
		}
	}
	public function exportXLS(&$dataFiles, $destinationFolder = '') {
		if (!is_string($destinationFolder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		if ((!is_array($dataFiles) && $this->array_depth($dataFiles) != 4) || empty($dataFiles)) return false; // throw new Exception($this->language->get('anyport_invalid_data'));
		
		require_once(ANYPORT_ROOT.'/vendors/phpexcel/PHPExcel.php');
		$this->now = empty($this->now) ? time() : $this->now;
		$now = $this->now;
		$success = array();
		$destinationFolder = '/'.implode('/', $this->clearInvalidEntries(explode('/', $destinationFolder))).'/';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		
		if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) throw new Exception($this->language->get('anyport_unable_cache'));
		
		//Generate files
		foreach ($dataFiles as $name => $data) {
			$filename = $name == '' ? '' : '_'.$name;
			$filename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.$filename.'_Backup_AnyPort_XLS';
			
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			// Set document properties
			$objPHPExcel->getProperties()
						->setCreator($this->user->getUserName())
						->setLastModifiedBy($this->user->getUserName())
						->setTitle($filename)
						->setSubject($filename)
						->setDescription($name." backup for Office 97, 2003 and later, generated using PHPExcel and AnyPort.")
						->setKeywords("office 2003 97 openxml php phpexcel anyport")
						->setCategory("Backup");
			
			// Add some data
			$sheetNum = 0;
			foreach($data as $sheet => $table) {
				if ($sheetNum > 0) $objPHPExcel->createSheet($sheetNum);
				$sheet = substr($sheet, 0, 31);
				$objPHPExcel->setActiveSheetIndex($sheetNum)->setTitle($sheet);
				
				//option to hide empty sheets - uncomment if needed
				//if (empty($table[1])) $objPHPExcel->getActiveSheet()->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
				
				foreach ($table as $rowNum => $row) {
					$colNum = 0;
					foreach ($row as $col) {
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $rowNum + 1, $col);
						$colNum++;
						unset($row[$colNum]);
					}
					unset($table[$rowNum]);
				}
				
				$sheetNum++;
				unset($data[$sheet]);
			}
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			try {
				$file = $filename.'.xls';
				$objWriter->save($destinationFolder.$file);
				$success[] = $file;
				$objPHPExcel->disconnectWorksheets();
				unset($objPHPExcel);
				unset($objWriter);
				$dataFiles[$name] = array();
			} catch(Exception $e) {
				foreach($success as $file) {
					unlink($destinationFolder.$file);
				}
				throw $e;
			}
		}
		unset($dataFiles);
		
		if (count($success) > 1) {
			//generate zip filename	
			$filename = date('Y-m-d_H-i-s', $now).'_'.ANYPORT_SERVER_NAME_READABLE.'_Backup_AnyPort_XLS.zip';
		} else {
			//keep original filename	
			$len = strrpos($success[0], '.');
			$filename = substr($success[0], 0, $len).'.zip';
		}
		
		//zip files here
		if (count($success) > 0) {
			$zipfile = $this->create_zip($success, $destinationFolder.$filename, false, $destinationFolder);
			
			foreach($success as $file) {
				unlink($destinationFolder.$file);
			}
			
			if ($zipfile === false) throw new Exception($this->language->get('anyport_unable_zip_file'));
			
			return $zipfile;
		}
		
		return false;
	}
	public function getStandardFile($file, $arrayName) {
		$allowedExts = array("txt", "xls", "xlsx", "zip", "csv", "gz");
		$name = $file['name'][$arrayName]['StandardFile'];
		$explode = explode(".", $name);
		$extension = end($explode);
		$result = false;
		if ($file['size'][$arrayName]['StandardFile'] <= $this->returnMaxUploadSize() && in_array($extension, $allowedExts)) { //file limit = post_max_size - 512KB
			if ($file['error'][$arrayName]['StandardFile'] > 0) throw new Exception("Upload Error Code: " . $file['error'][$arrayName]['StandardFile']);
			$dest = ANYPORT_ROOT.'/temp/'.$name;
			if (!move_uploaded_file($file['tmp_name'][$arrayName]['StandardFile'], $dest)) throw new Exception($this->language->get('anyport_unable_upload'));
			else $result = $dest;
		} else throw new Exception($this->language->get('anyport_invalid_file'));
		
		return $dest;
	}
	public function returnMaxUploadSize($readable = false) {
		$upload = $this->return_bytes(ini_get('upload_max_filesize'));
		$post = $this->return_bytes(ini_get('post_max_size'));
		
		if ($upload >= $post) return $readable ? $this->sizeToString($post - 524288) : $post - 524288;
		else return $readable ? $this->sizeToString($upload) : $upload;
	}
	public function returnMaxAllowedMemory($maxlimit = -1) {
		$memlimit = $this->return_bytes(ini_get('memory_limit')) - memory_get_usage() - 1024;
		return ($memlimit > $maxlimit && $maxlimit > -1 ? $maxlimit : $memlimit);
	}
	private function create_zip($files = array(),$destination = '',$overwrite = false,$destinationFolder = '../temp/') {
		//FUNCTION FOUND FROM: http://davidwalsh.name/create-zip-php
		if (!is_string($destinationFolder)) throw new Exception($this->language->get('anyport_folder_not_string'));
		if (!is_string($destination)) throw new Exception($this->language->get('anyport_destination_not_string'));
		if (!is_array($files) && $this->array_depth($files) != 1) throw new Exception($this->language->get('anyport_invalid_data'));
		
		$destinationFolder = '/'.implode('/', $this->clearInvalidEntries(explode('/', $destinationFolder))).'/';
		$destination = '/'.implode('/', $this->clearInvalidEntries(explode('/', $destination)));
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
			//make sure the file exists
				if(file_exists($destinationFolder.$file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			
			$zipEngine = $this->getArchiveEngine();
		
			switch ($zipEngine) {
				case 'ZipArchive' : {
					//create the archive
					$zip = new ZipArchive();
					if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
						return false;
					}
					//add the files
					foreach($valid_files as $file) {
						$zip->addFile($destinationFolder.$file,$file);
					}
					//debug
					//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
					
					//close the zip -- done!
					$zip->close();
				} break;
				case 'Tar' : {
					$destination = substr($destination, 0, strlen($destination) - 4) . '.tar.gz';
					
					foreach ($valid_files as $i => $file) {
						$valid_files[$i] = "'" . $file . "'";
					}
					
					$inlineFiles = implode(' ', $valid_files);
					
					exec('cd ' . dirname($destination) . '; tar -cf ' . basename($destination) . ' ' . $inlineFiles);
				}
			}
			
			//check to make sure the file exists
			if (file_exists($destination)) return $destination;
			else return false;
		}
		else return false;
	}
	private function generateUpToDateMimeArray($url){ //FUNCTION FROM Josh Sean @ http://www.php.net/manual/en/function.mime-content-type.php
		$s=array('gz' => 'application/x-gzip');
		foreach(@explode("\n",@file_get_contents($url))as $x)
			if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1)
				for($i=1;$i<$c;$i++)
					$s[$out[1][$i]]=$out[1][0];
		return ($s)?$s:false;
	}
	public function clearInvalidEntries($folders) {
		$result = array();
		foreach	($folders as $folder) {
			if ($folder != '') {
				$result[] = trim($folder);	
			}
		}
		return $result;
	}
	private function array_depth($array) { //FROM Jeremy Ruten @ http://stackoverflow.com/questions/262891/is-there-a-way-to-find-how-how-deep-a-php-array-is
		$max_depth = 1;
	
		if (is_array($array)) {
			foreach ($array as $value) {
				if (is_array($value)) {
					$depth = $this->array_depth($value) + 1;
		
					if ($depth > $max_depth) {
						$max_depth = $depth;
					}
				}
			}
		}
		return $max_depth;
	}
	private function writeToFile(&$fh, &$data, &$path) {
		if (fwrite($fh, $data) === false) {
			fclose($fh);
			unlink($path);
			throw new Exception($this->language->get('anyport_unable_write_file'));
		}
	}
	private function downloadFile($source, $destination) {
		$fh = fopen($destination, 'w+');
		if ($fh === false) throw new Exception($this->language->get('anyport_no_file'));
		$fr = fopen($source, 'r');
		if ($fr === false) throw new Exception($this->language->get('anyport_no_file'));
		
		while(!feof($fr)) { 
			$buffer = fread($fr, 524288);
			if ($buffer === false) throw new Exception($this->language->get('anyport_no_file'));
			
			$this->writeToFile($fh, $buffer, $destination);
		}
		
		fclose($fh);
		fclose($fr);
	}
	private function return_bytes($val) { //from http://php.net/manual/en/function.ini-get.php
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
	
		return $val;
	}
	private function sizeToString($size) {
		$count = 0;
		for ($i = $size; $i >= 1024; $i /= 1024) $count++;
		switch ($count) {
			case 0 : $suffix = ' B'; break;
			case 1 : $suffix = ' KB'; break;
			case 2 : $suffix = ' MB'; break;
			case 3 : $suffix = ' GB'; break;
			case ($count >= 4) : $suffix = ' TB'; break;
		}
		return round($i, 2).$suffix;
	}
	private function addFolderToZip(&$zipArchive, &$rootPath, $dir, $zipPath, $zipMode) {
		
		$result = false;
		if (is_dir($rootPath.$dir)) {
			if ($dh = opendir($rootPath.$dir)) {
				//Add the directory
				$result = $zipArchive->addEmptyDir($dir);
				
				// Loop through all the files
				while (($file = readdir($dh)) !== false && $result) {
					if( ($file == ".") || ($file == "..") || ($dir == "image/cache") || ($dir == "system/cache") || ($dir == "vqmod/vqcache") || ($dir == "imagecache")) continue;
					$result = $this->addFolderToZip($zipArchive, $rootPath, $dir . "/" . $file, $zipPath, $zipMode);
				}
			}
		} else {
			// Add the files
			if (is_file($rootPath.$dir)) $result = $zipArchive->addFile($rootPath . $dir, $dir);
			
			// File descriptor fix, from: http://php.net
			
			if(($this->fileCount++) == 200) { // the file descriptor limit 
				$zipArchive->close(); 
				if($zipArchive = new ZipArchive()) { 
					$zipArchive->open($zipPath, $zipMode); 
					$this->fileCount = 0; 
				} 
			} 
			
			
		}
		
		return $result;
	}
	public function getSetting($name) {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting($name);
		foreach($setting as $setKey => $setVal) {
			set_error_handler(create_function( '$severity, $message, $file, $line', 'return;' )); $setting[$setKey] = @unserialize(@base64_decode($setVal)) === false ? unserialize($setVal) : unserialize(base64_decode($setVal)); restore_error_handler();
		}
		return $setting;
	}
	public function editSetting($name, $value) {
		$this->load->model('setting/setting');
		$requestPost = $value;
		foreach($value as $serKey => $serVal) {
			$requestPost[$serKey] = base64_encode(serialize($serVal));
		}
		$this->model_setting_setting->editSetting($name, $requestPost);	
	}
	public function getProgress($error = NULL) {
		$result = array(
			'error' => false,
			'message' => '',
			'done' => false
		);
		if (!empty($error)) {
			$result['error'] = true;
			$result['message'] = $error;
			$result['done'] = false;
			$this->setProgress($result);
		} else {
			if (file_exists(ANYPORT_ROOT . '/temp/anyport_progress.txt')) {
				$fh = fopen(ANYPORT_ROOT . '/temp/anyport_progress.txt', 'r');
				$data = fread($fh, filesize(ANYPORT_ROOT . '/temp/anyport_progress.txt'));
				$result = json_decode($data, true);
			} else {
				$fh = fopen(ANYPORT_ROOT . '/temp/anyport_progress.txt', 'w');
				fwrite($fh, json_encode($result));
			}
			fclose($fh);
		}
		return $result;
	}
	public function setProgress($progress) {
		$fh = fopen(ANYPORT_ROOT . '/temp/anyport_progress.txt', 'w');
		fwrite($fh, json_encode($progress));
		fclose($fh);
	}
	
	public function deleteProgress() {
		if (file_exists(ANYPORT_ROOT . 'temp/excelport_progress.txt')) unlink(ANYPORT_ROOT . 'temp/excelport_progress.txt');	
	}
	
	/* HUMAN-READABLE PRODUCTS FUNCTIONS */
	public function getProducts(&$data) {
		
		if (!defined('VERSION')) return false; //we're going to need that for later, when we're using the version to determine the queries for the products
		$valid = false;
		switch (VERSION) {
			case '1.5.4' : { $valid = true; } break;
			case '1.5.4.1' : { $valid = true; } break;
		}
		if (!$valid) throw new Exception(str_replace('{VERSION}', '1.5.4.1', $this->language->get('text_feature_unsupported')));
		
		$data = array(
			'Products' => array_merge(
				$this->getProductsTableData(), 
				$this->getProductsDescriptionsTableData(), 
				$this->getProductsImagesTableData(),
				$this->getProductsCategoriesTableData(),
				$this->getProductsCategoriesDescriptionsTableData(),
				$this->getProductsDownloadsTableData(),
				$this->getProductsDownloadsDescriptionsTableData(),
				$this->getProductsManufacturersTableData(),
				$this->getProductsRewardsTableData(),
				$this->getProductsSpecialsTableData(),
				$this->getProductsDiscountsTableData()
			)
		);
		
		$this->refineProducts($data);
	}
	private function getProductsTableData() {
		
		$data = array('Products' => array());
		
		$mappings = array(
			'p_product_id' => 'Product ID',
			'p_model' => 'Model',
			'p_sku' => 'SKU',
			'p_upc' => 'UPC',
			'p_ean' => 'EAN',
			'p_jan' => 'JAN',
			'p_isbn' => 'ISBN',
			'p_mpn' => 'MPN',
			'p_location' => 'Location',
			'p_quantity' => 'Quantity',
			'p_stock_status_id' => 'Stock Status ID',
			'p_image' => 'Image',
			'p_manufacturer_id' => 'Manufacturer ID',
			'p_shipping' => 'Shipping',
			'p_price' => 'Price',
			'p_points' => 'Points',
			'p_tax_class_id' => 'Tax Class ID',
			'p_date_available' => 'Date Available',
			'p_weight' => 'Weight',
			'p_weight_class_id' => 'Weight Class ID',
			'p_length' => 'Length',
			'p_width' => 'Width',
			'p_height' => 'Height',
			'p_length_class_id' => 'Length Class ID',
			'p_subtract' => 'Substract',
			'p_minimum' => 'Minimum',
			'p_sort_order' => 'Sort Order',
			'p_status' => 'Status',
			'p_date_added' => 'Date Added',
			'p_date_modified' => 'Date Modified',
			'p_viewed' => 'Viewed',
			'patt' => 'Language to Attribute IDs',
			'pdes' => 'Language to Description IDs',
			'pdis' => 'Discounts IDs',
			'pima' => 'Images IDs',
			'popt' => 'Option to Values IDs',
			'prel' => 'Related Products IDs',
			'prew' => 'Rewards IDs',
			'pspe' => 'Specials IDs',
			'ptocat' => 'Categories IDs',
			'ptodow' => 'Downloads IDs',
			'ptolay' => 'Store to Layout IDs',
			'ptosto' => 'Stores IDs'
		);
		
		$sql = '
		SELECT
		
		p.product_id AS p_product_id, p.model AS p_model, p.sku AS p_sku, p.upc AS p_upc, p.ean AS p_ean, p.jan AS p_jan, p.isbn AS p_isbn, p.mpn AS p_mpn, p.location AS p_location, p.quantity AS p_quantity, p.stock_status_id AS p_stock_status_id, p.image AS p_image, p.manufacturer_id AS p_manufacturer_id, p.shipping AS p_shipping, p.price AS p_price, p.points AS p_points, p.tax_class_id AS p_tax_class_id, p.date_available AS p_date_available, p.weight AS p_weight, p.weight_class_id AS p_weight_class_id, p.length AS p_length, p.width AS p_width, p.height AS p_height, p.length_class_id AS p_length_class_id, p.subtract AS p_subtract, p.minimum AS p_minimum, p.sort_order AS p_sort_order, p.status AS p_status, p.date_added AS p_date_added, p.date_modified AS p_date_modified, p.viewed AS p_viewed, 
		
		GROUP_CONCAT(DISTINCT (SELECT language.code FROM language WHERE patt.language_id = language.language_id LIMIT 0,1), (SELECT ":"), patt.attribute_id ORDER BY patt.language_id ASC, patt.attribute_id ASC) AS patt, 
		GROUP_CONCAT(DISTINCT (SELECT language.code FROM language WHERE pdes.language_id = language.language_id LIMIT 0,1), (SELECT ":"), pdes.product_id ORDER BY pdes.language_id ASC, pdes.product_id ASC) AS pdes, 
		GROUP_CONCAT(DISTINCT pdis.product_discount_id ORDER BY pdis.product_discount_id ASC) AS pdis, 
		GROUP_CONCAT(DISTINCT pima.product_image_id ORDER BY pima.product_image_id ASC) AS pima, 
		GROUP_CONCAT(DISTINCT 
		
			popt.product_option_id, 
			(SELECT IF(
				(SELECT poptval2.product_option_value_id FROM product_option_value poptval2 WHERE popt.product_option_id = poptval2.product_option_id LIMIT 0,1), 
				":", 
				"")
			), 
			(SELECT IFNULL(
				GROUP_CONCAT(poptval.product_option_value_id ORDER BY poptval.product_option_value_id ASC SEPARATOR "+"), 
				""
			) 
			
			FROM product_option_value poptval 
			WHERE popt.product_option_id = poptval.product_option_id) 
			ORDER BY popt.product_option_id ASC) AS popt, 
		GROUP_CONCAT(DISTINCT prel.related_id ORDER BY prel.related_id ASC) AS prel, 
		GROUP_CONCAT(DISTINCT prew.product_reward_id ORDER BY prew.product_reward_id ASC) AS prew, 
		GROUP_CONCAT(DISTINCT pspe.product_special_id ORDER BY pspe.product_special_id ASC) AS pspe, 
		GROUP_CONCAT(DISTINCT ptocat.category_id ORDER BY ptocat.category_id ASC) AS ptocat, 
		GROUP_CONCAT(DISTINCT ptodow.download_id ORDER BY ptodow.download_id ASC) AS ptodow, 
		GROUP_CONCAT(DISTINCT ptolay.store_id, (SELECT ":"), ptolay.layout_id ORDER BY ptolay.store_id ASC, ptolay.layout_id ASC) AS ptolay, 
		GROUP_CONCAT(DISTINCT ptosto.store_id ORDER BY ptosto.store_id ASC) AS ptosto
		
		FROM product p 
		
		LEFT JOIN  product_attribute patt ON (p.product_id = patt.product_id) 
		LEFT JOIN  product_description pdes ON (p.product_id = pdes.product_id) 
		LEFT JOIN  product_discount pdis ON (p.product_id = pdis.product_id) 
		LEFT JOIN  product_image pima ON (p.product_id = pima.product_id) 
		LEFT JOIN  product_option popt ON (p.product_id = popt.product_id) 
		LEFT JOIN  product_related prel ON (p.product_id = prel.product_id) 
		LEFT JOIN  product_reward prew ON (p.product_id = prew.product_id) 
		LEFT JOIN  product_special pspe ON (p.product_id = pspe.product_id) 
		LEFT JOIN  product_to_category ptocat ON (p.product_id = ptocat.product_id) 
		LEFT JOIN  product_to_download ptodow ON (p.product_id = ptodow.product_id) 
		LEFT JOIN  product_to_layout ptolay ON (p.product_id = ptolay.product_id) 
		LEFT JOIN  product_to_store ptosto ON (p.product_id = ptosto.product_id) 
		
		GROUP BY p.product_id ORDER BY p.product_id';
		
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Products'][0][$raw] = $formatted;
		}
		
		if($result) {
			
			$products = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($products as $index => $product) {
				foreach ($mappings as $raw => $formatted) {
					$data['Products'][$index + 1][$raw] = $product[$raw];
				}
			}
			
		}
	
		return $data;
	}	
	private function getProductsDescriptionsTableData() {
		
		//get languages
		$sql = 'SELECT language_id, code FROM language ORDER BY language_id ASC';
		$result = $this->db->query($sql);
		
		if($result) {
			$languages = (isset($result->rows)) ? $result->rows : $result->row;
			$data = array();
			
			$mappings = array(
				'pdes_id' => 'Desciption ID',
				'pdes_name' => 'Product Name',
				'pdes_description' => 'Description',
				'pdes_meta_description' => 'Meta Description',
				'pdes_meta_keyword' => 'Meta Keyword',
				'pdes_tag' => 'Tags'
			);
			
			foreach ($languages as $language) {
				$sheetName = 'Product Descriptions ('.$language['code'].')';
				$data[$sheetName] = array();
				
				$sql = 'SELECT
		
						CONCAT(pdes.language_id, ":", pdes.product_id) AS pdes_id, pdes.name AS pdes_name, pdes.description AS pdes_description, pdes.meta_description AS pdes_meta_description, pdes.meta_keyword AS pdes_meta_keyword, pdes.tag AS pdes_tag
						
						FROM product_description pdes
						
						WHERE pdes.language_id = "'.$language['language_id'].'"
						
						ORDER BY pdes_id ASC
						';
						
				$result = $this->db->query($sql);
				
				foreach ($mappings as $raw => $formatted) {
					$data[$sheetName][0][$raw] = $formatted;
				}
				
				if($result) {
			
					$descriptions = (isset($result->rows)) ? $result->rows : $result->row;
					
					foreach ($descriptions as $index => $description) {
						foreach ($mappings as $raw => $formatted) {
							$data[$sheetName][$index + 1][$raw] = $description[$raw];
						}
					}
					
				}
			}
			return $data;
		}
		return array();
	}	
	private function getProductsImagesTableData() {
		
		$data = array();
		
		$mappings = array(
			'pima_product_image_id' => 'Image ID',
			'pima_image' => 'Image',
			'pima_sort_order' => 'Sort Order'
		);
			
		$data['Images'] = array();
		
		$sql = 'SELECT

				pima.product_image_id AS pima_product_image_id, pima.image AS pima_image, pima.sort_order AS pima_sort_order
								
				FROM product_image pima
				
				ORDER BY pima.product_image_id
				';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Images'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$images = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($images as $index => $image) {
				foreach ($mappings as $raw => $formatted) {
					$data['Images'][$index + 1][$raw] = $image[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsCategoriesTableData() {
		
		$data = array();
		
		$mappings = array(
			'c_category_id' => 'Category ID',
			'c_image' => 'Image',
			'c_parent_id' => 'Parent ID',
			'c_top' => 'Top',
			'c_column' => 'Column',
			'c_sort_order' => 'Sort Order',
			'c_status' => 'Status',
			'c_date_added' => 'Date Added',
			'c_date_modified' => 'Date Modified',
			'cdes' => 'Language to Category Description IDs',
			'ctolay' => 'Category to Layout IDs',
			'ctosto' => 'Category to Store IDs'
		);
			
		$data['Categories'] = array();
		
		$sql = 'SELECT
		
		c.category_id as c_category_id, c.image as c_image, c.parent_id as c_parent_id, c.top as c_top, c.column as c_column, c.sort_order as c_sort_order, c.status as c_status, c.date_added as c_date_added, c.date_modified as c_date_modified,
		
		GROUP_CONCAT(DISTINCT (SELECT language.code FROM language WHERE cdes.language_id = language.language_id LIMIT 0,1), (SELECT ":"), cdes.category_id ORDER BY cdes.language_id ASC, cdes.category_id ASC) AS cdes, 
		GROUP_CONCAT(DISTINCT ctolay.store_id, (SELECT ":"), ctolay.layout_id ORDER BY ctolay.store_id ASC, ctolay.layout_id ASC) AS ctolay, 
		GROUP_CONCAT(DISTINCT ctosto.store_id ORDER BY ctosto.store_id ASC) AS ctosto
		
		FROM category c 
		
		LEFT JOIN  category_description cdes ON (c.category_id = cdes.category_id) 
		LEFT JOIN  category_to_layout ctolay ON (c.category_id = ctolay.category_id) 
		LEFT JOIN  category_to_store ctosto ON (c.category_id = ctosto.category_id) 
		
		GROUP BY c.category_id ORDER BY c.category_id
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Categories'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$categories = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($categories as $index => $category) {
				foreach ($mappings as $raw => $formatted) {
					$data['Categories'][$index + 1][$raw] = $category[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsCategoriesDescriptionsTableData() {
		
		//get languages
		$sql = 'SELECT language_id, code FROM language ORDER BY language_id ASC';
		$result = $this->db->query($sql);
		
		if($result) {
			$languages = (isset($result->rows)) ? $result->rows : $result->row;
			$data = array();
			
			$mappings = array(
				'cdes_id' => 'Category ID',
				'cdes_name' => 'Category Name',
				'cdes_description' => 'Description',
				'cdes_meta_description' => 'Meta Description',
				'cdes_meta_keyword' => 'Meta Keyword'
			);
			
			foreach ($languages as $language) {
				$sheetName = 'Category Descriptions ('.$language['code'].')';
				$data[$sheetName] = array();
				
				$sql = 'SELECT
		
						CONCAT(cdes.language_id, ":", cdes.category_id) AS cdes_id, cdes.name AS cdes_name, cdes.description AS cdes_description, cdes.meta_description AS cdes_meta_description, cdes.meta_keyword AS cdes_meta_keyword
						
						FROM category_description cdes
						
						WHERE cdes.language_id = "'.$language['language_id'].'"
						
						ORDER BY cdes_id
						';
						
				$result = $this->db->query($sql);
				
				foreach ($mappings as $raw => $formatted) {
					$data[$sheetName][0][$raw] = $formatted;
				}
				
				if($result) {
			
					$categoryDescriptions = (isset($result->rows)) ? $result->rows : $result->row;
					
					foreach ($categoryDescriptions as $index => $categoryDescription) {
						foreach ($mappings as $raw => $formatted) {
							$data[$sheetName][$index + 1][$raw] = $categoryDescription[$raw];
						}
					}
					
				}
			}
			return $data;
		}
		return array();
	}	
	private function getProductsDownloadsTableData() {
		
		$data = array();
		
		$mappings = array(
			'd_download_id' => 'Download ID',
			'd_filename' => 'Filename',
			'd_mask' => 'Mask',
			'd_remaining' => 'Remaining',
			'd_dade_added' => 'Date Added',
			'ddes' => 'Language to Description IDs'
		);
			
		$data['Downloads'] = array();
		
		$sql = 'SELECT
		
		d.download_id as d_download_id, d.filename as d_filename, d.mask as d_mask, d.remaining as d_remaining, d.date_added as d_dade_added, 
		
		GROUP_CONCAT(DISTINCT (SELECT language.code FROM language WHERE ddes.language_id = language.language_id LIMIT 0,1), (SELECT ":"), ddes.download_id ORDER BY ddes.language_id ASC, ddes.download_id ASC) AS ddes 
		
		FROM download d 
		
		LEFT JOIN  download_description ddes ON (d.download_id = ddes.download_id) 
		
		GROUP BY d.download_id ORDER BY d.download_id
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Downloads'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$downloads = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($downloads as $index => $download) {
				foreach ($mappings as $raw => $formatted) {
					$data['Downloads'][$index + 1][$raw] = $download[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsDownloadsDescriptionsTableData() {
		
		//get languages
		$sql = 'SELECT language_id, code FROM language ORDER BY language_id ASC';
		$result = $this->db->query($sql);
		
		if($result) {
			$languages = (isset($result->rows)) ? $result->rows : $result->row;
			$data = array();
			
			$mappings = array(
				'ddes_id' => 'Download Description ID',
				'ddes_name' => 'Download Name'
			);
			
			foreach ($languages as $language) {
				$sheetName = 'Download Descriptions ('.$language['code'].')';
				$data[$sheetName] = array();
				
				$sql = 'SELECT
		
						CONCAT(ddes.language_id, ":", ddes.download_id) AS ddes_id, ddes.name AS ddes_name
						
						FROM download_description ddes
						
						WHERE ddes.language_id = "'.$language['language_id'].'"
						
						ORDER BY ddes_id ASC
						';
						
				$result = $this->db->query($sql);
				
				foreach ($mappings as $raw => $formatted) {
					$data[$sheetName][0][$raw] = $formatted;
				}
				
				if($result) {
			
					$downloadDescriptions = (isset($result->rows)) ? $result->rows : $result->row;
					
					foreach ($downloadDescriptions as $index => $downloadDescription) {
						foreach ($mappings as $raw => $formatted) {
							$data[$sheetName][$index + 1][$raw] = $downloadDescription[$raw];
						}
					}
					
				}
			}
			return $data;
		}
		return array();
	}	
	private function getProductsManufacturersTableData() {
		
		$data = array();
		
		$mappings = array(
			'm_manufacturer_id' => 'Manufacturer ID',
			'm_name' => 'Name',
			'm_image' => 'Image',
			'm_sort_order' => 'Sort Order',
			'mtosto' => 'Stores IDs'
		);
			
		$data['Manufacturers'] = array();
		
		$sql = 'SELECT
		
		m.manufacturer_id as m_manufacturer_id, m.name as m_name, m.image as m_image, m.sort_order as m_sort_order, 
		
		GROUP_CONCAT(DISTINCT mtosto.store_id ORDER BY mtosto.store_id ASC) AS mtosto 
		
		FROM manufacturer m 
		
		LEFT JOIN  manufacturer_to_store mtosto ON (m.manufacturer_id = mtosto.manufacturer_id) 
		
		GROUP BY m.manufacturer_id ORDER BY m.manufacturer_id
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Manufacturers'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$manufacturers = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($manufacturers as $index => $manufacturer) {
				foreach ($mappings as $raw => $formatted) {
					$data['Manufacturers'][$index + 1][$raw] = $manufacturer[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsRewardsTableData() {
		
		$data = array();
		
		$mappings = array(
			'prew_product_reward_id' => 'Reward ID',
			'prew_customer_group_id' => 'Customer Group ID',
			'prew_points' => 'Points'
		);
			
		$data['Rewards'] = array();
		
		$sql = 'SELECT
		
		prew.product_reward_id as prew_product_reward_id, prew.customer_group_id as prew_customer_group_id, prew.points as prew_points
		
		FROM product_reward prew 
		
		GROUP BY prew.product_reward_id ORDER BY prew.product_reward_id
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Rewards'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$rewards = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($rewards as $index => $reward) {
				foreach ($mappings as $raw => $formatted) {
					$data['Rewards'][$index + 1][$raw] = $reward[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsSpecialsTableData() {
		
		$data = array();
		
		$mappings = array(
			'pspe_product_special_id' => 'Special ID',
			'pspe_customer_group_id' => 'Customer Group ID',
			'pspe_priority' => 'Priority',
			'pspe_price' => 'Price',
			'pspe_date_start' => 'Date Start',
			'pspe_date_end' => 'Date End'
		);
			
		$data['Specials'] = array();
		
		$sql = 'SELECT
		
		pspe.product_special_id as pspe_product_special_id, pspe.customer_group_id as pspe_customer_group_id, pspe.priority as pspe_priority, pspe.price as pspe_price, pspe.date_start as pspe_date_start, pspe.date_end as pspe_date_end
		
		FROM product_special pspe 
		
		GROUP BY pspe.product_special_id ORDER BY pspe.product_special_id ASC, pspe.priority ASC, pspe.date_start ASC
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Specials'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$specials = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($specials as $index => $special) {
				foreach ($mappings as $raw => $formatted) {
					$data['Specials'][$index + 1][$raw] = $special[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function getProductsDiscountsTableData() {
		
		$data = array();
		
		$mappings = array(
			'pdis_product_discount_id' => 'Discount ID',
			'pdis_customer_group_id' => 'Customer Group ID',
			'pdis_quantity' => 'Quantity',
			'pdis_priority' => 'Priority',
			'pdis_price' => 'Price',
			'pdis_date_start' => 'Date Start',
			'pdis_date_end' => 'Date End'
		);
			
		$data['Discounts'] = array();
		
		$sql = 'SELECT
		
		pdis.product_discount_id as pdis_product_discount_id, pdis.customer_group_id as pdis_customer_group_id, pdis.quantity as pdis_quantity, pdis.priority as pdis_priority, pdis.price as pdis_price, pdis.date_start as pdis_date_start, pdis.date_end as pdis_date_end
		
		FROM product_discount pdis 
		
		GROUP BY pdis.product_discount_id ORDER BY pdis.product_discount_id ASC, pdis.priority ASC, pdis.date_start ASC
		';
				
		$result = $this->db->query($sql);
		
		foreach ($mappings as $raw => $formatted) {
			$data['Discounts'][0][$raw] = $formatted;
		}
		
		if($result) {
	
			$discounts = (isset($result->rows)) ? $result->rows : $result->row;
			
			foreach ($discounts as $index => $discount) {
				foreach ($mappings as $raw => $formatted) {
					$data['Discounts'][$index + 1][$raw] = $discount[$raw];
				}
			}
			
		}
			
		return $data;
		
	}	
	private function refineProducts(&$data) {
		
		
		$refinmentAreas = array(
			array('Products', 'Product Descriptions', 'pdes', 'pdes_id'),
			array('Categories', 'Category Descriptions', 'cdes', 'cdes_id'),
			array('Downloads', 'Download Descriptions', 'ddes', 'ddes_id')
		);
		
		
		foreach ($refinmentAreas as $area) {
			foreach ($data['Products'] as $sheet => $sheetData) {
				if ($sheet != $area[0]) continue;
				$modifyIndex = 1;
				foreach ($sheetData as $index => $row) {
					if ($index == 0) continue;
					
					$pdes = explode(',', $row[$area[2]]);
					$data['Products'][$area[0]][$index][$area[2]] = array();
					foreach ($pdes as $pdesItem) {
						$parts = explode(':', $pdesItem);
						
						foreach ($data['Products'][$area[1].' ('.$parts[0].')'] as $descriptionIndex => $descriptionData) {
							if ($descriptionIndex == 0) continue;
							
							$descriptionParts = explode(':', $descriptionData[$area[3]]);
							
							if (!empty($descriptionParts[1])) {
								if ($descriptionParts[1] == $parts[1]) {
									$data['Products'][$area[1].' ('.$parts[0].')'][$descriptionIndex][$area[3]] = $modifyIndex;
									$data['Products'][$area[0]][$index][$area[2]][] = $parts[0].':'.$modifyIndex;
									$modifyIndex ++;
								}
							}
						}
					}
					$data['Products'][$area[0]][$index][$area[2]] = implode(',', $data['Products'][$area[0]][$index][$area[2]]);
				}
			}
		}
	} 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>