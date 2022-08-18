<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<style>
	.accordion_body ul{
		padding-left: 20px;
		list-style-type: disc;
	}
	#content-faq h2 a{
		font-size: 20px;
		margin-top: 10px;
		margin-bottom: 20px;
		display: block;
		font-weight: 500;
	}
	#content-faq .faq-category-content{
		margin-bottom: 20px;
	}
</style>

<section id="content-faq">
	<?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
	<div class="wrap">
	<?php echo $content_top; ?>
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
	
			<div id="faq-cat-content-<?php echo $category['category_id']; ?>" class="faq-category-content">
				<?php foreach($category['questions'] as $qa_info){ ?>
					<div id="faq-question-<?php echo $qa_info['question_id']; ?>" class="accordion_head">
						<span class="plusminus"><i class="fa fa-angle-right"></i></span>
						<span class="head_faq"><?php echo $qa_info['question']; ?></span>
					</div>
					<div id="faq-answer-<?php echo $qa_info['question_id']; ?>" class="accordion_body" style="display: none;">
						<?php echo $qa_info['answer']; ?>
					</div>
				<?php } ?>
			</div>
	<?php } ?>
  <?php } ?>
  
  <?php echo $content_bottom; ?>
  </div>
</section>
<script type="text/javascript">

$(document).ready(function() {
  $(".accordion_head").click(function() {
      if ($('.accordion_body').is(':visible')) {
        $(".accordion_body").slideUp(300);
        $(".plusminus").html('<i class="fa fa-angle-right"></i>');
      }

      if ($(this).next(".accordion_body").is(':visible')) {
        $(this).next(".accordion_body").slideUp(300);
        $(this).children(".plusminus").html('<i class="fa fa-angle-right"></i>');
      } else {
        $(this).next(".accordion_body").slideDown(300);
        $(this).children(".plusminus").html('<i class="fa fa-angle-down"></i>');

      }
  });
  
});




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
</script>  
  
<?php echo $footer; ?>