<div id="tab-related-data">
    <table class="form">
        <tr>
            <td style="width:33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Набор обязательных характеристик</span>
                    <span class='help'>
                        <i class="fa fa-info-circle"></i> Для управления фильтрами
					</span>
                </p>
                <input type="text" name="attributes_required_category_autocomplete" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="attribute-required-category" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($attributes_required_category as $attribute_required_category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="attribute-required-category<?php echo $attribute_required_category['attribute_id']; ?>" class="<?php echo $class; ?>"><?php echo $attribute_required_category['name']; ?>
                        <img src="view/image/delete.png" alt=""/>
                        <input type="hidden" name="attributes_required_category[]" value="<?php echo $attribute_required_category['attribute_id']; ?>"/>
                    </div>
                    <?php } ?>
                </div>
            </td>

            <td style="width:33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Акции (сущность: actions)</span>
                    <span class='help'>
						 <i class="fa fa-info-circle"></i> Выводит эти акции на странице категории
					</span>
                </p>
                <input type="text" name="action" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="category-action" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($category_actions as $category_action) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="category-action<?php echo $category_action['action_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_action['name']; ?><img src="view/image/delete.png" alt="" />
                        <input type="hidden" name="category_action[]" value="<?php echo $category_action['action_id']; ?>" />
                    </div>
                    <?php } ?>
                </div>
            </td>

            <td style="width: 33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Фильтра (сущность: filter)</span>
                    <span class='help' style="color:darkred">
						 <i class="fa fa-info-circle"></i> Имплементация штатной логики движка, не используется
					</span>
                </p>
                <input type="text" name="filter" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="category-filter" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($category_filters as $category_filter) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="category-filter<?php echo $category_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_filter['name']; ?>
                        <img src="view/image/delete.png" alt=""/>
                        <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>"/>
                    </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>

    <table class="form">
        <tr>
            <td style="width:33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Связанные категории (сущность: categories)</span>
                    <span class='help'>
						 <i class="fa fa-info-circle"></i> Для автоподбора аксессуаров / связанных товаров, если они не заданы явно
					</span>
                </p>
                <input type="text" name="related_category_autocomplete" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="related-category" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($related_categories as $related_category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="related-category<?php echo $related_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $related_category['name']; ?>
                        <img src="view/image/delete.png" alt=""/>
                        <input type="hidden" name="related_category[]" value="<?php echo $related_category['category_id']; ?>"/>
                    </div>
                    <?php } ?>
                </div>
            </td>

            <td style="width:33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Набор характеристик для подбора (сущность: attributes)</span>
                    <span class='help'>
						 <i class="fa fa-info-circle"></i> Подбор аксессуаров / дополнений происходит с учетом равенства значений этих характеристик
					</span>
                </p>
                <input type="text" name="attributes_similar_category_autocomplete" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="attribute-similar-category" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($attributes_similar_category as $attribute_similar_category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="attribute-similar-category<?php echo $attribute_similar_category['attribute_id']; ?>" class="<?php echo $class; ?>"><?php echo $attribute_similar_category['name']; ?>
                        <img src="view/image/delete.png" alt=""/>
                        <input type="hidden" name="attributes_similar_category[]" value="<?php echo $attribute_similar_category['attribute_id']; ?>"/>
                    </div>
                    <?php } ?>
                </div>
            </td>

            <td style="width:33%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Набор равных характеристик для подбора (сущность: attributes)</span>
                    <span class='help'>
						 <i class="fa fa-info-circle"></i> Подбор похожих товаров или замен в форме заказа с учетом равенства значений этих характеристик
					</span>
                </p>
                <input type="text" name="attributes_category_autocomplete" value="" style="width:90%" placeholder="Начни вводить что-то для поиска"/><br/><br/>
                <div id="attribute-category" class="scrollbox" style="min-height: 300px;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($attributes_category as $attribute_category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="attribute-category<?php echo $attribute_category['attribute_id']; ?>" class="<?php echo $class; ?>"><?php echo $attribute_category['name']; ?>
                        <img src="view/image/delete.png" alt=""/>
                        <input type="hidden" name="attributes_category[]" value="<?php echo $attribute_category['attribute_id']; ?>"/>
                    </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $('input[name=\'attributes_required_category_autocomplete\']').autocomplete({
        delay: 100,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.attribute_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('#attribute-required-category' + ui.item.value).remove();

            $('#attribute-required-category').append('<div id="attribute-required-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="attributes_required_category[]" value="' + ui.item.value + '" /></div>');

            $('#attribute-required-category div:odd').attr('class', 'odd');
            $('#attribute-required-category div:even').attr('class', 'even');

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });

    $('#attribute-required-category div img').live('click', function() {
        $(this).parent().remove();

        $('#attribute-required-category div:odd').attr('class', 'odd');
        $('#attribute-required-category div:even').attr('class', 'even');
    });
