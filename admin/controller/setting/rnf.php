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

        'config_rainforest_enable_category_queue_parser',
        'config_rainforest_category_queue_parser_time_start',
        'config_rainforest_category_queue_parser_time_end',
        'config_rainforest_category_queue_update_period',

        'config_rainforest_enable_add_variants_queue_parser',
        'config_rainforest_add_variants_queue_parser_time_start',
        'config_rainforest_add_variants_queue_parser_time_end',

        'config_rainforest_enable_recoverasins_parser',
        'config_rainforest_recoverasins_parser_time_start',
        'config_rainforest_recoverasins_parser_time_end',

        'config_rainforest_enable_offersqueue_parser',
        'config_rainforest_offersqueue_parser_time_start',
        'config_rainforest_offersqueue_parser_time_end',

        'config_rainforest_enable_reprice_cron',
        'config_rainforest_reprice_cron_time_start',
        'config_rainforest_reprice_cron_time_end',
        
        'config_rainforest_enable_asins_parser',
        'config_rainforest_enable_eans_parser',        
        'config_rainforest_enable_offers_after_order',
        'config_enable_seogen_cron',

        'config_rainforest_enable_checkzipcodes_parser',
        'config_rainforest_checkzipcodes_bad_request_limit'
	];

	private $debug_settings = [
		'config_rainforest_debug_library',
		'config_rainforest_debug_http_library',			
		'config_rainforest_debug_offers',
		'config_rainforest_debug_products',
		'config_rainforest_debug_categories',

		'config_rainforest_debug_products_v2_file',

		'config_rainforest_debug_request_timeout',

		'config_rainforest_debug_curl_request_timeout',
		'config_rainforest_debug_curl_connect_timeout',
		'config_rainforest_debug_mysql_pricelogic',

        'config_rainforest_cleanup_empty_attributes',
        'config_rainforest_cleanup_empty_manufacturers'
	];

	private $other_settings = [
		'config_rainforest_category_update_period',
		'config_rainforest_max_variants',
		'config_rainforest_skip_variants',
		'config_rainforest_skip_min_offers_products',
		'config_rainforest_skip_low_price_products',
		'config_rainforest_merchant_skip_low_price_products',
		'config_rainforest_skip_high_price_products',
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

		'config_rainforest_check_technical_category_id',
		'config_rainforest_check_unknown_category_id',
		'config_rainforest_default_technical_category_id',
		'config_rainforest_default_unknown_category_id',

		'config_rainforest_test_asin',

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
		'config_rainforest_description_symbol_limit',
		'config_rainforest_export_names_with_openai',
		'config_rainforest_short_names_with_openai',
		'config_openai_enable_shorten_names_before_translation',
		'config_openai_enable_shorten_names_after_translation',
		'config_openai_shortennames_length',
		'config_openai_exportnames_length',

		'config_rainforest_external_test_asin',
		'config_rainforest_external_enable_attributes',
		'config_rainforest_external_enable_features',
		'config_rainforest_external_enable_descriptions',
		'config_rainforest_external_enable_names',
		'config_rainforest_external_enable_color',
		'config_rainforest_external_enable_material',
		'config_rainforest_external_enable_dimensions',
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

		'config_rainforest_skip_not_native_offers',
		'config_rainforest_native_country_code',
		'config_rainforest_do_not_skip_countries',

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
		$this->load->model('sale/supplier');

		$this->data['languages'] 		= $this->model_localisation_language->getLanguages();
		$this->data['order_statuses'] 	= $this->model_localisation_order_status->getOrderStatuses();	
		$this->data['stock_statuses'] 	= $this->model_localisation_stock_status->getStockStatuses();
		$this->data['stores'] 			= $this->model_setting_store->getStores();
		$this->data['supplier_countries'] = $this->model_sale_supplier->getAllSupplierCountryCodes();
		
		$this->data['heading_title'] = 'Управление фреймворком Rainforest / Amazon';
		$this->document->setTitle($this->data['heading_title']);


		foreach ($this->cron_settings as $cron_setting){
			$this->data[$cron_setting] = $this->config->get($cron_setting);		
		}		

		foreach ($this->debug_settings as $debug_setting){
			$this->data[$debug_setting] = $this->config->get($debug_setting);		
		}

		foreach ($this->other_settings as $other_setting){
			$this->data[$other_setting] = $this->config->get($other_setting);		
		}		

		foreach ($this->data['languages'] as $rnf_language){
			if ($rnf_language['code'] != $this->data['config_rainforest_source_language']){
				$this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_enable_language_' . $rnf_language['code']);
			}
		}

		foreach ($this->data['languages'] as $rnf_language){
			if ($rnf_language['code'] != $this->data['config_rainforest_source_language']){
				$this->data['config_rainforest_external_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_external_enable_language_' . $rnf_language['code']);
			}
		}

		$this->data['config_currency'] = $this->config->get('config_currency');

		if (isset($this->request->post['config_rainforest_main_formula_count'])) {
            $this->data['config_rainforest_main_formula_count'] = $this->request->post['config_rainforest_main_formula_count'];
        } else {
            $this->data['config_rainforest_main_formula_count'] = $this->config->get('config_rainforest_main_formula_count');
        }

        $this->data['zones_config'] = [];
        for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){
            if (isset($this->request->post['config_rainforest_main_formula_overload_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->request->post['config_rainforest_main_formula_overload_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->config->get('config_rainforest_main_formula_overload_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_default_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->request->post['config_rainforest_main_formula_default_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->config->get('config_rainforest_main_formula_default_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_costprice_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_costprice_' . $crmfc] = $this->request->post['config_rainforest_main_formula_costprice_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_costprice_' . $crmfc] = $this->config->get('config_rainforest_main_formula_costprice_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_min_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->request->post['config_rainforest_main_formula_min_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->config->get('config_rainforest_main_formula_min_' . $crmfc);
            }

            $this->data['zones_config'][] = (float)$this->data['config_rainforest_main_formula_min_' . $crmfc];

            if (isset($this->request->post['config_rainforest_main_formula_max_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->request->post['config_rainforest_main_formula_max_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->config->get('config_rainforest_main_formula_max_' . $crmfc);
            }

            $this->data['zones_config'][] = (float)$this->data['config_rainforest_main_formula_max_' . $crmfc];
        } 

        if (!$this->data['zones_config']){
        	$this->data['zones_config'] = [0, 20, 50, 100, 1000, 10000];
        } else {
        	$this->data['zones_config'] = array_unique($this->data['zones_config']);
        }

        asort($this->data['zones_config'], SORT_NUMERIC);

        $this->data['zones_config'] = implode(' ', $this->data['zones_config']);


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

			if (isset($this->request->post['config_rainforest_default_costprice_multiplier_' . $store['store_id']])) {
				$this->data['config_rainforest_default_costprice_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_default_costprice_multiplier_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_default_costprice_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_default_costprice_multiplier_' . $store['store_id']);
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

	public function getZipCodes(){
		$result = [];

		$zipcodes = $this->rainforestAmazon->checkZipCodes();

		if (!empty($zipcodes['zipcodes']) && !empty($zipcodes['zipcodes'][$this->config->get('config_rainforest_api_domain_1')])){
			foreach ($zipcodes['zipcodes'][$this->config->get('config_rainforest_api_domain_1')] as $zipcode){
				if ($zipcode['status'] == 'available'){
					$result[] = $zipcode['zipcode'];
				}				
			}
		} else {
		}

		$this->response->setOutput(json_encode($result));
	}

	public function calculate(){
		$this->load->model('catalog/product');		
		$mainFormula 					= $this->request->post['main_formula'];
		$weightCoefficient 				= $this->request->post['weight_coefficient'];
		$defaultMultiplier				= $this->request->post['default_multiplier'];
		$defaultCostPriceMultiplier		= $this->request->post['default_costprice_multipiler'];
		$maxMultiplier					= $this->request->post['max_multiplier'];
		$useVolumetricWeight			= $this->request->post['use_volumetric_weight'];
		$volumetricWeightCoefficient 	= $this->request->post['volumetric_weight_coefficient'];
		$volumetricMaxWCMultiplier 		= $this->request->post['volumetric_max_wc_multiplier'];
		$showRandomProducts 			= $this->request->post['show_random_products'];
		$limitProducts 					= $this->request->post['limit_products'];
		$zonesConfig 					= $this->request->post['zones_config'];
		$explicitProducts 				= $this->request->post['explicit_products'];
		$explicitCategories 			= $this->request->post['explicit_categories'];

		$formulaOverloadData			= []; 	

		if ($explicitProducts){
			$explodedExplicitProducts = explode(' ', $explicitProducts);
			$explicitProducts = [];

			foreach ($explodedExplicitProducts as $explicitProduct){
				$explicitProducts[] = (int)trim($explicitProduct);
			}
		}

		if ($explicitCategories){
			$explodedExplicitCategories = explode(' ', $explicitCategories);
			$explicitCategories = [];

			foreach ($explodedExplicitCategories as $explicitCategory){
				$explicitCategories[] = (int)trim($explicitCategory);
			}
		}

		for ($crmfc = 1; $crmfc <= $this->config->get('config_rainforest_main_formula_count'); $crmfc++){		
			if (!empty($this->request->post['main_formula_min_' . $crmfc])
				&& !empty($this->request->post['main_formula_max_' . $crmfc])
				&& !empty($this->request->post['main_formula_costprice_' . $crmfc])
				&& !empty($this->request->post['main_formula_default_' . $crmfc])
				&& !empty($this->request->post['main_formula_overload_' . $crmfc])
			){
				$formulaOverloadData[$crmfc] = [
					'min' 		=> (float)$this->request->post['main_formula_min_' . $crmfc],
					'max' 		=> (float)$this->request->post['main_formula_max_' . $crmfc],
					'costprice' => (float)$this->request->post['main_formula_costprice_' . $crmfc],
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

						if (!$this->config->get('config_rainforest_enable_offers_for_added_from_amazon')){
							$sql .= " AND (p.added_from_amazon = 1)";
						}

						if (!$this->config->get('config_rainforest_enable_offers_for_stock')){
							$sql .= " AND (" . $this->rainforestAmazon->offersParser->PriceLogic->buildStockQueryField() . " = 0)";
						}

						if ($this->config->get('config_rainforest_pass_offers_for_ordered')){
							$sql .= " AND ( p.actual_cost_date = '0000-00-00' ";

							if ($this->config->get('config_rainforest_pass_offers_for_ordered_days')){
								$sql .= " OR DATE_ADD(p.actual_cost_date, INTERVAL " . (int)$this->config->get('config_rainforest_pass_offers_for_ordered_days') . " DAY) < DATE(NOW())";
							}

							$sql .= ")";
						}

						if ($this->config->get('config_rainforest_disable_offers_use_field_ignore_parse')){
							$sql .= " AND NOT (p.ignore_parse = 1 AND (p.ignore_parse_date_to = '0000-00-00' OR DATE(p.ignore_parse_date_to) > DATE(NOW())))";
						}

						if ($this->config->get('config_rainforest_disable_offers_if_has_special')){
							$sql .= " AND p.product_id NOT IN (SELECT product_id FROM product_special ps WHERE ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())))";
						}

						if ($explicitCategories){
							$sql .= " AND p2c.category_id IN (" . implode(',', $explicitCategories) . ")";							
						}

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

				$product['counted_weight'] = $this->rainforestAmazon->offersParser->PriceLogic->recalculateProductWeight($product, 0);

				$result['counted_weight'] 	=  $this->weight->format($product['counted_weight'], $this->config->get('config_weight_class_id'));
				$result['compiled_weight'] 	= $this->rainforestAmazon->offersParser->PriceLogic->recalculateProductWeight($product, 0, true);

				$result['used_default_multiplier'] 				= $defaultMultiplier;
				$result['used_default_costprice_multiplier'] 	= $defaultCostPriceMultiplier;
				$result['used_formula'] 						= $mainFormula;

				$overloadFormulaForProduct = $this->rainforestAmazon->offersParser->PriceLogic->getProductOverPriceFormula($product['product_id'], $product['amazon_best_price']);
				if ($overloadFormulaForProduct){
					$defaultMultiplier 			= $overloadFormulaForProduct['default'];
					$defaultCostPriceMultiplier = $overloadFormulaForProduct['costprice'];
					$mainFormula 				= $overloadFormulaForProduct['formula'];

					$result['formula_overloaded'] 		= true;
					$result['formula_overloaded_from'] 	= 'FROM CATEGORY + BY PRICE';
					$result['used_min'] 				= $this->currency->format($overloadFormulaForProduct['min'], 'EUR', 1);
					$result['used_max'] 				= $this->currency->format($overloadFormulaForProduct['max'], 'EUR', 1);
					$result['used_default_multiplier'] 				= $overloadFormulaForProduct['default'];
					$result['used_default_costprice_multiplier'] 	= $overloadFormulaForProduct['costprice'];
					$result['used_formula'] 						= $overloadFormulaForProduct['formula'];
				} 

				$overloadFormula = $this->rainforestAmazon->offersParser->PriceLogic->checkOverloadFormula($product['amazon_best_price'], $formulaOverloadData);
				if ($formulaOverloadData && $overloadFormula && !$overloadFormulaForProduct){
					$defaultMultiplier 			= $overloadFormula['default'];
					$defaultCostPriceMultiplier = $overloadFormula['costprice'];
					$mainFormula 				= $overloadFormula['formula'];

					$result['formula_overloaded'] 		= true;
					$result['formula_overloaded_from'] 	= 'MAIN SETTINGS BY PRICE';
					$result['used_min'] 				= $this->currency->format($overloadFormula['min'], 'EUR', 1);
					$result['used_max'] 				= $this->currency->format($overloadFormula['max'], 'EUR', 1);
					$result['used_default_multiplier'] 				= $overloadFormula['default'];
					$result['used_default_costprice_multiplier'] 	= $overloadFormula['costprice'];
					$result['used_formula'] 						= $overloadFormula['formula'];
				}

				$params = [
					'PRODUCT_ID' 					=> (int)$product['product_id'],
					'WEIGHT' 						=> (float)$product['counted_weight'],
					'KG_LOGISTIC' 					=> (float)$weightCoefficient,
					'DEFAULT_MULTIPLIER' 			=> (float)$defaultMultiplier,
					'DEFAULT_COSTPRICE_MULTIPLIER' 	=> (float)$defaultCostPriceMultiplier,
					'MAX_MULTIPLIER' 				=> (float)$maxMultiplier,
					'VAT_SRC' 						=> (float)$this->config->get('config_rainforest_formula_vat_src_0'),
					'VAT_DST' 						=> (float)$this->config->get('config_rainforest_formula_vat_dst_0'),
					'TAX' 							=> (float)$this->config->get('config_rainforest_formula_tax_0'),
					'SUPPLIER' 						=> (float)$this->config->get('config_rainforest_formula_supplier_0'),
					'INVOICE' 						=> (float)$this->config->get('config_rainforest_formula_invoice_0')										
				];

				$result['counted_price']  		= $this->rainforestAmazon->offersParser->PriceLogic->mainFormula($product['amazon_best_price'], $params, $mainFormula);
				$result['compiled_formula'] 	= $this->rainforestAmazon->offersParser->PriceLogic->compileFormula($product['amazon_best_price'], $params, $mainFormula);	
				$result['compiled_multiplier'] 	= $this->rainforestAmazon->offersParser->PriceLogic->getMainMultiplier($result['compiled_formula']);										

				$result['amazon_best_price'] 		= $this->currency->format($product['amazon_best_price'], 'EUR', 1);
				$result['counted_price_eur'] 		= $this->currency->format($result['counted_price'], 'EUR', 1);
				$result['counted_price_national'] 	= $this->currency->format($this->currency->convert($result['counted_price'], 'EUR', $this->config->get('config_regional_currency')), $this->config->get('config_regional_currency'), 1);

				$result['counted_costprice']  			= $this->rainforestAmazon->offersParser->PriceLogic->mainCostPriceFormula($product['amazon_best_price'], $params, $mainFormula);
				$result['compiled_costprice_formula'] 	= $this->rainforestAmazon->offersParser->PriceLogic->compileCostPriceFormula($product['amazon_best_price'], $params, $mainFormula);
				$result['counted_сostprice_eur'] 		= $this->currency->format($result['counted_costprice'], 'EUR', 1);
				$result['counted_сostprice_national'] 	= $this->currency->format($this->currency->convert($result['counted_costprice'], 'EUR', $this->config->get('config_regional_currency')), $this->config->get('config_regional_currency'), 1);

				$result['diff']				= $result['counted_price'] - $result['counted_costprice'];			 
				$result['profitability']	= round(($result['diff'] / $result['counted_price']), 3) * 100;

				$result['diff_eur']			= $this->currency->format($result['diff'], 'EUR', 1);
				$result['diff_national']	= $this->currency->format($this->currency->convert($result['diff'], 'EUR', $this->config->get('config_regional_currency')), $this->config->get('config_regional_currency'), 1);

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