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
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_affiliate; ?></td>
            <td class="left"><?php echo $column_email; ?></td>
            <td class="right"><?php echo $column_count_transitions; ?></td>
            <td class="right"><?php echo $column_count_orders; ?></td>
            <td class="right"><?php echo $column_count_shopping; ?></td>
            <td class="right"><?php echo $column_sum_orders; ?></td>
            <td class="right"><?php echo $column_sum_shopping; ?></td>
            <td class="right"><?php echo $column_sum_credited; ?></td>
            <td class="right"><?php echo $column_sum_paid; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($affiliates) { ?>
          <?php foreach ($affiliates as $affiliate) { ?>
          <tr>
            <td class="left"><?php echo $affiliate['affiliate']; ?></td>
            <td class="left"><?php echo $affiliate['email']; ?></td>
            <td class="right"><?php echo $affiliate['count_transitions']; ?></td>
            <td class="right"><?php echo $affiliate['count_orders']; ?><br /><a class="button" href="<? echo $affiliate['link']; ?>"><i class="fa fa-eye"></i></a></td>
            <td class="right"><?php echo $affiliate['count_shopping']; ?></td>
            <td class="right"><?php echo $affiliate['sum_orders']; ?></td>
            <td class="right"><?php echo $affiliate['sum_shopping']; ?></td>
            <td class="right"><?php echo $affiliate['sum_credited']; ?></td>
            <td class="right"><?php echo $affiliate['sum_paid']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=report/affiliate_statistics&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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