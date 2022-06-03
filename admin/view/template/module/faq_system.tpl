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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
	<div id="tabs" class="htabs">		
		<a id="fq-category" href="#tab-category"><?php echo $tab_category; ?></a>
		<a id="fq-question" href="#tab-question"><?php echo $tab_question; ?></a>
		<a id="fq-setting" href="#tab-setting"><?php echo $tab_setting; ?></a>
		<a id="fq-proposal" href="#tab-proposal"><?php echo $tab_proposal; ?></a>
		<div class="clr"></div>
	</div>
  <div class="th_style"></div>
    <div id="tab-setting">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		  <div class="attention"><?php echo $text_info_save; ?></div>
		  <table class="form" id="acr">			
			<tr>
				<td class="left"><?php echo $entry_category_initial_status; ?></td>
				<td class="left"><select name="faq_system_category_initial_status">
				<?php if ($faq_system_category_initial_status) {  ?>
					<option value="1" selected="selected"><?php echo $text_expanded; ?></option>			
					<option value="0"><?php echo $text_collapsed; ?></option>			
				<?php } else { ?>
					<option value="1"><?php echo $text_expanded; ?></option>			
					<option value="0" selected="selected"><?php echo $text_collapsed; ?></option>
				<?php } ?>
				</select></td>
			</tr>
			<tr>
				<td class="left"><?php echo $entry_allow_propose; ?></td>
				<td class="left"><select name="faq_system_allow_propose">
				<?php if ($faq_system_allow_propose) {  ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>			
					<option value="0"><?php echo $text_disabled; ?></option>			
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>			
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
				</select></td>
			</tr>
		  </table>
		</form>
	</div>

	<div id="tab-category">
		<div id="category-list"></div>
		<div id="dialog-category-form" style="background: #FFF;"></div>
	</div>
	
	<div id="tab-question">
		<div id="question-list"></div>
		<div id="dialog-question-form" style="background: #FFF;"></div>
	</div>
	
	<div id="tab-proposal">
		<div id="proposal-list"></div>
	</div>

	
  </div>
</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--	
$('#tabs a').tabs();

$('#category-list').load('index.php?route=module/faq_system/getCategories&token=<?php echo $token; ?>');
$('#question-list').load('index.php?route=module/faq_system/getQuestions&token=<?php echo $token; ?>');
$('#proposal-list').load('index.php?route=module/faq_system/getQuestions&status=0&token=<?php echo $token; ?>');


function getCategoryForm(category_id){
	category_id = typeof(category_id) != 'undefined' ? category_id : 0;
	
	$.ajax({
		url: 'index.php?route=module/faq_system/getCategoryForm&token=<?php echo $token; ?>',
		data: (category_id > 0)? 'category_id='+ category_id : '',
		dataType: 'json',
		success: function(json){
			$('#dialog-category-form').html(json['output']);
		}
	});
	
	$('#dialog-category-form').dialog({
		title: '<?php echo $category_form_title; ?>',
		close: function (event, ui) {
		},	
		width: 600,
		height: 500,
		resizable: true
	});
}

function deleteCategories(){
	if ( $('#category-list input[type=\'checkbox\']:checked').length ) {
		$.ajax({
			type: 'POST',
			url: 'index.php?route=module/faq_system/deleteCategories&token=<?php echo $token; ?>',
			data: $('#category-list input[type=\'checkbox\']:checked'),
			dataType: 'json',
			success: function(json){
				$('.warning').remove();
				$('.success').remove();
			
				if (json['error']){
					if (json['error']['warning']){
						$('#category-list-buttons').after('<div class="warning">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}
				}
				
				if (json['success']){
					//$('#category-list-buttons').after('<div class="success">' + json['success'] + '</div>');
					//$('.success').fadeIn('slow');
					
					$('#category-list').load('index.php?route=module/faq_system/getCategories&token=<?php echo $token; ?>');
				}
			}
		});
	}
}

function getQuestionForm(question_id){
	question_id = typeof(question_id) != 'undefined' ? question_id : 0;
	
	$.ajax({
		url: 'index.php?route=module/faq_system/getQuestionForm&token=<?php echo $token; ?>',
		data: (question_id > 0)? 'question_id='+ question_id : '',
		dataType: 'json',
		success: function(json){
			$('#dialog-question-form').html(json['output']);
		}
	});
	
	$('#dialog-question-form').dialog({
		title: '<?php echo $question_form_title; ?>',
		close: function (event, ui) {
		},	
		width: 1100,
		height: 700,
		resizable: true
	});
}

function deleteQuestions(type){
	var selector = "";
	
	if (type == 'validated'){
		selector = '#question-list';
	} else {
		selector = '#proposal-list';
	}
	
	if ( $(selector +' input[type=\'checkbox\']:checked').length ) {
		$.ajax({
			type: 'POST',
			url: 'index.php?route=module/faq_system/deleteQuestions&token=<?php echo $token; ?>',
			data: $(selector +' input[type=\'checkbox\']:checked'),
			dataType: 'json',
			success: function(json){
				$('.warning').remove();
				$('.success').remove();
			
				if (json['error']){
					if (json['error']['warning']){
						$('#question-list-buttons').after('<div class="warning">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}
				}
				
				if (json['success']){
					//$('#question-list-buttons').after('<div class="success">' + json['success'] + '</div>');
					//$('.success').fadeIn('slow');
					
					$('#question-list').load('index.php?route=module/faq_system/getQuestions&token=<?php echo $token; ?>');
					$('#proposal-list').load('index.php?route=module/faq_system/getQuestions&status=0&token=<?php echo $token; ?>');
				}
			}
		});
	}
}
//--></script> 

<?php echo $footer; ?>