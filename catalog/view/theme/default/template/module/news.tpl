<!--news 1-->
<?php if ($articles) { ?>
<section class="news">
  <div class="wrap">
    <h2 class="title center"><?php echo $heading_title; ?></h2>
    <!-- news-slider -->
    <div class="swiper-container news-slider">
      <div class="swiper-wrapper">
        <!--swiper-slide-->
        <?php foreach ($article as $articles) { ?>
        	<?php if (!empty($articles['name'])) { ?>	
		        <div class="swiper-slide">
		        	<a href="<?php echo $articles['href']; ?>" style="display: block;">
		          	<div class="news__photo">
			          	<?php if ($articles['thumb']) { ?>
			            	<img loading="lazy" src="<?php echo $articles['thumb']; ?>" title="<?php echo $articles['name']; ?>" alt="<?php echo $articles['name']; ?>" width="473" height="288" />
			            <?php } ?>
		          	</div>	
		          	<?php if ($articles['name']) { ?>							
		          		<div class="news__title">
							<?php echo $articles['name']; ?>
		            	</div>		
		          	<?php } ?>					
					<?php if ($articles['description']) { ?>
						<div class="news__desc">
							<?php echo $articles['description']; ?>
						</div>
		          	<?php } ?>
		          	<?php if ($articles['date_added']) { ?>
		         	 	<div class="news__date"><?php echo $articles['date_added']; ?></div>
		          	<?php } ?>
		          	</a>
		        </div>
	         <?php } ?>
        <?php } ?>
        <!--/swiper-slide-->
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
    </div>
    <!-- /news-slider -->
  </div>
</section>
<?php } ?>
<!--/news-->



<script>
	$(document).ready(function () {
		jQuery(function($){
			var max_col_height = 0;
			$('.news__title').each(function(){
				if ($(this).height() > max_col_height) {
					max_col_height = $(this).height();
				}
			});
			$('.news__title').height(max_col_height);
		});
	});
</script>