<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?> : <?php echo $return_id; ?></h1>
      <div class="buttons"><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">

     <div id="tab-return" style="width:45%; float:left; margin-right:10px;">
      <table class="form">
        <tr>
          <td><?php echo $text_return_id; ?></td>
          <td><?php echo $return_id; ?></td>
        </tr>
        <?php if ($order) { ?>
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></td>
          </tr>
        <?php } else { ?>
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td><?php echo $order_id; ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td><?php echo $text_date_ordered; ?></td>
          <td><?php echo $date_ordered; ?></td>
        </tr>
        <?php if ($customer) { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
        <?php } else { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td><?php echo $text_email; ?></td>
          <td><?php echo $email; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_telephone; ?></td>
          <td><?php echo $telephone; ?></td>
        </tr>
        <?php if ($return_status) { ?>
          <tr>
            <td><?php echo $text_return_status; ?></td>
            <td id="return-status"><?php echo $return_status; ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td><?php echo $text_date_added; ?></td>
          <td><?php echo $date_added; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_date_modified; ?></td>
          <td><?php echo $date_modified; ?></td>
        </tr>
      </table>
    </div>

    <div id="tab-product" style="width:45%; float:left;">
      <table class="form">
        <tr>
          <td><?php echo $text_product; ?></td>
          <td><?php echo $product; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_model; ?></td>
          <td><?php echo $model; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_quantity; ?></td>
          <td><?php echo $quantity; ?></td>
        </tr>
        <tr>
          <td>Цена товара</td>
          <td><?php echo $price; ?></td>
        </tr>
        <tr>
          <td>Сумма возврата</td>
          <td><?php echo $total; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_return_reason; ?></td>
          <td><?php echo $return_reason; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_opened; ?></td>
          <td><?php echo $opened; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_return_action; ?></td>
          <td><select name="return_action_id">
            <option value="0"></option>
            <?php foreach ($return_actions as $return_action) { ?>
              <?php if ($return_action['return_action_id'] == $return_action_id) { ?>
                <option value="<?php echo $return_action['return_action_id']; ?>" selected="selected"><?php echo $return_action['name']; ?></option>
              <?php } else { ?>
                <option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
              <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <?php if ($comment) { ?>
          <tr>
            <td><?php echo $text_comment; ?></td>
            <td><?php echo $comment; ?></td>
          </tr>
        <?php } ?>
      </table>
    </div>



    <div id="tab-history">
      <div id="history"></div>
      <table class="form">
        <tr>
          <td><?php echo $entry_return_status; ?></td>
          <td><select name="return_status_id">
            <?php foreach ($return_statuses as $return_status) { ?>
              <?php if ($return_status['return_status_id'] == $return_status_id) { ?>
                <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
              <?php } else { ?>
                <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
              <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_notify; ?></td>
          <td>
            <input id="notify" class="checkbox" type="checkbox" name="notify" value="1" />
            <label for="notify"></label>
          </td>
        </tr>
        <tr id="summaryRow" style="display: none">
          <td><?php echo $entry_summary; ?></td>
          <td>
            <label><input type="checkbox" name="show_summary" value="1" style="vertical-align: middle;" /> <?php echo $entry_show_summary; ?></label><br />
            <?php if(!empty($templates_options)){ ?>      
            </td>
          </tr>
          <tr class="emailOptions" style="display: none">
            <td><?php echo $entry_template; ?></td>
            <td>
              <select id="field_templates" name="field_template">
                <option value=''><?php echo $text_select; ?></option>
                <?php foreach($templates_options as $item){ ?>
                  <option value="<?php echo $item['value']; ?>"><?php echo $item['label']; ?></option>
                <?php } ?>
              </select>
            <?php } ?>
          </tr>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>
              <div style="margin-top: 10px; text-align: right;"><a onclick="history();" id="button-history" class="button"><?php echo $button_add_history; ?></a></div></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

   <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
   <script type="text/javascript"><!--
      // Order history show/hide summary options
      (function($){
        function showEmailOptions($row, $checkbox){
          if($checkbox.is(':checked')) { 
            $row.show(); 
          } else { 
            $row.hide(); 
          }
        } 

        $(document).ready(function() {
          $('input[name=notify]').eq(0).each(function(){
            showEmailOptions($('.emailOptions'), $(this));
          }).change(function(){
            showEmailOptions($('.emailOptions'), $(this));
          });

          $('select#field_templates').change(function(){      
            var val = $(this).val();
            if (!val || !confirm("<?php echo $warning_template_content; ?>")) return;
            $.ajax({
              url: '<?php echo html_entity_decode($templates_action); ?>',
              type: 'get',
              data: 'id=' + val + '&store_id=<?php echo $store_id; ?>' + '&language_id=<?php echo $language_id; ?>' + '&order_id=<?php echo $order_id; ?>',
              dataType: 'html',
              success: function(html) {
                $('textarea[name=comment]').val(html);

                if(typeof CKEDITOR !== "undefined")
                  CKEDITOR.instances['comment'].setData(html);
              },
              error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                alert('Error. More details in console.');
              }
            }); 
          });
        }); 
      })(jQuery);   
      
      if(typeof CKEDITOR !== "undefined"){
        CKEDITOR.replace('comment', {
          filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
          filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
          filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
        }); 

        // Output dimensions of images as width and height
        CKEDITOR.on('instanceReady', function (ev) {
          ev.editor.dataProcessor.htmlFilter.addRules({
            elements: {
              $: function(element){                
                if (element.name == 'img') {
                  var style = element.attributes.style;

                  if (style) {
                    // Get the width from the style.
                    var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                    width = match && match[1];

                    // Get the height from the style.
                    match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                    var height = match && match[1];

                    if (width) {
                      element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                      element.attributes.width = width;
                    }

                    if (height) {
                      element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                      element.attributes.height = height;
                    }
                  }
                }

                if (!element.attributes.style) delete element.attributes.style;

                return element;
              }
            }
          });
        }); 
      }


      if(typeof CKEDITOR !== "undefined")
        CKEDITOR.instances.comment.updateElement();
    </script>

    <script type="text/javascript"><!--
    $('select[name=\'return_action_id\']').bind('change', function() {
     $.ajax({
      url: 'index.php?route=sale/return/action&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
      type: 'post',
      dataType: 'json',
      data: 'return_action_id=' + this.value,
      beforeSend: function() {
       $('.success, .warning, .attention').remove();

       $('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
     },
     success: function(json) {
       $('.success, .warning, .attention').remove();

       if (json['error']) {
        $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');

        $('.warning').fadeIn('slow');
      }

      if (json['success']) {
        $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');

        $('.success').fadeIn('slow');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
     alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   }
 });	
   });

    $('#history .pagination a').live('click', function() {
     $('#history').load(this.href);

     return false;
   });			

    $('#history').load('index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>');

    function history() {
     $.ajax({
      url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
      type: 'post',
      dataType: 'html',
      data: 'return_status_id=' + encodeURIComponent($('select[name=\'return_status_id\']').val()) + '&show_summary=' + encodeURIComponent($('input[name=\'show_summary\']').attr('checked') ? 1 : 0) 
      + ($('select[name=\'field_template\']').val() ? '&field_template=' + $('select[name=\'field_template\']').val() : '')
      + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
      beforeSend: function() {
       $('.success, .warning').remove();
       $('#button-history').attr('disabled', true);
       $('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
     },
     complete: function() {
       $('#button-history').attr('disabled', false);
       $('.attention').remove();
     },
     success: function(html) {
       $('#history').html(html);

       $('textarea[name=\'comment\']').val(''); 

       if(typeof CKEDITOR !== "undefined")
              CKEDITOR.instances.comment.setData('');

       $('#return-status').html($('select[name=\'return_status_id\'] option:selected').text());
     }
   });
   }
   //--></script> 
   <?php echo $footer; ?>