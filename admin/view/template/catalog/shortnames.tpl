<?php echo $header; ?>
<style>
    .red{
        background-color:#ef5e67;
    }
    .orange{
        background-color:#ff7f00;
    }
    .green{
        background-color:#00ad07;
    }    
    .black{
         background-color:#353740;
    }

    .text-red{
        color:#ef5e67;
    }
    .text-orange{
        color:#ff7f00;
    }
    .text-green{
        color:#00ad07;
    }

    input.process, textarea.process{
        border:2px solid #ff7f00;
        background-color: #ead985;
    }
    input.finished, textarea.finished{
        border:2px solid #00ad07;
        background-color: #e9ece6;
    }

    span.smallbutton{
        padding:3px 5px;
        display:inline-block;
        margin-right:10px;
        color:white;
        cursor:pointer;
    }
</style>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><?php echo $heading_title; ?></h1>
            <div class="clr"></div>
            <span class="help"><i class="fa fa-info-circle"></i> Экспортные наименования должны быть не более 50 символов. Сохранение работает на лету</span>
            <span class="help"><i class="fa fa-info-circle"></i> Можно редактировать как короткие описания, так и основные</span>
        </div>
        <div class="content">            
            <table class="list">
                <thead>
                    <tr>
                        <td class="center" style="width:120px;">ASIN</td>                    
                        <td class="left" style="width:45%">Оригинальное название</td> 
                        <td class="left" style="width:45%">Экспортное название</td> 
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products) { ?>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td class="center" style="width:120px;">
                                    <span class="status_color" style="display:inline-block; width:100px; padding:3px 5px; background:#FF7815; color:#FFF"><?php echo $product['asin']; ?></span>   
                                    <span class="status_color" style="display:inline-block;  width:80px; padding:3px 5px; background:#9832FF; color:#FFF"><?php echo $product['product_id']; ?></span>
                                    <?php if ($product['status']) { ?>
                                        <span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-check-circle"></i></span>
                                    <? } else { ?>
                                        <span style="color:#CF4A61; font-size:18px; font-weight: 700;"><i class="fa fa-times"></i></span>
                                    <? } ?>
                                    <div style="margin-top:10px;">
                                        <a class="button" href="<?php echo $product['edit']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
                                        <a class="button" href="<?php echo $product['view']; ?>"  target="_blank"><i class="fa fa-eye"></i></a>
                                    </div>          
                                </td>
                                <td class="left">
                                    <div>
                                        <div style="text-align: left; height:20px; margin-bottom:5px;">
                                            <img src="view/image/flags/<?php echo $native_language['image']; ?>" title="<?php echo $native_language['name']; ?>" />                                          

                                           <?php if (mb_strlen($product['native_name']) >= $this->config->get('config_openai_shortennames_length') && $this->config->get('config_openai_enable_shorten_names')) { ?>   
                                               <span class="smallbutton black" onclick="shortenbyai($(this), 'native', '<?php echo $native_language['code']; ?>', <?php echo $product['product_id']; ?>)">🤖 AI до <?php echo $this->config->get('config_openai_shortennames_length'); ?> символов</span><span></span>
                                           <?php } ?>
                                       </div>
                                        
                                        <textarea style="width:98%" rows="3" class="native_name_textarea" name="native_name_<?php echo $product['product_id']; ?>" data-product-id="<?php echo $product['product_id']; ?>" data-language-id="<?php echo $native_language['language_id']; ?>" data-name="full"><?php echo $product['native_name']; ?></textarea>                                        
                                    </div>

                                    <div>
                                        <div style="text-align: left; height:20px; margin-bottom:5px; margin-top:5px;">
                                            <img src="view/image/flags/<?php echo $amazon_language['image']; ?>" title="<?php echo $amazon_language['name']; ?>" />

                                              <?php if (mb_strlen($product['amazon_name']) >= $this->config->get('config_openai_shortennames_length') && $this->config->get('config_openai_enable_shorten_names')) { ?>   
                                               <span class="smallbutton black" onclick="shortenbyai($(this), 'amazon', '<?php echo $amazon_language['code']; ?>', <?php echo $product['product_id']; ?>)">🤖 AI до <?php echo $this->config->get('config_openai_shortennames_length'); ?> символов</span><span></span>
                                             <?php } ?>                                  
                                            </div>

                                           <textarea style="width:98%" rows="3" class="amazon_name_textarea" name="amazon_name_<?php echo $product['product_id']; ?>" data-product-id="<?php echo $product['product_id']; ?>" data-language-id="<?php echo $amazon_language['language_id']; ?>" data-name="full"><?php echo $product['amazon_name']; ?></textarea>
                                    </div>
                                </td>


                                <td class="left">
                                    <div style="margin-bottom:10px;">
                                        <div style="text-align: left; height:30px;">
                                            <img src="view/image/flags/<?php echo $native_language['image']; ?>" title="<?php echo $native_language['name']; ?>" />

                                              <span class="smallbutton <?php if (mb_strlen($product['native_name']) <= $this->config->get('config_openai_exportnames_length')) { ?>green<?php } else { ?>red<?php } ?>" onclick="copy('native', <?php echo $product['product_id']; ?>)">
                                               <i class="fa fa-copy"></i>Копировать из полного
                                           </span>

                                           <?php if (mb_strlen($product['native_name']) >= $this->config->get('config_openai_exportnames_length') && $this->config->get('config_openai_enable_export_names')) { ?>   
                                             <span class="smallbutton black" onclick="exportbyai($(this), 'native', '<?php echo $native_language['code']; ?>', <?php echo $product['product_id']; ?>)">🤖 AI до <?php echo $this->config->get('config_openai_exportnames_length'); ?> символов</span><span></span>
                                         <?php } ?> 
                                        </div>
                                        
                                        <input type="text" style="width:98%" maxlength="50" class="native_short_name_input" name="native_short_name_<?php echo $product['product_id']; ?>" data-product-id="<?php echo $product['product_id']; ?>" data-language-id="<?php echo $native_language['language_id']; ?>" data-name="short" value="<?php echo $product['native_short_name']; ?>" />

                                        <br /><small id="native_short_name_length_<?php echo $product['product_id']; ?>"> <?php echo mb_strlen($product['native_short_name']); ?> символов</small>
                                    </div>

                                    <div>
                                        <div style="text-align: left; height:30px;">
                                            <img src="view/image/flags/<?php echo $amazon_language['image']; ?>" title="<?php echo $amazon_language['name']; ?>" />

                                            <span class="smallbutton <?php if (mb_strlen($product['amazon_name']) <= $this->config->get('config_openai_exportnames_length')) { ?>green<?php } else { ?>red<?php } ?>" onclick="copy('amazon', <?php echo $product['product_id']; ?>)">
                                             <i class="fa fa-copy"></i>Копировать из полного                                               
                                         </span>

                                         <?php if (mb_strlen($product['amazon_name']) >= $this->config->get('config_openai_exportnames_length') && $this->config->get('config_openai_enable_export_names')) { ?>   
                                             <span class="smallbutton black" onclick="exportbyai($(this), 'amazon', '<?php echo $amazon_language['code']; ?>', <?php echo $product['product_id']; ?>)">🤖 AI до <?php echo $this->config->get('config_openai_exportnames_length'); ?> символов</span><span></span>
                                         <?php } ?> 
                                     </div>

                                        <input type="text" style="width:98%" maxlength="50" class="amazon_short_name_input" name="amazon_short_name_<?php echo $product['product_id']; ?>" data-product-id="<?php echo $product['product_id']; ?>" data-language-id="<?php echo $amazon_language['language_id']; ?>" data-name="short" value="<?php echo $product['amazon_short_name']; ?>" />

                                        <br /><small id="amazon_short_name_length_<?php echo $product['product_id']; ?>"> <?php echo mb_strlen($product['amazon_short_name']); ?> символов</small>
                                    </div>
                                </td>
                            </tr>
                        <? } ?>                    
                    <?php } else { ?>
                        <tr>
                            <td class="center" colspan="2"><?php echo $text_no_results; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>  

<script>

    function shortenbyai(elem, type, language_code, product_id){
        let from            = $('textarea[name='+ type + '_name_' + product_id + ']');
        let to              = $('textarea[name='+ type + '_name_' + product_id + ']');
        let name            = from.val();

        $.ajax({
            url: 'index.php?route=catalog/shortnames/shortbyai&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'text',
            data: {
                name: name,
                language_code: language_code
            },
            success: function(text) {
                to.val(text);
                recount(type, product_id);

                if (text.length > 0 && text.length <= <?php echo $this->config->get('config_openai_shortennames_length'); ?>){
                    save(to);
                }
            },
            beforeSend: function(){
                elem.next().html('<i class="fa fa-spinner fa-spin"></i>');
            },
            complete: function(){
                elem.next().html('<i class="fa fa-check"></i>');
            }
        });
    }

    function exportbyai(elem, type, language_code, product_id){
        let from            = $('textarea[name='+ type + '_name_' + product_id + ']');
        let to              = $('input[name='+ type + '_short_name_' + product_id + ']');
        let name            = from.val();

        $.ajax({
            url: 'index.php?route=catalog/shortnames/exportbyai&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'text',
            data: {
                name: name,
                language_code: language_code
            },
            success: function(text) {
                to.val(text);

                if (text.length > 0 && text.length <= <?php echo $this->config->get('config_openai_exportnames_length'); ?>){
                    save(to);
                }
            },
            beforeSend: function(){
                elem.next().html('<i class="fa fa-spinner fa-spin"></i>');
            },
            complete: function(){
                elem.next().html('<i class="fa fa-check"></i>');
            }
        });
    }

    function copy(type, product_id){
        let from    = $('textarea[name='+ type + '_name_' + product_id + ']');
        let to      = $('input[name='+ type + '_short_name_' + product_id + ']');
        let text    = from.val();

        to.val(text);
        recount(type, product_id);

        if (text.length > 0 && text.length <= 50){
            save(to);
        }
    }

    function save(elem){
        let product_id  = elem.attr('data-product-id');
        let language_id = elem.attr('data-language-id');
        let name        = elem.attr('data-name');

        $.ajax({
            url : 'index.php?route=catalog/shortnames/write&token=<?php echo $token; ?>',
            data: {
                product_id:     product_id,
                language_id:    language_id,
                name:           name,
                text:           elem.val()
            },
            type: 'POST',
            beforeSend: function(){
                elem.removeClass('process, finished').addClass('finished');
            },
            success: function(){
                elem.removeClass('process, finished').addClass('finished');
            }
        });
    }

    function recount(type, product_id){
       let length           = $('input[name='+ type + '_short_name_' + product_id + ']').val().length;
       let counter_span     = $('small#'+ type + '_short_name_length_' + product_id);

       counter_span.text(length + ' символов');
        if (length > 0 && length <= <?php echo $this->config->get('config_openai_exportnames_length'); ?>){
            counter_span.removeClass('text-red, text-green').addClass('text-green');
        } else {
            counter_span.removeClass('text-red, text-green').addClass('text-red');
        }
    }

    $('.native_short_name_input, .amazon_short_name_input, .native_name_textarea, .amazon_name_textarea').on('change', function(){
        save($(this));
    });

    $('.native_short_name_input, .amazon_short_name_input, .native_name_textarea, .amazon_name_textarea').on('focusout', function(){
        save($(this));
    });

    $('.native_short_name_input').on('keyup', function(){
        recount('native', $(this).attr('data-product-id'));
    });

    $('.amazon_short_name_input').on('keyup', function(){
       recount('amazon', $(this).attr('data-product-id'));
    });
</script>

<?php echo $footer; ?>