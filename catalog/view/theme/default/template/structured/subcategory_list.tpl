<style type="text/css">
	@media (min-width: 1000px){
		.categories-photo__row__collection{
			overflow: hidden;
		}
		.categories-photo__row__collection.open{
			height:auto !important;				
		}
	}
	@media screen and (max-width: 560px) {
		.categories-photo__label span {
		    display: none;
		}
		#categories-photo{
			display: flex;
			overflow-x: auto;
			width: 100%;
			grid-gap: inherit;
			scrollbar-color: transparent !important;
			scrollbar-width: transparent !important;
		}

		#categories-photo::-webkit-scrollbar-thumb {
		    background-color: transparent !important;
		    scrollbar-color: transparent !important;
		}
		#categories-photo::-webkit-scrollbar-track {

		}
		#categories-photo .categories-photo__item{
			width: 95px;
			display: inline-block;
			min-width: 80px;
			height: 120px;
			margin-right: 6px;
		}
		#categories-photo .categories-photo__item:last-child{
			margin-right: 0;
		}
		#categories-photo .categories-photo__item a{
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.subcategory_list .categories-photo__label {
	    	padding: 0;
			width: 100% !important;
			overflow: hidden;
			text-overflow: ellipsis;
			position: initial;
			margin: 0;
			background: transparent;
			text-align: center;
		    height: auto;
		    /*overflow: hidden;
		    text-overflow: ellipsis;
		    position: initial;*/
		}
		.categories-photo__item img{
			width: 70px;
			height: 70px;
			margin: auto;
			object-fit: cover;
			margin-top: 3px;
			margin-bottom: 5px;
			border-radius: 50px;
			border: 2px solid transparent;
			background: linear-gradient(45deg,#53a87f,#79b04d) border-box;
			mask-composite: exclude;
		}
		.subcategory_list .categories-photo__label {
		   
		}
		.subcategory_list .categories-photo__label p{
			/*font-size: 12px !important;
			white-space: nowrap !important;
			line-height: 1 !important;*/
			font-size: 12px !important;
		    line-height: 14px !important;
		    text-overflow: ellipsis;
		    margin: 0;
		    background: transparent;
		    text-align: center;
		    display: -webkit-box;
		    -webkit-line-clamp: 2;
		    -webkit-box-orient: vertical;
		    padding-bottom: 2px;
		}

	}
</style>
<?php if ($categories) { ?>

<div class="categories-photo subcategory_list" style="margin-bottom: 15px">
  	<div class="wrap">
	    <div id="categories-photo" class="categories-photo__row__collection">
	      
	      	<?php foreach ($categories as $category) { ?>
		      	<div class="categories-photo__item">
			        <a href="<? echo $category['href']; ?>" title="<? echo $category['name']; ?>">
						<img src="<?php echo $category['thumb']; ?>" alt="<? echo $category['name']; ?>">
						<div class="categories-photo__label">
		          			<span><? echo $text_subcategory_category; ?></span>
		            		<p><? echo $category['name']; ?></p>
		          		</div>
			        </a>
		      	</div>
	      	<? } ?>

	    </div>
	    <div class="show-more">
	      	<button class="show-more_btn" style="display: none;">
	        	<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
	          		<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        		</svg>
	        	<span><? echo $text_view_all; ?></span>
	      	</button>
	    </div>
  	</div>
</div>

<? } ?>

<script>
	// jQuery(document).ready(function($) {
	// 	if(document.documentElement.clientWidth > 1000) {  
	// 		setTimeout(function(){
	// 			var _hSubCat = $('.categories-photo__row__collection .categories-photo__item').innerHeight();
	// 			var _hTopMenu1 = $('.top-menu').innerHeight();
	// 			var positionSubCat = $('#categories-photo').last();
	// 			var offsetPaginationTopSubCat = positionSubCat.offset();

	// 			if ($('.categories-photo__item').length > 3) {
	// 				$('.categories-photo__row__collection').css('height', _hSubCat+10);
	// 				$('.categories-photo__row__collection .categories-photo__item').css('height', _hSubCat);
	// 				$('.show-more_btn').show();
	// 			}
				
	// 			$('.show-more_btn').on('click', function() {
	// 				var _textBtn = $(this).find('span').text();
	// 				$("html, body").animate({scrollTop: offsetPaginationTopSubCat.top-_hTopMenu1}, 400);
	// 				$(this).toggleClass('open');
	// 				$('.categories-photo__row__collection').toggleClass('open');
	// 				$(this).find('span').text(_textBtn == "Посмотреть все" ? "Скрыть" : "Посмотреть все");
	// 			});
	// 		}, 300);				
			
	// 	} else {
			// var _hTopMenu1 = $('.top-menu').innerHeight();
			// var positionSubCat = $('#categories-photo').last();
			// var offsetPaginationTopSubCat = positionSubCat.offset();
			// if ($('.categories-photo__item').length > 3) {
			//   $('.categories-photo__item:gt(2)').hide();
			//   $('.show-more_btn').show();
			// }
			
			// $('.show-more_btn').on('click', function() {
			// 	var _textBtn = $(this).find('span').text();
			// 	$('.categories-photo__item:gt(2)').slideToggle();
			// 	$("html, body").animate({scrollTop: offsetPaginationTopSubCat.top-_hTopMenu1}, 400);
			// 	$(this).toggleClass('open');
			// 	$(this).find('span').text(_textBtn == "Посмотреть все" ? "Скрыть" : "Посмотреть все");
			// });
		// }
	// });

			
</script>
