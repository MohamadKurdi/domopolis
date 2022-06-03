<?php echo $header; ?>
<script type="text/javascript">
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
  $('#h1').toggle(
    function(){
      $(this).siblings('#module1').stop(false, true).slideDown(0);
    },
   function(){
      $(this).siblings('#module1').stop(false, true).slideUp(0);
   }
 );
 $('#h2').toggle(
    function(){
      $(this).siblings('#module2').stop(false, true).slideDown(0);
    },
   function(){
      $(this).siblings('#module2').stop(false, true).slideUp(0);
   }
 );
 $('#h3').toggle(
    function(){
      $(this).siblings('#module3').stop(false, true).slideDown(0);
    },
   function(){
      $(this).siblings('#module3').stop(false, true).slideUp(0);
   }
 );
 $('#h4').toggle(
    function(){
      $(this).siblings('#module4').stop(false, true).slideDown(0);
    },
   function(){
      $(this).siblings('#module4').stop(false, true).slideUp(0);
   }
 );
});
$(function(){
    if($(".buttons a").length>0){
        urlAjax = $("form").attr("action");

        $(".buttons").prepend($('<a class="button"><span>Применить</span></a>').click(function(event){
            event.preventDefault();
            if ( typeof CKEDITOR != 'undefined' ){
                for ( instance in CKEDITOR.instances ){
                    CKEDITOR.instances[instance].updateElement();
                }
            }
            $(".content").fadeTo(100, .2);
            $("span.error").remove();
            $("div.warning").remove();
            $.post(urlAjax, $("#form").serialize(),function(data){
                $data = $(data);
                if($data.find("span.error").length>0){
                    $data.find("span.error").each(function(i,e){
                        name = $(e).prevAll(":input").attr("name");
                        console.log(name);
                        $control = $("[name='"+ name +"']");
                        
                        if($control.next("img").length>0){
                            $control.next("img").after(e)
                        }else{
                            $control.after(e);
                        }
                    });
                    $data.find("div.warning").each(function(i,e){
                        warning_class = $(e).prev().attr("class");
                        console.log(warning_class);
                        $("."+ warning_class).after(e);
                    });
                }else{
                    $("span.error").remove();
                }
                $(".content").fadeTo(100, 1);
            });
        }));
    }
});
</script>
<style>
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
    .switch {width: 100px;}
    table.form {width: auto;margin: 0px auto; display:none;}
    h2 {text-align:center;}
    table.form > tbody > tr > td:first-child {width:300px;}
    .box > .content{min-height:1000px;}
	.box > .content h2 {text-align:left;}
