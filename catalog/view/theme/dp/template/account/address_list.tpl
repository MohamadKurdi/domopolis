<?php echo $header; ?>
<style>
    .adress-content-list .content.default_address{
        box-shadow: 0 8px 20px rgb(0 0 0 / 20%);
        border-color: #c0e856;
    }
    .adress-content-list .content .adress{
        display: flex;
        flex-direction: column;
    }
    .adress-content-list .content .adress span i{
        font-style: initial;
        font-size: 13px;
        color: #98b549;
        font-weight: 600;
    }
    @media screen and (max-width: 560px) {
        .adress-content-list .content .buttons a{
            width: 100%;
            padding: 10px ;
        }
        .adress-content-list .content .adress h4{
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 5px
        }
        .adress-content-list .content .adress span{
            font-size: 15px;
        }
    }
</style>
<?php if ($success) { ?>
<div class="wrap"><div class="success"><?php echo $success; ?></div></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/breadcrumbs.tpl')); ?>
<section id="content" class="adress-content-list account_wrap">    
    <div class="wrap two_column"> 
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div> 
        <div class="account_content">
            <?php echo $content_top; ?>
            <?php foreach ($addresses as $result) { ?>
                <div class="content <?php if ($result['default']) { ?>default_address<?php } ?>">    
                    <div class="adress">
                        <h4><?php echo $result['city']; ?></h4>
                        <span><?php echo $result['address']; ?> 
                            <?php if ($result['default']) { ?>
                                <i>• <?php echo $text_default; ?></i>
                            <?php } ?>
                        </span>

                        
                    </div>

                    <div class="buttons">          
                        <a href="<?php echo $result['update']; ?>" class="button btn btn-acaunt"><?php echo $button_edit; ?></a> 
                        <a href="<?php echo $result['delete']; ?>" class="button btn btn-acaunt-none"><?php echo $button_delete; ?></a>
                    </div>
                </div>
            <?php } ?>
            <div class="buttons">
                <!-- <a href="<?php echo $back; ?>" class="btn btn-acaunt-none"><?php echo $button_back; ?></a> -->
                <a href="<?php echo $insert; ?>" class="btn btn-acaunt"><?php echo $button_new_address; ?></a>
            </div>
            <?php echo $content_bottom; ?>
        </div>
    </div>  
</section>
<?php echo $footer; ?>