<?php echo $header; ?>
<style>
    div.red{
        background-color:#ef5e67;
    }
    div.orange{
        background-color:#ff7f00;
    }
    div.green{
        background-color:#00ad07;
    }
    div.black{
        background-color:#2e3438;
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
            <div id="rnf-status" style="float: left; line-height: 26px; padding-top: 5px; margin-left:20px;" class="delayed-load short-delayed-load" data-route='setting/rnf/getRainForestStats' data-reload="5000"></div>      

            <div class="buttons">
                <a href="<?php echo $queue; ?>" class="button" style="margin-left:100px;"><i class="fa fa-backward"></i> Вернуться в очередь</a>
                <a class="button" onclick="clear_cat();"><i class="fa fa-eraser"></i> Очистить категорию магазина</a>
                <a class="button" onclick="clear_all();"><i class="fa fa-eraser"></i> Очистить всё</a>
                <?php if (!$this->config->get('config_rainforest_disable_add_all_button')) { ?>
                    <a class="button" onclick="add_all();"><i class="fa fa-plus"></i> Добавить все</a>  
                <?php } ?>              
                <a class="button" onclick="reload();"><i class="fa fa-refresh"></i> Загрузить / Обновить</a>
            </div>     
        </div>
        <div class="content">        
            <div class="clr"></div>
            <div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px;">
                <table style="width:100%;">
                    <tr>
                        <td style="width:10%">
                            <div>
                                <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Тип запроса</p><br />
                                <select name="type">
                                    <?php foreach (\hobotix\RainforestAmazon::searchPageTypes as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>" <?php if ($value['default']) { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help">должен соответствовать ссылке</span>
                            </div>
                        </td>
                        <td style="width:40%">
                            <div>
                              <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Ссылка с Amazon.de</p><br />
                              <input type="text" id="amazon-category-url" name="url" style="width:90%" placeholder="Перейди на amazon, скопируй и вставь" />
                              <span class="help">любая ссылка листинга на <a href="https://amazon.de/" target="_blank">amazon.de</a>, важно соответствие страницы типу запроса</span>
                          </div>
                      </td>
                      <td style="width:10%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF;"><i class="fa fa-plus"></i> Офферы</p> <sup style="color:#CF4A61">DEV</sup><br />
                            <select name="offers">
                                <option value="1">Да</option>
                                <option value="0" selected="selected">Нет</option>
                            </select>
                        </div>
                    </td>
                      <td style="width:20%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF;">category_id</p><br />
                            <input type="text" name="category" style="width:90%" placeholder="bestsellers_appliances" />
                            <span class="help">
                                <a style="text-decoration:underline;" href="index.php?route=catalog/category/getAmazonCategoriesCSV&token=<?php echo $token; ?>"><i class="fa fa-download"></i> bestseller</a>, 
                                <i class="fa fa-download"></i> category
                            </span>
                        </div>
                        </td>
                    <td style="width:10%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF;">Сортировка</p><br />
                            <select name="sort">
                               <?php foreach (\hobotix\RainforestAmazon::searchSorts as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>" <?php if ($value['default']) { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>
                                    <?php } ?>
                            </select>
                            <span class="help">работает не со всеми типами</span>
                        </div>
                    </td>
                    <td style="width:10%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF;">Страница</p><br />
                            <input type="number" step="1" name="page" style="width:50px;" value="1" />
                            <span class="help">страница результатов</span>
                        </div>
                    </td>                    
                </tr>
            </table>

            <table style="width:100%;">
                <tr>
                    <td class="left" style="width:40%">
                        <div id="amazon_category_search_field">
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF;"><i class="fa fa-amazon"></i> Заполнить из категории Amazon</p><br />
                            <input type="text" name="category_amazon" style="width:90%" placeholder="Начни заполнять, чтоб выбрать категорию amazon, только на языке <?php echo $this->config->get('config_rainforest_source_language')?>" />                            
                            <span class="help">подбор выполняется соответственно с выбранным типом запроса</span>
                        </div>
                        <div id="amazon_general_search_field" style="display:none;">
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF;"><i class="fa fa-amazon"></i> Искать на Amazon</p><br />
                            <input type="text" name="search_term" style="width:90%" placeholder="любой запрос, только на языке <?php echo $this->config->get('config_rainforest_source_language')?>" />
                            <a class="button" onclick="reload();"><i class="fa fa-search"></i></a>                           
                            <span class="help">выводит товары без привязки к категориям</span>
                        </div>
                    </td>

                    <td class="left" style="width:40%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF;">
                                <i class="fa fa-exclamation-circle"></i> Заполнить и привязать к категории магазина.
                                <?php if ($this->config->get('config_rainforest_do_not_add_without_category')) { ?>
                                    ЗАПОЛНЯТЬ ОБЯЗАТЕЛЬНО!
                                <?php } ?>
                              </p>
                            <input type="text" name="category_shop" style="width:90%" placeholder="Начни заполнять, чтоб выбрать категорию" />                           
                            <span class="help">будет добавлен в эту категорию, если не задать, будет попытка автоматического определения, выбираются только конечные категории</span>
                        </div>
                    </td>

                    <td class="left" style="width:10%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF;"><i class="fa fa-refresh"></i> Бренды</p><br />
                            <select style="width:100px;" name="brand_logic">
                                <option value="0">Нет</option>
                                <option value="1">Да</option>
                            </select>
                            <span class="help">раскладывать по брендам</span>
                        </div>
                    </td>

                    <td class="left" style="width:10%">
                        <div>
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF;"><i class="fa fa-bars"></i> ID магазина</p><br />
                            <input style="width:100px;" type="number" step="1" name="category_id" value="0" />
                            <span class="help">заполняется автоматически</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>            

        <div id="result">
            <div style="font-size:18px; padding:50px; line-height:24px;">               
                <i class="fa fa-info-circle"></i> Можно подбирать категории в обе стороны, если существуют привязки. 
                <ul>
                    <li>
                        Можно выбрать категорию магазина, и данные для запроса заполнятся, если к категории привязана категория Amazon. Текущий режим работы и привязок: <b><?php echo $this->config->get('config_rainforest_category_model');?></b>. Если текущий режим равен типу запроса, то заполнение категории магазина не обязательно - в процессе разбора данных о товаре он с высокой вероятностью попадет в нужную категорию.
                    </li>
                    <li>
                        Можно также выбрать категорию Amazon. Поиск по дереву категорий Amazon выполняется с учётом типа запроса (bestsellers, standard).
                    </li>
                </ul>
                
                <i class="fa fa-info-circle"></i> Загрузка данных происходит либо по ссылке, либо по комбинации "category_id + страница + сортировка"<br />
                <i class="fa fa-info-circle"></i> При загрузке по ссылке нельзя выбрать сортировку и страницу, так как она определена в самой ссылке<br />
                <i class="fa fa-info-circle"></i> Сортировка также не работает для бестселлеров (только пагинация, у бестселлеров максимум 2 страницы). Она у амазона вообще очень странно работает.<br />
                <i class="fa fa-info-circle"></i> Обязательно нужно выбирать правильный тип запроса, в API нет автоопределения<br />
                <i class="fa fa-info-circle"></i> Обновление результатов происходит при изменении любого поля<br />
                <i class="fa fa-info-circle"></i> В процессе отбора товары могут быть исключены по цене, либо низкому рейтингу<br />
                <i class="fa fa-info-circle"></i> Также не отображаются товары, из <a target="_blank" href="index.php?route=report/product_deletedasin&token=<?php echo $token;?>">справочника удаленных ASIN</a><br />
                <i class="fa fa-info-circle"></i> Также исключаются уже существующие в базе товары, либо те, которые уже в очереди на добавление<br /><br />                
                <i class="fa fa-info-circle"></i> Нажатие на кнопку удаления добавляет товар в <a target="_blank" href="index.php?route=report/product_deletedasin&token=<?php echo $token;?>">список исключенных</a><br />
                <i class="fa fa-info-circle"></i> Добавление товаров в базу не происходит моментально, а в течении 10-15 минут, при активной работе возможно и дольше<br />
                <i class="fa fa-info-circle"></i> Мониторить очередь ручного добавления <a target="_blank" href="index.php?route=catalog/addasin&token=<?php echo $token;?>">можно здесь</a><br />
            </div>

        </div>
    </div>
</div>
</div>  
<script type="text/javascript">
    function fade(asin){
        $('#' + asin + '-wrap').fadeOut(300, function(){ $('#' + asin + '-wrap').remove(); });
    }

    function add(asin){
        var category_id = $('input[name=category_id]').val();
        var brand_logic = $('select[name=brand_logic]').children("option:selected").val();

        <?php if ($this->config->get('config_rainforest_do_not_add_without_category')) { ?>
            console.log(category_id);
            if (!category_id || category_id == 0){
                swal({ title: "Заполни категорию привязки", text: "Включен режим, запрещающий добавление товаров без привязки к категории магазина", type: "error"}, function() {  });
                return;
            }            
        <?php } ?>

        if (asin){
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'index.php?route=kp/amazon/add&token=<?php echo $token; ?>',
                data: {
                    asin:   asin,
                    category_id: category_id,
                    brand_logic: brand_logic
                },
                beforeSend: function(){
                    $('#' + asin + '-add-status').html('<i class="fa fa-spinner fa-spin"></i> Добавляю');
                },
                success: function(html){
                    $('#' + asin + '-add-status').html('<i class="fa fa-check-circle"></i> В очереди');
                    $('#' + asin + '-wrap').addClass('grey');
                    window.setTimeout( fade(asin) , 3000 );             
                },                
                error: function(html){
                    $('#' + asin + '-add-status').html('<i class="fa fa-exclamation-circle"></i> Ошибка!');
                }
            });

        }
    }

    function ignore(asin){
        if (asin){
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'index.php?route=kp/amazon/ignore&token=<?php echo $token; ?>',
                data: {
                    asin:   asin
                },
                beforeSend: function(){
                    $('#' + asin + '-ignore-status').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(html){
                    $('#' + asin + '-ignore-status').html('<i class="fa fa-check-circle"></i>');
                    $('#' + asin + '-wrap').addClass('grey');
                    window.setTimeout( fade(asin) , 3000 );             
                },                
                error: function(html){
                    $('#' + asin + '-ignore-status').html('<i class="fa fa-exclamation-circle"></i>');
                }
            });

        }
    }
