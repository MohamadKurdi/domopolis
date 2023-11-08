<?
class ControllerCatalogCollection extends Controller { 
	private $error = array();

	public function collectionBuilder(){
		$this->load->model('catalog/collection');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');

		echoLine('[i] Collection Builder By Valera and Victor');

		$this->db->query("UPDATE product SET mpn = trim(mpn) WHERE 1");
		$this->db->query("UPDATE product SET collection_id = 0 WHERE LENGTH(mpn)=0");
		$this->db->query("UPDATE collection SET name = trim(name) WHERE 1");

		$query = $this->db->query("SELECT collection_id, name FROM collection");

		foreach ($query->rows as $row){
			$query2 = $this->db->query("SELECT product_id FROM product WHERE mpn LIKE '" . $this->db->escape($row['name']) . "' LIMIT 5");
			$query3 = $this->db->query("SELECT collection_id FROM collection WHERE parent_id = '" . (int)$row['collection_id'] . "' LIMIT 5");

			if (!$query2->num_rows && !$query3->num_rows){
				$query4 = $this->db->query("SELECT virtual FROM collection WHERE collection_id = '" . (int)$row['collection_id'] . "' AND virtual = 1");

				if (!$query4->num_rows){
					echoLine('[i] Коллекция ' . $row['name'] . ' (' . (int)$row['collection_id'] . ') не существует! удаляем!');
				} else {
					echoLine('[i] Виртуальная Коллекция ' . $row['name'] .' (' . (int)$row['collection_id'] . ') пропускаем!');
				}

			}

			$this->db->query("UPDATE product SET collection_id='" . (int)$row['collection_id'] . "' WHERE mpn LIKE '". $this->db->escape($row['name']) ."'");

		}

		foreach ($this->model_localisation_language->getLanguages() as $language){
			$this->db->query("INSERT IGNORE INTO collection_description (`collection_id`, `language_id`) SELECT DISTINCT collection_id, '" . $language['language_id'] . "' FROM collection");
		}


		$query = $this->db->query("SELECT DISTINCT mpn from product WHERE LENGTH(mpn)>0");

		foreach ($query->rows as $row){
			$collection_id = false;
			$collection_query = $this->db->query("SELECT collection_id FROM collection WHERE name LIKE '" . $this->db->escape($row['mpn']) . "' LIMIT 1");
			
			if ($collection_query->num_rows){
				$collection_id = $collection_query->row['collection_id'];
			}

			if ($collection_id){
				echoLine('[i] Коллекция ' . $row['mpn'] . ' существует, id: ' . $collection_id);
			} else {
				echoLine('[i] Коллекция ' . $row['mpn'] . ' не существует, создаем');

				$this->db->query("INSERT INTO collection (`name`) VALUES ('" . $this->db->escape($row['mpn']) . "')");
					$collection_id = $this->db->getLastId();

					echoLine('[i] Коллекция ' . $collection_id . ' создали, оце молодці');

					foreach ($this->model_localisation_language->getLanguages() as $language){
						$this->db->query("INSERT INTO collection_description (`collection_id`, `language_id`) VALUES ('" . $collection_id . "', '" . (int)$language['language_id'] . "')");
					}

					foreach ($this->model_setting_store->getStores() as $store){
						$this->db->query("INSERT INTO collection_to_store (`collection_id`, `store_id`) VALUES ('" . $collection_id . "', '" . (int)$store['store_id'] . "')");
					}
				}

				$this->db->query("UPDATE product SET collection_id='" . (int)$collection_id . "' WHERE mpn LIKE '". $this->db->escape($row['mpn']) ."'");

				$query4 = $this->db->query("SELECT collection_id, manufacturer_id, image FROM product WHERE collection_id = '". $collection_id ."' AND manufacturer_id > 0 AND LENGTH(image) > 0 ORDER BY sort_order DESC LIMIT 1");

				foreach ($query4->rows as $row){

					$query5 = $this->db->query("UPDATE collection SET 
						image 					= '". $this->db->escape(trim($row['image'])) ."', 
						manufacturer_id 		= '". (int)$row['manufacturer_id'] ."'
						WHERE collection_id 	= '" . (int)$collection_id. "'
						AND not_update_image 	= 0");

					if ($this->db->countAffected()){
						echoLine('[i] Коллекция ' . $collection_id . ' обновили картинку и бренд коллекции');
					} else {
						echoLine('[i] Коллекция ' . $collection_id . ' ничего не обновляли');
					}


				}
			}
		}

		public function index() {

			$this->language->load('catalog/collection');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/collection');

			$this->getList();
		}

		public function insert() {
			$this->language->load('catalog/collection');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/collection');

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_collection->addCollection($this->request->post);
				if (isset($this->request->post['continue']) && $this->request->post['continue'] == '1') {
					$collection = $this->session->data['new_collection_id'];
					if ($collection_id) {
						unset($this->session->data['new_collection_id']);
						$this->session->data['success'] = $this->language->get('text_success');	
						$this->redirect($this->url->link('catalog/collection/update', 'token='.$this->session->data['token'].'&collection_id='.$collection_id, 'SSL'));
					}
				}


				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				$this->redirect($this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->getForm();
		}


		public function update() {
			$this->language->load('catalog/collection');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/collection');

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_collection->editCollection($this->request->get['collection_id'], $this->request->post);
				if (isset($this->request->post['continue']) && $this->request->post['continue'] == '1') {
					$collection_id = $this->request->get['collection_id'];
					$this->session->data['success'] = $this->language->get('text_success');	
					$this->redirect($this->url->link('catalog/collection/update', 'token='.$this->session->data['token'].'&collection_id='.$collection_id, 'SSL'));
				}

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}


				if(isset($this->request->post['apply']) and $this->request->post['apply'])
					$this->redirect($this->url->link('catalog/collection/update', 'token=' . $this->session->data['token'] . '&collection_id=' . $this->request->get['collection_id'] . $url, 'SSL'));
				else
					$this->redirect($this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->getForm();
		}


		public function delete() {
			$this->language->load('catalog/collection');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/collection');

			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $collection_id) {
					$this->model_catalog_collection->deleteCollection($collection_id);
				}

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				$this->redirect($this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->getList();
		}

		protected function getList() {
			// Нужно получть список всех производителей
			$this->load->model('catalog/manufacturer');
			$this->data['brandArray'] = $this->model_catalog_manufacturer->getManufacturers();
			$this->data['control_brand'] = isset($_GET['manufacturer_id']) ? $_GET['manufacturer_id'] : 0; 

			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'name';
			}

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url),
				'separator' => ' :: '
			);

