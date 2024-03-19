<?php echo $header; ?>

  <script>
    
    $(document).ready(function () {
      $('#forgotten-pass').mask("<?php echo $this->config->get('config_phonemask'); ?>");
    });
  </script>

<style>
  .tabs__content form{
    text-align: center;
  }
  .tabs__content .content{
    display: flex;
    width: 100%;
    justify-content: center;
    margin-bottom: 25px;
  }
  span.error{
    font-size: 13px;
    font-weight: 500;
    color: #fd2f30;
  }
  .tabs__content input{
    max-width: 200px;
    margin: 0 auto 15px;
  }
  .tabs__content input::placeholder {
    opacity: 0.9;
    color: #a7a4a4;
  }

  .simplecheckout-customer .simplecheckout-customer-right{
    display: flex;
    flex-direction: column;
  }
</style>
<?php echo $column_left; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section id="content">
  <?php echo $content_top; ?>

  <?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'password_reset'}).then(function(token) {
                document.getElementById('g-recaptcha-response-1').value=token;
      });
    });
  </script> 

  <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'password_reset'}).then(function(token) {
                document.getElementById('g-recaptcha-response-2').value=token;
      });
    });
  </script> 
<?php } ?>

  <div class="wrap">

    <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="tabs">
        <!--tabs__nav-->
        <div class="tabs__nav">
          <ul class="tabs__caption">
            <?php if ($this->config->get('config_restore_password_enable_sms')) { ?>
              <li class="active">
                <?php echo $restore_by_telephone; ?>
              </li>          
            <?php } ?> 
             <li <?php if (!$this->config->get('config_restore_password_enable_sms')) { ?>class="active"<? } ?>>
                <?php echo $restore_by_email; ?>
              </li>       
          </ul>
        </div>

         <?php if ($this->config->get('config_restore_password_enable_sms')) { ?>
        <div class="tabs__content active">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="simplecheckout-customer" id="restore-by-phone">    
            <div class="content">
              <table class="form">
                <tr>
                  <td class="simplecheckout-customer-right">

                    <input type="text" name="telephone"  id='forgotten-pass' placeholder="<?php echo $this->config->get('config_phonemask'); ?>"  value="" />

                    <?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
                        <input type="hidden" id="g-recaptcha-response-1" name="g-recaptcha-response" />
                        <?php if ($error_captcha) { ?>
                          <span class="error"><?php echo $error_captcha; ?></span>
                        <?php } ?>
                    <?php } ?>
                  </td>
                </tr>
              </table>
            </div>
            <div class="buttons">
              <a href="<?php echo $back; ?>" class="btn btn-acaunt-none" style="padding: 11.3px 30px;"><?php echo $button_back; ?></a>      
              <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-acaunt" />     
            </div>
          </form>    
        </div>
         <?php } ?> 

        <div class="tabs__content" <?php if (!$this->config->get('config_restore_password_enable_sms')) { ?>class="active"<? } ?>>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="simplecheckout-customer"  id="restore-by-email">    
            <div class="content">
              <table class="form">
                <tr>
                  <td class="simplecheckout-customer-right">
                    <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" />

                    <?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
                        <input type="hidden" id="g-recaptcha-response-2" name="g-recaptcha-response" />
                        <?php if ($error_captcha) { ?>
                          <span class="error"><?php echo $error_captcha; ?></span>
                        <?php } ?>
                    <?php } ?>
                  </td>
                </tr>
              </table>
            </div>
            <div class="buttons">
              <a href="<?php echo $back; ?>" class="btn btn-acaunt-none" style="padding: 11.3px 30px;"><?php echo $button_back; ?></a>      
              <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-acaunt" />     
            </div>
          </form>  
        </div>
    </div>


    <?php echo $content_bottom; ?>
  </div>
</section>
<?php echo $footer; ?>