<?php
	
	namespace hobotix\Amazon;
	
	class SellerRetriever extends RainforestRetriever
	{
						
		const CLASS_NAME = 'hobotix\\Amazon\\SupplierRetriever';

		public function checkData($seller){

			if (!empty($seller['seller_details'])){
				return $seller['seller_details'];
			}

			return false;
		}

		public function getSeller($amazon_seller_id){
			$this->checkIfPossibleToMakeRequest();

			$options = [
				'type' 			=> 'seller_profile',
				'seller_id' 	=> $amazon_seller_id,
			];

			$this->doRequest($options);

			return $this->getJsonResult();
		}
	}