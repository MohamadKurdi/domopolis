<a class="top"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-warning" ></i>&nbsp;&nbsp;<?php echo $total_notification; ?></span></a>
<ul style="width: 196px;">
	<li><a href="<?php echo $order; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_new_order; ?><span class="label pull-right<?php echo ($total_new_order == '0' ? ' label-warning' : ' label-danger'); ?>"><?php echo $total_new_order; ?></span></a></li>
	
	<li><a href="<?php echo $order; ?>&filter_order_status_id=1"><?php echo $text_pending_order; ?><span class="label pull-right<?php echo ($total_pending_order == '0' ? ' label-warning' : ' label-danger'); ?>"><?php echo $total_pending_order; ?></span></a></li>
	
	<li><a href="<?php echo $customer; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_new_customer; ?><span class="label pull-right<?php echo ($total_new_customer == '0' ? ' label-success' : ' label-danger'); ?>"><?php echo $total_new_customer; ?></span></a></li>
	
	<li><a href="<?php echo $customer; ?>&filter_approved=0"><?php echo $text_pending_customer; ?><span class="label pull-right<?php echo ($total_customer_approval == '0' ? ' label-success' : ' label-danger'); ?>"><?php echo $total_customer_approval; ?></span></a></li>
	
	<li><a href="<?php echo $product; ?>&filter_quantity=0"><?php echo $text_stockout; ?><span class="label pull-right<?php echo ($total_stockout == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_stockout; ?></span></a></li>
	
	<li><a href="<?php echo $return; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_return; ?><span class="label pull-right<?php echo ($total_new_return == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_new_return; ?></span></a></li>
	
	<li><a href="<?php echo $review; ?>&sort=r.status&order=ASC"><?php echo $text_pending_review; ?><span class="label pull-right<?php echo ($total_review_approval == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_review_approval; ?></span></a></li>
	
	<li><a href="<?php echo $affiliate; ?>&filter_approved=0"><?php echo $text_pending_affiliate; ?><span class="label pull-right<?php echo ($total_affiliate_approval == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_affiliate_approval; ?></span></a></li>
</ul>