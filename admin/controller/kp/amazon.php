<?php
class ControllerKPAmazon extends Controller {


	public function add(){
		$this->load->model('report/product');

		$asin 			= trim($this->request->post['asin']);
		$category_id 	= (int)($this->request->post['category_id']);
		$brand_logic	= (int)($this->request->post['brand_logic']);

		if ($asin){
			$this->model_report_product->insertAsinToQueue(['asin' => $asin, 'category_id' => $category_id, 'brand_logic' => $brand_logic]);	

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'add', 'entity_type' => 'product', 'entity_id' => 0]);		
		}

		$this->response->setOutput(json_encode(['result' => 'success']));
	}

	public function ignore(){
		$this->load->model('report/product');

		$asin 			= trim($this->request->post['asin']);
		$category_id 	= (int)($this->request->post['category_id']);

		if ($asin){
			$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($asin) . "', name = 'FROM_AMAZON_LOOKUP', date_added = NOW(), user_id = '" . $this->user->getID() . "'");	

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'delete', 'entity_type' => 'product', 'entity_id' => 0]);		
		}

		$this->response->setOutput(json_encode(['result' => 'success']));
	}

	public function getRainforestPage(){
		$url 			= trim($this->request->post['url']);
		$category 		= trim($this->request->post['category']);
		$page 			= (int)($this->request->post['page']);
		$sort 			= trim($this->request->post['sort']);
		$search_term 	= trim($this->request->post['search_term']);
		$type 			= trim($this->request->post['type']);

		$compile 		= !empty($this->request->post['compile'])?1:0;
		$offers 		= !empty($this->request->post['offers'])?1:0;

		if ($type == 'standard'){
			$type = 'category';
		}

		if (!empty($search_term)){
			$options = [
				'type' 			=> $type, 		
				'search_term' 	=> $search_term,
				'page' 			=> $page,
				'sort' 			=> $sort
			];

			if ($sort != 'amazon'){
				$options['sort'] = $sort;
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
				$options['sort'] = $sort;
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

		$this->data['products'] = [];		
		$this->data['existent_products'] = [];
		$this->data['cheap_products'] = [];
		$this->data['deleted_products'] = [];
		$this->data['in_queue_products'] = [];

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

}		