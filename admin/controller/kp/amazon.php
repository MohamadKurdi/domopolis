<?php
class ControllerKPAmazon extends Controller {
	private $json = [];

	public function counters(){
		$categoryWord = $this->rainforestAmazon->categoryRetriever->getCategorySearchWordInfo($this->request->data['category_word_id']);

		$this->response->setOutput(json_encode($categoryWord));
	}

	public function add(){
		$this->load->model('report/product');

		$asin 			= trim($this->request->post['asin']);
		$category_id 	= (int)($this->request->post['category_id']);
		$brand_logic	= (int)($this->request->post['brand_logic']);

		if ($asin){
			$this->model_report_product->insertAsinToQueue(['asin' => $asin, 'category_id' => $category_id, 'brand_logic' => $brand_logic]);	

			if (!empty($this->request->post['category_word_id'])){
				$this->rainforestAmazon->categoryRetriever->updateCategorySearchWordProductAddedPlus($this->request->post['category_word_id'], 1);
			}
		}

		$this->response->setOutput(json_encode(['result' => 'success']));
	}

	public function ignore(){
		$this->load->model('report/product');

		$asin 			= trim($this->request->post['asin']);
		$category_id 	= (int)($this->request->post['category_id']);

		if ($asin){
			$this->model_report_product->insertDeletedASIN(['asin' => $asin, 'name' => 'FROM_AMAZON_LOOKUP']);			
		}

		$this->response->setOutput(json_encode(['result' => 'success']));
	}

	public function countPagination(){
		$pagination = [
			'total_pages' => 0,
			'total_results' => 0,
		];

		$options 	= $this->rainforestAmazon->prepareAmazonRainforestPageRequest($this->request->post);

		$curl 		= $this->rainforestAmazon->categoryRetriever->createRequest($options);
		$result 	= curl_exec($curl);		

		if ($result){
			$pagination = $this->rainforestAmazon->processAmazonRainforestPageRequestPaginationResults($result);
		}

		$this->response->setOutput(json_encode($pagination));
	}

