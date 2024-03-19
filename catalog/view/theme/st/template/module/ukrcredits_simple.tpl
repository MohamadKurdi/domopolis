	<div id="prop<?php echo $credit['type']; ?>" class="main_wrap_credit 31">
		<div class="prop_calc 32">
			<div class="prop_paymentsCount">
				<?php echo $credit['partsCount']; ?>
			</div>
			<div class="prop_select">
				<select id="termInput<?php echo $credit['type']; ?>" name="select<?php echo $credit['type']; ?>" class="form-control">
					 <?php for($credit['type']=='MB'?$i=2:$i=1; $i<=$credit['partsCount']; $i++){ ?>
						<option value="<?php echo $i; ?>" <?php if (!empty($credit['partsCountSel']) && $i == $credit['partsCountSel']) { ?>selected="selected"<?php } ?>><?php echo $i; ?> <?php echo $text_mounth; ?></option>
					 <?php } ?>
				</select>
			</div>
			<div class="prop_permounth">
				<?php echo $text_per; ?> <span><?php echo $credit['price']; ?></span> <?php echo $currency_left; ?>
			</div>
			<div class="prop_price">
				<?php echo $text_total; ?> <span><?php echo $credit['price']; ?></span> <?php echo $currency_left; ?>
			</div>	
		</div>
		<div id="termSlider<?php echo $credit['type']; ?>">
			<div class="progress">
				<div id="termProgress<?php echo $credit['type']; ?>" class="progress-bar progress-bar-success" role="progressbar" style="width: 0;"></div>
			</div>
		</div>
		<div class="clearfix"></div>
		<button class="info open-credit">
			<i class="fa fa-info-circle" aria-hidden="true"></i>
			Як скористатись
		</button>
	</div> 


  	<div class="popup-overlay_credit">
        <div class="popup">
            <p><?php echo $credit['text_in_cart']; ?></p>
            <button class="close-popup">Зрозуміло</button>
        </div>
    </div>

<script>
var UCconstants = {
	'termStep': 1,
	'priceInitial<?php echo $credit['type']; ?>': 	<?php echo round($credit['price'], 2); ?>,
	'termMax<?php echo $credit['type']; ?>': 		<?php echo isset($credit['partsCount'])?$credit['partsCount']:24; ?>,
	'termMin<?php echo $credit['type']; ?>': 		<?php echo $credit['type']=='MB'?2:1; ?>,
	'termSelected<?php echo $credit['type']; ?>': 	<?php echo $credit['partsCountSel']?$credit['partsCountSel']:($credit['type']=='MB'?2:1); ?>,
};

UCinitElements = function () {
	UCinitTermInput<?php echo $credit['type']; ?>();
	UCinitTermSlider<?php echo $credit['type']; ?>();
},

UCinitTermSlider<?php echo $credit['type']; ?> = function () {
	$("#termSlider<?php echo $credit['type']; ?>").slider({
		value: 	UCconstants.termSelected<?php echo $credit['type']; ?>,
		max: 	UCconstants.termMax<?php echo $credit['type']; ?>,
		min: 	UCconstants.termMin<?php echo $credit['type']; ?>,
		step: 	UCconstants.termStep,
		slide: function (event, ui) {
			UCsliderMoved($(this), $("#termInput<?php echo $credit['type']; ?>"), $("#termProgress<?php echo $credit['type']; ?>"), ui.value);
		}
	});
	var initprogress<?php echo $credit['type']; ?> = (UCconstants.termSelected<?php echo $credit['type']; ?> - UCconstants.termMin<?php echo $credit['type']; ?>) * 100 / ( UCconstants.termMax<?php echo $credit['type']; ?> - UCconstants.termMin<?php echo $credit['type']; ?> );
	$("#termProgress<?php echo $credit['type']; ?>").css('width', initprogress<?php echo $credit['type']; ?> + "%");
},

UCinitTermInput<?php echo $credit['type']; ?> = function () {
	var $inp = $("#termInput<?php echo $credit['type']; ?>");
	$inp.attr("min", UCconstants.termMin<?php echo $credit['type']; ?>);
	$inp.attr("max", UCconstants.termMax<?php echo $credit['type']; ?>);
	$inp.val(UCconstants.termSelected<?php echo $credit['type']; ?>);
	$inp.on('change', function () {
		UCinputChanged($inp, $("#termSlider<?php echo $credit['type']; ?>"), $("#termProgress<?php echo $credit['type']; ?>"));
	});
},

UCsliderMoved = function (slider, inputToChange, progressToChange, newValue) {
    var sMax = slider.slider("option", "max");
    var sMin = slider.slider("option", "min");
    inputToChange.val(newValue);
    var progress = (newValue - sMin) * 100 / ( sMax - sMin );
    progressToChange.css('width', progress + "%");
	UCcalc();
	savepartscount(inputToChange.attr('id'),newValue);
},

