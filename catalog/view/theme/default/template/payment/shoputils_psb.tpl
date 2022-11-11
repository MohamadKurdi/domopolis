
<?php if ($instruction) { ?>
  <div class="content"><p><?php echo $instruction; ?></p></div>
<?php } ?>
<form action="<?php echo $action ?>" method="post" id="checkout">
    <?php foreach ($parameters as $key=>$value) { ?>
      <?php if (is_array($value)) { ?>
        <?php foreach ($value as $val) { ?>
          <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>"/>
        <?php } ?>
      <?php } else { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>
      <?php } ?>
    <?php } ?>
</form>
<div class="buttons">
    <div class="right">
        <input type="button" class="button" id="button-confirm" value="Подтвердить и оплатить заказ" />
    </div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
    $.ajax({
        type: 'get',
        url: 'index.php?route=payment/shoputils_psb/confirm',
        beforeSend: function() {
            $('#button-confirm').attr('disabled', true);
            $('#button-confirm').after('<img src="catalog/view/theme/default/image/loading.gif" alt="" />');
        },
    success: function() {
      <?php if ($pay_status) { ?>
        document.forms['checkout'].submit();
      <?php } else { ?>
        location = '<?php echo $continue; ?>';
      <?php } ?>
   }
    });
});
//--></script>
</div>
