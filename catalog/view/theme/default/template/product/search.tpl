<?php echo $header; ?><?php if( ! empty( $mfilter_json ) ) { echo '<div id="mfilter-json" style="display:none">' . base64_encode( $mfilter_json ) . '</div>'; } ?>
<style type="text/css">
	@media screen and (min-width: 768px) {
	.sticky {
	position: fixed;
	z-index: 101;
	}
	.stop {
	position: relative;
	z-index: 101;
	}
	}
</style>
<?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>

<?php include(dirname(__FILE__).'/../structured/search_categories_list.tpl'); ?>

<?php include(dirname(__FILE__).'/../structured/intersections_list.tpl'); ?>
<?php $intersections = $intersections2; ?>
<?php include(dirname(__FILE__).'/../structured/intersections_list.tpl'); ?>

<?php echo $content_top; ?><div id="mfilter-content-container">
	
	<!--catalog-->
	
	<?php if (!empty($elastic_failed_error)) { ?>
	<div class="wrap">
		<h1 class="title"><?php echo $elastic_failed_error;?></h1>
		<code><?php echo $elastic_failed_error_message;?></code>
	</div>
	<?php } ?>
	
	<?php if (!empty($nothing_found)) { ?>
	<div class="wrap">
		<h1 class="title"><i class="fas fa-sad-tear"></i> <?php echo $text_nothing_found;?></h1>
	</div>
	<?php } ?>
	
	<?php include(dirname(__FILE__).'/../structured/product_list.tpl'); ?>
	<!--/catalog-->
	
	<script>
		
		$(document).ready(function () {
			
			if ((typeof fbq !== 'undefined')){
				fbq('track', 'ViewContent', 
				{
					content_type: 'product_group',
					content_ids: '<?php echo $category_id; ?>'
				});
			}
			
			$('.mob-menu__btn').click(function () {
				if($(this).hasClass("open")){
					$('.mfilter-free-button').removeClass('hide-btn');
					} else {
					$('.mfilter-free-button').addClass('hide-btn');
				}
			});
			
		});
		
	</script>
</div>
<!-- 	<script type="text/javascript">
	var google_tag_params = {
	<? foreach ($google_tag_params as $name => $value) { ?>
		<? if ($name != 'dynx_totalvalue' && $name != 'ecomm_totalvalue') { ?>
			<? echo $name; ?>:'<? echo $value ?>',
			<? } else { ?>
			<? echo $name; ?>:<? echo $value ?>,
		<? } ?>
	<? } ?>
	};
</script>  -->

<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_bottom; ?>
<?php } ?>


<?php echo $footer; ?>			
	