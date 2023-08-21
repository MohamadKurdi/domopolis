<?php
class ControllerSettingRnf extends Controller {
	private $error = array();

	private $cron_settings = [
		'config_rainforest_enable_new_parser',
        'config_rainforest_new_parser_time_start',
        'config_rainforest_new_parser_time_end',

        'config_rainforest_enable_data_parser',
        'config_rainforest_data_parser_time_start',
        'config_rainforest_data_parser_time_end',

        'config_rainforest_enable_tech_category_parser',
        'config_rainforest_tech_category_parser_time_start',
        'config_rainforest_tech_category_parser_time_end',

        'config_rainforest_enable_category_tree_parser',
        
        'config_rainforest_enable_data_l2_parser',
        'config_rainforest_data_l2_parser_time_start',
        'config_rainforest_data_l2_parser_time_end',

        'config_rainforest_enable_offers_parser',
        'config_rainforest_enable_nooffers_parser',
        'config_rainforest_offers_parser_time_start',
        'config_rainforest_offers_parser_time_end',

        'config_rainforest_enable_add_queue_parser',
        'config_rainforest_add_queue_parser_time_start',
        'config_rainforest_add_queue_parser_time_end',
        'config_rainforest_delay_queue_offers',
        'config_rainforest_delay_queue_variants',

        'config_rainforest_enable_add_variants_queue_parser',
        'config_rainforest_add_variants_queue_parser_time_start',
        'config_rainforest_add_variants_queue_parser_time_end',

        'config_rainforest_enable_recoverasins_parser',
        'config_rainforest_recoverasins_parser_time_start',
        'config_rainforest_recoverasins_parser_time_end',

        'config_rainforest_enable_offersqueue_parser',
        'config_rainforest_offersqueue_parser_time_start',
        'config_rainforest_offersqueue_parser_time_end',
        
        'config_rainforest_enable_asins_parser',
        'config_rainforest_enable_eans_parser',        
        'config_rainforest_enable_offers_after_order',
        'config_enable_seogen_cron'
	];

	private $other_settings = [
		'config_rainforest_category_update_period',
		'config_rainforest_max_variants',
		'config_rainforest_skip_variants',
		'config_rainforest_skip_low_price_products',
		'config_rainforest_drop_low_price_products',
		'config_rainforest_drop_low_price_products_for_manual',
		'config_rainforest_update_period',
		'config_rainforest_delay_price_setting',
		'config_rainforest_delay_stock_setting',

		'config_rainforest_enable_review_adding',
		'config_rainforest_max_review_per_product',
		'config_rainforest_min_review_rating',
		'config_rainforest_max_review_length',

		'config_enable_amazon_specific_modes',
		'config_brands_in_mmenu',
		'config_second_level_subcategory_in_categories',
		'config_display_subcategory_in_all_categories',
		'config_rainforest_show_only_filled_products_in_catalog',		

		'config_no_zeroprice',
		'config_product_hide_sku',
		'config_product_replace_sku_with_product_id',

		'config_sort_default',
		'config_order_default',
		'config_disable_filter_subcategory',

		'config_delivery_outstock_enable',
		'config_product_count',

		'config_disable_empty_categories',
		'config_enable_non_empty_categories',

		'config_rainforest_enable_recursive_adding',
		'config_rainforest_enable_compare_with_similar_parsing',
		'config_rainforest_enable_related_parsing',
		'config_rainforest_enable_sponsored_parsing',
		'config_rainforest_enable_similar_to_consider_parsing',
		'config_rainforest_enable_view_to_purchase_parsing',
		'config_rainforest_enable_also_viewed_parsing',
		'config_rainforest_enable_also_bought_parsing',
		'config_rainforest_enable_shop_by_look_parsing',

		'config_rainforest_enable_compare_with_similar_adding',
		'config_rainforest_enable_related_adding',
		'config_rainforest_enable_sponsored_adding',
		'config_rainforest_enable_similar_to_consider_adding',
		'config_rainforest_enable_view_to_purchase_adding',
		'config_rainforest_enable_also_viewed_adding',
		'config_rainforest_enable_also_bought_adding',
		'config_rainforest_enable_shop_by_look_adding',

		'config_rainforest_source_language',
		'config_rainforest_export_names_with_openai',
		'config_rainforest_short_names_with_openai',
		'config_openai_shortennames_length',
		'config_openai_exportnames_length'
	];

