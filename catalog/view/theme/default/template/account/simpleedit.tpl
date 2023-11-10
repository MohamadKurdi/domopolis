<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php $simple_page = 'simpleedit'; include $simple_header; ?>
<style type="text/css">
   
    #quick_order_simplecheckout_btn{
        display: none;
    }
     .account_wrap .two_column .simpleregister .field-group.row-edit_viber_news .simplecheckout-tooltip {
        display: block;
        font-size: 14px;
        color: #757575;
        line-height: 20px;
    }
</style>
<div class="side_bar">
    <?php echo $column_left; ?>
</div>
<div class="account_content">
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
        var startSimpleInterval = window.setInterval(function(){
            if (typeof jQuery !== 'undefined' && typeof Simplepage === "function" && jQuery.isReady) {
                window.clearInterval(startSimpleInterval);

               setTimeout(function() { 
                var simplepage = new Simplepage({
                    additionalParams: "<?php echo $additional_params ?>",
                    additionalPath: "<?php echo $additional_path ?>",
                    mainUrl: "<?php echo $action; ?>",
                    mainContainer: "#simplepage_form",
                    scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                    notificationDefault: <?php echo $notification_default ? 1 : 0 ?>,
                    notificationToasts: <?php echo $notification_toasts ? 1 : 0 ?>,
                    notificationCheckForm: <?php echo $notification_check_form ? 1 : 0 ?>,
                    notificationCheckFormText: "<?php echo $notification_check_form_text ?>",
                    languageCode: "<?php echo $language_code ?>",
                    javascriptCallback: function() {<?php echo $javascript_callback ?>}
                });

                if (typeof toastr !== 'undefined') {
                    toastr.options.positionClass = "<?php echo $notification_position ? $notification_position : 'toast-top-right' ?>";
                    toastr.options.timeOut = "<?php echo $notification_timeout ? $notification_timeout : '5000' ?>";
                    toastr.options.progressBar = true;
                    toastr.options.preventDuplicates = true; 
                }

                simplepage.init();
            },2000);
            }
        },0);
    </script>
    <?php } ?>
    <form  action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">
        <div class="simpleregister" id="simpleedit">
            <?php if ($error_warning) { ?>
            <div class="warning alert alert-danger"><?php echo $error_warning; ?></div>
            <?php } ?>
            <div class="simpleregister-block-content">
                <?php foreach ($rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
            </div>
            <div class="simpleregister-button-block buttons">
                <div class="simpleregister-button-right">
                    <a class="button btn-primary button_oc btn btn-acaunt" data-onclick="submit" id="simpleregister_button_confirm"><span><?php echo $button_continue; ?></span></a>
                </div>
            </div>
        </div>
        <?php if ($redirect) { ?>
            <input type="hidden" id="simple_redirect_url" value="<?php echo $redirect ?>">
        <?php } ?>

        <?php if ($birthday_logic_is_used) { ?>
            <?php if (!empty($text_birthday_reward)) { ?>
                <script>
                    $('input#edit_birthday').parent('.field-input').parent('.row-edit_birthday').children('.field-label').append('<span class="alert alert-success alert-no-padding"><?php echo $text_birthday_reward; ?></span>');
                </script>
            <?php } ?>
            <?php if ($birthday_is_already_set) { ?>
                <script>
                    $('input#edit_birthday').attr('disabled', 'disabled');
                    $('.simplecheckout-rule-group[data-for=edit_birthday]').children('.simplecheckout-rule').show();
                </script>
            <?php } ?>
        <?php } ?>
    </form>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
</div>
<?php include $simple_footer ?>
<?php } ?>
