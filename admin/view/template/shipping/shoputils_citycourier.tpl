<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/shipping.png"/><?php echo $heading_title; ?></h1>

            <div class="buttons"><a onclick="$('#form').submit();"
                                    class="button"><span><?php echo $button_save; ?></span></a><a
                    onclick="location = '<?php echo $cancel; ?>';"
                    class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td><select name="shoputils_citycourier_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $shoputils_citycourier_geo_zone_id) { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                    selected="selected"><?php echo $geo_zone['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="shoputils_citycourier_status">
                            <?php if ($shoputils_citycourier_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="shoputils_citycourier_sort_order"
                                   value="<?php echo $shoputils_citycourier_sort_order; ?>" size="1"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_minimal_order; ?><br><span
                                class="help"><?php echo $entry_minimal_order_help; ?></span></td>
                        <td><input type="text" name="shoputils_citycourier_minimal_order"
                                   value="<?php echo $shoputils_citycourier_minimal_order; ?>" size="15"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_percent; ?><br><span
                                class="help"><?php echo $entry_percent_help; ?></span>
                        </td>
                        <td><input type="text" name="shoputils_citycourier_percent"
                                   value="<?php echo $shoputils_citycourier_percent; ?>" size="15"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_minimal_price; ?><br><span
                                class="help"><?php echo $entry_minimal_price_help; ?></span></td>
                        <td><input type="text" name="shoputils_citycourier_minimal_price"
                                   value="<?php echo $shoputils_citycourier_minimal_price; ?>" size="15"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_free_shipping; ?><br><span
                                class="help"><?php echo $entry_free_shipping_help; ?></span></td>
                        <td><input type="text" name="shoputils_citycourier_free_shipping"
                                   value="<?php echo $shoputils_citycourier_free_shipping; ?>" size="15"/></td>
                    </tr>
                </table>
                <div id="languages" class="htabs">
                    <?php foreach ($languages as $language) { ?>
                    <?php if ($language['status']) { ?>
                    <a href="#language<?php echo $language['language_id']; ?>"><img
                            src="view/image/flags/<?php echo $language['image']; ?>"
                            title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?></a>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <?php if ($language['status']) { ?>
                <div id="language<?php echo $language['language_id']; ?>">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_name; ?><br><span
                                    class="help"><?php echo $entry_name_help; ?></span></td>
                            <td><input type="text" size=70 id="name<?php echo $language['language_id']; ?>"
                                       name="langdata[<?php echo $language['language_id']; ?>][name]"
                                       value="<?php echo isset($langdata[$language['language_id']])
                                                   ? $langdata[$language['language_id']]['name'] : ''; ?>"/>
                        </tr>
                        <tr>
                            <td><?php echo $entry_description; ?><br><span
                                    class="help"><?php echo $entry_description_help; ?></span></td>
                            <td><textarea style="width:100%" id="description<?php echo $language['language_id']; ?>"
                                          name="langdata[<?php echo $language['language_id']; ?>][description]"><?php echo isset($langdata[$language['language_id']])
                                        ? $langdata[$language['language_id']]['description'] : ''; ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php } ?>
                <?php } ?>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript"><!--
$('#languages a').tabs();
$('#tabs a').tabs();
//--></script>

<?php echo $footer; ?>