UCinputChanged = function (input, slider, progressToChange) {
    var newVal = input.val();
    slider.slider("value", newVal);
    var sMax = slider.slider("option", "max");
    var sMin = slider.slider("option", "min");
    var progress = (newVal - sMin) * 100 / ( sMax - sMin );
    progressToChange.css('width', progress + "%");
	UCcalc();
	savepartscount(input.attr('id'),input.val());
},

savepartscount = function(selector,partscount){
	type = selector.substr(-2).toLowerCase().replace('ia','ii').replace('pb','pp');
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/ukrcredits_' + type + '/setUkrcreditsType',
		dataType: 'json',
		data: {partsCount: partscount},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
		success: function(){
			reloadAll();
			<?php if ($ukrcredits_setting[str_replace(array('ia','pb'),array('ii','pp'),mb_strtolower($credit['type'])).'_markup_type'] == 'custom') { ?>
				
			<?php } ?>
		}	     
	});
},

payments = [<?php echo $text_payments; ?>],
declOfNum = function(number, titles) {  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>7 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
},

UCcalc = function(){
	var resCalc<?php echo $credit['type']; ?> = UC_CALCULATOR.calculatePhys($('#termInput<?php echo $credit['type']; ?>').val(), UCconstants.priceInitial<?php echo $credit['type']; ?>);
	if (resCalc<?php echo $credit['type']; ?> != undefined) {
		$('#prop<?php echo $credit['type']; ?> .prop_paymentsCount').html(resCalc<?php echo $credit['type']; ?>.payCount + ' ' + declOfNum(resCalc<?php echo $credit['type']; ?>.payCount,payments));
		$('#paymentsCount<?php echo $credit['type']; ?>').html(resCalc<?php echo $credit['type']; ?>.payCount);
		$('#prop<?php echo $credit['type']; ?> .prop_permounth span').html(resCalc<?php echo $credit['type']; ?>.<?php echo mb_strtolower($credit['type']); ?>Value);
		$('#prop<?php echo $credit['type']; ?> .prop_price span').html(resCalc<?php echo $credit['type']; ?>.<?php echo mb_strtolower($credit['type']); ?>Price);
	}
},

UC_CALCULATOR = (function () {
    var uc = {};
    function privParseInt(num) {
        return parseInt(num, 10)
    }
     uc.calculatePhys = function (paymentsCount, price) {
        if (isNaN(paymentsCount) || isNaN(price)) return;
        paymentsCount = privParseInt(paymentsCount) + 1;
		price = privParseInt(price);
		
		<?php if ($ukrcredits_setting['pp_markup_type'] == 'custom') { ?>
			var commissionsPP = $.parseJSON('<?php echo json_encode($ukrcredits_setting['pp_markup_custom_PP']); ?>');
			markupPP = Number(commissionsPP[paymentsCount-1]) + Number(<?php echo $ukrcredits_setting['pp_markup_acquiring']; ?>);
			price_pp = price + price * (markupPP / 100);
		<?php } else {?>
			price_pp = price;
		<?php } ?>
		
		<?php if ($ukrcredits_setting['ii_markup_type'] == 'custom') { ?>
		var commissionsII = $.parseJSON('<?php echo json_encode($ukrcredits_setting['ii_markup_custom_II']); ?>');
			markupII = Number(commissionsII[paymentsCount-1]) + Number(<?php echo $ukrcredits_setting['ii_markup_acquiring']; ?>);
			price_ia = price + price * (markupII / 100);
		<?php } else { ?>
			price_ia = price;
		<?php } ?>
		
		<?php if ($ukrcredits_setting['mb_markup_type'] == 'custom') { ?>
			var commissionsMB = $.parseJSON('<?php echo json_encode($ukrcredits_setting['mb_markup_custom_MB']); ?>');
			markupMB = Number(commissionsMB[paymentsCount-1]) + Number(<?php echo $ukrcredits_setting['mb_markup_acquiring']; ?>);
			price_mb = price + price * (markupMB / 100);
		<?php } else {?>
			price_mb = price;
		<?php } ?>
		
        var ip = price / paymentsCount + price * (0.019);
        var ia = (price_ia / paymentsCount) + (price_ia * 0.99 / 100);
        var pp = price_pp / paymentsCount;
		var mb = price_mb / paymentsCount;
        return ({
            payCount: paymentsCount,
            ppValue: pp.toFixed(2),
			pbValue: pp.toFixed(2),
            iiValue: ip.toFixed(2),
            iaValue: ia.toFixed(2),
			mbValue: mb.toFixed(2),
			
			iiPrice: (ip * paymentsCount).toFixed(2),
			iaPrice: price_ia.toFixed(2),
			ppPrice: price_pp.toFixed(2),
			mbPrice: price_mb.toFixed(2)
        });
    };
    return uc;
}());
$(document).ready(function() {
    UCinitElements();
    UCcalc();

    $(".open-credit").click(function() {
        $(".popup-overlay_credit").fadeIn();
    });

    $(".popup-overlay_credit, .close-popup").click(function() {
        $(".popup-overlay_credit").fadeOut();
    });

    $(".popup").click(function(event) {
        event.stopPropagation();
    });

});
</script>