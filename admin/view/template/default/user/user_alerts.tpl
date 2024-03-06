<?php echo $header; ?>
<div id="content">
<style>
	table.list .tr_warning td {background-color:#FFEAA8; color:#826200}
	table.list .tr_success td {background-color:#BCF5BC; color:darkgreen}
	table.list .tr_information td {background-color:#78C5E7; color:#FFF}
	table.list .tr_error td {background-color:#FF8181; color:#FFF}
</style>
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
    <div class="heading">
      <h1><img src="view/image/user-group.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">    
        <table class="list">
          <thead>
            <tr>  
              <td class="left"></td>
              <td class="right">Время</td>
			  <td class="center">Тип</td>
			  <td class="center">Текст</td>
			  <td class="center">Объект</td>
			  <td class="center">Ссылка на объект</td>
            </tr>
          </thead>		  
          <tbody>
            <?php if ($user_alerts) { ?>
            <?php foreach ($user_alerts as $user_alert) { ?>
            <tr class="tr_<?php echo $user_alert['type']; ?>">
              <td class="left" style="width:1px;"><?php echo $user_alert['alertlog_id']; ?></td>
			  
			  <td class="right" style="width:1px; font-size:10px;"><?php echo $user_alert['datetime']; ?></td>
			  
              <td class="right" style="width:1px;"><?php echo $user_alert['type']; ?></td>
			  
			  <td class="center"><?php echo $user_alert['text']; ?></td>
			  
			  <td class="center" style=""><?php echo $user_alert['entity_type']; ?></td>
	
			  <td class="center" style="">
				<? if ($user_alert['entity_id'] && $user_alert['url']) { ?>
					<a class="button" href="<? echo $user_alert['url']; ?>" ><? echo $user_alert['entity_id']; ?></a>
				<? } ?>
			  
			  </td>	  			  			  			   
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3">Нету уведомлений</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<?php echo $footer; ?> 