</script>

<script type="text/javascript">
 <?php if (!$this->config->get('config_rainforest_disable_add_all_button')) { ?> 
    function add_all(){
        $( ".products-container-product-add" ).each(function( index ) {
            $(this).trigger('click');
        });
    }
<?php } ?>

    function clear_cat(){                                    
        $('input[name=category_shop]').val('');
        $('input[name=category_id]').val('0');
    }

    function clear_all(){                                    
        $('input[name=url]').val('');
        $('input[name=category]').val('');
        $('input[name=page]').val('1');
        $('input[name=category_amazon]').val('');
        $('input[name=search_term]').val('');
        $('input[name=category_shop]').val('');
        $('input[name=category_id]').val('0');
        $('select[name=brand_logic]').val('0');
    }

    function iS(){
        const funnyPhrases = [
          "Изучаю язык воронов..",
          "Пытаюсь выучить азбуку Морзе в обратном порядке..",
          "Собираю мозаику из печенья фортуны..",
          "Ищу иголку в стоге сена (и не думайте, что это легко)..",
          "Тестирую гравитационные законы на своем коте..",
          "Охота за бабочками... с сеткой из спагетти..",
          "Строю замок из карт..",
          "Ищу сокровища на дне своего кофейного кубка..",
          "Пишу стихи о котах на языке эльфов..",
          "Рисую портрет своего компьютера..",
          "Перебираю книги в поисках самой длинной фразы..",
          "Учусь играть на битбоксе..",
          "Пытаюсь прыгнуть через собственный хвост..",
          "Катаю резинового утенка по офисному столу..",
          "Ищу алфавит в облаках..",
          "Разговариваю с домашними растениями..",
          "Пытаюсь заменить все слова в предложении на синонимы..",
          "Играю в «Крестики-нолики» с самим собой..",
          "Читаю книгу задом наперед..",
          "Тренируюсь в медитации на звук печатной машинки..",
          "Рисую смайлики на кусочках льда..",
          "Пытаюсь узнать, какие звуки издают листья..",
          "Изучаю произношение самых длинных слов в мире..",
          "Рисую красивые картинки на песке в песочнице..",
          "Слушаю музыку и пытаюсь петь под нее на языке, которого не существует..",
          "Заполняю лабиринт на обратной стороне книги..",
          "Ищу секреты жизни в прозрачных кристаллах льда..",
          "Пытаюсь запомнить все цвета радуги в обратном порядке..",
          "Отсчитываю все свои родственные связи до пятого колена..",
          "Решаю головоломки на одной ноге..",
          'Подготавливаю слона к пешему переходу через Альпы..',
          'Общаюсь с ChatGPT..',
          'Знакомлюсь с Памелой Андерсон..'
          ];

        $('#text-counter').html(funnyPhrases[Math.floor(Math.random()*funnyPhrases.length)] + '<br/>');
    }

    function iM() {
        window.msc += 1;
        seconds = Math.floor(window.msc / 100);
        milliseconds = window.msc % 100;
        $('#milliseconds-counter').html('<i class="fa fa-clock-o"></i> ' + seconds + ':<small>' + milliseconds + '</small>');
    }

    function page(i){
        $('input[name=page]').val(i).trigger('change');
    }

    function setAllDisabled(){
        $('select[name=type]').prop('disabled', true);
        $('input[name=url]').prop('disabled', true);
        $('input[name=category]').prop('disabled', true);
        $('input[name=search_term]').prop('disabled', true);
        $('input[name=page]').prop('disabled', true);
        $('select[name=sort]').prop('disabled', true);
        $('input[name=category_amazon]').prop('disabled', true);
        $('input[name=category_shop]').prop('disabled', true);
        $('select[name=brand_logic]').prop('disabled', true);
    }

    function setAllEnabled(){
        $('select[name=type]').prop('disabled', false);
        $('input[name=url]').prop('disabled', false);
        $('input[name=category]').prop('disabled', false);
        $('input[name=search_term]').prop('disabled', false);
        $('input[name=page]').prop('disabled', false);
        $('select[name=sort]').prop('disabled', false);
        $('input[name=category_amazon]').prop('disabled', false);
        $('input[name=category_shop]').prop('disabled', false);
        $('select[name=brand_logic]').prop('disabled', false);
    }

    function reload(){
        var type            = $('select[name=type]').children("option:selected").val();
        var url             = $('input[name=url]').val();
        var category        = $('input[name=category]').val();
        var search_term     = $('input[name=search_term]').val();
        var page            = $('input[name=page]').val();
        var sort            = $('select[name=sort]').val();
        var brand_logic     = $('select[name=brand_logic]').children("option:selected").val();

        let catUrl;
        async function getCat() {
            const response = await fetch('index.php?route=common/home/cat&token=<?php echo $token; ?>');
            catUrl = await response.text();
        }

        getCat().then(() => { 

            var counterhtml = '<div style="text-align:center; padding-top:20px; color:#00ad07">';
            counterhtml += catUrl;
            counterhtml += '<br /><br />';
            counterhtml += '<span id="milliseconds-counter" style="font-size:24px;"></span>';
            counterhtml += '<br /><br />';        
            counterhtml += '<span style="color:#cf4a61"><i class="fa fa-lock"></i> Все поля ввода заблокированы на время запроса</span>';
            counterhtml += '<br /><br />';
            counterhtml += '<span id="text-counter" style="font-size:14px;"></span>';
            counterhtml +='</div>';

            if (url || category || search_term){
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: 'index.php?route=kp/amazon/getRainforestPage&token=<?php echo $token; ?>',
                    data: {
                        type:       type,
                        url:        url,
                        category:      category,
                        brand_logic:   brand_logic,
                        search_term: search_term,
                        page:       page,
                        sort:       sort,
                    },
                    beforeSend: function(){
                        $('#result').html(counterhtml);

                        setAllDisabled();

                        window.msc = 0;
                        window.intrvl = setInterval(iM, 10);
                        window.intrvl2 = setInterval(iS, 2000);
                    },
                    success: function(html){                                        
                        $('#result').html(html);

                        setAllEnabled();

                        clearInterval(window.intrvl);
                        clearInterval(window.intrvl2);
                    }
                });
            }      
        });            
    }   

    $('input[name=page], select[name=sort]').on('change', function() {
       reload();
    });

    $('select[name=type]').bind('change', function() {
        if ($('select[name=type]').val() == 'search'){
            $('#amazon_general_search_field').fadeIn();
        } else {
            $('input[name=search_term]').val('');
            $('#amazon_general_search_field').fadeOut();
        }
    });
