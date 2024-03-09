<?php

class MegaFilterCore {	
	
	public static $_specialRoute	= array( 'product/special' );	
	public static $_searchRoute		= array( 'product/search' );	
	public static $_homeRoute		= array( 'common/home' );	
	private static $_cache			= [];
	private $_sql	= '';
	private $_data	= [];
	private $_ctrl	= NULL;
	private $_mfilterUrl	= '';
	private $_parseParams	= [];
	private $_attribs		= [];
	public $_settings		= [];
	private $_options		= [];
	private $_filters		= [];
	private $_categories	= [];
	private $_conditions	= [];
	private static $_hasFilters	= NULL;

	static public function newInstance( & $ctrl, $sql ) {
		return new MegaFilterCore( $ctrl, $sql );
	}
	
	static public function hasFilters() {
		if( self::$_hasFilters === NULL ) {
			self::$_hasFilters = version_compare( VERSION, '1.5.5', '>=' );
		}
		
		return self::$_hasFilters;
	}

	public function getJsonData( array $types, $idx = NULL ) {
		$json		= [];
		$modules	= $this->_ctrl->config->get('mega_filter_module');
		
		foreach( $types as $type ) {
			if( in_array( $type, array( 'manufacturers', 'stock_status', 'rating', 'price' ) ) ) {
					switch( $type ) {
						case 'stock_status'		: $json[$type] = $this->getCountsByStockStatus(); break;
						case 'manufacturers'	: $json[$type] = $this->getCountsByManufacturers(); break;
						case 'rating'			: $json[$type] = $this->getCountsByRating(); break;
						case 'price'			: $json[$type] = $this->getMinMaxPrice(); break;
					}
			} else {
				switch( $type ) {
					case 'attribute'		:
					case 'attributes'		: $json['attributes'] = $this->getCountsByAttributes(); break;
					case 'option'			:
					case 'options'			: $json['options'] = $this->getCountsByOptions(); break;
					case 'filter'			:
					case 'filters'			: if( self::hasFilters() ) { $json['filters'] = $this->getCountsByFilters(); } break;
					case 'categories:tree'	: {
						$json[$type] = $this->getTreeCategories();
						
						break;
					}
				}
			}
		}
		
		return $json;
	}

	private function __construct( & $ctrl, $sql ) {
		$this->_ctrl	= & $ctrl;
		$this->_sql		= $sql;
		$this->_data	= self::_getData( $ctrl );
		
		$this->_settings	= $this->_ctrl->config->get('mega_filter_settings');
		
		$this->_mfilterUrl	= isset( $this->_ctrl->request->get['mfp'] ) ? $this->_ctrl->request->get['mfp'] : '';
		
		if( !empty( $this->_settings['in_stock_default_selected'] ) ) {
			if( false === mb_strpos( $this->_mfilterUrl, 'stock_status', 0, 'utf-8' ) ) {
				$this->_mfilterUrl .= $this->_mfilterUrl ? ',' : '';
				$this->_mfilterUrl .= 'stock_status[' . $this->inStockStatus() . ']';
			}
		}
		
		$this->_parseParams();
	}
	
	public function cacheName() {
		return md5( $this->_mfilterUrl . serialize( $this->_data ) . $this->_ctrl->config->get( 'config_language_id' ) );
	}
	
	public static function _getData( & $ctrl ) {
		$data = [];
		
		if( !empty( $ctrl->request->get['category_id'] ) ) {
			$data['filter_category_id'] = (int) $ctrl->request->get['category_id'];
		} else if( !empty( $ctrl->request->get['path'] ) ) {
			$parts = explode( '_', (string) $ctrl->request->get['path'] );
			$data['filter_category_id'] = (int) array_pop( $parts );
		}
		
		if( !empty( $ctrl->request->get['sub_category'] ) ) {
			$data['filter_sub_category'] = $ctrl->request->get['sub_category'];
		} else if( ! in_array( self::_route( $ctrl ), array( 'common/home' ) ) ) {
			if( self::_showProductsFromSubcategories( $ctrl ) ) {
				$data['filter_sub_category'] = '1';
			}
		}
		
		if( !empty( $ctrl->request->get['filter'] ) ) {
			$data['filter_filter'] = $ctrl->request->get['filter'];
		}
		
		if( !empty( $ctrl->request->get['filter_ocfilter'] ) ) {
			$data['filter_ocfilter'] = $ctrl->request->get['filter_ocfilter'];
		}
		
		if( !empty( $ctrl->request->get['description'] ) ) {
			$data['filter_description'] = $ctrl->request->get['description'];
		}

		if( !empty( $ctrl->request->get['intersection_id'] ) ) {
			$data['filter_category_id_intersect'] = $ctrl->request->get['intersection_id'];
			$data['filter_sub_category_intersect'] = true;
		}

		if( !empty( $ctrl->request->get['filter_current_in_stock'] ) ) {
			$data['filter_current_in_stock'] = $ctrl->request->get['filter_current_in_stock'];
		}

		if( !empty( $ctrl->request->get['filterinstock'] ) ) {
			$data['filterinstock'] = $ctrl->request->get['filterinstock'];
		}

		if( !empty( $ctrl->request->get['filter_in_stock'] ) ) {
			$data['filter_in_stock'] = $ctrl->request->get['filter_in_stock'];
		}

		if( !empty( $ctrl->request->get['filter_not_bad'] ) ) {
			$data['filter_not_bad'] = $ctrl->request->get['filter_not_bad'];
		}

		if( !empty( $ctrl->request->get['new'] ) ) {
			$data['new'] = $ctrl->request->get['new'];
		}
		
		if( !empty( $ctrl->request->get['filter_tag'] ) ) {
			$data['filter_tag'] = $ctrl->request->get['filter_tag'];
		} else if( !empty( $ctrl->request->get['tag'] ) ) {
			$data['filter_tag'] = $ctrl->request->get['tag'];
		} else if( !empty( $ctrl->request->get['search'] ) ) {
			$data['filter_tag'] = $ctrl->request->get['search'];
		}
		
		if( !empty( $ctrl->request->get['manufacturer_id'] ) ) {
			$data['filter_manufacturer_id'] = (int) $ctrl->request->get['manufacturer_id'];
		}

		if( !empty( $ctrl->request->get['actions_id'] ) ) {
			$ctrl->load->model('catalog/actions');
			$action_info = $ctrl->model_catalog_actions->getActions($ctrl->request->get['actions_id']);

			if (!empty($action_info['ao_group'])){
				$data['filter_product_additional_offer'] = $action_info['ao_group'];
			} else {
				$data['filter_actions_id'] = (int)$ctrl->request->get['actions_id'];
			}
		}
		
		if( !empty( $ctrl->request->get['search'] ) ) {
			$data['filter_name'] = (string) $ctrl->request->get['search'];
		}
		
		return $data;
	}
	
	private static function _showProductsFromSubcategories( & $ctrl ) {
		$settings = $ctrl->config->get('mega_filter_settings');
		
		if( empty( $settings['show_products_from_subcategories'] ) ) {
			return false;
		}
		
		if( !empty( $settings['level_products_from_subcategories'] ) ) {
			$level = (int) $settings['level_products_from_subcategories'];
			$path = explode( '_', empty( $ctrl->request->get['path'] ) ? '' : $ctrl->request->get['path'] );
			
			if( $path && count( $path ) < $level ) {
				return false;
			}
		}
		
		return true;
	}

	public function getParseParams() {
		return $this->_parseParams;
	}
	
	public function inStockStatus() {
		return $inStockStatus = empty( $this->_settings['in_stock_status'] ) ? 7 : $this->_settings['in_stock_status'];
	}

	private function _parseParams() {
		$this->_parseParams = [];
		$this->_attribs		= [];
		$this->_options		= [];
		$this->_filters		= [];
		$this->_categories	= [];
		$this->_conditions	= array(
			'out' => array(),
			'in' => array()
		);
		
		if( $this->_mfilterUrl ) {
			preg_match_all( '/([a-z0-9\-_]+)\[([^]]*)\]/', $this->_mfilterUrl, $matches );
			
			if( !empty( $matches[0] ) ) {
				foreach( $matches[0] as $k => $match ) {
					if( empty( $matches[1][$k] ) )						
						continue;
					
					$key	= $matches[1][$k];
					
					if( empty( $matches[2][$k] ) ) {
						if( $key == 'stock_status' && !empty( $this->_settings['in_stock_default_selected'] ) ) {
							$this->_parseParams[$key] = [];
						}
						
						continue;
					}
					
					$value	= explode( ',', $matches[2][$k] );
					
					foreach( $value as $kk => $vv ) {						
						$value[$kk]	= str_replace(array(
							'LA==', 'Ww==', 'XQ=='
						), array(
							',', '[', ']'
						), $vv);
					}

					switch( $key ) {
						case 'search_oc' :
						case 'search' : {
							if( isset( $value[0] ) ) {
								$this->_data['filter_name'] = $value[0];
								$this->_data['filter_mf_name'] = $value[0];
							} else {
								$value = NULL;
							}
							
							break;
						}
						case 'price' : {
							if( isset( $value[0] ) && isset( $value[1] ) ) {
								$this->_conditions['out']['mf_price'] = '( `mf_price` > ' . ( (int) $value[0] - 1 ) . ' AND `mf_price` < ' . ( (int) $value[1] + 1 ) . ')';
							} else {
								$value = NULL;
							}
							
							break;
						}
						case 'manufacturers' : {
							$this->_conditions['in']['manufacturers'] = '`p`.`manufacturer_id` IN(' . implode( ',', $this->_parseArrayToInt( $value ) ) . ')';
							
							break;
						}
						case 'rating' : {
							$sql = [];
							
							foreach( $this->_parseArrayToInt( $value ) as $rating ) {
								switch( $rating ) {
									case '1' :
									case '2' :
									case '3' :
									case '4' : {
										$sql[] = '( `mf_rating` >= ' . $rating . ' AND `mf_rating` < ' . ( $rating + 1 ) . ')'; 
										
										break;
									}
									case '5' : {
										$sql[] = '`mf_rating` = 5';
									}
								}
							}
							
							if( $sql )
								$this->_conditions['out']['mf_rating'] = '(' . implode( ' OR ', $sql ) . ')';
							
							break;
						}
						case 'stock_status' : {
							$value = $this->_parseArrayToInt( $value );
							
							$this->_conditions['in']['stock_status'] = sprintf( 'IF( `p`.`quantity` > 0, %s, `p`.`stock_status_id` ) IN(%s)', 
								$this->inStockStatus(),
								implode( ',', $value )
							);
							
							break;
						}
						case 'path' : {
							if( isset( $value[0] ) ) {
								$this->_ctrl->request->get['path'] = $value[0];
								
								$this->_data['path'] = $value[0];
								$this->_data['filter_category_id'] = explode( '_', $value[0] );
								$this->_data['filter_category_id'] = end( $this->_data['filter_category_id'] );
								
								if( isset( $this->_ctrl->request->get['category_id'] ) ) {									
									$this->_ctrl->request->get['category_id'] = $this->_data['filter_category_id'];
								}						
							}
							
							break;
						}
						default : {
							if( preg_match( '/^c-.+-[0-9]+$/', $key ) ) {
								$this->_categories[$key][] = $this->_parseArrayToInt( $value );
							} else {
								$k = explode( '-', $key );

								if( isset( $k[0] ) && isset( $k[1] ) && 'o' == mb_substr( $k[0], -1, 1, 'utf-8' ) ) {
									$this->_options[trim( $k[0], 'o').'-'.$k[1]][] = implode( ',', $this->_parseArrayToInt( $value ) );
								} else if( isset( $k[0] ) && isset( $k[1] ) && 'f' == mb_substr( $k[0], -1, 1, 'utf-8' ) ) {
									if( self::hasFilters() ) {
										$this->_filters[trim( $k[0], 'f').'-'.$k[1]][] = implode( ',', $this->_parseArrayToInt( $value ) );
									}
								} else {
									if( empty( $this->_settings['attribute_separator'] ) ) {
										$this->_attribs[$key][] = $this->_parseArrayToStr( $value );
									} else {
										$this->_attribs[$key][] = $this->_parseArrayToStr( $value, $this->_settings['attribute_separator'] );
									}
								}
							}
						}
					}
					
					if( $value !== NULL )
						$this->_parseParams[$key] = $value;
				}
			}
		}
		
		return $this->_parseParams;
	}
	
