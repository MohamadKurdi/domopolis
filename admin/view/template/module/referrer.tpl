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
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a class="button" id="insert"><span><?php echo $button_insert; ?></span></a>
                <a id="btn-delete" class="button"><span><?php echo $button_delete; ?></span></a>
            </div>
        </div>
        <div class="content">
            <div id="form-add" style="display:none;">
                <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-insert">
                    <table class="form">
                        <tr><td><?php echo $text_name ?>:</td><td><input type="text" name="name" size="60" /></td></tr>
                        <tr><td><?php echo $text_url_mask ?>:</td><td><input type="text" name="url_mask" size="60" /></td></tr>
                        <tr><td><?php echo $text_url_param ?>:</td><td><input type="text" name="url_param" size="60" /></td></tr>
                        <tr><td colspan="2" align="left">
                                <a onclick="$('#form-insert').submit();" class="button"><span><?php echo $button_save; ?></span></a>
                                <a onclick="fnCancel();" class="button"><span><?php echo $button_cancel; ?></span></a>
                                <input type="hidden" name="pattern_id" value="0">
                            </td></tr>
                    </table
                </form>
            </div>
            <!-- FORM -->
            <form action="delete" method="post" id="form"></form>
            <form action="<?php echo $delete ?>" method="post" enctype="multipart/form-data" id="form-list">
                <table class="list">
                    <thead>
                    <tr>
                        <td width="1" style="text-align: center;">
							<input id="oll" class="checkbox" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							<label for="oll"></label>
							</td>
                        <td class="center"><?php if ($sort == 'name') { ?>
                            <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                            <?php } ?></td>
                        <td class="left"><?php if ($sort == 'url_mask') { ?>
                            <a href="<?php echo $sort_url_mask; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_url_mask; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_url_mask; ?>"><?php echo $column_url_mask; ?></a>
                            <?php } ?></td>
                        <td class="left"><?php if ($sort == 'url_param') { ?>
                            <a href="<?php echo $sort_url_param; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_url_param; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_url_param; ?>"><?php echo $column_url_param; ?></a>
                            <?php } ?></td>
                        <td class="right"><?php echo $column_action; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($patterns) { ?>
                    <?php foreach ($patterns as $pattern) { ?>
                    <tr class="tr<?php echo $pattern['pattern_id']; ?>">
                        <td style="text-align: center;"><?php if ($pattern['selected']) { ?>
                            <input id="selected_<?php echo $pattern['pattern_id']; ?>" class="checkbox" type="checkbox" name="selected[]" value="<?php echo $pattern['pattern_id']; ?>" checked="checked" />
                            <label for="selected_<?php echo $pattern['pattern_id']; ?>"></label>
							<?php } else { ?>
                            <input id="selected_<?php echo $pattern['pattern_id']; ?>" class="checkbox" type="checkbox" name="selected[]" value="<?php echo $pattern['pattern_id']; ?>" />
                            <label for="selected_<?php echo $pattern['pattern_id']; ?>"></label>
							<?php } ?></td>
                        <td class="left"><?php echo $pattern['name']; ?></td>
                        <td class="left"><?php echo $pattern['url_mask']; ?></td>
                        <td class="left"><?php echo $pattern['url_param']; ?></td>
                        <td class="right"><a class="button" onclick="itemEdit(<?php echo $pattern['pattern_id']; ?>)"><?php echo $pattern['action_text']; ?></a></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    function itemEdit(pattern_id) {
        $('input[name="name"]').val($('.tr'+pattern_id+' td:eq(1)').text());
        $('input[name="url_mask"]').val($('.tr'+pattern_id+' td:eq(2)').text());
        $('input[name="url_param"]').val($('.tr'+pattern_id+' td:eq(3)').text());
        $('input[name="pattern_id"]').val(pattern_id);
        $('#form-add').show();
        $('input[name="name"]').focus();
        return false;
    }
    function fnCancel() {
        $('#form-add').hide();
        $('input[name="name"]').val('');
        $('input[name="url_mask"]').val('');
        $('input[name="url_param"]').val('');
        $('input[name="pattern_id"]').val('0');
        return false;
    }

    $('#insert').click(function() {
        fnCancel();
        $('#form-add').show();
        return false;
    });

    $(document).ready(function() {
        $('#btn-delete').click(function() {
            if (!confirm('Удаление невозможно отменить! Вы уверены, что хотите это сделать?')) {
                return false;
            } else {
                $('#form-list').submit();
            }
        });
    });
    //--></script>
<?php echo $footer; ?>