</script>

<script type="text/javascript">
    $('input[name=\'attributes_similar_category_autocomplete\']').autocomplete({
        delay: 100,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.attribute_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('#attribute-similar-category' + ui.item.value).remove();

            $('#attribute-similar-category').append('<div id="attribute-similar-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="attributes_similar_category[]" value="' + ui.item.value + '" /></div>');

            $('#attribute-similar-category div:odd').attr('class', 'odd');
            $('#attribute-similar-category div:even').attr('class', 'even');

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });

    $('#attribute-similar-category div img').live('click', function() {
        $(this).parent().remove();

        $('#attribute-similar-category div:odd').attr('class', 'odd');
        $('#attribute-similar-category div:even').attr('class', 'even');
    });
</script>

<script type="text/javascript">
    $('input[name=\'attributes_category_autocomplete\']').autocomplete({
        delay: 100,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.attribute_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('#attribute-category' + ui.item.value).remove();

            $('#attribute-category').append('<div id="attribute-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="attributes_category[]" value="' + ui.item.value + '" /></div>');

            $('#attribute-category div:odd').attr('class', 'odd');
            $('#attribute-category div:even').attr('class', 'even');

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });

    $('#attribute-category div img').live('click', function() {
        $(this).parent().remove();

        $('#attribute-category div:odd').attr('class', 'odd');
        $('#attribute-category div:even').attr('class', 'even');
    });
</script>

<script type="text/javascript">
    $('input[name=\'action\']').autocomplete({
        delay: 100,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/actions/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.action_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('#category-action' + ui.item.value).remove();

            $('#category-action').append('<div id="category-action' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_action[]" value="' + ui.item.value + '" /></div>');

            $('#category-action div:odd').attr('class', 'odd');
            $('#category-action div:even').attr('class', 'even');

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });

    $('#category-action div img').live('click', function() {
        $(this).parent().remove();

        $('#category-action div:odd').attr('class', 'odd');
        $('#category-action div:even').attr('class', 'even');
    });
</script>
<script type="text/javascript">
    $('input[name=\'filter\']').autocomplete({
        delay: 500,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item.name,
                            value: item.filter_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('#category-filter' + ui.item.value).remove();

            $('#category-filter').append('<div id="category-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_filter[]" value="' + ui.item.value + '" /></div>');

            $('#category-filter div:odd').attr('class', 'odd');
            $('#category-filter div:even').attr('class', 'even');

            return false;
        },
        focus: function (event, ui) {
            return false;
        }
    });

    $('#category-filter div img').live('click', function () {
        $(this).parent().remove();

        $('#category-filter div:odd').attr('class', 'odd');
        $('#category-filter div:even').attr('class', 'even');
    });
</script>
<script>
    $('input[name=\'related_category_autocomplete\']').autocomplete({
        delay: 500,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item.name,
                            value: item.category_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('#related-category' + ui.item.value).remove();

            $('#related-category').append('<div id="related-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="related_category[]" value="' + ui.item.value + '" /></div>');

            $('#related-category div:odd').attr('class', 'odd');
            $('#related-category div:even').attr('class', 'even');

            return false;
        },
        focus: function (event, ui) {
            return false;
        }
    });

    $('#related-category div img').live('click', function () {
        $(this).parent().remove();

        $('#related-category div:odd').attr('class', 'odd');
        $('#related-category div:even').attr('class', 'even');
    });
</script>