</script>

<script type="text/javascript">
    $('input[name=\'category_amazon\']').autocomplete({
        delay: 500,
        source: function(request, response) {       
            $.ajax({
                url: 'index.php?route=catalog/category/amazon_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term) + '&type=' + encodeURIComponent($('select[name=type]').val()),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        'category_id':  0,
                        'name':  'Не выбрано'
                    });

                    response($.map(json, function(item) {
                        return {
                            label: item.path,
                            value: item.id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('input[name=\'category_amazon\']').val(ui.item.label);
            $('input[name=\'category\']').val(ui.item.value);

            $.ajax({
                url: 'index.php?route=catalog/category/amazon_info_by_amazon_id&token=<?php echo $token; ?>&amazon_category_id=' +  ui.item.value,
                dataType: 'json',
                success: function(json) {
                    if (json.category_id){
                        $('input[name=\'category_id\']').val(json.category_id);
                        $('input[name=\'category_shop\']').val(json.name);
                    }

                     reload();
                }
            });

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });


    $('input[name=\'category_shop\']').autocomplete({
        delay: 500,
        source: function(request, response) {       
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete_final&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        'category_id':  0,
                        'name':  'Не выбрано'
                    });

                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.category_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('input[name=\'category_shop\']').val(ui.item.label);
            $('input[name=\'category_id\']').val(ui.item.value);

            if ($('select[name=\'type\']').val() == '<?php echo $this->config->get('config_rainforest_category_model'); ?>'){
                console.log($('select[name=\'type\']').val() + ' is equal to current model, trying to complete category_id');
                $.ajax({
                    url: 'index.php?route=catalog/category/amazon_info&token=<?php echo $token; ?>&category_id=' +  parseInt(ui.item.value),
                    dataType: 'json',
                    success: function(json) {
                        if (json.amazon_category_id){
                            $('input[name=\'category\']').val(json.amazon_category_id);
                        }
                    }
                });
            } else {
                console.log($('select[name=\'type\']').val() + ' is not equal to current model');
            }

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });
</script> 

<script type="text/javascript">
    function filter() {
        url = 'index.php?route=catalog/addasin&token=<?php echo $token; ?>';

        var filter_asin = $('input[name=\'filter_asin\']').attr('value');

        if (filter_asin) {
            url += '&filter_asin=' + encodeurlComponent(filter_asin);
        }

        var filter_name = $('input[name=\'filter_name\']').attr('value');

        if (filter_name) {
            url += '&filter_name=' + encodeurlComponent(filter_name);
        }

        location = url;
    }
</script>
<?php echo $footer; ?>