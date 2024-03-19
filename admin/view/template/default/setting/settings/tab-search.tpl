<div id="tab-search">
    <h2><i class="fa fa-search"></i> Общие настройки</h2>
    <table class="form">
        <tr>
            <td style="width:20%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Библиотека поиска</span>
                    </p>
                    <select name="config_search_library">
                        <?php foreach ($search_libraries as $search_library) { ?>
                        <?php if ($search_library == $config_search_library) { ?>
                        <option value="<?php echo $search_library; ?>" selected="selected"><?php echo $search_library; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $search_library; ?>"><?php echo $search_library; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td style="width:40%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Маршрут полного поиска</span>
                    </p>
                    <input type="text" placeholder="search/catalog" name="config_search_catalog_route" value="<?php echo $config_search_catalog_route; ?>"/>
                </div>
            </td>
            <td style="width:40%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Маршрут ajax поиска</span>
                    </p>
                    <input type="text" placeholder="search/ajax" name="config_search_ajax_route" value="<?php echo $config_search_ajax_route; ?>"/>
                </div>
            </td>
        </tr>
    </table>

    <h2 style="color:#1035BC"><i class="fa fa-search"></i> TypeSense: параметры движка</h2>
    <table class="form">
        <tr>
            <td style="width:15%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Хост</span>
                    </p>
                    <input type="text" placeholder="localhost" name="config_typesense_host" value="<?php echo $config_typesense_host; ?>"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Порт</span>
                    </p>
                    <input type="text" placeholder="8108" name="config_typesense_port" value="<?php echo $config_typesense_port; ?>"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Протокол</span>
                    </p>
                    <input type="text" placeholder="http" name="config_typesense_protocol" value="<?php echo $config_typesense_protocol; ?>"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Api Key</span>
                    </p>
                    <input type="text" placeholder="http" name="config_typesense_api_key" value="<?php echo $config_typesense_api_key; ?>"/>
                </div>
            </td>

            <td style="width:20%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Индекс товаров</span>
                    </p>
                    <input type="text" placeholder="products" name="config_typesense_products_index" value="<?php echo $config_typesense_products_index; ?>"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Индекс категорий</span>
                    </p>
                    <input type="text" placeholder="categories" name="config_typesense_categories_index" value="<?php echo $config_typesense_categories_index; ?>"/>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Индекс брендов</span>
                    </p>
                    <input type="text" placeholder="manufacturers" name="config_typesense_manufacturers_index" value="<?php echo $config_typesense_manufacturers_index; ?>"/>
                </div>
                <div>
                    <span class="help">название индексов, в случае если на одном движке работает несколько магазинов</span>
                </div>
            </td>

            <td style="width:15%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Emdedded Name Index</span>
                    </p>
                    <select name="config_typesense_enable_embedded_name_index">
                        <?php if ($config_typesense_enable_embedded_name_index) { ?>
                        <option value="1" selected="selected">Да</option>
                        <option value="0">Нет</option>
                        <?php } else { ?>
                        <option value="1">Да</option>
                        <option value="0" selected="selected">Нет</option>
                        <? } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Emdedded Name Model</span>
                    </p>
                    <input type="text" placeholder="ts/all-MiniLM-L12-v2" name="config_typesense_embedded_name_index_model" value="<?php echo $config_typesense_embedded_name_index_model; ?>"/>
                    <br/>
                    <span class="help">список поддерживаемых embedded моделей можно найти на сайте typesense</span>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Api key</span>
                    </p>
                    <input type="text" placeholder="APIKEY" name="config_typesense_embedded_name_index_model_api_key" value="<?php echo $config_typesense_embedded_name_index_model_api_key; ?>"/>
                    <br/>
                    <span class="help">в случае, если токенизатор удаленный, например openai</span>
                </div>
            </td>

            <td style="width:15%">
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Индексировать описания</span>
                    </p>
                    <select name="config_typesense_enable_description_index">
                        <?php if ($config_typesense_enable_description_index) { ?>
                        <option value="1" selected="selected">Да</option>
                        <option value="0">Нет</option>
                        <?php } else { ?>
                        <option value="1">Да</option>
                        <option value="0" selected="selected">Нет</option>
                        <? } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Отдельный индекс брендов</span>
                    </p>
                    <select name="config_typesense_enable_manufacturer_index">
                        <?php if ($config_typesense_enable_manufacturer_index) { ?>
                        <option value="1" selected="selected">Да</option>
                        <option value="0">Нет</option>
                        <?php } else { ?>
                        <option value="1">Да</option>
                        <option value="0" selected="selected">Нет</option>
                        <? } ?>
                    </select>
                </div>
                <div>
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Индексировать связки</span>
                    </p>
                    <select name="config_typesense_enable_keyworder_index">
                        <?php if ($config_typesense_enable_keyworder_index) { ?>
                        <option value="1" selected="selected">Да</option>
                        <option value="0">Нет</option>
                        <?php } else { ?>
                        <option value="1">Да</option>
                        <option value="0" selected="selected">Нет</option>
                        <? } ?>
                    </select>
                </div>
            </td>
        </tr>
    </table>

    <h2><i class="fa fa-search"></i> TypeSense: Параметры поиска</h2>
    <table class="form">
        <tr>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Num Typos</span>
                </p>
                <input type="number" step="1" placeholder="2" name="config_typesense_search_num_typos" value="<?php echo $config_typesense_search_num_typos; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Maximum number of typographical errors (0, 1 or 2) that would be tolerated.</span>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Min Len-1 Typo</span>
                </p>
                <input type="number" placeholder="4" step="1" name="config_typesense_search_min_len_1typo" value="<?php echo $config_typesense_search_min_len_1typo; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Minimum word length for 1-typo correction to be applied. The value of num_typos is still treated as the maximum allowed typos.</span>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Min Len-2 Typo</span>
                </p>
                <input type="number" placeholder="7" step="1" name="config_typesense_search_min_len_2typo" value="<?php echo $config_typesense_search_min_len_2typo; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Minimum word length for 2-typo correction to be applied. The value of num_typos is still treated as the maximum allowed typos.</span>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Split Join Tokens</span>
                </p>
                <input type="text" placeholder="always" step="1" name="config_typesense_search_split_join_tokens" value="<?php echo $config_typesense_search_split_join_tokens; ?>" size="10" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Treat space as typo: search for q=basket ball if q=basketball is not found or vice-versa.</span>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#1035BC; color:#FFF"><i class="fa fa-search"></i> Split Join Tokens</span>
                </p>
                <input type="text" placeholder="1" step="1" name="config_typesense_search_typo_tokens_threshold" value="<?php echo $config_typesense_search_typo_tokens_threshold; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> If typo_tokens_threshold is set to a number N, if at least N results are not found for a search term, then Typesense will start looking for typo-corrected variations, until at least N results are found</span>
            </td>
        </tr>
    </table>

    <h2 style="color:#00ad07"><i class="fa fa-search"></i> ElasticSearch: параметры движка <sup style="color:red">Elastic
            support moved to legacy, i will not mantain it more</sup></h2>
    <table class="form">
        <tr>
            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Много model</span>
                </p>
                <select name="config_elasticseach_many_models">
                    <?php if ($config_elasticseach_many_models) { ?>
                    <option value="1" selected="selected">Да</option>
                    <option value="0">Нет</option>
                    <?php } else { ?>
                    <option value="1">Да</option>
                    <option value="0" selected="selected">Нет</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Разные варианты написания поля model</span>
            </td>

            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Много sku</span>
                </p>
                <select name="$config_elasticseach_many_skus">
                    <?php if ($config_elasticseach_many_skus) { ?>
                    <option value="1" selected="selected">Да</option>
                    <option value="0">Нет</option>
                    <?php } else { ?>
                    <option value="1">Да</option>
                    <option value="0" selected="selected">Нет</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Разные варианты написания поля model</span>
            </td>

            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Числа в текст</span>
                </p>
                <select name="config_elasticseach_many_textnumbers">
                    <?php if ($config_elasticseach_many_textnumbers) { ?>
                    <option value="1" selected="selected">Да</option>
                    <option value="0">Нет</option>
                    <?php } else { ?>
                    <option value="1">Да</option>
                    <option value="0" selected="selected">Нет</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> можно искать по "два, три, десять". значительно увеличивает индекс</span>
            </td>

            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Индексировать бренды</span>
                </p>
                <select name="config_elasticseach_index_manufacturers">
                    <?php if ($config_elasticseach_index_manufacturers) { ?>
                    <option value="1" selected="selected">Да</option>
                    <option value="0">Нет</option>
                    <?php } else { ?>
                    <option value="1">Да</option>
                    <option value="0" selected="selected">Нет</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> увеличивает индекс</span>
            </td>

            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Индексировать связки</span>
                </p>
                <select name="config_elasticseach_index_keyworder">
                    <?php if ($config_elasticseach_index_keyworder) { ?>
                    <option value="1" selected="selected">Да</option>
                    <option value="0">Нет</option>
                    <?php } else { ?>
                    <option value="1">Да</option>
                    <option value="0" selected="selected">Нет</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> увеличивает индекс</span>
            </td>


            <td style="width:15%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Использовать свои склады</span>
                </p>
                <select name="config_elasticsearch_use_local_stock">
                    <?php if ($config_elasticsearch_use_local_stock) { ?>
                    <option value="1" selected="selected">Включить</option>
                    <option value="0">Отключить</option>
                    <?php } else { ?>
                    <option value="1">Включить</option>
                    <option value="0" selected="selected">Отключить</option>
                    <? } ?>
                </select>

                <br/>
                <span class="help"><i class="fa fa-search"></i> Если включено, товары, которые есть на локальном складе - всегда будут вверху любых результатов поиска. При этом товары, которых нет на складе - значительно пессимизируются в выдаче</span>
            </td>
        </tr>
    </table>

    <h2 style="color:#00ad07"><i class="fa fa-search"></i> ElasticSearch: Параметры поиска <sup style="color:red">Elastic
            support moved to legacy, i will not mantain it more</sup></h2>
    <table class="form">
        <tr>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора товаров</span>
                </p>
                <input type="number" step="0.1" name="config_elasticsearch_fuzziness_product" value="<?php echo $config_elasticsearch_fuzziness_product; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
            </td>

            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора категорий</span>
                </p>
                <input type="number" step="0.1" name="config_elasticsearch_fuzziness_category" value="<?php echo $config_elasticsearch_fuzziness_category; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
            </td>

            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для автокомплита</span>
                </p>
                <input type="number" step="0.1" name="config_elasticsearch_fuzziness_autcocomplete" value="<?php echo $config_elasticsearch_fuzziness_autcocomplete; ?>" size="3" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
            </td>

            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Суффикс именования индексов</span>
                </p>
                <input type="text" name="config_elasticsearch_index_suffix" value="<?php echo $config_elasticsearch_index_suffix; ?>" size="20" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> в случае работы нескольки магазинов на одном движке</span>
            </td>


            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Минимум символов для поиска</span>
                </p>
                <input type="number" name="config_elasticseach_index_autocomplete_symbols" value="<?php echo $config_elasticseach_index_autocomplete_symbols; ?>" size="20" style="width:100px;"/>

                <br/>
                <span class="help"><i class="fa fa-search"></i> если меньше - работает только автокомплит</span>
            </td>
        </tr>
    </table>
</div>