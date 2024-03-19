<?php
	$certs_names = array(
	'newcertificate' => $this->language->get('text_newyear_present_certificate'),
	'certificate' => $this->language->get('text_present_certificate')
	);
	
	$certs_imgs = array(
	'newcertificate' => 'catalog/view/theme/default/images/cert-2.jpg',
	'certificate' => 'image/present-cert-kp.jpg',
	);
	
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

<?php echo $header; ?><?php /* echo $column_left; ?><?php echo $column_right; */ ?>
<link rel="stylesheet" href="/catalog/view/theme/kpua/css/sumoselect.css">
<script src="/catalog/view/theme/kpua/js/sumoselect.min.js"></script>
<script src="catalog/view/theme/default/js/product/jquery.elevateZoom-3.0.8.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		
		// zoom
		$("#main-image").elevateZoom({
			gallery:'add-gallery',  
			galleryActiveClass: 'active',
			zoomType: "inner",
			cursor: "pointer"
		});	
		
	});
</script> 

<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/breadcrumbs.tpl')); ?>

<section id="content" class="presentcerts-page">
	<div class="wrap">
		
		<?php echo $content_top; ?>
		
		
		<?php if ($products_grouped) { ?>
			<?php foreach ($products_grouped as $key => $products) { ?>	
				<? $tp = $products; $last_product = array_pop($tp); ?>
				<? $this->load->model('tool/image'); $this->load->model('catalog/product');  ?>
				
				<? 
					//ЭТО СПИСОК МАЛЕНЬКИХ КАРТИНОК
					//Они хранятся, как дополнительные картинки в одном из товаров-сертификатов (в последнем)
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
		
	</div>
	
	
	<?php if ($description) { ?>
		<div class="cont_bottom">
			<div class="wrap">
	 			<div class="category-info">
					<?php if ($description) { ?>
	  					<?php echo $description; ?>
					<?php } ?>
				</div>
			</div>
		</div>	
	<?php } ?>
	
	
	<script>
		$('.addtocart').click(function(){
			var product_id = $('#select-'+$(this).attr('data-id')+' option:selected').attr('data-product-id');	
			if (product_id > 0){addToCart(product_id);}
		});
		
	</script>
</div>
</section>


<?php echo $content_bottom; ?>






<script>
	$(document).ready(function() {
		$('select').SumoSelect();
	});
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