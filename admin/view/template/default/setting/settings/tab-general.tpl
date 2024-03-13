<div id="tab-general">
    <h2>Основное</h2>
    <table class="form">
        <tr>
            <td style="width:33%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Название</span>
                    </p>
                    <input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">HTTPS (для соместимости с хрефланг)</span>
                    </p>
                    <input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_owner; ?></span>
                    </p>
                    <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40"/>
                </div>

            </td>
            <td style="width:33%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Время работы</span>
                    </p>
                    <input type="text" name="config_worktime" value="<?php echo $config_worktime; ?>" size="60"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_address; ?></span>
                    </p>
                    <textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
                </div>
            </td>
            <td style="width:33%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_email; ?></span>
                    </p>
                    <input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail для отображения в контактах</span>
                    </p>
                    <input type="text" name="config_display_email" value="<?php echo $config_display_email; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail оптовый</span>
                    </p>
                    <input type="text" name="config_opt_email" value="<?php echo $config_opt_email; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail b2b для отображения</span>
                    </p>
                    <input type="text" name="config_email_b2b_display" value="<?php echo $config_email_b2b_display; ?>" size="40"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail b2b TO</span>
                    </p>
                    <input type="text" name="config_email_b2b_to" value="<?php echo $config_email_b2b_to; ?>" size="40"/>
                </div>
            </td>
        </tr>
    </table>

    <h2>Телефоны</h2>
    <table class="form">
        <tr>
            <td style="width:25%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст над</span>
                    </p>
                    <input type="text" name="config_t_tt" value="<?php echo $config_t_tt; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 1</span>
                    </p>
                    <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст под</span>
                    </p>
                    <input type="text" name="config_t_bt" value="<?php echo $config_t_bt; ?>" size="40"/>
                </div>
            </td>

            <td style="width:25%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст над</span>
                    </p>
                    <input type="text" name="config_t2_tt" value="<?php echo $config_t2_tt; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 2</span>
                    </p>
                    <input type="text" name="config_telephone2" value="<?php echo $config_telephone2; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст под</span>
                    </p>
                    <input type="text" name="config_t2_bt" value="<?php echo $config_t2_bt; ?>" size="40"/>
                </div>
            </td>

            <td style="width:25%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 3</span>
                    </p>
                    <input type="text" name="config_telephone3" value="<?php echo $config_telephone3; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 4</span>
                    </p>
                    <input type="text" name="config_telephone4" value="<?php echo $config_telephone4; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Факс</span>
                    </p>
                    <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" size="40"/>
                </div>
            </td>

            <td style="width:25%">

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Оптовый телефон - 1</span>
                    </p>
                    <input type="text" name="config_opt_telephone" value="<?php echo $config_opt_telephone; ?>" size="40"/>
                </div>

                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Оптовый телефон - 2</span>
                    </p>
                    <input type="text" name="config_opt_telephone2" value="<?php echo $config_opt_telephone2; ?>" size="40"/>
                </div>
            </td>
        </tr>
    </table>

    <h2>Другие настройки</h2>
    <table class="form">
        <tr>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Mета тайтл</span>
                </p>
                <textarea name="config_title" cols="40" rows="5"><?php echo $config_title; ?></textarea>
            </td>

            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Мета описание</span>
                </p>
                <textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea>
            </td>

            <td style="width:20%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Шаблон</span>
                    </p>
                    <select name="config_template">
                        <?php foreach ($templates as $template) { ?>
                        <?php if ($template == $config_template) { ?>
                        <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_layout; ?></span>
                    </p>
                    <select name="config_layout_id" style="width:150px;">
                        <?php foreach ($layouts as $layout) { ?>
                        <?php if ($layout['layout_id'] == $config_layout_id) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </td>

            <td style="width:20%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Добавить меню в homepage</span>
                    </p>
                    <select type="select" name="config_mmenu_on_homepage">
                        <? if ($config_mmenu_on_homepage) { ?>
                        <option value="1" selected='selected'>Включить</option>
                        <option value="0">Отключить</option>
                        <? } else { ?>
                        <option value="1">Включить</option>
                        <option value="0" selected='selected'>Отключить</option>
                        <? } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бестселлеры в мегаменю</span>
                    </p>
                    <select type="select" name="config_bestsellers_in_mmenu">
                        <? if ($config_bestsellers_in_mmenu) { ?>
                        <option value="1" selected='selected'>Включить</option>
                        <option value="0">Отключить</option>
                        <? } else { ?>
                        <option value="1">Включить</option>
                        <option value="0" selected='selected'>Отключить</option>
                        <? } ?>
                    </select>
                </div>
            </td>

            <td style="width:20%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бренды в мегаменю</span>
                    </p>
                    <select type="select" name="config_brands_in_mmenu">
                        <? if ($config_brands_in_mmenu) { ?>
                        <option value="1" selected='selected'>Включить</option>
                        <option value="0">Отключить</option>
                        <? } else { ?>
                        <option value="1">Включить</option>
                        <option value="0" selected='selected'>Отключить</option>
                        <? } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бренды на главной</span>
                    </p>
                    <select type="select" name="config_brands_on_homepage">
                        <? if ($config_brands_on_homepage) { ?>
                        <option value="1" selected='selected'>Включить</option>
                        <option value="0">Отключить</option>
                        <? } else { ?>
                        <option value="1">Включить</option>
                        <option value="0" selected='selected'>Отключить</option>
                        <? } ?>
                    </select>
                </div>
            </td>
        </tr>
    </table>
</div>