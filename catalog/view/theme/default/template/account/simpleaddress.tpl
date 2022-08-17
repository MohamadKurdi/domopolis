<?php if (!$ajax && !$popup && !$as_module) { ?>
    <?php $simple_page = 'simpleaddress'; include $simple_header; ?>
    <style type="text/css">
        .row-address_country_id{
        display: none;
        }
        .field-input{
        margin-bottom: 15px;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 100%;
            height: 60px;
            padding: 10px;
            font-weight: 400;
            color: #000;
            border: .1rem solid #e5e5e5;
            border-radius: .4rem;
            margin-bottom: 10px;
            font-size: 15px;
        }
        .select2-dropdown {
            z-index: 10;
            background-color: #fff;
            box-shadow: 0 0 1rem rgb(0 0 0 / 15%);
            border-radius: .8rem;
            padding: 10px;
        }
        .select2-container--default .select2-selection--single {
            height: 60px;
            border: 0;  
            border-bottom: 1px solid #bdbdbd !important;
            border-radius: 0 !important;
            margin-top: -73px;
            background: transparent;
            position: relative;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 0;
            bottom: 0;
            margin: auto;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #51a881;
            color: white;
        }
        .select2-results__option--selectable {
            font-weight: 500;
            font-size: 15px;
        }
    </style>
    <style>
        .row-shipping_courier_city_dadata_unrestricted_value{display:none!important;}
        .row-shipping_courier_city_dadata_beltway_hit{display:none!important;}
        .row-shipping_courier_city_dadata_beltway_distance{display:none!important;}
        .row-shipping_courier_city_dadata_geolocation{display:none!important;}
        .row-shipping_courier_city_dadata_postalcode{display:none!important;}
        
        .row-shipping_address_country_id{display:none!important;}
        .row-shipping_address_novaposhta_city_guid{display:none!important;}
        .row-shipping_address_cdek_city_guid{display:none!important;}
        .row-shipping_address_cdek_city_fias{display:none!important;}
        .optWrapper > ul.options > li.opt.disabled {display:none!important;}
        
        .loadingspan {float: right; margin-right: 6px; margin-top: -20px;position: relative;z-index: 2;color: red;}
    </style>
    
    <div class="side_bar">
        <?php echo $column_left; ?>
    </div>
    <div class="account_content">
        <div class="simple-content">
        <?php } ?>
        <?php if (!$ajax || ($ajax && $popup)) { ?>
            <script type="text/javascript">
                var startSimpleInterval = window.setInterval(function(){
                    if (typeof jQuery !== 'undefined' && typeof Simplepage === "function" && jQuery.isReady) {
                        window.clearInterval(startSimpleInterval);
                        
                        var simplepage = new Simplepage({
                            additionalParams: "<?php echo $additional_params ?>",
                            additionalPath: "<?php echo $additional_path ?>",
                            mainUrl: "<?php echo $action; ?>",
                            mainContainer: "#simplepage_form",
                            useAutocomplete: <?php echo $use_autocomplete ? 1 : 0 ?>,
                            scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                            notificationDefault: <?php echo $notification_default ? 1 : 0 ?>,
                            notificationToasts: <?php echo $notification_toasts ? 1 : 0 ?>,
                            notificationCheckForm: <?php echo $notification_check_form ? 1 : 0 ?>,
                            notificationCheckFormText: "<?php echo $notification_check_form_text ?>",
                            languageCode: "<?php echo $language_code ?>",
                            javascriptCallback: function() {<?php echo $javascript_callback ?>}
                        });
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.options.positionClass = "<?php echo $notification_position ? $notification_position : 'toast-top-right' ?>";
                            toastr.options.timeOut = "<?php echo $notification_timeout ? $notification_timeout : '5000' ?>";
                            toastr.options.progressBar = true;
                            toastr.options.preventDuplicates = true; 
                        }
                        
                        simplepage.init();
                    }
                },0);
            </script>
        <?php } ?>
        <form  action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">    
            <div class="simpleregister " id="simpleaddress">
                <div class="simpleregister-block-content">
                    <?php foreach ($rows as $row) { ?>
                        <?php echo $row ?>
                    <?php } ?>
                    <?php foreach ($hidden_rows as $row) { ?>
                        <?php echo $row ?>
                    <?php } ?>
                    
                    <div class="field-group row-shipping_address_novaposhta_city_guid">
                        <div class="field-label">NP CITY GUID</div>
                        <div class="field-input">
                            <input class="field" type="text" name="shipping_address[novaposhta_city_guid]" id="shipping_address_novaposhta_city_guid" value="" placeholder="" data-onchange="reloadAll">
                        </div>
                    </div>
                    <div class="field-group row-shipping_address_cdek_city_guid">
                        <div class="field-label">CDEK CITY GUID</div>
                        <div class="field-input">
                            <input class="field" type="text" name="shipping_address[cdek_city_guid]" id="shipping_address_cdek_city_guid" value="" placeholder="" data-onchange="reloadAll">
                        </div>
                    </div>
                    
                    
                </div>
                <div class="simpleregister-button-block buttons">
                    <div class="simpleregister-button-right">
                        <a class="button btn-primary button_oc btn btn-acaunt" data-onclick="submit" id="simpleregister_button_confirm"><span><?php echo $button_continue; ?></span></a>
                    </div>
                </div>
            </div>
            <?php if ($redirect) { ?>
                <input type="hidden" id="simple_redirect_url" value="<?php echo $redirect ?>">
            <?php } ?>
        </form>
        <?php if (!$ajax && !$popup && !$as_module) { ?>
        </div>
    </div>
    
    <script>
		function initCityTriggers(){
			
			if ($('input#address_city').length == 0){
				console.log('pass init City');
				return;
            }
			
			<?php if ($this->config->get('config_country_id') == 220) { ?>
				<?php $cityGuidFieldID = 'shipping_address_novaposhta_city_guid'; ?>
				<?php } else { ?>
				<?php $cityGuidFieldID = 'shipping_address_cdek_city_guid'; ?>
            <?php } ?>
			
			$('input#address_city').on('click keyup keydown contextmenu', function(){
				$('select#shipping_address_city_select').select2('destroy');
				$('select#shipping_address_city_select').remove();
				$('input#address_city').prop( "disabled", true );
				
				$('input#address_city').after('<select id="shipping_address_city_select" lang="<?php if ($this->config->get('config_language_id') == 6) { ?>ua<? } else {?>ru<?php } ?>"></select>');
				
				$('select#shipping_address_city_select').select2({
					language: <?php if ($this->config->get('config_language_id') == 6) { ?>'ua'<? } else {?>'ru'<?php } ?>,
					ajax: {
						url: 'index.php?route=kp/checkout/suggestCities',
						dataType: 'json',                        
						data: function (params) {
							var query = {
								query: params.term,
                            }                        
							return query;
                        }
                    },
					templateSelection: function (data, container) {                            
						$('input#address_city').val(data.text);
						$('input#<?php echo $cityGuidFieldID; ?>').val(data.id);
						$('select#shipping_address_city_select').select2('destroy');
						$('select#shipping_address_city_select').remove();
						$('input#address_city').prop( "disabled", false ).trigger('change');
						return data.text;
                    }
                }).select2('open');
				
				$('select#shipping_address_city_select').on('select2:close', function (e) {
					$('input#address_city').prop( "disabled", false );
                });
				
            });
			
			console.log('init City Triggers');
			
        }			                      
    </script>
	
	<script>
		function guessCityIDWhenNotSet(){
			var city = $('input#address_city').val();
			var city_guid = $('input#<?php echo $cityGuidFieldID; ?>').val();
			
			if (city.length > 0 && city_guid.length == 0){
				console.log('trying to guess City ID');
				
				jQuery.ajax({
					url: "index.php?route=kp/checkout/guessCitiesIDWhenNOTSET",
					dataType: "json",
					data: {
						query: city                           
                    },
					error:{
                        
                    },
					success: function( data ) {
						if (data.city.length > 0){
							$('input#<?php echo $cityGuidFieldID; ?>').val(data.city);
							$('input#<?php echo $cityGuidFieldID; ?>').prop('value', data.city);					
                        }
                    }
                });
				
				
				} else {
				console.log('all ok, pass guessing City ID');
            }	
        }
    </script>
	
	<script>
		$(document).ready(function(){
			initCityTriggers();
			guessCityIDWhenNotSet();
        });
    </script>			
    
    <?php include $simple_footer ?>
<?php } ?>