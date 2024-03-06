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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
   	<div class="heading order_head">
      <h1><img src="view/image/user-group.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
		<div class="clear:both"></div>
    <div class="content">    
        <table class="list">
          <thead>
            <tr>  
              <td class="left"></td>
              <td class="right"><?php if ($sort == 'date_end') { ?>
                <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>">Время звонка</a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_end; ?>">Время звонка</a>
                <?php } ?></td>
			  <td class="center">Длительность</td>
			  <td class="center">Покупатель</td>
			  <td class="center">Телефон покупателя</td>
			  <td class="center">Направление</td>
			  <td class="center">Внутр. телефон</td>
			  <td class="center">Послушать</td>
            </tr>
          </thead>
		  <tr class="filter">
			 <td class="left"></td>
              <td class="center"><input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" size="12" id="date" /></td>
			  <td class="right"></td>
			  <td class="right"><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" size="15" /></td>
			  <td class="right"><input type="text" name="filter_telephone" value="<?php echo $filter_telephone; ?>" size="15" /></td>
			  <td class="center"></td>
			  <td></td>
              <td align="right"><a onclick="filter();" class="button">Фильтр</a></td>
		  
		  </tr>
          <tbody>
            <?php if ($user_calls) { ?>
            <?php foreach ($user_calls as $user_call) { ?>
            <tr>
              <td class="left" style="width:1px;"><?php echo $user_call['customer_call_id']; ?></td>
			  <td class="right"><?php echo $user_call['date_end']; ?></td>
              <td class="right">
			  <? if ($user_call['length'] > 0) { ?>
				<?php echo $user_call['length']; ?> сек.
			  <? } else { ?>
				<span style='color:white; padding:3px; background:#ff5656;'>пропущ.</span>
			  <? } ?>
			</td>
			  
			  <? if ($user_call['customer_link']) { ?>
				<td class="right">
				<a style="font-size:14px;" href='<?php echo $user_call['customer_link']; ?>' target='_blank'><?php echo $user_call['customer_name']; ?></a><br />
				<a style="font-size:11px;" href='<?php echo $user_call['customer_id_link']; ?>' target='_blank'>фильтр</a>
				</td>
			  <? } else { ?>
				<td class="right"><?php echo $user_call['customer_name']; ?></td>
			  <? } ?>
			  
			  <td class="right"><a href="<?php echo $user_call['customer_phone_link']; ?>"><?php echo $user_call['customer_phone']; ?></a><span class='click2call' data-phone="<?php echo $user_call['customer_phone']; ?>"></span></td>
			  
			   <? if ($user_call['inbound']) { ?>
				<td class="center"><i style="font-size:14px; color:#2c82b8" class='fa fa-arrow-right'></i></td>
			  <? } else { ?>
				<td class="center"><i style="font-size:14px; color:#85B200" class='fa fa-arrow-left'></i></td>
			  <? } ?>
			  
			  <td class="center"><?php echo $user_call['internal_pbx_num']; ?></td>
			  
			  
			   <td class="right"><? if ($user_call['length'] > 0 && $user_call['filename']) { ?>
			   <audio src="<? echo $user_call['filename']; ?>" controls preload='none'></audio>&nbsp;
			   <a href="<? echo $user_call['filename']; ?>" style="text-decoration:none;"><i class="fa fa-download"></i></a>
			   <? } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3">Нету звонков!</td>
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
	url = 'index.php?route=user/user_sip/history&user_id=<? echo $user_id; ?>&token=<?php echo $token; ?>';

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_telephone = $('input[name=\'filter_telephone\']').attr('value');
	
	if (filter_telephone) {
		url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
	}

	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?> 