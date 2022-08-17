<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php $simple_page = 'simpleregister'; include $simple_header; ?>
<style type="text/css">
    .simple-content{
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 30px;
    }
    table.simplecheckout-table-form,
    #quick_order_simplecheckout_btn{
        display: none;
    }
    .field-input{
        position: relative;
    }
    .simpleregister-block-content{
        padding: 0;
    }
    .password-toggle{
       position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        height: 20px;    
        cursor: pointer;    
    }
    .field-group{
        margin-bottom: 15px;
    }
    .btn-group-register{
        display: flex;
        margin-top: 20px;
        margin-bottom: 20px;
        justify-content: space-between;
    }
    .btn-group-register button{
        width: 49%;
        display: flex;
        flex-direction: row;
        align-content: center;
        align-items: center;
        margin-bottom: 10px;
        padding: 15px 8px;
        background: #fff !important;
        background-color: #fff;
        font-size: 18px;
        color: #0385c1 !important;
        text-shadow: none;
        transition: .15s ease-in-out;
        outline: none !important;
        font-weight: 500;
        letter-spacing: .21px;
        height: 54px;
        border: 1px solid #51a881;
    }
    .btn-group-register button span{
        font-size: 17px !important;
    }
    .btn-group-register button:hover{
        box-shadow: 1px 1px 5px #ccc;
    }
    .btn-group-register button .btn-img {
        background-color: #fff;
        -webkit-border-radius: 1px;
        border-radius: 1px;
        padding: 15px;
        display: flex;
        margin-right: 10px;
        max-height: 52px;
    }
    .social_register span.title{
        font-size: 18px;
    }
    .simpleregister-have-account a{
        color: #51a881;
        text-decoration: underline;
    }
    .simpleregister-have-account{
        margin-bottom: 20px;
        font-size: 18px;
    }
    @media screen and (max-width: 560px) {
        .simple-content{
            display: flex;
            flex-direction: column;
            grid-gap: 0;
        }
    }
