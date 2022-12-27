<?php
	$this->language->load('module/mattimeotheme');
	$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

    <style type="text/css">
        .collection-alphabet{
            bottom: inherit !important;
        }
        .wrap-collection-alphabet{
            background-color: #fff;
            border-radius: 4px;
            min-height: 62px;
            margin-top: 15px;
            box-shadow: 0 2px 20px 0 #e2e1e1;
        }

        .collection-alphabet.swiper-pagination-bullets{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin: 0;
            padding: 0 20px;
            height: auto;
            position: relative;
        }
        .collection-alphabet .swiper-pagination-bullet{
            background: transparent !important;
            margin: 0 !important;
            width: 35px;
        }

        .collection-alphabet .swiper-pagination-bullet:hover,
        .collection-alphabet .swiper-pagination-bullet-active{
            background: transparent !important;
        }

        .collection-alphabet.swiper-pagination-bullets .swiper-pagination-bullet-active.box .text{
            color: #51a881;
            font-size: 25px;
            padding: 20px 20px 20px 10px;
            font-weight: 500;
        }
        .collection-alphabet.swiper-pagination-bullets .box .text{
            cursor: pointer;
            display: block;
            font-size: 16px;
            padding: 20px 15px;
            transition: all ease .3s;
            width: 12px;
            text-align: center;
            white-space: nowrap;
        }
        .collection-all{
            position: relative;
            width:auto;
            height:auto;
            padding: 50px 75px 0 75px;
        }
        .collection-all ul.list-collection{
            display: -ms-flexbox;
            display: flex;
            padding: 20px 0;
            box-sizing: content-box;
            
        }
        .collection-all ul.list-collection.bottom-row{
           margin-left: 110px;     
        }
        .collection-all ul li{
            width: auto;
            margin: 0;
            padding: 0 20px;
            max-height: 400px;
        }
        .collection-all ul li a{
            display: block;
            text-decoration: none;
            width: 400px;
        }
        .collection-all ul li a .categori-photo{
            width: 66%;
            padding: 12px 25px;
            margin-top: -50px;
            position: relative;
            background: #fff;
        }
        .collection-all ul li a .categori-photo span{
            line-height: 20px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #7f7f7f;
        }
        .collection-all ul li a .categori-photo p{
            font-size: 19px;
            margin-bottom: 0;
            line-height: 30px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .collection-all ul li a img{
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }
        
        .collection-all span.collections-slider-letter{
            font-size: 350px;
            margin: 0;
            position: absolute;
            left: -37px;
            top: 58%;
            transform: translate(0,-50%);
            color: #f5f5f5;
            z-index: -1;
        }  
        .collection-select .content{
            margin: 70px 0 60px;
            text-align: center;
        }
        .collection-select .title{
            
        }
        .collection-select .title::before {
            display: inline-block;
            position: absolute;
            left: 50%;
            top: -25px;
            content: " ";
            border-top: 1px solid #000;
            width: 69px;
            transform: translateX(-50%);
        }
        .collection-select .catalog__sort-btn #collection-select{
            
        }
        .collection-select  .SumoSelect > .CaptionCont {
            width: 250px;
            text-align: right;
        }
        .collection-conten .swiper-container{
            padding:0 40px;
        }
        @media screen and (max-width: 1400px){
            .collection-all ul li a img{
                max-height: 240px;
            }
            .collection-all {
                padding: 15px 75px 0 75px;
            }
            .collection-all ul.list-collection {
                padding: 5px 0;
            }
            .open_mob_filter{
                display: none !important;
            }
           
        }
        @media screen and (min-width: 768px) {
            .collection-alphabet-mobile{
                display: none;
            }
        }
        @media screen and (max-width: 768px){
            #content_collection{
                display: flex;
                flex-direction: column-reverse;
            }
            .content-collection{
                flex-direction: column;
            }
            .collection-all{
                padding: 0;
                display: flex;
                flex-direction: column;
            }
            .collection-all ul.list-collection{
                flex-direction: column;
                align-items: center;
            }
            .collection-all ul.list-collection.bottom-row{
                margin-left: 0;
            }
            .collection-all span.collections-slider-letter{
                position: relative;
                top: 0;
                left: 0;
                font-size: 65px;
                transform: translate(0,0%);
                display: block;
                width: 100%;
                height: 70px;
                color: #bdbdbd;
                line-height: 70px;
                margin-bottom: 3px;
            }
            .collection-all ul li,
            .collection-all ul li a{
                width: 100%;
                padding: 0;
            }
            .nav-coolection{
                padding: 0;
            }
            .collection-conten .swiper-container{
                padding: 0 20px;            
            }
            .collection-all ul li a img{
                max-height: 350px;
            }
            .collection-select .content {
                margin: 10px 0 15px;
            }
            .collection-select .title::before{
                display: none;
            }
            .nav-coolection{
                display: none;
            }
        }
        .product__item.tpl_list .product__info .reward_wrap {
            top: 0;
            right: 0;
        }
        @media screen and (min-width: 1280px){
            .product__item.tpl_list .product__line__promocode {
                margin-top: 25px;
            }
            .tpl_list .product__info {
                position: relative;
                top: 0;
                right: 0;
                left: 0
            } 
            .product__item.tpl_list:hover .product__info {
                position: relative;
                top: 0;
                right: 0;
                left: 0
            }
        }