	private $pricing_settings = [
		'config_rainforest_nooffers_action',
		'config_rainforest_enable_pricing',
		'config_rainforest_enable_offers_only_for_filled',
		'config_rainforest_enable_offers_after_order',

		'config_rainforest_disable_offers_if_has_special',
		'config_rainforest_disable_offers_use_field_ignore_parse',

		'config_rainforest_nooffers_status_id',
		'config_rainforest_delete_no_offers',
		'config_rainforest_delete_no_offers_counter',
		'config_rainforest_delete_invalid_asins',

		'config_rainforest_nooffers_action_for_manual',
		'config_rainforest_nooffers_quantity_for_manual',
		'config_rainforest_delete_no_offers_for_manual',
		'config_rainforest_delete_no_offers_counter_for_manual',
		'config_rainforest_delete_invalid_asins_for_manual',
		'config_rainforest_nooffers_status_id_for_manual',

		'config_rainforest_nooffers_quantity',
		'config_rainforest_pass_offers_for_ordered',
		'config_rainforest_pass_offers_for_ordered_days',
		'config_rainforest_enable_offers_for_stock',
		'config_rainforest_supplierminrating',
		'config_rainforest_supplierminrating_inner',
		'config_rainforest_max_delivery_days_for_offer',
		'config_rainforest_max_delivery_price',
		'config_rainforest_max_delivery_price_multiplier',

		'config_rainforest_main_formula',
		'config_rainforest_default_store_id',
		'config_rainforest_volumetric_max_wc_multiplier'
	];

