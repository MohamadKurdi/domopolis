<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<a href="<?php echo $extention_setting_link;?>" class="btn btn-default">Настройки</a>	   
        <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" class="btn btn-default"><?php echo $button_cancel; ?></a>
		</div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
	   <div class="alert alert-info loader_upper_block">
		<span id="shift-info"></span>  
		</div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
         
          <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
		  
		    <div class="form-group"> 
                <div class="col-sm-9">
				 <h2>Перегляд поточного замовлення</h2>
                  <div id="order_info"></div>
                </div> 
                <div class="col-sm-3">
				 <h2>Перегляд можливого чеку</h2>
                  <div id="receipt_html_preview"></div>
				  
				  <div class="text-center">				  
				   <button data-create="<?php echo $create; ?>" type="button"  style="    width: 80%;" class="btn   btn-primary button mini create-receipt " ><i class="fa fa-plus"></i> Створити чек</button>
					</div>
		
		
				 
                </div>
              </div>
			  
		   
		  
          </div>
  
        </form>
      </div>
    </div>
  </div> 
  <script type="text/javascript"><!--
  
$('#shift-info').load('index.php?route=sale/receipt/shift&token=<?php echo $token;?>');
  
  
$('#order_info').load('index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?> #content .vtabs-content  ');

 $('#receipt_html_preview').load('index.php?route=sale/receipt/receipt_html_preview&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

var loader = '<div class="loader_block"><img class="loader" src="view/image/loader-search.gif" alt="Загрузка..." title="Загрузка..." /></div>';

  $(document).delegate('button.create-receipt', 'click', function() {
	 
	 $.ajax({
		url: $(this).attr('data-create'),
		dataType: 'json',
		beforeSend: function() {
			$('button.create-receipt').button('loading'); 
			$('button.create-receipt').attr('disabled','disabled'); 
			$(this).parent().append(loader) ;
			$('.loader_block').remove();
			$('.alert').remove();
		},
		complete: function() {
			$('button.create-receipt').button('reset');
			$('button.create-receipt').removeAttr('disabled');
			$('.loader_block').remove();
		},
		success: function(json) { 
			 console.log(json);
			if(json['error']){
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					animate2up();
				}
				
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				animate2up();
			}
 
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			$('.loader_block').remove();
		}
	}); 
	event.preventDefault();
});
 
 function animate2up(){ 
 $('html, body').animate({ scrollTop: 0 }, 'slow');
}
$(document).delegate('#content .container-fluid  button.close', 'click', function() {
   $(this).parent().remove();
});
//--></script> 


</div>
<style>
#receipt_settings_preview .form-group{
	display:none;
}
#receipt_settings_preview .form-group.preview{
	display:block;
}
#order_info .tab-content{
	display:none;
}
#order_info .nav.nav-tabs{
	display:none;
}
</style>

<link type="text/css" href="view/stylesheet/checkbox/bootstrap.css" rel="stylesheet" /> 
<?php echo $footer; ?>
