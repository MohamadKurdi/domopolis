<div class="popup-city__title"><? echo $text_retranslate_41; ?></div>

<div class="popup-city__search">
	<svg width="16" height="16" viewBox="0 0 16 16" fill="none"
	xmlns="http://www.w3.org/2000/svg">
		<path d="M1 15L4.38333 11.6167M2.55556 7.22222C2.55556 10.6587 5.34134 13.4444 8.77778 13.4444C12.2142 13.4444 15 10.6587 15 7.22222C15 3.78578 12.2142 1 8.77778 1C5.34134 1 2.55556 3.78578 2.55556 7.22222Z"
		stroke="#FFC34F" stroke-width="2" stroke-linecap="round"
		stroke-linejoin="round"></path>
	</svg>
	<input id="change-city-customer-city-name-selection-popup-search" type="text" name="search-city" autocomplete="off" placeholder="<? echo $text_retranslate_42; ?>">
</div>
<ul class="popup-city__city-list">
	<?php foreach ($cities as $city) { ?>
		<li class="popup-city__city-list-city-item" data-id="<?php echo $city['id']; ?>" data-city="<?php echo $city['text'];?>"><span><?php echo $city['text'];?></span></li>
	<?php } ?>
</ul>
<div class="popup-city__close popup-city__close-list"></div>

<style>
	.navigator-geo-init > span{border-bottom:1px dashed grey; cursor:pointer;}
</style>
<script>
	$(".popup-city__close-list").on('click', function () {
		$('#change-city-customer-city-name-ask-popup').hide();
		$('#change-city-customer-city-name-selection-popup').hide();
		return false;
	});
	
	function setBadResult(elem, text){
		elem.html(text).addClass('text-danger');
	}
	
	function initBrowserNavigationTrigger(){
		if (navigator.geolocation) {
			
			$('.navigator-geo-init').remove();
			$('input#change-city-customer-city-name-selection-popup-search').after('<div class="navigator-geo-init" data-for="change-city-customer-city-name-selection-popup-search"><span><i class="fas fa-map-marker-alt"></i> <?php echo $text_retranslate_43?> </span></div>');
			
			$('.navigator-geo-init').on('click', function(){
				
				var selector = $(this).attr('data-for');
				var elem = $(this);
				
				
				elem.before('<span class="text-loading"><i class="fas fa-spinner fa-spin"></i></span>');
				
				navigator.geolocation.getCurrentPosition(
				(position) => {					
					jQuery.ajax({
						url: "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude + "&localityLanguage=ru",
						dataType: "json",
						type: "GET",
						beforeSend: function(){
							elem.parent().children('.text-loading').remove();
							elem.before('<span class="text-loading"><i class="fas fa-spinner fa-spin"></i></span>');
						},
						complete: function(){
							elem.parent().children('.text-loading').remove();
						},
						success: function(json){
							if (!json || json.countryCode == null || json.countryCode != '<?php echo $country_code; ?>'){
								elem.parent().children('.text-loading').remove();
								
								if (json.countryName != null){
									setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_44; ?> (' + json.countryName + ')');
									} else {
									setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>');
								}
								} else {								
								
								jQuery.ajax({
									url: "index.php?route=kp/checkout/getCurrentCity",
									dataType: "json",
									data: {
										city: json.city, 
										locality: json.locality
									},
									beforeSend: function(){										
									},
									success: function(json2){
										if (json2.id && json2.id != null && json2.found){
											setCustomerCityAjax(json2.city, json2.id);
											} else {
											setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>');
										/*	
											if (json.city){
												setCustomerCityWithNoIDAjax(json.city);
												} else if (json.locality){
												setCustomerCityWithNoIDAjax(json.locality);
												} else {
												
												setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>');
											}
										*/
										}
									},
									error: function(){
										setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>');									
									},
									complete: function(){
										elem.parent().children('.text-loading').remove();	
									}
								});
								
								
							}
						},
						error: function(){							
							setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>');
						}					
					});
					
					
					
				},
				(err) => { setBadResult(elem, '<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_45; ?>'); },
				{
					enableHighAccuracy: true,
					timeout: 5000,
					maximumAge: 0
				});			
				});
			
		}
	}
	
	$(document).ready(function(){
		initBrowserNavigationTrigger();           
	});		
	
	$('li.popup-city__city-list-city-item').on('click', function(e){
		setCustomerCityAjax($(this).attr('data-city'), $(this).attr('data-id'));
	});
	
	
	jQuery.ui.autocomplete.prototype._resizeMenu = function () {
		var ul = this.menu.element;
		ul.outerWidth(this.element.outerWidth());
	}
	
	$('input#change-city-customer-city-name-selection-popup-search').autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: "index.php?route=kp/checkout/suggestCities&json=true&limit=10",
				dataType: "json",
				data: {
					query: request.term,                               
				},
				success: function( data ) {
					response( jQuery.map( data, function( item ) {					
						return {
							label: item.text,
							value: item.text,
							id:	   item.id,
							city:  item.text,
							city_short: item.text_short
						}
					}));
				}
			});
		},
		select: function( event, ui ) {
			if (ui.item.city_short != null && ui.item.id != null){
				console.log('City is selected by user, remembering: ' + ui.item.city_short + ', ' + ui.item.id);
				setCustomerCityAjax(ui.item.city_short, ui.item.id);
			}
		},
	  	classes: {
		    "ui-autocomplete": "countryWrap"
		},
		minLength: 2,
		delay: 300
	}); 
	//# sourceURL=citySuggest.js
</script>