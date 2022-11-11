<?php
class ControllerFeedGoogleCSV extends Controller {
	private $limit = 5000;

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}

	private function getCategories($product_id){
		$categories = $this->model_catalog_product->getCategories($product_id);

		$string = '';

		foreach ($categories as $category) {
			$path = $this->getPath($category['category_id']);
			if ($path) {
				$string = '';

				foreach (explode('_', $path) as $path_id) {
					$category_info = $this->model_catalog_category->getCategory($path_id);
						if ($category_info) {
							if (!$string) {
								$string = $category_info['name'];
							} else {
								$string .= ' &gt; ' . $category_info['name'];
							}
						}
					}
			}
		}
		return $string;
	}


	public function index() {
		$csvlog = new Log('google_feed.txt');

		$store_id = $this->config->get('config_store_id');

		header( 'Content-Type: text/csv charset=utf-8' );

		$file = fopen('php://output', 'w');

		$full_out = ($store_id == 0 && false);

		if ($full_out) {
			$header = array(
				'id',
				'title',
				'description',
				'product_type',
				'link',
				'condition',
				'image_link',
				'price',
				'sale_price',
				'brand',
			//	'gtin',
				'availability'
			);
		} else {
			$header = array(
				'ID',
				'Item Title',
				'Item Description',
				'Item Category',
				'Final URL',
				'Price',
				'Sale Price',
				'Image URL',
				'Item Subtitle',
			);
		}

		fputcsv($file, $header);

		$this->load->model('catalog/supersitemap');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');

		$total_products = $this->model_catalog_supersitemap->getTotalProducts();
		$index = (int)($total_products / $this->limit);

		for ($i=0; $i<=$index; $i++){
				$start = $i * $this->limit;

				$products = $this->model_catalog_supersitemap->getProductsFacebook($start, $this->limit);

				foreach ($products as $product) {

					if ($product['name'] && (mb_strlen($product['image']) > 5) && $product['price'] > 0) {
						
						$r_cur = array('UAH', 'RUB');
						$t_cur = array(' UAH', ' RUB');

						if ($product['special']){
							$special = str_replace($r_cur, $t_cur, str_replace(' ', '', $this->currency->format($product['special'], '', '', true, false, true)));
						} else {
							$special = '';
						}

						if (mb_strlen($product['description'], 'UTF-8') > 5){
							$description = mb_substr(preg_replace("/[\\n\\r]+/", " ", strip_tags(htmlspecialchars_decode($product['description']))), 0, 255, 'UTF-8').'..';
						} else {
							$description = 'Элитная посуда от '.$product['manufacturer'];
						}												

					if ($full_out) {
						$product = array(
							$product['product_id'],
							preg_replace("/[\\n\\r]+/", "", $product['name']),
							$description,
							$this->getCategories($product['product_id']),
							$this->url->link('product/product', 'product_id='.$product['product_id']),
							'new',
							$this->model_tool_image->resize($product['image'], 300, 300),
							str_replace(' ', '', $this->currency->format($product['price'], '', '', true, false, true)),
							$special,
							$product['manufacturer'],
						//	$product['model'],
							($product['stock_status_id']==$this->config->get('config_not_in_stock_status_id') || $product['stock_status_id']==$this->config->get('config_partly_in_stock_status_id'))?'out of stock':'in stock',
						);
					} else {
						$product = array(
							$product['product_id'],
							preg_replace("/[\\n\\r]+/", "", $product['name']),
							$description,
							$this->getCategories($product['product_id']),
							$this->url->link('product/product', 'product_id='.$product['product_id']),
							str_replace($r_cur, $t_cur, str_replace(' ', '', $this->currency->format($product['price'], '', '', true, false, true))),
							$special,
							$this->model_tool_image->resize($product['image'], 300, 300),
							$product['manufacturer'],
						);

					}


						fputcsv($file, $product);

					}
				}


		}

		fclose($file);
		$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
		$csvlog->write('Store: '.$store_id . ': Generated in ' . $time . ' seconds');		
	}
}
?>
