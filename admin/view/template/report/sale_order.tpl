<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>

      <div class="buttons">
        <a href="<?php echo $download_xlsx; ?>" target="_blank" class="button"><i class="fa fa-file-excel-o"></i> XLSX</a>
        <a onclick="filter();" class="button"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></a>
      </div>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td>
            <p><?php echo $entry_date_start; ?></p>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" />
          </td>
          <td>
            <p><?php echo $entry_date_end; ?></p>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" />
          </td>
          <td>
            <p><?php echo $entry_group; ?></p>
            <select name="filter_group">
              <?php foreach ($groups as $groups) { ?>
                <?php if ($groups['value'] == $filter_group) { ?>
                  <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </td>
          <td>
            <p><?php echo $entry_status; ?></p>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </td>
          <td>
            <p>Категория <span style="border-bottom:1px dashed #CF4A61; cursor:pointer; color:#CF4A61;" onclick="$('#filter_category_path').val(''); $('#filter_category_id').val('0');">очистить</span></p>
            <input type="text" name="filter_category_path" id="filter_category_path" value="<?php echo $filter_category_path; ?>" placeholder="Автодополнение" size="60" />
            <input type="number" step="1" name="filter_category_id" id="filter_category_id" value="<?php echo $filter_category_id; ?>" size="9" />
          </td>
          <td>
            <p>Разбивка по конечным</p>
            <select name="filter_divide_by_categories">
              <?php if ($filter_divide_by_categories) { ?>
                <option value="1" selected="selected">Разбить по конечным</option>
                <option value="0">Не разбивать</option>
              <?php } else { ?>
                 <option value="1">Разбить по конечным</option>
                <option value="0"  selected="selected">Не разбивать</option>
              <?php } ?>
            </select>
          </td>
        </tr>
      </table>
      <div id="report-results">  
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_date_start; ?></td>
              <td class="left"><?php echo $column_date_end; ?></td>
              <td class="center">Заказов</td>

              <?php if ($this->config->get('config_enable_amazon_specific_modes')){ ?>
                <?php foreach (\hobotix\RainforestAmazon::amazonOffersType as $type) { ?>
                  <td class="center" style="width:50px;"><?php echo $type; ?></td>
                <?php } ?>
              <?php } ?>

              <td class="center">Товаров</td>

              <?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
               <td class="right">Мин. рентабельность</td>
               <td class="right">Ср. рентабельность</td>
               <td class="right">Макс рентабельность</td>
             <?php } ?>

             <td class="right">Ср. чек, <?php echo $this->config->get('config_currency');?></td>
             <td class="right">Ср. чек, <?php echo $this->config->get('config_regional_currency');?></td>
             <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_currency');?></td>
             <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_regional_currency');?></td>
           </tr>
         </thead>
         <tbody>
          <?php if (!empty($orders)) { ?>
            <?php foreach ($orders as $order) { ?>
              <?php include(dirname(__FILE__) . '/sale_order_orders.tpl'); ?>
            <?php } ?>
          <?php } ?>          

          <?php if (!empty($categories)) { ?>
            <?php foreach ($categories as $category) { ?>
              <tr>
                <td style="border-top:2px solid black;" colspan="<?php if ($this->config->get('config_show_profitability_in_order_list')) { ?>16<?php } else { ?>11<?php } ?>">
                  <b style="font-size:16px; color:<?php if ($category['orders']) { ?>#51A62D<?php } else { ?>#e16a5d<?php } ?>"><?php echo $category['category']; ?></b>
                </td>
              </tr>

              <?php foreach ($category['orders'] as $order) { ?>
                <?php include(dirname(__FILE__) . '/sale_order_orders.tpl'); ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function loadxls(){
       $.ajax({
          url:        'index.php?route=report/sale_order/xls&token=<?php echo $token; ?>',
          dataType:   'html',
          type:       'POST',
          data:{
            html : $('#report-results').html()
          }
       });
  }
</script>

<script type="text/javascript">
    $('input[name=\'filter_category_path\']').autocomplete({
      delay: 500,
      source: function(request, response) {   
        $.ajax({
          url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
          dataType: 'json',
          success: function(json) {
            json.unshift({
              'category_id':  0,
              'name':  '<?php echo $text_none; ?>'
            });
            
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item.category_id
              }
            }));
          }
        });
      },
      select: function(event, ui) {
        $('input[name=\'filter_category_path\']').val(ui.item.label);
        $('input[name=\'filter_category_id\']').val(ui.item.value);
        
        return false;
      },
      focus: function(event, ui) {
        return false;
      }
    });
</script> 

<script type="text/javascript">
function filter() {
	url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_category_id = $('input[name=\'filter_category_id\']').attr('value');
  
  if (filter_category_id) {
    url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
  }

  var filter_divide_by_categories = $('select[name=\'filter_divide_by_categories\']').attr('value');
  
  if (filter_divide_by_categories) {
    url += '&filter_divide_by_categories=' + encodeURIComponent(filter_divide_by_categories);
  }
		
	var filter_group = $('select[name=\'filter_group\']').attr('value');
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
}
</script> 
<script type="text/javascript">
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
</script> 
<?php echo $footer; ?>