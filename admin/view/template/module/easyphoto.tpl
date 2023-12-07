<?php //EasyPhoto 3.1 ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Easyphoto</title>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap2/bootstrap-switch.min.css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<style>#footer{display:none!important;}h1{margin-top:0px!important;}.page-header{margin-top:20px!important;}</style>
</head>
<body>
<div id="content">
  <script>
    function hided_setting(selector, selector2 = false){
      $('[name="' + selector + '"]').parent().parent().slideToggle(200);
      if(selector2){
        $('[name="' + selector2 + '"]').parent().parent().slideToggle(200);
      }
    }
  </script>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a id="submit_form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <script>
    $('#submit_form').on('click', function(e){
      $.ajax({
        url: '<?php echo $action; ?>&token=<?php echo $token; ?>',
        type: 'post',
        data: $('#form-easyphoto').serialize(),
        dataType: 'json',
        beforeSend: function(json){
          setTimeout(function(){
            location.reload();
          }, 300);
        }
      });
    });
  </script>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <?php if($easyphoto_key){ ?>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-easyphoto" class="form-horizontal">
            <input type="hidden" value="<?php echo $easyphoto_key; ?>" name="easyphoto_key">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="easyphoto_status" id="input-status" class="form-control">
                  <?php if ($easyphoto_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-easyphoto_direct"><?php echo $entry_direct; ?></label>
              <div class="col-sm-10">
                <input type="text" name="easyphoto_direct" value="<?php echo $easyphoto_direct; ?>" placeholder="easyphoto" id="input-easyphoto_direct" class="form-control" />
              </div>
            </div>
            <?php //3.1 ?>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-easyphoto_separate"><?php echo $entry_separate; ?></label>
              <div class="col-sm-10">
                <input type="text" name="easyphoto_separate" value="<?php echo $easyphoto_separate; ?>" placeholder="<?php echo $separate_placeholder; ?>" id="input-easyphoto_separate" class="form-control" />
              </div>
            </div>
            <?php //3.1 ?>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-easyphoto_main" style="padding-top:0px;"><?php echo $entry_first_main; ?></label>
              <div class="col-sm-10">
                <input type="checkbox" name="easyphoto_main" value="1" <?php if($easyphoto_main){ ?>checked="checked"<?php } ?> id="input-easyphoto_main" class="form-control" />
              </div>
            </div>
  		      <div class="form-group">
              <label class="col-sm-2 control-label" for="input-easyphoto_name" style="padding-top:0px;"><?php echo $entry_photo_name; ?></label>
              <div class="col-sm-10">
                <input type="checkbox" onchange="hided_setting('easyphoto_language', 'easyphoto_from')" name="easyphoto_name" value="1" <?php if($easyphoto_name){ ?>checked="checked"<?php } ?> id="input-easyphoto_name" class="form-control" />
              </div>
            </div>

            <?php //3.1 ?>
            <div class="form-group" <?php if(!$easyphoto_name){ ?>style="display:none;"<?php } ?>>
             <label class="col-sm-2 control-label" for="input-easyphoto_from"><?php echo $entry_from; ?></label>
             <div class="col-sm-10">
              <select name="easyphoto_from" id="input-easyphoto_from" class="form-control">
                <?php foreach ($fields as $field) { ?>
                <?php if ($field == $easyphoto_from) { ?>
                <option value="<?php echo $field; ?>" selected="selected"><?php echo $field; ?></option>
                <?php } else { ?>
                <option value="<?php echo $field; ?>"><?php echo $field; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
             </div>
            </div>
            <?php //3.1 ?>

            <div class="form-group" <?php if(!$easyphoto_name){ ?>style="display:none;"<?php } ?>>
             <label class="col-sm-2 control-label" for="input-easyphoto_language"><?php echo $entry_language; ?></label>
             <div class="col-sm-10">
              <select name="easyphoto_language" id="input-easyphoto_language" class="form-control">
                <?php foreach ($languages as $language) { ?>
                <?php if ($language['language_id'] == $easyphoto_language) { ?>
                <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
             </div>
            </div>
  		      <div class="form-group">
              <label class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <?php echo $text_manual; ?>
  		        </div>
            </div>
            <?php //3.1 ?>
            <div class="form-group">
              <div class="col-sm-12">
                <?php echo $more_info; ?>
  		        </div>
            </div>
            <?php //3.1 ?>
          </form>
        <?php }else{ ?>
          <div style="font-size:22px;text-align:center;"><?php echo $text_no_active; ?></div>
          <style>
            .easyphoto_img_main{border:2px solid #ccc;padding:5px;margin:30px auto;width:600px;}
            .easyphoto_img_main img{max-width:100%;}
          </style>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script>
	$("[type='checkbox']").bootstrapSwitch('onColor', 'success');
</script>
<?php echo $footer; ?>
