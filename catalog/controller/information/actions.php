<?php 
class ControllerInformationActions extends Controller {
	private $error = [];

	public function index() {  
		$this->language->load('information/actions');

		$this->load->model('catalog/actions');
		$this->load->model('setting/setting');		
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');

		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_action_end'] = $this->language->get('action_end');

		$this->data['text_day'] = $this->language->get('day');
		$this->data['text_hour'] = $this->language->get('hour');
		$this->data['text_minute'] = $this->language->get('minute');
		$this->data['text_sec'] = $this->language->get('sec');
		$this->data['text_byu'] = $this->language->get('byu');
		$this->data['text_last_actions'] = $this->language->get('last_actions');
		$this->data['text_in_gift'] = $this->language->get('in_gift');

		$this->data['text_no_data'] = $this->language->get('no_data');
		$this->data['text_archive'] = $this->language->get('archive');
		$this->data['text_action_sale_ended'] = $this->language->get('text_action_sale_ended');

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => ''
		);


		if (isset($this->request->get['actions_id'])) {
			$this->getAction($this->request->get['actions_id']);
		} else {
			if (isset($this->request->get['archive']) && $this->request->get['archive']) {
				$this->data['this_is_archive'] = true;
				$this->getList(true);
			} else {
				$this->data['this_is_archive'] = false;
				$this->getList();
			}
		}

