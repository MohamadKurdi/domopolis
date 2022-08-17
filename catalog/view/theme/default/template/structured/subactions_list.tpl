
<?php if (!empty($top_actions)) { ?>
	<style>
		.action_slider.swiper-container{
			max-width: 790px;
		}
		.wrap_action_slider .wrap-btn{
			max-width: 1000px;
			position: absolute;
			top: 0;
			bottom: 0;
			right: 0;
			left: 0;
			margin: auto;
		}
		.wrap_action_slider .swiper-button-next:after, 
		.wrap_action_slider .swiper-button-prev:after{			
    		color: #51a881;
		}
		.wrap_action_slider .swiper-button-prev:after{
			filter: drop-shadow(2px 0 0 #fff);
		}
		.wrap_action_slider .swiper-button-next:after{
			filter: drop-shadow(-2px 0 0 #fff);
		}
		.action_slider .action-txt{
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 0 30px;
		}
		.detail_action{
			height: 32px;
		}
		.detail_action:hover{
			color: #fff;
		}
		.action_slider .action-txt p{
			font-weight: 500;
		    font-size: 18px;
		}
		.action_slider{
			margin-bottom: 40px;
		}
		.action_slider a{
			display: block;
		}
		.wrap_action_slider .swiper-pagination{
			display: none;
		}
		@media screen and (max-width:870px) {
			.action_slider.swiper-container{
				max-width: 90%;
			}
			.wrap_action_slider .wrap-btn{
				max-width: 100%;
			}
			.action_slider {
			    margin-bottom: 0;
			}
			.wrap_action_slider .swiper-button-next, 
			.wrap_action_slider .swiper-button-prev{
				top: 40%;
			}
			.wrap_action_slider .wrap-btn{
				display: none;
			}
			.wrap_action_slider .swiper-pagination{
				display: block;
				position: relative;
				bottom: 0;
				margin-top: 10px;
			}
		}
		@media screen and (max-width:576px) {
			.action_slider.swiper-container{
				max-width: 100%;
			}
			.wrap_action_slider .swiper-button-prev{
				left: 30px;
			}
			.wrap_action_slider .swiper-button-next{
				right: 30px;
			}
			.action_slider .action-txt span{
				font-size: 12px;
			}
			.action_slider .action-txt p{
				font-size: 15px;
			}
			.action_slider .action-txt {
			    align-items: flex-start;
			    flex-direction: column;
			    padding: 0;
			}
			.detail_action{
				margin-top: 5px;
			}
		}
	</style>
	<div class="wrap wrap_action_slider">
		<div class="action_slider swiper-container">

		    <div class="swiper-wrapper">	   
		        <?php foreach ($top_actions as $top_action) { ?>
					<div class="swiper-slide">
						<a href="<? echo $top_action['href']; ?>">
							<img src="<?php echo $top_action['thumb']; ?>" alt="<? echo $top_action['name']; ?>">
							<div class="action-txt">
								<span><? echo $text_subcategory_action; ?></span>
								<p><? echo $top_action['name']; ?></p>
								<a href="<? echo $top_action['href']; ?>" class="detail_action"><? echo $text_more_details; ?></a>
							</div>
						</a>
					</div>
				<? } ?>
		    </div>

		    <div class="swiper-pagination"></div>

		    

		</div>
		<div class="wrap-btn">
			<div class="swiper-button-prev"></div>
	    	<div class="swiper-button-next"></div>
		</div>
		
	</div>
	<script>
		$(document).ready(function() {
			let prevBtn = $('.action_slider').parent().find('.swiper-button-prev');
			let nextBtn = $('.action_slider').parent().find('.swiper-button-next');

			if($(".action_slider .swiper-slide").length == 1) {
				$('.action_slider').parent().find('.swiper-button-prev').remove();
				$('.action_slider').parent().find('.swiper-button-next').remove();
			}

			
				var mySwiper = new Swiper('.action_slider', {
					loop: true,
					preloadImages: false,   
					lazy: true,
					navigation: {
						nextEl: nextBtn,
						prevEl: prevBtn,
						clickable: true,
					},
					autoplay: {
					    delay: 8000,
				  	},
					pagination: {
						el: '.action_slider .swiper-pagination',
						clickable: true,
					},
					autoHeight: true
				});
			if($(".action_slider .swiper-slide").length > 1) {	
			};
		});
	</script>
	
<? } ?>