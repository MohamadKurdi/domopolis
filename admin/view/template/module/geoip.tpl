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
                <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>

                <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a
                            onclick="location = '<?php echo $cancel; ?>';"
                            class="button"><?php echo $button_cancel; ?></a></div>
            </div>
            <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-general"><?php echo $tab_general; ?></a>
                <a href="#tab-popup"><?php echo $tab_popup; ?></a>
                <a href="#tab-messages"><?php echo $tab_messages; ?></a>
                <a href="#tab-redirects"><?php echo $tab_redirects; ?></a>
                <a href="#tab-currencies"><?php echo $tab_currencies; ?></a>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-general">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_set_zone; ?></td>
                            <td>
                                <input type="radio" name="geoip_setting[set_zone]" value="1"
                                        <?php echo !empty($geoip_setting['set_zone']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="geoip_setting[set_zone]" value="0"
                                        <?php echo empty($geoip_setting['set_zone']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_no; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_from_ajax; ?></td>
                            <td>
                                <input type="radio" name="geoip_setting[from_ajax]" value="1"
                                        <?php echo !empty($geoip_setting['from_ajax']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="geoip_setting[from_ajax]" value="0"
                                        <?php echo empty($geoip_setting['from_ajax']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_no; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tab-popup">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_popup_active; ?></td>
                            <td>
                                <input type="radio" name="geoip_setting[popup_active]" value="1"
                                        <?php echo !empty($geoip_setting['popup_active']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="geoip_setting[popup_active]" value="0"
                                        <?php echo empty($geoip_setting['popup_active']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_no; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_popup_cookie_time; ?></td>
                            <td><input type="text" name="geoip_setting[popup_cookie_time]"
                                       value="<?php echo !empty($geoip_setting['popup_cookie_time']) ? $geoip_setting['popup_cookie_time'] : '' ?>">
                            </td>
                        </tr>
                    </table>
                    <h3><?php echo $text_popup_cities; ?></h3>
                    <table id="cities" class="list">
                        <thead>
                        <td class="left"><?php echo $entry_city; ?></td>
                        <td></td>
                        </thead>
                        <?php $city_row = 0; ?>
                        <?php foreach ($cities as $city) { ?>
                            <tbody id="city-row<?php echo $city_row; ?>">
                            <tr>
                                <td class="left">
                                    <input type="text" name="geoip_city[<?php echo $city_row; ?>][name]"
                                           value="<?php echo $city['name']; ?>" class="row-fias-name"/>
                                    <input type="hidden" name="geoip_city[<?php echo $city_row; ?>][fias_id]"
                                           value="<?php echo $city['fias_id']; ?>" class="row-fias-id"/>
                                    <input type="hidden" name="geoip_city[<?php echo $city_row; ?>][geoip_city_id]"
                                           value="<?php echo $city['geoip_city_id']; ?>"/>
                                    <?php if (isset($error_city_fias[$city_row])) { ?>
                                        <span class="error"><?php echo $error_city_fias[$city_row]; ?></span>
                                    <?php } ?>
                                </td>

                                <td class="left"><a onclick="$('#city-row<?php echo $city_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $city_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td class="left"><a onclick="addCity();"
                                                class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="tab-messages">
                    <table id="rules" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_key; ?></td>
                            <td class="left"><?php echo $entry_zone; ?></td>
                            <td class="left"><?php echo $entry_value; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $rule_row = 0; ?>
                        <?php foreach ($rules as $rule) { ?>
                            <tbody id="rule-row<?php echo $rule_row; ?>">
                            <tr>
                                <td class="left"><input type="text"
                                                        name="geoip_rule[<?php echo $rule_row; ?>][key]"
                                                        value="<?php echo $rule['key']; ?>"/>
                                    <?php if (isset($error_key[$rule_row])) { ?>
                                        <span class="error"><?php echo $error_key[$rule_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <input type="text" name="" value="<?php echo $rule['fias_name']; ?>"
                                           class="row-fias-name"/>
                                    <input type="hidden" name="geoip_rule[<?php echo $rule_row; ?>][fias_id]"
                                           value="<?php echo $rule['fias_id']; ?>" class="row-fias-id"/>
                                    <input type="hidden" name="geoip_rule[<?php echo $rule_row; ?>][geoip_rule_id]"
                                           value="<?php echo $rule['geoip_rule_id']; ?>"/>
                                    <?php if (isset($error_fias[$rule_row])) { ?>
                                        <span class="error"><?php echo $error_fias[$rule_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <textarea
                                            name="geoip_rule[<?php echo $rule_row; ?>][value]"><?php echo $rule['value']; ?></textarea>
                                </td>

                                <td class="left"><a onclick="$('#rule-row<?php echo $rule_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $rule_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="left"><a onclick="addRule();"
                                                class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="tab-redirects">
                    <table id="redirects" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_zone; ?></td>
                            <td class="left"><?php echo $entry_subdomain; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $redirect_row = 0; ?>
                        <?php foreach ($redirects as $redirect) { ?>
                            <tbody id="redirect-row<?php echo $redirect_row; ?>">
                            <tr>
                                <td class="left">
                                    <input type="text" name="" value="<?php echo $redirect['fias_name']; ?>"
                                           class="row-fias-name"/>
                                    <input type="hidden" name="geoip_redirect[<?php echo $redirect_row; ?>][fias_id]"
                                           value="<?php echo $redirect['fias_id']; ?>" class="row-fias-id"/>
                                    <input type="hidden"
                                           name="geoip_redirect[<?php echo $redirect_row; ?>][geoip_redirect_id]"
                                           value="<?php echo $redirect['geoip_redirect_id']; ?>"/>
                                    <?php if (isset($error_redirect_fias[$redirect_row])) { ?>
                                        <span class="error"><?php echo $error_redirect_fias[$redirect_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <input type="text" name="geoip_redirect[<?php echo $redirect_row; ?>][url]"
                                           value="<?php echo $redirect['url']; ?>"/>
                                    <?php if (isset($error_subdomain[$redirect_row])) { ?>
                                        <span class="error"><?php echo $error_subdomain[$redirect_row]; ?></span>
                                    <?php } ?>
                                </td>

                                <td class="left"><a onclick="$('#redirect-row<?php echo $redirect_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $redirect_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="left"><a onclick="addRedirect();"
                                                class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="tab-currencies">
                    <table id="currencies" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_country; ?></td>
                            <td class="left"><?php echo $entry_currency; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $currency_row = 0; ?>
                        <?php foreach ($geoip_currencies as $geoip_currency) { ?>
                            <tbody id="currency-row<?php echo $currency_row; ?>">
                            <tr>
                                <td class="left">
                                    <select name="geoip_currency[<?php echo $currency_row; ?>][country_id]">
                                        <option value="0"><?php echo $text_none; ?></option>
                                        <?php foreach ($countries as $country) { ?>
                                            <option value="<?php echo $country['country_id']; ?>"
                                                    <?php echo $country['country_id'] == $geoip_currency['country_id'] ? 'selected' : ''; ?>>
                                                <?php echo $country['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($error_currency_country[$currency_row])) { ?>
                                        <span class="error"><?php echo $error_currency_country[$currency_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <select name="geoip_currency[<?php echo $currency_row; ?>][code]">
                                        <option value="0"><?php echo $text_none; ?></option>
                                        <?php foreach ($currencies as $currency) { ?>
                                            <option value="<?php echo $currency['code']; ?>"
                                                    <?php echo $currency['code'] == $geoip_currency['code'] ? 'selected' : ''; ?>>
                                                <?php echo $currency['title']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php if (isset($error_currency_code[$currency_row])) { ?>
                                        <span class="error"><?php echo $error_currency_code[$currency_row]; ?></span>
                                    <?php } ?>
                                </td>

                                <td class="left"><a onclick="$('#currency-row<?php echo $currency_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $currency_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="left"><a onclick="addCurrency();"
                                                class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>

                    <select id="select-countries" style="display: none;">
                        <option value="0"><?php echo $text_none; ?></option>
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>">
                                <?php echo $country['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <select id="select-currencies" style="display: none;">
                        <option value="0"><?php echo $text_none; ?></option>
                        <?php foreach ($currencies as $currency) { ?>
                            <option value="<?php echo $currency['code']; ?>">
                                <?php echo $currency['title']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </form>
            </div>
        </div>
    </div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
    var rule_row = <?php echo $rule_row; ?>;

    function addRule() {
        var html = '<tbody id="rule-row' + rule_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="geoip_rule[' + rule_row + '][key]"/></td>';
        html += '    <td class="left"><input type="text" name="" class="row-fias-name"/>';
        html += '<input type="hidden" name="geoip_rule[' + rule_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_rule[' + rule_row + '][geoip_rule_id]"/></td>';
        html += '    <td class="left"><textarea name="geoip_rule[' + rule_row + '][value]"/></td>';
        html += '    <td class="left"><a onclick="$(\'#rule-row' + rule_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#rules').find('tfoot').before(html);

        rule_row++;
    }
    
    var redirect_row = <?php echo $redirect_row; ?>;

    function addRedirect() {
        var html = '<tbody id="redirect-row' + redirect_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="" class="row-fias-name"/>';
        html += '<input type="hidden" name="geoip_redirect[' + redirect_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_redirect[' + redirect_row + '][geoip_redirect_id]"/></td>';
        html += '    <td class="left"><input type="text" name="geoip_redirect[' + redirect_row + '][url]"/></td>';
        html += '    <td class="left"><a onclick="$(\'#redirect-row' + redirect_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#redirects').find('tfoot').before(html);

        redirect_row++;
    }    
    
    var currency_row = <?php echo $currency_row; ?>;

    function addCurrency() {
        var html = '<tbody id="currency-row' + currency_row + '">';
        html += '  <tr>';
        html += '    <td class="left">';
        html += '       <select name="geoip_currency[' + currency_row + '][country_id]">';
        html += $('#select-countries').html();
        html += '       </select></td>';
        html += '    <td class="left"><select name="geoip_currency[' + currency_row + '][code]">';
        html += $('#select-currencies').html();
        html += '       </select></td>';
        html += '    <td class="left"><a onclick="$(\'#currency-row' + currency_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#currencies').find('tfoot').before(html);

        currency_row++;
    }    
    
    var city_row = <?php echo $city_row; ?>;

    function addCity() {
        var html = '<tbody id="city-row' + city_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="geoip_city[' + city_row + '][name]" class="row-fias-name"/>';
        html += '<input type="hidden" name="geoip_city[' + city_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_city[' + city_row + '][geoip_city_id]" value=""/></td>';
        html += '    <td class="left"><a onclick="$(\'#city-row' + city_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#cities').find('tfoot').before(html);

        city_row++;
    }
//--></script>
<script type="text/javascript"><!--
    $('#rules, #redirects, #cities').on('focus', '.row-fias-name', function() {
        if (!$(this).data('autocomplete')) {
            addAutocomplete($(this));
        }
    });

    $('.row-fias-name').each(function() {
        addAutocomplete($(this));
    });

    function addAutocomplete(el) {
        el.autocomplete({
            source:    'index.php?route=module/geoip/search&token=<?php echo $token; ?>',
            minLength: 2,
            select:    function(e, ui) {
                $(this).next('.row-fias-id').val(ui.item.fias_id);
            }
        });
    }
//--></script>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
//--></script>