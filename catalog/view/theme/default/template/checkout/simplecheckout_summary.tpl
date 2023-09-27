<div class="simplecheckout-block cart__total" id="simplecheckout_summary" <?php echo $hide ? 'data-hide="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <h3 class="title"><?php echo $text_summary ?></h3>
	<?php } ?>
	
		
    <?php if (isset($modules['coupon'])) { ?> 		
		<?php if ($cart_has_additional_offer) { ?>
			  <div class="simplecheckout-cart-total promo-code no_coupon">
				<span class="promo-code-txt"><?php echo $text_retranslate_no_coupon; ?></span>
			  </div>
		<?php } elseif ($cart_is_using_credit_payments) { ?>
				<div class="simplecheckout-cart-total promo-code no_coupon">
				<span class="promo-code-txt"><?php echo $text_retranslate_no_coupon_credit; ?></span>
			  </div>
		<?php } else { ?>		
        <div class="simplecheckout-cart-total promo-code">
            <span class="inputs"><input class="form-control field" type="text" data-onchange="reloadAll" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" /></span>
            <span class="inputs buttons"><a id="simplecheckout_button_cart"  class="button btn-primary button_oc btn oct-button"><span class="promo-code-txt"><?php echo $text_retranslate_15; ?></span></a></span>
		</div>
		<?php } ?>
	<?php } ?>
	
    <?php if (isset($modules['voucher'])) { ?>
    	<?php if ($cart_is_using_credit_payments) { ?>
    		<div class="simplecheckout-cart-total promo-code no_coupon">
				<span class="promo-code-txt"><?php echo $text_retranslate_no_voucher_credit; ?></span>
			  </div>
    	<?php } else { ?>
        <div class="simplecheckout-cart-total voucher-code">
            <span class="inputs"><input class="form-control field" type="text" name="voucher" data-onchange="reloadAll" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>"/></span>
            <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button btn-primary button_oc btn oct-button"><span class="voucher-code-txt"><?php echo $text_retranslate_17; ?></span></a></span>
		</div>
		<?php } ?>	
	<?php } ?>
	
	
	
	<?php if (isset($modules['reward']) && $customer_can_use_points && $points > 0) { ?>	
		<?php if($isLogged) { ?>
			
			<?php } else { ?>
			<div class="reward_isLogged_wrap">
				<span class="reward_isLogged_title"><?php echo $text_retranslate_42; ?></span>
				<span class="reward_isLogged_text"><?php echo $text_retranslate_44; ?> <a class="back_isLogged_btn"><?php echo $text_retranslate_45; ?></a></span>
			</div>
		<?php } ?>
		
		<?php if (!$can_not_use_reward_at_all) { ?>
			<div class="simplecheckout-cart-total reward-code">
				<div class="head"> 
					<span class="title"><?php echo $text_retranslate_41; ?><span class="icon_info" arial-label="<?php echo $text_retranslate_43; ?>"></span></span>
					<span class="text"><?php echo $text_retranslate_47; ?> <?php echo $total_points_customer_has; ?> <?php echo $points_pluralized; ?></span>
				</div>
				<div class="content">
					<span class="inputs"><input class="form-control field" type="number" min="0" onkeydown="checkKey(event)" onkeyup="this.value = this.value.replace(/[^\d]/g,'');" placeholder="<?php echo $text_retranslate_46; ?>" name="reward" data-onchange="" value="<?php echo $reward; ?>" /></span>
					<span class="inputs buttons">
						<a id="simplecheckout_button_cart" data-onclick="reloadAll" class="reward_btn button btn-primary button_oc btn oct-button"><span class="reward-code-txt"><?php echo $text_retranslate_17; ?></span></a>
					</span>
				</div>
			</div>
			<?php } else { ?>
			<div class="simplecheckout-cart-total reward-code reward-code-disabled">
				<div class="head"> 
					<span class="title"><?php echo $text_retranslate_41; ?><span class="icon_info" arial-label="<?php echo $text_retranslate_48; ?>"></span></span>
					<span class="text"><?php echo $text_retranslate_47; ?> <?php echo $total_points_customer_has; ?> <?php echo $points_pluralized; ?></span>
				</div>
				<div class="head"> 
					<span class="text"><?php echo $text_retranslate_48; ?></span>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
	
    <?php if ($display_products) { ?>
        <table class="simplecheckout-cart">
            <colgroup>
                <col class="image">
                <col class="name">
                <col class="model">
                <col class="quantity">
                <col class="price">
                <col class="total">
			</colgroup>
            <thead>
                <tr>
                    <th class="image"><?php echo $column_image; ?></th>
                    <th class="name"><?php echo $column_name; ?></th>
                    <th class="model"><?php echo $column_model; ?></th>
                    <th class="quantity"><?php echo $column_quantity; ?></th>
                    <th class="price"><?php echo $column_price; ?></th>
                    <th class="total"><?php echo $column_total; ?></th>
				</tr>
			</thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <td class="image">
                            <?php if ($product['thumb']) { ?>
                                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
							<?php } ?>
						</td>
                        <td class="name">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            <div class="options">
								<?php foreach ($product['option'] as $option) { ?>
									&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
								<?php } ?>
							</div>
                            <?php if ($product['reward']) { ?>
								<small><?php echo $product['reward']; ?></small>
							<?php } ?>
						</td>
                        <td class="model"><?php echo $product['model']; ?></td>
                        <td class="quantity"><?php echo $product['quantity']; ?></td>
                        <td class="price"><?php echo $product['price']; ?></td>
                        <td class="total"><?php echo $product['total']; ?></td>
					</tr>
				<?php } ?>
                <?php foreach ($vouchers as $voucher_info) { ?>
                    <tr>
                        <td class="image"></td>
                        <td class="name"><?php echo $voucher_info['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity">1</td>
                        <td class="price"><?php echo $voucher_info['amount']; ?></td>
                        <td class="total"><?php echo $voucher_info['amount']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } ?>
	
	
	
    <?php if ($display_totals_block) { ?>
		<?php foreach ($totals as $total) { ?>
			<?php if (in_array($total['code'], $display_totals)) { ?>
				<div class="simplecheckout-cart-total cart__total_text" id="total_<?php echo $total['code']; ?>">
					<span><?php echo $total['title']; ?>:</span>
					<span class="simplecheckout-cart-total-value"><?php echo $total['text']; ?></span>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	
    <?php if (!$display_products && $display_totals_block && !empty($display_totals)) { ?>
		<div class="simplecheckout-summary-totals">
		<?php } ?>
        <button id="simplecheckout-button-main-confirm" class="btn simplecheckout_button_confirm"  onclick="doSubmitOrderByFakeButton();"><?php echo $text_retranslate_40; ?></button>
	</div>
    <?php /* if (!$display_products && $display_totals_block && !empty($display_totals)) { ?>
		<div id="report_bug_simple">
			<span class="title"><?php echo $text_retranslate_err1; ?></span>
			<form  method="post" id="report_bug-form" enctype="multipart/form-data">
				<textarea name="text" id="" rows="10" placeholder="<?php echo $text_retranslate_err2; ?>"></textarea>
				<button id="report_bug_simple_btn" class="button-review-report_bug_simple"><?php echo $text_retranslate_err3; ?>
				</button>
			</form>
		</div>
		
	</div>
	
<?php } */ ?>


<script>
	function doSubmitOrderByFakeButton(){
		
		if ($('input[name=\'payment_method_current\']').val() != ''){
			if (typeof window.simplecheckout_0 == 'object' && window.simplecheckout_0.isPaymentFormEmpty()){										
				window.simplecheckout_0.copyPaymentFormFromDefaultIfIsEmptyAndFinishOrder();
			}
		}
		
		$('#simplecheckout_button_confirm').trigger('click');
	}
</script>



<?php if ($display_comment) { ?>
	<?php if ($summary_comment) { ?>
        <table class="simplecheckout-cart simplecheckout-summary-info">
			<thead>
				<tr>
					<th class="name"><?php echo $text_summary_comment; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $summary_comment; ?></td>
				</tr>
			</tbody>
		</table>
		<!--  <div class="simplecheckout-cart simplecheckout-summary-info">
            <div class="field-group">
			<div class="field-label name">
			<?php echo $text_summary_comment; ?>
			</div>
			<div class="field-input">
			<?php echo $summary_comment; ?>
			</div>
            </div>
		</div> -->
	<?php } ?>
<?php } ?>

<?php if ($display_address) { ?>
	<?php if ($summary_payment_address || $summary_shipping_address) { ?>
		<!--  <table class="simplecheckout-cart simplecheckout-summary-info">
			<thead>
            <tr>
			<?php if ($summary_payment_address) { ?>
				<th class="name"><?php echo $text_summary_payment_address; ?></th>
			<?php } ?>
			<?php if ($summary_shipping_address) { ?>
				<th class="name"><?php echo $text_summary_shipping_address; ?></th>
			<?php } ?>
            </tr>
			</thead>
			<tbody>
            <tr>
			<?php if ($summary_payment_address) { ?>
				<td><?php echo $summary_payment_address; ?></td>
			<?php } ?>
			<?php if ($summary_shipping_address) { ?>
				<td><?php echo $summary_shipping_address; ?></td>
			<?php } ?>
            </tr>
			</tbody>
		</table> -->
        <div class="simplecheckout-cart simplecheckout-summary-info">            
            <?php if ($summary_payment_address) { ?>
                <div class="field-group">
                    <div class="field-label">
                        <?php echo $text_summary_payment_address; ?>
					</div>
                    <div class="field-input">
                        <?php echo $summary_payment_address; ?>
					</div>
				</div>
			<?php } ?>
            <?php if ($summary_shipping_address) { ?>
                <div class="field-group">
                    <div class="field-label">
                        <?php echo $text_summary_shipping_address; ?>
					</div>
                    <div class="field-input">
                        <?php echo $summary_shipping_address; ?>
					</div>
				</div>
			<?php } ?>
			
			
			
		</div>
	<?php } ?>
<?php } ?>


<script>
	$('.back_isLogged_btn').on('click', function(){
		$('#simplecheckout_button_prev').trigger('click');
		setTimeout(function(){
			$('.checkout .tabs__caption li.loadLoginTab').trigger('click');
		},100)
		
	});

	function checkKey(e) {
        if(e.keyCode == "13") {
	        $('.reward_btn').trigger( "click" )
	    }
	}
	
	$("#report_bug_simple_btn").bind("click", function (e) {
		e.preventDefault();
		let __btn = $(this);
		let formData = new FormData($("form#report_bug-form")[0]);
		let coment = $("textarea[name='text']");
		
		$.ajax({
			url: "/index.php?route=kp/errorreport/write",
			type: "post",
			dataType: "json",
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$(".success, .warning").remove();
				$("#report_bug_simple_btn").attr("disabled", true);
				__btn.after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function (data) {
				$("report_bug_simple_btn").attr("disabled", false);
				$(".attention").remove();
			},
			success: function (data) {
				if (data["error"]) {
					__btn.before('<div class="warning" style="color:red;">' + data["error"] + "</div>");
				}
				
				if (data.success) {
					__btn.before('<div class="success">' + data["success"] + "</div>");
					coment.attr("value");
				}
				
			},
			error: function (data) {
				console.log("error", data);
			},
		});
		
	});
</script>