<?php
class ControllerModuleAnyport extends Controller {

	protected function index() {
		$this->language->load('module/anyport');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['data']['AnyPort'] = str_replace('http', 'https', $this->config->get('AnyPort'));
		} else {
			$this->data['data']['AnyPort'] = $this->config->get('AnyPort');
		}
		$this->data['currenttemplate'] =  $this->config->get('config_template');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/anyport.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/anyport.tpl';
		} else {
			$this->template = 'default/template/module/anyport.tpl';
		}
		
		$this->render();
	}

	public function dropboxcron() {
		if (empty($_SERVER['argc']) || (int)$_SERVER['argc'] !== 1) exit;
		
		if (defined('VERSION'))
		switch (VERSION) {
			case '1.5.0' : { exit; } break;
			case '1.5.0.1' : { exit; } break;
			case '1.5.0.2' : { exit; } break;
			case '1.5.0.3' : { exit; } break;
			case '1.5.0.4' : { exit; } break;
			case '1.5.0.5' : { exit; } break;
			case '1.5.1' : { exit; } break;
			case '1.5.1.1' : { exit; } break;
			case '1.5.1.2' : { exit; } break;
		}
		
		$this->load->model('setting/setting');
		$this->load->model('tool/anyport');
		
		$setting = $this->model_tool_anyport->getSetting('AnyPort');
		
		if (empty($setting['AnyPort']['Settings']['Dropbox']['Enable']) || empty($setting['AnyPort']['AutoBackup']['Enable']) || $setting['AnyPort']['AutoBackup']['Enable'] == "No") exit;
		
		
		$rootFolders = (empty($setting['AnyPort']['AutoBackup']['Dropbox']['Folders'])) ? NULL : $setting['AnyPort']['AutoBackup']['Dropbox']['Folders'];
					
		if (!empty($rootFolders) && $rootFolders == 'All') {
			$predefinedFolders = array(
				'admin/',
				'catalog/',
				'download/',
				'extension/',
				'extensions/',
				'image/',
				'install/',
				'system/',
				'temp/',
				'vendor/',
				'vendors/',
				'vqmod/',
				'.htaccess.txt',
				'.htaccess',
				'php.ini',
				'config.php',
				'error_log',
				'index.php'
			);
			$predefinedFolders[] = $setting['AnyPort']['AutoBackup']['AdminFolder'] . '/';
			$rootFolders = array();
			foreach ($predefinedFolders as $predefinedFolder) {
				if (file_exists(ANYPORT_ROOT.'/'.$predefinedFolder) && !in_array($predefinedFolder, $rootFolders)) array_push($rootFolders, $predefinedFolder);	
			}
		} else if (!empty($rootFolders) && $rootFolders == 'AllComplete') {
			$filesandfolders = scandir(ANYPORT_ROOT);
			$rootfolders = array();
			$rootfiles = array();
			foreach ($filesandfolders as $rootelement) {
				if (in_array($rootelement, array('.', '..', 'temp'))) continue;
				if (is_dir(ANYPORT_ROOT.'/'.$rootelement)) $rootfolders[$rootelement] = $rootelement.'/';
				else $rootfiles[$rootelement] = $rootelement;
			}
			$rootFolders = array_merge($rootfolders, $rootfiles);
		} else if (!empty($rootFolders) && $rootFolders == 'Txt') {
			$txtBackupConfig = './' . $setting['AnyPort']['AutoBackup']['AdminFolder'] . '/anyport.txt';
			if (!file_exists($txtBackupConfig)) {
				$predefinedFolders = array(
					'admin/',
					'catalog/',
					'download/',
					'extension/',
					'extensions/',
					'image/',
					'install/',
					'system/',
					'temp/',
					'vendor/',
					'vendors/',
					'vqmod/',
					'.htaccess.txt',
					'.htaccess',
					'php.ini',
					'config.php',
					'error_log',
					'index.php'
				);
				$predefinedFolders[] = $setting['AnyPort']['AutoBackup']['AdminFolder'] . '/';
				$rootFolders = array();
				foreach ($predefinedFolders as $predefinedFolder) {
					if (file_exists('./'.$predefinedFolder) && !in_array($predefinedFolder, $rootFolders)) array_push($rootFolders, $predefinedFolder);	
				}
			} else {
				$fh = fopen($txtBackupConfig, 'r');
				$rootFolders = array();
				while ($line = fgets($fh)) {
					$line = implode('/', $this->model_tool_anyport->clearInvalidEntries(explode('/', trim($line))));
					if (file_exists(ANYPORT_ROOT.'/'.$line) && !in_array($line, $rootFolders)) array_push($rootFolders, $line);
				}
			}
		} else if (!empty($rootFolders) && $rootFolders == 'None') {
			$rootFolders = array();
		}
		
		$data = $this->model_tool_anyport->getListOfTables(false);
		$exportType = 'CronBackup';
		
		$folder = $setting['AnyPort']['Settings']['Dropbox']['WorkingFolder'];
		
		try { 
			$this->model_tool_anyport->exportToDropbox($data, $rootFolders, $folder, true, $exportType);
		} catch(Exception $e) {
			//Write to error log
			$fh = fopen(ANYPORT_ROOT.'/' . $setting['AnyPort']['AutoBackup']['AdminFolder'] . '/anyport_error_log.txt', 'a');
			fwrite($fh, date('[Y-m-d H:i:s]').' Anyport Cron Backup Error: '.$e->getMessage()."\n");
			fclose($fh);
		}
		$this->model_tool_anyport->cleanTemp(ANYPORT_ROOT.'/temp');	
	}
}

?>