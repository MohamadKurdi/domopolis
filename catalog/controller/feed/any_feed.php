<?php
############################################################################################
#  AnyFeed Module for Opencart 1.5.x from HostJars http://opencart.hostjars.com    	       #
############################################################################################
class ControllerFeedAnyFeed extends Controller {
	private $names = array();
	private $input_fields = array(
		'Product ID' => 'product_id',
		'URL' => 'url',
		'Name' => 'name',
		'Image' => 'image',
		//'additional_image' => 'additional_image',
		'Price' => 'price',
		'Description' => 'description',
		//'Categories' => 'categories',
        //'Filters' => 'filters',
		'Manufacturer' => 'manufacturer',
		'Model' => 'model',
		'SKU' => 'sku',
		'UPC' => 'upc',
		'EAN' => 'ean',
		'JAN' => 'jan',
		'ISBN' => 'isbn',
		'MPN' => 'mpn',
		'Quantity' => 'quantity',
		'Date Added' => 'date_added',
		'Viewes' => 'viewed',
		'Special Price' => 'special_price',
		'any_feed_field_stockstatus' => 'stock_status_id',
		'Length' => 'length',
		'Width' => 'width',
		'Height' => 'height',
		'Location' => 'location',
		'Points' => 'points',
		'Date Available' => 'date_available',
		'Weight' => 'weight',
		'Shipping' => 'shipping',
		'Status' => 'status',
		'Date Modified' => 'date_modified',
		'Product Tags' => 'tag',
		'Meta Tag Keywords' => 'meta_keyword',
		'Meta Tag Description' => 'meta_description',
		'SEO Keyword' => 'keyword',
		'Discount' => 'discount',
		'Special' => 'special',
		'Reward' => 'reward',
		'Rating' => 'rating',
		//'Attributes' => 'attributes',
		//'Options' => 'options',
	);