</style>
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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<h2 id="h1"><?php echo $text_category; ?></h2>
        <table id="module1" class="form">
            <tr>
              <td><?php echo $entry_default; ?></td>
              <td class="switch"><?php if ($config_default) { ?>
                <input type="radio" id="radio118" name="config_default" value="1" checked="checked" />
                <input type="radio" id="radio117" name="config_default" value="0" />
                <label for="radio118" class="cb-enable selected"><span>On</span></label>
                <label for="radio117" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio118" name="config_default" value="1" />
                <input type="radio" id="radio117" name="config_default" value="0" checked="checked" />
                <label for="radio118" class="cb-enable"><span>On</span></label>
                <label for="radio117" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
              <td>
              <?php if ($config_default_text) { ?>
              <input type="text" name="config_default_text" value="<?php echo $config_default_text; ?>" size="40" />
              <?php } else { ?>
              <input type="text" name="config_default_text" value="<?php echo $entry_default; ?>" size="40" />
              <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_asc; ?></td>
              <td class="switch"><?php if ($config_name_asc) { ?>
                <input type="radio" id="radio116" name="config_name_asc" value="1" checked="checked" />
                <input type="radio" id="radio115" name="config_name_asc" value="0" />
                <label for="radio116" class="cb-enable selected"><span>On</span></label>
                <label for="radio115" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio116" name="config_name_asc" value="1" />
                <input type="radio" id="radio115" name="config_name_asc" value="0" checked="checked" />
                <label for="radio116" class="cb-enable"><span>On</span></label>
                <label for="radio115" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_asc_text) { ?>
                <input type="text" name="config_name_asc_text" value="<?php echo $config_name_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_asc_text" value="<?php echo $entry_name_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_desc; ?></td>
              <td class="switch"><?php if ($config_name_desc) { ?>
                <input type="radio" id="radio114" name="config_name_desc" value="1" checked="checked" />
                <input type="radio" id="radio113" name="config_name_desc" value="0" />
                <label for="radio114" class="cb-enable selected"><span>On</span></label>
                <label for="radio113" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio114" name="config_name_desc" value="1" />
                <input type="radio" id="radio113" name="config_name_desc" value="0" checked="checked" />
                <label for="radio114" class="cb-enable"><span>On</span></label>
                <label for="radio113" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_desc_text) { ?>
                <input type="text" name="config_name_desc_text" value="<?php echo $config_name_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_desc_text" value="<?php echo $entry_name_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_asc; ?></td>
              <td class="switch"><?php if ($config_price_asc) { ?>
                <input type="radio" id="radio112" name="config_price_asc" value="1" checked="checked" />
                <input type="radio" id="radio111" name="config_price_asc" value="0" />
                <label for="radio112" class="cb-enable selected"><span>On</span></label>
                <label for="radio111" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio112" name="config_price_asc" value="1" />
                <input type="radio" id="radio111" name="config_price_asc" value="0" checked="checked" />
                <label for="radio112" class="cb-enable"><span>On</span></label>
                <label for="radio111" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_asc_text) { ?>
                <input type="text" name="config_price_asc_text" value="<?php echo $config_price_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_asc_text" value="<?php echo $entry_price_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_desc; ?></td>
              <td class="switch"><?php if ($config_price_desc) { ?>
                <input type="radio" id="radio110" name="config_price_desc" value="1" checked="checked" />
                <input type="radio" id="radio109" name="config_price_desc" value="0" />
                <label for="radio110" class="cb-enable selected"><span>On</span></label>
                <label for="radio109" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio110" name="config_price_desc" value="1" />
                <input type="radio" id="radio109" name="config_price_desc" value="0" checked="checked" />
                <label for="radio110" class="cb-enable"><span>On</span></label>
                <label for="radio109" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_desc_text) { ?>
                <input type="text" name="config_price_desc_text" value="<?php echo $config_price_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_desc_text" value="<?php echo $entry_price_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_asc; ?></td>
              <td class="switch"><?php if ($config_rating_asc) { ?>
                <input type="radio" id="radio108" name="config_rating_asc" value="1" checked="checked" />
                <input type="radio" id="radio107" name="config_rating_asc" value="0" />
                <label for="radio108" class="cb-enable selected"><span>On</span></label>
                <label for="radio107" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio108" name="config_rating_asc" value="1" />
                <input type="radio" id="radio107" name="config_rating_asc" value="0" checked="checked" />
                <label for="radio108" class="cb-enable"><span>On</span></label>
                <label for="radio107" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_asc_text) { ?>
                <input type="text" name="config_rating_asc_text" value="<?php echo $config_rating_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_asc_text" value="<?php echo $entry_rating_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_desc; ?></td>
              <td class="switch"><?php if ($config_rating_desc) { ?>
                <input type="radio" id="radio106" name="config_rating_desc" value="1" checked="checked" />
                <input type="radio" id="radio105" name="config_rating_desc" value="0" />
                <label for="radio106" class="cb-enable selected"><span>On</span></label>
                <label for="radio105" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio106" name="config_rating_desc" value="1" />
                <input type="radio" id="radio105" name="config_rating_desc" value="0" checked="checked" />
                <label for="radio106" class="cb-enable"><span>On</span></label>
                <label for="radio105" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_desc_text) { ?>
                <input type="text" name="config_rating_desc_text" value="<?php echo $config_rating_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_desc_text" value="<?php echo $entry_rating_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_asc; ?></td>
              <td class="switch"><?php if ($config_model_asc) { ?>
                <input type="radio" id="radio104" name="config_model_asc" value="1" checked="checked" />
                <input type="radio" id="radio103" name="config_model_asc" value="0" />
                <label for="radio104" class="cb-enable selected"><span>On</span></label>
                <label for="radio103" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio104" name="config_model_asc" value="1" />
                <input type="radio" id="radio103" name="config_model_asc" value="0" checked="checked" />
                <label for="radio104" class="cb-enable"><span>On</span></label>
                <label for="radio103" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_asc_text) { ?>
                <input type="text" name="config_model_asc_text" value="<?php echo $config_model_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_asc_text" value="<?php echo $entry_model_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_desc; ?></td>
              <td class="switch"><?php if ($config_model_desc) { ?>
                <input type="radio" id="radio102" name="config_model_desc" value="1" checked="checked" />
                <input type="radio" id="radio101" name="config_model_desc" value="0" />
                <label for="radio102" class="cb-enable selected"><span>On</span></label>
                <label for="radio101" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio102" name="config_model_desc" value="1" />
                <input type="radio" id="radio101" name="config_model_desc" value="0" checked="checked" />
                <label for="radio102" class="cb-enable"><span>On</span></label>
                <label for="radio101" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_desc_text) { ?>
                <input type="text" name="config_model_desc_text" value="<?php echo $config_model_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_desc_text" value="<?php echo $entry_model_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_asc; ?></td>
              <td class="switch"><?php if ($config_viewed_asc) { ?>
                <input type="radio" id="radio100" name="config_viewed_asc" value="1" checked="checked" />
                <input type="radio" id="radio99" name="config_viewed_asc" value="0" />
                <label for="radio100" class="cb-enable selected"><span>On</span></label>
                <label for="radio99" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio100" name="config_viewed_asc" value="1" />
                <input type="radio" id="radio99" name="config_viewed_asc" value="0" checked="checked" />
                <label for="radio100" class="cb-enable"><span>On</span></label>
                <label for="radio99" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_asc_text) { ?>
                <input type="text" name="config_viewed_asc_text" value="<?php echo $config_viewed_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_asc_text" value="<?php echo $entry_viewed_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_desc; ?></td>
              <td class="switch"><?php if ($config_viewed_desc) { ?>
                <input type="radio" id="radio98" name="config_viewed_desc" value="1" checked="checked" />
                <input type="radio" id="radio97" name="config_viewed_desc" value="0" />
                <label for="radio98" class="cb-enable selected"><span>On</span></label>
                <label for="radio97" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio98" name="config_viewed_desc" value="1" />
                <input type="radio" id="radio97" name="config_viewed_desc" value="0" checked="checked" />
                <label for="radio98" class="cb-enable"><span>On</span></label>
                <label for="radio97" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_desc_text) { ?>
                <input type="text" name="config_viewed_desc_text" value="<?php echo $config_viewed_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_desc_text" value="<?php echo $entry_viewed_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_asc; ?></td>
              <td class="switch"><?php if ($config_quantity_asc) { ?>
                <input type="radio" id="radio96" name="config_quantity_asc" value="1" checked="checked" />
                <input type="radio" id="radio95" name="config_quantity_asc" value="0" />
                <label for="radio96" class="cb-enable selected"><span>On</span></label>
                <label for="radio95" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio96" name="config_quantity_asc" value="1" />
                <input type="radio" id="radio95" name="config_quantity_asc" value="0" checked="checked" />
                <label for="radio96" class="cb-enable"><span>On</span></label>
                <label for="radio95" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_asc_text) { ?>
                <input type="text" name="config_quantity_asc_text" value="<?php echo $config_quantity_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_asc_text" value="<?php echo $entry_quantity_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_desc; ?></td>
              <td class="switch"><?php if ($config_quantity_desc) { ?>
                <input type="radio" id="radio94" name="config_quantity_desc" value="1" checked="checked" />
                <input type="radio" id="radio93" name="config_quantity_desc" value="0" />
                <label for="radio94" class="cb-enable selected"><span>On</span></label>
                <label for="radio93" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio94" name="config_quantity_desc" value="1" />
                <input type="radio" id="radio93" name="config_quantity_desc" value="0" checked="checked" />
                <label for="radio94" class="cb-enable"><span>On</span></label>
                <label for="radio93" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_desc_text) { ?>
                <input type="text" name="config_quantity_desc_text" value="<?php echo $config_quantity_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_desc_text" value="<?php echo $entry_quantity_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_asc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_asc) { ?>
                <input type="radio" id="radio92" name="config_manufacturer_asc" value="1" checked="checked" />
                <input type="radio" id="radio91" name="config_manufacturer_asc" value="0" />
                <label for="radio92" class="cb-enable selected"><span>On</span></label>
                <label for="radio91" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio92" name="config_manufacturer_asc" value="1" />
                <input type="radio" id="radio91" name="config_manufacturer_asc" value="0" checked="checked" />
                <label for="radio92" class="cb-enable"><span>On</span></label>
                <label for="radio91" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_asc_text) { ?>
                <input type="text" name="config_manufacturer_asc_text" value="<?php echo $config_manufacturer_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_asc_text" value="<?php echo $entry_manufacturer_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_desc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_desc) { ?>
                <input type="radio" id="radio90" name="config_manufacturer_desc" value="1" checked="checked" />
                <input type="radio" id="radio89" name="config_manufacturer_desc" value="0" />
                <label for="radio90" class="cb-enable selected"><span>On</span></label>
                <label for="radio89" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio90" name="config_manufacturer_desc" value="1" />
                <input type="radio" id="radio89" name="config_manufacturer_desc" value="0" checked="checked" />
                <label for="radio90" class="cb-enable"><span>On</span></label>
                <label for="radio89" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_desc_text) { ?>
                <input type="text" name="config_manufacturer_desc_text" value="<?php echo $config_manufacturer_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_desc_text" value="<?php echo $entry_manufacturer_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_asc; ?></td>
              <td class="switch"><?php if ($config_date_added_asc) { ?>
                <input type="radio" id="radio9211" name="config_date_added_asc" value="1" checked="checked" />
                <input type="radio" id="radio9111" name="config_date_added_asc" value="0" />
                <label for="radio9211" class="cb-enable selected"><span>On</span></label>
                <label for="radio9111" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio9211" name="config_date_added_asc" value="1" />
                <input type="radio" id="radio9111" name="config_date_added_asc" value="0" checked="checked" />
                <label for="radio9211" class="cb-enable"><span>On</span></label>
                <label for="radio9111" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_asc_text) { ?>
                <input type="text" name="config_date_added_asc_text" value="<?php echo $config_date_added_asc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_asc_text" value="<?php echo $entry_date_added_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_desc; ?></td>
              <td class="switch"><?php if ($config_date_added_desc) { ?>
                <input type="radio" id="radio9011" name="config_date_added_desc" value="1" checked="checked" />
                <input type="radio" id="radio8911" name="config_date_added_desc" value="0" />
                <label for="radio9011" class="cb-enable selected"><span>On</span></label>
                <label for="radio8911" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio9011" name="config_date_added_desc" value="1" />
                <input type="radio" id="radio8911" name="config_date_added_desc" value="0" checked="checked" />
                <label for="radio9011" class="cb-enable"><span>On</span></label>
                <label for="radio8911" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_desc_text) { ?>
                <input type="text" name="config_date_added_desc_text" value="<?php echo $config_date_added_desc_text; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_desc_text" value="<?php echo $entry_date_added_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
        </table>
<h2 id="h2"><?php echo $text_manufacturer; ?></h2>
        <table id="module2" class="form">
            <tr>
              <td><?php echo $entry_default; ?></td>
              <td class="switch"><?php if ($config_default_man) { ?>
                <input type="radio" id="radio88" name="config_default_man" value="1" checked="checked" />
                <input type="radio" id="radio87" name="config_default_man" value="0" />
                <label for="radio88" class="cb-enable selected"><span>On</span></label>
                <label for="radio87" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio88" name="config_default_man" value="1" />
                <input type="radio" id="radio87" name="config_default_man" value="0" checked="checked" />
                <label for="radio88" class="cb-enable"><span>On</span></label>
                <label for="radio87" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_default_text_man) { ?>
                <input type="text" name="config_default_text_man" value="<?php echo $config_default_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_default_text_man" value="<?php echo $entry_default; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_asc; ?></td>
              <td class="switch"><?php if ($config_name_asc_man) { ?>
                <input type="radio" id="radio86" name="config_name_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio85" name="config_name_asc_man" value="0" />
                <label for="radio86" class="cb-enable selected"><span>On</span></label>
                <label for="radio85" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio86" name="config_name_asc_man" value="1" />
                <input type="radio" id="radio85" name="config_name_asc_man" value="0" checked="checked" />
                <label for="radio86" class="cb-enable"><span>On</span></label>
                <label for="radio85" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_asc_text_man) { ?>
                <input type="text" name="config_name_asc_text_man" value="<?php echo $config_name_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_asc_text_man" value="<?php echo $entry_name_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_desc; ?></td>
              <td class="switch"><?php if ($config_name_desc_man) { ?>
                <input type="radio" id="radio84" name="config_name_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio83" name="config_name_desc_man" value="0" />
                <label for="radio84" class="cb-enable selected"><span>On</span></label>
                <label for="radio83" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio84" name="config_name_desc_man" value="1" />
                <input type="radio" id="radio83" name="config_name_desc_man" value="0" checked="checked" />
                <label for="radio84" class="cb-enable"><span>On</span></label>
                <label for="radio83" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_desc_text_man) { ?>
                <input type="text" name="config_name_desc_text_man" value="<?php echo $config_name_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_desc_text_man" value="<?php echo $entry_name_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_asc; ?></td>
              <td class="switch"><?php if ($config_price_asc_man) { ?>
                <input type="radio" id="radio82" name="config_price_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio81" name="config_price_asc_man" value="0" />
                <label for="radio82" class="cb-enable selected"><span>On</span></label>
                <label for="radio81" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio82" name="config_price_asc_man" value="1" />
                <input type="radio" id="radio81" name="config_price_asc_man" value="0" checked="checked" />
                <label for="radio82" class="cb-enable"><span>On</span></label>
                <label for="radio81" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_asc_text_man) { ?>
                <input type="text" name="config_price_asc_text_man" value="<?php echo $config_price_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_asc_text_man" value="<?php echo $entry_price_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_desc; ?></td>
              <td class="switch"><?php if ($config_price_desc_man) { ?>
                <input type="radio" id="radio80" name="config_price_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio79" name="config_price_desc_man" value="0" />
                <label for="radio80" class="cb-enable selected"><span>On</span></label>
                <label for="radio79" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio80" name="config_price_desc_man" value="1" />
                <input type="radio" id="radio79" name="config_price_desc_man" value="0" checked="checked" />
                <label for="radio80" class="cb-enable"><span>On</span></label>
                <label for="radio79" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_desc_text_man) { ?>
                <input type="text" name="config_price_desc_text_man" value="<?php echo $config_price_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_desc_text_man" value="<?php echo $entry_price_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_asc; ?></td>
              <td class="switch"><?php if ($config_rating_asc_man) { ?>
                <input type="radio" id="radio78" name="config_rating_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio77" name="config_rating_asc_man" value="0" />
                <label for="radio78" class="cb-enable selected"><span>On</span></label>
                <label for="radio77" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio78" name="config_rating_asc_man" value="1" />
                <input type="radio" id="radio77" name="config_rating_asc_man" value="0" checked="checked" />
                <label for="radio78" class="cb-enable"><span>On</span></label>
                <label for="radio77" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_asc_text_man) { ?>
                <input type="text" name="config_rating_asc_text_man" value="<?php echo $config_rating_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_asc_text_man" value="<?php echo $entry_rating_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_desc; ?></td>
              <td class="switch"><?php if ($config_rating_desc_man) { ?>
                <input type="radio" id="radio76" name="config_rating_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio75" name="config_rating_desc_man" value="0" />
                <label for="radio76" class="cb-enable selected"><span>On</span></label>
                <label for="radio75" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio76" name="config_rating_desc_man" value="1" />
                <input type="radio" id="radio75" name="config_rating_desc_man" value="0" checked="checked" />
                <label for="radio76" class="cb-enable"><span>On</span></label>
                <label for="radio75" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_desc_text_man) { ?>
                <input type="text" name="config_rating_desc_text_man" value="<?php echo $config_rating_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_desc_text_man" value="<?php echo $entry_rating_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_asc; ?></td>
              <td class="switch"><?php if ($config_model_asc_man) { ?>
                <input type="radio" id="radio74" name="config_model_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio73" name="config_model_asc_man" value="0" />
                <label for="radio74" class="cb-enable selected"><span>On</span></label>
                <label for="radio73" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio74" name="config_model_asc_man" value="1" />
                <input type="radio" id="radio73" name="config_model_asc_man" value="0" checked="checked" />
                <label for="radio74" class="cb-enable"><span>On</span></label>
                <label for="radio73" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_asc_text_man) { ?>
                <input type="text" name="config_model_asc_text_man" value="<?php echo $config_model_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_asc_text_man" value="<?php echo $entry_model_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_desc; ?></td>
              <td class="switch"><?php if ($config_model_desc_man) { ?>
                <input type="radio" id="radio72" name="config_model_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio71" name="config_model_desc_man" value="0" />
                <label for="radio72" class="cb-enable selected"><span>On</span></label>
                <label for="radio71" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio72" name="config_model_desc_man" value="1" />
                <input type="radio" id="radio71" name="config_model_desc_man" value="0" checked="checked" />
                <label for="radio72" class="cb-enable"><span>On</span></label>
                <label for="radio71" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_desc_text_man) { ?>
                <input type="text" name="config_model_desc_text_man" value="<?php echo $config_model_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_desc_text_man" value="<?php echo $entry_model_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_asc; ?></td>
              <td class="switch"><?php if ($config_viewed_asc_man) { ?>
                <input type="radio" id="radio70" name="config_viewed_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio69" name="config_viewed_asc_man" value="0" />
                <label for="radio70" class="cb-enable selected"><span>On</span></label>
                <label for="radio69" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio70" name="config_viewed_asc_man" value="1" />
                <input type="radio" id="radio69" name="config_viewed_asc_man" value="0" checked="checked" />
                <label for="radio70" class="cb-enable"><span>On</span></label>
                <label for="radio69" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_asc_text_man) { ?>
                <input type="text" name="config_viewed_asc_text_man" value="<?php echo $config_viewed_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_asc_text_man" value="<?php echo $entry_viewed_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_desc; ?></td>
              <td class="switch"><?php if ($config_viewed_desc_man) { ?>
                <input type="radio" id="radio68" name="config_viewed_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio67" name="config_viewed_desc_man" value="0" />
                <label for="radio68" class="cb-enable selected"><span>On</span></label>
                <label for="radio67" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio68" name="config_viewed_desc_man" value="1" />
                <input type="radio" id="radio67" name="config_viewed_desc_man" value="0" checked="checked" />
                <label for="radio68" class="cb-enable"><span>On</span></label>
                <label for="radio67" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_desc_text_man) { ?>
                <input type="text" name="config_viewed_desc_text_man" value="<?php echo $config_viewed_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_desc_text_man" value="<?php echo $entry_viewed_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_asc; ?></td>
              <td class="switch"><?php if ($config_quantity_asc_man) { ?>
                <input type="radio" id="radio66" name="config_quantity_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio65" name="config_quantity_asc_man" value="0" />
                <label for="radio66" class="cb-enable selected"><span>On</span></label>
                <label for="radio65" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio66" name="config_quantity_asc_man" value="1" />
                <input type="radio" id="radio65" name="config_quantity_asc_man" value="0" checked="checked" />
                <label for="radio66" class="cb-enable"><span>On</span></label>
                <label for="radio65" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_asc_text_man) { ?>
                <input type="text" name="config_quantity_asc_text_man" value="<?php echo $config_quantity_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_asc_text_man" value="<?php echo $entry_quantity_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_desc; ?></td>
              <td class="switch"><?php if ($config_quantity_desc_man) { ?>
                <input type="radio" id="radio64" name="config_quantity_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio63" name="config_quantity_desc_man" value="0" />
                <label for="radio64" class="cb-enable selected"><span>On</span></label>
                <label for="radio63" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio64" name="config_quantity_desc_man" value="1" />
                <input type="radio" id="radio63" name="config_quantity_desc_man" value="0" checked="checked" />
                <label for="radio64" class="cb-enable"><span>On</span></label>
                <label for="radio63" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_desc_text_man) { ?>
                <input type="text" name="config_quantity_desc_text_man" value="<?php echo $config_quantity_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_desc_text_man" value="<?php echo $entry_quantity_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_asc; ?></td>
              <td class="switch"><?php if ($config_date_added_asc_man) { ?>
                <input type="radio" id="radio8966" name="config_date_added_asc_man" value="1" checked="checked" />
                <input type="radio" id="radio8965" name="config_date_added_asc_man" value="0" />
                <label for="radio8966" class="cb-enable selected"><span>On</span></label>
                <label for="radio8965" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio8966" name="config_date_added_asc_man" value="1" />
                <input type="radio" id="radio8965" name="config_date_added_asc_man" value="0" checked="checked" />
                <label for="radio8966" class="cb-enable"><span>On</span></label>
                <label for="radio8965" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_asc_text_man) { ?>
                <input type="text" name="config_date_added_asc_text_man" value="<?php echo $config_date_added_asc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_asc_text_man" value="<?php echo $entry_date_added_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_desc; ?></td>
              <td class="switch"><?php if ($config_date_added_desc_man) { ?>
                <input type="radio" id="radio4664" name="config_date_added_desc_man" value="1" checked="checked" />
                <input type="radio" id="radio4663" name="config_date_added_desc_man" value="0" />
                <label for="radio4664" class="cb-enable selected"><span>On</span></label>
                <label for="radio4663" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio4664" name="config_date_added_desc_man" value="1" />
                <input type="radio" id="radio4663" name="config_date_added_desc_man" value="0" checked="checked" />
                <label for="radio4664" class="cb-enable"><span>On</span></label>
                <label for="radio4663" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_desc_text_man) { ?>
                <input type="text" name="config_date_added_desc_text_man" value="<?php echo $config_date_added_desc_text_man; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_desc_text_man" value="<?php echo $entry_date_added_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
        </table>
<h2 id="h3"><?php echo $text_special; ?></h2>
        <table id="module3" class="form">
            <tr>
              <td><?php echo $entry_default; ?></td>
              <td class="switch"><?php if ($config_default_sp) { ?>
                <input type="radio" id="radio58" name="config_default_sp" value="1" checked="checked" />
                <input type="radio" id="radio57" name="config_default_sp" value="0" />
                <label for="radio58" class="cb-enable selected"><span>On</span></label>
                <label for="radio57" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio58" name="config_default_sp" value="1" />
                <input type="radio" id="radio57" name="config_default_sp" value="0" checked="checked" />
                <label for="radio58" class="cb-enable"><span>On</span></label>
                <label for="radio57" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_default_text_sp) { ?>
                <input type="text" name="config_default_text_sp" value="<?php echo $config_default_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_default_text_sp" value="<?php echo $entry_default; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_asc; ?></td>
              <td class="switch"><?php if ($config_name_asc_sp) { ?>
                <input type="radio" id="radio56" name="config_name_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio55" name="config_name_asc_sp" value="0" />
                <label for="radio56" class="cb-enable selected"><span>On</span></label>
                <label for="radio55" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio56" name="config_name_asc_sp" value="1" />
                <input type="radio" id="radio55" name="config_name_asc_sp" value="0" checked="checked" />
                <label for="radio56" class="cb-enable"><span>On</span></label>
                <label for="radio55" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_asc_text_sp) { ?>
                <input type="text" name="config_name_asc_text_sp" value="<?php echo $config_name_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_asc_text_sp" value="<?php echo $entry_name_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_desc; ?></td>
              <td class="switch"><?php if ($config_name_desc_sp) { ?>
                <input type="radio" id="radio54" name="config_name_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio53" name="config_name_desc_sp" value="0" />
                <label for="radio54" class="cb-enable selected"><span>On</span></label>
                <label for="radio53" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio54" name="config_name_desc_sp" value="1" />
                <input type="radio" id="radio53" name="config_name_desc_sp" value="0" checked="checked" />
                <label for="radio54" class="cb-enable"><span>On</span></label>
                <label for="radio53" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_desc_text_sp) { ?>
                <input type="text" name="config_name_desc_text_sp" value="<?php echo $config_name_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_desc_text_sp" value="<?php echo $entry_name_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_asc; ?></td>
              <td class="switch"><?php if ($config_price_asc_sp) { ?>
                <input type="radio" id="radio52" name="config_price_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio51" name="config_price_asc_sp" value="0" />
                <label for="radio52" class="cb-enable selected"><span>On</span></label>
                <label for="radio51" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio52" name="config_price_asc_sp" value="1" />
                <input type="radio" id="radio51" name="config_price_asc_sp" value="0" checked="checked" />
                <label for="radio52" class="cb-enable"><span>On</span></label>
                <label for="radio51" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_asc_text_sp) { ?>
                <input type="text" name="config_price_asc_text_sp" value="<?php echo $config_price_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_asc_text_sp" value="<?php echo $entry_price_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_desc; ?></td>
              <td class="switch"><?php if ($config_price_desc_sp) { ?>
                <input type="radio" id="radio50" name="config_price_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio49" name="config_price_desc_sp" value="0" />
                <label for="radio50" class="cb-enable selected"><span>On</span></label>
                <label for="radio49" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio50" name="config_price_desc_sp" value="1" />
                <input type="radio" id="radio49" name="config_price_desc_sp" value="0" checked="checked" />
                <label for="radio50" class="cb-enable"><span>On</span></label>
                <label for="radio49" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_desc_text_sp) { ?>
                <input type="text" name="config_price_desc_text_sp" value="<?php echo $config_price_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_desc_text_sp" value="<?php echo $entry_price_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_asc; ?></td>
              <td class="switch"><?php if ($config_rating_asc_sp) { ?>
                <input type="radio" id="radio48" name="config_rating_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio47" name="config_rating_asc_sp" value="0" />
                <label for="radio48" class="cb-enable selected"><span>On</span></label>
                <label for="radio47" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio48" name="config_rating_asc_sp" value="1" />
                <input type="radio" id="radio47" name="config_rating_asc_sp" value="0" checked="checked" />
                <label for="radio48" class="cb-enable"><span>On</span></label>
                <label for="radio47" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_asc_text_sp) { ?>
                <input type="text" name="config_rating_asc_text_sp" value="<?php echo $config_rating_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_asc_text_sp" value="<?php echo $entry_rating_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_desc; ?></td>
              <td class="switch"><?php if ($config_rating_desc_sp) { ?>
                <input type="radio" id="radio46" name="config_rating_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio45" name="config_rating_desc_sp" value="0" />
                <label for="radio46" class="cb-enable selected"><span>On</span></label>
                <label for="radio45" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio46" name="config_rating_desc_sp" value="1" />
                <input type="radio" id="radio45" name="config_rating_desc_sp" value="0" checked="checked" />
                <label for="radio46" class="cb-enable"><span>On</span></label>
                <label for="radio45" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_desc_text_sp) { ?>
                <input type="text" name="config_rating_desc_text_sp" value="<?php echo $config_rating_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_desc_text_sp" value="<?php echo $entry_rating_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_asc; ?></td>
              <td class="switch"><?php if ($config_model_asc_sp) { ?>
                <input type="radio" id="radio44" name="config_model_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio43" name="config_model_asc_sp" value="0" />
                <label for="radio44" class="cb-enable selected"><span>On</span></label>
                <label for="radio43" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio44" name="config_model_asc_sp" value="1" />
                <input type="radio" id="radio43" name="config_model_asc_sp" value="0" checked="checked" />
                <label for="radio44" class="cb-enable"><span>On</span></label>
                <label for="radio43" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_asc_text_sp) { ?>
                <input type="text" name="config_model_asc_text_sp" value="<?php echo $config_model_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_asc_text_sp" value="<?php echo $entry_model_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_desc; ?></td>
              <td class="switch"><?php if ($config_model_desc_sp) { ?>
                <input type="radio" id="radio42" name="config_model_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio41" name="config_model_desc_sp" value="0" />
                <label for="radio42" class="cb-enable selected"><span>On</span></label>
                <label for="radio41" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio42" name="config_model_desc_sp" value="1" />
                <input type="radio" id="radio41" name="config_model_desc_sp" value="0" checked="checked" />
                <label for="radio42" class="cb-enable"><span>On</span></label>
                <label for="radio41" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_desc_text_sp) { ?>
                <input type="text" name="config_model_desc_text_sp" value="<?php echo $config_model_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_desc_text_sp" value="<?php echo $entry_model_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_asc; ?></td>
              <td class="switch"><?php if ($config_viewed_asc_sp) { ?>
                <input type="radio" id="radio40" name="config_viewed_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio39" name="config_viewed_asc_sp" value="0" />
                <label for="radio40" class="cb-enable selected"><span>On</span></label>
                <label for="radio39" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio4" name="config_viewed_asc_sp" value="1" />
                <input type="radio" id="radio39" name="config_viewed_asc_sp" value="0" checked="checked" />
                <label for="radio40" class="cb-enable"><span>On</span></label>
                <label for="radio39" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_asc_text_sp) { ?>
                <input type="text" name="config_viewed_asc_text_sp" value="<?php echo $config_viewed_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_asc_text_sp" value="<?php echo $entry_viewed_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_desc; ?></td>
              <td class="switch"><?php if ($config_viewed_desc_sp) { ?>
                <input type="radio" id="radio38" name="config_viewed_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio37" name="config_viewed_desc_sp" value="0" />
                <label for="radio38" class="cb-enable selected"><span>On</span></label>
                <label for="radio37" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio38" name="config_viewed_desc_sp" value="1" />
                <input type="radio" id="radio37" name="config_viewed_desc_sp" value="0" checked="checked" />
                <label for="radio38" class="cb-enable"><span>On</span></label>
                <label for="radio37" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_desc_text_sp) { ?>
                <input type="text" name="config_viewed_desc_text_sp" value="<?php echo $config_viewed_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_desc_text_sp" value="<?php echo $entry_viewed_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_asc; ?></td>
              <td class="switch"><?php if ($config_quantity_asc_sp) { ?>
                <input type="radio" id="radio36" name="config_quantity_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio35" name="config_quantity_asc_sp" value="0" />
                <label for="radio36" class="cb-enable selected"><span>On</span></label>
                <label for="radio35" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio36" name="config_quantity_asc_sp" value="1" />
                <input type="radio" id="radio35" name="config_quantity_asc_sp" value="0" checked="checked" />
                <label for="radio36" class="cb-enable"><span>On</span></label>
                <label for="radio35" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_asc_text_sp) { ?>
                <input type="text" name="config_quantity_asc_text_sp" value="<?php echo $config_quantity_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_asc_text_sp" value="<?php echo $entry_quantity_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_desc; ?></td>
              <td class="switch"><?php if ($config_quantity_desc_sp) { ?>
                <input type="radio" id="radio34" name="config_quantity_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio33" name="config_quantity_desc_sp" value="0" />
                <label for="radio34" class="cb-enable selected"><span>On</span></label>
                <label for="radio33" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio34" name="config_quantity_desc_sp" value="1" />
                <input type="radio" id="radio33" name="config_quantity_desc_sp" value="0" checked="checked" />
                <label for="radio34" class="cb-enable"><span>On</span></label>
                <label for="radio33" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_desc_text_sp) { ?>
                <input type="text" name="config_quantity_desc_text_sp" value="<?php echo $config_quantity_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_desc_text_sp" value="<?php echo $entry_quantity_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_asc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_asc_sp) { ?>
                <input type="radio" id="radio32" name="config_manufacturer_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio31" name="config_manufacturer_asc_sp" value="0" />
                <label for="radio32" class="cb-enable selected"><span>On</span></label>
                <label for="radio31" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio32" name="config_manufacturer_asc_sp" value="1" />
                <input type="radio" id="radio31" name="config_manufacturer_asc_sp" value="0" checked="checked" />
                <label for="radio32" class="cb-enable"><span>On</span></label>
                <label for="radio31" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_asc_text_sp) { ?>
                <input type="text" name="config_manufacturer_asc_text_sp" value="<?php echo $config_manufacturer_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_asc_text_sp" value="<?php echo $entry_manufacturer_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_desc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_desc_sp) { ?>
                <input type="radio" id="radio30" name="config_manufacturer_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio29" name="config_manufacturer_desc_sp" value="0" />
                <label for="radio30" class="cb-enable selected"><span>On</span></label>
                <label for="radio29" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio30" name="config_manufacturer_desc_sp" value="1" />
                <input type="radio" id="radio29" name="config_manufacturer_desc_sp" value="0" checked="checked" />
                <label for="radio30" class="cb-enable"><span>On</span></label>
                <label for="radio29" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_desc_text_sp) { ?>
                <input type="text" name="config_manufacturer_desc_text_sp" value="<?php echo $config_manufacturer_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_desc_text_sp" value="<?php echo $entry_manufacturer_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_asc; ?></td>
              <td class="switch"><?php if ($config_date_added_asc_sp) { ?>
                <input type="radio" id="radio983" name="config_date_added_asc_sp" value="1" checked="checked" />
                <input type="radio" id="radio982" name="config_date_added_asc_sp" value="0" />
                <label for="radio983" class="cb-enable selected"><span>On</span></label>
                <label for="radio982" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio983" name="config_date_added_asc_sp" value="1" />
                <input type="radio" id="radio982" name="config_date_added_asc_sp" value="0" checked="checked" />
                <label for="radio983" class="cb-enable"><span>On</span></label>
                <label for="radio982" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_asc_text_sp) { ?>
                <input type="text" name="config_date_added_asc_text_sp" value="<?php echo $config_date_added_asc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_asc_text_sp" value="<?php echo $entry_date_added_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_desc; ?></td>
              <td class="switch"><?php if ($config_date_added_desc_sp) { ?>
                <input type="radio" id="radio9830" name="config_date_added_desc_sp" value="1" checked="checked" />
                <input type="radio" id="radio9829" name="config_date_added_desc_sp" value="0" />
                <label for="radio9830" class="cb-enable selected"><span>On</span></label>
                <label for="radio9829" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio9830" name="config_date_added_desc_sp" value="1" />
                <input type="radio" id="radio9829" name="config_date_added_desc_sp" value="0" checked="checked" />
                <label for="radio9830" class="cb-enable"><span>On</span></label>
                <label for="radio9829" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_desc_text_sp) { ?>
                <input type="text" name="config_date_added_desc_text_sp" value="<?php echo $config_date_added_desc_text_sp; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_desc_text_sp" value="<?php echo $entry_date_added_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
        </table>
<h2 id="h4"><?php echo $text_search; ?></h2>
        <table id="module4" class="form">
            <tr>
              <td><?php echo $entry_default; ?></td>
              <td class="switch"><?php if ($config_default_sr) { ?>
                <input type="radio" id="radio28" name="config_default_sr" value="1" checked="checked" />
                <input type="radio" id="radio27" name="config_default_sr" value="0" />
                <label for="radio28" class="cb-enable selected"><span>On</span></label>
                <label for="radio27" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio28" name="config_default_sr" value="1" />
                <input type="radio" id="radio27" name="config_default_sr" value="0" checked="checked" />
                <label for="radio28" class="cb-enable"><span>On</span></label>
                <label for="radio27" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_default_text_sr) { ?>
                <input type="text" name="config_default_text_sr" value="<?php echo $config_default_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_default_text_sr" value="<?php echo $entry_default; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_asc; ?></td>
              <td class="switch"><?php if ($config_name_asc_sr) { ?>
                <input type="radio" id="radio26" name="config_name_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio25" name="config_name_asc_sr" value="0" />
                <label for="radio26" class="cb-enable selected"><span>On</span></label>
                <label for="radio25" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio26" name="config_name_asc_sr" value="1" />
                <input type="radio" id="radio25" name="config_name_asc_sr" value="0" checked="checked" />
                <label for="radio26" class="cb-enable"><span>On</span></label>
                <label for="radio25" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_asc_text_sr) { ?>
                <input type="text" name="config_name_asc_text_sr" value="<?php echo $config_name_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_asc_text_sr" value="<?php echo $entry_name_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_name_desc; ?></td>
              <td class="switch"><?php if ($config_name_desc_sr) { ?>
                <input type="radio" id="radio24" name="config_name_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio23" name="config_name_desc_sr" value="0" />
                <label for="radio24" class="cb-enable selected"><span>On</span></label>
                <label for="radio23" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio24" name="config_name_desc_sr" value="1" />
                <input type="radio" id="radio23" name="config_name_desc_sr" value="0" checked="checked" />
                <label for="radio24" class="cb-enable"><span>On</span></label>
                <label for="radio23" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_name_desc_text_sr) { ?>
                <input type="text" name="config_name_desc_text_sr" value="<?php echo $config_name_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_name_desc_text_sr" value="<?php echo $entry_name_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_asc; ?></td>
              <td class="switch"><?php if ($config_price_asc_sr) { ?>
                <input type="radio" id="radio22" name="config_price_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio21" name="config_price_asc_sr" value="0" />
                <label for="radio22" class="cb-enable selected"><span>On</span></label>
                <label for="radio21" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio22" name="config_price_asc_sr" value="1" />
                <input type="radio" id="radio21" name="config_price_asc_sr" value="0" checked="checked" />
                <label for="radio22" class="cb-enable"><span>On</span></label>
                <label for="radio21" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_asc_text_sr) { ?>
                <input type="text" name="config_price_asc_text_sr" value="<?php echo $config_price_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_asc_text_sr" value="<?php echo $entry_price_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_price_desc; ?></td>
              <td class="switch"><?php if ($config_price_desc_sr) { ?>
                <input type="radio" id="radio20" name="config_price_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio19" name="config_price_desc_sr" value="0" />
                <label for="radio20" class="cb-enable selected"><span>On</span></label>
                <label for="radio19" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio20" name="config_price_desc_sr" value="1" />
                <input type="radio" id="radio19" name="config_price_desc_sr" value="0" checked="checked" />
                <label for="radio20" class="cb-enable"><span>On</span></label>
                <label for="radio19" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_price_desc_text_sr) { ?>
                <input type="text" name="config_price_desc_text_sr" value="<?php echo $config_price_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_price_desc_text_sr" value="<?php echo $entry_price_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_asc; ?></td>
              <td class="switch"><?php if ($config_rating_asc_sr) { ?>
                <input type="radio" id="radio18" name="config_rating_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio17" name="config_rating_asc_sr" value="0" />
                <label for="radio18" class="cb-enable selected"><span>On</span></label>
                <label for="radio17" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio18" name="config_rating_asc_sr" value="1" />
                <input type="radio" id="radio17" name="config_rating_asc_sr" value="0" checked="checked" />
                <label for="radio18" class="cb-enable"><span>On</span></label>
                <label for="radio17" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_asc_text_sr) { ?>
                <input type="text" name="config_rating_asc_text_sr" value="<?php echo $config_rating_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_asc_text_sr" value="<?php echo $entry_rating_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_rating_desc; ?></td>
              <td class="switch"><?php if ($config_rating_desc_sr) { ?>
                <input type="radio" id="radio16" name="config_rating_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio15" name="config_rating_desc_sr" value="0" />
                <label for="radio16" class="cb-enable selected"><span>On</span></label>
                <label for="radio15" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio16" name="config_rating_desc_sr" value="1" />
                <input type="radio" id="radio15" name="config_rating_desc_sr" value="0" checked="checked" />
                <label for="radio16" class="cb-enable"><span>On</span></label>
                <label for="radio15" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_rating_desc_text_sr) { ?>
                <input type="text" name="config_rating_desc_text_sr" value="<?php echo $config_rating_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_rating_desc_text_sr" value="<?php echo $entry_rating_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_asc; ?></td>
              <td class="switch"><?php if ($config_model_asc_sr) { ?>
                <input type="radio" id="radio14" name="config_model_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio13" name="config_model_asc_sr" value="0" />
                <label for="radio14" class="cb-enable selected"><span>On</span></label>
                <label for="radio13" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio14" name="config_model_asc_sr" value="1" />
                <input type="radio" id="radio13" name="config_model_asc_sr" value="0" checked="checked" />
                <label for="radio14" class="cb-enable"><span>On</span></label>
                <label for="radio13" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_asc_text_sr) { ?>
                <input type="text" name="config_model_asc_text_sr" value="<?php echo $config_model_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_asc_text_sr" value="<?php echo $entry_model_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_model_desc; ?></td>
              <td class="switch"><?php if ($config_model_desc_sr) { ?>
                <input type="radio" id="radio12" name="config_model_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio11" name="config_model_desc_sr" value="0" />
                <label for="radio12" class="cb-enable selected"><span>On</span></label>
                <label for="radio11" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio12" name="config_model_desc_sr" value="1" />
                <input type="radio" id="radio11" name="config_model_desc_sr" value="0" checked="checked" />
                <label for="radio12" class="cb-enable"><span>On</span></label>
                <label for="radio11" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_model_desc_text_sr) { ?>
                <input type="text" name="config_model_desc_text_sr" value="<?php echo $config_model_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_model_desc_text_sr" value="<?php echo $entry_model_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_asc; ?></td>
              <td class="switch"><?php if ($config_viewed_asc_sr) { ?>
                <input type="radio" id="radio10" name="config_viewed_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio9" name="config_viewed_asc_sr" value="0" />
                <label for="radio10" class="cb-enable selected"><span>On</span></label>
                <label for="radio9" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio10" name="config_viewed_asc_sr" value="1" />
                <input type="radio" id="radio9" name="config_viewed_asc_sr" value="0" checked="checked" />
                <label for="radio10" class="cb-enable"><span>On</span></label>
                <label for="radio9" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_asc_text_sr) { ?>
                <input type="text" name="config_viewed_asc_text_sr" value="<?php echo $config_viewed_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_asc_text_sr" value="<?php echo $entry_viewed_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_viewed_desc; ?></td>
              <td class="switch"><?php if ($config_viewed_desc_sr) { ?>
                <input type="radio" id="radio800" name="config_viewed_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio700" name="config_viewed_desc_sr" value="0" />
                <label for="radio800" class="cb-enable selected"><span>On</span></label>
                <label for="radio700" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio800" name="config_viewed_desc_sr" value="1" />
                <input type="radio" id="radio700" name="config_viewed_desc_sr" value="0" checked="checked" />
                <label for="radio800" class="cb-enable"><span>On</span></label>
                <label for="radio700" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_viewed_desc_text_sr) { ?>
                <input type="text" name="config_viewed_desc_text_sr" value="<?php echo $config_viewed_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_viewed_desc_text_sr" value="<?php echo $entry_viewed_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_asc; ?></td>
              <td class="switch"><?php if ($config_quantity_asc_sr) { ?>
                <input type="radio" name="config_quantity_asc_sr" value="1" checked="checked" />
                <input type="radio" name="config_quantity_asc_sr" value="0" />
                <label for="radio8" id="radio8" class="cb-enable selected"><span>On</span></label>
                <label for="radio7" id="radio7" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio8" name="config_quantity_asc_sr" value="1" />
                <input type="radio" id="radio7" name="config_quantity_asc_sr" value="0" checked="checked" />
                <label for="radio8" class="cb-enable"><span>On</span></label>
                <label for="radio7" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_asc_text_sr) { ?>
                <input type="text" name="config_quantity_asc_text_sr" value="<?php echo $config_quantity_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_asc_text_sr" value="<?php echo $entry_quantity_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity_desc; ?></td>
              <td class="switch"><?php if ($config_quantity_desc_sr) { ?>
                <input type="radio" id="radio6" name="config_quantity_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio5" name="config_quantity_desc_sr" value="0" />
                <label for="radio6" class="cb-enable selected"><span>On</span></label>
                <label for="radio5" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio6" name="config_quantity_desc_sr" value="1" />
                <input type="radio" id="radio5" name="config_quantity_desc_sr" value="0" checked="checked" />
                <label for="radio6" class="cb-enable"><span>On</span></label>
                <label for="radio5" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_quantity_desc_text_sr) { ?>
                <input type="text" name="config_quantity_desc_text_sr" value="<?php echo $config_quantity_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_quantity_desc_text_sr" value="<?php echo $entry_quantity_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_asc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_asc_sr) { ?>
                <input type="radio" id="radio4" name="config_manufacturer_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio3" name="config_manufacturer_asc_sr" value="0" />
                <label for="radio4" class="cb-enable selected"><span>On</span></label>
                <label for="radio3" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio4" name="config_manufacturer_asc_sr" value="1" />
                <input type="radio" id="radio3" name="config_manufacturer_asc_sr" value="0" checked="checked" />
                <label for="radio4" class="cb-enable"><span>On</span></label>
                <label for="radio3" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_asc_text_sr) { ?>
                <input type="text" name="config_manufacturer_asc_text_sr" value="<?php echo $config_manufacturer_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_asc_text_sr" value="<?php echo $entry_manufacturer_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer_desc; ?></td>
              <td class="switch"><?php if ($config_manufacturer_desc_sr) { ?>
                <input type="radio" id="radio1" name="config_manufacturer_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio2" name="config_manufacturer_desc_sr" value="0" />
                <label for="radio1" class="cb-enable selected"><span>On</span></label>
                <label for="radio2" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio1" name="config_manufacturer_desc_sr" value="1" />
                <input type="radio" id="radio2" name="config_manufacturer_desc_sr" value="0" checked="checked" />
                <label for="radio1" class="cb-enable"><span>On</span></label>
                <label for="radio2" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_manufacturer_desc_text_sr) { ?>
                <input type="text" name="config_manufacturer_desc_text_sr" value="<?php echo $config_manufacturer_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_manufacturer_desc_text_sr" value="<?php echo $entry_manufacturer_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_asc; ?></td>
              <td class="switch"><?php if ($config_date_added_asc_sr) { ?>
                <input type="radio" id="radio454" name="config_date_added_asc_sr" value="1" checked="checked" />
                <input type="radio" id="radio453" name="config_date_added_asc_sr" value="0" />
                <label for="radio454" class="cb-enable selected"><span>On</span></label>
                <label for="radio453" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio454" name="config_date_added_asc_sr" value="1" />
                <input type="radio" id="radio453" name="config_date_added_asc_sr" value="0" checked="checked" />
                <label for="radio454" class="cb-enable"><span>On</span></label>
                <label for="radio453" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_asc_text_sr) { ?>
                <input type="text" name="config_date_added_asc_text_sr" value="<?php echo $config_date_added_asc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_asc_text_sr" value="<?php echo $entry_date_added_asc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added_desc; ?></td>
              <td class="switch"><?php if ($config_date_added_desc_sr) { ?>
                <input type="radio" id="radio451" name="config_date_added_desc_sr" value="1" checked="checked" />
                <input type="radio" id="radio452" name="config_date_added_desc_sr" value="0" />
                <label for="radio451" class="cb-enable selected"><span>On</span></label>
                <label for="radio452" class="cb-disable"><span>Off</span></label>
                <?php } else { ?>
                <input type="radio" id="radio451" name="config_date_added_desc_sr" value="1" />
                <input type="radio" id="radio452" name="config_date_added_desc_sr" value="0" checked="checked" />
                <label for="radio451" class="cb-enable"><span>On</span></label>
                <label for="radio452" class="cb-disable selected"><span>Off</span></label>
                <?php } ?></td>
                <td>
                <?php if ($config_date_added_desc_text_sr) { ?>
                <input type="text" name="config_date_added_desc_text_sr" value="<?php echo $config_date_added_desc_text_sr; ?>" size="40" />
                <?php } else { ?>
                <input type="text" name="config_date_added_desc_text_sr" value="<?php echo $entry_date_added_desc; ?>" size="40" />
                <?php } ?>
                </td>
            </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>