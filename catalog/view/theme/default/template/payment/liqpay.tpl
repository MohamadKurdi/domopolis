<form method="POST" action="<?=$action?>" id="liqpay" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data?>" />
    <input type="hidden" name="signature" value="<?=$signature?>" />
    <div class="buttons">
        <div class="right">
             <table class="pay"><tr><td><img src="/image/data/payment/liqpay_logo.png" /></td><td><input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" /></td></tr></table>
        </div>
    </div>
</form>

<script>
$("input#button-confirm").click(function() {
    $.ajax({
        type: 'get',
        url: '<?=$url_confirm?>',
        success: function() {
            $("form#liqpay").submit();
        }
    });
    return false;
});
</script>
