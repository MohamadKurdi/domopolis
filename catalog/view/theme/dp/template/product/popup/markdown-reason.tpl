<style type="text/css">
	.markdown-reason-content{
		background: #fff;
		padding: 20px 30px 20px;
		display: flex;
		flex-wrap: wrap;
	}
	.markdown-reason-content .characteristics{
		flex-basis: 50%;
		width: 50%;
		padding-left: 10px;
	}
	.markdown-reason-content .characteristics .char-list{
		margin-bottom: 15px;
		border: 1px solid #eae9e8;
		padding: 10px 15px;
	}
	.markdown-reason-content .text_warning{
		margin-bottom: 30px;
		font-weight: 500;
		color: #e16a5d;
		font-size: 13px;
	}
	.markdown-reason-content .characteristics .char-list li span:nth-child(1) {
	    background: #fff;
	    font-weight: 500;
	}
	.markdown-reason-content .image-markdown-reason{
		flex-basis: 50%;
		width: 50%;
		padding-right: 10px;
		margin-bottom: 0;
	}
	.markdown-reason-content .btn-group{
		flex-basis: 100%;
		width: 100%;
		text-align: center;
	}
	.markdown-reason-content .btn-group button{
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 50px;
		line-height: 50px;
		color: #fff;
		font-weight: 600;
		font-size: 17px;
		text-decoration: none;
		text-align: center;
		margin-left: auto;
		margin-right: auto;
	}
	.markdown-reason-content .price .price__new,
	.markdown-reason-content .price .price__old{
		white-space: nowrap;
	}
	.price__btn-group-popup{
		display: flex;
		margin-top: 0;
		height: 50px;
	}
	#markdown-reason-addToCart{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
	}
	.markdown-reason-content .characteristics .price{
		margin-bottom: 0;
	}
	@media screen and (max-width: 1280px){
		#markdown-reason-addToCart{
			width: 100% !important;
			margin-top: 7px;
		}
	}
	@media screen and (max-width: 1200px){
		.markdown-reason-content .characteristics,
		.markdown-reason-content .image-markdown-reason{
			flex-basis: 100%;
			width: 100%;
			padding: 0;
		}
		.markdown-reason-content .characteristics{
		    display: grid;		
		    grid-template-columns: 1fr 1fr;
		    grid-gap: 20px;
    	}
		.markdown-reason-content .characteristics .price{
			border: 0;
		}
		.markdown-reason-content .characteristics .price .price__sale{

		}
	}
	@media screen and (max-width: 768px){
		.markdown-reason-content .characteristics{	
		    grid-template-columns: 1fr;
    	}
    	.markdown-reason-content .text_warning{
    		margin-bottom: 0;
    	}
    	.markdown-reason-content .characteristics .price{
    		padding: 0;
    	}
    	.markdown-reason-content .characteristics .price .price__sale {
		    right: 0 !important;
		    left: inherit !important;
		    top: 0;
		}
	}
</style>

<div class="content markdown-reason-content">
	<div class="image-markdown-reason slider-box clrfix">        
      	<!--gallery-thumbs-->
      	<div class="gallery-thumbs">
            <div class="swiper-container">
          		<!--swiper-wrapper-->
          		<div class="swiper-wrapper">
	                <!--swiper-slide-->
	                <?php if ($thumb) { ?>
						<?php if ($thumb) { ?>						
							<?php for ($i = 1; $i <= 6; $i++) { ?>
								<div class="swiper-slide">
							    	<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/>
							    </div>
							<?php } ?>	
						<?php } ?>
					<?php } ?>
	                <!--/swiper-slide-->	                
          		</div>
          		<!--/swiper-wrapper-->
   		 	</div>
        	<!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
      	</div>
      	<!--/gallery-thumbs-->
      	<!--gallery-top-->
      	<div class="gallery-top">
        	<div class="swiper-container">
              	<!--swiper-wrapper-->
              	<div class="swiper-wrapper">
	                <!--swiper-slide-->
	                <?php if ($thumb) { ?>
						<?php if ($thumb) { ?>						
							<?php for ($i = 1; $i <= 6; $i++) { ?>
								<div class="swiper-slide">
							    	<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/>
							    </div>
							<?php } ?>	
						<?php } ?>
					<?php } ?>
              	</div>
              	<!--/swiper-wrapper-->
              	<div class="swiper-pagination"></div>
        	</div>
      	</div>
      	<!--/gallery-top-->        
	</div>	
	<div class="characteristics">
		<div class="characteristics-list">
			<ul class="char-list">
				<li>
					<h2>
						<span>Внешний вид:</span> <span><?php echo $markdown_appearance; ?></span>
					</h2>
				</li>
				<li>
					<h2>
						<span>Состояние:</span> <span><?php echo $markdown_condition; ?></span>
					</h2>
				</li>
				<li>
					<h2>
						<span>Упаковка:</span> <span><?php echo $markdown_pack; ?></span>
					</h2>
				</li>
				<li>
					<h2>
						<span>Комплектация:</span> <span><?php echo $markdown_equipment; ?></span>
					</h2>
				</li>
			</ul> 	

			<p class="text_warning">*Обратите внимание! Обмен и возврат уценённого товара невозможен.</p>	
		</div>
		<div class="price">
			<?php if ($price) { ?>
				<?php if (!$special) { ?>
					<div class="price__new"><?php echo $price; ?></div>
				<?php } else { ?>
					<div class="price__new"><?php echo $special; ?></div>
					<div class="price__old"><?php echo $price; ?></div>
					<div class="price__bottom">
						<div class="price__sale">-<?php echo $saving; ?>%</div>
					</div>	
				<?php } ?>
			<?php } ?>
			<div class="price__btn-group-popup">
				<button id="markdown-reason-addToCart" class="price__btn-buy btn">
					<svg width="26" height="25" viewbox="0 0 26 25" fill="none"
					xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
						stroke="white" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round"></path>
					</svg>
				<span>Купить</span></button>
			</div>
		</div>
	</div>	
	
</div>
<script>

	$('#markdown-reason-addToCart').on('click', function(){
		$('#main-add-to-cart-button').trigger('click');
		$('.overlay-popup-close').trigger('click');
	});		
		
</script>