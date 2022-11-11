<?php echo $header; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style type="text/css">
   .password-toggle{
       position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        height: 20px;    
        cursor: pointer;    
    }
</style>
<section id="content" class="password-change account_wrap">
    <div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div>

        <div class="account_content">
            <?php echo $content_top; ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <div class="content">
                    <table style="width: auto;" class="form simplecheckout-customer">
                        <tr>
                        <td class="simplecheckout-customer-left"><span class="required">*</span> <?php echo $entry_password; ?></td>
                        <td class="simplecheckout-customer-right"><input type="password" name="password" value="<?php echo $password; ?>" />
                        <?php if ($error_password) { ?>
                        <span class="error"><?php echo $error_password; ?></span>
                        <?php } ?></td>
                        </tr>
                        <tr>
                        <td class="simplecheckout-customer-left"><span class="required">*</span> <?php echo $entry_confirm; ?></td>
                        <td class="simplecheckout-customer-right"><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                        <?php if ($error_confirm) { ?>
                        <span class="error"><?php echo $error_confirm; ?></span>
                        <?php } ?></td>
                        </tr>
                    </table>
                </div>
                <div class="buttons">
                    <!--   <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div> -->
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-acaunt" />
                </div>
            </form>
            <?php echo $content_bottom; ?>
        </div>
    
    </div>
</section>
<?php echo $footer; ?>