	private function _conditionsOutConvertToFullWhere( $conditionsOut ) {
		foreach( $conditionsOut as $k => $v ) {
			switch( $k ) {
				case 'mf_rating' : {
					$conditionsOut[$k] = str_replace( '`' . $k . '`', $this->_ratingCol(''), $v );
					
					break;
				}
				case 'mf_price' : {
					$conditionsOut[$k] = str_replace( '`' . $k . '`', $this->_mfPriceCol(''), $v );
					
					break;
				}
			}
		}
		
		return $conditionsOut;
	}
	
	private function _mfPriceCol( $alias = 'mf_price' ) {
		if( $this->_ctrl->config->get( 'config_tax' ) ) {
			return '( ( ' . $this->_priceCol( NULL ) . ' * ( 1 + IFNULL(' . $this->_percentTaxCol( NULL ) . ', 0) / 100 ) + IFNULL(' . $this->_fixedTaxCol( NULL ) . ', 0) ) * ' . (float) $this->_ctrl->currency->getValue() . ')' . ( $alias ? ' AS `' . $alias . '`' : '' );
		} else {
			return '(' . $this->_priceCol( NULL ) . '* ' . (float) $this->_ctrl->currency->getValue() . ')' . ( $alias ? ' AS `' . $alias . '`' : '' );
		}
	}
	
	private function _baseColumns() {
		$columns = func_get_args();
		
		if( !empty( $this->_conditions['out']['mf_price'] ) ) {
			$columns['mf_price'] = $this->_mfPriceCol();
		}
		
		if( !empty( $this->_conditions['out']['mf_rating'] ) ) {
			$columns['mf_rating'] = $this->_ratingCol();
		}
		
		return $columns;
	}
	
	private function _findMainWhere( $sql ) {
		$start = 0;
		
		while( false !== ( $pos = mb_strpos( mb_strtolower( $sql, 'utf8' ), 'where', $start, 'utf8' ) ) ) {
			$pre = mb_substr( $sql, 0, $pos, 'utf8' );
			
			if( mb_substr_count( $pre, '(', 'utf8' ) == mb_substr_count( $pre, ')', 'utf8' ) ) {
				$start = $pos;
				
				break;
			} else {
				$start = $pos + 5;
			}
		}
		
		return $pos === false ? 0 : $start;
	}
	
	private function _replaceMainWhere( $sql, $conditions ) {
		if( 0 != ( $whereStart = $this->_findMainWhere( $sql ) ) ) {
			$sql = mb_substr( $sql, 0, $whereStart, 'utf8' ) . $this->_conditionsToSQL( $conditions ) . ' AND ' . mb_substr( $sql, $whereStart + 5, mb_strlen( $sql, 'utf8' ), 'utf8' );
		} else {
			$sql = preg_replace('~(.*)WHERE~ms', '$1' . $this->_conditionsToSQL( $conditions ) . ' AND ', $sql, 1);
		}
		
		return $sql;
	}
	
	private function _beforeMainWhere( $sql, $insert ) {
		if( 0 != ( $whereStart = $this->_findMainWhere( $sql ) ) ) {
			$sql = mb_substr( $sql, 0, $whereStart, 'utf8' ) . ' ' . $insert . ' ' . mb_substr( $sql, $whereStart, mb_strlen( $sql, 'utf8' ), 'utf8' );
		} else {
			$sql = preg_replace('~(.*)WHERE~ms', ' ' . $insert . ' $1', $sql, 1);
		}
		
		return $sql;
	}

