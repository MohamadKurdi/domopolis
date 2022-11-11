<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <h1><?php echo $heading_title; ?></h1>
 <div class="interplusplus">
 <p><?php echo $send_text . $inv_id . $send_text2 . $out_summ ; ?> </p>
 <div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a href="<?php echo $merchant_url;?>" class="button" id="inter"><span><?php echo $button_pay; ?></span></a></td>
    </tr>
  </table>
</div>
</div>
  </div>
  <?php echo $content_bottom; ?>
<?php echo $footer; ?>