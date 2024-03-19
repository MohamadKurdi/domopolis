<?php echo $header; ?>
<?php echo $column_left; ?>

<style>
	.accordion_body ul{
		padding-left: 20px;
		list-style-type: disc;
	}
	.faq_main_wrap{
		display: grid;
		grid-template-columns: 1fr 517px;
		gap: 40px;
	}
	.faq_main_wrap .column_right{
		display: flex;
		flex-direction: column;
	}
	#content-faq h2 a{
		margin-top: 10px;
		margin-bottom: 20px;
		display: block;
		font-family: 'Unbounded', sans-serif;
		font-weight: 600;
		font-size: 16px;
		line-height: 20px;
		color: #404345;

	}
	#content-faq .faq-category-content{
		margin-bottom: 20px;
		background: transparent;
		padding: 0;
	}
	#content-faq .accordion__item{
		margin-bottom: 8px;
	}
	#content-faq .accordion__item .accordion__header{
		background: #E9EBF1;
		border-radius: 12px;
		padding: 15px 31px 15px 15px;
	
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-left: 1px solid transparent;
		border-right: 1px solid transparent;
		border-top: 1px solid transparent;
		position: relative;
		cursor: pointer;
		font-size: 16px;
		line-height: 19px;
		color: #696F74;	
		transition: background-color 0.2s ease-out;
	}
	#content-faq .accordion__item:not(.accordion__item_show) .accordion__header:hover{
		background: #D5D7DD;
	}
	#content-faq .accordion__item.accordion__item_slidedown .accordion__header,
	#content-faq .accordion__item.accordion__item_show .accordion__header{
		border-radius: 12px 12px 0 0;
		border-color:#DDE1E4;
		background: #FFFFFF;
	}
	#content-faq .accordion__item.accordion__item_slidedown .accordion__header .plusminus,
	#content-faq .accordion__item.accordion__item_show .accordion__header .plusminus{
		transform: rotate(180deg);
	}
	#content-faq .accordion__item.accordion__item_show .accordion__header .plusminus path{
		stroke: #EB3274;
	}
	#content-faq .accordion__item .accordion__header .plusminus{
		position: unset;
		transition: .3s ease-in-out;
		display: flex;
	}
	#content-faq .accordion__item .accordion__body .accordion_content{
		font-weight: 400;
		font-size: 14px;
		line-height: 17px;
		color: #696F74;
		border-left: 1px solid #DDE1E4;
		border-right: 1px solid #DDE1E4;
		border-bottom: 1px solid #DDE1E4;
		border-radius: 0 0 12px 12px;
		background: #fff;
		padding: 15px;
	}
	#content-faq .accordion__item .accordion__body .accordion_content ul{
		margin-top: 10px;
		margin-bottom: 10px;
		padding-left: 20px;
		list-style-type: disc;
	}
	#content-faq .accordion__item .accordion__body .accordion_content ul li{
		margin-bottom: 4px;
	}
	#content-faq .accordion__item .accordion__header::before{
		content:'';
		position: absolute;
		background: #EFF1F2;
		width: 0;
		height: 1px;
		opacity: 0;
		left: 0;
		bottom: 0;
		right: 0;
		margin: 0 auto;

	}
	#content-faq .accordion__item.accordion__item_show .accordion__header::before{
		width: calc(100% - 30px);
		opacity: 1;
	}

	.accordion__item:not(.accordion__item_show) .accordion__body {
      display: none;
    }

    footer{
    	margin-top: 48px;
    }

    .faq_main_wrap .column_right #footer_app_google_play{
    	background: #FFFFFF;
		border-radius: 12px;
		margin-top: 50px !important;
		height: 470px;
		min-height: 470px;
		overflow: hidden;
		margin-bottom: 60px !important;
    } 
    .faq_main_wrap .column_right #footer_app_google_play .wrap{
    	padding: 20px 24px;
    	align-items: start;
    }
    .faq_main_wrap .column_right #footer_app_google_play .content{
    	padding-left: 0;
    }
    #footer_app_google_play .content .text{
     	font-weight: 600;
		font-size: 22px;
		line-height: 27px;
		color: #121415;
    }
    .faq_main_wrap #footer_app_google_play .bg{
    	bottom: -205px;
    	height: 496px;
    }
    .faq_main_wrap .column_right #callback-module{
    	background-color: #BFEA43;
		border-radius: 12px;
		padding: 20px 24px;
		background-position: center 70px;
		background-size: contain;
		background-repeat: no-repeat;
		background-image: url(/catalog/view/theme/dp/img/callback_bg.png);
		height: 100%;
    }
    .faq_main_wrap .column_right #callback-module .contact-phone label,
    .faq_main_wrap .column_right #callback-module .contact-name,
    .faq_main_wrap .column_right #callback-module .contact-email,
    .faq_main_wrap .column_right #callback-module .contact-textarea,
    .faq_main_wrap .column_right #callback-module .left-modal{
    	display: none;
    }
    .faq_main_wrap .column_right #callback-module h3{
    	font-family: 'Unbounded', sans-serif;
    	font-weight: 500;
		font-size: 22px;
		line-height: 27px;
		color: #121415;
		margin-bottom: 9px;
    }
    .faq_main_wrap .column_right #callback-module  #contact-body,
    .faq_main_wrap .column_right #callback-module .message{
    	font-weight: 500;
		font-size: 16px;
		line-height: 19px;
		color: #696F74;
		margin-bottom: 19px;
		display: block;
    }
    .faq_main_wrap .column_right #callback-module .contact-phone input{
    	background: #EFF1F2;
		border-radius: 8px;
		font-weight: 500;
		font-size: 14px;
		line-height: 17px;
		color: #888F97;
		padding: 17px;
		margin-bottom: 18px;
		display: block;
		width: 100%;
    }
    .faq_main_wrap .column_right #callback-module #contact-send{
    	background: #FFFFFF;
		border-radius: 36px;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		font-weight: 500;
		font-size: 16px;
		line-height: 19px;
		color: #121415;
		padding: 16px;
		margin: 0;
    }
    @media screen and (max-width: 560px) {
    	.faq_main_wrap{
    		display: flex;
    		flex-direction: column;
    	}
    	.faq_main_wrap .column_right #callback-module h3{
    		font-size: 20px;
			line-height: 25px;	
			margin-bottom: 11px;
    	}
    	.faq_main_wrap .column_right #callback-module{
    		padding: 20px 20px 320px;
			background-position: center bottom -71px;
			background-size: 100%;
    	}
    }
