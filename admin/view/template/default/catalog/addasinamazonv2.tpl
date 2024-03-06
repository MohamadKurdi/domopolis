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
                <a class="button" onclick="clear_cat();"><i class="fa fa-eraser"></i> Очистить категорию</a>
                <a class="button" onclick="reload_rules();"><i class="fa fa-refresh"></i> Перезагрузить правила</a>
                <?php if (!$this->config->get('config_rainforest_disable_add_all_button')) { ?>
                    <a class="button" onclick="add_all();"><i class="fa fa-plus"></i> Добавить все</a>  
                <?php } ?>              
                <a class="button" onclick="reload();"><i class="fa fa-refresh"></i> Загрузить / Обновить</a>
            </div>     
        </div>
        <div class="content">        
            <div class="clr"></div>
            <div style="border:1px dashed #cf4a61; border-radius: 5px; padding:10px; margin-bottom:10px;">
                <table style="width:100%;">
                    <tr>
                        <td class="left" style="width:60%">
                            <div>
                                <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Категория магазина</p><br />
                                <input type="text" name="category_shop" style="width:80%" placeholder="Начни заполнять, чтоб выбрать категорию" />
                                <input type="number" step="1" name="category_id" style="width:10%" value="0" size="5" />                           
                                <span class="help" style="display:inline-block;">при выборе будут загружены наборы правил поиска, привязанные к категории</span>
                                <span class="help" style="display:inline-block; border-bottom:1px dashed #7F00FF; cursor: pointer; color:#7F00FF" onclick="open_category();">открыть редактор правил</span>
                            </div>
                        </td>
                        <td style="width:10%">
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Пользователь</p><br />
                            <div>
                                <select name="user_id">
                                    <option value="0">Пользователь</option>
                                    <? foreach ($users as $user) { ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $this->user->getId()) { ?>selected="selected"<? } ?>><?php echo $user['user']; ?></option>
                                    <? } ?>   
                                </select><br />
                                <span class="help" style="display:inline-block;">автор правил</span>
                            </div>
                        </td>
                        <td style="width:10%">
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Авто-правила</p><br />
                            <div>
                                <select name="filter_auto">                                    
                                    <option value="0" selected="selected">Нет</option>
                                    <option value="1">Да</option>
                                </select><br />
                                <span class="help" style="display:inline-block;">добавить авто</span>
                            </div>
                        </td>
                         <td style="width:10%">
                            <p class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">По брендам</p><br />
                            <div>
                                <select name="brand_logic">                                    
                                    <option value="0" selected="selected">Нет</option>
                                    <option value="1">Да</option>
                                </select><br />
                                <span class="help" style="display:inline-block;">временные категории</span>
                            </div>
                        </td>
                    </tr>
            </table>
        </div> 

        <div id="rules" style="padding:10px; border:1px dashed #00AD07;  border-radius: 5px;  margin-bottom:10px;"></div>           

        <div id="result"></div>
    </div>
</div>
</div>  
   
<?php if (!$this->config->get('config_rainforest_disable_add_all_button')) { ?> 
    <script type="text/javascript">
        function add_all(){
            $( ".products-container-product-add" ).each(function( index ) {
                $(this).trigger('click');
            });
        }
    </script>
<?php } ?>

<script type="text/javascript">
    function fade(asin){
        $('#' + asin + '-wrap').fadeOut(300, function(){ $('#' + asin + '-wrap').remove(); });
    }

    function reload_counters(category_word_id){
        $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: 'index.php?route=kp/amazon/counters&token=<?php echo $token; ?>',
                data: {
                    category_word_id:   category_word_id      
                },
                beforeSend: function(){
                    $('#small-category-word-pages-parsed-' + category_word_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#small-category-word-total-pages-' + category_word_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#small-category-word-total-products-' + category_word_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#small-category-word-products-added-' + category_word_id).html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(json){
                    $('#small-category-word-pages-parsed-' + category_word_id).html(json.category_word_pages_parsed);
                    $('#small-category-word-total-pages-' + category_word_id).html(json.category_word_total_pages);
                    $('#small-category-word-total-products-' + category_word_id).html(json.category_word_total_products);
                    $('#small-category-word-products-added-' + category_word_id).html(json.category_word_product_added);
                },                
                error: function(json){                    
                }
        });

    }

    function add(asin, category_word_id){
        var category_id = $('input[name=category_id]').val();
        var brand_logic = $('select[name=brand_logic]').children("option:selected").val();

        if (asin){
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'index.php?route=kp/amazon/add&token=<?php echo $token; ?>',
                data: {
                    asin:               asin,
                    category_id:        category_id,
                    brand_logic:        brand_logic,
                    category_word_id:   category_word_id      
                },
                beforeSend: function(){
                    $('#' + asin + '-add-status').html('<i class="fa fa-spinner fa-spin"></i> Добавляю');
                },
                success: function(html){
                    $('#' + asin + '-add-status').html('<i class="fa fa-check-circle"></i> В очереди');
                    $('#' + asin + '-wrap').addClass('grey');
                    window.setTimeout( fade(asin) , 3000 );             
                },             
                complete: function(){
                    reload_counters(category_word_id);
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
    function open_category(category_id){
        if (parseInt($('input[name=category_id]').val()) > 0){
            window.open('index.php?route=catalog/category/update&token=<?php echo $token; ?>&category_id=' +  $('input[name=category_id]').val());
        } else {
            swal({ title: "Выбери категорию", text: "Шо открыть? Не выбрана категория!", type: "error"}, function() {  });
        }        
    }

    function reload_rules(){
        $.ajax({
            url: 'index.php?route=kp/amazon/load_category_search_words&token=<?php echo $token; ?>',
            data: {
                category_id: $('input[name=category_id]').val(),
                user_id: $('select[name=user_id]').val(),
                filter_auto: $('select[name=filter_auto]').val(),
            },
            dataType: 'html',
            success: function(html) {
                $('#rules').html(html);
            },
            complete: function(){

            }
        });
    }

    function clear_cat(){                                    
        $('input[name=category_shop]').val('');
        $('input[name=category_id]').val('0');
        reload_rules();
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

    function reload(){
        let catUrl;
        async function getCat() {
            const response = await fetch('index.php?route=common/home/cat&token=<?php echo $token; ?>');
            catUrl = await response.text();
        }

        getCat().then(() => { 
           var counterhtml = '<div style="text-align:center; padding-top:10px; color:#00ad07">';
           counterhtml += catUrl;
           counterhtml += '<br />';
           counterhtml += '<span id="milliseconds-counter" style="font-size:24px;"></span>';
           counterhtml += '<br /><br />';        
           counterhtml += '<span style="color:#cf4a61"><i class="fa fa-lock"></i> Все поля ввода заблокированы на время запроса</span>';
           counterhtml += '<br /><br />';
           counterhtml += '<span id="text-counter" style="font-size:14px;"></span>';
           counterhtml +='</div>';

           $.ajax({
            url: 'index.php?route=kp/amazon/getCategoryWordSearchResults&token=<?php echo $token; ?>',
            type: 'GET',
            dataType: 'html',
            data: $('form#category_search_words_form').serialize(),
            beforeSend: function(){
                $('#result').html(counterhtml);

                window.msc = 0;
                window.intrvl = setInterval(iM, 10);
                window.intrvl2 = setInterval(iS, 2000);
            },
            success: function(html){                                        
               $('#result').html(html);

               clearInterval(window.intrvl);
               clearInterval(window.intrvl2);
           }
       });     
       }); 
    }           
</script>

<script type="text/javascript">
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

            reload_rules();

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });
</script> 
<?php echo $footer; ?>