	public function getRainforestPage(){
		$url 			= trim($this->request->data['url']);
		$category 		= trim($this->request->data['category']);
		$page 			= (int)($this->request->data['page']);
		$sort 			= trim($this->request->data['sort']);
		$search_term 	= trim($this->request->data['search_term']);
		$type 			= trim($this->request->data['type']);
		$compile 		= !empty($this->request->data['compile'])?1:0;
		$offers 		= !empty($this->request->data['offers'])?1:0;

		if ($type == 'standard'){
			$type = 'category';
		}

		if (!empty($search_term)){
			$options = [
				'type' 			=> $type, 		
				'search_term' 	=> $search_term,
				'page' 			=> $page,
			];

			if ($sort != 'amazon'){
				$options['sort_by'] = $sort;
			}

		} elseif (!empty($url)){
			$options = [
				'type' 			=> $type, 		
				'url' 			=> str_replace('&amp;', '&', $url),
				'page' 			=> $page,
			];
		} else {
			$options = [
				'type' 			=> $type, 		
				'category_id' 	=> $category,
				'page' 			=> $page,
			];

			if ($sort != 'amazon'){
				$options['sort_by'] = $sort;
			}
		}		

		$curl = $this->rainforestAmazon->categoryRetriever->createRequest($options);
		$result = curl_exec($curl);
		$result = $this->rainforestAmazon->categoryRetriever->parseResponse($result);
		
		$products = [];

		if ($type == 'category'){
			$products = $result['category_results'];
		} elseif ($type == 'bestsellers'){
			$products = $result['bestsellers'];
		} elseif ($type == 'deals'){
			$products = $result['deals'];
		} elseif ($type == 'search'){
			$products = $result['search_results'];
		} elseif ($type == 'store'){
			$products = $result['store_results'];
		} elseif ($type == 'store'){
			$products = $result['store_results'];
		} elseif ($type == 'seller_products'){
			$products = $result['seller_products'];
		}

		$this->data['products'] 			= [];		
		$this->data['existent_products'] 	= [];
		$this->data['cheap_products'] 		= [];
		$this->data['deleted_products'] 	= [];
		$this->data['in_queue_products'] 	= [];

		foreach ($products as $product){
			if ($this->rainforestAmazon->productsRetriever->model_product_get->checkIfAsinIsDeleted($product['asin'])){
				$this->data['deleted_products'][] = $product;
				continue;
			}

			if ($this->rainforestAmazon->productsRetriever->model_product_get->getIfAsinIsInQueue($product['asin'])){
				$this->data['in_queue_products'][] = $product;
				continue;
			}

			if (!empty($product['price']) && !empty($product['price']['value'])){
				if ($this->config->get('config_rainforest_skip_low_price_products')){
					if ((float)$product['price']['value'] < (float)$this->config->get('config_rainforest_skip_low_price_products')){
						$this->data['cheap_products'][] = $product;
						continue;
					}
				}
			}	

			if ($this->rainforestAmazon->productsRetriever->getProductsByAsin($product['asin'])){
				$this->data['existent_products'][] = $product;
				continue;
			}

			$this->data['products'][] = $product;
		}
		
		$this->data['pagination'] = '';
		if (!empty($result['pagination'])){

			if (!empty($result['pagination']['total_results'])){
				$this->data['total_results'] = $result['pagination']['total_results'];
			}

			$this->data['num_pages'] 	= $result['pagination']['total_pages'];
			$this->data['current_page'] = $result['pagination']['current_page'];

			$pagination 				= new Pagination();
			$this->data['pagination'] 	= $pagination->render_simple([
				'num_pages' 	=> 	$result['pagination']['total_pages'],
				'page' 			=>	$result['pagination']['current_page'],
				'num_links' 	=>  10 							
			]);
		}

		$this->template = 'kp/amazon_category_results.tpl';

		$this->response->setOutput($this->render());
	}

