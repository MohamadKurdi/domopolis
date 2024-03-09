

<style>
</style>

<div class="table-responsive card-body" id="refund_window" style="max-width: 400px;
        margin: 10px 0 0 5vw;">
    <table class="table mono-subtable">
        <?php if($is_refunded){?>
            <tr>
                <th>Invoice Id</th>
                <td class="value"><?php echo $payment_id;?></td>
            </tr>
            <tr>
                <th>Amount Refunded</th>
                <td class="value"><?php echo $amount_refunded;?></td>
            </tr>
      <?php }else{?>
            <tr>
                <th>Invoice Id</th>
                <td class="value"><?php echo $payment_id;?></td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td class="value"><?php echo $amount;?></td>
            </tr>
            <tr>
                <td colspan="2" class="value">
                    <strong>
                        <span id="refund_span_<?php echo $payment_id;?>">
                        <a class="btn btn-primary" 
                           href="javascript:void(0);" 
                           onclick="$('#refund_span_<?php echo $payment_id;?>').hide();$('#refund_form_<?php echo $payment_id;?>').show();">
                            Refund
                        </a>
                        </span>
                        <form id="refund_form_<?php echo $payment_id;?>" method="post"
                              action="#" 
                              class="form-horizontal" style="display:none" onsubmit="return false">
                            <div class="">
                                    <label for="mono_amount" class="form-control-label label-on-top col-12">
                                        Enter the amount<span class="text-danger">*</span>
                                    </label>
                                 <div class="col-sm">
                                    <div class="input-group">
                                        <input type="text" id="mono_amount" required="required" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="text-left">
                                <button 
                                    type="button" 
                                    class="btn btn-secondary" 
                                    onclick="$('#refund_span_<?php echo $payment_id;?>').show();$('#refund_form_<?php echo $payment_id;?>').hide();">
                                    Cancel
                                </button>
                                
                                <input type="hidden" id="payment_id" value="<?php echo $payment_id;?>" />
                                <button type="submit" name="mono_refund" id="mono_refund" class="btn btn-primary">Refund Payment</button>
                            </div>
                        </form>
                    </strong>
                </td>
            </tr>
       <?php } ?>
    </table>
</div>
<script type="text/javascript">
 document.addEventListener('DOMContentLoaded', function() {
    $('#mono_refund').click(function(e){
        var mono_amount = $('#mono_amount').val();
        var payment_id = $('#payment_id').val();
        
        $.ajax({
		url: 'index.php?route=payment/mono/refund&token=<?php echo $user_token;?>',
                type: 'post',
                data: 'order_id=<?php echo $order_id;?>&payment_id='+payment_id+'&mono_amount='+mono_amount,
		dataType: 'json',
		beforeSend: function() {
                    $('#mono_refund').button('loading');
		},
		complete: function() {
                    $('#mono_refund').button('reset');
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

</script> 
