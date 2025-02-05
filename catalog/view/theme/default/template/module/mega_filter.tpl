<style>
	@media screen and (max-width: 560px){
		#mfilter-free-container-0{
			overflow: initial !important;
		}
	}

</style>
<?php if( ! empty( $settings['javascript'] ) ) { ?>
	<script type="text/javascript">
		<?php echo htmlspecialchars_decode( $settings['javascript'] ); ?>
	</script>
<?php } ?>

<?php

	$button_template = '<div class="mfilter-button mfilter-button-%s">%s</div>';
	$button_temp = '<a href="#" class="%s">%s</a>';
	$buttons = array( 'top' => array(), 'bottom' => array() );

	if( ! empty( $settings['show_reset_button'] ) ) {
		$buttons['bottom'][] = sprintf( $button_temp, 'mfilter-button-reset', $text_reset_all );
	}

	if( ! empty( $settings['show_top_reset_button'] ) ) {
		$buttons['top'][] = sprintf( $button_temp, 'mfilter-button-reset', $text_reset_all );
	}

	if( ! empty( $settings['refresh_results'] ) && $settings['refresh_results'] == 'using_button' && ! empty( $settings['place_button'] ) ) {
		$place_button = explode( '_', $settings['place_button'] );

		if( in_array( 'top', $place_button ) ) {
			$buttons['top'][] = sprintf( $button_temp, 'button', $text_button_apply );
		}

		if( in_array( 'bottom', $place_button ) ) {
			$buttons['bottom'][] = sprintf( $button_temp, 'button', $text_button_apply );
		}
	}

	foreach( $buttons as $bKey => $bVal ) {
		$buttons[$bKey] = $bVal ? sprintf( $button_template, $bKey, implode( '', $bVal ) ) : '';
	}


?>

<?php if( ! empty( $hide_container ) ) { ?>
	<div style="display: none;">
<?php } ?>

