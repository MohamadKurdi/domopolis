<?php echo $header; ?>
<?php if ($success) { ?>
    <div class="wrap">
        <div class="success"><?php echo $success; ?></div>
    </div>
<?php } ?>

<?php echo $column_right; ?>
<div id="content-account" class="account_wrap my_office_page">
    <?php echo $content_top; ?>
    <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
    <div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div>
        <div class="account_content">
            <div class="top_content">
                <div class="bonus_content">
                    <span class="text"><?php echo $text_retranslate_account_available_points; ?></span>
                    <span class="value">
                        <?php if($points_active == 0) {?>
                            0
                        <?php } else { ?>
                            <?php echo $points_active_formatted_as_currency; ?>
                        <?php } ?>
                    </span>
                </div>
                <div class="content">
                    <p><?php echo $text_retranslate_account_main_line_1; ?></p>
                    <ul>
                        <li><?php echo $text_retranslate_account_main_line_2; ?></li>
                        <li><?php echo $text_retranslate_account_main_line_3; ?></li>
                    </ul>
                </div>
            </div>
            <div class="bottom_content">
                <p class="title"><?php echo $text_retranslate_account_main_line_4; ?></p>

                <p><?php echo $text_retranslate_account_main_line_5; ?></p>
            </div>  
            
        </div>
        <?php echo $content_bottom; ?>
    </div>
</div>
</div>
<?php echo $footer; ?> 