	public function getSQL( $fn, $sql = NULL, $template = NULL, array $conditions = array() ) {
		if( $sql === NULL )
			$sql = $this->_sql;
		
		$sql 		= trim( $sql );
		$limit		= '';
		$limitReg	= '/LIMIT\s+[0-9]+(\s*,\s*[0-9]+)?$/i';
		
		if( preg_match( $limitReg, $sql, $matches ) ) {
			if( !empty( $matches[0] ) ) {
				$limit 	= $matches[0];
				$sql	= preg_replace( $limitReg, '', $sql );
			}
		}
		
		if( ! $conditions )
			$conditions = $this->_conditions;
		
		if( ! isset( $conditions['in'] ) )
			$conditions['in'] = [];
		
		if( ! isset( $conditions['out'] ) )
			$conditions['out'] = [];
		
		if( isset( $this->_data['filter_mf_name'] ) && NULL != ( $baseConditions = $this->_baseConditions() ) && isset( $baseConditions['search'] ) )
			$conditions['in']['search'] = $baseConditions['search'];
		
		if( ! $conditions['out'] && ! $conditions['in'] && ! $this->_attribs && ! $this->_options && ! $this->_filters && ! $this->_categories && ! $template && ! $this->_data ) {
			return $sql . ( $limit ? ' ' . $limit : '' );
		}
		
		$columns = implode( ',', $this->_baseColumns() );		
		$conditionsOut = [];
		
		if( $columns )
			$columns = ',' . $columns;
		
		if( NULL != ( $conditionsToSQL = $this->_conditionsToSQL( $conditions['out'], '' ) ) ) {
			$conditionsOut[] = $conditionsToSQL;
		}
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditions['in'], $conditionsOut );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditions['in'], $conditionsOut );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditions['in'], $conditionsOut );
		
		if( self::_showProductsFromSubcategories( $this->_ctrl ) || $this->_categories ) {			
			if( preg_match( '/FROM\s+`?product_to_category`?\s+(AS)?`?p2c`?/ims', $sql ) ) {
				$sql = preg_replace( '/(LEFT|INNER)\s+JOIN\s+`?(product_to_category|category_path)`?\s+(AS)?`?(p2c|cp)`?\s+ON\s*\(?\s*`?(cp|p2c|p)`?\.`?(category_id|product_id)`?\s*=\s*`?(p2c|cp|p)`?\.`?(category_id|product_id)`?\s*\)?/ims', '', $sql );
				$sql = preg_replace( 
					'/FROM\s+`?product_to_category`?\s+(AS)?`?p2c`?/ims', 
					'
						FROM 
							`category_path` AS `cp`
						INNER JOIN
							`product_to_category` AS `p2c`
						ON
							`p2c`.`category_id` = `cp`.`category_id`
					', 
					$sql 
				);
				$sql = preg_replace( '/AND\s+`?p2c`?\.`?category_id`?\s*=/ims', 'AND `cp`.`path_id` =', $sql );
			}			
		}
		
		if( !empty( $this->_data['filter_category_id'] ) && strpos( $sql, 'category_path' ) === false && strpos( $sql, 'product_to_category' ) === false ) {
			$skip = [];
			
			if( strpos( $sql, 'product_to_store' ) !== false ) {
				$skip[] = 'p2s';
			}
			
			if( strpos( $sql, 'product_description' ) !== false ) {
				$skip[] = 'pd';
			}
			
			if( strpos( $sql, 'product_to_category' ) !== false ) {
				$skip[] = 'p2c';
			}
			
			if( strpos( $sql, 'category_path' ) !== false ) {
				$skip[] = 'cp';
			}
			
			$sql = $this->_beforeMainWhere( $sql, $this->_baseJoin( $skip ) );
			$sql = $this->_replaceMainWhere( $sql, $this->_baseConditions() );
		}
		
		if( $conditions['in'] ) {
			$sql = $this->_replaceMainWhere( $sql, $conditions['in'] );
		}
		
		switch( $fn ) {
			case 'getTotalProductSpecials' :
			case 'getTotalProducts' : {
				$sql = preg_replace( '/COUNT\(\s*DISTINCT\s*(`?[^.]+`?)\.`?product_id`?\s*\)\s*(AS\s*)?total/', 'DISTINCT `$1`.`product_id`' . $columns, $sql );
				$sql = sprintf( $template ? $template : 'SELECT COUNT(DISTINCT `product_id`) AS `total` FROM(%s) AS `tmp`', $this->_createSQLByCategories( $sql ) );
							
				
				break;
			}
			case 'getProductSpecials' :
			case 'getProducts' : {
				$cols = '*';
			
				if( false !== mb_strpos( $sql, 'SQL_CALC_FOUND_ROWS', 0, 'utf-8' ) ) {
					$sql = str_replace( 'SQL_CALC_FOUND_ROWS', '', $sql );
					$cols = 'SQL_CALC_FOUND_ROWS *';
				}
				
				$sql = preg_replace( '/^(\s?SELECT\s)(DISTINCT\s)?([^.]+\.product_id)/', '$1$2$3' . $columns, $sql );
				$sql = sprintf( $template ? $template : 'SELECT ' . $cols . ' FROM(%s) AS `tmp`', $this->_createSQLByCategories( $sql ) );
				
				break;
			}
		}
		
		if( $conditionsOut ) {
			$sql .= ' WHERE ' . implode( ' AND ', $conditionsOut );
		}
		
		if( $limit ) {
			$sql .= ' ' . $limit;
		}
		
		return $sql;
	}
	
	private function _optionsToSQL( $join = ' WHERE ', array $options = NULL, & $conditionsIn = NULL, & $conditionsOut = NULL, $field_id = '`product_id`' ) {
		if( $options === NULL )
			$options = $this->_options;
		
		if( false != ( $mFilterPlus = $this->mfilterPlus() ) ) {
			$sql = $mFilterPlus->optionsToSQL( $join, $options, $conditionsIn, $conditionsOut );
			
			if( $conditionsIn !== NULL && $sql ) {
				$conditionsIn[] = $sql;
			}
			
			return $sql;
		}
		
		if( $options ) {
			$sql		= [];
			$quantity	= '';			
		
			if( !empty( $this->_settings['in_stock_default_selected'] ) || ( !empty( $this->_parseParams['stock_status'] ) && in_array( $this->inStockStatus(), $this->_parseParams['stock_status'] ) ) ) {
				$quantity .= ' AND `quantity` > 0';
			}
			
			foreach( $options as $opt ) {
				if( !empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ) {
					$opt	= implode( ',', $opt );
					$opt	= explode( ',', $opt );
					
					foreach( $opt as $opt2 ) {
						$sql[] = sprintf( $field_id . " IN(
							SELECT
								`product_id`
							FROM
								`product_option_value`
							WHERE
								`option_value_id` = %s %s
						)", $opt2, $quantity );
					}
				} else {				
					$sql[] = sprintf( $field_id . " IN( 
						SELECT 
							`product_id` 
						FROM 
							`product_option_value` 
						WHERE 
							`option_value_id` IN(%s) %s
					)", implode( ',', $opt ), $quantity );
				}
			}
			
			$sql = $join . implode( ' AND ', $sql );
		} else {
			$sql = '';
		}
		
		if( $conditionsOut !== NULL && $sql ) {
			$conditionsOut[] = $sql;
		}
		
		return $sql;
	}
	
	private function _categoriesToSQL( $join = ' WHERE ', array $categories = NULL ) {
		if( $categories === NULL )
			$categories = $this->_categories;
		
		if( $categories ) {
			$ids = [];
			$sql = [];
			
			foreach( $categories as $cat1 ) {
				foreach( $cat1 as $cat2 ) {
					$ids[] = end( $cat2 );
				}
			}
			
			$ids = implode( ',', $ids );
			
			$sql[] = '`mf_cp`.`path_id` IN(' . $ids . ')';
			$sql = $join . implode( ' AND ', $sql );
		} else {
			$sql = '';
		}
		
		return $sql;
	}
	
	private function _filtersToSQL( $join = ' WHERE ', array $filters = NULL, & $conditionsIn = NULL, & $conditionsOut = NULL, $field_id = '`product_id`' ) {
		if( ! self::hasFilters() )
			return '';
		
		if( $filters === NULL )
			$filters = $this->_filters;
		
		if( false != ( $mFilterPlus = $this->mfilterPlus() ) ) {
			$sql = $mFilterPlus->filtersToSQL( $join, $filters );
			
			if( $conditionsIn !== NULL && $sql )
				$conditionsIn[] = $sql;
			
			return $sql;
		}
		
		if( $filters ) {
			$sql		= [];
			
			foreach( $filters as $opt ) {
				if( !empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ) {
					$opt	= implode( ',', $opt );
					$opt	= explode( ',', $opt );
					
					foreach( $opt as $opt2 ) {
						$sql[] = sprintf( $field_id . " IN(
							SELECT
								`product_id`
							FROM
								`product_filter`
							WHERE
								`filter_id` = %s
						)", $opt2 );
					}
				} else {				
					$sql[] = sprintf( $field_id . " IN( 
						SELECT 
							`product_id` 
						FROM 
							`product_filter` 
						WHERE 
							`filter_id` IN(%s)
					)", implode( ',', $opt ) );
				}
			}
			
			$sql = $join . implode( ' AND ', $sql );
		} else {
			$sql = '';
		}
		
		if( $conditionsOut !== NULL && $sql ) {
			$conditionsOut[] = $sql;
		}
		
		return $sql;
	}
	
	private function _convertAttribs( $attribs, $field = 'text' ) {
		$tmp		= [];
		
		foreach( $attribs as $attr ) {
			foreach( $attr as $att ) {
				if( !empty( $this->_settings['attribute_separator'] ) && $this->_settings['attribute_separator'] == ',' ) {
					$tmp[] = sprintf( "FIND_IN_SET( REPLACE(REPLACE(REPLACE(%s, ' ', ''), '\r', ''), '\n', ''), REPLACE(REPLACE(REPLACE(`%s`, ' ', ''), '\r', ''), '\n', '') )", $att, $field );
				} else if( ! is_array( $att ) ) {
					$tmp[] = sprintf( "REPLACE(REPLACE(REPLACE(`%s`, ' ', ''), '\r', ''), '\n', '') LIKE REPLACE(REPLACE(REPLACE(%s, ' ', ''), '\r', ''), '\n', '')", $field, $att );
				} else {
					foreach( $att as $at ) {
						$tmp[] = sprintf( "REPLACE(REPLACE(REPLACE(`%s`, ' ', ''), '\r', ''), '\n', '') LIKE REPLACE(REPLACE(REPLACE(%s, ' ', ''), '\r', ''), '\n', '')", $field, $at );
					}
				}
			}
		}
		
		return $tmp;
	}
	
	private function mfilterPlus() {
		if( ! file_exists( DIR_SYSTEM . 'library/mfilter_plus.php' ) )
			return false;
			
		$this->_ctrl->load->library('mfilter_plus');
		
		$mfilterPlus = Mfilter_Plus::getInstance( $this->_ctrl );
		
		return $mfilterPlus->setValues( $this->_attribs, $this->_options, $this->_filters );
	}
	
	private function _attribsToSQL( $join = ' WHERE ', array $attribs = NULL, & $conditionsIn = NULL, & $conditionsOut = NULL, $field_id = '`product_id`' ) {
		if( $attribs === NULL )
			$attribs = $this->_attribs;
		
		if( false != ( $mFilterPlus = $this->mfilterPlus() ) ) {
			$sql = $mFilterPlus->attribsToSQL( $join, $attribs );
			
			if( $conditionsIn !== NULL && $sql )
				$conditionsIn[] = $sql;
			
			return $sql;
		}
		
		if( $attribs ) {
			$sql		= [];
			
			foreach( $attribs as $key => $attr ) {
				list( $attrib_id ) 	= explode( '-', $key );
				
				$sql[]	= sprintf( $field_id . " IN( 
					SELECT 
						`product_id` 
					FROM 
						`product_attribute`
					WHERE 
						( %s ) AND
						`language_id` = " . (int) $this->_ctrl->config->get( 'config_language_id' ) . " AND
						`attribute_id` = " . (int) $attrib_id . " 
				)", implode( 
						!empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ? ' AND ' : ' OR ', 
						$this->_convertAttribs( $attr ) 
					) 
				);
			}
			
			$sql = $join . implode( ' AND ', $sql );
		} else {
			$sql = '';
		}
		
		if( $conditionsOut !== NULL && $sql )
			$conditionsOut[] = $sql;
		
		return $sql;
	}

	private function _ratingCol( $alias = 'mf_rating' ) {
		return "(SELECT ROUND(AVG(`rating`)) AS `total` FROM `review` AS `r1` WHERE `r1`.`product_id` = `p`.`product_id` AND `r1`.`status` = '1' GROUP BY `r1`.`product_id`)" . ( $alias ? " AS `" . $alias . '`' : '' );
	}
	
	private function _customerGroupId() {
		return $this->_ctrl->customer->isLogged() ? (int) $this->_ctrl->customer->getCustomerGroupId() : (int) $this->_ctrl->config->get( 'config_customer_group_id' );
	}
	
	private function _storeId() {
		return (int) $this->_ctrl->config->get( 'config_store_id' );
	}

	private function _discountCol( $alias = 'discount' ) {
		$sql = "SELECT `price` FROM `product_discount` AS `pd2` WHERE `pd2`.`product_id` = `p`.`product_id` AND `pd2`.`customer_group_id` = '" . (int) $this->_customerGroupId() . "' AND `pd2`.`quantity` = '1' AND ((`pd2`.`date_start` = '0000-00-00' OR `pd2`.`date_start` < NOW()) AND (`pd2`.`date_end` = '0000-00-00' OR `pd2`.`date_end` > NOW())) ORDER BY `pd2`.`priority` ASC, `pd2`.`price` ASC LIMIT 1";
		
		return $alias ? sprintf( "(%s) AS %s", $sql, $alias ) : $sql;
	}
	
	private function _storeNationalCol( $alias = 'storeoverload' ) {
		$sql = "SELECT `price` FROM `product_price_to_store` AS `pp2s` WHERE `pp2s`.`product_id` = `p`.`product_id` AND `pp2s`.`store_id` = '" . (int)$this->_storeId() . "' LIMIT 1";
		
		return $alias ? sprintf( "(%s) AS %s", $sql, $alias ) : $sql;
	}

	public function _specialCol( $alias = 'special' ) {
	$sql = "SELECT `price` FROM `product_special` AS `ps` WHERE `ps`.`product_id` = `p`.`product_id` AND (`ps`.`store_id` = '" . (int)$this->_storeId() . "' OR ps.store_id = -1) AND (ISNULL(ps.currency_scode) OR ps.currency_scode = '') AND `ps`.`customer_group_id` = '" . (int) $this->_customerGroupId() . "' AND ((`ps`.`date_start` = '0000-00-00' OR `ps`.`date_start` < NOW()) AND (`ps`.`date_end` = '0000-00-00' OR `ps`.`date_end` > NOW())) ORDER BY `ps`.`priority` ASC, `ps`.`price` ASC LIMIT 1";
		
		return $alias ? sprintf( "(%s) AS %s", $sql, $alias ) : $sql;
	}
	
	private function _taxConditions() {
		$conditions	= [];
		
		$country_id	= $p_country_id = $s_country_id = (int) $this->_ctrl->config->get('config_country_id');
		$zone_id = $p_zone_id = $s_zone_id = (int) $this->_ctrl->config->get('config_zone_id');
		
		if( !empty( $this->_ctrl->session->data['payment_country_id'] ) && !empty( $this->_ctrl->session->data['payment_zone_id'] ) ) {
			$p_country_id = (int) $this->_ctrl->session->data['payment_country_id'];
			$p_zone_id = (int) $this->_ctrl->session->data['payment_zone_id'];
		}
		
		if( !empty( $this->_ctrl->session->data['shipping_country_id'] ) && !empty( $this->_ctrl->session->data['shipping_zone_id'] ) ) {
			$s_country_id = (int) $this->_ctrl->session->data['shipping_country_id'];
			$s_zone_id = (int) $this->_ctrl->session->data['shipping_zone_id'];
		}
		
		$conditions[] = "(
			`tr1`.`based` = 'store' AND 
			`z2gz`.`country_id` = " . $country_id . " AND (
				`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . $zone_id . "'
			)
		)";
		
		$conditions[] = "(
			`tr1`.`based` = 'payment' AND 
			`z2gz`.`country_id` = " . $p_country_id . " AND (
				`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . $p_zone_id . "'
			)
		)";
		
		$conditions[] = "(
			`tr1`.`based` = 'shipping' AND 
			`z2gz`.`country_id` = " . $s_country_id . " AND (
				`z2gz`.`zone_id` = '0' OR `z2gz`.`zone_id` = '" . $s_zone_id . "'
			)
		)";	
		
		return implode( ' OR ', $conditions );
	}
	
	private function _taxCol( $type, $alias ) {
		return "
			(
				SELECT
					SUM(`tr2`.`rate`)
				FROM
					`tax_rule` AS `tr1`
				LEFT JOIN
					`tax_rate` AS `tr2`
				ON
					`tr1`.`tax_rate_id` = `tr2`.`tax_rate_id`
				INNER JOIN
					`tax_rate_to_customer_group` AS `tr2cg`
				ON
					`tr2`.`tax_rate_id` = `tr2cg`.`tax_rate_id`
				LEFT JOIN
					`zone_to_geo_zone` AS `z2gz`
				ON
					`tr2`.`geo_zone_id` = `z2gz`.`geo_zone_id`
				WHERE
					`tr1`.`tax_class_id` = `p`.`tax_class_id` AND
					`tr2`.`type` = '" . $type . "' AND
					( " . $this->_taxConditions() . " ) AND
					`tr2cg`.`customer_group_id` = " . $this->_customerGroupId() . "
			)" . ( $alias ? ' AS ' . $alias : '' ) . "
		";
	}
	
	private function _priceCol( $alias = 'price' ) {
		return "
			IFNULL( (" . $this->_specialCol( NULL ) . "), 
			IFNULL( (" . $this->_storeNationalCol( NULL ) . "), `p`.`price` ) )" . ( $alias ? " AS `" . $alias . '`' : '' ) . "
		";
	}

	private function _fixedTaxCol( $alias = 'f_tax' ) {
		return $this->_taxCol( 'F', $alias );
	}

	private function _percentTaxCol( $alias = 'p_tax' ) {
		return $this->_taxCol( 'P', $alias );
	}
	
	public function _baseConditions( array $conditions = array() ) {
		array_unshift( $conditions, "`p`.`status` = '1'");
		array_unshift( $conditions, "`p`.`stock_status_id` <> '" . $this->_ctrl->config->get('config_not_in_stock_status_id') . "'");
		array_unshift( $conditions, "`p`.`date_available` <= NOW()" );

		if ($this->_ctrl->config->get('config_no_zeroprice')){
			array_unshift($conditions, "(p.price > 0 OR p.price_national > 0)");
		} else {
			$notNullPriceCondition = " (";
			$notNullPriceCondition .= " p.price > 0 OR p.price_national > 0";
			$notNullPriceCondition .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->_ctrl->config->get('config_store_id') . "' LIMIT 1) > 0";
			$notNullPriceCondition .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->_ctrl->config->get('config_store_id') . "' LIMIT 1) > 0";
			$notNullPriceCondition .= ")";
			array_unshift($conditions, $notNullPriceCondition);
		}	

		if ($this->_ctrl->config->get('config_enable_amazon_specific_modes') && $this->_ctrl->config->get('config_rainforest_show_only_filled_products_in_catalog')){
			array_unshift($conditions, "((p.added_from_amazon = 0) OR (p.added_from_amazon = 1 AND p.filled_from_amazon = 1))");			
		}
		
		if( !empty( $this->_data['filter_category_id'] ) ) {
			$__current_category = $this->_ctrl->db->query("SELECT deletenotinstock FROM category WHERE category_id = '" . $this->_data['filter_category_id'] . "'");
			
			if (!empty($__current_category->row['deletenotinstock'])) {
				array_unshift( $conditions, "`p`.`" . $this->_ctrl->config->get('config_warehouse_identifier') . "` > 0" );
			}		
		}

		if( !empty( $this->_data['filter_actions_id'] ) ) {
			$conditions[] = "p.product_id IN (SELECT product_id FROM actions_to_product a2p WHERE actions_id = '" . (int)$this->_data['filter_actions_id'] . "')";			
		}

		if( !empty( $this->_data['filter_product_additional_offer'] ) ) {
			$conditions[] = "p.product_id IN (SELECT product_id FROM product_additional_offer pao WHERE ao_group LIKE '" . $this->_ctrl->db->escape($this->_data['filter_product_additional_offer']) . "')";			
		}

		if (!empty($this->_data['filter_category_id']) && $this->_ctrl->config->get('config_special_category_id') && (int)$this->_data['filter_category_id'] == (int)$this->_ctrl->config->get('config_special_category_id')) {
			$conditions[] = "p.product_id IN (SELECT product_id FROM product_special ps WHERE ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (store_id = '" . (int)$this->_ctrl->config->get('config_store_id') . "' OR store_id = -1)) AND p.stock_status_id <> '" . (int)$this->_ctrl->config->get('config_not_in_stock_status_id') . "' AND p.quantity > 0";
		}			

		if (!empty($this->_data['filter_category_id_intersect'])) {				
			if (is_array($filter_category_id_intersect = explode(':', $this->_data['filter_category_id_intersect']))){
				$filter_category_id_intersect = array_map('intval', $filter_category_id_intersect);
			}


			if (!empty($filter_category_id_intersect)){
				if (!empty($this->_data['filter_sub_category_intersect'])) {
					$conditions[] = "p.product_id IN (SELECT product_id FROM category_path cpi LEFT JOIN product_to_category p2ci ON (cpi.category_id = p2ci.category_id) WHERE cpi.path_id IN (" . implode(',', $filter_category_id_intersect) . "))";
				} else {
					$conditions[] = "p.product_id IN (SELECT product_id FROM product_to_category p2ci WHERE p2ci.category_id IN (" . implode(',', $filter_category_id_intersect) . "))";
				}
			}
		}

		if (!empty($this->_data['filter_current_in_stock'])) {
			$conditions[] = "p." . $this->_ctrl->config->get('config_warehouse_identifier') . " > 0";
		}

		if (!empty($this->_data['filterinstock'])) {
			$conditions[] = "p." . $this->_ctrl->config->get('config_warehouse_identifier') . " > 0";
		}

		if (!empty($this->_data['filter_in_stock'])) {
			$conditions[] = "p." . $this->_ctrl->config->get('config_warehouse_identifier') . " > 0";
		}

		if (!empty($this->_data['filter_not_bad'])) {
			$conditions[] = "p.stock_status_id NOT IN (" . $this->_ctrl->config->get('config_not_in_stock_status_id') . ',' . $this->_ctrl->config->get('config_partly_in_stock_status_id') . ")";
		}

		if (!empty($this->_data['new'])) {
			$conditions[] = "p.new = 1 AND (DATE(p.new_date_to) > '". date('Y-m-d') . "' OR DATE(p.date_added) > '" . date('Y-m-d', strtotime('-45 day')) . "')";
		}
		
		if( !empty( $this->_data['filter_manufacturer_id'] ) ) {
			$conditions[] = '`p`.`manufacturer_id` = ' . (int) $this->_data['filter_manufacturer_id'];
		}
	
		if( !empty( $this->_data['filter_category_id'] ) ) {
			if( !empty( $this->_data['filter_sub_category'] ) || $this->_categories ) {
				$conditions['cat_id'] = "`cp`.`path_id` = '" . (int) $this->_data['filter_category_id'] . "'";
			} else {
				$conditions['cat_id'] = "`p2c`.`category_id` = '" . (int) $this->_data['filter_category_id'] . "'";
			}
			
			if( self::hasFilters() && !empty( $this->_data['filter_filter'] ) && !empty( $this->_data['filter_category_id'] ) ) {
				$filters = explode( ',', $this->_data['filter_filter'] );
				
				$conditions[] = '`pf`.`filter_id` IN(' . implode( ',', $this->_parseArrayToInt( $filters ) ) . ')';
			}
		}
		
		if( !empty( $this->_data['filter_name'] ) || !empty( $this->_data['filter_tag'] ) ) {
			$sql = [];
			
			if( !empty( $this->_data['filter_name'] ) ) {
				$implode	= [];
				$words		= explode( ' ', trim( preg_replace( '/\s\s+/', ' ', $this->_data['filter_name'] ) ) );
				
				foreach( $words as $word ) {
					$implode[] = "`pd`.`name` LIKE '%" . $this->_ctrl->db->escape( $word ) . "%'";
				}
				
				if( $implode ) {
					$sql[] = '(' . implode( ' AND ', $implode ) . ')';
				}
				
				if( !empty( $this->_data['filter_description'] ) ) {
					$sql[] = "`pd`.`description` LIKE '%" . $this->_ctrl->db->escape( $this->_data['filter_name'] ) . "%'";
				}
			}
			
			if( !empty( $this->_data['filter_tag'] ) ) {
				$sql[] = "`pd`.`tag` LIKE '%" . $this->_ctrl->db->escape( $this->_data['filter_tag'] ) . "%'";
			}
			
			if( !empty( $this->_data['filter_name'] ) ) {
				$tmp = array( '`p`.`model`', '`p`.`sku`', '`p`.`upc`', '`p`.`ean`', '`p`.`jan`', '`p`.`isbn`', '`p`.`mpn`' );
				
				foreach( $tmp as $tm ) {
					$sql[] = "LCASE(" . $tm . ") = '" . $this->_ctrl->db->escape( utf8_strtolower( $this->_data['filter_name'] ) ) . "'";
				}
			}
			
			if( $sql ) {
				$conditions['search'] = '(' . implode( ' OR ', $sql ) . ')';
			}
		}		
		
		if( false != ( $mFilterPlus = $this->mfilterPlus() ) ) {
			$mFilterPlus->baseConditions( $conditions );
		}
		
		return $conditions;
	}
	
	public function _baseJoin( array $skip = array() ) {
		$sql = '';
		
		if( ! in_array( 'p2s', $skip ) ) {
			$sql .= "
				INNER JOIN
					`product_to_store` AS `p2s`
				ON
					`p2s`.`product_id` = `p`.`product_id` AND `p2s`.`store_id` = " . (int) $this->_ctrl->config->get( 'config_store_id' ) . "
			";
		}
		
		if( ( !empty( $this->_data['filter_name'] ) || !empty( $this->_data['filter_tag'] ) ) && ! in_array( 'pd', $skip ) ) {
			$sql .= "
				INNER JOIN
					`product_description` AS `pd`
				ON
					`pd`.`product_id` = `p`.`product_id` AND `pd`.`language_id` = " . (int) $this->_ctrl->config->get( 'config_language_id' ) . "
			";
		}
		
		if( !empty( $this->_data['filter_category_id'] ) || $this->_categories ) {
			if( ! in_array( 'p2c', $skip ) ) {
				$sql .= $this->_joinProductToCategory( 'p2c' );
			}
			
			if( ( !empty( $this->_data['filter_sub_category'] ) || $this->_categories ) && ! in_array( 'cp', $skip ) ) {
				$sql .= $this->_joinCategoryPath( 'cp', 'p2c' );
			}
		
			if( !empty( $this->_data['filter_filter'] ) && ! in_array( 'pf', $skip ) ) {
				$sql .= "
					INNER JOIN
						`product_filter` AS `pf`
					ON
						`p2c`.`product_id` = `pf`.`product_id`
				";
			}
		}
		
		if( false != ( $mFilterPlus = $this->mfilterPlus() ) ) {
			$sql .= $mFilterPlus->baseJoin( $skip );
		}
		
		return $sql;
	}
	
	private function _joinProductToCategory( $alias = 'mf_p2c', $on = 'p' ) {
		return "
			INNER JOIN
				`product_to_category` AS `" . $alias . "`
			ON
				`" . $alias . "`.`product_id` = `" . $on . "`.`product_id`
		";
	}
	
	private function _joinCategoryPath( $alias = 'mf_cp', $on = 'mf_p2c' ) {
		return "
			INNER JOIN
				`category_path` AS `" . $alias . "`
			ON
				`" . $alias . "`.`category_id` = `" . $on . "`.`category_id`
		";
	}

	private function _createSQL( array $columns, array $conditions = array(), array $group_by = array( '`p`.`product_id`' ) ) {
		$conditions	= $this->_baseConditions( $conditions );
		$group_by	= $group_by ? ' GROUP BY ' . implode( ',', $group_by ) : '';
		
		$sql = $this->_createSQLByCategories( str_replace( array( '{COLUMNS}', '{CONDITIONS}', '{GROUP_BY}' ), array( implode( ',', $columns ), implode( ' AND ', $conditions ), $group_by ), sprintf("
			SELECT
				{COLUMNS}
			FROM
				`product` AS `p`
			INNER JOIN
				`product_description` AS `pd`
			ON
				`pd`.`product_id` = `p`.`product_id` AND `pd`.`language_id` = " . (int) $this->_ctrl->config->get( 'config_language_id' ) . "
			%s
			WHERE
				{CONDITIONS}
			{GROUP_BY}
		", $this->_baseJoin( array( 'pd' ) ) ) ) );

		return $sql;
	}
	
	private function _createSQLByCategories( $sql ) {
		if( ! $this->_categories )
			return $sql;
		
		$sql = sprintf("SELECT `tmp`.* 	FROM ( %s ) AS `tmp` %s %s %s", $sql, $this->_joinProductToCategory( 'mf_p2c', 'tmp' ), $this->_joinCategoryPath(), $this->_categoriesToSQL() );

		return $sql;
	}
	
	private static function _route( & $ctrl ) {
		if( isset( $ctrl->request->get['mfilterRoute'] ) ){
			return base64_decode( $ctrl->request->get['mfilterRoute'] );
		}
		
		if( isset( $ctrl->request->get['route'] ) )
			return $ctrl->request->get['route'];
		
		return 'common/home';
	}
	
	public function route() {
		return self::_route( $this->_ctrl );
	}

	public function getMinMaxPrice() {
		$sel			= '`price_tmp`';
		$columns		= array( $this->_priceCol( 'price_tmp' ) );
		$baseColumns	= $this->_baseColumns();
		
		if( isset( $baseColumns['mf_rating'] ) )
			$columns[] = $baseColumns['mf_rating'];
		
		if( $this->_ctrl->config->get( 'config_tax' ) ) {
			$sel = '( ' . $sel . ' * ( 1 + IFNULL(`p_tax`, 0) / 100 ) + IFNULL(`f_tax`, 0) )';
			$columns[] = $this->_fixedTaxCol();
			$columns[] = $this->_percentTaxCol();
		}
		
		$conditionsOut	= $this->_conditions['out'];
		$conditionsIn	= $this->_conditions['in'];
		
		if( isset( $conditionsOut['mf_price'] ) )
			unset( $conditionsOut['mf_price'] );
		
		if( $this->_attribs || $this->_options || $this->_filters || $this->_categories )
			$columns[] = '`p`.`product_id`';
		
		$conditions		= [];
		
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
		
		if( isset( $conditionsOut['mf_rating'] ) ) {
			$conditions[] = $conditionsOut['mf_rating'];
			unset( $conditionsOut['mf_rating'] );
		}
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditions[] = '`special` IS NOT NULL';
		}		
		
		$conditions = $conditions ? ' WHERE ' . implode( ' AND ', $conditions ) : '';

		$createdSQL = $this->_createSQL( $columns, $conditionsIn, [] );
		$sql = sprintf( 
			'SELECT MIN(`price`) AS `p_min`, MAX(`price`) AS `p_max` FROM( SELECT ' . $sel . ' AS `price` FROM( %s ) AS `tmp` %s ) AS `tmp` ' . $this->_conditionsToSQL( $conditionsOut ),
			$createdSQL, $conditions
		);		

		$query = $this->_ctrl->db->query( $sql );
		
		if( ! $query->num_rows )
			return array( 'min' => 0, 'max' => 0 );
		
		return array(
			'min'	=> floor( $query->row['p_min'] * $this->_ctrl->currency->getValue() ),
			'max'	=> ceil( $query->row['p_max'] * $this->_ctrl->currency->getValue() )
		);
	}

	public function getTreeCategories() {		
		$root_cat	= empty( $this->_ctrl->request->get['path'] ) ? array( 0 ) : explode( '_', $this->_ctrl->request->get['path'] );
		$root_cat	= (int) end( $root_cat );
		$tree		= [];
		$path		= array( $root_cat => $root_cat );
		
		foreach( $this->_ctrl->db->query( "SELECT category_id FROM `category_path` WHERE `path_id` = " . (int) $root_cat )->rows as $row ) {
			$path[$row['category_id']] = (int) $row['category_id'];
		}
		
		$conditionsIn	= $this->_baseConditions( $this->_conditions['in'] );
		$conditionsOut	= $this->_conditions['out'];
		$columns		= array( 'COUNT(DISTINCT `p`.`product_id`) AS total' );
		
		if( isset( $conditionsIn['cat_id'] ) ) {
			unset( $conditionsIn['cat_id'] );
		}
		
		$conditionsIn[] = '`cp`.`path_id` = `c`.`category_id`';
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditionsOut, '`p`.`product_id`' );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditionsOut, '`p`.`product_id`' );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditionsOut, '`p`.`product_id`' );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$conditionsIn[] = '('.$this->_specialCol('') . ') IS NOT NULL';
		}
		
		$sql = sprintf("
			SELECT
				%s
			FROM
				`product_to_category` AS `p2c`
			INNER JOIN
				`product` AS `p`
			ON
				`p`.`product_id` = `p2c`.`product_id`
			INNER JOIN
				`category_path` AS `cp`
			ON
				`cp`.`category_id` = `p2c`.`category_id`
				%s
				%s
			",
			implode( ',', $columns ),
			$this->_baseJoin(array('p2c','cp')),
			$this->_conditionsToSQL( array_merge( $conditionsIn, $this->_conditionsOutConvertToFullWhere( $conditionsOut ) ) )
		);
		
		$sql		= "
			SELECT
				`c`.`parent_id`,
				`c`.`category_id`,
				`cd`.`name`,
				(
					" . $sql . "
				) AS `aggregate`
			FROM
				`category` AS `c`
			INNER JOIN
				`category_description` AS `cd`
			ON
				`cd`.`category_id` = `c`.`category_id` AND `cd`.`language_id` = '" . (int) $this->_ctrl->config->get('config_language_id') . "'
			INNER JOIN
				`category_to_store` AS `c2s`
			ON
				`c2s`.`category_id` = `c`.`category_id` AND `c2s`.`store_id` = '" . (int) $this->_ctrl->config->get('config_store_id') . "'
			WHERE
				`c`.`status` = '1' AND `c`.`parent_id` = " . $root_cat . "
			GROUP BY
				`c`.`category_id`
			ORDER BY
				`c`.`sort_order` ASC, `cd`.`name` ASC
		";
		
		/*$sql		= "
			SELECT
				`c`.`parent_id`,
				`c`.`category_id`,
				`cd`.`name`,
				(
					SELECT
						COUNT(DISTINCT `p2c`.`product_id`)
					FROM
						`product_to_category` AS `p2c`
					INNER JOIN
						`product` AS `p`
					ON
						`p`.`product_id` = `p2c`.`product_id`
					INNER JOIN
						`product_to_store` AS `p2s`
					ON
						`p2s`.`product_id` = `p2c`.`product_id` AND `p2s`.`store_id` = '" . (int)$this->_ctrl->config->get('config_store_id') . "'
					INNER JOIN
						`category_path` AS `cp`
					ON
						`cp`.`category_id` = `p2c`.`category_id`
					WHERE
						`p`.`status` = '1' AND 						
						`p`.`date_available` <= NOW() AND
						`cp`.`path_id` = `c`.`category_id`
				) AS `aggregate`
			FROM
				`category` AS `c`
			INNER JOIN
				`category_description` AS `cd`
			ON
				`cd`.`category_id` = `c`.`category_id` AND `cd`.`language_id` = '" . (int) $this->_ctrl->config->get('config_language_id') . "'
			WHERE
				`c`.`status` = '1' AND `c`.`parent_id` = " . $root_cat . "
			GROUP BY
				`c`.`category_id`
			ORDER BY
				`c`.`sort_order` ASC, `cd`.`name` ASC
		";*/
		
		foreach( $this->_ctrl->db->query( $sql )->rows as $cat ) {
			//if( ! $cat['aggregate'] ) continue;
			
			$tree[] = array(
				'name' => $cat['name'],
				'id' => $cat['category_id'],
				'pid' => $cat['parent_id'],
				'cnt' => $cat['aggregate']
			);
		}
		
		return $tree;
	}
	
	private function _conditionsToSQL( $conditions, $join = ' WHERE ' ) {
		return $conditions ? $join . implode( ' AND ', $conditions ) : '';
	}

	public function getCountsByStockStatus() {
		$conditionsIn	= $this->_conditions['in'];
		$conditionsOut	= $this->_conditions['out'];
		$columns		= $this->_baseColumns(sprintf(
			'IF( `p`.`quantity` > 0, %s, `p`.`stock_status_id` ) AS `stock_status_id`', $this->inStockStatus()
		));
		
		if( isset( $conditionsIn['stock_status'] ) )
			unset( $conditionsIn['stock_status'] );		
		
		$columns[] = '`p`.`product_id`';
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditionsOut[] = '`special` IS NOT NULL';
		}
		
		$sql = sprintf(
			'SELECT COUNT(DISTINCT `product_id`) AS `total`, `stock_status_id` FROM( %s ) AS `tmp` %s GROUP BY `stock_status_id`',
			$this->_createSQL( $columns, $conditionsIn, array() ), $this->_conditionsToSQL( $conditionsOut )
		);
		
		$query = $this->_ctrl->db->query( $sql );		
		$counts = [];
		
		foreach( $query->rows as $row ) {
			$counts[$row['stock_status_id']] = $row['total'];
		}
		
		return $counts;
	}

	public function getCountsByRating() {
		$conditionsIn	= $this->_conditions['in'];
		$conditionsOut	= $this->_conditions['out'];
		$columns		= $this->_baseColumns();
		
		$columns['mf_rating'] = $this->_ratingCol();
		
		if( isset( $conditionsOut['mf_rating'] ) )
			unset( $conditionsOut['mf_rating'] );
		
		$columns[] = '`p`.`product_id`';
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditionsOut[] = '`special` IS NOT NULL';
		}
		
		$conditionsOut[] = '`mf_rating` IS NOT NULL';
		
		$sql = sprintf(
			'SELECT COUNT(DISTINCT `product_id`) AS `total`, `mf_rating` FROM( %s ) AS `tmp` %s GROUP BY `mf_rating`',
			$this->_createSQL( $columns, $conditionsIn, array() ), $this->_conditionsToSQL( $conditionsOut )
		);
		
		$query = $this->_ctrl->db->query( $sql );
		$counts = [];
		
		foreach( $query->rows as $row ) {
			$counts[(int)$row['mf_rating']] = $row['total'];
		}
		
		return $counts;
	}

	public function getCountsByManufacturers() {
		$conditionsIn	= $this->_conditions['in'];
		$conditionsOut	= $this->_conditions['out'];
		$columns		= $this->_baseColumns( '`p`.`manufacturer_id`' );
		
		if( isset( $conditionsIn['manufacturers'] ) )
			unset( $conditionsIn['manufacturers'] );
		
		//if( $this->_attribs || $this->_options || $this->_filters ) {
			$columns[] = '`p`.`product_id`';
		//}
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditionsOut );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditionsOut[] = '`special` IS NOT NULL';
		}
		
		$conditionsIn[] = '`p`.stock_status_id NOT IN ('. $this->_ctrl->config->get('config_not_in_stock_status_id') .')';
		
		$sql = sprintf(
			'SELECT COUNT(DISTINCT `product_id`) AS `total`, `manufacturer_id` FROM( %s ) AS `tmp` %s GROUP BY `manufacturer_id`',
			$this->_createSQL( $columns, $conditionsIn, array( '`p`.`product_id`' ) ), $this->_conditionsToSQL( $conditionsOut )
		);
		
		$query = $this->_ctrl->db->query( $sql );
		$counts = [];
		
		foreach( $query->rows as $row ) {
			$counts[$row['manufacturer_id']] = $row['total'];
		}
		
		return $counts;
	}
	
	private function _replaceCounts( array $counts1, array $counts2 ) {
		foreach( $counts2 as $k1 => $v1 ) {
			foreach( $v1 as $k2 => $v2 ) {				
				$counts1[$k1][$k2] = $v2;
			}
		}
		
		return $counts1;
	}
	
	// ATRYBUTY ////////////////////////////////////////////////////////////////
	
	private function _getCountsByAttributes( array $conditions, array $conditionsIn ) {
		$counts	= [];
		
		$conditionsOut		= $this->_conditions['out'];
		$columns			= $this->_baseColumns( '`pa`.`attribute_id`', '`p`.`product_id`', '`pa`.`text`' );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditions[] = '`special` IS NOT NULL';
		}

		$sql = $this->_createSQLByCategories(sprintf( "
			SELECT
				%s
			FROM
				`product` AS `p`
			INNER JOIN
				`product_attribute` AS `pa`
			ON
				`pa`.`product_id` = `p`.`product_id` AND `pa`.`language_id` = '" . (int) $this->_ctrl->config->get('config_language_id') . "'
			%s
			WHERE
				%s
		", implode( ',', $columns ), $this->_baseJoin(), implode( ' AND ', $this->_baseConditions( $conditionsIn ) ) ));

		if( $conditionsOut )
			$sql = sprintf( "SELECT * FROM( %s ) AS `tmp` WHERE %s", $sql, implode( ' AND ', $conditionsOut ) );

		$sql = sprintf( "
			SELECT 
				REPLACE(REPLACE(TRIM(`text`), '\r', ''), '\n', '') AS `text`, `attribute_id`, COUNT( DISTINCT `tmp`.`product_id` ) AS `total`
			FROM( %s ) AS `tmp` 
				%s 
			GROUP BY 
				`text`, `attribute_id`
		", $sql, $this->_conditionsToSQL( $conditions ) );
		$cName = __FUNCTION__ . md5( $sql );
		
		if( isset( self::$_cache[$cName] ) )
			return self::$_cache[$cName];
		
		$query = $this->_ctrl->db->query( $sql );
		
		foreach( $query->rows as $row ) {
			if( !empty( $this->_settings['attribute_separator'] ) ) {
				$texts = array_map( 'trim', explode( $this->_settings['attribute_separator'], $row['text'] ) );
				
				foreach( $texts as $txt ) {
					if( ! isset( $counts[$row['attribute_id']][md5($txt)] ) )
						$counts[$row['attribute_id']][md5($txt)] = 0;
					
					$counts[$row['attribute_id']][md5($txt)] += $row['total'];
				}
			} else {
				$counts[$row['attribute_id']][md5($row['text'])] = $row['total'];
			}
		}
		
		self::$_cache[$cName] = $counts;
		
		return $counts;
	}

	public function getCountsByAttributes() {
		$attribs	= array_keys( $this->_attribs );
		$ids		= [];
		$counts		= [];
		
		foreach( $attribs as $attrib ) {
			list( $id ) = explode( '-', $attrib );
			
			$id = (int) $id;
			
			if( $id )
				$ids[] = $id;
		}
		
		$conditions = [];
		$conditionsIn = $this->_conditions['in'];
		
		if( $ids ) {
			$conditions[] = sprintf( '`tmp`.`attribute_id` NOT IN(%s)', implode( ',', $ids ) );
		}
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
		
		$counts = $this->_getCountsByAttributes( $conditions, $conditionsIn );
		
		$clearConditions	= [];
		$conditionsIn		= $this->_conditions['in'];
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		$clearCounts = $conditions ? $this->_getCountsByAttributes( $clearConditions, $conditionsIn ) : array();
		
		foreach( $attribs as $key ) {
			$copy			= $this->_attribs;
			$conditions		= [];
			$conditionsIn	= $this->_conditions['in'];
			
			list( $k ) = explode( '-', $key );
			
			unset( $copy[$key] );
			
			if( $copy ) {
				// atrybuty
				$this->_attribsToSQL( '', $copy, $conditionsIn, $conditions );
				
				// opcje
				$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
				
				// filtry
				$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
				
				$tmp = $this->_getCountsByAttributes( $conditions, $conditionsIn );
				
				if( isset( $tmp[$k] ) ) {
					$counts = $this->_replaceCounts( $counts, array( $k => $tmp[$k] ) );
					//$counts = $counts + array( $k => $tmp[$k] );
				}
			} else {				
				if( isset( $clearCounts[$k] ) ) {
					$counts = $this->_replaceCounts( $counts, array( $k => $clearCounts[$k] ) );
					//$counts = $counts + array( $k => $clearCounts[$k] );
				}
			}
		}
		
		return $counts;
	}

	// OPCJE ///////////////////////////////////////////////////////////////////
	
	private function _getCountsByOptions( array $conditions, array $conditionsIn ) {
		$counts	= [];
		
		$conditionsOut		= $this->_conditions['out'];
		$columns			= $this->_baseColumns( '`pov`.`option_value_id`', '`pov`.`option_id`', '`p`.`product_id`' );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditions[] = '`special` IS NOT NULL';
		}
		
		if( !empty( $this->_settings['in_stock_default_selected'] ) || ( !empty( $this->_parseParams['stock_status'] ) && in_array( $this->inStockStatus(), $this->_parseParams['stock_status'] ) ) ) {
			$conditionsIn[] = '`pov`.`quantity` > 0';
		}

		$sql = $this->_createSQLByCategories(sprintf( "
			SELECT
				%s
			FROM
				`product` AS `p`
			INNER JOIN
				`product_option_value` AS `pov`
			ON
				`pov`.`product_id` = `p`.`product_id`
			%s
			WHERE
				%s
		", implode( ',', $columns ), $this->_baseJoin(), implode( ' AND ', $this->_baseConditions( $conditionsIn ) ) ));

		if( $conditionsOut )
			$sql = sprintf( "SELECT * FROM( %s ) AS `tmp` WHERE %s", $sql, implode( ' AND ', $conditionsOut ) );

		$sql = sprintf( "
			SELECT 
				`option_value_id`, `option_id`, COUNT( DISTINCT `tmp`.`product_id` ) AS `total`
			FROM( %s ) AS `tmp` 
				%s 
			GROUP BY 
				`option_id`, `option_value_id`
		", $sql, $this->_conditionsToSQL( $conditions ) );
		
		$cName = __FUNCTION__ . md5( $sql );
		
		if( isset( self::$_cache[$cName] ) )
			return self::$_cache[$cName];
		
		$query = $this->_ctrl->db->query( $sql );

		foreach( $query->rows as $row ) {
			$counts[$row['option_id']][$row['option_value_id']] = $row['total'];
		}
		
		self::$_cache[$cName] = $counts;
		
		return $counts;
	}
	
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function getCountsByOptions() {
		$options	= array_keys( $this->_options );
		$ids		= [];
		$counts		= [];
		
		foreach( $options as $attrib ) {
			list( $id ) = explode( '-', $attrib );
			
			$id = (int) $id;
			
			if( $id )
				$ids[] = $id;
		}
		
		$conditions = [];
		$conditionsIn = $this->_conditions['in'];
		
		if( $ids ) {
			$conditions[] = sprintf( '`tmp`.`option_value_id` NOT IN(%s)', implode( ',', $ids ) );
		}
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
		
		$counts = $this->_getCountsByOptions( $conditions, $conditionsIn );
		
		$clearConditions	= [];
		$conditionsIn		= $this->_conditions['in'];
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		$clearCounts = $conditions ? $this->_getCountsByOptions( $clearConditions, $conditionsIn ) : array();
		
		foreach( $options as $key ) {
			$copy			= $this->_options;
			$conditions		= [];
			$conditionsIn	= $this->_conditions['in'];
			
			list( $k ) = explode( '-', $key );
			
			unset( $copy[$key] );
			
			if( $copy ) {
				// opcje
				$this->_optionsToSQL( '', $copy, $conditionsIn, $conditions );
				
				// atrybuty
				$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
				
				// filtry
				$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
				
				$tmp = $this->_getCountsByOptions( $conditions, $conditionsIn );
				
				if( isset( $tmp[$k] ) ) {
					$counts = $this->_replaceCounts( $counts, array( $k => $tmp[$k] ) );
					//$counts = $counts + array( $k => $tmp[$k] );
				}
			} else {				
				if( isset( $clearCounts[$k] ) ) {
					$counts = $this->_replaceCounts( $counts, array( $k => $clearCounts[$k] ) );
					//$counts = $counts + array( $k => $clearCounts[$k] );
				}
			}
		}
		
		return $counts;
	}
	
	// FILTRY //////////////////////////////////////////////////////////////////
	
	private function _getCountsByFilters( array $conditions, array $conditionsIn ) {
		$counts	= [];
		
		$conditionsOut		= $this->_conditions['out'];
		$columns			= $this->_baseColumns( '`f`.`filter_group_id`', '`pf`.`filter_id`', '`p`.`product_id`' );
		
		if( in_array( $this->route(), self::$_specialRoute ) ) {
			$columns[] = $this->_specialCol();
			$conditions[] = '`special` IS NOT NULL';
		}

		$sql = $this->_createSQLByCategories(sprintf( "
			SELECT
				%s
			FROM
				`product` AS `p`
			INNER JOIN
				`product_filter` AS `pf`
			ON
				`pf`.`product_id` = `p`.`product_id`
			INNER JOIN
				`filter` AS `f`
			ON
				`f`.`filter_id` = `pf`.`filter_id`
			%s
			WHERE
				%s
		", implode( ',', $columns ), $this->_baseJoin(), implode( ' AND ', $this->_baseConditions( $conditionsIn ) ) ));

		if( $conditionsOut )
			$sql = sprintf( "SELECT * FROM( %s ) AS `tmp` WHERE %s", $sql, implode( ' AND ', $conditionsOut ) );

		$sql = sprintf( "
			SELECT 
				`filter_id`, `filter_group_id`, COUNT( DISTINCT `tmp`.`product_id` ) AS `total`
			FROM( %s ) AS `tmp` 
				%s 
			GROUP BY 
				`filter_group_id`, `filter_id`
		", $sql, $this->_conditionsToSQL( $conditions ) );
		$cName = __FUNCTION__ . md5( $sql );
		
		if( isset( self::$_cache[$cName] ) )
			return self::$_cache[$cName];
		
		$query = $this->_ctrl->db->query( $sql );

		foreach( $query->rows as $row ) {
			$counts[$row['filter_group_id']][$row['filter_id']] = $row['total'];
		}
		
		self::$_cache[$cName] = $counts;
		
		return $counts;
	}

	public function getCountsByFilters() {
		$filters	= array_keys( $this->_filters );
		$ids		= [];
		$counts		= [];
		
		foreach( $filters as $attrib ) {
			list( $id ) = explode( '-', $attrib );
			
			$id = (int) $id;
			
			if( $id )
				$ids[] = $id;
		}
		
		$conditions = [];
		$conditionsIn = $this->_conditions['in'];
		
		if( $ids )
			$conditions[] = sprintf( '`tmp`.`filter_group_id` NOT IN(%s)', implode( ',', $ids ) );
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
		
		// filtry
		$this->_filtersToSQL( '', NULL, $conditionsIn, $conditions );
		
		$counts = $this->_getCountsByFilters( $conditions, $conditionsIn );
		
		$clearConditions	= [];
		$conditionsIn		= $this->_conditions['in'];
		
		// atrybuty
		$this->_attribsToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		// opcje
		$this->_optionsToSQL( '', NULL, $conditionsIn, $clearConditions );
		
		$clearCounts = $conditions ? $this->_getCountsByFilters( $clearConditions, $conditionsIn ) : array();
		
		foreach( $filters as $key ) {
			$copy			= $this->_filters;
			$conditions		= [];
			$conditionsIn	= $this->_conditions['in'];
			
			list( $k ) = explode( '-', $key );
			
			unset( $copy[$key] );
			
			if( $copy ) {
				// filtry
				$this->_filtersToSQL( '', $copy, $conditionsIn, $conditions );
				
				// atrybuty
				$this->_attribsToSQL( '', NULL, $conditionsIn, $conditions );
				
				// opcje
				$this->_optionsToSQL( '', NULL, $conditionsIn, $conditions );
				
				$tmp = $this->_getCountsByFilters( $conditions, $conditionsIn );
				
				if( isset( $tmp[$k] ) ) {
					$counts = $counts + array( $k => $tmp[$k] );
				}
			} else {				
				if( isset( $clearCounts[$k] ) ) {
					$counts = $this->_replaceCounts( $counts, array( $k => $clearCounts[$k] ) );
					//$counts = $counts + array( $k => $clearCounts[$k] );
				}
			}
		}
		
		return $counts;
	}

	private function _parseArrayToInt( $params ) {
		foreach( $params as $k => $v ) {
			if( $v === '' ) {
				unset( $params[$k] );
			} else {
				$params[$k] = (int) $v;
			}
		}
		
		return $params;
	}
	
	/**
	 * Zamień wszystkie elementy tablicy na stringi przygotowane do zapytania SQL
	 * 
	 * @param array $params
	 * @return array 
	 */
    private function _parseArrayToStr($params, $like = false)
    {
        foreach ($params as $k => $v) {
            $v = (string)$v;

            if ($v === '') {
                unset($params[$k]);
            } else {
                if ($like && $like != ',') {
                    $params[$k] = [];
                    $params[$k][] = "'" . $this->_ctrl->db->escape($v) . "'";
                    $params[$k][] = "'%" . $like . $this->_ctrl->db->escape($v) . $like . "%'";
                    $params[$k][] = "'" . $this->_ctrl->db->escape($v) . $like . "%'";
                    $params[$k][] = "'%" . $like . $this->_ctrl->db->escape($v) . "'";
                } else {
                    $params[$k] = "'" . $this->_ctrl->db->escape($v) . "'";
                }
            }
        }

        return $params;
    }
}

class ModelModuleMegaFilter extends Model {	
	
	private static $_tmp_sort_parameters = NULL;

	public function getStockStatuses() {
		$list = [];
		
		foreach( $this->db->query("
			SELECT
				*
			FROM
				`stock_status`
			WHERE
				`language_id` = " . (int) $this->config->get('config_language_id') . "
		")->rows as $row ) {
			$list[] = array(
				'key' => $row['stock_status_id'],
				'value' => $row['stock_status_id'],
				'name' => $row['name']
			);
		}
		
		return $list;
	}
	
	public function getManufacturers() {
		$sql = "
			SELECT 
				`m`.* 
			FROM 
				`manufacturer` AS `m` 
			INNER JOIN 
				`manufacturer_to_store` AS `m2s` 
			ON 
				`m`.`manufacturer_id` = `m2s`.`manufacturer_id` AND `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'
			{join}
			{conditions}
			{group}
			ORDER BY 
				`m`.`sort_order` ASC,
				`m`.`name` ASC
		";
		
		$core		= MegaFilterCore::newInstance( $this, NULL );
		$data		= MegaFilterCore::_getData( $this );
		$join		= '';
		$group		= [];
		$conditions	= $core->_baseConditions();
		$join		= 'INNER JOIN `product` AS `p` ON `p`.`manufacturer_id` = `m`.`manufacturer_id`';
		
		if( in_array( $core->route(), MegaFilterCore::$_specialRoute ) ) {
			$conditions[] = '(' . $core->_specialCol( '' ) . ') IS NOT NULL';
		}
		
		if( !empty( $data['filter_name'] ) || !empty( $data['filter_category_id'] ) || !empty( $data['filter_manufacturer_id'] ) || !empty( $conditions['search'] ) ) {
			$join .= ' ' . $core->_baseJoin();
		}
		
		if( $join ) {
			$group[] = '`m`.`manufacturer_id`';
		}
		
		$group		= $group ? 'GROUP BY ' . implode( ',', $group ) : '';
		$conditions	= $conditions ? 'WHERE ' . implode( ' AND ', $conditions ) : '';
		
		$sql			= str_replace( array( '{join}', '{conditions}', '{group}' ), array( $join, $conditions, $group ), $sql );
		$manufacturers	= [];
		
		foreach( $this->db->query( $sql )->rows as $row ) {
			$manufacturers[] = array(
				'key'	=> $row['manufacturer_id'],
				'value'	=> $row['manufacturer_id'],
				'name'	=> $row['name'],
				'image'	=> empty( $row['image'] ) ? '' : $row['image']
			);
		}
		
		return $manufacturers;
	}
	
	private function stockStatusIsEnabled( $idx ) {		
		$modules	= $this->config->get('mega_filter_module');
		$attribs	= $idx !== NULL && isset( $modules[$idx]['base_attribs'] ) ? $modules[$idx]['base_attribs'] : $this->_settings['attribs'];
		
		return empty( $attribs['stock_status']['enabled'] ) ? false : true;
	}

	public function createFilters( $idx, array $config ) {
		$sql = "
			SELECT
				`fgd`.`name` AS `gname`,
				`f`.`filter_group_id`,
				`f`.`filter_id`,
				`fd`.`name`
			FROM
				`product` AS `p`
			INNER JOIN
				`product_to_store` AS `p2s`
			ON
				`p`.`product_id` = `p2s`.`product_id` AND `p2s`.`store_id` = " . (int) $this->config->get( 'config_store_id' ) . "
			INNER JOIN
				`product_filter` AS `pf`
			ON
				`p`.`product_id` = `pf`.`product_id`
			INNER JOIN
				`filter` AS `f`
			ON
				`pf`.`filter_id` = `f`.`filter_id`
			INNER JOIN
				`filter_description` AS `fd`
			ON
				`pf`.`filter_id` = `fd`.`filter_id` AND `fd`.`language_id` = " . (int) $this->config->get('config_language_id') . "
			INNER JOIN
				`filter_group_description` AS `fgd`
			ON
				`f`.`filter_group_id` = `fgd`.`filter_group_id` AND `fgd`.`language_id` = " . (int) $this->config->get('config_language_id') . "
			{join}
			WHERE
				{conditions}
			GROUP BY
				`f`.`filter_group_id`, `f`.`filter_id`
			ORDER BY
				`f`.`sort_order`, `fd`.`name`
		";
		
		$filter_ids		= [];
		
		if( !empty( $config['based_on_category'] ) ) {
			$category_id	= isset( $this->request->get['path'] ) ? explode( '_', (string) $this->request->get['path'] ) : array();
			$category_id	= end( $category_id );

			if( ! $category_id )
				return array();

			foreach( $this->db->query( 'SELECT `filter_id` FROM `category_filter` WHERE `category_id` = ' . (int) $category_id )->rows as $row ) {
				$filter_ids[] = (int) $row['filter_id'];
			}

			if( ! $filter_ids )
				return array();
		}
		
		$core			= MegaFilterCore::newInstance( $this, NULL );
		$conditions		= $core->_baseConditions();
		$filters		= [];
		$join			= $core->_baseJoin(array('p2s','pf'));
		
		if( $filter_ids ) {
			$conditions[]	= sprintf( '`f`.`filter_id` IN(%s)', implode( ',', $filter_ids ) );
		}
		
		if( in_array( $core->route(), MegaFilterCore::$_specialRoute ) ) {
			$conditions[] = '(' . $core->_specialCol( '' ) . ') IS NOT NULL';
		}
		
		if( ! $this->stockStatusIsEnabled( $idx ) && !empty( $core->_settings['in_stock_default_selected'] ) ) {
			$conditions[] = sprintf( '( `p`.`quantity` > 0 OR `p`.`stock_status_id` = %s )', $core->inStockStatus() );
		}
		
		$sql	= str_replace( array( '{conditions}', '{join}' ), array( implode( ' AND ', $conditions ), $join ), $sql );
		$sort	= [];
		
		foreach( $this->db->query( $sql )->rows as $filter ) {
			if( empty( $config[$filter['filter_group_id']]['enabled'] ) ) continue;
			
			$item = $config[$filter['filter_group_id']];
			
			if( ! isset( $filters['f_'.$filter['filter_group_id']] ) ) {
				$filters['f_'.$filter['filter_group_id']] = array(
					'id'					=> $filter['filter_group_id'],
					'type'					=> $item['type'],
					'base_type'				=> 'filter',
					'sort_order'			=> $item['sort_order'],
					'name'					=> $filter['gname'],
					'seo_name'				=> $filter['filter_group_id'] . 'f-' . clear_uri( $filter['gname'] ),
					'options'				=> array(),
					'collapsed'				=> empty( $item['collapsed'] ) ? false : $item['collapsed'],
					'display_list_of_items'	=> empty( $item['display_list_of_items'] ) ? '' : $item['display_list_of_items'],
					'display_live_filter'	=> empty( $item['display_live_filter'] ) ? '' : $item['display_live_filter']
				);
			}
			
			if( !empty( $item['sort_order_values'] ) )
				$sort['f_'.$filter['filter_group_id']] = $item['sort_order_values'];
			
			$filters['f_'.$filter['filter_group_id']]['options'][$filter['filter_id']] = array(
				'name' => $filter['name'],
				'value' => $filter['filter_id'],
				'key' => $filter['filter_id']
			);
		}
		
		foreach( $sort as $filter_group_id => $type ) {
			$this->_sortOptions( $filters[$filter_group_id]['options'], $type, true );
		}
		
		return $filters;
	}

	public function createOptions( $idx, array $opts ) {
		$sql = "
			SELECT
				`o`.`option_id`,
				`ov`.`option_value_id`,
				`od`.`name` AS `gname`,
				`ov`.`image`,
				`ovd`.`name`
			FROM
				`product` AS `p`
			INNER JOIN
				`product_to_store` AS `p2s`
			ON
				`p`.`product_id` = `p2s`.`product_id` AND `p2s`.`store_id` = " . (int) $this->config->get( 'config_store_id' ) . "
			INNER JOIN
				`product_option_value` AS `pov`
			ON
				`p`.`product_id` = `pov`.`product_id`
			INNER JOIN
				`option_value` AS `ov`
			ON
				`ov`.`option_value_id` = `pov`.`option_value_id`
			INNER JOIN
				`option_value_description` AS `ovd`
			ON
				`ov`.`option_value_id` = `ovd`.`option_value_id` AND `ovd`.`language_id` = " . (int) $this->config->get('config_language_id') . "
			INNER JOIN
				`option` AS `o`
			ON
				`o`.`option_id` = `pov`.`option_id`
			INNER JOIN
				`option_description` AS `od`
			ON
				`od`.`option_id` = `o`.`option_id` AND `od`.`language_id` = " . (int) $this->config->get('config_language_id') . "
			{join}
			WHERE
				{conditions}
			GROUP BY
				`o`.`option_id`, `ov`.`option_value_id`
			ORDER BY
				`ov`.`sort_order`, `ovd`.`name`
		";
		
		$this->load->model('tool/image');
		
		$core			= MegaFilterCore::newInstance( $this, NULL );
		$conditions		= $core->_baseConditions();
		$conditions[]	= "`o`.`type` IN('radio','checkbox','select','image')";
		$options		= [];
		$join			= $core->_baseJoin(array('p2s'));
		
		if( in_array( $core->route(), MegaFilterCore::$_specialRoute ) ) {
			$conditions[] = '(' . $core->_specialCol( '' ) . ') IS NOT NULL';
		}
		
		if( ! $this->stockStatusIsEnabled( $idx ) && !empty( $core->_settings['in_stock_default_selected'] ) ) {
			$conditions[] = '`pov`.`quantity` > 0';
			$conditions[] = sprintf( '( `p`.`quantity` > 0 OR `p`.`stock_status_id` = %s )', $core->inStockStatus() );
		}
		
		$sql	= str_replace( array( '{conditions}', '{join}' ), array( implode( ' AND ', $conditions ), $join ), $sql );
		$sort	= [];
		
		foreach( $this->db->query( $sql )->rows as $option ) {
			if( empty( $opts[$option['option_id']]['enabled'] ) ) continue;
			
			$item = $opts[$option['option_id']];
			
			if( ! isset( $options['o_'.$option['option_id']] ) ) {
				$options['o_'.$option['option_id']] = array(
					'id'					=> $option['option_id'],
					'type'					=> $item['type'],
					'base_type'				=> 'option',
					'sort_order'			=> $item['sort_order'],
					'name'					=> $option['gname'],
					'seo_name'				=> $option['option_id'] . 'o-' . clear_uri( $option['gname'] ),
					'options'				=> array(),
					'collapsed'				=> empty( $item['collapsed'] ) ? false : $item['collapsed'],
					'display_list_of_items'	=> empty( $item['display_list_of_items'] ) ? '' : $item['display_list_of_items'],
					'display_live_filter'	=> empty( $item['display_live_filter'] ) ? '' : $item['display_live_filter']
				);
			}
			
			if( !empty( $item['sort_order_values'] ) )
				$sort['o_'.$option['option_id']] = $item['sort_order_values'];
			
			$value = array(
				'name'	=> $option['name'],
				'value'	=> $option['option_value_id'],
				'key' => $option['option_value_id']
			);
			
			if( in_array( $item['type'], array( 'image', 'image_list_radio', 'image_list_checkbox' ) ) ) {
				list( $w, $h ) = $this->_imageSize();
				
				$value['image'] = empty( $option['image'] ) ? $this->model_tool_image->resize('no_image.jpg', $w, $h) : $this->model_tool_image->resize($option['image'], $w, $h);
			}
			
			$options['o_'.$option['option_id']]['options'][$option['option_value_id']] = $value;
		}
		
		foreach( $sort as $option_id => $type ) {
			$this->_sortOptions( $options[$option_id]['options'], $type, true );
		}
		
		return $options;
	}
	
	private function _imageSize() {
		$settings	= $this->config->get('mega_filter_settings');
		
		$w = empty( $settings['image_size_width'] ) ? 20 : (int) $settings['image_size_width'];
		$h = empty( $settings['image_size_height'] ) ? 20 : (int) $settings['image_size_height'];
		
		return array( $w, $h );
	}

	public function createAttribs( $idx, array $attribs ) {
		$sql = "
			SELECT
				`a`.`attribute_id`,
				REPLACE(REPLACE(TRIM(pa.text), '\r', ''), '\n', '') AS `txt`,
				`ad`.`name`,
				`agd`.`name` AS `gname`,
				`agd`.`attribute_group_id`
			FROM
				`product` AS `p`
			INNER JOIN
				`product_to_store` AS `pts`
			ON
				`p`.`product_id` = `pts`.`product_id` AND `pts`.`store_id` = " . (int) $this->config->get( 'config_store_id' ) . "
			INNER JOIN
				`product_attribute` AS `pa`
			ON
				`p`.`product_id` = `pa`.`product_id` AND `pa`.`language_id` = " . (int)$this->config->get('config_language_id') . "
			INNER JOIN
				`attribute` AS `a`
			ON
				`a`.`attribute_id` = `pa`.`attribute_id`
			INNER JOIN
				`attribute_description` AS `ad`
			ON
				`ad`.`attribute_id` = `a`.`attribute_id` AND `ad`.`language_id` = " . (int) $this->config->get('config_language_id') . "
			INNER JOIN
				`attribute_group` AS `ag`
			ON
				`ag`.`attribute_group_id` = `a`.`attribute_group_id`
			INNER JOIN
				`attribute_group_description` AS `agd`
			ON
				`agd`.`attribute_group_id` = `ag`.`attribute_group_id` AND `agd`.`language_id` = " . (int)$this->config->get('config_language_id') . "
			{join}
			WHERE
				{conditions}				
			GROUP BY
				`txt`, `pa`.`attribute_id`
			HAVING 
				`txt` != ''
			ORDER BY
				`a`.`sort_order`,
				`txt`
		";
		
		$this->load->model('tool/image');
		
		$core		= MegaFilterCore::newInstance( $this, NULL );
		$conditions	= $core->_baseConditions();
		$attributes = [];
		$join		= $core->_baseJoin(array('p2s'));
		$settings	= $this->config->get('mega_filter_settings');
		
		if( in_array( $core->route(), MegaFilterCore::$_specialRoute ) ) {
			$conditions[] = '(' . $core->_specialCol( '' ) . ') IS NOT NULL';
		}
		
		if( ! $this->stockStatusIsEnabled( $idx ) && !empty( $core->_settings['in_stock_default_selected'] ) ) {
			$conditions[] = sprintf( '( `p`.`quantity` > 0 OR `p`.`stock_status_id` = %s )', $core->inStockStatus() );
		}

		if ($this->config->get('config_special_attr_id')){
			$conditions[] = "(`agd`.`attribute_group_id` <> '" . (int)$this->config->get('config_special_attr_id') . "')";
		}
		
		$sql	= str_replace( array( '{conditions}', '{join}' ), array( implode( ' AND ', $conditions ), $join ), $sql );
		$sort	= [];
		
		foreach( $this->db->query( $sql )->rows as $attribute ) {
			if( empty( $attribs[$attribute['attribute_group_id']]['items'][$attribute['attribute_id']]['enabled'] ) ) continue;
			
			$item = $attribs[$attribute['attribute_group_id']]['items'][$attribute['attribute_id']];
			$images = (array) $this->config->get('mega_filter_at_img_' . $attribute['attribute_id'] . '_' . $this->config->get('config_language_id'));
			
			if( ! isset( $attributes['a_'.$attribute['attribute_id']] ) ) {
				$attributes['a_'.$attribute['attribute_id']] = array(
					'id'					=> $attribute['attribute_id'],
					'group_id'				=> $attribute['attribute_group_id'],
					'type'					=> $item['type'],
					'base_type'				=> 'attribute',
					'sort_order'			=> empty( $attribs[$attribute['attribute_group_id']]['sort_order'] ) ? 0 : (int) $attribs[$attribute['attribute_group_id']]['sort_order'],
					'sort_order-sec'		=> $item['sort_order'],
					'name'					=> $attribute['name'],
					'seo_name'				=> $attribute['attribute_id'] . '-' . clear_uri( $attribute['name'] ),
					'options'				=> array(),
					'collapsed'				=> empty( $item['collapsed'] ) ? false : $item['collapsed'],
					'display_list_of_items'	=> empty( $item['display_list_of_items'] ) ? '' : $item['display_list_of_items'],
					'display_live_filter'	=> empty( $item['display_live_filter'] ) ? '' : $item['display_live_filter']
				);
			}
			
			if( !empty( $settings['attribute_separator'] ) ) {
				$attribute['txt'] = array_map( 'trim', explode( $settings['attribute_separator'], $attribute['txt'] ) );
			} else {
				$attribute['txt'] = array( $attribute['txt'] );
			}
			
			$unique	= [];
			foreach( $attribute['txt'] as $text ) {
				$k = md5( $text );
				
				$text = htmlspecialchars( htmlspecialchars_decode( $text ) );
				
				if( isset( $unique[$text] ) ) continue;
				
				$unique[$text]	= true;
				$value			= array(
					'name' => $text,
					'value' => $text,
					'key' => $k
				);
				
				if( in_array( $item['type'], array( 'image', 'image_list_radio', 'image_list_checkbox' ) ) ) {
					list( $w, $h ) = $this->_imageSize();
					
					$value['image'] = isset( $images[$k] ) ? $this->model_tool_image->resize($images[$k], $w, $h) : $this->model_tool_image->resize('no_image.jpg', $w, $h);
				}
				
				$attributes['a_'.$attribute['attribute_id']]['options'][$k] = $value;
			}
			
			if( !empty( $item['sort_order_values'] ) )
				$sort['a_'.$attribute['attribute_id']] = $item['sort_order_values'];
		}
		
		if( !empty( $settings['attribute_separator'] ) ) {
			foreach( $attributes as $attribute_id => $attribute ) {
				if( !empty( $attribute['options'] ) ) {
					$this->_sortOptions( $attribute['options'], empty( $sort[$attribute_id] ) ? '' : $sort[$attribute_id], true, $attribute_id );
				}
				
				$attributes[$attribute_id] = $attribute;
			}
		} else {
			foreach( $attributes as $attribute_id => $attribute ) {
				$this->_sortOptions( $attributes[$attribute_id]['options'], empty( $sort[$attribute_id] ) ? '' : $sort[$attribute_id], true, $attribute_id );
			}
			//foreach( $sort as $attribute_id => $type ) {
			//	$this->_sortOptions( $attributes[$attribute_id]['options'], $type, false, $attribute_id );
			//}
		}
		
		return $attributes;
	}

	public function createCategories( & $core, $idx, $config ) {
		$categories = [];
		$params		= $core->getParseParams();
		
		foreach( $config as $key => $category ) {
			$row = array(
				'sort_order'	=> $category['sort_order'],
				'type'			=> $category['type'],
				'base_type'		=> 'categories',
				'collapsed'		=> empty( $category['collapsed'] ) ? false : $category['collapsed'],
				'show_button'	=> empty( $category['show_button'] ) ? false : true,
				'name'			=> empty( $category['name'][$this->config->get('config_language_id')] ) ? current( $category['name'] ) : $category['name'][$this->config->get('config_language_id')],
			);
			
			$row['seo_name']	= 'c-' . clear_uri( $row['name'] ? $row['name'] : 'cat' ) . '-' . $key;
			$values				= empty( $params[$row['seo_name']] ) ? array() : $params[$row['seo_name']];
			
			switch( $category['type'] ) {
				case 'tree' : {
					$row['categories'] = $core->getTreeCategories();
					
					if( $row['categories'] ) {
						$row['top_path'] = 0;

						if( isset( $this->request->get['path'] ) ) {
							$row['top_path'] = explode( '_', $this->request->get['path'] );
							array_pop( $row['top_path'] );
							$row['top_path'] = $row['top_path'] ? implode( '_', $row['top_path'] ) : 0;
						}

						$row['top_url'] = $row['top_path'] ? $this->url->link( 'product/category', '&path=' . $row['top_path'], 'SSL' ) : '';
					} else {
						$row = NULL;
					}
					
					break;
				}
				case 'related' : {
					$row['levels']		= [];
					$row['labels']		= [];
					$row['auto_levels']	= empty( $category['auto_levels'] ) ? false : true;
					$root_category_id	= empty( $category['root_category_id'] ) ? NULL : $category['root_category_id'];
					$start_id			= 0;
					
					if( !empty( $category['root_category_type'] ) ) {
						switch( $category['root_category_type'] ) {
							case 'by_url' : {
								if( !empty( $this->request->get['path'] ) ) {
									$path = explode( '_', $this->request->get['path'] );
									$start_id = count( $path ) - 1;
									$root_category_id = end( $path );
								}
								
								break;
							}
							case 'top_category' : {
								$root_category_id = 0;
								
								break;
							}
						}
					}
					
					if( !empty( $category['levels'] ) ) {						
						$levels = $category['levels'];
							
						foreach( $category['levels'] as $level ) {
							$row['labels'][] = empty( $level[$this->config->get('config_language_id')] ) ? $this->language->get('text_select') : $level[$this->config->get('config_language_id')];
						}
						
						if( $start_id ) {
							if( empty( $category['auto_levels'] ) ) {
								$levels = array_slice( $levels, $start_id );
							} else {
								$levels = array_slice( $levels, $start_id, count( $values ) ? count( $values ) : 1 );
							}
							
							//$values = array_slice( $values, $start_id );
							$row['labels'] = array_slice( $row['labels'], $start_id );
						}
						
						$level_id = 0;
						foreach( $levels as $level ) {
							$level = array(
								'name'			=> $row['labels'][$level_id],
								'options'		=> array()
							);
							$value = empty( $values[$level_id-1] ) ? $root_category_id : $values[$level_id-1];
							
							if( ! $row['levels'] || $value ) {
								if( $value ) {
									$this->load->model('catalog/category');
									
									foreach( $this->model_catalog_category->getCategories( $value ) as $cat ) {
										$level['options'][$cat['category_id']] = $cat['name'];
									}
									
									//if( isset( $values[$level_id-1] ) ) {
										if( $level_id && ! isset( $row['levels'][$level_id-1]['options'][$value] ) ) {
											if( !empty( $category['auto_levels'] ) ) {
												break;
											} else {
												$level['options'] = [];
											}
										}
									//}
								}
								
								if( ! $level['options'] && ! $value ) {
									$row = NULL;
									break;
								}
							}
							
							$row['levels'][] = $level;
							$level_id++;
						}
						
						if( empty( $row['levels'] ) ) {
							$row = NULL;
						}
					} else {
						$row = NULL;
					}
					
					break;
				}
			}
			
			if( $row != NULL ) {
				$categories[] = $row;
			}
		}
		
		return $categories;
	}
	
	private static function _sortItems( $a, $b ) {
		$as = isset( self::$_tmp_sort_parameters['config'][md5($a['name'])] ) ? self::$_tmp_sort_parameters['config'][md5($a['name'])] : count(self::$_tmp_sort_parameters['config']);
		$bs = isset( self::$_tmp_sort_parameters['config'][md5($b['name'])] ) ? self::$_tmp_sort_parameters['config'][md5($b['name'])] : count(self::$_tmp_sort_parameters['config']);
		
		if( $as > $bs )
			return 1;
		
		if( $as < $bs )
			return -1;
		
		return 0;
	}
	
	private function _sortOptions( & $options, $sort, $a = false, $attribute_id = NULL ) {		
		if( $sort ) {
			list( $type, $order ) = explode( '_', $sort );
		} else {
			$type = $order = '';
		}
		
		if( $attribute_id ) {
			$attribute_id = str_replace(array(
				'a_', 'o_', 'f_'
			), '', $attribute_id);
		}
		
		self::$_tmp_sort_parameters = array(
			'attribute_id'	=> $attribute_id,
			'type'			=> $type,
			'order'			=> $order,
			'a'				=> $a,
			'options'		=> $options,
			'config'		=> $this->config->get('mega_filter_at_sort_' . $attribute_id . '_' . $this->config->get('config_language_id') )
		);
		
		if( ! self::$_tmp_sort_parameters['type'] && ! self::$_tmp_sort_parameters['config'] ) {
			self::$_tmp_sort_parameters = NULL;
			
			return;
		}
		
		if( ! self::$_tmp_sort_parameters['type'] && self::$_tmp_sort_parameters['attribute_id'] !== NULL && self::$_tmp_sort_parameters['config'] ) {
			uasort( $options, array( 'ModelModuleMegaFilter', '_sortItems' ) );
		} else {
			$tmp = [];
			
			foreach( $options as $k => $v ) {
				$tmp['_'.$k] = htmlspecialchars_decode( $v['name'] );
			}
			
			if( $order == 'desc' ) {
				arsort( $tmp, $type == 'string' ? SORT_STRING : SORT_NUMERIC );
			} else {
				asort( $tmp, $type == 'string' ? SORT_STRING : SORT_NUMERIC );
			}
			
			$tmp2 = [];
			
			foreach( $tmp as $k => $v ) {
				$tmp2[trim($k,'_')] = $options[trim($k,'_')];
			}
		
			$options = $tmp2;
		}
			
		self::$_tmp_sort_parameters = NULL;
	}

	public function getAttributes( & $core, $idx, array $base_attribs, array $attribs, array $opts, array $filters, array $categories ) {
		$attributes = 
			$this->createAttribs( $idx, $attribs ) + 
			$this->createOptions( $idx, $opts ) + 
			( MegaFilterCore::hasFilters() ? $this->createFilters( $idx, $filters ) : array() ) + 
			$this->createCategories( $core, $idx, $categories );
		
		/**
		 * Dodaj podstawowe atrybuty
		 */
		if( !empty( $base_attribs ) ) {
			$this->load->model('tool/image');
			
			foreach( $base_attribs as $type => $attribute ) {
				if( empty( $attribute['enabled'] ) ) continue;
				
				if( ( $type == 'search' || $type == 'search_oc' ) && ( isset( $this->request->get['search'] ) || in_array( MegaFilterCore::newInstance( $this, NULL )->route(), MegaFilterCore::$_searchRoute ) ) ) {
					continue;
				}
				
				$attribute['type']					= isset( $attribute['display_as_type'] ) ? $attribute['display_as_type'] : $type;
				$attribute['base_type']				= $type;
				$attribute['id']					= $type;
				$attribute['sort_order']			= (int) $attribute['sort_order'];
				$attribute['name']					= $this->language->get( 'name_' . $type );
				$attribute['seo_name']				= $type;
				$attribute['collapsed']				= empty( $attribute['collapsed'] ) ? false : $attribute['collapsed'];
				$attribute['display_list_of_items']	= empty( $attribute['display_list_of_items'] ) ? '' : $attribute['display_list_of_items'];
				$attribute['display_live_filter']	= empty( $attribute['display_live_filter'] ) ? '' : $attribute['display_live_filter'];
				
				if( $type == 'manufacturers' ) {
					$attribute['options'] = $this->getManufacturers();
					
					if( ! $attribute['options'] ) {
						continue;
					}
					
					if( in_array( $attribute['type'], array( 'image_list_checkbox', 'image_list_radio', 'image' ) ) ) {
						list( $w, $h ) = $this->_imageSize();
						
						foreach( $attribute['options'] as $k => $v ) {
							$attribute['options'][$k]['image'] = empty( $v['image'] ) ? $this->model_tool_image->resize('no_image.jpg', $w, $h) : $this->model_tool_image->resize($v['image'], $w, $h);
						
							if( empty( $attribute['options'][$k]['image'] ) ) {
								$attribute['options'][$k]['image'] = $this->model_tool_image->resize('no_image.jpg', $w, $h);
							}
						}
					}
				} else if( $type == 'stock_status' ) {
					$attribute['options'] = $this->getStockStatuses();
					
					if( ! $attribute['options'] )
						continue;
				}
				
				$attributes[] = $attribute;
			}
		}
		
		/**
		 * Posortuj 
		 */
		usort( $attributes, array( 'ModelModuleMegaFilter', '_sortAttributes' ) );
		
		return $attributes;
	}

	private static function _sortAttributes( $a, $b ) {
		/*$sa = isset( $a['sort_order-sec'] ) ? $a['sort_order-sec'] : $a['sort_order'];
		$sb = isset( $b['sort_order-sec'] ) ? $b['sort_order-sec'] : $b['sort_order'];
		
		if( $sa < $sb )
			return -1;
		
		if( $sa > $sb )
			return 1;
		
		return 0;*/
		
		
		if( ! isset( $a['sort_order-sec'] ) || ! isset( $b['sort_order-sec'] ) ) {
			if( (int) $a['sort_order'] < (int) $b['sort_order'] )
				return -1;
		
			if( (int) $a['sort_order'] > (int) $b['sort_order'] )
				return 1;
			
			return 0;
		}
		
		if( (int) $a['sort_order-sec'] < (int) $b['sort_order-sec'] )
			return -1;
		
		if( (int) $a['sort_order-sec'] > (int) $b['sort_order-sec'] )
			return 1;
		
		if( (int) $a['sort_order'] < (int) $b['sort_order'] )
			return -1;
		
		if( (int) $a['sort_order'] > (int) $b['sort_order'] )
			return 1;
		
		return 0;
	}

}