/*        @media screen and (max-width:560px){
            .product__grid{
                justify-content: space-between;
                flex-wrap: wrap;
            }
            .product__grid__colection .product__item,
            .product__item, .manufacturer-list .manufacturer-content ul li:not(.list), 
            #content-search .catalog__content .product__item, #content-news-product .catalog__content .product__item{
                flex-basis: 49% !important;
                margin-bottom: 10px;
                padding: 10px;
                display: flex;
                flex-direction: column;
            }
            .product__item:hover .product__btn-cart button, .product__item:hover .product__btn-cart a,
            .product__item .product__btn-cart button, .product__item .product__btn-cart a{
                font-size: 0;
            }
            .product__rating{
                display: none
            }
            .product__title a {
                font-size: 12px;
                line-height: 14px;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .product__item .product__delivery{
                font-size: 10px;
                line-height: 1;
            }
            .product__label > div{
                font-size: 10px;
                padding: 4px 6px;
                line-height: 1;
                height: auto
            }
            .product__item .price__sale {
                right: 10px;
                left: inherit;
                top: 10px;
                font-size: 10px;
                padding: 4px 6px;
                line-height: 1;
            }
            .product__photo{
                margin-bottom: 10px
            }
            .product__price-new {
                font-size: 15px;
                line-height: 1;
                margin: 0
            }
            .product__price-old {
                font-size: 12px;
                line-height: 1;
                margin: 0;
                align-self: start;
            }
            .product__price {
                display: flex;
                flex-wrap: wrap;
                align-self: start;
                flex-direction: column;
                justify-content: start;
                align-items: start;
                text-align: left;
            }
            .product__item .product__btn-cart .number__product__block{
                display: none
            }
             .product__item .product__btn-cart button{
                padding-left: 0;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
             }
              .product__item .product__btn-cart button svg{
                margin: 0 !important;
                width: 15px;
                height: 15px
              }
             .product__item .product__btn-cart {
                position: absolute;
                margin: 0;
                z-index: 1;
                width: auto;
                right: 10px;
                bottom: 10px;
                height: 30px
            }
            .product_add-favorite {
                top: 10px;
                width: 40px;
                height: 35px;
                bottom: initial;
                left: 10px;
                flex-direction: column;
            }
            .product_add-favorite button{
                margin-left: 0 !important;
                margin-top: 0 !important;
                font-size: 15px !important;
            }
            .product_add-favorite button i{
                font-size: 15px !important;
            }
            .product_add-favorite button svg{
                width: 25px;
                height: 25px
            }
            .product__item:hover .product__btn-cart{
                width: auto
            }
            .product__item:hover .product__btn-cart button{
                padding-left: 0
            }
            .product__item:hover .product__price{
                flex-direction: column;
            }
            .product__item .product__info{
                padding-bottom: 0 !important
            }
        }*/
    </style>