		if ($this->error && isset($this->error['error'])) {
			$this->document->setTitle($this->error['error']);

			$actions_all = NULL;

			$this->data['heading_title'] = $this->language->get('text_error');
			$this->data['text_error'] = $this->language->get('text_error');
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->link('common/home');

			$this->template = 'error/not_found.tpl';

		} else {	

			$this->template = 'information/actions';

			if (isset($this->request->get['actions_id'])) {
				$this->load->model('design/layout');
				$layout_id = $this->model_catalog_actions->getActionsLayoutId($this->request->get['actions_id']);
				if (!$layout_id){				
					$layout_id = $this->model_design_layout->getLayout('information/actions');				
				}	

				if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
					$this->template = $template;
				}
			}								
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	public function getAction($actions_id) {
		foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		foreach ($this->language->loadRetranslate('actions/actions') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {			
			$sort = $this->config->get('sort_default');
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = $this->config->get('order_default');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}							

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

		$this->data['page_type'] = 'category';

		$actions_setting = $this->config->get('actions_setting');	
		$action_info = $this->model_catalog_actions->getActions($actions_id);

		if (!$action_info) {
			$this->error['error'] = $this->language->get('text_error');
			return;
		}

		if ($action_info['display_all_active']){
			$additionalActions = $this->model_catalog_actions->getAllActiveActions($actions_id);
		} else {
			$additionalActions = $this->model_catalog_actions->getAdditionalActions($actions_id);
		}

		foreach ($additionalActions as $k => $v) {
			$date_start = date( 'j', $v['date_start'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $v['date_start'] ));
			$date_end 	= date( 'j', $v['date_end'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $v['date_end'] ));

			if ($actions_setting['show_date']) {
				$date = sprintf($this->language->get('date_actions_format'), $date_start, $date_end);
			} else {
				$date = FALSE;
			}

			$additionalActions[$k]['date'] = $date;
			$additionalActions[$k]['thumb'] = $this->model_tool_image->resize($v['image'], $actions_setting['image_width'], $actions_setting['image_height']);
			$additionalActions[$k]['href'] = $this->url->link('information/actions', 'actions_id=' . $v['actions_id']);
			$additionalActions[$k]['caption'] = $v['caption'];
		}

		$this->data['text_read_more'] = $this->language->get('text_read_more');

		$this->data['additionalActions'] 	= $additionalActions;
		$this->data['product_related'] 		= [];

		if ($action_info['deletenotinstock'] || $action_info['only_in_stock']){
			$filter_in_stock = true;
		} else {
			$filter_in_stock = false;
		}

		$data = [				
				'filter_in_stock' 				=> $filter_in_stock,
				'no_child'      				=> true, 
				'sort'               			=> $sort,
				'order'              			=> $order,
				'start'              			=> ($page - 1) * $limit,
				'limit'              			=> $limit
		];

		if (!empty($action_info['ao_group'])){
			$data['filter_product_additional_offer'] = $action_info['ao_group'];
		} else {
			$data['filter_actions_id'] = $actions_id;
		}
		
		$results 		= $this->model_catalog_product->getProducts($data);
		$product_total 	=  $this->model_catalog_product->getTotalProducts($data);

		$this->data['product_related'] = $this->model_catalog_product->prepareProductToArray($results);		

		$this->data['product_groups'] = [];
		if (!empty($action_info['category_related_id']) && !$action_info['category_related_no_intersections']){
			
			$intersections = $this->model_catalog_category->getCategoriesIntersections($action_info['category_related_id']);

			foreach ($intersections as $intersection){
				$filter_data = [
					'filter_category_id' 			=> $action_info['category_related_id'],
					'filter_sub_category' 			=> true,
					'filter_category_id_intersect' 	=> $intersection['category_id'],
					'filter_sub_category_intersect' => true,
					'filterinstock' 				=> true,
					'no_child'      				=> true, 
					'sort'               			=> 'p.sort_order',
					'order'              			=> 'DESC',
					'start'              			=> 0,
					'limit'              			=> $action_info['category_related_limit_products']
				];

				$products = $this->model_catalog_product->getProducts($filter_data);

				if ($products){
					$this->data['product_groups'][] = [
						'name' 		=> $intersection['name'],
						'href' 		=> $this->url->link('product/category', 'path=' .$action_info['category_related_id'] . '&intersection_id=' . $intersection['category_id']),
						'products' 	=> $this->model_catalog_product->prepareProductToArray($products)
					];
				}
			}
		}

		if ($action_info['coupon']){
			$this->data['coupon'] = $action_info['coupon'];
			$this->data['text_use_promocode'] = $this->language->get('text_use_promocode');
		}

		if ($action_info['only_in_stock']){
			$this->data['text_only_in_stock'] = $this->language->get('text_only_in_stock');
		}

		if ($action_info['label']){
			$this->data['label'] 					= $action_info['label'];
			$this->data['label_background'] 		= $action_info['label_background'];
			$this->data['label_color'] 				= $action_info['label_color'];
			$this->data['label_text'] 				= $action_info['label_text'];
			$this->data['text_label_at_products'] 	= $this->language->get('text_label_at_products');
		}

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_actions'),
			'href'      => $this->url->link('information/actions'),
			'separator' => $this->language->get('text_separator')
		);			

		$this->data['text_relproduct_header'] = $this->language->get('text_relproduct_header');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_action_sale_ended'] = $this->language->get('text_action_sale_ended');

		$this->data['special'] = $this->url->link('product/special');

		if($action_info['title']) {
			$this->document->setTitle($action_info['title']);
		} else {
			$this->document->setTitle($action_info['caption']);
		}

		$this->document->addLink($this->url->link('information/actions','actions_id=' . $actions_id), 'canonical');

		$this->data['this_link'] = $this->url->link('information/actions','actions_id=' . $actions_id);

		if ($action_info['meta_description']) {
			$this->document->setDescription($action_info['meta_description']);
		}

		if ($action_info['meta_keywords']) {
			$this->document->setKeywords($action_info['meta_keywords']);
		}

		if ($action_info['h1']) {
			$this->data['h1'] = $action_info['h1'];
		} else {
			$this->data['h1'] = $action_info['caption'];
		}

		if ($action_info['image']) {
			$this->data['image'] = $image = $this->model_tool_image->resize($action_info['image'], 970, 400);
		} else {
			$this->data['image'] = false;
		}

		$this->data['end_date'] 		= date('Y-m-d H:i:s', $action_info['date_end']);
		$this->data['end_date_format'] 	= date('Y-m-d', $action_info['date_end']);
		$this->data['caption']			= $action_info['caption'];
		$this->data['actions_id']		= $action_info['actions_id'];

		if ($actions_setting['show_actions_date'] == 1) {
			$date_start = date( 'j', $action_info['date_start'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $action_info['date_start'] ));
			$date_end = date( 'j', $action_info['date_end'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $action_info['date_end'] ));

			$this->data['date']		= sprintf($this->language->get('date_actions_format'), $date_start, $date_end);
		} else {
			$this->data['date'] = NULL;
		} 

		$this->data['fancybox']		= $action_info['fancybox'];
		$this->data['content'] 		= html_entity_decode($action_info['content'], ENT_QUOTES, 'UTF-8');

		$this->data['button_cart'] 		= $this->language->get('button_cart');
		$this->data['button_continue'] 	= $this->language->get('button_all_actions');
		$this->data['continue'] 		= $this->url->link('information/actions');

		$url = '';

		if( ! empty( $this->request->get['mfp'] ) ) {
			$url .= '&mfp=' . $this->request->get['mfp'];
		}				

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
				
		$pagination 		= new Pagination($this->registry);
		$pagination->total 	= $product_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $limit;
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('information/actions','actions_id=' . $actions_id . $url . '&page={page}');

		$this->data['text_show_more'] 	= $this->language->get('text_show_more');
		$this->data['pagination'] 		= $pagination->render();
		$this->data['pagination_text'] 	= $pagination->render_text();

		$this->data['sort']  = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
	}

