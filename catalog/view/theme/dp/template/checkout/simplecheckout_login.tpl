<div class="simplecheckout-block" id="simplecheckout_login" <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <div class="simplecheckout-login simplecheckout-block-content">
        <div id="simple_login_header" style="display: none;"><img style="cursor:pointer;" data-onclick="close" src="<?php echo $additional_path ?>catalog/view/image/close.png"></div>
        <?php if ($error_login) { ?>
        <div class="simplecheckout-warning-block"><?php echo $error_login ?></div>
        <?php } ?>
        <div class="field-group">
            <div class="simplecheckout-login-left field-label">
                <?php echo $text_retranslate_37; ?>                
            </div>
            <div class="simplecheckout-login-right field-input"><input class="field" data-onkeydown="detectEnterAndLogin" type="text" name="email" value="<?php echo $email; ?>" />
            </div>
        </div>
        <div class="field-group">
            <div class="simplecheckout-login-left field-label"><?php echo $entry_password; ?></div>
            <div class="simplecheckout-login-right field-input"><input class="field" data-onkeydown="detectEnterAndLogin" type="password" name="password" value="" />
			<span class="password-toggle" onclick="passwordToggle($(this));"><i class="fas fa-eye"></i></span>
			</div>
        </div>
        <div class="btn-group simplecheckout-login-right buttons">
            <a id="simplecheckout_button_login" data-onclick="login" class="button_oc btn"><span><?php echo $button_login; ?></span></a>
            <a href="<?php echo $forgotten; ?>" class="forgotten"><?php echo $text_forgotten; ?></a>
        </div>
        
        
    </div>
</div>






<!-- <div class="simplecheckout-block" id="simplecheckout_login" <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <div class="simplecheckout-block-content">
        <div id="simple_login_header" style="display: none;"><img style="cursor:pointer;" data-onclick="close" src="<?php echo $additional_path ?>catalog/view/image/close.png"></div>
        <?php if ($error_login) { ?>
        <div class="simplecheckout-warning-block"><?php echo $error_login ?></div>
        <?php } ?>
        <table class="simplecheckout-login">
            <tr class="field-group">
                <td class="simplecheckout-login-left field-label"><?php echo $entry_email; ?></td>
                <td class="simplecheckout-login-right field-input"><input class="field" data-onkeydown="detectEnterAndLogin" type="text" name="email" value="<?php echo $email; ?>" /></td>
            </tr>
            <tr class="field-group">
                <td class="simplecheckout-login-left field-label"><?php echo $entry_password; ?></td>
                <td class="simplecheckout-login-right field-input"><input class="field" data-onkeydown="detectEnterAndLogin" type="password" name="password" value="" /></td>
            </tr>
            <tr>
                <td></td>
                <td class="simplecheckout-login-right "><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></td>
            </tr>
            <tr>
                <td></td>
                <td class="simplecheckout-login-right buttons"><a id="simplecheckout_button_login" data-onclick="login" class="button btn-primary button_oc btn"><span><?php echo $button_login; ?></span></a></td>
            </tr>
        </table>
    </div>
</div> -->