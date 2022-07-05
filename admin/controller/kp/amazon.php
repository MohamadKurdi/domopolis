<?php
class ControllerKPAmazon extends Controller {


	public function getProductOffers(){
		$product_id = (int)$this->request->get['product_id'];

		if (!empty($this->request->get['order_id'])){
			$order_id = (int)$this->request->get['order_id'];	
		}

		if (!empty($this->request->get['explicit'])){	
			$explicit = (int)$this->request->get['explicit'];		
		}

		$this->load->model('catalog/product');
		$this->load->model('kp/product');

		$product = $this->model_catalog_product->getProduct($product_id);

		if ($explicit){
			$rfOffers = $this->rainforestAmazon->getProductOffers($product);
			$product = $this->model_catalog_product->getProduct($product_id);

			if ($product['asin']){
				$this->rainforestAmazon->offersParser->addOffersForASIN($product['asin'], $rfOffers);
			}
		}

		$this->data['offers'] = $offers = [];
		if ($product['asin']){		
			$offers = $this->model_kp_product->getProductAmazonOffers($product['asin']);
		}

		foreach ($offers as $offer){
			if ($offer['conditionIsNew']){
				$this->data['offers'][] = [					
					'seller' 				=> $offer['sellerName'],
					'prime'	 				=> $offer['isPrime'],
					'is_best'				=> $offer['isBestOffer'],
					'offer_rating'			=> $offer['offerRating'],
					'supplier'				=> $this->rainforestAmazon->offersParser->Suppliers->getSupplier($offer['sellerName']),
					'price'	 				=> $this->currency->format_with_left($offer['priceAmount'], $offer['priceCurrency'], 1),
					'delivery'	 			=> $this->currency->format_with_left($offer['deliveryAmount'], $offer['deliveryCurrency'], 1),	
					'total'					=> $this->currency->format_with_left($offer['priceAmount'] + $offer['deliveryAmount'], $offer['priceCurrency'], 1),
					'delivery_fba' 			=> $offer['deliveryIsFba'],
					'delivery_comment' 		=> $offer['deliveryComments'],
					'is_min_price'			=> $offer['is_min_price'],							
					'reviews'				=> (int)$offer['sellerRatingsTotal'],
					'rating'				=> (int)$offer['sellerRating50'] / 10,
					'positive'				=> (int)$offer['sellerPositiveRatings100'],
					'date_added'			=> date('Y-m-d H:i:s', strtotime($offer['date_added'])),
					'link'					=> $offer['sellerLink']?$offer['sellerLink']:$this->rainforestAmazon->createLinkToAmazonSearchPage($product['asin']),
					'link2'					=> $this->rainforestAmazon->createLinkToAmazonSearchPage($offer['asin'])
				];
			}
		}

		$this->data['amazon_best_price'] 	= $product['amazon_best_price'];
		$this->data['amazon_lowest_price'] 	= $product['amazon_lowest_price'];

		$this->template = 'sale/amazon_offers_list.tpl';

		$this->response->setOutput($this->render());

	}


















}		