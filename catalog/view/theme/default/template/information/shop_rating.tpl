<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>

<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'homepage'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value=token;
			});
		});
	</script>
<?php } ?>

<div id="content-shop-rating">
	<?php echo $content_top; ?>
	<div class="wrap">
    <div class="">
        <div><?php echo $content_top; ?>
            <?php if ($success != '') { ?>
				<div class="success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
			<?php } ?>
            <?php if ($error_name) { ?>
				<div class="warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_name; ?>
				</div>
			<?php } ?>
            <?php if ($error_email) { ?>
				<div class="warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_email; ?>
				</div>
			<?php } ?>
            <?php if ($error_comment) { ?>
				<div class="warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_comment; ?>
				</div>
			<?php } ?>
            <?php if ($error_captcha) { ?>
				<div class="warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_captcha; ?>
				</div>
			<?php } ?>
			
            <?php
				if(isset($shop_rating_authorized)){
					if(isset($customer_id) && $customer_id != 0){
						$show_form = true;
                        }else{
						$show_form = false;
					}
					
				}else{ $show_form = true; }
			?>
			
			
            <div class="shop_summary_rating">
                <?php if(!empty($shop_rating_summary) && !empty($general['count'])){ ?>
					<div class="shop_summary_general">
						<div class="shop_summary_general_title"><?php echo $text_summary; ?></div>
						<div class="shop_summary_general_rating"><?php echo $general['summ']?></div>
						<div class="summary-rate-star-show">
							<div class="summary-rate-star-show star-change" style="<?php echo 'width: '.$general['summ_perc'].'%'; ?>"></div>
						</div>
						<div class="shop_summary_general_desc"><?php echo $text_count; ?> <?php echo $general['count'];?></div>
					</div>
					<div class="shop_summary_detail">
						<div class="shop_summary_detail_line">
							<span class="star">5</span>
							<span class="percent_line" style=" <?php echo 'width:'. round($general['5']*100/$general['count']) . 'px';?>"></span>
							<span><?php echo $general['5'];?>x</span>
						</div>
						<div class="shop_summary_detail_line">
							<span class="star">4</span>
							<span class="percent_line" style="<?php echo 'width:' . round($general['4']*100/$general['count']) . 'px';?>"></span>
							<span><?php echo $general['4'];?>x</span>
						</div>
						<div class="shop_summary_detail_line">
							<span class="star">3</span>
							<span class="percent_line" style="<?php echo 'width:' . round($general['3']*100/$general['count']) . 'px';?>"></span>
							<span><?php echo $general['3'];?>x</span>
						</div>
						<div class="shop_summary_detail_line">
							<span class="star">2</span>
							<span class="percent_line" style="<?php echo 'width:' . round($general['2']*100/$general['count']) . 'px';?>"></span>
							<span><?php echo $general['2'];?>x</span>
						</div>
						<div class="shop_summary_detail_line">
							<span class="star">1</span>
							<span class="percent_line" style="<?php echo 'width:'.round($general['1']*100/$general['count']).'px';?>"></span>
							<span><?php echo $general['1'];?>x</span>
						</div>
					</div>
					<div class="shop_summary_add_rating price__btn-group">
					</div>
					<div style="clear: both"></div>
					<?php }else{ ?>

					<?php if($show_form){ ?>
						<button class="rating_btn add_rating-button" data-remodal-target="ratingModal"><?php echo $send_rating;?></button>
						<?php } else { ?>
						<?php echo $text_login; ?>						
					<?php } ?>
				<?php } ?>
			</div>
			
            <div class="shop_rates_list">
                <?php foreach($ratings as $rating){ ?>
					<div class="reviews-col__item">
						<div class="reviews__author-name">
							<span class="name">
								<b><?php echo $rating['customer_name'];?></b>
								<?php if(isset($rating['customer_id']) && $rating['customer_id'] != 0){ ?>
									(<?php echo $registered_customer_text; ?>)
								<?php } ?>							
							</span>
							
							<span class="date"><?php echo date('d.m.Y', strtotime($rating['date_added']));?></span>
							<div class="clearfix"></div>
						</div>
						<div class="rates-line">
							<div class="ratings-item-rates" <?php if($rating['customs']){ echo 'style="width: 47%; float:left"'; }?>>
								<?php if(isset($shop_rating_shop_rating)){ ?>
									<div class="ratings-item-rates-item shop-rates" <?php if($rating['customs']){ echo 'style="width: 100%"'; }?>>
										<div class="rates-title"><?php echo $entry_rate; ?>: </div>
										<div class="rate-stars">
											<div class="rate-star-show <?php if($rating['shop_rate'] >= 1)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['shop_rate'] >= 2)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['shop_rate'] >= 3)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['shop_rate'] >= 4)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['shop_rate'] == 5)echo 'star-change';?>"></div>
										</div>
									</div>
								<?php } ?>
								<?php if(isset($shop_rating_site_rating)){ ?>
									<div class="ratings-item-rates-item shop-rates" <?php if($rating['customs']){ echo 'style="width: 100%"'; }?>>
										<div class="rates-title"><?php echo $entry_site_rate; ?>: </div>
										<div class="rate-stars">
											<div class="rate-star-show <?php if($rating['site_rate'] >= 1)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['site_rate'] >= 2)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['site_rate'] >= 3)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['site_rate'] >= 4)echo 'star-change';?>"></div>
											<div class="rate-star-show <?php if($rating['site_rate'] == 5)echo 'star-change';?>"></div>
										</div>
									</div>
								<?php } ?>
								<div class="clearfix"></div>
							</div>
							<?php if($rating['customs']){ ?>
								<div class="ratings-item-rates" style="width: 47%; float: right; margin-top: 15px">
									<?php foreach($rating['customs'] as $custom_rate){ ?>
										<div class="ratings-item-rates-item custom-rate">
											<div class="rates-title"><?php echo $custom_rate['title']; ?> </div>
											<div class="rate-stars">
												<div class="rate-star-show small-stars <?php if($custom_rate['value'] >= 1)echo 'star-change';?>"></div>
												<div class="rate-star-show small-stars <?php if($custom_rate['value'] >= 2)echo 'star-change';?>"></div>
												<div class="rate-star-show small-stars <?php if($custom_rate['value'] >= 3)echo 'star-change';?>"></div>
												<div class="rate-star-show small-stars <?php if($custom_rate['value'] >= 4)echo 'star-change';?>"></div>
												<div class="rate-star-show small-stars <?php if($custom_rate['value'] == 5)echo 'star-change';?>"></div>
											</div>
										</div>
									<?php } ?>
									<div class="clearfix"></div>
								</div>
							<?php } ?>
							<div class="clearfix"></div>
						</div>
						<div class="ratings-item-comment"><?php echo nl2br($rating['comment']);?></div>
						<?php if(isset($shop_rating_good_bad)){ ?>
							<div class="ratings-item-good-bad">
								<div class="good">
									<ul>
										<?php $goodonce = explode("\n", nl2br($rating['good'])) ?>
										<?php foreach($goodonce as $good_item){ ?>
											<?php if($good_item != ''){ ?>
												<li><?php echo $good_item;?></li>
											<?php }?>
										<?php }?>
									</ul>
									
								</div>
								<div class="bad">
									
									<ul>
										<?php $badonce = explode("\n", nl2br($rating['bad'])) ?>
										<?php foreach($badonce as $bad_item){ ?>
											<?php if($bad_item != ''){ ?>
												<li><?php echo $bad_item;?></li>
											<?php }?>
										<?php }?>
									</ul>
								</div>
								<div class="clearfix"></div>
							</div>
						<?php } ?>
						
						
					</div>
					<?php if(isset($rating_answers[$rating['rate_id']]) && $rating_answers[$rating['rate_id']] != ''){ ?>
						<div class="ratings-item-answer reviews-col__item">
							<div class="ratings-item-answer-title"><?php echo $answer; ?></div>
							<div class="ratings-item-answer-content"><p><?php echo nl2br($rating_answers[$rating['rate_id']]); ?></p></div>
							
						</div>
					<?php } ?>
				
				<?php }?>
				
			</div>
			<div class="shop_rates_pagination">
				<div class="pagination"><?php echo $pagination; ?></div>
			</div>
			<?php echo $content_bottom; ?>
			<?php echo $column_right; ?>
			<div class="clearfix"></div>
			
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>