</style>
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
        <?php if ($popup) { ?> 
            var simpleScriptsInterval = window.setInterval(function(){
                if (typeof jQuery !== 'undefined' && jQuery.isReady) {
                    window.clearInterval(simpleScriptsInterval);

                    if (typeof Simplepage !== "function") {
                        <?php foreach ($simple_scripts as $script) { ?> 
                            jQuery("head").append('<script src="' + '<?php echo $script ?>' + '"></' + 'script>');
                        <?php } ?>

                        <?php foreach ($simple_styles as $style) { ?> 
                            jQuery("head").append('<link href="' + '<?php echo $style ?>' + '" rel="stylesheet"/>');
                        <?php } ?>                         
                    }
                }
            },0);
        <?php } ?>

        var startSimpleInterval = window.setInterval(function(){
            if (typeof jQuery !== 'undefined' && typeof Simplepage === "function" && jQuery.isReady) {
                window.clearInterval(startSimpleInterval);

                var simplepage = new Simplepage({
                    additionalParams: "<?php echo $additional_params ?>",
                    additionalPath: "<?php echo $additional_path ?>",
                    mainUrl: "<?php echo $action; ?>",
                    mainContainer: "#simplepage_form",
                    useAutocomplete: <?php echo $use_autocomplete ? 1 : 0 ?>,
                    loginLink: "<?php echo $login_link ?>",
                    scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                    notificationDefault: <?php echo $notification_default ? 1 : 0 ?>,
                    notificationToasts: <?php echo $notification_toasts ? 1 : 0 ?>,
                    notificationCheckForm: <?php echo $notification_check_form ? 1 : 0 ?>,
                    notificationCheckFormText: "<?php echo $notification_check_form_text ?>",
                    languageCode: "<?php echo $language_code ?>",
                    popup: <?php echo ($popup || $as_module) ? 1 : 0 ?>,
                    javascriptCallback: function() {<?php echo $javascript_callback ?>}
                });

                if (typeof toastr !== 'undefined') {
                    toastr.options.positionClass = "<?php echo $notification_position ? $notification_position : 'toast-top-right' ?>";
                    toastr.options.timeOut = "<?php echo $notification_timeout ? $notification_timeout : '5000' ?>";
                    toastr.options.progressBar = true;            
                    toastr.options.preventDuplicates = true;        
                }

                simplepage.init();
            }
        },0);
    </script>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">
        <div class="simpleregister" id="simpleregister">
            <p class="simpleregister-have-account"><?php echo $text_account_already; ?></p>
            <?php if ($error_warning) { ?>
            <div class="warning alert alert-danger"><?php echo $error_warning; ?></div>
            <?php } ?>
            <div class="simpleregister-block-content">
                <?php foreach ($rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
                <?php foreach ($hidden_rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
            </div>

            <?php if ($display_agreement_checkbox) { ?>
                <div class="simpleregister-warning-block" id="agreement_warning" <?php if ($error_agreement) { ?>data-error="true"<?php } else { ?>style="display:none;"<?php } ?>>
                    <div class="agreement_all">
                        <?php foreach ($error_warning_agreement as $agreement_id => $warning_agreement) { ?>
                            <div class="agreement_<?php echo $agreement_id ?>"><?php echo $warning_agreement ?></div>
                        <?php } ?>
                    </div>                    
                </div>
            <?php } ?>

            <div class="simpleregister-button-block buttons">
                <div class="simpleregister-button-right">
                    <?php if ($display_agreement_checkbox) { ?>
                        <span id="agreement_checkbox">
                            <?php foreach ($text_agreements as $agreement_id => $text_agreement) { ?>
                                <label><input type="checkbox" name="agreements[]" value="<?php echo $agreement_id ?>" <?php echo in_array($agreement_id, $agreements) ? 'checked="checked"' : '' ?> /><?php echo $text_agreement; ?></label>&nbsp;
                            <?php } ?>
                        </span>
                    <?php } ?>
                    <a class="button btn-primary button_oc btn btn-acaunt" data-onclick="submit" id="simpleregister_button_confirm"><span><?php echo $button_continue; ?></span></a>
                </div>
            </div>
        </div>
        <?php if ($redirect) { ?>
            <input type="hidden" id="simple_redirect_url" value="<?php echo $redirect ?>">
        <?php } ?>
    </form>
    <div class="social_register">
        <span class="title">Войти с помощью:</span>
        <div class="btn-group-register">

            <?php if ($this->config->get('social_auth_google_app_id')) { ?>
            <button type="button" onclick="social_auth.googleplus(this)" data-loading-text="Loading" class="btn btn-primary btn-google">
                <div class="btn-img" style="padding: 0;margin-right: 13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="46px" height="46px" viewBox="0 0 46 46" version="1.1">
                        <defs>
                            <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-1">
                                <feOffset dx="0" dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
                                <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter1" result="shadowBlurOuter1"/>
                                <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.168 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1"/>
                                <feOffset dx="0" dy="0" in="SourceAlpha" result="shadowOffsetOuter2"/>
                                <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter2" result="shadowBlurOuter2"/>
                                <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.084 0" in="shadowBlurOuter2" type="matrix" result="shadowMatrixOuter2"/>
                                <feMerge>
                                    <feMergeNode in="shadowMatrixOuter1"/>
                                    <feMergeNode in="shadowMatrixOuter2"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                            <rect id="path-2" x="0" y="0" width="40" height="40" rx="2"/>
                        </defs>
                        <g id="Google-Button" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                            <g id="9-PATCH" sketch:type="MSArtboardGroup" transform="translate(-608.000000, -160.000000)"/>
                            <g id="btn_google_light_normal" sketch:type="MSArtboardGroup" transform="translate(-1.000000, -1.000000)">
                                
                                <g id="logo_googleg_48dp" sketch:type="MSLayerGroup" transform="translate(15.000000, 15.000000)">
                                    <path d="M17.64,9.20454545 C17.64,8.56636364 17.5827273,7.95272727 17.4763636,7.36363636 L9,7.36363636 L9,10.845 L13.8436364,10.845 C13.635,11.97 13.0009091,12.9231818 12.0477273,13.5613636 L12.0477273,15.8195455 L14.9563636,15.8195455 C16.6581818,14.2527273 17.64,11.9454545 17.64,9.20454545 L17.64,9.20454545 Z" id="Shape" fill="#4285F4" sketch:type="MSShapeGroup"/>
                                    <path d="M9,18 C11.43,18 13.4672727,17.1940909 14.9563636,15.8195455 L12.0477273,13.5613636 C11.2418182,14.1013636 10.2109091,14.4204545 9,14.4204545 C6.65590909,14.4204545 4.67181818,12.8372727 3.96409091,10.71 L0.957272727,10.71 L0.957272727,13.0418182 C2.43818182,15.9831818 5.48181818,18 9,18 L9,18 Z" id="Shape" fill="#34A853" sketch:type="MSShapeGroup"/>
                                    <path d="M3.96409091,10.71 C3.78409091,10.17 3.68181818,9.59318182 3.68181818,9 C3.68181818,8.40681818 3.78409091,7.83 3.96409091,7.29 L3.96409091,4.95818182 L0.957272727,4.95818182 C0.347727273,6.17318182 0,7.54772727 0,9 C0,10.4522727 0.347727273,11.8268182 0.957272727,13.0418182 L3.96409091,10.71 L3.96409091,10.71 Z" id="Shape" fill="#FBBC05" sketch:type="MSShapeGroup"/>
                                    <path d="M9,3.57954545 C10.3213636,3.57954545 11.5077273,4.03363636 12.4404545,4.92545455 L15.0218182,2.34409091 C13.4631818,0.891818182 11.4259091,0 9,0 C5.48181818,0 2.43818182,2.01681818 0.957272727,4.95818182 L3.96409091,7.29 C4.67181818,5.16272727 6.65590909,3.57954545 9,3.57954545 L9,3.57954545 Z" id="Shape" fill="#EA4335" sketch:type="MSShapeGroup"/>
                                    <path d="M0,0 L18,0 L18,18 L0,18 L0,0 Z" id="Shape" sketch:type="MSShapeGroup"/>
                                </g>
                                <g id="handles_square" sketch:type="MSLayerGroup"/>
                            </g>
                        </g>
                    </svg>           
                </div>
                <span style="color: #000000; opacity: 0.54; font-size: 14px;">Google</span>            
            </button>
            <? } ?>
            
            <?php if ($this->config->get('social_auth_facebook_app_id')) { ?>
            <button type="button" onclick="social_auth.facebook(this)" data-loading-text="Loading" class="btn btn-primary btn-facebook">
                <div class="btn-img"><i class="fab fa-facebook-f"></i></div> 
                <span style="color: #000000; opacity: 0.54; font-size: 14px;">Facebook</span>  
            </button> 
             <? } ?>  
        </div>
    </div>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
<?php include $simple_footer ?>
<?php } ?>