</style>


<section id="content-faq">
	<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
	<div class="wrap">
		<?php echo $content_top; ?>

		<div class="faq_main_wrap">
			<div class="faq_wrap">
				
			
			  	<?php if ($allow_propose) { ?>
				    <div class="attention"><?php echo $text_info; ?></div>
					
					<div id="faq-propose-dialog" title="<?php echo $text_propose; ?>">
						<table class="form" id="form-propose">      
							<tr>
							  	<td><span class="required">*</span> <?php echo $entry_question; ?></td>
							  	<td><textarea name="question" id="faq-question" cols="60" rows="6"></textarea></td>
							</tr>
							<tr>
							  	<td><span class="required">*</span> <?php echo $entry_captcha; ?></td>
							  	<td><input type="text" name="captcha" id="faq-captcha" value="" /><br />
								  	<img src="index.php?route=product/product/captcha" alt="" id="faq-captcha-image" />
							  	</td>
							</tr>
						</table>	
						<div class="buttons">
						  	<div class="right">
								<input type="submit" class="button" value="<?php echo $button_save_dialog; ?>" id="faq-save-dialog">
						  	</div>
						</div>
					</div>
			  	<?php } ?>
			  
			  	<?php if ($faq) { ?>
					<?php foreach($faq as $category) { ?>

						<?php if (!$single) { ?>
							<h2><a href="<?php echo $category['href']; ?>" title="<?php echo $category['category_name']; ?>"><?php echo $category['category_name']; ?></a></h2>
						<?php } ?>
				
						<div id="faq-cat-content-<?php echo $category['category_id']; ?>" class="faq-category-content accordion">
							<?php foreach($category['questions'] as $qa_info){ ?>
							 	<div class="accordion__item">
							      	<div id="faq-question-<?php echo $qa_info['question_id']; ?>" class="accordion__header">
								       <?php echo $qa_info['question']; ?>
								       <span class="plusminus">
											<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 1L5 5L9 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
							      	</div>
							      	<div id="faq-answer-<?php echo $qa_info['question_id']; ?>" class="accordion__body">
							        	<div class="accordion_content">
							          		<?php echo $qa_info['answer']; ?>
							        	</div>
							      	</div>
								</div>
							<?php } ?>
						</div>

					<?php } ?>
		  		<?php } ?>
		  	</div>
		  	<div class="column_right">
		  		<div id="callback-module"></div>
		  		<?php echo $column_right; ?>
		  	</div>
	  	</div>
	  	
	  	<?php echo $content_bottom; ?>

  	</div>
