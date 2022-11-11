<?php
/**
 * Liqpay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        Liqpay
 * @package         Payment
 * @version         3.0
 * @author          Liqpay
 * @copyright       Copyright (c) 2014 Liqpay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * OpenCart         1.5.6
 * LiqPay API       https://www.liqpay.com/ru/doc
 *
 */
?>

<?=$header?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb): ?>
            <?=$breadcrumb['separator']?><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a>
        <?php endforeach ?>
    </div>

    <?php if ($error_warning): ?><div class="warning"><?=$error_warning?></div><?php endif ?>

    <div class="box">
        <div class="heading order_head">
		  <form action="<?=$action?>" method="post" enctype="multipart/form-data" id="form">
            <h1><img src="view/image/payment/liqpay.png" alt="" /> <?=$heading_title?></h1>
            <div class="buttons">
				<?php if (count($stores) > 0) { ?>
				<div class="stores" style="margin-right: 5px; display: inline-block;">
					Магазин:&nbsp;
					<select name="store_id" id="store_id" onchange="location='<?php echo str_replace("'", "\\'",$action_without_store); ?>'+'&store_id='+$(this).val()">
							<?php foreach ($stores as $key => $value) { ?>
									<option value="<?php echo $value['store_id'] ?>" <?php echo $store_id == $value['store_id'] ? 'selected="selected"' : '' ?>><?php echo $value['store_id'] ?> - <?php echo $value['name'] ?></option>
							<?php } ?>
					</select>
				</div>
			<?php } ?>
                <a onclick="$('#form').submit();" class="button"><?=$button_save?></a>
                <a href="<?=$cancel?>" class="button"><?=$button_cancel?></a>
            </div>
        </div>
        <div class="content">          
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?=$entry_public_key?></td>
                        <td><input type="text" name="liqpay_public_key" value="<?=$liqpay_public_key?>" />
                            <?php if ($error_public_key):?><span class="error"><?=$error_public_key?></span><?php endif?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?=$entry_private_key?></td>
                        <td><input type="text" name="liqpay_private_key" value="<?=$liqpay_private_key?>" />
                            <?php if ($error_private_key):?><span class="error"><?=$error_private_key?></span><?php endif?>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?=$entry_action?></td>
                        <td><input type="text" name="liqpay_action" value="<?=$liqpay_action?>" />
                            <?php if ($error_action):?><span class="error"><?=$error_action?></span><?php endif?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$entry_pay_way?></td>
                        <td>

                            <input id="card" type="checkbox" value="card" name="card" class="checkbox pay_way" <?php if (strpos($liqpay_pay_way, "card") !== false):?>checked="checked"<?php endif?> />
							<label for="card" onclick="payWay()">Карта</label>
                           

                           <input id="liqpay" type="checkbox" value="liqpay" name="liqpay" class="checkbox pay_way" <?php if (strpos($liqpay_pay_way, "liqpay") !== false):?>checked="checked"<?php endif?> />  
						   <label for="liqpay" onclick="payWay()" >Liqpay</label>
                           

                            <input id="delayed" type="checkbox" value="delayed" name="delayed" class="checkbox pay_way" <?php if (strpos($liqpay_pay_way, "delayed") !== false):?>checked="checked"<?php endif?> /> 
							<label for="delayed" onclick="payWay()">Терминал</label>
                           

                            <input id="invoice" type="checkbox" value="invoice" name="invoice" class="checkbox pay_way" <?php if (strpos($liqpay_pay_way, "invoice") !== false):?>checked="checked"<?php endif?> />
							<label for="invoice" onclick="payWay()">Invoice</label>
                            

                           <input id="privat24" type="checkbox" value="privat24" name="privat24" class="checkbox pay_way" <?php if (strpos($liqpay_pay_way, "privat24") !== false):?>checked="checked"<?php endif?> /> 
							<label for="privat24" onclick="payWay()">Privat24</label>
                            

                            <input type="text" id="pay_way" name="liqpay_pay_way" 
                            value="<?=$liqpay_pay_way?>" hidden/>

                        </td>
                    </tr>
                    <tr>
                        <td><?=$entry_total?></td>
                        <td><input type="text" name="liqpay_total" value="<?=$liqpay_total?>" /></td>
                    </tr>
                    <tr>
                        <td><?=$entry_order_status?></td>
                        <td>
                            <select name="liqpay_order_status_id">
                                <?php
                                    foreach ($order_statuses as $order_status):
                                        $order_status_id = $order_status['order_status_id'];
                                        $sel = ($order_status_id == $liqpay_order_status_id);
                                ?>
                                    <option <?php if ($sel):?>selected="selected"<?php endif?> value="<?=$order_status_id?>">
                                        <?=$order_status['name']?>
                                    </option>
                                <?php endforeach?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$entry_geo_zone?></td>
                        <td>
                            <select name="liqpay_geo_zone_id">
                                <option value="0"><?=$text_all_zones?></option>
                                <?php
                                    foreach ($geo_zones as $geo_zone):
                                        $geo_zone_id = $geo_zone['geo_zone_id'];
                                        $sel = ($geo_zone_id == $liqpay_geo_zone_id);
                                ?>
                                    <option <?php if ($sel):?>selected="selected"<?php endif?> value="<?=$geo_zone_id?>">
                                        <?=$geo_zone['name']?>
                                    </option>
                                <?php endforeach?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Статус фронта</td>
                        <td>
                            <select name="liqpay_status">
                                <option <?php if ($liqpay_status): ?>selected="selected"<?php endif?> value="1">
                                    <?=$text_enabled?>
                                </option>
                                <option <?php if (!$liqpay_status): ?>selected="selected"<?php endif?> value="0">
                                    <?=$text_disabled?>
                                </option>
                            </select>
                             <br /><span class="help">для штатной логики и фронта</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Статус админки</td>
                        <td>
                            <select name="liqpay_status_fake">
                                <option <?php if ($liqpay_status_fake): ?>selected="selected"<?php endif?> value="1">
                                    <?=$text_enabled?>
                                </option>
                                <option <?php if (!$liqpay_status_fake): ?>selected="selected"<?php endif?> value="0">
                                    <?=$text_disabled?>
                                </option>
                            </select>
                            <br /><span class="help">если включено, то в форме заказа можно выставлять счет, оплату по QR в любом случае, вне зависимости от статуса на фронте</span>
                        </td>
                    </tr>
                    
					<tr>
                        <td>Вторичный метод</td>
                        <td>
                            <select name="liqpay_ismethod">
                                <option <?php if ($liqpay_ismethod): ?>selected="selected"<?php endif?> value="1">
                                    <?=$text_enabled?>
                                </option>
                                <option <?php if (!$liqpay_ismethod): ?>selected="selected"<?php endif?> value="0">
                                    <?=$text_disabled?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$entry_language?></td>
                        <td>
                            <select name="liqpay_language">
                                <option <?php if ($liqpay_language == 'ru'): ?>selected="selected"<?php endif?> value="ru">
                                    ru
                                </option>
                                <option <?php if ($liqpay_language == 'en'): ?>selected="selected"<?php endif?> value="en">
                                    en
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                      <td><?=$entry_sort_order?></td>
                      <td><input type="text" name="liqpay_sort_order" value="<?=$liqpay_sort_order?>" size="1" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
        function payWay(){
         
            var elems = $(".pay_way:checked");
            var str = '';
            elems.each(function(){
                str += $(this).prop("name") + ',';
            })
            $("#pay_way").val(str);

        }
</script>
<?=$footer?>