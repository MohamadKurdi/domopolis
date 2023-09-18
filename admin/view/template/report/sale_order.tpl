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
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_date_start; ?><br/>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $entry_date_end; ?><br/>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
          <td><?php echo $entry_group; ?><br/>
            <select name="filter_group">
              <?php foreach ($groups as $groups) { ?>
              <?php if ($groups['value'] == $filter_group) { ?>
              <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_status; ?><br/>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_date_start; ?></td>
            <td class="left"><?php echo $column_date_end; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_products; ?></td>
            <?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
                 <td class="right">Минимальная рентабельность</td>
                 <td class="right">Средняя рентабельность</td>
                 <td class="right">Максимальная рентабельность</td>
            <?php } ?>
            <td class="right">Средний чек, <?php echo $this->config->get('config_currency');?></td>
            <td class="right">Средний чек, <?php echo $this->config->get('config_regional_currency');?></td>
            <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_currency');?></td>
            <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_regional_currency');?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="left">
              <b><?php echo $order['date_start']; ?></b>
            </td>
            <td class="left">
              <b><?php echo $order['date_end']; ?></b>
            </td>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#CF4A61; color:#FFF; white-space:nowrap;"><?php echo $order['orders']; ?></span>
            </td>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#FF7815; color:#FFF; white-space:nowrap;"><?php echo $order['products']; ?></span>
            </td>

            <?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
               <td class="right">
                <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['min_profitability']; ?> %</span>
              </td>

              <td class="right">
                <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['avg_profitability']; ?> %</span>
              </td>

              <td class="right">
                <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['max_profitability']; ?> %</span>
              </td>
            <?php } ?>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#7F00FF; color:#FFF; white-space:nowrap;"><?php echo $order['avg_total']; ?></span>
            </td>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#7F00FF; color:#FFF; white-space:nowrap;"><?php echo $order['avg_total_national']; ?></span>
            </td>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#51A62D; color:#FFF; white-space:nowrap;"><?php echo $order['total']; ?></span>
            </td>

            <td class="right">
              <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#51A62D; color:#FFF; white-space:nowrap;"><?php echo $order['total_national']; ?></span>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
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
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>