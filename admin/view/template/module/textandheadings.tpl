<?php echo $header; ?>
<div id="content">
<?php
// module: Текст и Заголовки!
// version: 0.1 Comercial
// autor: Шуляк Роман   roma78sha@gmail.com   www.opencartforum.com/index.php?app=core&module=search&do=user_activity&search_app=downloads&mid=678008 ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="infoclose"><?php echo $text_infoclose; ?></div>
  
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">

      <form action="<?php echo $action; ?>" method="post" id="form">
	  <div id="tab-module">
        <div id="language" class="htabs" style="height:30px; border-bottom:2px solid #1f4962; padding-left:40px;">
          <?php foreach ($languages as $language) { ?>
            <a onclick='txhDoubleTabs();return false;' href="#tab-language-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
          <?php } ?>
        </div>
        <?php foreach ($languages as $language) { ?>
        <div id="tab-language-<?php echo $language['language_id']; ?>">
		  
		  <div id="directory-<?php echo $language['language_id']; ?>" class="directory_tab htabs">
			<a href="#tab-directory-<?php echo $language['language_id']; ?>-1" class="directory_double directory_double_all all" style="display:inline;"><?php echo $text_common; ?></a>
			<?php $nDir = 1; ?>
			<?php foreach ($languageArrey[$language["directory"]] as $dirname => $directory) { ?>
			  <?php $nDir++; ?>
			  <?php if ($dirname == "module") { ?>
				<a href="#tab-directory-<?php echo $language['language_id']; ?>-<?php echo $nDir; ?>" data-directoryid="<?php echo $language['language_id']; ?>-<?php echo $nDir; ?>" class="start_selected directory_double directory_double_<?php echo $dirname; ?> selected"><?php echo $dirname; ?></a>
			  <?php } else { ?>
				<a href="#tab-directory-<?php echo $language['language_id']; ?>-<?php echo $nDir; ?>" class="directory_double directory_double_<?php echo $dirname; ?>"><?php echo $dirname; ?></a>
			  <?php } ?>
			<?php } ?>
		  </div>

			<div id="tab-directory-<?php echo $language['language_id']; ?>-1" class="directory_double directory_double_all directory-1 directory">
				<?php foreach ($langfileCom[$language['directory']]['var'] as $key0 => $com) { ?>
				  <div onclick='tahRedactCommonFile(this, "<?php echo $language["directory"]; ?>", "<?php echo $langfileCom[$language['directory']]['filename']; ?>", "<?php echo $key0; ?>");'><span class="file_var"><?php echo $key0; ?>&nbsp;=&nbsp;</span><span class="file_set"><xmp class="file_set_xmp"><?php echo $com; ?></xmp></span><span class="ui-icon ui-icon-pencil"></span><input type="text" class="file_input" name="" value='<?php print($com); ?>' size="50"></div>
				<?php } ?>
			</div>
		  
		  <?php $nDir = 1; ?>
		  <?php foreach ($languageArrey[$language["directory"]] as $dirname => $directory) { ?> 
			<?php $nDir++; ?>
			<div id="tab-directory-<?php echo $language['language_id']; ?>-<?php echo $nDir; ?>" class="directory_double_<?php echo $dirname; ?> directory">
			  <div class="accordion">
			  <?php foreach ($directory as $key2 => $file) { ?>
				<h3><?php echo $key2; ?><?php if (isset($file["heading_title"])) { ?>&nbsp;<span>(<?php echo $file["heading_title"]; ?>)</span><?php } ?></h3>
				<div>
				<?php foreach ($file as $key3 => $var) { ?>
				  <div onclick='tahRedact(this, "<?php echo $language["directory"]; ?>", "<?php echo $dirname; ?>", "<?php echo $key2; ?>", "<?php echo $key3; ?>");'><span class="file_var"><?php echo $key3; ?>&nbsp;=&nbsp;</span><span class="file_set"><xmp class="file_set_xmp"><?php echo $var; ?></xmp></span><span class="ui-icon ui-icon-pencil"></span><input type="text" class="file_input" name="" value='<?php print($var); ?>' size="50"></div>
				<?php } ?>
				</div>
			  <?php } ?>
			  </div>
			</div>
		  <?php } ?>
        </div>
        <?php } ?>
      </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!-- 
$('#language a').tabs();
$('div.directory_tab a').tabs();
$("div.accordion").accordion({autoHeight:false});

$(document).ready(function () {
  $("div.directory").hide();
  $("div.directory_tab > a").removeClass('selected');
  t = $("div.htabs > a.start_selected");
  $(t).addClass('selected');
  $(t).each(function(){
	t_id = $(this).attr('data-directoryid');
	$("#tab-directory-"+t_id).show();
  });
  
  // infoclose
  t_link = '<button onclick="$(this).parent(\'div.infoclose\').hide(\'slow\')">X</button>';
  $("div.infoclose").append(t_link);
});
  
  // дополнение к tabs
  function txhDoubleTabs() {
	t = $('a.directory_double.selected').html();
	if(t == "Общее") t = "all";
    $('a.directory_double.directory_double_'+t).addClass('selected');
    $('div.directory_double_'+t).show();
  }

function tahRedact(th, v1, v2, v3, v4){
  // показать выбранный инпут
  $(th).children("span.file_set, span.ui-icon").hide();
  var v = 'var['+v1+']['+v2+']['+v3+']['+v4+']';
  var input = $(th).children("input.file_input");
  var size = input.val().length;
  var text = $(th).find("xmp.file_set_xmp").text();
  input.fadeIn("3000").attr("name", v).attr('size',size+9).attr('value',text);
} 
function tahRedactCommonFile(th, lang, filename, value){
  // показать выбранный инпут
  $(th).children("span.file_set, span.ui-icon").hide();
  var v = 'com['+lang+']['+filename+']['+value+']';
  var input = $(th).children("input.file_input");
  var size = input.val().length;
  var text = $(th).find("xmp.file_set_xmp").text();
  input.fadeIn("3000").attr("name", v).attr('size',size+9).attr('value',text);
}

//--></script> 
<?php echo $footer; ?>