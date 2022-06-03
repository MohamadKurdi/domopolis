<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                        <tr>
                            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                            <td class="left"><?php if ($sort == 'pd.name') { ?>
                                <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                            <?php } ?></td>
                            <td class="left"><?php if ($sort == 'r.author') { ?>
                                <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                            <?php } ?></td>
                            
                            <td>Куплено</td>
                            
                            <td class="left"><?php if ($sort == 'r.status') { ?>
                                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                            <?php } ?></td>
                            
                            <td>Достатки</td>
                            <td>Недостатки</td>
                            <td>Текст</td>
                            
                            <td>Фотка</td>
                            
                            <td class="right"><?php if ($sort == 'r.rating') { ?>
                                <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                            <?php } ?></td>
                            <td class="left"><?php if ($sort == 'r.date_added') { ?>
                                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                            <?php } ?></td>
                            <td class="right"><?php echo $column_action; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reviews) { ?>
                            <?php foreach ($reviews as $review) { ?>
                                <tr>
                                    <td style="text-align: center;"><?php if ($review['selected']) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
                                    <?php } ?></td>
                                    <td class="left" style="width:250px;">
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $review['name']; ?></span>
                                    </td>                                    
                                    
                                    <td class="left">
                                        <?php if ($review['customer_link']) { ?>
                                            <a href="<?php echo $review['customer_link']; ?>" target="_blank"><?php echo $review['author']; ?></a>
                                            <? } else { ?>
                                            <?php echo $review['author']; ?>
                                        <? } ?>
                                    </td>
                                    
                                    <td class="right">
                                        <? if ($review['purchased']) { ?>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
                                            <? } else { ?>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
                                        <? } ?>
                                    </td>
                                    
                                    <td class="left">
                                        <? if ($review['status']) { ?>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Включено</span>
                                            <? } else { ?>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отключено</span>
                                        <? } ?>
                                    </td>
                                    
                                    <td class="right"><span style="font-size:10px; color:#4ea24e"><?php echo $review['good']; ?></span></td>
                                    <td class="right"><span style="font-size:10px; color:#cf4a61"><?php echo $review['bads']; ?></span></td>
                                    <td class="right"><div style="font-size:10px; color:grey;"><?php echo $review['text']; ?></div></td>
                                    
                                    <td class="right"><?php if ($review['image']) { ?><img src="<?php echo $review['image']; ?>" /><?php } ?></td>      
                                    
                                    <td class="right"><img src="../catalog/view/theme/default/image/stars-<?php echo $review['rating'] . '.png'; ?>" /></td>           
                                    
                                    
                                    
                                    <td class="left"><?php echo $review['date_added']; ?></td>
                                    <td class="right"><?php foreach ($review['action'] as $action) { ?>
                                        <a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                                    <?php } ?></td>
                                </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>