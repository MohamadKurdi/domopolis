<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    
    <div class="box">
        <div class="heading">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">    
                <h1><?php echo $heading_title; ?></h1>
                
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
                    
                    
                    <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
            </div>
            
        </div>
        
        
        <div class="content">
            
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
                    <td>
                        <input type="text" name="wayforpay_merchant"
                        value="<?php echo $wayforpay_merchant; ?>" class="form-control"/>
                        <?php if ($error_merchant) { ?>
                            <div class="text-danger"><?php echo $error_merchant; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_secretkey; ?></td>
                    <td>
                        <input type="text" name="wayforpay_secretkey"
                        value="<?php echo $wayforpay_secretkey; ?>" class="form-control"/>
                        <?php if ($error_secretkey) { ?>
                            <div class="text-danger"><?php echo $error_secretkey; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_returnUrl; ?></td>
                    <td>
                        <input type="text" name="wayforpay_returnUrl"
                        value="<?php echo $wayforpay_returnUrl; ?>" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_serviceUrl; ?></td>
                    <td>
                        <input type="text" name="wayforpay_serviceUrl"
                        value="<?php echo $wayforpay_serviceUrl; ?>" class="form-control"/>
                    </td>
                </tr>
                
                <tr>
                    <td>Вторичный метод</td>
                    <td>
                        <select name="wayforpay_ismethod">
                            <option <?php if ($wayforpay_ismethod): ?>selected="selected"<?php endif?> value="1">
                                <?=$text_enabled?>
                            </option>
                            <option <?php if (!$wayforpay_ismethod): ?>selected="selected"<?php endif?> value="0">
                                <?=$text_disabled?>
                            </option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td><?php echo $entry_language; ?></td>
                    <td>
                        <input type="text" name="wayforpay_language"
                        value="<?php echo ($wayforpay_language == "") ?
                        "RU" : $wayforpay_language; ?>" class="form-control"/>
                    </td>
                </tr>
                
                <tr>
                    <td>Регион</td>
                    <td>
                        <select name="wayforpay_geo_zone_id">
                            <option value="0"><?=$text_all_zones?></option>
                            <?php
                                foreach ($geo_zones as $geo_zone):
                                $geo_zone_id = $geo_zone['geo_zone_id'];
                                $sel = ($geo_zone_id == $wayforpay_geo_zone_id);
                            ?>
                            <option <?php if ($sel):?>selected="selected"<?php endif?> value="<?=$geo_zone_id?>">
                                <?=$geo_zone['name']?>
                            </option>
                            <?php endforeach?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td><?php echo $entry_order_status; ?></td>
                    <td>
                        <select name="wayforpay_order_status_id" class="form-control">
                            <?php
                                foreach ($order_statuses as $order_status) {
                                    
                                    $st = ($order_status['order_status_id'] == $wayforpay_order_status_id) ? ' selected="selected" ' : "";
                                ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                <?= $st ?> ><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td> <?php echo $entry_status; ?></td>
                    <td>
                        <select name="wayforpay_status" class="form-control">
                            <? $st0 = $st1 = "";
                                if ( $wayforpay_status == 0 ) $st0 = 'selected="selected"';
                                else $st1 = 'selected="selected"';
                            ?>
                            <option value="1"
                            <?= $st1 ?> ><?php echo $text_enabled; ?></option>
                            <option value="0"
                            <?= $st0 ?> ><?php echo $text_disabled; ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td>Статус админки</td>
                    <td>
                        <select name="wayforpay_status_fake" class="form-control">
                            <? $st0 = $st1 = "";
                                if ( $wayforpay_status_fake == 0 ) $st0 = 'selected="selected"';
                                else $st1 = 'selected="selected"';
                            ?>
                            <option value="1"
                            <?= $st1 ?> ><?php echo $text_enabled; ?></option>
                            <option value="0"
                            <?= $st0 ?> ><?php echo $text_disabled; ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td>
                        <input type="text" name="wayforpay_sort_order"
                        value="<?php echo $wayforpay_sort_order; ?>" class="form-control"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
</div>
<?php echo $footer; ?>
