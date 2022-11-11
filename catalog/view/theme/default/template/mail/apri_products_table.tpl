<table style="border-collapse: collapse; width: 100%; border-top: 1px dotted <?php echo $table_border_color; ?>;  margin-bottom: 20px;">
<tbody>
  <?php foreach ($products as $product) { ?>
  <tr>
	<td style="font-size: 12px;	text-align: left; border-bottom: 1px dotted <?php echo $table_border_color; ?>; background-color: <?php echo $table_body_bg; ?>; color: <?php echo $table_body_text_color; ?>; padding: 7px; text-decoration: none;"><a href="<?php echo $product['href']; ?>" target="blank"><img src="<?php echo $product['image']; ?>" /></a></td>
	<td style="font-size: 12px;	text-align: left; border-bottom: 1px dotted <?php echo $table_border_color; ?>; background-color: <?php echo $table_body_bg; ?>; color: <?php echo $table_body_text_color; ?>; padding: 7px; text-decoration: none;"><a href="<?php echo $product['href']; ?>" target="blank"><?php echo $product['name']; ?></a></td>
	<td style="font-size: 12px;	text-align: left; border-bottom: 1px dotted <?php echo $table_border_color; ?>; background-color: <?php echo $table_body_bg; ?>; color: <?php echo $table_body_text_color; ?>; padding: 7px; text-decoration: none;"><a href="<?php echo $product['href']; ?>" target="blank"><img src="<?php echo $add_review_image; ?>" /></a></td>
  </tr>
  <?php } ?>
</tbody>
</table>

