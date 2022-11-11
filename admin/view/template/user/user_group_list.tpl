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
      <h1><img src="view/image/user-group.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
			  <td class="left">SIP очередь</td>
			   <td class="left">Bitrix24 id</td>
			  <td class="left">Очередь уведомлений</td>
			  <td class="left">Префикс шаблона</td>
			  <td class="left">Постановка задач</td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($user_groups) { ?>
            <?php foreach ($user_groups as $user_group) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($user_group['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" />
                <?php } ?></td>
			
              <td class="left"><?php echo $user_group['name']; ?></td>
			    <td class="left">
					<? if ($user_group['sip_queue']) { ?>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user_group['sip_queue']; ?></span>
					<? } ?>
			  </td>
			  <td class="left">
					<? if ($user_group['bitrix_id']) { ?>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user_group['bitrix_id']; ?></span>
					<? } ?>
			  </td>
			   <td class="left">
					<? if ($user_group['alert_namespace']) { ?>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user_group['alert_namespace']; ?></span>
					<? } ?>
			  </td>
			   <td class="left">
					<? if ($user_group['template_prefix']) { ?>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $user_group['template_prefix']; ?></span>
					<? } ?>
			  </td>
			  <td class="left">
				<? if ($user_group['ticket']) { ?>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
				<? } else { ?>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
				<? } ?>
				</td>
              <td class="right"><?php foreach ($user_group['action'] as $action) { ?>
                <a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 