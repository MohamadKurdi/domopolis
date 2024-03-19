<?php echo $header; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style type="text/css">
	#countdown .digit {
    background-color: #51a881;
    color: #fff;
    font-size: 25px
	}
	#countdown .position {
	width: 30px;
	height: 40px;
	}
	#countdown .countDiv{
    height: 40px;
	}
	#countdown .digit.static {
	box-shadow: none;
	background-image: linear-gradient(bottom, #51a881 50%, #51a881 50%);
	background-image: -o-linear-gradient(bottom, #51a881 50%, #51a881 50%);
	background-image: -moz-linear-gradient(bottom, #51a881 50%, #51a881 50%);
	background-image: -webkit-linear-gradient(bottom, #51a881 50%, #51a881 50%);
	background-image: -ms-linear-gradient(bottom, #51a881 50%, #51a881 50%);
	background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0.5, #51a881), color-stop(0.5, #51a881) );
	}
	#countdown .countDiv:before,#countdown .countDiv:after {
	background-color: #FFC34F;
	box-shadow: none;
	left: 40%;
	}
	#countdown .bottom-left-days span{
    width: 57px;
    margin-left: 16px;
	}
	#countdown{
    padding-top: 50px;
    padding-bottom: 25px;
	}
	#countdown .bottom-left-days span:first-child {
	width: 57px;
	text-align: center;
	line-height: 14px;
	display: inline-block;
	margin-left: 0;
	}
	.actions_Date_Full{
    font-size: 20px;
    margin-bottom: 30px;
    margin-top: 5px;
	}
	@media (min-width: 950px){
	#actions_Info_Full .actions_Top_Full_Block #actions_Right_Block {
	text-align: center;
	display: flex;
	flex-direction: column;
	justify-content: center;
	}
	}
	#promo-code-txt{
    color: #50a780;
    white-space: nowrap;
    padding: 2px 9px;
    border: 1px dashed;
    font-weight: 500;
    cursor: pointer;
	}
	.btn-copy {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDc3Ljg2NyA0NzcuODY3IiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik0zNDEuMzMzLDg1LjMzM0g1MS4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJ2MjkwLjEzM2MwLDI4LjI3NywyMi45MjMsNTEuMiw1MS4yLDUxLjJoMjkwLjEzMyAgICBjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjEzNi41MzNDMzkyLjUzMywxMDguMjU2LDM2OS42MSw4NS4zMzMsMzQxLjMzMyw4NS4zMzN6IE0zNTguNCw0MjYuNjY3ICAgIGMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3SDUxLjJjLTkuNDI2LDAtMTcuMDY3LTcuNjQxLTE3LjA2Ny0xNy4wNjdWMTM2LjUzM2MwLTkuNDI2LDcuNjQxLTE3LjA2NywxNy4wNjctMTcuMDY3ICAgIGgyOTAuMTMzYzkuNDI2LDAsMTcuMDY3LDcuNjQxLDE3LjA2NywxNy4wNjdWNDI2LjY2N3oiLz4gIDwvZz48L2c+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik00MjYuNjY3LDBoLTMwNy4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJjMCw5LjQyNiw3LjY0MSwxNy4wNjcsMTcuMDY3LDE3LjA2N1MxMDIuNCw2MC42MjYsMTAyLjQsNTEuMiAgICBzNy42NDEtMTcuMDY3LDE3LjA2Ny0xNy4wNjdoMzA3LjJjOS40MjYsMCwxNy4wNjcsNy42NDEsMTcuMDY3LDE3LjA2N3YzMDcuMmMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3ICAgIHMtMTcuMDY3LDcuNjQxLTE3LjA2NywxNy4wNjdzNy42NDEsMTcuMDY3LDE3LjA2NywxNy4wNjdjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjUxLjIgICAgQzQ3Ny44NjcsMjIuOTIzLDQ1NC45NDQsMCw0MjYuNjY3LDB6Ii8+ICA8L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
    width: 22px;
    height: 12px;
    background-color: transparent;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    display: inline-flex;
    margin-left: 5px;
    cursor: pointer;
    position: relative;
	}
	.btn-copy .tooltiptext{
    display: none;
    width: 116px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 9px 6px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    font-size: 12px;
    top: -50px;
    left: 0;
	}
	.stock_wrap{
	padding-left: 20px;
	margin-top: 10px;
	}
	.stock_wrap li{
	list-style-type: disc;
	margin-bottom: 10px;
	}
	.stock_wrap li > div{
	display: flex;
	align-items: center;
	}
	.stock_wrap li:last-child{
	margin-bottom: 0;
	}
	.stock_wrap li > div #promo-code-txt-action{
	color: #50a780;
	white-space: nowrap;
	padding: 2px 9px;
	border: 1px dashed;
	font-weight: 500;
	cursor: pointer;
	margin-left: 15px;
	}
	.stock_wrap li > div .product__label-hit{
	margin-left: 15px;
	padding: 0 10px;
	}
