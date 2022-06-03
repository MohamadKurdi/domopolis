<?php echo $header; ?>
<div id="content">
  
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <div id="notification">
    <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
  </div>
    
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
      <table id="fields_attributes" class="list">
        <thead>
          <tr>            
            <td class="left" width="250"></td>              
            <?php foreach($customer_groups as $group): ?>
              <td class="left"><?php echo $group['name']; ?></td>
            <?php endforeach; ?>
          </tr>
        </thead>
        
        <tbody>
         
          <?php foreach($categories as $category): ?>
          
            <tr>
              <td class="left"><?php echo $category['name']; ?></td>              
              
              <?php foreach($customer_groups as $group): ?>
                <td class="left">
                  +/-<br/><input style="width: 100px;" type="text" name="group_price[<?php echo $category['category_id']; ?>][<?php echo $group['customer_group_id']; ?>]" 
                       value="<?php echo isset($categories_price[$category['category_id']][$group['customer_group_id']]) 
                         ? $categories_price[$category['category_id']][$group['customer_group_id']]['price'] : '';?>" class="price-value"> 
                  <select style="margin-top: 5px;" name="group_price_type[<?php echo $category['category_id']; ?>][<?php echo $group['customer_group_id']; ?>]"  class="price-type">
                    <option value="1" <?php echo (isset($categories_price[$category['category_id']][$group['customer_group_id']]) 
                            && $categories_price[$category['category_id']][$group['customer_group_id']]['type'] == 1) 
                         ? "selected='selected'" : '';?>>%</option>
                    <option value="2" <?php echo (isset($categories_price[$category['category_id']][$group['customer_group_id']]) 
                            && $categories_price[$category['category_id']][$group['customer_group_id']]['type'] == 2) 
                         ? "selected='selected'" : '';?>><?php echo $currency; ?></option>
                  </select>  
                  
                  <?php if($group['customer_group_id'] == 1): //default group ?>                  
                    <a class="refresh-price" data-category_id="<?php echo $category['category_id']; ?>" title="<?php echo $refresh_link_title;?>">
                      <img src="view/image/filemanager/refresh.png">
                    </a>                  
                  <?php endif; ?>
                  
                </td>
              <?php endforeach; ?>
              
            </tr>
                      
          <?php endforeach; ?>
            
        </tbody>
      </table>        
      
      </form>  
            
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$(document).ready(function(){

  $('a.refresh-price').click(function(){
    
    if (!confirm('<?php echo $refresh_link_confirm; ?>')) {
      return false;
    } 
    
    var category_id = $(this).data('category_id');
    var price_value = $('.price-value', $(this).parent()).val();
    var price_type  = $('.price-type', $(this).parent()).val();
    
    $.ajax({
      url: 'index.php?route=module/group_price/updateDbPrice&token=<?php echo $token; ?>&category_id=' + encodeURIComponent(category_id) + '&price_value=' + encodeURIComponent(price_value) + '&price_type=' + encodeURIComponent(price_type),
      dataType: 'json',
      success: function(json) {
        $('.success, .error').remove();

        if (json['error']) {
          $('#notification').html('<div class="warning" style="display: none;">' + json['error'] + '</div>');
          $('.warning').fadeIn('slow');          
        } 

        if (json['success']) {
          $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '</div>');
          $('.success').fadeIn('slow');
        }	
      }
    });    
    
    return false;
    
  });
});
</script>