<script type="text/javascript">
	if ($(window).width() >= '1000'){
		CloudZoom.quickStart();
	}            
</script>  

<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>

<div id="content" class="colection-content">
	<?php echo $content_top; ?>
    <div class="cont_bottom">
        <?php echo $content_bottom; ?>
	</div>

	<!--slider-box-->
	<div class="wrap">
		<!--slider-box-->
            <?php 
                $iImage = count($images);                
            ?>
            <?php if($iImage >= 1)  { ?>
    			<div class="slider-box clrfix">
    				<!--gallery-thumbs-->
    				<?php if (!$images) { ?>
    					<div class="gallery-thumbs" style="display: none !important;">
    							<div class="swiper-container">
    								<!--swiper-wrapper-->
    								<div class="swiper-wrapper"></div>
    						</div>
    					</div>
    				<?php } ?>
    				<?php if ($thumb || $images) { ?>
    					<?php $i=1; if ($images) { ?>
    						<div class="gallery-thumbs">
    							<div class="swiper-container thumbImages_<?php echo count($images); ?>">
    								<!--swiper-wrapper-->
    								<div class="swiper-wrapper">
    									<!--swiper-slide-->
    									
    									
    										<?php if ($thumb) { ?>
    											<div class="swiper-slide">
    												<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/>
    											</div>
    										<?php } ?>
    										
    										
    										<?php $i = 1;
    											if ($images) { ?>
    											<?php foreach ($images as $image) { ?>
    												<?php if (isset($image['thumb'])) { ?>
    													<div class="swiper-slide">
    														<img src="<?php echo $image['middle']; ?>" alt="<?php echo $heading_title; ?>">
    													</div>
    												<?php } ?>
    												<?php $i++;
    												} ?>
    										<?php } ?>
    									
    									<!--/swiper-slide-->
    								</div>
    								<!--/swiper-wrapper-->
    							</div>
    							<!-- Add Arrows -->
    							<div class="swiper-button-next"></div>
    							<div class="swiper-button-prev"></div>
    						</div>
    					<?php } ?>
    				<?php } ?>
    				<!--/gallery-thumbs-->
    				
    				<!--gallery-top-->
    				<div class="gallery-top"  <?php if (!$images) { ?> style="margin-left: 0" <?php } ?>>
    					<div class="swiper-container topImages_<?php echo count($images); ?>">
    						<!--swiper-wrapper-->
    						<div class="swiper-wrapper">
    							<!--swiper-slide-->
    							<?php if ($thumb || $images) { ?>
    								<?php if (($thumb) && ($smallimg) && ($this->config->get('product_zoom') == '1')) { ?>
    									<div class="swiper-slide">
    										<img class="cloudzoom"
    										src="<?php echo $popup; ?>" 
    										alt="<?php echo $heading_title; ?>"	
    										data-cloudzoom = "
    										zoomImage: '<?php echo $popup_ohuevshiy; ?>',
    										zoomSizeMode: 'image',
    										autoInside:750,
    										zoomPosition:'.cloudzoom-container'
    										">
    									</div>
    								<?php } ?>
    								
    								<?php $i = 1;
    									if ($images) { ?>
    									<?php foreach ($images as $image) { ?>
    										<?php if (isset($image['thumb'])) { ?>
    											<div class="swiper-slide">
    												<img class="cloudzoom"
    													src="<?php echo $image['popup']; ?>"
    													alt="<?php echo $heading_title; ?>"
    													data-cloudzoom = "
    													zoomImage: '<?php echo $image['popup_ohuevshiy']; ?>',
    													zoomSizeMode: 'image',
    													autoInside:750,
    													zoomPosition:'.cloudzoom-container'
    													">
    											</div>
    										<?php } ?>
    										<?php $i++;
    										} ?>
    								<?php } ?>
    							<?php } ?>
    							<!--/swiper-slide-->
    						</div>
    						<!--/swiper-wrapper-->
    						<div class="swiper-pagination"></div>
    					</div>
    				</div>
    				<!--/gallery-top-->
    			</div>
            <?php } ?>
			<!--/slider-box-->
		<!--/slider-box-->
		
		<!--item-column-->
		<div id="colaction_description" class="item-column colaction_description <?php if($iImage == 0)  { ?>no_image_left<?php } ?>">
			<div class="cloudzoom-container"></div>
			<a href="<?php echo $manufacturer_href; ?>" class="manufacturer_logo"><img loading="lazy" src="<?php echo $manufacturer_img_260; ?>" alt="image-brand"></a>
			
			<?php if (!empty($heading_title)) { ?>
				<h1 class="title text-center"><?php echo $heading_title; ?></h1>
			<?php } ?>

			<div class="details-brand" style="margin-bottom: 25px;">
				<p><span><?php echo $text_retranslate_brand; ?> </span><a href="<?php echo $manufacturer_href; ?>" style="color: #51A881;font-weight: bold;"><?php echo $manufacturer_name; ?></a></p>
				<?php if(!empty($manufacturer_location)) { ?>
					<p><span><?php echo $text_retranslate_manufacturer_country; ?> </span><span><?php echo $manufacturer_location; ?></span></p>
				<?php } ?>
			</div>

			<?php if ($description) { ?>
				<div class="manufacturer-info"><?php echo $description; ?></div>
			<?php } ?>
		</div>
		<script>
			    $(document).ready(function () {
			    	if(document.documentElement.clientWidth > 1600) {  
			        
						let _hDescripion = $('.manufacturer-info').innerHeight();

						if( _hDescripion > 300){

							$('.manufacturer-info').css({'height':'230px','overflow':'hidden'}).addClass('manufacturer-info-hide');

							$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

							$('.btn-open-desc').on('click', function(){
								$('.manufacturer-info').parent().toggleClass('open-btn');
								return false;
							});

						}
					};

    			});
		</script>
		<!--/item-column-->
	</div>			
	
