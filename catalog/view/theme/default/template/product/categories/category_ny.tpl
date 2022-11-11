<?php
$this->language->load('module/mattimeotheme');
$category_details = $this->language->get('category_details');
$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>



<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" title="<?php echo $breadcrumb['text']; ?>" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a></span>
    <?php } ?>
  </div>

    <div style="float:left;width:100%" class="block_banner_once">
        <h1><?php echo $heading_title; ?></h1>


        <?php if ($categories) { ?>
        <div class="category-list" style="float:left; max-width:200px; width:200px;">
            <?php if (count($categories) <= 5) { ?>
            <ul style="width:100%;">
                <?php foreach ($categories as $category) { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                <?php } ?>
            </ul>
            <?php } else { ?><?php for ($i = 0; $i < count($categories);) { ?>
            <ul>
                <?php $j = $i + ceil(count($categories) / 4); ?><?php for (; $i < $j; $i++) { ?><?php if (isset($categories[$i])) { ?>
                <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
                <?php } ?><?php } ?>
            </ul>
            <?php } ?><?php } ?>
        </div>
        <?php } ?>

        <div style="float:right;" class="banner-list">
            <?php echo $content_top; ?>
        </div>
    </div>
  
  <?php if ($products) { ?>
  <div class="product-filter">
   
    <!--<div class="display"><b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><?php echo $text_grid; ?></a></div>-->
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;" class="select1">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort">
<? /*	
	  <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
			<span class="sortcurrent" ><?php echo $sorts['text']; ?></span>
		 <?php } else { ?>
			<span onclick="location = <?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></span>
        <?php } ?>
        <?php } ?>
*/?>	
      <select onchange="location = this.value;" class="select1">
       <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div> 
   <?php if ($this->config->get('show_compare') == '1')  { ?>
    <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><div></div><?php echo $text_compare; ?></a></div>
    <?php } ?>
  </div>
   

  <div class="product-grid">
    <?php foreach ($products as $product) { ?>
   
       <div class="item">
       
       <?php if ($product['thumb']) { ?> 
       
           <?php if  ($this->config->get('img_additional2') == '1') { ?> 
               <!--Additional images--> 
           <div class="owl-addimagecat owl-carousel"> <?php } ?>
               
         <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" ></a></div>
           
           <?php if ((isset($product['dop_img'])) && ($this->config->get('img_additional2') == '1')) { ?> 
           <?php foreach ($product['dop_img'] as $key => $img) { ?>
               <div class="image image<?php echo $key;?>"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $img;?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
           <?php } ?>
           <?php } ?>
           
           <?php if  ($this->config->get('img_additional2') == '1') { ?>  
            <!--end additional images--> 
            </div> <?php } ?>
            
           <?php } ?> 
     
     
     <div class="name">
      <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
      <div class="description"><?php echo $product['description']; ?></div>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>
        <?php echo $product['price']; ?>
        <?php } else { ?>
     
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
            <?php if (isset($product['saving'])) { ?>
              <div  class="savemoney">- <?php echo $product['saving']; ?>%</div>
           <?php } ?>
        <?php } ?>
        <?php if ($product['tax']) { ?>
        <br />
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <?php } ?>
      </div>
      <?php } ?>
     
      <div class="cart">
        <input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
      </div>
     <?php if ($this->config->get('config_review_status')) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
      <?php } ?>
               <div class="hover_but">
               <?php if ($this->config->get('show_wishlist') == '1')  { ?>
               <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>"></a></div>
               <?php } ?>
               <?php if ($this->config->get('show_compare') == '1')  { ?>
               <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>"></a></div>
               <?php } ?>
               <?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
               <div class="quickviewbutton"><a class='quickview' href="<?php echo $product['quickview']; ?>" title="<?php echo $button_quick; ?>"></a></div>
               <?php } ?> <br />
			   
			    <div class="cart">
        <input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
      </div>
			   
               </div>
      
      
    </div>
    <?php } ?>

  </div>
  
  
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  </div>
  <div class="cont_bottom">
	    <?php if ($description) { ?>
	<div class="category-info">
		<?php if ($description) { ?>
		<?php echo $description; ?>
		<?php } ?>
	</div>
	<?php } ?>
  
  </div>
  <?php echo $content_bottom; ?>
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
				/*html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';	*/
			html += '</div>'; 
			html += '</div>'; 
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
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
	display('grid');
}
//--></script>
  

<?php echo $footer; ?>