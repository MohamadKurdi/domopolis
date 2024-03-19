<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style>
	.blackfriday_wrap{
		background: #141414;
		padding: 60px 0;
		margin-bottom: 20px;
	}
	.blackfriday_wrap .action_two_column{
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 20px;
		
	}
	.blackfriday_wrap .action_two_column:not(:last-child){
		margin-bottom: 35px;
	}
	.blackfriday_wrap .action_two_column .action_left img{
		width: 100%;
		margin: auto;
	}
	.blackfriday_wrap .action_two_column .action_right{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.blackfriday_wrap .action_two_column .action_right .title{
		color: #fff;
		font-size: 32px;
		font-weight: 600;
		display: block;
		margin-bottom: 25px;
		text-transform: uppercase;
	}
	.blackfriday_wrap .action_two_column .action_right .date{
		color: #fbc17e;
		font-size: 32px;
		font-weight: 500;
		margin-bottom: 25px;
	}
	.blackfriday_wrap .text{
		color: #fff;
		font-size: 16px;
		margin-bottom: 20px;
	}
	.blackfriday_wrap .text a{
		color: #fbc17e !important;
	}
	.blackfriday_wrap ul{
		list-style: disc;
		padding-left: 20px;
		margin-bottom: 20px;
	}
	.blackfriday_wrap ul li{
		color: #fff;
		font-size: 16px;
	}
	.blackfriday_wrap .last-action-block {
		margin-top: 150px;
	}
	.blackfriday_wrap .last-action-block .title{
		text-align: center;
	}
	.blackfriday_wrap .last-action-block .news__title a{
		color: #fbc17e;
	}
	.blackfriday_wrap .last-action-block .news-items,
	.blackfriday_wrap .last-action-block .title{
		color: #fff;
	}
	.blackfriday_wrap .last-action-block .news__desc::after{
		display: none;
	}
	.blackfriday_wrap .action_two_column .action_right.text-left{
		align-items: self-start;
	}
	.blackfriday_wrap .last-action-block .news__desc{
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.blackfriday_wrap .last-action-block .news__photo{
		max-height: 200px;
		display: flex;
		align-items: center;
	}
	#promo-code-txt-action {
		color: #fbc17e;
		white-space: nowrap;
		padding: 2px 9px;
		border: 1px dashed;
		font-weight: 500;
		cursor: pointer;
		margin-left: 15px;
	}
	#promo-code-txt{
		color: #fbc17e;
		white-space: nowrap;
		padding: 2px 9px;
		border: 1px dashed;
		font-weight: 500;
		cursor: pointer;
	}
	.btn-copy {

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
	@media screen and (max-width: 768px) {
		.blackfriday_wrap .last-action-block{
			margin-top: 50px
		}
		.blackfriday_wrap .action_two_column{
			display: flex;
			flex-direction: column;
		}
	}	
</style>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section class="action-page blackfriday_wrap">
	<div class="wrap">
		<div class="action_two_column">
			<div class="action_left">
				<img src="/catalog/view/theme/default/img/blackfriday.jpg" alt="<?php echo $text_blackfriday_title; ?>">
			</div>
			<div class="action_right">
				<h1 class="title"><?php echo $text_blackfriday_title; ?></h1>
				<p class="date"><?php echo $text_blackfriday_pre_title; ?></p>
				<p class="text"><?php echo $text_blackfriday_text_1; ?>
				<span id="promo-code-txt-action" onclick="copytext(this)" title="скопировать промокод"><?php echo $text_blackfriday_promo_text; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt-action')" title="скопировать промокод"><span class="tooltiptext">Промокод скопирован</span></button></p>
			</div>
		</div>
		<div class="action_two_column">
			<div class="action_left">
				<p class="text"><?php echo $text_blackfriday_text_2; ?></p>
				<p class="text"><?php echo $text_blackfriday_text_3; ?></p>
			</div>
			<div class="action_right text-left">
				<p class="text"><?php echo $text_blackfriday_text_4; ?></p>
				<p class="text"><?php echo $text_blackfriday_text_5; ?></p>
				<ul>
					<li><?php echo $text_blackfriday_text_6; ?></li>
					<li><?php echo $text_blackfriday_text_7; ?></li>
					<li><?php echo $text_blackfriday_text_8; ?></li>
				</ul>
				<p class="text"><?php echo $text_blackfriday_text_9; ?></p>
			</div>
		</div>


		<?php if ($product_groups) { ?>
			<?php foreach ($product_groups as $product_group) { ?>
				<div>
					<a href="<?php echo $product_group['href']?>" title="<?php echo $product_group['href']?>"><?php echo $product_group['name']?></a>

					<?php foreach ($product_group['products'] as $product) { ?>
						<?php /* include single product template */ ?>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } ?>


		<div class="last-action-block">
			<h2 class="title"><?=$text_blackfriday_title_list ?></h2>
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
		
	</div>
</section>
<script>
	
	
	
	function copytext(el) {
		var $tmp = $("<textarea>");
		$("body").append($tmp);
		$tmp.val($(el).text()).select();
		document.execCommand("copy");
		$tmp.remove();
		$('.btn-copy').find('.tooltiptext').show();
		setInterval(function(){
			$('.btn-copy').find('.tooltiptext').hide();
		}, 1500);
	}  
</script>
<?php echo $footer; ?>