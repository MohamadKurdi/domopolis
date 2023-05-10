<?php echo $header; ?>
<div id="content">
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    
    <div class="box">
        <div class="heading">           
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">    
                <h1><?php echo $heading_title; ?></h1>
                
                <div class="buttons">                    
                    <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
                </div>            
            </div>    

            <div class="content">            
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
                        <td>
                         <input style="margin-bottom:0%;" type="text" name="mono_merchant" value="<?php echo $mono_merchant ;?>" id="input-merchant" class="mono-select" required />
                         <?php if ($error_merchant) { ?>
                            <span class="error"><?php echo $error_merchant ;?></span>
                        <?php } ?>
                        <p class="mono-text"><?php echo $mono_text ;?> <a href="https://web.monobank.ua/" style="color:#EA5357;" target="_blank">web.monobank.ua</a></p>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_redirect; ?></td>
                    <td>
                        <input style="margin-bottom:0%;" type="text" name="mono_redirect_url" value="<?php echo $mono_redirect_url ;?>" id="input-redirect" class="mono-select" />                         
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_destination; ?></td>
                    <td>
                     <input style="margin-bottom:0%;" type="text" name="mono_destination" value="<?php echo $mono_destination ;?>" id="input-destination" class="mono-select" />
                 </td>
             </tr>

             <tr>
                <td>
                    <?php echo $entry_geo_zone ;?>                                
                </td>
                <td>
                    <select name="mono_geo_zone_id" id="input-geo-zone" class="mono-select">
                        <option value="0"><?php echo $text_all_zones ;?></option>
                        <?php foreach($geo_zones as $geo_zone) { ?>
                            <option class="mono-option"  value="<?php echo $geo_zone['geo_zone_id'] ;?>" <?php echo $geo_zone['geo_zone_id'] == $mono_geo_zone_id ? 'selected' : '' ;?>><?php echo $geo_zone['name'] ;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $entry_order_success_status ;?>                                
                </td>
                <td>
                    <select name="mono_order_success_status_id" id="input-order-status" class="mono-select">
                        <?php foreach($order_statuses as $order_status) { ?>
                            <option class="mono-option"  value="<?php echo $order_status['order_status_id'] ;?>" <?php echo $order_status['order_status_id'] == $mono_order_success_status_id ? 'selected' : '' ;?>><?php echo $order_status['name'] ;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $entry_order_cancelled_status ;?>                                
                </td>
                <td>
                    <select name="mono_order_cancelled_status_id" id="input-order-status" class="mono-select">
                        <?php foreach($order_statuses as $order_status) { ?>
                            <option class="mono-option"  value="<?php echo $order_status['order_status_id'] ;?>" <?php echo $order_status['order_status_id'] == $mono_order_cancelled_status_id ? 'selected' : '' ;?>><?php echo $order_status['name'] ;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $entry_order_process_status ;?>                                
                </td>
                <td>
                    <select name="mono_order_process_status_id" id="input-order-status" class="mono-select">
                        <?php foreach($order_statuses as $order_status) { ?>
                            <option class="mono-option"  value="<?php echo $order_status['order_status_id'] ;?>" <?php echo $order_status['order_status_id'] == $mono_order_process_status_id ? 'selected' : '' ;?>><?php echo $order_status['name'] ;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><?php echo $entry_status; ?></td>
                <td>
                    <select name="mono_status" class="form-control">
                       <?php if ( $mono_status == 1 ) { ?>
                        <option class="mono-option" value="1"  selected="selected" ><?php echo $text_enabled; ?></option>
                        <option class="mono-option" value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                        <option class="mono-option" value="1"><?php echo $text_enabled; ?></option>
                        <option class="mono-option" value="0"  selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Вторичный метод</td>
            <td>
             <select name="mono_ismethod" class="form-control">
                 <?php if ( $mono_ismethod == 1 ) { ?>
                    <option class="mono-option" value="1"  selected="selected" ><?php echo $text_enabled; ?></option>
                    <option class="mono-option" value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                    <option class="mono-option" value="1"><?php echo $text_enabled; ?></option>
                    <option class="mono-option" value="0"  selected="selected"><?php echo $text_disabled; ?></option>
                <?php }?>
            </select>
        </td>
    </tr>

    <tr>
        <td>
           <?php echo $entry_hold ;?>                                 
       </td>
       <td>
           <select name="mono_hold_mode" id="input-hold" class="mono-select">
            <?php if ($mono_hold_mode == 1) { ?>
                <option class="mono-option" value="1" selected="selected"><?php echo $text_enabled ;?></option>
                <option class="mono-option" value="0"><?php echo $text_disabled ;?></option>
            <?php } else{ ?>
                <option class="mono-option" value="1"><?php echo $text_enabled ;?></option>
                <option class="mono-option" value="0" selected="selected"><?php echo $text_disabled ;?></option>
            <?php }?>
        </select>
    </td>
</tr>

<tr>
    <td>Статус админки</td>
    <td>
       <select name="mono_status_fake" class="form-control">
         <?php if ( $mono_status_fake == 1 ) { ?>
            <option value="1"  selected="selected" ><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
        <?php } else {?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0"  selected="selected"><?php echo $text_disabled; ?></option>
        <?php }?>
    </select>
</td>
</tr>

<tr>
    <td><?php echo $entry_sort_order; ?></td>
    <td>
        <input type="text" name="mono_sort_order" value="<?php echo $mono_sort_order; ?>" class="form-control"/>
    </td>
</tr>
</table>
</form>

<img src="view/image/payment/cat.png" alt="Monobank" style="position:absolute;  margin-top:-25%; right: 10%; width:20%;"/>
</div>

</div>


<?php echo $footer; ?>