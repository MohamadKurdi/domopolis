<?php
class ControllerFeedGoogleSitemap extends Controller {
	private $limit 					= 2000;
	private $exclude_language_id 	= 6;

	private function getStoreID(){

		$store_id = $this->config->get('config_store_id');

		if ($this->config->get('config_second_language') && $secondary_language_id = $this->model_localisation_language->getLanguageByCode($this->config->get('config_second_language'))){

			if ($secondary_language_id == $this->config->get('config_language_id')){
				$store_id .= '_' . $secondary_language_id;
			}

		}

		return $store_id;

	}

	public function index(){
		$this->load->model('setting/store');

		error_reporting(E_ALL);

		if ($this->config->get('google_sitemap_status')) {			

			$store_id = $this->getStoreID();
			$this->response->addHeader('Content-Type: application/xml');

			$file = DIR_SITEMAPS_CACHE . 'sitemap_'.$store_id.'.xml';

			if (file_exists($file)){
				$content = file_get_contents($file);
			} else {
				$content  = '<?xml version="1.0" encoding="UTF-8"?>';
				$content .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

				xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

				$content .= '<url>' . PHP_EOL;
				$content .= '<loc><![CDATA[' . $this->url->link('common/home') . ']]></loc>' . PHP_EOL;
				$content .= '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
				$content .= '<changefreq>daily</changefreq>' . PHP_EOL;
				$content .= '<priority>1</priority>' . PHP_EOL;
				$content .= '</url>' . PHP_EOL;

				$content .= '</urlset>';
			}

			$this->response->setNonHTTPSOutput($content);


		}
	}