	public function getCategoryWordSearchResults(){
		$pages 		= [];
		$offers 	= [];
		foreach ($this->request->data['checkboxes'] as $category_word_id){
			$pages[$category_word_id] = $this->request->data['pages'][$category_word_id];
		}

		foreach ($pages as $category_word_id => $page){
			$categoryWord = $this->rainforestAmazon->categoryRetriever->getCategorySearchWordInfo($category_word_id);

			$options = [
				'type' 					=> $categoryWord['category_word_type'],
				'word_or_uri' 			=> $categoryWord['category_search_word'],
				'page' 					=> $page,
				'sort' 					=> $categoryWord['category_search_sort'],
				'category_id'			=> $categoryWord['category_word_category_id'],
			];			

			$request = $this->rainforestAmazon->prepareAmazonRainforestPageRequest($options);
			$requests[$categoryWord['category_search_word_id']] = $request;
		}

		if (!$this->config->get('config_rainforest_debug_products_v2_file')){
			$results = $this->rainforestAmazon->categoryRetriever->doMultiRequest($requests);	
		} else {					
			$results = json_decode(file_get_contents(DIR_SYSTEM . '/temp/rainforest.addasinv2.json'), true);
		}

		$this->data['results'] = [];
		foreach ($results as $category_word_id => $result){
			$categoryWord = $this->rainforestAmazon->categoryRetriever->getCategorySearchWordInfo($category_word_id);			

			$title = \hobotix\RainforestAmazon::searchPageTypes[$categoryWord['category_word_type']]['name'] . ': ';
			if (!empty($categoryWord['category_search_word'])) {
				$title .= $categoryWord['category_search_word'];
			} else {
				$title .= $categoryWord['category_word_category'];
			}

			$products_good 	= [];			
			$products_bad 	= [
				'checkIfAsinIsDeleted' 	=> [],
				'getIfAsinIsInQueue' 	=> [],
				'getProductsByAsin' 	=> [],
				'checkIfNameIsExcluded' => [],
				'validate_name' 		=> [],
				'min_rating'	 		=> [],
				'min_reviews'	 		=> [],
				'prime_filter'	 		=> [],
				'no_price'		 		=> [],
				'min_price'		 		=> [],
				'max_price'		 		=> [],
				'min_offers'		 	=> [],
			];
			$products_after_first_exclusions = [];

			if ($pagination = $this->rainforestAmazon->processAmazonRainforestPageRequestPaginationResults($result)){
				$this->rainforestAmazon->categoryRetriever->setCategorySearchWordTotalProducts($category_word_id, $pagination['total_results']);
				$this->rainforestAmazon->categoryRetriever->setCategorySearchWordTotalPages($category_word_id, $pagination['total_pages']);
			}

			$products = $this->rainforestAmazon->processAmazonRainforestPageRequestProductResults($result);

            if ($products){
                $total = count($products);
            } else {
                $total = 0;
            }

			$pagination['total_product_on_page'] = $total;
			foreach ($products as &$product){
				if ($this->rainforestAmazon->productsRetriever->model_product_get->checkIfAsinIsDeleted($product['asin'])){
					$products_bad['checkIfAsinIsDeleted'][] = $product;
					continue;
				}

				if ($this->rainforestAmazon->productsRetriever->model_product_get->getIfAsinIsInQueue($product['asin'])){
					$products_bad['getIfAsinIsInQueue'][] = $product;
					continue;
				}

				if ($this->rainforestAmazon->productsRetriever->getProductsByAsin($product['asin'])){
					$products_bad['getProductsByAsin'][] = $product;
					continue;
				}

				if ($this->rainforestAmazon->productsRetriever->model_product_get->checkIfNameIsExcluded($product['title'], $categoryWord['category_id'], $validation_reason)){		
					$this->rainforestAmazon->productsRetriever->model_product_edit->deleteASINFromQueue($product['asin']);	
					$product['validation_name_reason'] 			= $validation_reason;			
					$products_bad['checkIfNameIsExcluded'][] 	= $product;
					continue;
				}

				if (!empty(trim($categoryWord['category_search_exact_words']))){
					$category_search_exact_words = prepareEOLArray($categoryWord['category_search_exact_words']);

					if ($category_search_exact_words){
						if (!validate_name($product['title'], $category_search_exact_words, $validation_reason)){
							$product['validation_name_reason'] 		= $validation_reason;
							$products_bad['validate_name'][] 		= $product;
							continue;
						}
					}
				}

				if ((float)$categoryWord['category_search_min_rating'] > 0){
					if (empty($product['rating'])){
						$products_bad['min_rating'][] 	= $product;
						continue;
					} else {
						if ((float)$product['rating'] < (float)$categoryWord['category_search_min_rating']){
							$products_bad['min_rating'][] 	= $product;
							continue;
						}
					}
				}

				if ((int)$categoryWord['category_search_min_reviews'] > 0){
					if (empty($product['ratings_total'])){
						$products_bad['min_reviews'][] 	= $product;
						continue;
					} else {
						if ((int)$product['ratings_total'] < (int)$categoryWord['category_search_min_reviews']){
							$products_bad['min_reviews'][] 	= $product;
							continue;
						}
					}
				}

				if ((bool)$categoryWord['category_search_has_prime']){
					if (empty($product['is_prime'])){
						$products_bad['prime_filter'][] 	= $product;
						continue;
					}
				}

				if (empty($product['price']) || empty($product['price']['value'])){
					$products_bad['no_price'][] 	= $product;		
				}

				if (!empty($product['price']) && !empty($product['price']['value'])){
					if ((float)$categoryWord['category_search_min_price']){
						if ((float)$product['price']['value'] < (float)$categoryWord['category_search_min_price']){
							$products_bad['min_price'][] 	= $product;				
							continue;
						}
					}

					if ((float)$categoryWord['category_search_max_price']){
						if ((float)$product['price']['value'] > (float)$categoryWord['category_search_max_price']){
							$products_bad['max_price'][] 	= $product;					
							continue;
						}
					}
				}

				$products_good[$product['asin']] = $product;
			}

			if (!empty($this->request->data['offers']) && !empty($this->request->data['offers'][$category_word_id])){
				$good_products_asins = array_keys($products_good);
				$total = count($good_products_asins);
				$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);

				for ($i = 1; $i <= $iterations; $i++){
					$timer = new \hobotix\FPCTimer();
					$slice = array_slice($good_products_asins, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);

					$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

					if ($results){
						foreach ($results as $asin => $offers){
							$products_good[$asin]['count_offers_before'] = (int)count($offers);

							if ((int)$categoryWord['category_search_min_offers'] > (int)count($offers)){								
								$products_bad['min_offers'][] 	= $products_good[$asin];
								unset($products_good[$asin]);
							} else {
								$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);	
								$count_offers = $this->rainforestAmazon->offersParser->getOffersForASIN($asin);

								$products_good[$asin]['count_offers_after'] = (int)count_offers;

								if ((int)$categoryWord['category_search_min_offers'] > $count_offers){
									$products_bad['min_offers'][] 	= $products_good[$asin];
									unset($products_good[$asin]);
								}
							}												
						}
					}
				}
			}

