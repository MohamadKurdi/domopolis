<?php echo $header; ?>
<style>
    #menu {
        z-index: 999999;
    }

    .box>.content {
        padding: 15px;
    }

    .box>.heading {
        height: 38px;
        line-height: 35px;
    }

    .form-group {
        padding-top: 15px;
    }

    .fix_form_group {
        padding: 0 15px !important;
        vertical-align: top !important;
    }

    .fix_form_group .form-group .control-label {
        line-height: 26px;
    }

    .new_code_form {
        padding-top: 0;
        padding-bottom: 0;
    }

    .label-code {
        height: 470px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .group_column_flex {
        display: flex;
        flex-wrap: wrap;
    }

    .group_column {
        width: 50%;
        padding: 0 15px;
        display: flex;
        position: relative;
    }

    .group_column .control-label {
        min-width: 75px;
        padding-right: 15px;
        padding-left: 15px;
        border-left: 1px solid #e2e2e2;
    }

    .alert_route {
        position: absolute;
        top: 100%;
        z-index: 999;
        right: 15px;
        left: 15px;
    }

    .alert_route blockquote {
        border-left: none;
    }

    .js_pimur_help {
        position: relative;
    }

    .js_pimur_help .popover.left {
        left: -281px;
        top: -20px;
    }

    .js_pimur_help .popover.left>.arrow {
        top: 43px;
    }
</style>
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
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="pull-right btn-group-sm">
                <a onclick="$('#form-suggest_help').submit();" class="btn btn-success"><?php echo $button_save; ?></a>
                <a href="<?php echo $cancel; ?>" class="btn btn-danger"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-suggest_help"
                class="form-horizontal">
                <div class="row">
                    <div class="col-sm-10 col-sm-push-2">

                        <div class="form-inline alert alert-info">
                            <div class="pull-right js_pimur_help">
                                <button type="button" class="btn btn-default btn-sm" id="js_pimur_help">Заметка об API
                                    <i class="fa fa-question-circle" aria-hidden="true"></i></button>
                                <div class="popover fade left in">
                                    <div class="arrow"></div>
                                    <div class="popover-inner">
                                        <h3 class="popover-title">Заметка об API</h3>
                                        <div class="popover-content">
                                            <p>
                                                <ul class="list-group list_popover">
                                                    <li class="list-group-item small">
                                                        После регистрации на сайте Dadata, обязательно подтвердите почту
                                                        указанную Вами при регистрации. Иначе Api ключ не будет
                                                        работать.
                                                    </li>
                                                    <li class="list-group-item small">
                                                        В поле <b>Ключ</b> надо вставлять <b>API-ключ</b>, а не
                                                        Секретный
                                                        ключ для стандартизации
                                                    </li>
                                                    <li class="list-group-item small">
                                                        Привязка к доменам
                                                        <ins>делать не нужно</ins>
                                                        . Иначе работать не будет.
                                                    </li>
                                                    <li class="list-group-item small">
                                                        Больше информации по api - <a
                                                            href="https://dadata.userecho.com/knowledge-bases/4-baza-znanij/categories/7-podskazki/articles"
                                                            target="blank">База знаний Dadata</a>
                                                    </li>
                                                </ul>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="input-ke">Ключ</label>
                                <input type="text" name="suggest_help_key" value="<?php echo $suggest_help_key; ?>"
                                    id="input-key" placeholder="Значение" class="form-control" style="width: 280px;" />
                            </div>

                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="suggest_help_status" id="input-status" class="form-control">
                                    <?php if ($suggest_help_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-2 col-sm-pull-10">
                        <div class="alert alert-warning"><a href="https://dadata.ru/suggestions/" target="blank"
                                class="btn btn-default btn-block btn-sm" id="js_prev_btn"><?php echo $entry_key; ?></a>
                        </div>
                    </div>

                    <div class="clearfix"><br><br><br></div>
                </div>

                <table id="route" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="text-left" style="width: 180px;">Описание:</td>
                            <td class="text-left">Значение:</td>
                            <td style="width: 100px;">Действие:</td>
                        </tr>
                    </thead>

                    <tbody id="js_td_body">
                        <?php $route_row = 0; ?>
                        <?php foreach($form_code as $code) { ?>

                        <tr id="route-row<?php echo $route_row; ?>">

                            <td class="text-left fix_form_group">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Route</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label label-code">Create object</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label label-call">Object Call</label>
                                </div>
                            </td>

                            <td class="text-left fix_form_group">
                                <div class="form-group group_column_flex">

                                    <div class="group_column" id="js_route_row_<?php echo $route_row; ?>">
                                        <input type="text" name="suggest_help_module[<?php echo $route_row; ?>][route]"
                                            data-name="route" value="<?php echo $code['route']; ?>"
                                            class="form-control js_code__name_<?php echo $route_row; ?>">

                                    </div>
                                    <input type="hidden" name="suggest_help_module[<?php echo $route_row; ?>][position]"
                                        value="content_top" data-name="position"
                                        class="js_code__name_<?php echo $route_row; ?>">
                                    <input type="hidden" name="suggest_help_module[<?php echo $route_row; ?>][status]"
                                        value="1" data-name="status" class="js_code__name_<?php echo $route_row; ?>">
                                    <div class="group_column">
                                        <label class="control-label">Layout</label>
                                        <select name="suggest_help_module[<?php echo $route_row; ?>][layout_id]"
                                            data-name="layout_id" data-layout="<?php echo $code['layout_id']; ?>"
                                            class="form-control js_select js_code__name_<?php echo $route_row; ?>"
                                            onblur="writeName(this.value, <?php echo $route_row; ?>);"></select>
                                    </div>

                                </div>


                                <div class="form-group new_code_form">
                                    <textarea name="suggest_help_module[<?php echo $route_row; ?>][data]"
                                        data-name="data"
                                        class="js_code_new js_code__name_<?php echo $route_row; ?>"><?php echo $code['data']; ?></textarea>
                                </div>
                                <div class="form-group new_code_form">
                                    <textarea name="suggest_help_module[<?php echo $route_row; ?>][init]"
                                        data-name="init"
                                        class="js_code_new js_code__name_<?php echo $route_row; ?>"><?php echo $code['init']; ?></textarea>
                                </div>
                            </td>
                            <td class="text-left">
                                <button type="button" onclick="$('#route-row<?php echo $route_row; ?>').remove()"
                                    data-toggle="tooltip" title="Удалить" class="btn btn-danger">&#45;
                                </button>
                            </td>
                        </tr>
                        <?php $route_row++; ?>
                        <?php } ?>

                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-left">
                                <button type="button"
                                    onclick="addRoute('#js_td_body', $('#js_td_body').children('tr').length);"
                                    data-toggle="tooltip" title="Добавить" class="btn btn-primary">&#43;
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>

            <blockquote>
                <p>Route vs Layout:</p>
                <p class="small"><b>Layout</b> - она же схема, на которую добавляют модули.</p>
                <p class="small"><b>Route</b> - представляет из себя метод контроллера, который нужно выполнить.</p>
                <p class="small"><b>Layout и Route</b> - Добавляем схему, указываем route. Подсказка показывается при
                    выборе схемы.</p>
                <p class="small"><b>Пример</b> - На корзину симплы, я бы рекомендовал добавить полный route.
                    <b>checkout/%</b>
                    - короткий, будет действовать на все корзины. <b>checkout/simplecheckout</b> - полный, будет
                    действовать только на этот контроллер.</p>
                <p>Код для модуля <b>Simple</b>:</p>
                <p class="small">Возьмите из поля Object Call, код вызова и вставьте в модуле Simple в разделе
                    Javascript, в любое место.</p>
                <p class="small">Так же в модуле Simple указать <b>для поля «Страна»</b> значение по умолчанию:
                    <b>Российская
                        Федерация</b>, но это не обязательно. Модуль проверяет на соответствие стране. Если у
                    пользователя будет не РФ, то модуль просто не запуститься для данного пользователя. Пока
                    пользователь не сменит на страну РФ.</p>
                <p>Модификация кода:</p>
                <p class="small">По умолчанию, при добавлении новой формы - я заранее подготовил распространенный
                    вариант создания Объекта - <b>Create object</b>. Если у вас свои селекторы отличающиеся от данных.
                    То смените их на свои.</p>
                <p class="small">Что такое селектор и как ими пользоваться <a
                        href="https://learn.javascript.ru/css-selectors" target="blank">css-selectors</a>.</p>
                <p>Возможности:</p>
                <p class="small">Объект Javascript постарался сделать универсальным. Это означает что можно повесить и
                    настроить вызов не только на корзину Simple, но и на страницу регистрации, и любые другие формы.</p>
            </blockquote>

            <p class="text-right">
                <small><?php echo $text_copyright; ?></small>
            </p>
        </div>
    </div>
</div>
<script id="js_layots" type="text/obj">
    <option value="">---Выберите схему---</option>
    <?php foreach($layouts as $layout) { ?>
    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
    <?php } ?>
</script>
<script id="js_new_td" type="text/obj">
    <tr id="route-row{{route_row}}">
        <td class="text-left fix_form_group">
        <div class="form-group">
        <label class="col-sm-12 control-label">Route</label>
        </div>
        <div class="form-group">
        <label class="col-sm-12 control-label label-code">Create object</label>
        </div>
        <div class="form-group">
        <label class="col-sm-12 control-label label-call">Object Call</label>
    </div>
    </td>
    <td class="text-left fix_form_group">
        <div class="form-group group_column_flex">

        <div class="group_column" id="js_route_row_{{route_row}}">
        <input type="text" name=""  data-name="route" class="form-control js_code__name_{{route_row}}">
        </div>

        <input type="hidden" name="" value="content_top" data-name="position" class="js_code__name_{{route_row}}">
        <input type="hidden" name="" value="0" data-name="status" class="js_code__name_{{route_row}}">

        <div class="group_column">
        <label class="control-label">Layout</label>
        <select name="" data-name="layout_id" class="form-control js_code__name_{{route_row}}
    " onblur="writeName(this.value, {{route_row}});">{{layouts}}</select>
        </div>

        </div>

        <div class="form-group new_code_form">
        <textarea name="" data-name="data" class="js_code_new js_code__name_{{route_row}}"></textarea>
        </div>
        <div class="form-group new_code_form">
        <textarea name="" data-name="init" class="js_code_new js_code__name_{{route_row}}">new UserDadata(ChangeState);</textarea>
    </div>
    </td>
    <td class="text-left">
        <button type="button" onclick="$('#route-row{{route_row}}').remove()"
    data-toggle="tooltip" title="Удалить"
    class="btn btn-danger">&#45;</button>
    </td>
    </tr>
</script>
<script id="js_default_obj" type="text/obj">
const ChangeState = {
    country: ['#shipping_address_country_id', 176],
    email: '#customer_email',
    name: '#shipping_address_firstname',
    surname: '#shipping_address_lastname',
    region: '#shipping_address_zone_id',
    city: '#shipping_address_city',
    address: '#shipping_address_address_1',
    postal_code: '#shipping_address_postcode',

    make_keys: {
        email: {
            email: 'value'
        },
        fio: {
            name: ['name', 'patronymic'],
            surname: ['surname']
        },
        address: {
            region: ['region'],
            city: ['city', 'settlement_with_type', 'area_with_type'],
            address: ['city_district_with_type', 'street_with_type', ['house_type', 'house'], ['block_type', 'block'], ['flat_type', 'flat']],
            postal_code: ['postal_code'],
        }
    },
    input: {
        email: ['tr', {className: 'suggest_tr'}, ' \
            <td colspan="2"> \
                <div class ="form-group jumbotron"> \
                    <label for="js_suggest_email" class="control-label h4">Email - Автозаполнение:</label> \
                    <input type="email" id="js_suggest_email" class="form-control" placeholder="Работает автозаполнение Email"> \
                    <div class="js_suggest" hidden> \
                        <ul class="js_suggest_list"></ul> \
                        <button type="button" class="btn btn-light">&#10005;</button> \
                        <div class="small">Выберите вариант или продолжите ввод</div> \
                    </div> \
                </div> \
            </td> \
        '],
        fio: ['tr', {className: 'suggest_tr'}, ' \
            <td colspan="2"> \
                <div class ="form-group jumbotron"> \
                    <label for="js_suggest_fio" class="control-label h4">Ф.И.О - Автозаполнение:</label> \
                    <input type="text" id="js_suggest_fio" class="form-control" placeholder="Работает автозаполнение Ф.И.О"> \
                    <div class="js_suggest" hidden> \
                        <ul class="js_suggest_list"></ul> \
                        <button type="button" class="btn btn-light">&#10005;</button> \
                        <div class="small">Выберите вариант или продолжите ввод</div> \
                    </div> \
                </div> \
            </td> \
        '],
        address: ['tr', {className: 'suggest_tr'}, ' \
            <td colspan="2"> \
                <div class ="form-group jumbotron"> \
                    <label for="js_suggest_address" class="control-label h4">Адрес - Автозаполнение:</label> \
                    <textarea id="js_suggest_address" class="form-control" placeholder="Работает автозаполнение по адресу" rows="3"></textarea> \
                    <div class="js_suggest" hidden> \
                        <ul class="js_suggest_list"></ul> \
                        <button type="button" class="btn btn-light">&#10005;</button> \
                        <div class="small">Выберите вариант или продолжите ввод</div> \
                    </div> \
                </div> \
            </td> \
        '],
    },
    reload: {
        fio: ['surname', 'name'],
        address: ['address'],
    },
    callback: [reloadAll],
    insert: {
        before: [
            ['#simplecheckout_customer .row-customer_email', 'email'],
            ['#simplecheckout_shipping_address .row-shipping_address_firstname', 'fio'],
            ['#simplecheckout_shipping_address .row-shipping_address_country_id', 'address']
        ]
    }
};
</script>
<script>
    var defaultObj = document.getElementById('js_default_obj').innerHTML;
    var defaultLayots = document.getElementById('js_layots').innerHTML;

    $('.js_select').each(function (i, el) {
        $(el).html(defaultLayots).val($(el).data('layout'));
    });

    initEditors('.js_code_new');

    function addRoute(selector, i) {
        var newTd = renderTemplateTable('js_new_td', {
            route_row: i,
            layouts: defaultLayots
        });
        $(selector).append(newTd);
        initEditors('.js_code_new');
    }

    function renderTemplateTable(name, data) {
        var template = document.getElementById(name).innerHTML;

        for (var property in data) {
            if (data.hasOwnProperty(property)) {
                var search = new RegExp('{{' + property + '}}', 'g');
                template = template.replace(search, data[property]);
            }
        }
        return template;
    }

    function initEditors(selector) {
        var formCode = $(selector);
        $(formCode).each(function (i, el) {
            var h;
            if (i % 2 == 0) {
                h = 500;
            } else {
                h = 100;
            }
            if (!$(el).val()) {
                $(el).val(defaultObj)
            }
            editors(el, h);
            $(el).removeClass(selector.replace(/[.#]/gi, '')).addClass('js_code')
        });
    }

    function editors(el, h) {
        CodeMirror.fromTextArea(el, {
            styleActiveLine: true,
            matchBrackets: true,
            mode: 'javascript',
            theme: 'material',
            mode: { name: "javascript", json: true },
            theme: "material",
            lineNumbers: true,
            readOnly: false,
        }).setSize('auto', h);
    }

    function writeName(_value, num) {
        // console.log(_value)
        $('.js_code__name_' + num).each(function (i, el) {
            if (!_value) {
                $(el).removeAttr('name').val('');
                return;
            }
            console.log(el.type)
            if (el.type == 'select-one') {
                getLayout('#js_route_row_' + num, _value);
            }

            var name = $(el).data('name');

            name = 'suggest_help_module[' + num + '][' + name + ']';

            if (el.type == 'hidden') {

                $(el).attr('name', name).val($(el).data('name') == 'status' ? 1 : 'content_top');
                return;
            }


            $(el).attr('name', name);
        })

    }

    function getLayout(selector, id) {
        $.ajax({
            url: 'index.php?route=module/suggest_help/layout_route&token=<?php echo $token; ?>&layout_id=' + id,
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $('.alert_route').remove();
                //$('#button-guest').button('loading');
            },
            success: function (json) {

                if (json.layout_routes) {
                    var list = '';

                    for (var i = 0; i < json.layout_routes.length; i++) {

                        var obj = json.layout_routes[i];

                        list += '<blockquote>';
                        for (var key in obj) {
                            if (key != 'route') continue;
                            list += '<p class="small">' + key + ': {{  ' + obj[key] + '  }}</p>';
                        }
                        list += '</blockquote>';
                    }

                    if (list) {

                        $(selector).append($('<div class="alert alert-info alert_route">').html('<button type="button" onclick="$(this).parent().remove()" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + list));
                    }
                }


                // console.log(json)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    if (!localStorage.getItem('togglepopover')) {
        $('#js_pimur_help').next().show();
    }

    $('#js_pimur_help').on('click', function () {
        if (localStorage.getItem('togglepopover')) {
            localStorage.removeItem('togglepopover');
            $(this).next().show();
        } else {
            localStorage.setItem('togglepopover', 1);
            $(this).next().hide();
        }
    })


</script>
<?php echo $footer; ?>