<?php
class ControllerApiRainforestExternal extends Controller
{

    public function getProducts($asin_list){
        $results = ['status' => true];

        if (count($asin_list) > \hobotix\RainforestAmazon::externalAPIRequestLimits){
            $results = [
                'status'    => false,
                'message'   => 'Exceeded max amount of ASINS. You are trying to get ' . count($asin_list) . ', while max is ' . \hobotix\RainforestAmazon::externalAPIRequestLimits
            ];  
        }

        if ($results['status']){
            $check = $this->rainforestAmazon->checkIfPossibleToMakeRequest(true, false);

            if (!$check){
                $results = [
                    'status'    => false,
                    'message'   => 'RainforestAmazon API Failure: ' . $check['message']
                ];
            } 
        }

        if ($results['status']){
            if (!$this->translateAdaptor->checkIfItIsPossibleToMakeRequest()){
                $results = [
                    'status'    => false,
                    'message'   => 'Translation API Failure'
                ];  
            }
        }

        if ($results['status']){
            $results['products'] = [];

            try{
                $asinsSlice = [];
                foreach ($asin_list as $asin){
                    $asinsSlice[$asin] = [
                        'asin'          => $asin,
                        'product_id'    => $asin,
                    ];
                }

                if ($this->config->get('config_rainforest_external_test_asin')){
                    $this->load->model('kp/product');
                    $amazon_product_data = $this->model_kp_product->getProductAmazonFullData($this->config->get('config_rainforest_external_test_asin'));
                    $amazon_product_json = false;
                    if ($this->config->get('config_enable_amazon_asin_file_cache')){    
                        if ($amazon_product_data && file_exists(DIR_CACHE . $amazon_product_data['file'])){
                            $amazon_product_json = HTTPS_CATALOG . 'system/' . DIR_CACHE_NAME . $amazon_product_data['file'];               
                        }
                    }

                    $products = [
                        $this->config->get('config_rainforest_external_test_asin') => json_decode(file_get_contents($amazon_product_json), true)
                    ];
                } else {
                   $products = $this->rainforestAmazon->simpleProductParser->getProductByASINS($asinsSlice);
                }

                foreach ($products as $asin => $rfProduct){                    
                    if ($rfProduct){
                        $results['products'][$asin] = [
                            'status'        => true,                            
                            'reparsed'      => $this->rainforestAmazon->productsRetriever->passFullProduct($asin, $rfProduct),
                            'original'      => $rfProduct,
                        ];
                    } else {
                        $results['products'][$asin] = [
                            'status'     => false,
                            'reparsed'   => [],
                            'original'   => []
                        ];
                    }                   
                }

            } catch (\Exception $e)  {
                $results = [
                    'status'    => false,
                    'message'   => $e->getMessage()
                ];
            }     
        }


        $this->response->setJSON($results);
    }
}    