	public function index() {
		$this->language->load('setting/setting'); 

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('localisation/language');
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/stock_status');
		$this->load->model('setting/store');			

		$this->data['languages'] 		= $this->model_localisation_language->getLanguages();
		$this->data['order_statuses'] 	= $this->model_localisation_order_status->getOrderStatuses();	
		$this->data['stock_statuses'] 	= $this->model_localisation_stock_status->getStockStatuses();
		$this->data['stores'] 			= $this->model_setting_store->getStores();
		
		$this->data['heading_title'] = 'Управление фреймворком Rainforest / Amazon';
		$this->document->setTitle($this->data['heading_title']);


		foreach ($this->cron_settings as $cron_setting){
			$this->data[$cron_setting] = $this->config->get($cron_setting);		
		}		

		foreach ($this->other_settings as $other_setting){
			$this->data[$other_setting] = $this->config->get($other_setting);		
		}		

		foreach ($this->data['languages'] as $rnf_language){
			if ($rnf_language['code'] != $this->data['config_rainforest_source_language']){
				$this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_enable_language_' . $rnf_language['code']);
			}
		}

		$this->data['config_currency'] = $this->config->get('config_currency');

		if (isset($this->request->post['config_rainforest_main_formula_count'])) {
            $this->data['config_rainforest_main_formula_count'] = $this->request->post['config_rainforest_main_formula_count'];
        } else {
            $this->data['config_rainforest_main_formula_count'] = $this->config->get('config_rainforest_main_formula_count');
        }

        for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){
            if (isset($this->request->post['config_rainforest_main_formula_overload_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->request->post['config_rainforest_main_formula_overload_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->config->get('config_rainforest_main_formula_overload_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_min_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->request->post['config_rainforest_main_formula_min_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->config->get('config_rainforest_main_formula_min_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_default_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->request->post['config_rainforest_main_formula_default_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->config->get('config_rainforest_main_formula_default_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_max_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->request->post['config_rainforest_main_formula_max_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->config->get('config_rainforest_main_formula_max_' . $crmfc);
            }
        }

		foreach ($this->pricing_settings as $pricing_setting){
			$this->data[$pricing_setting] = $this->config->get($pricing_setting);		
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_kg_price_' . $store['store_id']])) {
				$this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->request->post['config_rainforest_kg_price_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->config->get('config_rainforest_kg_price_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_formula_vat_src_' . $store['store_id']])) {
				$this->data['config_rainforest_formula_vat_src_' . $store['store_id']] = $this->request->post['config_rainforest_formula_vat_src_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_formula_vat_src_' . $store['store_id']] = $this->config->get('config_rainforest_formula_vat_src_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_formula_vat_dst_' . $store['store_id']])) {
				$this->data['config_rainforest_formula_vat_dst_' . $store['store_id']] = $this->request->post['config_rainforest_formula_vat_dst_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_formula_vat_dst_' . $store['store_id']] = $this->config->get('config_rainforest_formula_vat_dst_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_formula_tax_' . $store['store_id']])) {
				$this->data['config_rainforest_formula_tax_' . $store['store_id']] = $this->request->post['config_rainforest_formula_tax_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_formula_tax_' . $store['store_id']] = $this->config->get('config_rainforest_formula_tax_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_formula_supplier_' . $store['store_id']])) {
				$this->data['config_rainforest_formula_supplier_' . $store['store_id']] = $this->request->post['config_rainforest_formula_supplier_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_formula_supplier_' . $store['store_id']] = $this->config->get('config_rainforest_formula_supplier_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_formula_invoice_' . $store['store_id']])) {
				$this->data['config_rainforest_formula_invoice_' . $store['store_id']] = $this->request->post['config_rainforest_formula_invoice_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_formula_invoice_' . $store['store_id']] = $this->config->get('config_rainforest_formula_invoice_' . $store['store_id']);
			}
		}

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_default_multiplier_' . $store['store_id']])) {
				$this->data['config_rainforest_default_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_default_multiplier_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_default_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_default_multiplier_' . $store['store_id']);
			}
		}		

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_max_multiplier_' . $store['store_id']])) {
				$this->data['config_rainforest_max_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_max_multiplier_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_max_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_max_multiplier_' . $store['store_id']);
			}
		}	

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_use_volumetric_weight_' . $store['store_id']])) {
				$this->data['config_rainforest_use_volumetric_weight_' . $store['store_id']] = $this->request->post['config_rainforest_use_volumetric_weight_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_use_volumetric_weight_' . $store['store_id']] = $this->config->get('config_rainforest_use_volumetric_weight_' . $store['store_id']);
			}
		}	

		foreach ($this->data['stores']  as $store){
			if (isset($this->request->post['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']])) {
				$this->data['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']] = $this->request->post['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']] = $this->config->get('config_rainforest_volumetric_weight_coefficient_' . $store['store_id']);
			}
		}	

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'setting/rnf.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

	public function calculate(){
		$this->load->model('catalog/product');		
		$mainFormula 					= $this->request->post['main_formula'];
		$weightCoefficient 				= $this->request->post['weight_coefficient'];
		$defaultMultiplier				= $this->request->post['default_multiplier'];
		$maxMultiplier					= $this->request->post['max_multiplier'];
		$useVolumetricWeight			= $this->request->post['use_volumetric_weight'];
		$volumetricWeightCoefficient 	= $this->request->post['volumetric_weight_coefficient'];
		$volumetricMaxWCMultiplier 		= $this->request->post['volumetric_max_wc_multiplier'];
		$showRandomProducts 			= $this->request->post['show_random_products'];
		$limitProducts 					= $this->request->post['limit_products'];
		$zonesConfig 					= $this->request->post['zones_config'];
		$explicitProducts 				= $this->request->post['explicit_products'];

		$formulaOverloadData			= []; 	

		if ($explicitProducts){
			$explodedExplicitProducts = explode(' ', $explicitProducts);
			$explicitProducts = [];

			foreach ($explodedExplicitProducts as $explicitProduct){
				$explicitProducts[] = (int)trim($explicitProduct);
			}
		}

		for ($crmfc = 1; $crmfc <= $this->config->get('config_rainforest_main_formula_count'); $crmfc++){			
			if (!empty($this->request->post['main_formula_min_' . $crmfc])
				&& !empty($this->request->post['main_formula_max_' . $crmfc])
				&& !empty($this->request->post['main_formula_default_' . $crmfc])
				&& !empty($this->request->post['main_formula_overload_' . $crmfc])
			){
				$formulaOverloadData[$crmfc] = [
					'min' 		=> (float)$this->request->post['main_formula_min_' . $crmfc],
					'max' 		=> (float)$this->request->post['main_formula_max_' . $crmfc],
					'default' 	=> (float)$this->request->post['main_formula_default_' . $crmfc],
					'formula' 	=> trim($this->request->post['main_formula_overload_' . $crmfc])
				];
			}
		}

		if ($zonesConfig){
			$zones 		= explode(' ', $zonesConfig);
		} else {
			$zones 		= [0, 20, 50, 100, 1000, 5000, 1000000];
		}

		$products 	= [];		

		for ( $i=0; $i<=(count($zones)-1); $i++){

			if (!empty($zones[$i+1])){
				$products['zone_' . $zones[$i] . '_' . $zones[$i+1]] = [];

				$sql = "SELECT DISTINCT(p.product_id), p.*, pd.name, p2c.category_id as main_category_id FROM product p
						LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '" . (int)$this->config->get('config_language_id') . "')
						LEFT JOIN product_to_category p2c ON (p2c.product_id = p.product_id AND main_category = 1)
						WHERE 
						p.amazon_best_price > '0' 
						AND p.amazon_best_price > '" . (int)$zones[$i] . "' 
						AND p.amazon_best_price <= '" . (int)$zones[$i+1] . "' 
						AND p.asin <> ''												
						AND p.asin <> 'INVALID'";

						if ($explicitProducts){
							$sql .= " AND p.product_id IN (" . implode(',', $explicitProducts) . ")";
						} else {
							$sql .= ' AND p.status = 1 AND weight > 0 AND length > 0';
						}

						if (!$showRandomProducts){
							$sql .= " ORDER BY amazon_best_price ASC ";
						} else {
							$sql .= " ORDER BY RAND() ";
						}

						$sql .= " LIMIT " . (int)$limitProducts;

				$query = $this->db->query($sql);

				foreach ($query->rows as $row){
					$products['zone_' . $zones[$i] . '_' . $zones[$i+1]] [] = $row;
				}
			}
		}

		$results = [];

		foreach ($products as $zone => $products){
			if ($products){
				$results[$zone] = [];			
			}
			foreach ($products as $product){
				$result = [];
				$result['name'] 		= $product['name'];
				$result['product_id'] 	= $product['product_id'];
				$result['asin'] 		= $product['asin'];
			
				$productDimensions = $this->rainforestAmazon->offersParser->PriceLogic->getProductDimensions($product);
				if ($productDimensions['weight_class_id'] == (int)$this->config->get('config_weight_class_id')){
					$result['weight'] = $productDimensions['weight'];
				} else {
					$result['weight'] = $this->weight->convert($productDimensions['weight'], $productDimensions['weight_class_id'], $this->config->get('config_weight_class_id'));
				}

				if ($productDimensions['length_class_id'] == (int)$this->config->get('config_length_class_id')){
					$result['length'] 	= $productDimensions['length'];
					$result['width'] 	= $productDimensions['width'];
					$result['height'] 	= $productDimensions['height'];
				} else {
					$result['length'] 	= $this->length->convert($productDimensions['length'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
					$result['width'] 	= $this->length->convert($productDimensions['width'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
					$result['height'] 	= $this->length->convert($productDimensions['height'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
				}

				$result['weight'] 	= $this->weight->format($result['weight'], $this->config->get('config_weight_class_id'));
				$result['length'] 	= $this->length->format($result['length'], $this->config->get('config_length_class_id'));
				$result['width'] 	= $this->length->format($result['width'], $this->config->get('config_length_class_id'));
				$result['height'] 	= $this->length->format($result['height'], $this->config->get('config_length_class_id'));

				$result['counted_volumetric_weight'] 			= $this->rainforestAmazon->offersParser->PriceLogic->getProductVolumetricWeight($product, 0, true, $volumetricWeightCoefficient);
				$result['counted_volumetric_weight_format']   	= $this->weight->format($result['counted_volumetric_weight'], $this->config->get('config_weight_class_id'));

				if ($useVolumetricWeight){
					$product['counted_weight'] 		= $this->rainforestAmazon->offersParser->PriceLogic->getProductVolumetricWeight($product, 0, false, $volumetricWeightCoefficient);
					$product['counted_weight_real'] = $this->rainforestAmazon->offersParser->PriceLogic->getProductWeight($product);

					if ($volumetricMaxWCMultiplier){
						if ((float)$product['counted_weight'] > ((float)$product['counted_weight_real'] * (float)$volumetricMaxWCMultiplier)){
							$product['counted_weight'] = $product['counted_weight_real'];
						}
					}

				} else {
					$product['counted_weight'] = $this->rainforestAmazon->offersParser->PriceLogic->getProductWeight($product);
				}

				$result['counted_weight'] =  $this->weight->format($product['counted_weight'], $this->config->get('config_weight_class_id'));

				$result['used_default_multiplier'] 	= $defaultMultiplier;
				$result['used_formula'] 			= $mainFormula;

				if ($formulaOverloadData && $overloadFormula = $this->rainforestAmazon->offersParser->PriceLogic->checkOverloadFormula($product['amazon_best_price'], $formulaOverloadData)){
					$defaultMultiplier 		= $overloadFormula['default'];
					$mainFormula 			= $overloadFormula['formula'];

					$result['formula_overloaded'] 		= true;
					$result['used_min'] 				= $this->currency->format($overloadFormula['min'], 'EUR', 1);
					$result['used_max'] 				= $this->currency->format($overloadFormula['max'], 'EUR', 1);
					$result['used_default_multiplier'] 	= $overloadFormula['default'];
					$result['used_formula'] 			= $overloadFormula['formula'];
				}

				$params = [
					'WEIGHT' 				=> (float)$product['counted_weight'],
					'KG_LOGISTIC' 			=> (float)$weightCoefficient,
					'DEFAULT_MULTIPLIER' 	=> (float)$defaultMultiplier,
					'MAX_MULTIPLIER' 		=> (float)$maxMultiplier,
					'VAT_SRC' 			=> (float)$this->config->get('config_rainforest_formula_vat_src_0'),
					'VAT_DST' 			=> (float)$this->config->get('config_rainforest_formula_vat_dst_0'),
					'TAX' 				=> (float)$this->config->get('config_rainforest_formula_tax_0'),
					'SUPPLIER' 			=> (float)$this->config->get('config_rainforest_formula_supplier_0'),
					'INVOICE' 			=> (float)$this->config->get('config_rainforest_formula_invoice_0')										
				];

				$result['counted_price']  = $this->rainforestAmazon->offersParser->PriceLogic->mainFormula($product['amazon_best_price'], $params, $mainFormula);

				$result['amazon_best_price'] 		= $this->currency->format($product['amazon_best_price'], 'EUR', 1);
				$result['counted_price_eur'] 		= $this->currency->format($result['counted_price'], 'EUR', 1);
				$result['counted_price_national'] 	= $this->currency->format($this->currency->convert($result['counted_price'], 'EUR', $this->config->get('config_regional_currency')), $this->config->get('config_regional_currency'), 1);

				$results[$zone][] = $result;
			}
		}

		$this->data['results'] = $results;

		if (defined('API_SESSION')){

			$this->response->setOutput(json_encode($results));

		} else {

			$this->template = 'setting/rnfpricetest.tpl';
			$this->response->setOutput($this->render());

		}
	}

	public function getRainForestStats(){

		$result = $this->rainforestAmazon->checkIfPossibleToMakeRequest(true);

		if ($result['status'] == true){
			$this->data['success'] 	= true;
			$this->data['answer'] 	= $result['answer'];
		} else {
			$this->data['success'] 	= false;
			$this->data['message'] 	= $result['message'];
			$this->data['answer']  	= $result['answer'];
		}

		$this->template = 'setting/rnfhdr.tpl';

		$this->response->setOutput($this->render());

	}
}