	private function loadSettings($store_id){

		$query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");				
		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$this->config->set($setting['key'], $setting['value']);
			} else {
				$this->config->set($setting['key'], unserialize($setting['value']));
			}
		}

		$query = $this->db->non_cached_query("SELECT * FROM language"); 			
		foreach ($query->rows as $result) {
			$languages[$result['code']] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
			);
		}

		$this->languages = $languages;

		$this->config->set('config_store_id', $store_id);
		$this->config->set('config_language_id', $languages[$this->config->get('config_language')]['language_id']);
		$this->currency->set($this->config->get('config_regional_currency'));		

		return $this;

	}

	public function makeAllExceptExcludedLanguageCron(){
		$query = $this->db->non_cached_query("SELECT * FROM " . DB_PREFIX . "store ORDER BY store_id ASC");

		$stores = [0];
		foreach ($query->rows as $row){
			$stores[] = $row['store_id'];
		}

		foreach ($stores as $store_id){
			if ($store_id == 18 || $this->config->get('config_language_id') == $this->exclude_language_id){
				continue;
			}


			$this->loadSettings($store_id);			
			echoLine('[CRON2] Магазин ' . $store_id . ', язык ' . $this->config->get('config_language'));
			$this->makeFeedsCron();
		}

	}

	public function makeFeedsCron() {

		error_reporting(E_ALL);

		if ($this->config->get('google_sitemap_status')) {
				//loading models
			$this->load->model('setting/store');	
			$this->load->model('catalog/supersitemap');	
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/countrybrand');
			$this->load->model('catalog/collection');			
			$this->load->model('catalog/information');
			$this->load->model('catalog/news');	
			$this->load->model('catalog/ncategory');
			$this->load->model('catalog/actions');	
			$this->load->model('localisation/language');	

			$host = $this->config->get('config_ssl');
			$store_id = $this->getStoreID();

				//sitemaps array
			$sitemaps = array();			

				//-----------------------------------------------------------------------------------
				//general sitemap start
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;	

			$output .= '<url>' . PHP_EOL;
			$output .= '<loc><![CDATA[' . $host . ']]></loc>' . PHP_EOL;
			$output .= '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
			$output .= '<changefreq>daily</changefreq>' . PHP_EOL;
			$output .= '<priority>1</priority>' . PHP_EOL;
			$output .= '</url>' . PHP_EOL;

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'general_sitemap_' . $store_id .'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'general_sitemap_' . $store_id . '.xml';			
			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	

				//-----------------------------------------------------------------------------------
				//products sitemap start

			$total_products = $this->model_catalog_supersitemap->getTotalProducts();
			$index = (int)($total_products / $this->limit);	

			echo '>> Начали товары ' . $store_id . PHP_EOL;

			for ($i=0; $i<=$index; $i++){
				$start = $i * $this->limit;

				echo $start . '...';

				$output  = '<?xml version="1.0" encoding="UTF-8"?>';
				$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

				xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

				$products = $this->model_catalog_supersitemap->getProducts($start, $this->limit);

				foreach ($products as $product) {
					$output .= '<url>' . PHP_EOL;
					$output .= '<loc><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></loc>' . PHP_EOL;
					$output .= '<image:image>' . PHP_EOL;
					$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . ']]></image:loc>' . PHP_EOL;
					$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>' . PHP_EOL;
					$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>' . PHP_EOL;
					$output .= '</image:image>' . PHP_EOL;
					$output .= '<lastmod>' . substr(max($product['date_added'], $product['date_modified']), 0, 10) . '</lastmod>' . PHP_EOL;
					$output .= '<changefreq>weekly</changefreq>' . PHP_EOL;
					$output .= '<priority>0.9</priority>' . PHP_EOL;
					$output .= '</url>' . PHP_EOL;
				}


				$output .= '</urlset>';

				$file = DIR_SITE . DIR_SITEMAPS . 'products_sitemap_'.$i.'_'.$store_id.'.xml';
				$sitemaps[] = $host . DIR_SITEMAPS . 'products_sitemap_'.$i.'_'.$store_id.'.xml';			
				$handle = fopen($file, 'w+');
				flock($handle, LOCK_EX);
				fwrite($handle, $output);
				flock($handle, LOCK_UN);
				fclose($handle);			
			}


				//end products	
				//-----------------------------------------------------------------------------------		
				//start categories
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$output .= $this->model_catalog_supersitemap->getCategories(0);

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'categories_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'categories_sitemap_'.$store_id.'.xml';

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);
				//end categories	
			//-----------------------------------------------------------------------------------		
				//start manufacturers
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . ']]></loc>';
				$output .= '<image:image>';
				$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($manufacturer['image'], 200, 200) . ']]></image:loc>';
				$output .= '<image:caption><![CDATA[Логотип ' . $manufacturer['name'] . ']]></image:caption>';
				$output .= '<image:title><![CDATA[Лого ' . $manufacturer['name'] . ']]></image:title>';
				$output .= '</image:image>';
				$output .= '<changefreq>daily</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';

					//KEYWORDER	
				$this->load->model('module/keyworder');
				$manufacturer_categories = $this->model_module_keyworder->getCategoriesByManufacturer($manufacturer['manufacturer_id']);

				foreach ($manufacturer_categories as $mc) {
					$output .= '<url>';
					$output .= '<loc><![CDATA[' . $this->url->link('product/category', 'path=' . $mc['category_id'] . '&manufacturer_id=' . $manufacturer['manufacturer_id']) . ']]></loc>';
					$output .= '<changefreq>daily</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '</url>';
				}				
			}

			$countrybrands = $this->model_catalog_countrybrand->getCountrybrands();

			foreach ($countrybrands as $countrybrand) {

				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('product/countrybrand', 'countrybrand_id=' . $countrybrand['countrybrand_id']) . ']]></loc>';
				$output .= '<changefreq>daily</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
				
				
			}

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'manufacturers_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'manufacturers_sitemap_'.$store_id.'.xml';			
			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);		
				//end manufacturers
				//-----------------------------------------------------------------------------------			
				//start collections
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$collections = $this->model_catalog_collection->getCollections();

			foreach ($collections as $collection) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('product/collection', 'collection_id=' . $collection['collection_id']) . ']]></loc>';
				$output .= '<image:image>';
				$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($collection['image'], 200, 200) . ']]></image:loc>';
				$output .= '<image:caption><![CDATA[' . $collection['name'] . ']]></image:caption>';
				$output .= '<image:title><![CDATA[' . $collection['name'] . ']]></image:title>';
				$output .= '</image:image>';
				$output .= '<changefreq>daily</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'collections_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'collections_sitemap_'.$store_id.'.xml';

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	
				//end collections
				//-----------------------------------------------------------------------------------			
				//start informations			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';	

				//shop rating page
			$output .= '<url>';
			$output .= '<loc><![CDATA[' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('information/shop_rating'))) . ']]></loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.5</priority>';
			$output .= '</url>';

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$actions =  $this->model_catalog_actions->getAllActions();

			$output .= '<url>';
			$output .= '<loc><![CDATA[' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('information/actions'))) . ']]></loc>';
			$output .= '<changefreq>daily</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';

			foreach ($actions as $action) {
				$output .= '<url>';				
				$output .= '<loc><![CDATA[' . $this->url->link('information/actions', 'actions_id=' . $action['actions_id']) . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'info_actions_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'info_actions_sitemap_'.$store_id.'.xml';

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	
				//end informations
				//-----------------------------------------------------------------------------------			
				//start news	
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';	

			$articles = $this->model_catalog_news->getNews();

			foreach ($articles as $article) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('news/article', 'news_id=' . $article['news_id']) . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';   
			}

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'articles_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'articles_sitemap_'.$store_id.'.xml';

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	
					//end news
					//-----------------------------------------------------------------------------------			
					//start news categories					
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"

			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';


			$output .= $this->model_catalog_supersitemap->getNcategories(0);

			$output .= '</urlset>';

			$file = DIR_SITE . DIR_SITEMAPS . 'articles_categories_sitemap_'.$store_id.'.xml';
			$sitemaps[] = $host . DIR_SITEMAPS . 'articles_categories_sitemap_'.$store_id.'.xml';

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	
					//end news categories
			
				//-----------------------------------------------------------------------------------			

			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= ' <sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"

			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"

			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';	

			foreach ($sitemaps as $sitemap){
				$output .= '<sitemap>';			
				$output .= '<loc><![CDATA['.$sitemap.']]></loc>';			
				$output .= '</sitemap>';				
			}

			$output .= '  </sitemapindex>';		

			$file = DIR_SITE . DIR_SITEMAPS . 'sitemap_'.$store_id.'.xml';
			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);	

			echo  PHP_EOL . PHP_EOL;
		}	

	}
}			