</section>
<script>
    class ItcAccordion {
      	constructor(target, config) {
	        this._el = typeof target === 'string' ? document.querySelector(target) : target;
	        const defaultConfig = {
	          alwaysOpen: true,
	          duration: 350
	        };
	        this._config = Object.assign(defaultConfig, config);
	        this.addEventListener();
      	}

      	addEventListener() {
	        this._el.addEventListener('click', (e) => {
	          	const elHeader = e.target.closest('.accordion__header');
	          	if (!elHeader) {
	            	return;
	          	}
	          	if (!this._config.alwaysOpen) {
	            	const elOpenItem = this._el.querySelector('.accordion__item_show');
	            	if (elOpenItem) {
	              		elOpenItem !== elHeader.parentElement ? this.toggle(elOpenItem) : null;
	            	}
	          	}
	          	this.toggle(elHeader.parentElement);
	        });
      	}

      	show(el) {
	        const elBody = el.querySelector('.accordion__body');
	        if (elBody.classList.contains('collapsing') || el.classList.contains('accordion__item_show')) {
          		return;
        	}
	        elBody.style['display'] = 'block';
	        const height = elBody.offsetHeight;
	        elBody.style['height'] = 0;
	        elBody.style['overflow'] = 'hidden';
	        elBody.style['transition'] = `height ${this._config.duration}ms ease`;
	        elBody.classList.add('collapsing');
	        el.classList.add('accordion__item_slidedown');
	        elBody.offsetHeight;
	        elBody.style['height'] = `${height}px`;
	        window.setTimeout(() => {
				elBody.classList.remove('collapsing');
				el.classList.remove('accordion__item_slidedown');
				elBody.classList.add('collapse');
				el.classList.add('accordion__item_show');
				elBody.style['display'] = '';
				elBody.style['height'] = '';
				elBody.style['transition'] = '';
				elBody.style['overflow'] = '';
        	}, this._config.duration);
      	}

      	hide(el) {
        	const elBody = el.querySelector('.accordion__body');
        	if (elBody.classList.contains('collapsing') || !el.classList.contains('accordion__item_show')) {
          		return;
        	}
			elBody.style['height'] = `${elBody.offsetHeight}px`;
			elBody.offsetHeight;
			elBody.style['display'] = 'block';
			elBody.style['height'] = 0;
			elBody.style['overflow'] = 'hidden';
			elBody.style['transition'] = `height ${this._config.duration}ms ease`;
			elBody.classList.remove('collapse');
			el.classList.remove('accordion__item_show');
			elBody.classList.add('collapsing');
			window.setTimeout(() => {
				elBody.classList.remove('collapsing');
				elBody.classList.add('collapse');
				elBody.style['display'] = '';
				elBody.style['height'] = '';
				elBody.style['transition'] = '';
				elBody.style['overflow'] = '';
        	}, this._config.duration);
      	}
      	toggle(el) {
        	el.classList.contains('accordion__item_show') ? this.hide(el) : this.show(el);
      	}
    }
</script>
<script>
	setTimeout(function(){
		$('#footer_app_google_play').prependTo('#content-faq .column_right');
	 	$("#callback-module").load("/index.php?route=module/callback");
	}, 100)
	
	$('#faq-propose').bind('click', function(){
		$('.warning').remove();
		$('.success').remove();

		$('#faq-propose-dialog').dialog({
			modal: true,
			width: 500,
			height: 390
		});
	});

	$('#faq-save-dialog').bind('click', function(){
		$.ajax({
			type: 'POST',
			url : 'index.php?route=information/faq_system/propose',
			data: $('#form-propose textarea, #form-propose input[type=\'text\']'),
			dataType: 'json',
			success: function(json){
				$('.warning').remove();
				$('.success').remove();
				
				if (json['error']){
					$('#form-propose').before('<div class="warning">' + json['error'] + '</div>');
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']){
					$('#form-propose').before('<div class="success">' + json['success'] + '</div>');
					$('.success').fadeIn('slow');
					
					$('#faq-captcha-image').attr('src', 'index.php?route=product/product/captcha&random=' + Math.random());
					$('#faq-question').val('');
					$('#faq-captcha').val('');
					
					setTimeout(function() {
						$('#faq-propose-dialog').dialog("close");
					}, 2000);
				}
			}		
		});
	});

    const accordionItmem = document.querySelectorAll(".accordion");

	accordionItmem.forEach((userItem) => {
	    new ItcAccordion(this, {
	      alwaysOpen: true
	    });
	});


</script>  
  
<?php echo $footer; ?>