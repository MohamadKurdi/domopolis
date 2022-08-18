<?php echo $header; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
  <?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
  <?php echo $description; ?>
  <?php echo $content_bottom; ?></div>

<script>

	$('.owl-carousel').each(function(){
		$(this).addClass('swiper-container');
		$(this).find('.view-model').wrapAll('<div class="swiper-wrapper"></div>').removeClass('view-model').addClass('swiper-slide');

		$(this).append('<div class="swiper-button-next"></div>');
		$(this).append('<div class="swiper-button-prev"></div>');
	});
	$('.owl-carousel').each(function(){
	        var swiper = new Swiper(this, {
     	      	slidesPerView: 3,
      			spaceBetween: 30,
      			loop: true,
      			navigation: {
			        nextEl: '.swiper-button-next',
			        prevEl: '.swiper-button-prev',
		      	},
			    breakpoints: {
			      320: {
			        slidesPerView: 1,
			      },
			      580: {
			        slidesPerView: 2,
			        spaceBetween: 30,
			      },
			      1120: {
			        slidesPerView: 3,
			        spaceBetween: 30,
			      },
			    }        
	    });
	});
    
    
</script>
<style>
    .swiper-container {
      width: 100%;
      height: 100%;
    }
    .swiper-slide a {
    	text-align: center;
    	display: block;
    }
    .swiper-slide a img{
    	max-height: 150px;
		display: inline-block;
		margin: 10px auto;
    }
    .swiper-slide .inf-block,
    .swiper-slide .inf-price{
    	font-weight: bold;
    }
    .swiper-button-next,
	.swiper-button-prev{
		color: #51a881;
	}
	.swiper-button-next{
		right: 0;
	}
	.swiper-button-prev{
		left: 0;
	}
	#content h1{
		padding-left: 0;
	}
	#button-comment{
		color: #fff;
	}
</style>
<?php echo $footer; ?>