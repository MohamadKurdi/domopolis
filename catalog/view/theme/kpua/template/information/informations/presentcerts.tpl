<?php

$products_grouped = array();
foreach ($products as $product){
	if (isset($product['location'])) {
		$s_o = $product['location'];
	} else {
		$s_o = 99;
	}	
	$products_grouped[$s_o][] = $product;
}

?>


<?php echo $header; ?>
<link rel="stylesheet" href="/catalog/view/theme/kpua/css/sumoselect.css">
<script src="/catalog/view/theme/kpua/js/sumoselect.min.js"></script>

<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/breadcrumbs.tpl')); ?>

<?php echo $column_left; ?><?php echo $column_right; ?>

<?php if (isset($informations) && $informations) { ?>
    <style>
      .info_menu_list li a{
        display: flex !important;
        flex-direction: column;
        transition: .2s ease-in-out;
      }

      .info_menu_list li a i{
        font-size: 45px;
      }

      .info_menu_list li a span{
        font-size: 18px;
        margin-top: 20px;
        font-family: montserrat,sans-serif;
      }

      .info_menu_list li a img{
        display: none;
      }

      .info_menu_list li a:hover,
      .info_menu_list li.active a{
        color: #51a881;
      }
    </style>
    <section class="info_block">
      	<div class="wrap">        
			<div class="info_menu_block">
				<div class="information">
					<ul class="info_menu_list">
						<?php foreach ($informations as $information) { ?>
							<li data-id="<?php echo $information['information_id'] ?>" 
							class="menu-<?php echo $information['information_id'] ?> <?php if ($information['active']) {?> active <?php } ?>">
								<a class="info_menu" style="text-align:center;" href="<?php echo $information['href'] ?>" title="<?php echo $information['title'] ?>">
									<i class="fas"></i>
									<img src="<?php echo $information['image'] ?>" title="" alt="<?php echo $information['title'] ?>" />
									<span><?php echo $information['title'] ?></span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>       
      	</div>
    </section>
<?php } ?>


<div id="content" class="presentcerts-page">
	<div class="wrap">
	<?php echo $content_top; ?> 
  
  	<?php if ($products_grouped) { ?>
	  	<?php foreach ($products_grouped as $key => $products) { ?>	
	  		<? $tp = $products; $last_product = array_pop($tp); ?>
			<? $this->load->model('tool/image'); $this->load->model('catalog/product');  ?>
	
			<? 
				$product_images = $this->model_catalog_product->getProductImages($last_product['product_id']);
				$images = array();
				if (count($product_images) > 0){
					foreach ($product_images as $image){
						$images[] = array(
							'thumb' => $this->model_tool_image->resize($image['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
							'big' => $this->model_tool_image->resize($image['image'], 650, 650)
						);									
					}											
				}
			?>		  				
			<div class="cert">

				<div class="image-cert">					
					<div class="gallery-top">				
						<div class="swiper-container topImages">					
							<!--swiper-wrapper-->
							<div class="swiper-wrapper">
								<!--swiper-slide-->
								<div class="swiper-slide">
									<img src="<?php echo $certs_imgs[$key] ?>" alt="<?php echo $heading_title; ?>">
								</div>

								<?php
								//Это их вывод
								//$one_small_image_for_carousel['thumb'] - маленькая, $one_small_image_for_carousel['big'] - большая,
								foreach ($images as $one_small_image_for_carousel) { ?>
							
				  					<div class="swiper-slide">
				  					<?php if ($one_small_image_for_carousel['thumb']) { ?>
					  					<img src="<? echo $one_small_image_for_carousel['big']; ?>">
				  					<?php } ?>
				  					</div>
			  					<?php } ?>
								<!--/swiper-slide-->
							</div>
							<!--/swiper-wrapper-->
							<div class="swiper-pagination"></div>
						</div>
					</div>
				</div>

				<div class="content-cert">
					<div class="headline-1"><?php echo $certs_names[$key]; ?></div>
					<!--<div class="headline-2" id="headline-2-<?php echo $key; ?>"></div>-->
					<div class="headline-3">
												
						<select id="select-<?php echo $key; ?>" onchange="$('#headline-2-<?php echo $key; ?>').html($(this).val());">							
							<?php foreach ($products as $product_one) { ?>
								<option data-price="<?php echo $product_one['price']; ?>" data-product-id="<?php echo $product_one['product_id']; ?>">
									<?php echo $product_one['price']; ?>											
								</option>
							<?php } ?>
						</select>

						<div class="cart">
							<input data-id="<?php echo $key; ?>" type="button" value="<?php echo $button_cart; ?>" class="button addtocart btn btn-acaunt" />	
						</div>						
					</div>
					<p><?php echo $product_one['description']; ?></p>
				</div>

			</div>	
		<?php } ?>
	<?php } ?>

	
  
  	<div id="information-wrapper-block">
		<?php echo $description; ?>
	</div>
  

  <?php echo $content_bottom; ?></div></div>
  
  
<script>

	$('.addtocart').click(function(){
		var product_id = $('#select-'+$(this).attr('data-id')+' option:selected').attr('data-product-id');	
		if (product_id > 0){addToCart(product_id);}
	});

	$('.menu-29').find('i').addClass('fa-info-circle');
	$('.menu-31').find('i').addClass('fa-truck');
	$('.menu-30').find('i').addClass('fa-wallet');
	$('.menu-34').find('i').addClass('fa-chess-rook');
	$('.menu-35').find('i').addClass('fa-gifts');
	$('.menu-32').find('i').addClass('fa-percent'); 
	$('.menu-33').find('i').addClass('fa-sync-alt'); 
	$(document).ready(function() {
		$('select').SumoSelect();
	});
</script>
  




<script>
	if ($(".gallery-top")[0]) {
		var galleryTop = new Swiper('.gallery-top .swiper-container.topImages', {
			slidesPerView: 'auto',
		 	loop: true,
		  	loopedSlides: 4,
		  	pagination: {
		        el: '.gallery-top .swiper-pagination',
		        clickable: true,
			},
			breakpoints: {
		        1280: {
					loopedSlides: 5,
				},
			},
		});
		
	}

	
  </script>

<?php echo $footer; ?>