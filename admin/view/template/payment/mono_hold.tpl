

<style>
</style>

<div class="table-responsive card-body" id="hold_window" style="max-width: 400px; margin: 10px 0 0 5vw;">
   <form id="refund_form_<?php echo $payment_id; ?>" method="post"
                              action="#" 
                              class="form-horizontal" style="display:block" onsubmit="return false">
                              <input type="hidden" id="amount" value="<?php echo $amount; ?>" />
                            <input type="hidden" id="payment_id" value="<?php echo $payment_id; ?>" />
                                <button type="submit" name="mono_hold" id="mono_hold" class="btn btn-primary">Сonfirm Hold</button>
                        </form>
</div>
<script type="text/javascript"><!--
 document.addEventListener('DOMContentLoaded', function() {
    $('#mono_hold').click(function(e){
        var mono_amount = $('#amount').val();
        var payment_id = $('#payment_id').val();
        
        $.ajax({
		url: 'index.php?route=payment/mono/hold&token=<?php echo $token; ?>',
                type: 'post',
                data: 'order_id=<?php echo $order_id; ?>&payment_id='+payment_id+'&mono_amount='+mono_amount,
		dataType: 'json',
		beforeSend: function() {
                    $('#mono_hold').button('loading');
		},
		complete: function() {
                    $('#mono_hold').button('reset');
		},
		success: function(json) {
                document.location.reload();
		},
		error: function(xhr, ajaxOptions, thrownError) {
                    document.location.reload();
		}
	});
    });
}, false)
//--></script> 