<?php if(!empty($children)) { ?>	
<div id="content_collection">
    <div class="collection-conten">  
        <div class="swiper-container">

            <?php
                $count_collectio = count($children); 
            ?> 
                 

            <div class="wrap nav-coolection">
                <div class="wrap-collection-alphabet"<?php if ($count_collectio  == 1) { ?>style="opacity: 0;min-height: 0;"<?php } ?>>  
                    <div class="swiper-pagination collection-alphabet"></div>
                </div>
            </div>
            <div class="swiper-wrapper content-collection">    
                
                <?php foreach ($children as $letterArrayElem) { ?> 
                    
                <div class="swiper-slide collection-all">
                        <?php 
                            $i = count($letterArrayElem['collection']);
                            $alphabetLetter = str_replace(" ", "", $letterArrayElem['name']);
                         ?>  

                        <ul class="list-collection top-row">  

                            <span class="collections-slider-letter" id="<?php echo $alphabetLetter;?>"><?php echo $alphabetLetter;?></span>  

                            <?php foreach ($letterArrayElem['collection'] as $i=> $collection) { ?> 
                                 <?php if (($i + 1) % 2 != 0)  {  ?> 
                                    <li>
                                        <a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                            <?php if (!$collection['thumb']) { ?>
                                                <img loading="lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                            <?php } ?>
                                            
                                            <?php if ($collection['thumb']) { ?>
                                                <img loading="lazy" src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['name']; ?>" />
                                            <?php } ?>                                    
                                            <div class="categori-photo">
                                                <span>Коллекция</span>
                                                <p><?php echo $collection['name']; ?></p>
                                            </div>
                                        </a> 
                                    </li>    
                                 <?php } ?>                           
                            <?php } ?>  

                        </ul>

                        <?php if ($i >= 1) { ?>

                            <ul class="list-collection bottom-row"> 
                                <?php foreach ($letterArrayElem['collection']  as $i=>  $collection) { ?> 
                                       <?php if (($i + 1) % 2 == 0)  {  ?>                                               
                                        <li>
                                            <a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                                <?php if (!$collection['thumb']) { ?>
                                                    <img loading="lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                                <?php } ?>
                                                
                                                <?php if ($collection['thumb']) { ?>
                                                    <img class="swiper-lazy swiper-lazy-loaded" loading="lazy" src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
                                                <?php } ?>
                                                
                                                <div class="categori-photo">
                                                    <span>Коллекция</span>
                                                    <p><?php echo $collection['name']; ?></p>
                                                </div>
                                            </a> 
                                        </li>    
                                     <?php } ?>                             
                                <?php } ?>    
                            </ul>

                        <?php } ?> 
                    </div>
                    
                <? } ?>     
            </div>
        </div>
    </div>

 
    <div class="wrap collection-select">
        <div class="content">
            <h3 class="title"><?php echo $text_know_what_collection; ?></h3>
            <div class="catalog__sort-btn">
                <select id="collection-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_brands_head_collections; ?></option>
                    <?php foreach ($children as $letterArrayElem) { ?>
                        <?php foreach ($letterArrayElem['collection'] as $collection) { ?>
                            <option value="<?php echo $collection['href']; ?>" ><?php echo $collection['name']; ?></option>
                        <?php }  ?>                                 
                    <?php } ?>
                </select>
            </div>
            <div class="collection-alphabet-mobile"<?php if ($count_collectio  == 1) { ?>style="display:none;"<?php } ?> >
                <span style="display: block; text-align: center;"><?php echo $text_or; ?></span>
                <div class="catalog__sort-btn">
                <select id="collection-alphabet-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_choose_letter; ?></option>
                    <?php if ($children) { ?>
                            <?php foreach ($children as $collection) { ?>
                                <option value="<?php echo $collection['anchor']; ?>">
                                        <a href="<?php echo $collection['anchor']; ?>"><?php echo $collection['name']; ?></a>
                                </option>
                            <?php } ?>
                    <?php } ?> 
                </select>
                </div>
            </div>
          </div>
    </div>      
</div>
<?php } ?>
	<!--catalog-->
	<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_list.tpl')); ?>
	<!--/catalog-->
	

	<style>			
		.product-detail section.top-slider{
		overflow: inherit;
		}
		
		
		.cloudzoom-lens{
		border:1px solid #dbdbdb;
		border-radius: 0;
		background:#51a88159;
		/*filter: contrast(0.7);*/
		}
		.cloudzoom-blank > div:nth-of-type(3){
		display: none !important;
		}	

		.cloudzoom-container{
			position: absolute;
			width: 100%;
			left: 0;
			top: 0;
			z-index: -1;
		}	
		#colaction_description{
			position: relative;
		}
	</style>


</div>
<? if (isset($google_tag_params)) { ?> 
	<script type="text/javascript">
		var google_tag_params = {
			<? foreach ($google_tag_params as $name => $value) { ?>
				<? if ($name != 'dynx_totalvalue' && $name != 'ecomm_totalvalue') { ?>
					<? echo $name; ?>:'<? echo $value ?>',
					<? } else { ?>
					<? echo $name; ?>:<? echo $value ?>,
				<? } ?>
			<? } ?>
		};
	</script> 
<? } ?>




<script>

	function hDescripionMob() {
		let _hDescripion = $('.manufacturer-info').innerHeight();
		if( _hDescripion > 150){

			$('.colaction_description').css({'height':'auto'})

			$('.manufacturer-info').css({'height':'70px','overflow':'hidden'}).addClass('manufacturer-info-hide');

			$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

			$('.btn-open-desc').on('click', function(){
				$('.manufacturer-info').parent().toggleClass('open-btn');
				return false;
			});	
		
		}
	}

	function hDescripionDesc() {
		let _hDescripion = $('.manufacturer-info').innerHeight();
		if( _hDescripion >220){

			$('.colaction_description').css({'height':'auto'})

			$('.manufacturer-info').css({'height':'160px','overflow':'hidden'}).addClass('manufacturer-info-hide');

			$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

			$('.btn-open-desc').on('click', function(){
				$('.manufacturer-info').parent().toggleClass('open-btn');
				return false;
			});
			let hLeftColumn = $('.colection-content .slider-box').height();
    		$('.colection-content .colaction_description').css('height', hLeftColumn);	
		
		} else {

		  	let hLeftColumn = $('.colection-content .slider-box').height();
    		$('.colection-content .colaction_description').css('height', hLeftColumn);
		}
	}




	
    $(document).ready(function () {
		if ($(window).width() >= '1000'){
			let _hLeftBlock = $('.gallery-top').innerHeight();
			$('.cloudzoom-container').css('height',_hLeftBlock);
		}	
    	

        if(document.documentElement.clientWidth < 768) {

			hDescripionMob();
			
    	} else if(document.documentElement.clientWidth < 1600) {  	        

			hDescripionDesc();
		};

    });

	$(window).resize(function() {
		if(document.documentElement.clientWidth < 768) {

			hDescripionMob();

    	} else if (document.documentElement.clientWidth < 1600) {  	        

			hDescripionDesc();
		};
    });




    setTimeout(function(){


	<?php if(count($images) >= 1)  {?>
		if ($(".gallery-top")[0]) {
		// Slider top product
		var galleryThumbs<?php echo count($images); ?> = new Swiper('.gallery-thumbs .swiper-container.thumbImages_<?php echo count($images); ?>', {
		 	centeredSlides: false,
			 	<?php if(count($images) >= 5)  {?>
					slidesPerView: 4,
					loop: true,
					loopedSlides: 4,
					height: 400,
					breakpoints: {
						1280: {
						  	loopedSlides: 5,
						},
					},
				<?php } elseif(count($images) == 4) { ?>
					slidesPerView: 4,
					height: 400,
					breakpoints: {
						1300: {
						  	slidesPerView: 4,
						  	height: 400,
						},
						1400: {
						  	slidesPerView: 5,
						  	height: 500,
						},
					},
				<?php } elseif(count($images) == 3) { ?>
					slidesPerView: 4,
					height: 400,
				<?php } elseif(count($images) == 2) { ?>
					slidesPerView: 3,
					height: 300,
				<?php } elseif(count($images) == 1) { ?>
					slidesPerView: 2,
					height: 200,
				<?php } ?>


			touchRatio: 0.2,
			slideToClickedSlide: true,
			slideActiveClass: 'swiper-slide-thumb-active',
			direction: 'vertical',
			navigation: {
				nextEl: '.gallery-thumbs .swiper-button-next',
				prevEl: '.gallery-thumbs .swiper-button-prev',
			},
			
		});
		
		var galleryTop<?php echo count($images); ?> = new Swiper('.gallery-top .swiper-container.topImages_<?php echo count($images); ?>', {
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
		  	<?php if(count($images) < 5)  {?>
	  			thumbs: {
				    swiper: galleryThumbs<?php echo count($images); ?>
				}
		  	<?php } ?>	
		});
		<?php if(count($images) >= 5)  {?>
			galleryTop<?php echo count($images); ?>.controller.control = galleryThumbs<?php echo count($images); ?>;
			galleryThumbs<?php echo count($images); ?>.controller.control = galleryTop<?php echo count($images); ?>;
		<?php } ?>	  
	}
     },1000)
<?php } ?>		
</script>
<?php if(!empty($children)) { ?>

	<script>


	    var menu = [
	        <?php if ($children) {                
	             foreach ($children as $collection) {
	              ?> '<?php echo (str_replace(" ", "", $collection['name'])); ?>', <?php
	                
	             } 
	        } ?>
	    ];  


	    if(document.documentElement.clientWidth > 768) {  

	        window.onload = function() {
	          var mySwiper = new Swiper('#content_collection .swiper-container', {
	            loop: true,
	            slidesPerView: "auto",
	            freeMode: true,
	            lazy: true,
	            centeredSlides: false,
	            spaceBetween: 0,
	            grabCursor: true,
	            pagination: {
	              el: '.swiper-pagination',
	              clickable: true,
	              renderBullet: function(index, className) {
	                return '\
	                  <div class="box ' + className + '">\
	                  <div class="text">' + (menu[index]) + '</div>\
	                  </div>';
	              },
	            }
	          })
	        };
	    }; 
	</script>
<?php } ?>
<?php echo $footer; ?>