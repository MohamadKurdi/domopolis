
<div class="field-group row-<?php echo $id ?>">
    <div class="field-label">
        <?php echo $label ?>
        <?php if ($required) { ?>
            <span class="simplecheckout-required">*</span>
        <?php } ?>
    </div>

    
    <div class="field-input" >
        <?php if ($type == 'select' || $type == 'select2') { ?>
            <select name="<?php echo $name ?>" id="<?php echo $id ?>" <?php echo $type == 'select2' ? 'data-type="select2"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
                <?php foreach ($values as $info) { ?>
                    <option value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'selected="selected"' : '' ?>><?php echo $info['text'] ?></option>
                <?php } ?>
            </select>
            <?php } elseif ($type == 'radio') { ?>
            <div class="simple-address " style="display: flex;align-items: center;height: 60px;">
                <div class="register_block_simpl">
                    <?php foreach ($values as $info) { ?>
                        <input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>_<?php echo $info['id'] ?>" value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
                        <label for="<?php echo $id ?>_<?php echo $info['id'] ?>"><?php echo $info['text'] ?></label>
                    <?php } ?>
                </div>
            </div>
            
            <?php } elseif ($type == 'checkbox') { ?>
            <?php foreach ($values as $info) { ?>
                <label><input type="checkbox" name="<?php echo $name ?>[]" id="<?php echo $id ?>_<?php echo $info_id ?>" value="<?php echo $info['id'] ?>" <?php echo in_array($info['id'], $value) ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $info['text'] ?></label>
            <?php } ?>
            <?php } elseif ($type == 'switcher') { ?>
            <label><input type="checkbox" name="<?php echo $name ?>"  data-unchecked-value="0" id="<?php echo $id ?>" value="1" <?php echo $value == '1' ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $placeholder ?></label>
            <?php } elseif ($type == 'textarea') { ?>
            <textarea name="<?php echo $name ?>" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $value ?></textarea>
            <?php } elseif ($type == 'captcha') { ?>
            <?php if ($site_key) { ?>
                <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>&onload=recaptchaInit&render=explicit" type="text/javascript" async defer></script>
                <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
                <script type="text/javascript">
                    function recaptchaCallback(value) {
                    $('#<?php echo $id ?>').val(value).trigger('change');
                }
                function recaptchaInit(){
                    grecaptcha.render('simple-recaptcha');
                }
            </script>
            <div id="simple-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="recaptchaCallback"></div>
            <?php } else { ?>
            <input class="field" type="text" name="<?php echo $name ?>" id="<?php echo $id ?>" value="" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
            <div class="simple-captcha-container"><img src="index.php?<?php echo $additional_path ?>route=common/simple_connector/captcha&t=<?php echo $time ?>" alt="" id="captcha" /></div>
            <?php } ?>
            <?php } elseif ($type == 'file') { ?>
            <input type="button" value="<?php echo $button_upload; ?>" data-file="<?php echo $id ?>" class="button">
            <div id="text_<?php echo $id ?>" style="margin-top:3px;max-width:200px;"><?php echo $filename ?></div>
            <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
            <?php } elseif ($type == 'tel') { ?>
            <div class="quick_order_wrap">
                <input class="field" type="<?php echo ($type == 'date' || $type == 'time' || $type == 'datetime') ? 'text' : $type ?>" <?php echo $type == 'password' ? 'data-validate-on="keyup"' : '' ?> name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
                <input type="button" class="btn disable-btn 1" id="quick_order_simplecheckout_btn" name="" value="Быстрый заказ" title="">                
            </div>
            <?php } else { ?>
            <input class="field" type="<?php echo ($type == 'date' || $type == 'time' || $type == 'datetime') ? 'text' : $type ?>" <?php echo $type == 'password' ? 'data-validate-on="keyup"' : '' ?> name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
        <?php } ?>
        <?php if (!empty($rules)) { ?>
            <div class="simplecheckout-rule-group" data-for="<?php echo $id ?>">
                <?php foreach ($rules as $rule) { ?>
                    <div <?php echo $rule['display'] && !$rule['passed'] ? '' : 'style="display:none;"' ?> data-for="<?php echo $id ?>" data-for-type="<?php echo $type ?>" data-rule="<?php echo $rule['id'] ?>" class="simplecheckout-error-text simplecheckout-rule" <?php echo $rule['attrs'] ?>><?php echo $rule['text'] ?></div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($description) { ?>
            <div class="simplecheckout-tooltip" data-for="<?php echo $id ?>"><?php echo $description ?></div>
        <?php } ?>
    </div>
</div>