	public function getList($archive = false) {
		$actions_setting = $this->config->get('actions_setting');

		$this->data['text_read_more'] = $this->language->get('text_read_more');
		$this->data['text_action_sale_ended'] = $this->language->get('text_action_sale_ended');
		
		if( !empty($actions_setting['seo'][$this->config->get('config_language_id')]['title']) ) {
			$this->document->setTitle($actions_setting['seo'][$this->config->get('config_language_id')]['title']);
		} else {
			$this->document->setTitle($this->language->get('text_actions_title'));
		}

		$this->document->addLink($this->url->link('information/actions'), 'canonical');

		if( !empty($actions_setting['seo'][$this->config->get('config_language_id')]['description']) ) {
			$this->document->setDescription($actions_setting['seo'][$this->config->get('config_language_id')]['description']);
		}

		if( !empty($actions_setting['seo'][$this->config->get('config_language_id')]['keywords']) ) {
			$this->document->setKeywords($actions_setting['seo'][$this->config->get('config_language_id')]['keywords']);
		}

		if( !empty($actions_setting['seo'][$this->config->get('config_language_id')]['h1']) ) {
			$this->data['h1'] = $actions_setting['seo'][$this->config->get('config_language_id')]['h1'];
		} else {
			$this->data['h1'] = $this->language->get('text_actions');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

		$url = 'limit=' . $limit;

		if ($archive) {
			$actions_total = $this->model_catalog_actions->getActionsArchiveTotal();
			$results = $this->model_catalog_actions->getActionsArchive(($page - 1) * $limit, $limit);
		} else {
			$actions_total = $this->model_catalog_actions->getActionsTotal();
			$results = $this->model_catalog_actions->getActionsAll( ($page - 1) * $limit, $limit);
		}

		$this->data['actions_all'] = [];

		foreach ($results as $result) {

			$date_start = date( 'j', $result['date_start'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $result['date_start'] ));
			$date_end = date( 'j', $result['date_end'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $result['date_end'] ));

			if ($result['image'] AND $actions_setting['show_image']) {
				$image = $this->model_tool_image->resize($result['image'], $actions_setting['image_width'], $actions_setting['image_height']);
			} else {
				$image = FALSE;
			}

			if ($actions_setting['show_date']) {
				$date = sprintf($this->language->get('date_actions_format'), $date_start, $date_end);
			} else {
				$date = FALSE;
			}

			$discount = false;
			if ($result['ao_group']){
				$query = $this->db->query("SELECT `percent`, `price` FROM `product_additional_offer` WHERE `ao_group` = '".$this->db->escape($result['ao_group'])."' LIMIT 1");
				if ($query->num_rows) {
					$percent = $query->row['percent'];
					if ($percent && $percent < 100) {
						$discount = "-".$percent."%";
					} elseif ($percent == 100) {
						$discount = "free";
					} else {
						$discount = $this->currency->format($this->tax->calculate($query->row['price'], 0, $this->config->get('config_tax')));
					}
				}
			}

			$this->data['actions_all'][] = array(
				'caption'		=> $result['caption'],
				'date_start'	=> $date_start,
				'date_end'		=> $date_end,
				'archive'		=> $result['archive'],
				'date'			=> $date,
				'thumb'			=> $image,
				'description'	=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href'			=> $this->url->link('information/actions', 'actions_id=' . $result['actions_id']),
				'discount'      => $discount
			);					
		}


		$this->data['limits'] = [];

		$limits = array_unique(array($this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit') * 2, $this->config->get('config_catalog_limit') * 3));
		sort($limits);

		foreach($limits as $value){
			$this->data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('information/actions', 'path=' . 'limit=' . $value)
			);
		}

		$pagination = new Pagination();
		$pagination->total = $actions_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		if ($archive){
			$pagination->url = $this->url->link('information/actions', 'archive=1&page={page}&limit=' .$limit);
		} else {
			$pagination->url = $this->url->link('information/actions', 'page={page}&limit=' .$limit);
		}

		$this->data['pagination'] = $pagination->render();

		$this->data['limit'] = $limit;
	}

	public function info() {
		$this->load->model('catalog/actions');

		if (isset($this->request->get['actions_id'])) {
			$actions_id = $this->request->get['actions_id'];
		} else {
			$actions_id = 0;
		}      

		$action_info = $this->model_catalog_actions->getActions($actions_id);

		if ($action_info) {
			$output  = '<html dir="ltr" lang="en">' . "\n";
			$output .= '<head>' . "\n";
			$output .= '  <title>' . $action_info['title'] . '</title>' . "\n";
			$output .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$output .= '</head>' . "\n";
			$output .= '<body>' . "\n";
			$output .= '  <br /><br /><h1>' . $action_info['title'] . '</h1>' . "\n";
			$output .= html_entity_decode($action_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
			$output .= '  </body>' . "\n";
			$output .= '</html>' . "\n";

			$this->response->setOutput($output);
		}
	}
}									