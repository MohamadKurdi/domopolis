<?php
$this->language->load('module/mattimeotheme');
$category_details = $this->language->get('category_details');
$button_quick = $this->language->get('entry_quickview');
?>


<div class="product-filter">
	<div class="display"><b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><?php echo $text_grid; ?></a></div>
	<div class="limit"><b><?php echo $text_limit; ?></b>
		<select onchange="location = this.value;">
			<?php foreach($limits as $limits) { ?>
			<?php if($limits['value'] == $limit) { ?>
				<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
	<div class="sort"><b><?php echo $text_sort; ?></b>
		<select onchange="location = this.value;">
			<?php foreach($sorts as $sorts) { ?>
			<?php if($sorts['value'] == $sort . '-' . $order) { ?>
				<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
</div>
<div class="product-list">

</div>
<div class="pagination"></div>
<script type="text/javascript"><!--
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
				html  = '<div class="left">';
			
			 <?php if  ($this->config->get('img_additional2') == '1') { ?> 
			html += '<div class="owl-addimagecat owl-carousel">';
		   <?php } ?>
			
			var image = $(element).find('.image').html();
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			<?php if  ($this->config->get('img_additional2') == '1') { 
			for ($key = 0; $key < 6; $key++) { ?>
			var image2 = $(element).find('.image<?php echo $key;?>').html();
			if (image2 != null) { 
			html += ' <div class="image image<?php echo $key;?>">' + image2 + '</div>';
			
			}
			<?php } ?>
			
			html += '</div>';
			<?php } ?> 
			
			html += '</div>';
						
			html += '<div class="centr">';

			html += ' <div class="name">' + $(element).find('.name').html() + '</div>';

			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
			html += '</div>';
				
		    html += '<div class="right">';
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			
            html += ' <div class="hover_but">';
			<?php if ($this->config->get('show_wishlist') == '1')  { ?>
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			<?php } ?>
			<?php if ($this->config->get('show_compare') == '1')  { ?>
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			<?php } ?>
		    <?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
             html += ' <div class="quickviewbutton">' + $(element).find('.quickviewbutton').html() + '</div>';
            <?php } ?>
			html += '</div>'; 
			
			html += '</div>';
						
			$(element).html(html);
		});	
		 $(".owl-addimagecat").owlCarousel({ navigation : true, pagination : false, singleItem : true });	
		
		$('.display').html('<span class="iconlist"></span> <a onclick="display(\'grid\');" class="icongrid"></a>');
		
		$.totalStorage('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			html += '<div class=img_but>';
		   <?php if  ($this->config->get('img_additional2') == '1') { ?> 
			html += '<div class="owl-addimagecat owl-carousel">';
		   <?php } ?>
			
			var image = $(element).find('.image').html();
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			<?php if  ($this->config->get('img_additional2') == '1') { 
			for ($key = 0; $key < 6; $key++) { ?>
			var image2 = $(element).find('.image<?php echo $key;?>').html();
			if (image2 != null) { 
			html += ' <div class="image image<?php echo $key;?>">' + image2 + '</div>';
			
			}
			<?php } ?>
			
			html += '</div>';
			<?php } ?>
			
            html += ' <div class="hover_but">';
			<?php if ($this->config->get('show_wishlist') == '1')  { ?>
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			<?php } ?>
			<?php if ($this->config->get('show_compare') == '1')  { ?>
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			<?php } ?>
		    <?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
             html += ' <div class="quickviewbutton">' + $(element).find('.quickviewbutton').html() + '</div>';
            <?php } ?>
			html += '</div>'; 
			html += '</div>'; 
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';	
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
			
			
			
			$(element).html(html);
		});	
		 $(".owl-addimagecat").owlCarousel({ navigation : true, pagination : false, singleItem : true });		
		$('.display').html('<a onclick="display(\'list\');" class="iconlist"></a> <span class="icongrid"></span>');
		
		$.totalStorage('display', 'grid');
	}
}

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('list');
}
//--></script> 
