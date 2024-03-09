<?php
class ControllerModuleEasyPhoto extends Controller {

	private $path = 'module/easyphoto';
	private $module = 'extension/module';

	public function install() {

	}

	public function index() {
		$data = $this->load->language($this->path);
		$a = 0;

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('easyphoto', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link($this->module, 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link($this->module, 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => '<span style="color:#00b32d;font-weight:bold;">Easy Photo</span>',
			'href' => $this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link($this->path, 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link($this->module, 'token=' . $this->session->data['token'], 'SSL');

		$vars = array(
			'easyphoto_status',
			'easyphoto_key',
			'easyphoto_direct',
			'easyphoto_main',
			'easyphoto_name',
			'easyphoto_separate', //3.1+
			'easyphoto_from', //3.1-
			'easyphoto_language',
		);

 		foreach($vars as $var){
			if (isset($this->request->post[$var])) {
				$data[$var] = $this->request->post[$var];
			} else {
				$data[$var] = $this->config->get($var);
			}
		}

		$data['fields'] = array('name','model','sku','upc','ean','jan','isbn','mpn','location');
		$data['easyphoto_key'] = 'test';

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['more_info'] = false;

		$this->data = $data;
		$this->data['column_left'] = '';
		$this->template = $this->path . '.tpl';
		$this->children = array('common/header','common/footer');
		$this->data['action'] = $this->url->link($this->path);
		$this->data['token'] = $this->session->data['token'];
		
		$this->response->setOutput($this->render());
	}

	private function get_http_response_code($url) {
	    $headers = get_headers($url);
	    return substr($headers[0], 9, 3);
	}

	public function getForm($product_images) {
		$data = $this->load->language($this->path);
		$this->document->addScript('view/javascript/easyphoto/jquery.magnific-popup.min.js');
		$this->document->addStyle('view/javascript/easyphoto/magnific-popup.css');
		$data['product_images'] = $product_images['product_images'];
		$data['main_photo'] = $product_images['image'];
		$data['main_thumb'] = $product_images['thumb'];
		$data['token'] = $this->session->data['token'];
		$data['easyphoto_status'] = $this->config->get('easyphoto_status');
		$data['upload_link'] = "index.php?route=" . $this->path . "/upload";
		$data['resize_link'] = "index.php?route=" . $this->path . "/resize_rename";
		$data['clear_cart_link'] = "index.php?route=" . $this->path . "/clear_cart";
		$data['rotate_link'] = "index.php?route=" . $this->path . "/rotate";
		$data['easy_product_id'] = !isset($this->request->get['product_id']) ? false : "&product_id=" . $this->request->get['product_id'];
		$data['easyphoto_main'] = $this->config->get('easyphoto_main');
		$data['easyphoto_not_delete'] = $this->config->get('easyphoto_not_delete');
		$data['easyphoto_for'] = false;

		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if(isset($this->request->get['product_id'])){
			$language_id = $this->config->get('easyphoto_language') ? $this->config->get('easyphoto_language') : '1';

			$product_name_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $this->request->get['product_id'] . "' AND language_id = '" . $language_id . "'");

			if(isset($product_name_query->row['name'])){
				$data['easyphoto_for'] = $product_name_query->row['name'];
			}
		}

		$data['trash_photos'] = array();
		if(isset($this->request->get['product_id'])){
			$in_product = array();
			$in_product[$product_images['image']] = $product_images['image'];
			foreach($product_images['product_images'] as $image_item){
				$in_product[$image_item['image']] = $image_item['image'];
			}
			$dir = DIR_IMAGE . $this->getDirectory() . $this->request->get['product_id'] . '/';
			if (file_exists($dir)){
				foreach(glob($dir . '*') as $file){
					if (file_exists($file)){
						$image = str_replace(DIR_IMAGE, "", $file);
						if(!in_array($image, $in_product)){
							$data['trash_photos'][$file] = array(
								'image' => $image,
								'thumb' => $this->model_tool_image->resize($image, 100, 100)
							);
						}
					}
				}
			}
		}

		$this->data = $data; 
		$this->template = 'module/easyphoto_form.tpl'; 
		
		$this->response->setOutput($this->render());
	}

	public function upload() {
		if(isset($this->request->files["easyphoto"]["name"])){
				if (!is_dir($this->getDirectory(1))) {
					mkdir($this->getDirectory(1), 0777, true);
				}
				if (!is_dir($this->getDirectory(1) . "tmp/")) {
					mkdir($this->getDirectory(1) . "tmp/", 0777, true);
				}
		 		move_uploaded_file($this->request->files["easyphoto"]["tmp_name"], $this->getDirectory(1) . "tmp/" . $this->request->files["easyphoto"]["name"]);
		 }
	}

	public function resize_rename($from_model = array()) {
		if(!$from_model){
			$photo = $this->request->get['photo'];
		}else{
			$photo = $from_model['image'];
		}

		$ext = "." . strtolower(preg_replace('/^.*\.(.*)$/s', '$1', $photo));
		$directory = $this->getDirectory() . 'tmp/';

		
		if(isset($this->request->get['product_id']) or isset($from_model['product_id'])){
			if(!$from_model){
				$product_id = $this->request->get['product_id'];
			}else{
				$product_id = $from_model['product_id'];
			}
			$language_id = $this->config->get('easyphoto_language') ? $this->config->get('easyphoto_language') : '1';

			if($this->config->get('easyphoto_name')){
				if(!$this->config->get('easyphoto_from')){
					$easyphoto_from = "name";
				}else{
					$easyphoto_from = $this->config->get('easyphoto_from');
				}
				if($easyphoto_from == "name"){
					$product_name_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "' AND language_id = '" . $language_id . "'");
					$name_from = isset($product_name_query->row['name'])?$product_name_query->row['name']:false;
				}else{
					$product_name_query = $this->db->query("SELECT " . $easyphoto_from . " FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "'");
					$name_from = isset($product_name_query->row[$easyphoto_from])?$product_name_query->row[$easyphoto_from]:false;

					if(empty($name_from)){
						$product_name_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "' AND language_id = '" . $language_id . "'");
						$name_from = isset($product_name_query->row['name'])?$product_name_query->row['name']:false;
					}
				}
			}

			if($name_from && $this->config->get('easyphoto_name')){
				$photo_name = $this->transform($name_from);
			}else{
				$photo_name = $this->transform($photo);
				$photo_name = str_replace("." . $ext, '', $photo_name);
			}

			$photo_dir_id = $this->getDirectory(1) . $product_id;
			if (!is_dir($photo_dir_id)) {
				mkdir($photo_dir_id, 0777, true);
			}

			if(!$this->config->get('easyphoto_separate')){
				$easyphoto_separate = "-";
			}else{
				$easyphoto_separate = trim($this->config->get('easyphoto_separate'));
				$easyphoto_separate = str_replace(array('"',"'","&","/"), "-", $easyphoto_separate);
			}
			//3.1-

			$all_photos = scandir($photo_dir_id);
			$counter = count($all_photos)-1;
			//3.1+
			$new_photo_name = $photo_name . $easyphoto_separate . $counter . $ext;
			//3.1-
			if (is_file($photo_dir_id . '/' . $new_photo_name)) {
				$new_photo_name = "alt_" . $new_photo_name;
			}

			if(!$from_model){
				$old_file = $this->getDirectory(1) . "tmp/" . $photo;
			}else{
				$old_file = DIR_IMAGE . $photo;
			}

			if(is_file($old_file)){
				if(!$from_model){
					rename($old_file, $photo_dir_id . '/' . $new_photo_name);
				}else{
					copy($old_file, $photo_dir_id . '/' . $new_photo_name);
				}
			}

			$photo = $new_photo_name;
			$directory = $this->getDirectory() . $product_id . '/';
		}

		$image = array();
		$image['mt'] = str_replace(".", "", microtime(true));
		$this->load->model('tool/image');
		$image['image'] = $directory . $photo;

		if(!$from_model){
			if (is_file(DIR_IMAGE . $image['image'])) {
				$image['thumb'] = $this->model_tool_image->resize($image['image'], 100, 100);
			} else {
				$image['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($image));
		}else{
			return $image['image'];
		}
	}

	public function clear_cart(){
		$count_cart = 0;

		if(isset($this->request->get['product_image_delete'])){
			foreach($this->request->get['product_image_delete'] as $filename){
				$file = DIR_IMAGE . $filename['image'];
				if (file_exists($file)){
					unlink($file);
					$count_cart++;
				}
			}
		}

		echo $count_cart;
	}

	public function clear_tmp(){
		$dir = DIR_IMAGE . $this->getDirectory() . 'tmp/';
		if (file_exists($dir)){
			foreach(glob($dir . '*') as $file){
				unlink($file);
			}
		}
	}

	public function transform($string){
		if($string){
			$translit=array(
				"А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d","Е"=>"e","Ё"=>"e","Ж"=>"zh","З"=>"z","И"=>"i","Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t","У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch","Ш"=>"sh","Щ"=>"shch","Ъ"=>"","Ы"=>"y","Ь"=>"","Э"=>"e","Ю"=>"yu","Я"=>"ya",
				"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"zh","з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"shch","ъ"=>"","ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
				"A"=>"a","B"=>"b","C"=>"c","D"=>"d","E"=>"e","F"=>"f","G"=>"g","H"=>"h","I"=>"i","J"=>"j","K"=>"k","L"=>"l","M"=>"m","N"=>"n","O"=>"o","P"=>"p","Q"=>"q","R"=>"r","S"=>"s","T"=>"t","U"=>"u","V"=>"v","W"=>"w","X"=>"x","Y"=>"y","Z"=>"z"
			);
			$string = str_replace("_", "-", $string);
			$string = mb_strtolower($string, 'UTF-8');
			$string = strip_tags($string);
			$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
			$string = strtr($string,$translit);
			$string = preg_replace("/[^a-zA-Z0-9_]/i","-",$string);
			$string = preg_replace("/\-+/i","-",$string);
			$string = preg_replace("/(^\-)|(\-$)/i","",$string);
			$string = preg_replace('/-{2,}/', '-', $string);
			$string = trim($string, "-");

			return $string;
		}
	}

	public function rotate() {

		$status = false;

		$degrees = $this->request->get['degrees'];
		$ext = strtolower(preg_replace('/^.*\.(.*)$/s', '$1', $this->request->get['photo']));

		$rotateFilename = str_replace("." . $ext, "", $this->request->get['photo']);
		$new_file = str_replace(array("_r90", "_r270"), "", $rotateFilename) . "_r" . $degrees . "." . $ext;
		$rotateFilename = DIR_IMAGE . $new_file;

		copy(DIR_IMAGE . $this->request->get['photo'], $rotateFilename);

		if($ext == 'png'){
		   header('Content-type: image/png');
		   $source = imagecreatefrompng($rotateFilename);
		   $bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
		   $rotate = imagerotate($source, $degrees, $bgColor);
		   imagesavealpha($rotate, true);
		   imagepng($rotate, $rotateFilename);
			 $status = true;
		}

		if($ext == 'jpg' || $ext == 'jpeg'){
		   header('Content-type: image/jpeg');
		   $source = imagecreatefromjpeg($rotateFilename);
		   $rotate = imagerotate($source, $degrees, 0);
		   imagejpeg($rotate, $rotateFilename);
			 $status = true;
		}

		if($status){
			imagedestroy($source);
			imagedestroy($rotate);

			$image = array();
			$this->load->model('tool/image');
			$image['image'] = $new_file;
			$image['mt'] = str_replace(".", "", microtime(true));

			if (is_file(DIR_IMAGE . $image['image'])) {
				$image['thumb'] = $this->model_tool_image->resize($image['image'], 100, 100);
			} else {
				$image['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($image));
		}

	}

	public function getDirectory($full = false) {

		$directory_set = $this->config->get('easyphoto_direct')?rtrim(ltrim($this->config->get('easyphoto_direct'), '/'), '/'):'easyphoto';
		$directory = "data/" . $directory_set . "/";

		if($full){
			$directory = DIR_IMAGE . $directory;
		}

		return $directory;
	}

}
