<?
class ControllerModulefeaturedreview extends Controller {
	protected function index($setting) {
		
		$manufacturer_id = 0;
		if (!empty($this->request->get['manufacturer_id'])){
			$manufacturer_id = (int)$this->request->get['manufacturer_id'];
		}

		$category_id = 0;
		if (!empty($this->request->get['path'])){
			$parts 			= explode('_', (string)$this->request->get['path']);
			$category_id 	= (int)array_pop($parts);				
		}

		$md5_template = 'home';
		if (isset($this->request->get['route'])){
			$md5_template = md5($this->request->get['route']);
		}

		$page_type = 'general';
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/category' && $category_id){
			$page_type = 'category';

			if ($manufacturer_id){
				$page_type = 'category-manufacturer';
			}
		}

		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/manufacturer' && $manufacturer_id){
			$page_type = 'manufacturer';
		}

		$options = [$manufacturer_id, $category_id, $md5_template, $page_type];

		$out = $this->cache->get($this->createCacheQueryString(get_class($this), $setting, $options));

		if ($out) {		

			$this->setCachedOutput($out);
			
		} else {


			$this->language->load('module/featuredreview');
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_all_reviews'] = $this->language->get('text_all_reviews');
			$this->data['no_reviews'] = $this->language->get('no_reviews');

			$this->data['button_cart'] = $this->language->get('button_cart');

			$this->data['customtitle'] = $this->config->get('popular_customtitle' . $this->config->get('config_language_id'));
			if (!$this->data['customtitle']) { $this->data['customtitle'] = $this->data['heading_title']; } 

			$this->load->model('catalog/product'); 

			$this->load->model('tool/image');

			$this->load->model('module/reviews');

			$this->language->load('product/product');

			$this->load->model('catalog/review');
			$this->load->model('module/reviews');

			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');

			if ($category_id) {
				$category = $this->model_catalog_category->getCategory($category_id);
			} else {
				$category = false;
			}

			if ($manufacturer_id) {
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			} else {
				$manufacturer = false;
			}

			if ($category_id && $manufacturer_id) {
				$microdata_name = $category['name'];
			} elseif ($category_id && !$manufacturer_id) {
				$microdata_name = $category['name'];
			} elseif ($manufacturer_id) {
				$microdata_name = $manufacturer['name'];
			} else {
				$microdata_name = $this->config->get('config_name');
			}

			$this->data['products'] = array();				

			if (empty($setting['limit'])) {
				$setting['limit'] = 5;
			}

			$products = array();

			$sql = "SELECT 
			r.product_id 
			FROM review r 
			JOIN product p ON r.product_id = p.product_id 	
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)
			WHERE r.status = 1 AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";

			if ($manufacturer_id){
				$sql .= " AND p.manufacturer_id = '" . (int)$this->request->get['manufacturer_id'] . "'";
			}

			if ($category_id){
				$sql .= " AND (r.product_id IN (SELECT product_id FROM product_to_category WHERE category_id = '" . (int)$category_id . "') 
				OR r.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE parent_id = '" . (int)$category_id . "')))";
			}

			$sql .= " ORDER BY r.date_added DESC LIMIT " . (int)$setting['limit'] . "";

			$query = $this->db->query($sql);

			foreach ($query->rows as $row){														
				$products[] = $row['product_id'];
			}

			if (empty($setting['limitrev'])) {
				$setting['limitrev'] = 3;
			}

			$products = array_slice($products, 0, (int)$setting['limit']);

			$microdata_reviews = array();
			$microdata_rating_score = array();

			$results = array();
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {					
					$results[$product_id] = $product_info;
				}
			}

			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);

			foreach ($this->data['products'] as &$product){

				$reviews = $this->model_catalog_review->getReviewsByProductId($product['product_id']);
				$reviews = array_slice($reviews, 0, (int)$setting['limitrev']);

				foreach ($reviews as &$review){
					$review['date_added'] = date('d.m.Y', strtotime($review['date_added']));
					$microdata_reviews[] = $review;
					$microdata_rating_score[] = $review['rating'];
				}

				$product['reviews2'] = $reviews;
			}				
				
			$this->data['microdata'] = array();

			$microdata_rating_value = null;

			if (count($microdata_rating_score) > 0) {
				$microdata_rating_value = array_sum($microdata_rating_score) / count($microdata_rating_score);
			}

			$this->data['microdata'] = array(
				'url'			=> 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'],
				'name'			=> $microdata_name,
				'rating_value'	=> $microdata_rating_value,
				'rating_count' 	=> count($microdata_rating_score),
				'reviews'		=> $microdata_reviews
			);


			$template = 'featuredreview.tpl';

			if (isset($this->request->get['route']) && ($this->request->get['route'] == 'product/category' || strpos($this->request->get['route'], 'product/manufacturer') !== false)){
				$template = 'featuredreview_catalog.tpl';
			}

			$this->template = 'module/'. $template;

			$out = $this->render();
			$this->cache->set($this->createCacheQueryString(get_class($this), $setting, $options), $out);
		}		
	}
}			