	public function index() {
			
		$this->load->model('feed/any_feed');
		if(isset($this->request->get['name'])){
			$profile_name = $this->request->get['name'];
		} else {
			echo "Profile name not set, please correct your url and try again";
			exit();
		}
		$profile = $this->model_feed_any_feed->getProfile($profile_name);
		$settings = json_decode($profile['settings'], true);
		$fields = json_decode($profile['fields'], true);

		if(isset($settings['enable']) &&  $settings['enable'] == 1) {
			$this->load->model('feed/any_feed');
			$stock_statuses = $this->model_feed_any_feed->getStockStatus();
			
			if ($settings['type'] == 'XML') {
				$format = 'xml';
			}
			else {
				$format = 'csv';
							
				if (isset($settings['delimiter'])) {
					$delim = $settings['delimiter'];
				}
				if ($delim == '\t') {
					$delim = "\t";
				}
			}
			
			// HEADINGS FOR CSV or XML
			if ($format == 'csv') {
				$output = '';
			}
			else {
				if (!empty($settings['root_tag'])) {
					$output = "<?xml version=\"1.0\"?>\n<".$settings['root_tag'].">\n";
				}
				else {
					$output = "<?xml version=\"1.0\"?>\n<any_feed_product_list>\n";
				}
			}

			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			
			//Allow start and limit for DB query - a fix for large feeds.
			$data = array();
			if (isset($this->request->get['start'])) {
				$data['start'] = $this->request->get['start'];
				$data['limit'] = 50000;
			}
			if (isset($this->request->get['numResults'])) {
				$data['limit'] = $this->request->get['numResults'];
			}
			$c = 0;
			$products = $this->model_catalog_product->getProducts($data);
			$max_cat = $this->model_feed_any_feed->getMaxCategories();
			$max_opt_names = $this->model_feed_any_feed->getProductOptionNames();
			$max_opt = count($max_opt_names);

			foreach ($products as $product) {
				
				// clean description html
				if(isset($fields['Description']) && isset($fields['Description']['settings']['Strip HTML']['value'])) {
					$product['description'] = html_entity_decode($product['description']);
					$product['description'] = strip_tags($product['description']);
				}
				
				//get the product URL
				if(isset($fields['URL'])) {
					$product['URL'] = HTTP_SERVER . 'index.php?route=product/product&product_id=' . $product['product_id'];
				}
				
				//get additional images
				if(isset($fields['Additional Images'])) {
					$image_prefix = $fields['Additional Images']['settings']['Field Name']['value'];
					$image_list = $this->model_catalog_product->getProductImages($product['product_id']);
					//get max number of additional images for any product in the catalog
					$max_add = $this->model_feed_any_feed->getMaxImages();
					for($k = 0; $k < $max_add; $k++) {
						$index = $k+1;
						$fields[$image_prefix.$index] = array('settings'=> array('Field Name' => array('value' => $image_prefix.$index)));
						$this->input_fields[$image_prefix.$index] = $image_prefix.$index;
						$product[$image_prefix.$index] = (isset($image_list[$k]['image'])) ? HTTP_SERVER . "image/" . $image_list[$k]['image'] : ' ';
					}
				}		


                //if filters are selected, export each product filter
                if(isset($fields['Filters'])) {
                    $product_filters = $this->model_feed_any_feed->getProductFilters($product['product_id']);
                    $filter_prefix = $fields['Filters']['settings']['Field Name']['value'];
                    $max_filters = $this->model_feed_any_feed->getMaxFilters();
                    //get max number of filters for any product in the catalog
                    for($k = 0; $k < $max_filters; $k++) {
                        $index = $k+1;
                        $product[$filter_prefix.$index] = (isset($product_filters[$k])) ? $product_filters[$k] : ' ';
                        $fields[$filter_prefix.$index] = array('settings'=> array('Field Name' => array('value' => $filter_prefix.$index)));
                        $this->input_fields[$filter_prefix.$index] = $filter_prefix.$index;
                    }

                }
				
				foreach ($stock_statuses as $ss) {
					if (isset($product['stock_status_id']) && isset($product['stock_status_id']) && $product['stock_status_id'] == $ss['stock_status_id']) {
						$product['stock_status_id'] = $ss['name'];
					}
				}
				if (isset($product['special']) && !empty($product['special']))
					$product['special_price'] = $this->currency->format($this->tax->calculate($product['special'],$product['tax_class_id'], $this->config->get('config_tax')));
				$product['price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
		
				$product['keyword'] = $this->model_feed_any_feed->getSeoKeyword($product['product_id']);
                $product['url'] = html_entity_decode($this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL'));
                
				if ($product['image']) {
					$product['image'] = HTTP_SERVER . "image/" . str_replace(' ', '%20', $product['image']);
				} else {
					$product['image'] = '';
				}
				$prod = array();

				//skip the special fields that have their own field lists
				$special_fields = array('Categories','Options','Attributes');

				//compare all the product fields, to the fields in the AnyFeed settings. Add appropriate ones
				$headings = array();
				
				foreach($fields as $key => $value) {
					if(!in_array($key, $special_fields)) {
						if(isset($this->input_fields[$key])) {
							if (isset($product[$this->input_fields[$key]])) {
								$headings[] = $value['settings']['Field Name']['value'];
								$prod[$value['settings']['Field Name']['value']] = $product[$this->input_fields[$key]];
							} else {
								$headings[] = $value['settings']['Field Name']['value'];
								$prod[$value['settings']['Field Name']['value']] = '';
							}
						}
					} else {
						//if categories are to export get all categories
						if($key == 'Categories' && isset($fields['Categories'])) {
							// get categories
							$categories = $this->model_catalog_product->getCategories($product['product_id']);
							$thiscat = '';
							$category_list = array();
							foreach ($categories as $category) {
								$catpath = $this->getCatInfo($category['category_id']);
								if ($catpath) {
									$thiscat = '';
									foreach (explode('_', $catpath) as $pathid) {
										$cat = $this->model_catalog_category->getCategory($pathid);
										if ($cat) {
											if (!$thiscat) {
												$thiscat = $cat['name'];
											} else {
												$thiscat .= ' > ' . $cat['name'];
											}
										}
									}
								}
								$category_list[] = $thiscat;
							}

							//remove all categories that are sub sets of the main category path
							for($i=0; $i <= count($category_list);$i++){
								if(isset($category_list[$i+1]) && $category_list[$i+1] != '' && isset($category_list[$i]) && $category_list[$i] != '') {
									if(strstr($category_list[$i+1], $category_list[$i])) {
										unset($category_list[$i]);
									}
								}
							}

							//add category paths to required arrays
							$category_prefix = $fields['Categories']['settings']['Field Name']['value'];
							$category_list = array_values($category_list);
							//get max number of categories for any product in the catalog
							for($k = 0; $k < $max_cat;$k++) {
								$index = $k+1;
								$headings[] =  $category_prefix.$index;
								$prod[$category_prefix.$index] = (isset($category_list[$k])) ? $category_list[$k] : ' ';
							}
						}
						//if attributes are selected, export each product attribute
						if($key == 'Attributes' && isset($fields['Attributes'])) {
							$attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);
							$total_attributes = $this->model_feed_any_feed->getAttributes();
							$attribute_prefix = $fields['Attributes']['settings']['Field Name']['value'];
							$attribute_list = array();
							foreach($attribute_groups as $attribute) {
								foreach($attribute['attribute'] as $values) {
									foreach($total_attributes as $total_attribute) {
										//tip compatibility: add blank values to attributes that exist but not assigned to product
										if ($values['name'] == $total_attribute['name']){
											$attribute_list[$values['name']] = $values['text'];
										} else if (!isset($attribute_list[$total_attribute['name']])) {
											$attribute_list[$total_attribute['name']] = ' ';
										}
									}
								}
							}
							foreach($attribute_list as $key=>$value) {
								$headings[] = $key;
								$prod[$key] = $value;
							}
						}

						//if options are selected, export each product option
						if($key == 'Options' && isset($fields['Options'])) {
							$options = $this->model_catalog_product->getProductOptions($product['product_id']);
							$options_prefix = $fields['Options']['settings']['Field Name']['value'];
							$option_list = array();
							foreach($options as $option) {
								$option_types = array('checkbox', 'select', 'radio');
								if (in_array($option['type'], $option_types)) {
									foreach($option['option_value'] as $values) {
										//set prefixes to blank for +
										($values['price_prefix'] != '-') ? $values['price_prefix'] = '' : $values['price_prefix'] = '-';
										($values['weight_prefix'] != '-') ? $values['weight_prefix'] = '' : $values['weight_prefix'] = '-';
										//fixed
										if(isset($option_list[$option['name']])) {
											$option_list[$option['name']] .= '|' . $values['name'].":".$values['quantity'].":".$values['price_prefix'].number_format($values['price'], 2, '.', '').":".$values['weight_prefix'].number_format($values['weight'], 2, '.', '').":".$option['required'].":".$option['type'];
										} else {
											$option_list[$option['name']] = $values['name'].":".$values['quantity'].":".$values['price_prefix'].number_format($values['price'], 2, '.', '').":".$values['weight_prefix'].number_format($values['weight'], 2, '.', '').":".$option['required'].":".$option['type'];
										}
									}
								}
							}
							foreach($max_opt_names as $opt_name) {
								$headings[] = $opt_name;
								$option_exists_for_prod = false;
								foreach($option_list as $key=>$value) {
									if ($key == $opt_name) {
										$prod[$opt_name] = $value;
										$option_exists_for_prod = true;
										break;
									}
								}
								if (!$option_exists_for_prod) {
									$prod[$opt_name] = '';
								}
							}
						}

					}
				}
				
				//generate all appropriate csv headers
				foreach($headings as $key) {
					$this->names[$key] = $key;
				}

				if ($format == 'csv') {
					$output .= $this->outputCSV($prod, $delim);
				}
				else {
					$output .= $this->outputXML($prod, $settings);
				}
				$c++;
			}
			//add all related headers to the front of the csv file
			if($format == 'csv') {
				$output = join($delim, $this->names) . "\n" . $output;
			}
			
			if ($format == 'xml') {
				if (!empty($settings['root_tag'])) {
					$output .= "</".$settings['root_tag'].">";
				}
				else {
					$output .= "</any_feed_product_list>";
				}
				$this->response->addHeader('Content-Type: application/xml');
			} 
			else {
				$this->response->addheader('Pragma: public');
				$this->response->addheader('Expires: 0');
				$this->response->addheader('Content-Type: application/octet-stream');
				$this->response->addheader('Content-Description: File Transfer');
				if (!empty($settings['filename'])) {
					$this->response->addheader('Content-Disposition: attachment; filename='.$settings['filename'].'.csv');
				} else {
					$this->response->addheader('Content-Disposition: attachment; filename=opencart_products.csv');
				}
				$this->response->addheader('Content-Transfer-Encoding: binary');
			}
			$this->response->setOutput($output, 0);
		} else {
			$this->redirect($this->url->link('common/home'));
		}
	}	
	
	protected function outputXML($product, $settings) {
		$result = "<product>\n";
		foreach ($product as $key => $value) {
			if(!isset($settings['cdata'])) {
				$value = str_replace('&', '&amp;', $value);
				$value = str_replace('>', '&gt;', $value);
				$value = str_replace('<', '&lt;', $value);
			} else {
				$value = "<![CDATA[" . $value . "]]>";
			}
			$valid_key = str_replace(' ', '_', $key);
			$valid_key = preg_replace('/[^A-Z0-9a-z_]/i', '', $valid_key);
			$product[$key] = "<" . $valid_key . ">";
			$product[$key] .= $value; 
			$product[$key] .= "</" . $valid_key . ">";
		}
		$result .= join("\n", $product);
		$result .= "\n</product>\n";
		return $result;
	}			
	
	protected function outputCSV($product, $delim) {
		foreach ($product as $key => $value) {
			$value = str_replace('"', '""', $value);
			$product[$key] = '"' . $value . '"';
		}
		$result = join($delim, $product);
		$result .= "\n";
		return $result;
	}
	
	protected function getCatInfo($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);
	
		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	
		
			$path = $this->getCatInfo($category_info['parent_id'], $new_path);
					
			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}
	
	public function generateFeed($profile_name='custom') {
		$this->load->model('feed/any_feed');
		if(isset($this->request->get['profile'])){
			$profile_name = $this->request->get['profile'];
		}
		$profile = json_encode($this->model_feed_any_feed->getProfile($profile_name));
		echo $profile;
		return;
	}
}
?>