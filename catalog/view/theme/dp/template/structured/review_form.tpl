<?php 
	$form1 = random_int(1, 99);
	$form2 = random_int(100, 199);
	$form3 = random_int(200, 299);
	$form4 = random_int(300, 599);
	$form5 = random_int(400, 499);
 ?>
<form class="product-review <?php echo $product_id; ?>" id="product-review-<?php echo $form4; ?>" method="post" enctype="multipart/form-data">	
	<div class="form-group flex-block">
		<label><?php echo $text_retranslate_72; ?></label>
		<input type="text" name="name" class="form-control"/>
	</div>
	<div class="form-group flex-block" id="review-title">
		<span>Ваша оцінка</span>
	    <div class="review_stars_wrap">
			<div class="review_stars">
				<?php 
					 $star_4 = random_int(1, 99);
					 $star_3 = random_int(100, 199);
					 $star_2 = random_int(200, 299);
					 $star_1 = random_int(300, 599);
					 $star_0 = random_int(400, 499);
				 ?>
				<input id="star-<?php echo($star_4); ?>" class="star-4" type="radio" value="5" name="rating" checked="checked"/>
			    <label title="<?php echo $text_retranslate_67; ?>" for="star-<?php echo($star_4); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_3); ?>" class="star-3" type="radio" value="4" name="rating"/>
			    <label title="<?php echo $text_retranslate_68; ?>" for="star-<?php echo($star_3); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_2); ?>" class="star-2" type="radio" value="3" name="rating"/>
			    <label title="<?php echo $text_retranslate_69; ?>" for="star-<?php echo($star_2); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_1); ?>" class="star-1" type="radio" value="2" name="rating"/>
			    <label title="<?php echo $text_retranslate_70; ?>" for="star-<?php echo($star_1); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_0); ?>" class="star-0" type="radio" value="1"  name="rating"/>
			    <label title="<?php echo $text_retranslate_71; ?>" for="star-<?php echo($star_0); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			</div>
	    </div>
	</div>	
	<div class="form-group flex-block">
		<label><?php echo $text_retranslate_73; ?></label>
		<input type="text" name="good" class="form-control"/>
	</div>
	<div class="form-group flex-block">
		<label><?php echo $text_retranslate_74; ?></label>
		<input type="text" name="bads" class="form-control"/>
	</div>
	<div class="form-group flex-block">
		<label><?php echo $text_retranslate_75; ?></label>
		<textarea name="text" class="form-control" rows="5"></textarea>
	</div>	
	<div class="form-group checkbox" style="display: none;">
		<input type="checkbox" id="bought_this" style="height:0"> <label for="bought_this"><?php echo $text_retranslate_76; ?></label>
	</div>	
	<div class="form-group image-form-rev">
		<div class="left-img-group">
			<i class="far fa-image"></i>
			<div>
				<p><?php echo $text_retranslate_77; ?></p>
				<span><?php echo $text_retranslate_78; ?></span>
			</div>
		</div>
		<div class="right-img-group">
			<input type="file" id="file-<?php echo $form3; ?>" name="add-review-image" class="inputfile" data-multiple-caption="{count}  <?php echo $text_retranslate_81; ?>" accept="image/jpeg,image/png,image/jpg" multiple >
		 	<label for="file-<?php echo $form3; ?>" title="<?php echo $text_retranslate_79; ?>"><span><?php echo $text_retranslate_79; ?></span></label>
		</div>
	</div>
	<div class="form-group-btn">
		<a id="button-review-<?php echo($form1); ?>" class="btn btn-default"><?php echo $text_retranslate_80; ?></a>
		<span class="text">
			Натискаючи кнопку надіслати ви погоджуєтесь з нашими Правилами сервісу та Політикою конфіденційності
		</span>
	</div>
</form>

<script>
$( document ).ready(function() {
	var inputs = document.querySelectorAll('.inputfile');
	Array.prototype.forEach.call(inputs, function(input){
	  var label  = input.nextElementSibling,
	      labelVal = label.innerHTML;
	  input.addEventListener('change', function(e){
	    var fileName = '';
	    if( this.files && this.files.length > 1 )
	      fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
	    else
	      fileName = e.target.value.split( '\\' ).pop();
	    if( fileName )
	      label.querySelector( 'span' ).innerHTML = fileName;
	    else
	      label.innerHTML = labelVal;
	  });
	});

	$('#button-review-<?php echo($form1); ?>').bind('click', function() {
		let __btn = $(this);
		let formData = new FormData($('form#product-review-<?php echo $form4; ?>')[0]);

		$.ajax({
			url: '/index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
			type: 'post',
			dataType: 'json',
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-review-<?php echo($form1); ?>').attr('disabled', true);
				__btn.after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
				
			},
			complete: function(data) {
				$('#button-review-<?php echo($form1); ?>').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(data) {
				if (data['error']) {
					__btn.before('<div class="warning">' + data['error'] + '</div>');
				}	

				if (data['success']) {
					console.log(data['success']);

					__btn.before('<div class="success">' + data['success'] + '</div>');
					
					$('input[name=\'name\']').val('');
					$('textarea[name=\'text\']').val('');
					$('input[name=\'rating\']:checked').attr('checked', '');
					$('input[name=\'good\']').val('');
					$('input[name=\'bads\']').val('');
					$('input[name=\'add-review-image\']').val('');
				}					
				
			}
		});
	});
});	
</script>