<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				
				<div id="tabs" class="htabs">
					<a href="#tab-general">Основное</a>
					<a href="#tab-data">Мультиязык</a>
					<div class="clr"></div>
				</div>
				
				<div id="tab-data">
					
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td>Текст отзыва</td>
									<td><textarea name="review_description[<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"><?php echo isset($review_description[$language['language_id']]) ? $review_description[$language['language_id']]['text'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Достоинства</td>
									<td><textarea name="review_description[<?php echo $language['language_id']; ?>][good]" cols="40" rows="5"><?php echo isset($review_description[$language['language_id']]) ? $review_description[$language['language_id']]['good'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Недостатки</td>
									<td><textarea name="review_description[<?php echo $language['language_id']; ?>][bads]" cols="40" rows="5"><?php echo isset($review_description[$language['language_id']]) ? $review_description[$language['language_id']]['bads'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Ответ на отзыв</td>
									<td><textarea name="review_description[<?php echo $language['language_id']; ?>][answer]" cols="40" rows="5"><?php echo isset($review_description[$language['language_id']]) ? $review_description[$language['language_id']]['answer'] : ''; ?></textarea></td>
								</tr>							
							</table>
						</div>
					<?php } ?>
				</div>
				
				
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_author; ?></td>
							<td><input type="text" name="author" value="<?php echo $author; ?>" />
								<?php if ($error_author) { ?>
									<span class="error"><?php echo $error_author; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_product; ?></td>
							<td><input type="text" name="product" value="<?php echo $product; ?>" />
								<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
								<?php if ($error_product) { ?>
									<span class="error"><?php echo $error_product; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							
							<td><?php echo $entry_rating; ?></td>
							<td>
								<div class="rating">
									<div class="radio-div">
										<?php if ($rating == 1) { ?>
											<input class="radio-star" type="radio" name="rating" value="1" checked/>
											<?php } else { ?>
											<input class="radio-star" type="radio" name="rating" value="1" />
										<?php } ?>
										<?php if ($rating == 2) { ?>
											<input class="radio-star" type="radio" name="rating" value="2" checked/>
											<?php } else { ?>
											<input class="radio-star" type="radio" name="rating" value="2" />
										<?php } ?>
										<?php if ($rating == 3) { ?>
											<input class="radio-star" type="radio" name="rating" value="3" checked/>
											<?php } else { ?>
											<input class="radio-star" type="radio" name="rating" value="3" />
										<?php } ?>
										<?php if ($rating == 4) { ?>
											<input class="radio-star" type="radio" name="rating" value="4" checked/>
											<?php } else { ?>
											<input class="radio-star" type="radio" name="rating" value="4" />
										<?php } ?>
										<?php if ($rating == 5) { ?>
											<input class="radio-star" type="radio" name="rating" value="5" checked/>
											<?php } else { ?>
											<input class="radio-star" type="radio" name="rating" value="5" />
										<?php } ?>
									</div>
									<div class="star-div">
										<?php if ($rating == 1) { ?><span class="icstars-1"></span><?php }elseif ($rating == 2) { ?><span class="icstars-2"></span><?php }elseif ($rating == 3) { ?><span class="icstars-3"></span><?php }elseif ($rating == 4) { ?><span class="icstars-4"></span><?php }elseif ($rating == 5) { ?><span class="icstars-5"></span><?php }elseif ($rating == 0) { ?><span class="icstars-0"></span><?php } ?>
									</div>
								</div>
							</td>
						</tr>
						<tr><td><?php echo $entry_goods; ?></td><td><textarea name="good" cols="60" rows="8"><?php echo $good; ?></textarea></td></tr>
						<tr><td><?php echo $entry_bads; ?></td><td><textarea name="bads" cols="60" rows="8"><?php echo $bads; ?></textarea></td></tr>
						<tr><td><span class="required">*</span> <?php echo $entry_texts; ?></td><td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea><?php if ($error_text) { ?><span class="error"><?php echo $error_text; ?></span><?php } ?></td> </tr>
						<tr><td><?php echo $entry_answer; ?></td><td><textarea name="answer" cols="60" rows="8"><?php echo $answer; ?></textarea>
							<a href="<?php echo $addimage_url; ?>" style="display:inline-block; float:right;"><img src="<?php echo $addimage_url; ?>" style="max-height:300px;" /></a>
						</td></tr>
						<tr><td><?php echo $entry_addimage; ?></td><td><input type="text" name="addimage" value="<?php echo $addimage; ?>" style="width:321px;" /></td></tr>
						<tr>
							<td><?php echo $entry_purchased; ?></td>
							<td class="switch">
								<?php if ($purchased) { ?>
									<input type="radio" id="radio1198" name="purchased" checked value="1" />
									<input type="radio" id="radio1199" name="purchased" value="0" />
									<label for="radio1198" class="cb-enable selected"><span>Вкл</span></label>
									<label for="radio1199" class="cb-disable"><span>Выкл</span></label>
									<?php } else { ?>
									<input type="radio" id="radio1198" name="purchased" value="1" />
									<input type="radio" id="radio1199" name="purchased" checked value="0" />
									<label for="radio1198" class="cb-enable"><span>Вкл</span></label>
									<label for="radio1199" class="cb-disable selected"><span>Выкл</span></label>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_html_status; ?></td>
							<td class="switch">
								<?php if ($html_status) { ?>
									<input type="radio" id="radio1197" name="html_status" checked value="1" />
									<input type="radio" id="radio1196" name="html_status" value="0" />
									<label for="radio1197" class="cb-enable selected"><span>Вкл</span></label>
									<label for="radio1196" class="cb-disable"><span>Выкл</span></label>
									<?php } else { ?>
									<input type="radio" id="radio1197" name="html_status" value="1" />
									<input type="radio" id="radio1196" name="html_status" checked value="0" />
									<label for="radio1197" class="cb-enable"><span>Вкл</span></label>
									<label for="radio1196" class="cb-disable selected"><span>Выкл</span></label>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td class="switch">
								<?php if ($status) { ?>
									<input type="radio" id="radio1195" name="status" checked value="1" />
									<input type="radio" id="radio1194" name="status" value="0" />
									<label for="radio1195" class="cb-enable selected"><span>Вкл</span></label>
									<label for="radio1194" class="cb-disable"><span>Выкл</span></label>
									<?php } else { ?>
									<input type="radio" id="radio1195" name="status" value="1" />
									<input type="radio" id="radio1194" name="status" checked value="0" />
									<label for="radio1195" class="cb-enable"><span>Вкл</span></label>
									<label for="radio1194" class="cb-disable selected"><span>Выкл</span></label>
								<?php } ?>
							</select></td>
					</tr>

					<tr>
						<td>
							<?php echo $entry_date_added; ?>							
						</td>
						<td>
							<input type="text" name="date_added" value="<?php echo $date_added; ?>" class="datetime" />
						</td>
					</tr>
					
				</table>
			</div>
		</form>
	</div>
</div>
</div>
<script src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script>$('.datetime').datetimepicker({showSecond: true,dateFormat: 'yy-mm-dd',timeFormat: 'hh:mm:ss'});</script>
<script>jQuery('.radio-star').hover(function(){var stars = jQuery(this).val();jQuery('.star-div').html('<span class="icstars-'+ stars +'"></span><i id="settext-'+ stars +'"></i>');},function(){var start = jQuery('input:radio[name=rating]:checked').val();if(typeof  start == 'undefined' ){start = 0;}jQuery('.star-div').html('<span class="icstars-'+ start +'"></span><i id="settext-'+ start +'"></i>');});jQuery('.radio-star').click(function(){jQuery('.radio-star').each(function(){jQuery(this).attr( 'checked', false );});jQuery(this).attr( 'checked', true );jQuery(this).each(function(){if(jQuery(this).attr("checked")=="checked"){var s = jQuery(this).val();jQuery('.star-div').stop().html('<span class="icstars-'+ s +'"></span><i id="settext-'+ s +'"></i>');}});});</script>
<script>
	$(document).ready( function(){
		$(".cb-enable").click(function(){
			var parent = $(this).parents('.switch');
			$('.cb-disable',parent).removeClass('selected');
			$(this).addClass('selected');
			$('.checkbox',parent).attr('checked', true);
		});
		$(".cb-disable").click(function(){
			var parent = $(this).parents('.switch');
			$('.cb-enable',parent).removeClass('selected');
			$(this).addClass('selected');
			$('.checkbox',parent).attr('checked', false);
		});
	});
</script>
<style>
	#settext-1:before{content: "Ужасно";margin-left: 7px;}
	#settext-2:before{content: "Плохо";margin-left: 7px;}
	#settext-3:before{content: "Средне";margin-left: 7px;}
	#settext-4:before{content: "Хорошо";margin-left: 7px;}
	#settext-5:before{content: "Отлично";margin-left: 7px;}	
	.radio-div{width:77px;float:left;margin-left:5px;}
	.radio-div input[type=radio]{position:relative;margin:0 0 0 -4px;padding:0;width:16px;height: 17px;opacity:0;z-index:2;cursor:pointer;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter: alpha(opacity=0);}
	.star-div{height:17px;float:left;margin:1px 0 0 -84px;}
	.star-div span{width:77px;height:17px;display:inline-block;background:url(view/image/stars.png) no-repeat;}
	span.icstars-0{background-position:0 0}
	span.icstars-1{background-position:0 -16px}
	span.icstars-2{background-position:0 -32px}
	span.icstars-3{background-position:0 -48px}
	span.icstars-4{background-position:0 -64px}
	span.icstars-5{background-position:0 -80px}
	.switch input[type='radio']{display: none;}
	.cb-enable, .cb-disable, .cb-enable span, .cb-disable span {background: url(view/image/switch.gif) repeat-x; display: block; float: left; }
	.cb-enable span, .cb-disable span { line-height: 30px; display: block; background-repeat: no-repeat; font-weight: bold; }
	.cb-enable span { background-position: left -90px; padding: 0 10px; }
	.cb-disable span { background-position: right -180px;padding: 0 10px; }
	.cb-disable.selected { background-position: 0 -30px; }
	.cb-disable.selected span { background-position: right -210px; color: #fff; }
	.cb-enable.selected { background-position: 0 -60px; }
	.cb-enable.selected span { background-position: left -150px; color: #fff; }
	.switch label, h2 {cursor:pointer;-moz-user-select:-moz-none;-o-user-select:none;-khtml-user-select:none;-webkit-user-select:none;}
</style>
<script type="text/javascript"><!--
	$('#tabs a').tabs();	
	$('#languages a').tabs();
//--></script> 
<script type="text/javascript"><!--
	$('input[name=\'product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product\']').val(ui.item.label);
			$('input[name=\'product_id\']').val(ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script> 
<?php echo $footer; ?>	