			$this->rainforestAmazon->categoryRetriever
					->setCategorySearchWordLastScan($category_word_id)
					->setCategorySearchWordPagesParsed($category_word_id, $page);

			$this->data['results'][$category_word_id] = [
				'title' 		=> $title,
				'page' 			=> $page,
				'pagination' 	=> $pagination,
				'products' 		=> $products_good,
				'products_bad' 	=> $products_bad
			];
		}

		$this->template = 'kp/amazon_category_results_extended.tpl';
		$this->response->setOutput($this->render());
	}


	public function getProductOffers(){
		$product_id = (int)$this->request->get['product_id'];

		if (!empty($this->request->get['order_id'])){
			$order_id = (int)$this->request->get['order_id'];	
		}

		if (!empty($this->request->get['explicit'])){	
			$explicit = (int)$this->request->get['explicit'];		
		} else {
			$explicit = false;
		}

		$this->load->model('catalog/product');
		$this->load->model('kp/product');

		$product = $this->model_catalog_product->getProduct($product_id);

		if ($explicit){
			$rfOffers = $allRfOffers = $this->rainforestAmazon->getProductOffers($product);
			$product = $this->model_catalog_product->getProduct($product_id);

			if ($product['asin']){
				$this->rainforestAmazon->offersParser->addOffersForASIN($product['asin'], $rfOffers);
			}
		}

		$this->data['offers'] 		= $offers 		= [];
		$this->data['bad_offers'] 	= $bad_offers 	= [];
		if ($product['asin']){		
			$offers = $this->model_kp_product->getProductAmazonOffers($product['asin']);
		}

		$good_offers = [];
		foreach ($offers as $offer){
				$good_offers[] = $offer['offer_id'];

				$supplier = $this->rainforestAmazon->offersParser->Suppliers->getSupplier($offer['sellerName'], $offer['sellerID']);

				$this->data['offers'][] = [					
					'seller' 				=> $offer['sellerName'],
					'prime'	 				=> $offer['isPrime'],
					'buybox_winner'	 		=> $offer['isBuyBoxWinner'],
					'is_best'				=> $offer['isBestOffer'],
					'offer_rating'			=> $offer['offerRating'],
					'supplier'				=> $supplier,
					'edit_supplier' 		=> $supplier?$this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier['supplier_id']):'',
					'price'	 				=> $this->currency->format_with_left($offer['priceAmount'], $offer['priceCurrency'], 1),
					'delivery'	 			=> $this->currency->format_with_left($offer['deliveryAmount'], $offer['deliveryCurrency'], 1),	
					'total'					=> $this->currency->format_with_left($offer['priceAmount'] + $offer['deliveryAmount'], $offer['priceCurrency'], 1),
					'delivery_fba' 			=> $offer['deliveryIsFba'],
					'delivery_comment' 		=> $offer['deliveryComments'],
					'min_days' 				=> $offer['minDays'],
					'delivery_from' 		=> $offer['deliveryFrom'],
					'delivery_to' 			=> $offer['deliveryTo'],
					'is_min_price'			=> $offer['is_min_price'],							
					'reviews'				=> (int)$offer['sellerRatingsTotal'],
					'rating'				=> (int)$offer['sellerRating50'] / 10,
					'positive'				=> (int)$offer['sellerPositiveRatings100'],
					'quality'				=> $offer['sellerQuality'],
					'country'				=> $offer['offerCountry'],
					'is_native'				=> $offer['isNativeOffer'],
					'date_added'			=> date('Y-m-d H:i:s', strtotime($offer['date_added'])),
					'link'					=> $offer['sellerLink']?$offer['sellerLink']:$this->rainforestAmazon->createLinkToAmazonSearchPage($product['asin']),
					'link2'					=> $this->rainforestAmazon->createLinkToAmazonSearchPage($offer['asin'])
				];
		}

		if ($explicit){
			$bad_offers = [];
			foreach ($allRfOffers as $rfOffer){
				if (!empty($rfOffer->getOriginalDataArray()['offer_id'])){
					$offer_id = $rfOffer->getOriginalDataArray()['offer_id'];
				} else {
					$offer_id = md5(serialize($rfOffer->getOriginalDataArray()));
				}
				
				if (!in_array($offer_id, $good_offers)){
					$bad_offers[] = $rfOffer;
				}
			}

			if ($bad_offers){
				$bad_offers = $this->rainforestAmazon->offersParser->reparseOffersToSkip($bad_offers, true);

				foreach ($bad_offers as $rfOffer){
					$buyBoxWinner = false;
					if (!empty($rfOffer->getOriginalDataArray()['buybox_winner'])){
						$buyBoxWinner = true;
					}

					$offerDates = false;
					if ($rfOffer->getDeliveryComments()){
						$offerDates = $this->rainforestAmazon->offersParser->parseAmazonDeliveryComment($rfOffer->getDeliveryComments());
					}

					$supplier = $this->rainforestAmazon->offersParser->Suppliers->getSupplier($rfOffer->getSellerName(), $rfOffer->getSellerID());

					$this->data['bad_offers'][] = [					
						'seller' 				=> $rfOffer->getSellerName(),
						'prime'	 				=> $rfOffer->getIsPrime(),
						'buybox_winner'	 		=> $buyBoxWinner,
						'is_best'				=> false,
						'offer_rating'			=> 0,
						'supplier'				=> $supplier,
						'edit_supplier' 		=> $supplier?$this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier['supplier_id']):'',
						'price'	 				=> $this->currency->format_with_left($rfOffer->getPriceAmount(), $rfOffer->getPriceCurrency(), 1),
						'delivery'	 			=> $this->currency->format_with_left($rfOffer->getDeliveryAmount(), $rfOffer->getDeliveryCurrency(), 1),	
						'total'					=> $this->currency->format_with_left($rfOffer->getPriceAmount() + $rfOffer->getDeliveryAmount(), $rfOffer->getPriceCurrency(), 1),
						'delivery_fba' 			=> $rfOffer->getDeliveryIsFba(),
						'delivery_comment' 		=> $rfOffer->getDeliveryComments(),
						'min_days' 				=> ($offerDates?(int)$offerDates['minDays']:'0'),
						'delivery_from' 		=> ($offerDates?$this->db->escape($offerDates['deliveryFrom']):'0'),
						'delivery_to'   		=> ($offerDates?$this->db->escape($offerDates['deliveryTo']):'0'),
						'is_min_price'			=> false,	
						'is_new'				=> false,						
						'reviews'				=> (int)$rfOffer->getSellerRatingsTotal(),
						'rating'				=> (int)$rfOffer->getSellerRating50() / 10,
						'positive'				=> (int)$rfOffer->getSellerPositiveRatings100(),
						'quality'				=> $rfOffer->getSellerQuality(),
						'country'				=> $rfOffer->getSellerCountry(),
						'is_native'				=> $rfOffer->getSellerNative(),
						'bad_reason'			=> $rfOffer->getBadReason(),
						'date_added'			=> 'skipped',
						'link'					=> $rfOffer->getSellerLink()?$rfOffer->getSellerLink():$this->rainforestAmazon->createLinkToAmazonSearchPage($product['asin']),
						'link2'					=> $this->rainforestAmazon->createLinkToAmazonSearchPage($product['asin'])
					];
				}
			}			
		}

		$this->data['amazon_best_price'] 	= $product['amazon_best_price'];
		$this->data['amazon_lowest_price'] 	= $product['amazon_lowest_price'];

		$this->template = 'sale/amazon_offers_list.tpl';

		$this->response->setOutput($this->render());
	}

	public function load_category_search_words(){
		$this->load->model('catalog/category');
		$this->load->model('user/user');

		$filter_data = [
			'filter_category_id' 	=> $this->request->get['category_id'],
			'filter_user_id' 		=> $this->request->get['user_id'],
			'filter_auto' 			=> $this->request->get['filter_auto'],
		];

		$this->data['category_search_words'] = $this->model_catalog_category->getCategorySearchWordsFilter($filter_data);

		foreach ($this->data['category_search_words'] as &$category_search_word){
			$category_search_word['category_word_user'] = $this->model_user_user->getRealUserNameById($category_search_word['category_word_user_id']);
			$category_search_word['category_search_min_price'] = $this->currency->format_with_left($category_search_word['category_search_min_price'], $this->config->get('config_currency'), 1);
			$category_search_word['category_search_max_price'] = $this->currency->format_with_left($category_search_word['category_search_max_price'], $this->config->get('config_currency'), 1);
		}

		$this->template = 'kp/category_search_words.tpl';
		$this->response->setOutput($this->render());
	}


	public function random_name(){		
		$result = [];

		$query = $this->db->query("SELECT name FROM product_description pd 
			LEFT JOIN product p ON (pd.product_id = p.product_id) 
			LEFT JOIN product_to_category p2c ON (p2c.product_id = p.product_id) 
			WHERE 
			p2c.category_id 	= '" . (int)$this->request->get['category_id'] . "'
			AND language_id 	= '" . $this->config->get('config_rainforest_source_language_id') . "' 
			AND name <> ''
			ORDER BY RAND() LIMIT 5");

		if (!$query->rows){
			$query = $this->db->query("SELECT name FROM product_description pd 
			LEFT JOIN product p ON (pd.product_id = p.product_id) 			
			WHERE 
			language_id 	= '" . $this->config->get('config_rainforest_source_language_id') . "' 
			AND name <> ''
			ORDER BY RAND() LIMIT 5");
		}

		foreach ($query->rows as $row){
			$result[] = $row['name'];
		}

		$this->response->setOutput(implode(PHP_EOL, $result));
	}

	public function validate_name(){
		$rules 	= $this->request->data['rules'];
		$texts 	= explode(PHP_EOL, $this->request->data['text']);

		$html = '';
		foreach ($texts as $text){
			$status = validate_name($text, $rules, $result);

			$html .= '<tr>';
			$html .= '<td style="width:60%" class="' . ($status?'validate_success':'validate_fail') . '">' . $text . '</td>';
			$html .= '<td class="' . ($status?'validate_success':'validate_fail') . '">' . $result . '</td>';
			$html .= '</tr>';
		}
		

		$this->response->setOutput($html);
	}

}		