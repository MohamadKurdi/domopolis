
<div class="checkout-steps__item">
    <div class="checkout-steps__head">
		<div class="checkout-steps__count">1</div>
		<div class="checkout-steps__info">
			<div class="checkout-steps__title"><?php echo $customer_data['firstname']; ?> <?php echo $customer_data['lastname']; ?>, <span><?php echo $customer_data['telephone']; ?></span></div>
			<div class="checkout-steps__edit">
				<a class="link" onclick="$('#simplecheckout_button_prev').trigger('click');">
					<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M11 15L7 11M7 11L11 7M7 11H15M1 11C1 16.5228 5.47715 21 11 21C16.5228 21 21 16.5228 21 11C21 5.47715 16.5228 1 11 1C5.47715 1 1 5.47715 1 11Z" stroke="#e25c1d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				<?php echo $text_retranslate_38; ?></a>
			</div>
		</div>
	</div>
</div>



<style>
	.row-shipping_address_country_id{display:none!important;}
	.row-shipping_address_novaposhta_city_guid{display:none!important;}
	.row-shipping_address_cdek_city_guid{display:none!important;}
	.row-shipping_address_cdek_city_fias{display:none!important;}
	.row-shipping_address_do_not_call{display:none!important;}
    .optWrapper > ul.options > li.opt.disabled {display:none!important;}
    
    .loadingspan {float: right; margin-right: 6px; margin-top: -20px;position: relative;z-index: 2;color: red;}
    .row-shipping_address_city .field-input{
    	height: 60px;
    }
    .row-shipping_address_city .field-input::before{
		display: flex;
	    content: "\f107";
	    font-family: "Font Awesome 5 Free";
	    font-weight: 900;
	    position: absolute;
	    right: 0;
	    top: 0;
	    bottom: 0;
	    color: #eadd4a;
	    height: 60px;
	    align-items: center;
	}
    .changeSity{
    	margin-top: 4px;
    	content: 'Обрати інше місто';
	    color: #e0695f;
	    text-decoration: underline;
	    cursor: pointer;
    }
</style>
<!--checkout-steps__item-->
<div class="checkout-steps__item">
    <div class="checkout-steps__head">
        <div class="checkout-steps__count active">2</div>
        <div class="checkout-steps__info">
            <div class="checkout-steps__title"><?php echo $text_retranslate_39; ?></div>
            <div class="checkout-steps__edit">
                <!-- <a class="link" onclick="$('#simplecheckout_button_prev').trigger('click');">Изменить данные</a> -->
                <a id="edit_cart_popap"  class="link" onclick="openCart();"> <?php echo $text_retranslate_1; ?> </a>
			</div>
		</div>
	</div>
    <!--order-cart-->
    <? /* <div class="checkout-steps__select">
        <div class="simplecheckout-block" id="simplecheckout_cart_second_step_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $has_error ? 'data-error="true"' : '' ?>>
        <?php if ($display_header) { ?>
        <h3 class="title"><?php echo $text_cart ?></h3>
        
        <?php } ?>
        
        <?php foreach ($products as $product) { ?>
        <div class="order-cart__item">
        <div class="product__photo">
        <?php if ($product['thumb']) { ?>
        <a href="<?php echo $product['href']; ?>" target="_blank"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
        <?php } ?>
        </div>
        <div class="product__title">
        <a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
        </div>
        <div class="order-cart__item-right">
        <div class="product__amount"><?php echo $product['quantity']; ?> шт</div>
        
        <div class="product__price-new value "><?php echo $product['price']; ?></div>
        </div>
        </div>
        <?php } ?>
        </div>
	</div> */ ?>
    <!--/order-cart-->
    
    <div class="simplecheckout-block checkout-steps__select" id="simplecheckout_shipping_address" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
        
        <?php if ($display_header) { ?>
            <div class="checkout-heading checkout-steps__select-label"><?php echo $text_checkout_shipping_address ?></div>
		<?php } ?>
        <div class="simplecheckout-block-content checkout-steps__select-right">
            <?php foreach ($rows as $row) { ?>
                <?php echo $row ?>
			<?php } ?>
            <?php foreach ($hidden_rows as $row) { ?>
                <?php echo $row ?>
			<?php } ?>
		</div>
	</div>
    
    
	<script>
		function initCityTriggers(){
			
			if ($('input#shipping_address_city').length == 0){
				console.log('pass init City');
				return;
			}
			
			<?php if ($this->config->get('config_country_id') == 220) { ?>
				<?php $cityGuidFieldID = 'shipping_address_novaposhta_city_guid'; ?>
				<?php } else { ?>
				<?php $cityGuidFieldID = 'shipping_address_cdek_city_guid'; ?>
			<?php } ?>
			
			$('input#shipping_address_city').on('click keyup keydown contextmenu', function(){
				$('select#shipping_address_city_select').select2('destroy');
				$('select#shipping_address_city_select').remove();
				$('input#shipping_address_city').prop( "disabled", true );
				
				$('input#shipping_address_city').after('<select id="shipping_address_city_select" lang="<?php if ($this->config->get('config_language_id') == 'uk') { ?>ua<? } else {?>ru<?php } ?>"></select>');
				
				$('select#shipping_address_city_select').select2({
					language: <?php if ($this->config->get('config_language_id') == 'uk') { ?>'ua'<? } else {?>'ru'<?php } ?>,
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
						$('input#shipping_address_city').val(data.text);
						$('input#<?php echo $cityGuidFieldID; ?>').val(data.id);
						$('select#shipping_address_city_select').select2('destroy');
						$('select#shipping_address_city_select').remove();
						$('input#shipping_address_city').prop( "disabled", false ).trigger('change');
						return data.text;
					}
				}).select2('open');
				
				$('select#shipping_address_city_select').on('select2:close', function (e) {
					$('input#shipping_address_city').prop( "disabled", false );
				});
				
			});
			
			console.log('init City Triggers');
			var inputSity = $('.row-shipping_address_city');
			inputSity.append("<p class=\"changeSity\">Обрати інше місто</p>");
			$('.changeSity').on('click', function(){
				$('#shipping_address_city').trigger('click')
			})
		}			        
	</script>
	
	<script>
		function guessCityIDWhenNotSet(){
			var city = $('input#shipping_address_city').val();
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