</style>


<section class="action-page">
	<div class="wrap">
		<h1 class="title" itemprop="headline"><?php echo $h1 ?></h1> 
		<div id="content-action">
			<?php echo $content_top;  ?>
			<?php if(isset($actions_id)) { ?>
				<div id="actions_Info_Full"  itemscope="" itemtype="http://schema.org/Article">
					<div  id="actions_Info_Full_Item" class="actionsContentFull" itemprop="articleBody">
						<div class="actions_Top_Full_Block">
							<div class="actions_Info_Full_Image">
								<?php if($image): ?>
								<img src="<?php echo $image; ?>" alt="<?php echo $h1 ?>" title="<?php echo $h1 ?>" itemprop="image" />
								<?php endif; ?>
							</div>
							
							<div id="actions_Right_Block">
								<?php if(date('Y-m-d H:i:s') < $end_date){ ?>
									<script>
										console.log('<?php print_r($end_date)?>');
									</script>
									<div id="countdown">
										<span class="bottom-top-days" style="font-family: 'Montserrat', sans-serif;left: 0;right: 0;font-size: 20px;margin-bottom: 30px;"><?=$text_action_end ?>:</span>
										<span class="bottom-left-days" style="font-family: 'Montserrat', sans-serif;">
											<span style="font-family: 'Montserrat', sans-serif;"><?=$text_day ?></span>
											<span style="font-family: 'Montserrat', sans-serif;"><?=$text_hour ?></span>
											<span style="font-family: 'Montserrat', sans-serif;"><?=$text_minute ?></span>
											<span style="font-family: 'Montserrat', sans-serif;"><?=$text_sec ?></span>
										</span>
									</div>
									<?php } else {?>
									<div class="col-xs-24">
										<h3><? echo $text_action_sale_ended; ?></h3>
									</div>
								<?php } ?>
								<?php if(date('Y-m-d H:i:s') < $end_date) : ?>
								<?php if(!empty($date)) { ?><div class="actions_Date_Full"><?php echo $date; ?></div> <?php } ?>
								<?php if( count($product_related) > 0) { ?>
									<div class="button-click-down">
										<a href="<? echo $this_link; ?>#product-grid-link" type="button" value="" class="btn btn-success btn-buy" placeholder=""> <?=$text_byu ?> </a>
									</div>
								<?php } ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="actions_Description_Full" itemprop="description">
							<?php echo $content; ?>
							<ul class="stock_wrap">
								<?php if (!empty($text_only_in_stock)) { ?>
									<li><div><?php echo $text_only_in_stock; ?></div></li>
								<? } ?>
								
								<?php if (!empty($text_use_promocode)) { ?>
									<li><div>
										<?php echo $text_use_promocode; ?> <span id="promo-code-txt-action" onclick="copytext(this)" title="скопировать промокод"><?php echo $coupon; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt-action')" title="скопировать промокод"><span class="tooltiptext">Промокод скопирован</span></button>
									</div></li>
								<? } ?>
								
								<?php if (!empty($text_label_at_products)) { ?>
									<li><div><?php echo $text_label_at_products; ?> <div class="product__label-hit" style="--tooltip-color:#00bf00; background-color:#<?php echo $label_background; ?>; color:#<?php echo $label_color; ?>" data-tooltip="<?php echo $label_text; ?>"><?php echo $label; ?></div></div></li>
								<? } ?>
							</div>
							
						</div>
					</div>
				</div>
				
				<?php if( count($product_related) > 0) { ?>				
					<div id="product-grid-link" class="action-product-block">
						<div class="actionsRelHeader">
							<h2 class="title"><?php echo $text_relproduct_header; ?> </h2>
						</div>
						<div class="catalog__content ">
							<!--aside-->
							<?php if ($column_left && empty($do_not_show_left_aside_in_list)) { ?>
								<div class="aside">
									<!-- <div class="filter-btn" data-name-btn="Показать фильтр" data-name-btn-opened="Скрыть фильтр"></div> -->
									<!--filter-->
									<div class="filter">
										<!--accordion-->
										<div class="accordion">
											<!--accordion__item-->
											<?php echo $column_left; ?>
											<!--/accordion__item-->
										</div>
									</div>
									<!--/filter-->
								</div>
							<?php } ?>
							<!--/aside-->

							<!--product__grid-->
							<div class="product__grid" id="product__grid">
								<!--product__item-->
								<?php foreach ($product_related as $product) { ?>
									<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
								<?php } ?>
								<!--/product__item-->
							</div>
							<!--/product__grid-->
						</div>
					</div> 

					<output id="output" class="hidden"></output>
					<!--/product__grid-->
					
					<!--pages-->
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/pagination.tpl')); ?>
					<!--/pages-->  
				<?php } ?>
					<?php if (!empty($additionalActions)){ ?>
						<div class="last-action-block">
							<h2 class="title"><?=$text_last_actions ?></h2>
							<div  id="additionalActions" class="news-block">
								<?php foreach ($additionalActions as $item) { ?>
									<div class="actionListItem news-items" itemscope="" itemtype="http://schema.org/Article">
										<div class="actionsImage news__photo">
											<a href="<?php echo $item['href']; ?>">
												<img src="<?php echo $item['thumb']; ?>" title="<?php echo $item['caption']; ?>" alt="<?php echo $item['caption']; ?>" itemprop="image" />
											</a>
										</div>
										<div class="actionsHeader news__title">
											<a href="<?php echo $item['href']; ?>"><?php echo $item['caption']; ?></a>
										</div>
										<div class="actionsDescription news__desc" itemprop="description"><?php echo $item['description']; ?></div> 
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				<?php } else {?>
				
				<?php echo $content_bottom; ?>
			</div>
			<div id="actionsList">
				<div class="news-block">
					<?php foreach ($actions_all as $actions) { ?>
						<div class="actionListItem news-items <?php if ($actions['archive']) { ?>news-items-archive<?php } ?>" itemscope="" itemtype="http://schema.org/Article">
							<?php if ($actions['thumb']) { ?>
								<div class="actionsImage news__photo">
									<a href="<?php echo $actions['href']; ?>">
										<img src="<?php echo $actions['thumb']; ?>" title="<?php echo $actions['caption']; ?>" alt="<?php echo $actions['caption']; ?>" itemprop="image" />
									</a>
									<?php if ($actions['discount']): ?>
									<div class="black-abs-b">
										<span><?php if($actions['discount'] == 'free') print $text_in_gift; else print $actions['discount'];?></span>
									</div>
									<?php endif; ?>
								</div><?php } ?>
								<div class="actionsHeader news__title">
									<?php if ($actions['archive']) { ?><span><? echo $text_action_sale_ended; ?></span><?php } ?>
									<a href="<?php echo $actions['href']; ?>"><?php echo $actions['caption']; ?></a>
								</div>
								<div class="actionsDescription news__desc" itemprop="description"><?php echo $actions['description']; ?></div>
								
								<div class="actionsReadMore">
									<?php if ($actions['date']) { ?>
									<div class="news__date"><?php echo $actions['date']; ?></div><?php } ?>
								</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<!--     <?php if (!$this_is_archive) { ?> 
				<div class="archiveBtn">
				<a href="/all-actions?archive=1"><?=$text_archive ?></a>
				</div>
			<?php } ?> -->
			
			<div class="pagination"><?php echo $pagination; ?></div>
		<?php } ?>
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans+Condensed:300" />
		<link rel="stylesheet" href="/catalog/view/javascript/countdown/jquery.countdown.css">
		<script src="/catalog/view/javascript/countdown/jquery.countdown.js"></script>
		<script>
			$(function(){
			
			var note = $('#note');
			
				ts = "<?php echo $end_date_format ?>";
				ts = new Date(ts);

				$('#countdown').countdown({
					timestamp : ts,
					format: "on",
					callback  : function(days, hours, minutes, seconds){
					
						var message = "";
						
						message += days + " day" + ( days==1 ? '':'s' ) + ", ";
						message += hours + " hour" + ( hours==1 ? '':'s' ) + ", ";
						message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " and ";
						message += seconds + " second" + ( seconds==1 ? '':'s' ) + " <br />";
						
						note.html(message);
					}
				});
			
			});
			
			$('#actions_Right_Block .button-click-down a').on('click', function () {
				var $target = $('#product-grid-link');
				$('html, body').stop().animate({
					'scrollTop': $target.offset().top - 50
				}, 900, 'swing')
			});

			function copytext(el) {
				var $tmp = $("<textarea>");
				$("body").append($tmp);
				$tmp.val($(el).text()).select();
				document.execCommand("copy");
				$tmp.remove();
				$(el).next('.btn-copy').find('.tooltiptext').show();
				setInterval(function(){
					$(el).next('.btn-copy').find('.tooltiptext').hide();
				}, 1500);
			}  
		</script>
	</div>
</section>
<?php echo $footer; ?>