			$this->data['insert'] = $this->url->link('catalog/collection/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/collection/delete', 'token=' . $this->session->data['token'] . $url);	

			$this->data['collections'] = array();

			$data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit'),
				'manufacturer_id' => isset($_GET['manufacturer_id']) ? $_GET['manufacturer_id'] : false
			);

			$this->load->model('tool/image');

			$collection_total = $this->model_catalog_collection->getTotalCollections($data);

			$results = $this->model_catalog_collection->getCollections($data);

			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('catalog/collection/update', 'token=' . $this->session->data['token'] . '&collection_id=' . $result['collection_id'] . $url, 'SSL')
				);

				$descriptions = $this->model_catalog_collection->getCollectionDescriptions($result['collection_id']);

				$_lngth['description'] = mb_strlen($descriptions['2']['description'], 'UTF-8');
				$_lngth['title'] = mb_strlen($descriptions['2']['seo_title'], 'UTF-8');
				$_lngth['meta_description'] = mb_strlen($descriptions['2']['meta_description'], 'UTF-8');			

				$parent = array();
				if ($result['parent_id']>0) {
					$parent = $this->model_catalog_collection->getCollection($result['parent_id']);
				} else {
					$parent['name'] = false;				
				}

				$this->data['collections'][] = array(
					'collection_id' => $result['collection_id'],
					'_lngth'		  => $_lngth,
					'name'            => $result['name'],
					'alternate_name'  => $result['alternate_name'],
					'parent_name'     => $parent['name'],
					'mname'     	  => $result['mname'],
					'type'			  => $descriptions['2']['type'],
					'not_update_image'=> $result['not_update_image'],
					'virtual'		  => $result['virtual'],
					'no_brand'		  => $result['no_brand'],
					'image'			  => $this->model_tool_image->resize($result['image'], 100, 100),
					'banner'		  => $this->model_tool_image->resize($result['banner'], 100, 100),
					'sort_order'      => $result['sort_order'],
					'selected'        => isset($this->request->post['selected']) && in_array($result['collection_id'], $this->request->post['selected']),
					'action'          => $action,
				);
			}	

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_no_results'] = $this->language->get('text_no_results');

			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_sort_order'] = $this->language->get('column_sort_order');
			$this->data['column_action'] = $this->language->get('column_action');		

			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$url = '';

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['sort_name'] = $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			$this->data['sort_sort_order'] = $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			$pagination = new Pagination();
			$pagination->total = $collection_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;

			$this->template = 'catalog/collection_list.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}

		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_default'] = $this->language->get('text_default');
			$this->data['text_image_manager'] = $this->language->get('text_image_manager');
			$this->data['text_browse'] = $this->language->get('text_browse');
			$this->data['text_clear'] = $this->language->get('text_clear');			
			$this->data['text_percent'] = $this->language->get('text_percent');
			$this->data['text_amount'] = $this->language->get('text_amount');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_store'] = $this->language->get('entry_store');
			$this->data['entry_keyword'] = $this->language->get('entry_keyword');
			$this->data['entry_image'] = $this->language->get('entry_image');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');

			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			} 
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';

			}

			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
			} else {
				$this->data['error_name'] = '';
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url),
				'separator' => ' :: '
			);

			if (!isset($this->request->get['collection_id'])) {
				$this->data['action'] = $this->url->link('catalog/collection/insert', 'token=' . $this->session->data['token'] . $url);
			} else {
				$this->data['action'] = $this->url->link('catalog/collection/update', 'token=' . $this->session->data['token'] . '&collection_id=' . $this->request->get['collection_id'] . $url);
			}

			$this->data['cancel'] = $this->url->link('catalog/collection', 'token=' . $this->session->data['token'] . $url);

			if (isset($this->request->get['collection_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$collection_info = $this->model_catalog_collection->getCollection($this->request->get['collection_id']);
			}


			$this->data['token'] = $this->session->data['token'];

			$this->load->model('localisation/language');		
			$this->data['languages'] = $this->model_localisation_language->getLanguages();

			if (isset($this->request->post['collection_description'])) {
				$this->data['collection_description'] = $this->request->post['collection_description'];
			} elseif (isset($this->request->get['collection_id'])) {
				$this->data['collection_description'] = $this->model_catalog_collection->getCollectionDescriptions($this->request->get['collection_id']);
			} else {
				$this->data['collection_description'] = array();
			}

			$this->load->model('kp/reward');
			if (isset($this->request->post['reward'])) {
				$this->data['rewards'] = $this->request->post['reward'];
			} elseif (isset($this->request->get['collection_id'])) {
				$this->data['rewards'] = $this->model_kp_reward->getEntityRewards($this->request->get['collection_id'], 'co');
			} else {
				$this->data['rewards'] = array();
			}

			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
			} elseif (!empty($collection_info)) {
				$this->data['name'] = $collection_info['name'];
			} else {	
				$this->data['name'] = '';
			}

			if (isset($this->request->post['not_update_image'])) {
				$this->data['not_update_image'] = $this->request->post['not_update_image'];
			} elseif (!empty($collection_info)) {
				$this->data['not_update_image'] = $collection_info['not_update_image'];
			} else {
				$this->data['not_update_image'] = 0;
			}

			if (isset($this->request->post['virtual'])) {
				$this->data['virtual'] = $this->request->post['virtual'];
			} elseif (!empty($collection_info)) {
				$this->data['virtual'] = $collection_info['virtual'];
			} else {
				$this->data['virtual'] = 0;
			}

			if (isset($this->request->post['no_brand'])) {
				$this->data['no_brand'] = $this->request->post['no_brand'];
			} elseif (!empty($collection_info)) {
				$this->data['no_brand'] = $collection_info['no_brand'];
			} else {
				$this->data['no_brand'] = 0;
			}

			$this->load->model('setting/store');

			$this->data['stores'] = $this->model_setting_store->getStores();

			if (isset($this->request->post['collection_store'])) {
				$this->data['collection_store'] = $this->request->post['collection_store'];
			} elseif (isset($this->request->get['collection_id'])) {
				$this->data['collection_store'] = $this->model_catalog_collection->getCollectionStores($this->request->get['collection_id']);
			} else {
				$this->data['collection_store'] = array(0);
			}	

			if (isset($this->request->post['keyword'])) {
				$this->data['keyword'] = $this->request->post['keyword'];
			} elseif (!empty($collection_info)) {
				$this->data['keyword'] = $this->model_catalog_collection->getKeyWords($this->request->get['collection_id']);
			} else {
				$this->data['keyword'] = array();
			}

			if (isset($this->request->post['parent'])) {
				$this->data['parent'] = $this->request->post['parent'];
			} elseif (!empty($collection_info)) {
				$this->data['parent'] = $collection_info['parent']?($collection_info['parent']. ' / ' . $collection_info['parent_mname']):'';
			} else {	
				$this->data['parent'] = '';
			}

			if (isset($this->request->post['manufacturer'])) {
				$this->data['manufacturer'] = $this->request->post['manufacturer'];
			} elseif (!empty($collection_info)) {
				$this->data['manufacturer'] = $collection_info['manufacturer'];
			} else {	
				$this->data['manufacturer'] = '';
			}

			if (isset($this->request->post['manufacturer_id'])) {
				$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
			} elseif (!empty($collection_info)) {
				$this->data['manufacturer_id'] = $collection_info['manufacturer_id'];
			} else {	
				$this->data['manufacturer_id'] = '';
			}

			if (isset($this->request->post['parent_id'])) {
				$this->data['parent_id'] = $this->request->post['parent_id'];
			} elseif (!empty($collection_info)) {
				$this->data['parent_id'] = $collection_info['parent_id'];
			} else {	
				$this->data['parent_id'] = '';
			}

			$this->load->model('tool/image');

			// Images
			if (isset($this->request->post['collection_image'])) {
				$collection_images = $this->request->post['collection_image'];
			} elseif (isset($this->request->get['collection_id'])) {
				$collection_images = $this->model_catalog_collection->getCollectionImages($this->request->get['collection_id']);
			} else {
				$collection_images = array();
			}

			$this->data['collection_images'] = array();

			foreach ($collection_images as $collection_image) {
				$this->data['collection_images'][] = array(
					'image'      => $collection_image['image'],
					'thumb'      => $this->model_tool_image->resize($collection_image['image'], 100, 100),
					'sort_order' => $collection_image['sort_order']
				);
			}

			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

			if (isset($this->request->post['image'])) {
				$this->data['image'] = $this->request->post['image'];
			} elseif (!empty($collection_info)) {
				$this->data['image'] = $collection_info['image'];
			} else {
				$this->data['image'] = '';
			}

			if (isset($this->request->post['banner'])) {
				$this->data['banner'] = $this->request->post['banner'];
			} elseif (!empty($collection_info)) {
				$this->data['banner'] = $collection_info['banner'];
			} else {
				$this->data['banner'] = '';
			}					

			if (isset($this->request->post['image'])) {
				$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
			} elseif (!empty($collection_info) && $collection_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($collection_info['image'], 100, 100);
			} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}

			if (isset($this->request->post['banner'])) {
				$this->data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['banner'], 100, 100);
			} elseif (!empty($collection_info) && $collection_info['banner']) {
				$this->data['banner_thumb'] = $this->model_tool_image->resize($collection_info['banner'], 100, 100);
			} else {
				$this->data['banner_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}

			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
			} elseif (!empty($collection_info)) {
				$this->data['sort_order'] = $collection_info['sort_order'];
			} else {
				$this->data['sort_order'] = '';
			}

			$this->template = 'catalog/collection_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}  

		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/collection')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
				$this->error['name'] = $this->language->get('error_name');
			}

			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}

		public function autocomplete() {
			$json = array();

			if (isset($this->request->get['filter_name'])) {
				$this->load->model('catalog/collection');
				$this->load->model('catalog/manufacturer');

				$data = array(
					'filter_name' 				=> $this->request->get['filter_name'],
					'filter_manufacturer_id' 	=> $this->request->get['filter_manufacturer_id'],
					'start'       				=> 0,
					'limit'       				=> 20
				);

				$results = $this->model_catalog_collection->getCollections($data);

				foreach ($results as $result) {

					$manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);

					if ($manufacturer){
						$mname = $manufacturer['name'];
					}

					$json[] = array(
						'collection_id' => $result['collection_id'], 
						'name'        => strip_tags(html_entity_decode($result['name'].' / '.$mname, ENT_QUOTES, 'UTF-8'))
					);
				}		
			}

			$sort_order = array();

			foreach ($json as $key => $value) {
				$sort_order[$key] = $value['name'];
			}

			array_multisort($sort_order, SORT_ASC, $json);

			$this->response->setOutput(json_encode($json));
		}		

		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/collection')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			$this->load->model('catalog/product');

			foreach ($this->request->post['selected'] as $collection_id) {
				$product_total = $this->model_catalog_product->getTotalProductsByCollectionId($collection_id);

				if ($product_total) {
					$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
				}	
			}

			if (!$this->error) {
				return true;
			} else {
				return false;
			}  
		}








	}