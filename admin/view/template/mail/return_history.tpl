<div class="emailContent">{$emailtemplate.content1}</div>

<?php if(!empty($comment)){ ?>
	<br /><br />
	<strong><?php echo $text_comment; ?></strong><br />
	<?php echo $comment; ?>
<?php } ?>

<?php /* PACKAGE TRACKING SERVICE */ 
if (!empty($pts['tracker_carrier_name'])) { ?>
	<?php if (!empty($pts['tracker_thumb'])) { ?>
  		<img src="<?php echo $pts['tracker_thumb']; ?>" width="210" height="70" alt="<?php echo $pts['tracker_carrier_name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" />
	<?php } ?>
	
	<div class="link">
  		<?php echo htmlentities($pts['tracker_carrier_name'], ENT_QUOTES, 'UTF-8'); ?>
		- <strong><?php echo htmlentities(implode(', ', $pts['tracking_numbers']), ENT_QUOTES, 'UTF-8'); ?></strong>
  		<?php 
  		if ($pts['tracker_status']) { 
	    	if ($tracking_links_count = count($pts['tracking_links'])) { ?>
	      		<br /><br /><b><?php echo htmlentities($pts['text_tracking_links'], ENT_QUOTES, 'UTF-8'); ?></b>
	      		<?php for ($k = 0; $k < $tracking_links_count; $k++) { ?>
	        		<br /><span class="icon">&raquo;</span>&nbsp;<a href="<?php echo $pts['tracking_links'][$k]; ?>"><b><?php echo htmlentities($pts['tracking_links'][$k], ENT_QUOTES, 'UTF-8'); ?></b></a>
	        	<?php }
	    	}
  		} ?>
  </div>
<?php } ?>


<?php if(!empty($show_summary)){ ?>
	<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="20">&nbsp;</td></tr></table>
	
	<table cellpadding="0" cellspacing="0" width="100%" class="table1">
	<thead>
		<tr>
	       	<th bgcolor="#ededed" align="center"><b><?php echo $text_product; ?></b></th>
	       	<th bgcolor="#ededed" align="center"><b><?php echo $text_return; ?></b></th>
	       	<th bgcolor="#ededed" align="center"><b><?php echo $text_opened; ?></b></th>
		</tr>
	</thead>
	<tbody>
	<?php $row_style_background = "#fafafa"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>">
				<?php echo $name; ?>
				<ul class="list">
					<?php if($model){ ?><li><strong><?php echo $text_model; ?></strong>&nbsp;<?php echo $model; ?></li><?php } ?>
					<?php if($quantity){ ?><li><strong><?php echo $text_quantity; ?></strong>&nbsp;<?php echo $quantity; ?></li><?php } ?>
				</ul>
			</td>
			<td bgcolor="<?php echo $row_style_background; ?>">
				<?php echo $reason; ?>
			</td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="center">
				<?php echo $opened; ?>
			</td>
		</tr>
	</tbody>
	</table>
<?php } ?>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

<div class="emailContent">{$emailtemplate.content2}</div>