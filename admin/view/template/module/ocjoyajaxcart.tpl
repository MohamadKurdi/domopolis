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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="vtabs">
          <a href="#ocjoyajaxcart-settingmain"><?php echo $text_main_tab_setting; ?></a>
          <a href="#ocjoyajaxcart-licence"><?php echo $text_licence; ?></a>
        </div>
        <div id="ocjoyajaxcart-settingmain" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_heading_forproducts; ?></td>
              <td><input type="text" name="config_textforproducts" value="<?php echo $config_textforproducts; ?>" size="100" /></td>
            </tr>
            <tr>
              <td><?php echo $text_ocjoyajaxcart_countname; ?></td>
              <td><input type="text" name="config_ocjoyajaxcart_countname" value="<?php echo $config_ocjoyajaxcart_countname; ?>" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo $text_ocjoyajaxcart_countdesc; ?></td>
              <td><input type="text" name="config_ocjoyajaxcart_countdesc" value="<?php echo $config_ocjoyajaxcart_countdesc; ?>" size="50" /></td>
            </tr>
            <tr>
              <td><?php echo $text_ocjoyajaxcart_showsku; ?></td>
              <td>
                  <select name="config_ocjoyajaxcart_showsku">
                      <?php if ($config_ocjoyajaxcart_showsku == "1") { ?>
                      <option value="1" selected="selected"><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } elseif ($config_ocjoyajaxcart_showsku == "2") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" selected="selected"><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } else { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  selected="selected"><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $text_ocjoyajaxcart_showmodel; ?></td>
              <td>
                  <select name="config_ocjoyajaxcart_showmodel">
                      <?php if ($config_ocjoyajaxcart_showmodel == "1") { ?>
                      <option value="1" selected="selected"><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } elseif ($config_ocjoyajaxcart_showmodel == "2") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" selected="selected"><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } else { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_yes; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_no; ?></option>
                      <option value=""  selected="selected"><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_type_productsincart; ?></td>
              <td>
                  <select name="config_type_ap" id="type_ap_change">
                      <?php if ($config_type_ap == "1") { ?>
                      <option value="1" selected="selected"><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" ><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } elseif ($config_type_ap == "2") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" selected="selected"><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" ><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                       <?php } elseif ($config_type_ap == "3") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" selected="selected"><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" ><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                       <?php } elseif ($config_type_ap == "4") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" selected="selected"><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" ><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                       <?php } elseif ($config_type_ap == "5") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" selected="selected"><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
					  <?php } elseif ($config_type_ap == "6") { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5"><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" selected="selected">Последние заказанные</option>
                      <option value=""  ><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } else { ?>
                      <option value="1" ><?php echo $text_ocjoyajaxcart_bycategory; ?></option>
                      <option value="2" ><?php echo $text_ocjoyajaxcart_viewed; ?></option>
                      <option value="3" ><?php echo $text_ocjoyajaxcart_special; ?></option>
                      <option value="4" ><?php echo $text_ocjoyajaxcart_bestsellers; ?></option>
                      <option value="5" ><?php echo $text_ocjoyajaxcart_latest; ?></option>
					  <option value="6" >Последние заказанные</option>
                      <option value=""  selected="selected"><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                      <?php } ?>
                  </select>
              </td>
            </tr>
            
          <tr id="type_ap_categories" style="display:none;">
              <td><?php echo $entry_select_categories; ?></td>
              <td><select name="config_parent_id">
                  <option value="0"><?php echo $text_ocjoyajaxcart_makeachoice; ?></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $config_parent_id) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
            </td>
            </tr>

            <script type="text/javascript">
              $(document).ready(function() {
                if ($('#type_ap_change').val() == '1') {
                  $('#type_ap_categories').show();
                };
                $('#type_ap_change').change(function(){
                  if($('#type_ap_change').val() == '1'){
                    $('#type_ap_categories').show();
                  } else {
                    $('#type_ap_categories').hide();
                  }
                });
              });
            </script>
          </table>
      </div>
        <div id="ocjoyajaxcart-licence" class="vtabs-content" style="min-height:400px;">
          <table class="form">
            <tr>
              <td><?php echo $text_youruidcode; ?></td>
              <td><input value="<?php echo base64_encode(DIR_SYSTEM.':'.$_SERVER['SERVER_NAME']); ?>" type="text" size="100"  readonly /></td>
            </tr>
            <tr>
              <td><?php echo $text_activationcode; ?></td>
              <td><input value="<?php echo $config_ukey_sc; ?>" type="text" name="config_ukey_sc" size="100" /></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
    <div id="ocjoy-copyright"><?php echo $text_copyright; ?></div>
  </div>
</div>

<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<style type="text/css">
#ocjoy-copyright {padding:15px 15px;border:1px solid #ccc;margin-top:15px;box-shadow:0 0px 5px rgba(0,0,0,0.1);}
</style>
<?php echo $footer; ?>