<!-- popup_form_revievs -->
<div class="overlay_popup"></div>
<div class="popup-form" id="formRev">
	<div class="object">
        <div class="overlay-popup-close">X</div>
        <h3>Оставить отзыв</h3>
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/rating_form_shop.tpl')); ?>
	</div>
</div>
<!-- popup_form_revievs -->

<script type="text/javascript">
    $(document).ready(function(){
        $('[data-remodal-id=ratingModal]').remodal();
        $('.rate-star').hover(
		function(){
			var params = this.id.split('-');
			var type = params[0];
			var id = params[1];
			$('[id^='+type+']').removeClass('star-hover');
			
			for(var i = 1; i<=id; i++){
				$('#'+type+'-'+i).addClass('star-hover');
			}
			},function(){
			var params = this.id.split('-');
			var type = params[0];
			var id = params[1];
			
			$('[id^='+type+']').removeClass('star-hover');
		}
        );
        $('.rate-star').click(function(){
            var params = this.id.split('-');
            var type = params[0];
            var id = params[1];
            $('[id^='+type+']').removeClass('star-change');
			
            for(var i = 1; i<=id; i++){
                $('#'+type+'-'+i).addClass('star-change');
			}
            //$("input[name="+type+"-input]").attr('checked', false);
            //$("input[name="+type+"-input][value=" + id + "]").attr('checked', true);
            $("input[name="+type+"-input]").val(id);
		});
	});
	
</script>
<?php echo $footer; ?>