<div class="box mfilter-box mfilter-<?php echo $_position; ?><?php echo ! empty( $hide_container ) ? ' mfilter-hide-container' : '' ?><?php echo $this->data['_displayOptionsAs'] == 'modern_horizontal' ? ' mfilter-hide' : ''; ?>" id="mfilter-box-<?php echo (int) $_idx; ?>">
	<?php if( $heading_title ) { ?>
		<div class="box-heading"><?php echo $heading_title; ?></div>
	<?php } ?>
	<div class="box-content mfilter-content<?php echo empty( $settings['calculate_number_of_products'] ) || empty( $settings['show_number_of_products'] ) ? ' mfilter-hide-counter' : ''; ?>">
		<?php echo $buttons['top']; ?>
		<ul class="accordion">
			<?php foreach( $filters as $kfilter => $filter ) { ?>
				<?php

					$base_type = empty( $filter['base_type'] ) ? $filter['type'] : $filter['base_type'];
					$base_id = empty( $filter['id'] ) ? '' : $filter['id'];

				?>
				<li
					data-type="<?php echo $filter['type']; ?>"
					data-base-type="<?php echo $base_type; ?>"
					data-id="<?php echo $base_id; ?>"
					data-seo-name="<?php echo $filter['seo_name']; ?>"
					data-display-options-as="<?php echo $_displayOptionsAs ? $_displayOptionsAs : ''; ?>"
					<?php if( isset( $filter['auto_levels'] ) ) { ?>
						data-auto-levels="<?php echo $filter['auto_levels']; ?>"
					<?php } ?>
					data-display-live-filter="<?php
						$display_live_filter = ! empty( $settings['display_live_filter']['enabled'] ) ? '1' : '-1';

						if( ! empty( $filter['display_live_filter'] ) ) {
							$display_live_filter = $filter['display_live_filter'];
						}

						if( $display_live_filter == '1' && ! empty( $settings['display_live_filter']['items'] ) ) {
							echo $settings['display_live_filter']['items'];
						} else {
							echo 0;
						}
					?>"
					data-display-list-of-items="<?php echo empty( $filter['display_list_of_items'] ) ? '' : $filter['display_list_of_items']; ?>"
					class="accordion__item mfilter-filter-item mfilter-<?php echo $filter['type']; ?> mfilter-<?php echo $base_type; ?><?php echo $base_type == 'attribute' ? ' mfilter-attributes' : ( $base_type == 'option' ? ' mfilter-options' : ( $base_type == 'filter' ? ' mfilter-filters' : '' ) ); ?>
					<?php

							if( $filter['collapsed'] ) {
								if( $filter['collapsed'] == '1' || ( $is_mobile && $filter['collapsed'] == 'mobile' ) || ( ! $is_mobile && $filter['collapsed'] == 'pc' ) ) {

									if( !empty( $params[$filter['seo_name']] ) ) {
										echo ' opened';
									}
								}
							}

						?>
						<?php
							if ($base_id == 'manufacturers') {
								echo ' opened';
							}

							if ($base_id == 'search') {
								echo ' opened';
							}

							if ($base_id == 'price') {
								echo ' opened';
							}

						?>

						"
					>

					<?php if( $filter['collapsed'] != 'hide_header' && ! empty( $filter['name'] ) ) { ?>
						<div class="accordion__title mfilter-headings">
							<div class="mfilter-heading-content">
								<div class="mfilter-heading-text"><span><?php echo $filter['name']; ?></span></div>

							</div>
						</div>
					<?php } ?>

					<div class="mfilter-content-opts accordion__content"<?php

							if( ( $filter['collapsed'] == '1' || ( $is_mobile && $filter['collapsed'] == 'mobile' ) || ( ! $is_mobile && $filter['collapsed'] == 'pc' ) ) && $filter['collapsed'] != 'hide_header' && $_position != 'content_top' ) {
								if( empty( $params[$filter['seo_name']] ) ) {
									echo ' style="display:none"';
								}
							}

						?>>
						<div class="mfilter-opts-container">
							<div class="mfilter-content-wrapper">
								<div class="mfilter-options">
									<?php if( $base_type == 'categories' ) { ?>
										<div class="mfilter-option mfilter-category mfilter-category-<?php echo $filter['type']; ?>">
											<?php if( $filter['type'] == 'related' ) { ?>
												<ul data-labels="<?php echo str_replace( '"', '&quot;', implode( '#|#', $filter['labels'] ) ); ?>">
													<?php $values = empty( $params[$filter['seo_name']] ) ? array() : $params[$filter['seo_name']]; ?>
													<?php foreach( $filter['levels'] as $level_id => $level ) { ?>
														<?php $value = empty( $values[$level_id] ) ? '' : $values[$level_id]; ?>
														<li>
															<select data-type="category-<?php echo $filter['type']; ?>">
																<option value=""><?php echo $level['name']; ?></option>
																<?php foreach( $level['options'] as $optKey => $optVal ) { ?>
																	<option value="<?php echo $optKey; ?>"<?php echo $value == $optKey ? ' selected="selected"' : ''; ?>><?php echo $optVal; ?></option>
																<?php } ?>
															</select>
														</li>
													<?php } ?>
												</ul>
											<?php } else if( $filter['type'] == 'tree' ) { ?>
												<input type="hidden" name="path" value="" />
												<ul class="side-catalog mfilter-tb" data-top-url="<?php echo $filter['top_url']; ?>" data-top-path="<?php echo $filter['top_path']; ?>">
													<?php foreach( $filter['categories'] as $category ) { ?>
														<?php if( ! empty( $settings['hide_inactive_values'] ) && empty( $category['cnt'] ) ) continue; ?>
														<li class="mfilter-tb-as-tr">
															<div class="mfilter-tb-as-td">
																<a href="#" data-id="<?php echo $category['id']; ?>" data-parent-id="<?php echo $category['pid']; ?>"><?php echo $category['name']; ?></a>
																<div class="mfilter-tb-as-td mfilter-col-count"><i class="mfilter-counter"><?php echo $category['cnt']; ?></i></div>
															</div>

														</li>
													<?php } ?>
												</ul>
											<?php } ?>

											<?php if( ! empty( $filter['show_button'] ) ) { ?>
												<div class="mfilter-button">
													<a href="#" class="button"><?php echo $text_button_apply; ?></a>
												</div>
											<?php } ?>
										</div>
									<?php } else if( $filter['type'] == 'search' || $filter['type'] == 'search_oc' ) { ?>
										<div class="mfilter-option mfilter-search<?php echo ! empty( $filter['button'] ) ? ' mfilter-search-button' : ''; ?>">
											<input
												id="mfilter-opts-search"
												class="field"
												type="text"
												placeholder="<?php echo $text_search_placeholder; ?>..."
												data-refresh-delay="<?php echo isset( $filter['refresh_delay'] ) ? $filter['refresh_delay'] : '-1'; ?>"
												value="<?php echo isset( $params['search'][0] ) ? $params['search'][0] : ( isset( $params['search_oc'][0] ) ? $params['search_oc'][0] : '' ); ?>"
												/>

											<?php if( ! empty( $filter['button'] ) ) { ?>
												<i
													id="mfilter-opts-search_button"
													type="submit"
													></i>
											<?php } ?>
										</div>
									<?php } else if( $filter['type'] == 'price' ) { ?>
										<div class="mfilter-option mfilter-price">
											<div class="mfilter-price-inputs">
												<div>
													<div class="price-filter__labels"><?php echo $text_price_placeholder_from; ?></div>
												<?php echo $this->currency->getSymbolLeft(); ?>
												<input
													id="mfilter-opts-price-min"
													type="text"
													value="<?php echo isset( $params['price'][0] ) ? $params['price'][0] : ''; ?>"
													/>
													<!-- <?php echo $this->currency->getSymbolRight(); ?> -->
												</div>
												<div>
													<div class="price-filter__labels"><?php echo $text_price_placeholder_to; ?></div>
												<?php echo $this->currency->getSymbolLeft(); ?>
												<input
													id="mfilter-opts-price-max"
													type="text"
													value="<?php echo isset( $params['price'][1] ) ? $params['price'][1] : ''; ?>"
													/>
													<!-- <?php echo $this->currency->getSymbolRight(); ?> -->
													</div>
											</div>
											<div class="mfilter-price-slider">
												<div id="mfilter-price-slider"></div>
											</div>
										</div>
									<?php } else if( $filter['type'] == 'slider' ) { ?>
										<div class="mfilter-option mfilter-slider">
											<div class="mfilter-slider-inputs">
												<input
													class="mfilter-opts-slider-min"
													type="text"
													value=""
													readonly="readonly"
													/>
													-
												<input
													class="mfilter-opts-slider-max"
													type="text"
													value=""
													readonly="readonly"
													/>
											</div>
											<div class="mfilter-slider-container">
												<div class="mfilter-slider-slider"></div>
											</div>
											<div class="mfilter-slider-data" style="display: none;"><?php

													$tmp = array();

													foreach( $filter['options'] as $k => $v ) {
														$tmp[] = $k;
													}

													echo base64_encode(json_encode(array(
														'keys' => $tmp,
														'options' => $filter['options']
													)));
												?></div>
										</div>
									<?php } else if( $filter['type'] == 'rating' ) { ?>
										<div class="mfilter-tb">
											<?php for( $i = 5; $i >= 1; $i-- ) { ?>
												<div class="checkbox mfilter-option mfilter-tb-as-tr ">
													<div class="mfilter-tb-as-td mfilter-col-input">
														<input
															id="mfilter-opts-rating-<?php echo (int) $_idx; ?>-<?php echo $i; ?>"
															type="checkbox"
															<?php echo ! empty( $params['rating'] ) && in_array( $i, $params['rating'] ) ? ' checked="checked"' : ''; ?>
															value="<?php echo $i; ?>" />
													</div>
													<label class="mfilter-tb-as-td" for="mfilter-opts-rating-<?php echo (int) $_idx; ?>-<?php echo $i; ?>">
														<?php echo $i; ?> <img src="catalog/view/theme/default/image/stars-<?php echo $i; ?>.png" alt="" />
														<div class="mfilter-tb-as-td mfilter-col-count"><i class="mfilter-counter">0</i></div>
													</label>

												</div>
											<?php } ?>
										</div>
									<?php } else if( in_array( $filter['type'], array( 'stock_status', 'manufacturers', 'checkbox', 'radio', 'image_list_radio', 'image_list_checkbox' ) ) ) { ?>
										<?php

											$_tmp_type = $filter['type'];

											if( in_array( $filter['type'], array( 'stock_status', 'manufacturers' ) ) ) {
												$_tmp_type = 'checkbox';
											}

										?>
										<div class="mfilter-options-container">
											<div class="mfilter-tb" >
											<?php $options_tmp = array(); ?>
											<?php foreach( $filter['options'] as $option_id => $option ) { if( $option['name'] === '' || isset( $options_tmp[$option['key']] ) ) continue; $options_tmp[$option['key']] = true; ?>
												<?php echo $_position == 'content_top' ? '<div class="mfilter-tb">' : ''; ?>
												<div class="checkbox mfilter-option mfilter-tb-as-tr">
													<div class="mfilter-tb-as-td mfilter-col-input">
														<input
															id="mfilter-opts-attribs-<?php echo (int) $_idx; ?>-<?php echo $base_id; ?>-<?php echo $option['key']; ?>"
															name="<?php echo $filter['seo_name']; ?>"
															type="<?php echo $_tmp_type == 'image_list_checkbox' ? 'checkbox' : ( $_tmp_type == 'image_list_radio' ? 'radio' : $_tmp_type ); ?>"
															<?php echo ! empty( $params[$filter['seo_name']] ) && in_array( $option['value'], $params[$filter['seo_name']] ) ? ' checked="checked"' : ''; ?>
															value="<?php echo str_replace( '"', '&quot;', $option['value'] ); ?>" />
													</div>
													<label class="mfilter-tb-as-td" for="mfilter-opts-attribs-<?php echo (int) $_idx; ?>-<?php echo $base_id; ?>-<?php echo $option['key']; ?>">
														<?php if( in_array( $_tmp_type, array( 'image_list_radio', 'image_list_checkbox' ) ) ) { ?>
															<img src="<?php echo $option['image']; ?>" /> <?php echo $option['name']; ?>
														<?php } else { ?>
															<?php echo $option['name']; ?>
														<?php } ?>
														<div class="mfilter-tb-as-td mfilter-col-count"><i class="mfilter-counter">0</i></div>
													</label>

												</div>
												<?php echo $_position == 'content_top' ? '</div>' : ''; ?>
											<?php } ?>
											</div>
										</div>
									<?php } else if( $filter['type'] == 'select' ) { ?>
										<div class="mfilter-tb">
											<div class="mfilter-option mfilter-select">
												<select>
													<option value="">---</option>
													<?php foreach( $filter['options'] as $option_id => $option ) { ?>
														<option
															id="mfilter-opts-select-<?php echo (int) $_idx; ?>-<?php echo $base_id; ?>-<?php echo $option['key']; ?>"
															value="<?php echo str_replace( '"', '&quot;', $option['value'] ); ?>"
															data-name="<?php echo $option['name']; ?>"
															<?php echo ! empty( $params[$filter['seo_name']] ) && in_array( $option['value'], $params[$filter['seo_name']] ) ? ' selected="selected"' : ''; ?>
															><?php echo $option['name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } else if( $filter['type'] == 'image' ) { ?>
										<div class="mfilter-tb">
											<ul>
												<?php foreach( $filter['options'] as $option_id => $option ) { ?>
													<li class="mfilter-option mfilter-image">
														<input
															id="mfilter-opts-attribs-<?php echo (int) $_idx; ?>-<?php echo $base_id; ?>-<?php echo $option['key']; ?>"
															name="<?php echo $filter['seo_name']; ?>"
															type="checkbox"
															style="display:none"
															<?php echo ! empty( $params[$filter['seo_name']] ) && in_array( $option['value'], $params[$filter['seo_name']] ) ? ' checked="checked"' : ''; ?>
															value="<?php echo str_replace( '"', '&quot;', $option['value'] ); ?>" />
														<label for="mfilter-opts-attribs-<?php echo (int) $_idx; ?>-<?php echo $base_id; ?>-<?php echo $option['key']; ?>" title="<?php echo $option['name']; ?>"><img src="<?php echo $option['image']; ?>" /></label>
													</li>
												<?php } ?>
											</ul>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
		<?php echo $buttons['bottom']; ?>
	</div>
</div>

<?php if( ! empty( $hide_container ) ) { ?>
	</div>
<?php } ?>


	<style>
		.mfilter-manufacturers .mfilter-tb,
		.checkbox-btn-group {
			font-size: 0;
			margin: 0 -5px -10px;
		}

		.mfilter-manufacturers .mfilter-tb .mfilter-option {
			display: inline-block;
			vertical-align: top;
			margin: 0 5px 10px 0px;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option input[type="checkbox"],
		.checkbox-btn input[type="checkbox"] {
			opacity: 0;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option label,
		.checkbox-btn label {
			position: relative;
			display: inline-block;
			padding: 6px 12px;
			font-size: 17px;
			background: #f7f4f4;
			cursor: pointer;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-col-input{
			display: none;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option label > div {
			display: inline-block;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option label:before,
		.mfilter-manufacturers .mfilter-tb .mfilter-option label:after{
			display: none;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option  label i,
		.checkbox-btn label i {
			font-size: 13px;
			font-style: normal;
			color: #51a881;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option.mfilter-disabled label{
			cursor: default;
		}
		.mfilter-manufacturers .mfilter-tb .mfilter-option.mfilter-input-active label,
		.checkbox-btn input[type="checkbox"]:checked+label {
			background: #e6e3e3;
		}
		#column-left .mfilter-slider-slider .ui-slider-handle,
		#column-left #mfilter-price-slider .ui-slider-handle{
			z-index: 1;
		}
	</style>
<script type="text/javascript">
	MegaFilterLang.text_display = '<?php echo $text_display; ?>';
	MegaFilterLang.text_list	= '<?php echo $text_list; ?>';
	MegaFilterLang.text_grid	= '<?php echo $text_grid; ?>';
	MegaFilterLang.text_select	= '<?php echo $text_select; ?>';

	jQuery().ready(function(){
		jQuery('#mfilter-box-<?php echo (int) $_idx; ?>').each(function(){
			var _t = jQuery(this).addClass('init'),
				_p = { };

			<?php foreach( $this->request->get as $k => $v ) { if( is_array( $v ) || ! in_array( $k, array( 'path', 'category_id', 'actions_id', 'manufacturer_id', 'intersection_id', 'filter', 'search', 'sub_category', 'description', 'filter_tag' ) ) ) continue; ?>
				_p['<?php echo $k; ?>'] = '<?php echo addslashes( $v ); ?>';
			<?php } ?>

			MegaFilterINSTANCES.push((new MegaFilter()).init( _t, {
				'idx'					: '<?php echo (int) $_idx; ?>',
				'route'					: '<?php echo $_route; ?>',
				'routeProduct'			: '<?php echo $_routeProduct; ?>',
				'routeHome'				: '<?php echo $_routeHome; ?>',
				'routeInformation'		: '<?php echo $_routeInformation; ?>',
				'contentSelector'		: '<?php echo empty( $settings['content_selector'] ) ? '#mfilter-content-container' : addslashes( htmlspecialchars_decode( $settings['content_selector'] ) ); ?>',
				'refreshResults'		: '<?php echo empty( $settings["refresh_results"] ) ? "immediately" : $settings["refresh_results"]; ?>',
				'refreshDelay'			: <?php echo empty( $settings["refresh_delay"] ) ? 1000 : (int) $settings["refresh_delay"]; ?>,
				'autoScroll'			: <?php echo empty( $settings["auto_scroll_to_results"] ) ? 'false' : 'true'; ?>,
				'ajaxInfoUrl'			: '<?php echo $ajaxInfoUrl; ?>',
				'ajaxResultsUrl'		: '<?php echo $ajaxResultsUrl; ?>',
				'ajaxCategoryUrl'		: '<?php echo $ajaxCategoryUrl; ?>',
				'priceMin'				: <?php echo (string) $price['min']; ?>,
				'priceMax'				: <?php echo (string) $price['max']; ?>,
				'mijoshop'				: <?php echo $mijo_shop ? 'true' : 'false'; ?>,
				'showNumberOfProducts'	: <?php echo empty( $settings['show_number_of_products'] ) ? 'false' : 'true'; ?>,
				'calculateNumberOfProducts' : <?php echo empty( $settings['calculate_number_of_products'] ) ? 'false' : 'true'; ?>,
				'addPixelsFromTop'		: <?php echo empty( $settings['add_pixels_from_top'] ) ? 0 : (int) $settings['add_pixels_from_top']; ?>,
				'displayListOfItems'	: {
					'type'				: '<?php echo empty( $settings['display_list_of_items']['type'] ) ? 'scroll' : $settings['display_list_of_items']['type']; ?>',
					'limit_of_items'	: <?php echo empty( $settings['display_list_of_items']['limit_of_items'] ) ? 4 : (int) $settings['display_list_of_items']['limit_of_items']; ?>,
					'maxHeight'			: <?php echo empty( $settings['display_list_of_items']['max_height'] ) ? 155 : (int) $settings['display_list_of_items']['max_height']; ?>,
					'textMore'			: '<?php echo $text_show_more; ?>',
					'textLess'			: '<?php echo $text_show_less; ?>'
				},
				'smp'					: {
					'isInstalled'			: <?php echo empty( $smp['isInstalled'] ) ? 'false' : 'true'; ?>,
					'disableConvertUrls'	: <?php echo empty( $smp['disableConvertUrls'] ) ? 'false' : 'true'; ?>
				},
				'params'				: _p,
				'inStockDefaultSelected': <?php echo empty( $settings['in_stock_default_selected'] ) ? 'false' : 'true'; ?>,
				'inStockStatus'			: '<?php echo empty( $settings['in_stock_status'] ) ? 7 : $settings['in_stock_status']; ?>',
				'showLoaderOverResults'	: <?php echo empty( $settings['show_loader_over_results'] ) ? 'false' : 'true'; ?>,
				'showLoaderOverFilter'	: <?php echo empty( $settings['show_loader_over_filter'] ) ? 'false' : 'true'; ?>,
				'hideInactiveValues'	: <?php echo empty( $settings['hide_inactive_values'] ) ? 'false' : 'true'; ?>,
				'manualInit'			: <?php echo empty( $settings['manual_init'] ) ? 'false' : 'true'; ?>,
				'displayOptionsAs'		: '<?php echo $_displayOptionsAs ? $_displayOptionsAs : ''; ?>',
				'text'					: {
					'loading'		: '<?php echo $text_loading; ?>',
					'go_to_top'		: '<?php echo $text_go_to_top; ?>',
					'init_filter'	: '<?php echo $text_init_filter; ?>',
					'initializing'	: '<?php echo $text_initializing; ?>'
				}
			}));
		});

		  $('.accordion__item').each(function (){
		    if($(this).hasClass("opened")){
		    //  $(this).find(".accordion__content").slideToggle(200);
		    }
		  });
	});
</script>
