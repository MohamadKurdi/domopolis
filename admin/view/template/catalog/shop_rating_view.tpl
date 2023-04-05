<?php echo $header; ?>
<div id="content">
    
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title.$rating['rate_id'];?></h1>
            <div class="buttons"><a onclick="$('form#form-shop_rate-answer').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shop_rate-answer">
                
                <div id="tabs" class="htabs">
                    <a href="#tab-general">Основное</a>
                    <a href="#tab-data">Мультиязык</a>
                    <div class="clr"></div>
                </div>
                
                <div id="tab-data">
                    
                    <div id="languages" class="htabs">
                        <?php foreach ($languages as $language) { ?>
                            <a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                        <?php } ?>
                    </div>
                    <div class="clr"></div>
                    <?php foreach ($languages as $language) { ?>
                        <div id="language<?php echo $language['language_id']; ?>">
                            <table class="form">
                                <tr>
                                    <td>Текст отзыва</td>
                                    <td><textarea name="shop_rating_description[<?php echo $language['language_id']; ?>][comment]" cols="40" rows="5"><?php echo isset($shop_rating_description[$language['language_id']]) ? $shop_rating_description[$language['language_id']]['comment'] : ''; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Достоинства</td>
                                    <td><textarea name="shop_rating_description[<?php echo $language['language_id']; ?>][good]" cols="40" rows="5"><?php echo isset($shop_rating_description[$language['language_id']]) ? $shop_rating_description[$language['language_id']]['good'] : ''; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Недостатки</td>
                                    <td><textarea name="shop_rating_description[<?php echo $language['language_id']; ?>][bad]" cols="40" rows="5"><?php echo isset($shop_rating_description[$language['language_id']]) ? $shop_rating_description[$language['language_id']]['bad'] : ''; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Ответ на отзыв</td>
                                    <td><textarea name="shop_rating_description[<?php echo $language['language_id']; ?>][answer]" cols="40" rows="5"><?php echo isset($shop_rating_description[$language['language_id']]) ? $shop_rating_description[$language['language_id']]['answer'] : ''; ?></textarea></td>
                                </tr>							
                            </table>
                        </div>
                    <?php } ?>
                </div>
                
                
                <div id="tab-general">    
                    
                    <table class="form">
                        <tbody>
                            <tr>
                                <td class="text-right" width="30%"><?php echo $entry_status.':'; ?></td>
                                <td style="padding: 6px 8px;">
                                    <?php if($rating['rate_status'] == 1){ ?>
                                        <a style="border: 1px solid transparent;color: white!important;" id="change_status-<?php echo $rating['rate_id']?>" class="button enabled change_status_button"><i class="fa fa-check-circle fa-fw"></i><span><?php echo $status_published;?></span></a>
                                        <?php }else{ ?>
                                        <a style="border: 1px solid transparent;color: white!important;" id="change_status-<?php echo $rating['rate_id']?>" class="button remove change_status_button"><i class="fa fa-times-circle fa-fw"></i><span><?php echo $status_unpublished;?></span></a>
                                    <?php }?>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-right" width="30%"><?php echo $rating_sender.':'; ?></td>
                                <td><b class="<?php if(isset($rating['customer_id']) && $rating['customer_id'] != 0) echo 'text-success';?>"><?php echo $rating['customer_name']; ?></b></td>
                            </tr>
                            <tr>
                                <td class="text-right" width="30%"><?php echo $rating_sender_email.':'; ?></td>
                                <td><?php echo $rating['customer_email']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr>
                                <td class="text-right" width="30%">Магазин : </td>
                                <td>
                                    <? echo isset($rating_store['name'])?$rating_store['name']:'Default'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" width="30%"><?php echo $shop_rating.':'; ?></td>
                                <td>
                                    <div class="rate-stars">
                                        <div class="rate-star big-stars <?php if($rating['shop_rate'] >= 1)echo 'star-change';?>" id="site_rate-1"></div>
                                        <div class="rate-star big-stars <?php if($rating['shop_rate'] >= 2)echo 'star-change';?>" id="site_rate-2"></div>
                                        <div class="rate-star big-stars <?php if($rating['shop_rate'] >= 3)echo 'star-change';?>" id="site_rate-3"></div>
                                        <div class="rate-star big-stars <?php if($rating['shop_rate'] >= 4)echo 'star-change';?>" id="site_rate-4"></div>
                                        <div class="rate-star big-stars <?php if($rating['shop_rate'] == 5)echo 'star-change';?>" id="site_rate-5"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" width="30%"><?php echo $site_rating.':'; ?></td>
                                <td>
                                    <div class="rate-stars">
                                        <div class="rate-star big-stars <?php if($rating['site_rate'] >= 1)echo 'star-change';?>" id="site_rate-1"></div>
                                        <div class="rate-star big-stars <?php if($rating['site_rate'] >= 2)echo 'star-change';?>" id="site_rate-2"></div>
                                        <div class="rate-star big-stars <?php if($rating['site_rate'] >= 3)echo 'star-change';?>" id="site_rate-3"></div>
                                        <div class="rate-star big-stars <?php if($rating['site_rate'] >= 4)echo 'star-change';?>" id="site_rate-4"></div>
                                        <div class="rate-star big-stars <?php if($rating['site_rate'] == 5)echo 'star-change';?>" id="site_rate-5"></div>
                                    </div>
                                    
                                </td>
                            </tr>
                            <?php foreach($rating['customs'] as $custom){ ?>
                                <tr>
                                    <td class="text-right" width="30%"><?php echo $custom['title'].':'; ?></td>
                                    <td>
                                        <div class="rate-stars">
                                            <div class="rate-star big-stars <?php if($custom['value'] >= 1)echo 'star-change';?>" id="site_rate-1"></div>
                                            <div class="rate-star big-stars <?php if($custom['value'] >= 2)echo 'star-change';?>" id="site_rate-2"></div>
                                            <div class="rate-star big-stars <?php if($custom['value'] >= 3)echo 'star-change';?>" id="site_rate-3"></div>
                                            <div class="rate-star big-stars <?php if($custom['value'] >= 4)echo 'star-change';?>" id="site_rate-4"></div>
                                            <div class="rate-star big-stars <?php if($custom['value'] == 5)echo 'star-change';?>" id="site_rate-5"></div>
                                        </div>
                                        
                                    </td>
                                </tr>
                            <?php } ?>
                            
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr>
                                <td class="text-center"><?php echo $comment; ?></td>
                                <td class="text-left">
                                    <textarea id="new_rating_comment" name="new_rating_comment" style="width: 100%; height: 100px"><?php echo html_entity_decode(nl2br($rating['comment']), ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr>
                                <td class="text-center text-success"><?php echo $good; ?></td>
                                <td class="text-left">
                                    <textarea name="new_rating_good" id="new_rating_good" style="width: 100%; height: 100px"><?php echo html_entity_decode($rating['good'], ENT_QUOTES, 'UTF-8');?></textarea>
                                    
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tr>
                            <td class="text-center text-danger"><?php echo $bad; ?></td>
                            <td  class="text-left">
                                <textarea name="new_rating_bad" id="new_rating_bad" style="width: 100%; height: 100px"><?php echo html_entity_decode($rating['bad'], ENT_QUOTES, 'UTF-8');?></textarea>
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
                <table class="form">
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $answer; ?></td>
                            <td class="text-left">
                                <input type="hidden" name="answer_id" value="<?php if(isset($rating_answer['id']))echo $rating_answer['id']; ?>">
                                <?php if(isset($rating_answer['comment'])){ ?>
                                    <textarea  name="answer" id="answer"  style="width: 100%; height: 100px"><?php echo nl2br($rating_answer['comment']);?></textarea>
                                    <?php }else{ ?>
                                    <textarea name="answer" id="answer"  style="width: 100%; height: 100px"></textarea>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>  
    </div>
</div>
<script type="text/javascript"><!--
	$('#tabs a').tabs();	
	$('#languages a').tabs();
//--></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.change_status_button').click(function(){
            var rating_id = this.id.split('-')[1];
            var btn = $(this);
            var url = '<?php echo htmlspecialchars_decode($change_status_url);?>';
            var status_publ = '<?php echo $status_published;?>';
            var status_unpubl = '<?php echo $status_unpublished;?>';
            
            $.post( url, { rate_id: rating_id }, function( data ) {
                if(data == "OK"){
                    if(btn.hasClass('remove')){
                        btn.removeClass('remove');
                        btn.addClass('enabled');
                        btn.find('span').text(status_publ);
                        
                        }else{
                        btn.removeClass('enabled');
                        btn.addClass('remove');
                        btn.find('span').text(status_unpubl);
                    }
                }
            }, "json");
        });
    });
    
</script>

<?php echo $footer; ?>        