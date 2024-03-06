<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/seo.jpg" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <div class="overview" style="width: 100%;">
        <div class="dashboard-heading" style="display: none;"></div>
		<div id="vtab-option" class="vtabs">
		<a href="#tab-prod" /><?php echo $heading_products; ?></a>
		<a href="#tab-categ" /><?php echo $heading_categ; ?></a>		
		<a href="#tab-manuf" /><?php echo $heading_manuf; ?></a>
		<a href="#tab-info" /><?php echo $heading_info; ?></a>
		</div>
	<div id="tab-prod" class="vtabs-content">
        <table class="form">		
			<tbody style="border: 1px solid #003A88;">
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_title; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="category<?php echo $language['language_id']; ?>" name="categories" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="category('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_h1; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="categoryh1<?php echo $language['language_id']; ?>" name="categoriesh1" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="categoryh1('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_kw; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="categorykey<?php echo $language['language_id']; ?>" name="categorieskey" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="categorykey('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_desc; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="categorydesc<?php echo $language['language_id']; ?>" name="categoriesdesc" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="categorydesc('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>	
				<tr>
					<td colspan="3"><?php echo $text_pattern_pro; ?></td>
				</tr>
				<tr>
					<td>SEO-URL</td>
					<td>
						<?php echo $text_seo_rewr; ?><input id="seokeyw" type="checkbox" name="seo_rewrite" >
					</td>
					<td>
						<a onclick="seokeyw();" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="3"><center><span style="color: red;"><?php echo $text_seo_warn; ?></span></center></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="tab-categ" class="vtabs-content">
		<!--categories-->		
		<table class="form">		
			<tbody style="border: 1px solid #003A88;">
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_title; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="product<?php echo $language['language_id']; ?>" name="product" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="product('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_h1; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="producth1<?php echo $language['language_id']; ?>" name="producth1" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="producth1('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_kw; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="productkey<?php echo $language['language_id']; ?>" name="productkey" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="productkey('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_desc; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="productdesc<?php echo $language['language_id']; ?>" name="productdesc" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="productdesc('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>	
				<tr>
					<td colspan="3"><?php echo $text_pattern_cat; ?></td>
				</tr>
				<tr>
					<td>SEO-URL</td>
					<td>
						<?php echo $text_seo_rewr; ?><input id="seokeyp" type="checkbox" name="seo_rewrite" >
					</td>
					<td>
						<a onclick="seokeyp();" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="3"><center><span style="color: red;"><?php echo $text_seo_warn; ?></span></center></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="tab-manuf" class="vtabs-content">
		<table class="form">
			<tbody style="border: 1px solid #003A88;">
				<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_title; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="manuf<?php echo $language['language_id']; ?>" name="manuf" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="manuf('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_h1; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="manufh1<?php echo $language['language_id']; ?>" name="manufh1" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="manufh1('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_kw; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="manufkey<?php echo $language['language_id']; ?>" name="manufkey" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="manufkey('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_desc; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="manufdesc<?php echo $language['language_id']; ?>" name="manufdesc" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="manufdesc('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>	
				<tr>
					<td colspan="3"><?php echo $text_pattern_manuf; ?></td>
				</tr>
				<tr>
					<td>SEO-URL</td>
					<td>
						<?php echo $text_seo_rewr; ?><input id="seomanuf" type="checkbox" name="seo_rewrite" >
					</td>
					<td>
						<a onclick="seomanuf();" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="3"><center><span style="color: red;"><?php echo $text_seo_warn; ?></span></center></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="tab-info" class="vtabs-content">
		<table class="form">		
			<tbody style="border: 1px solid #003A88;">
				<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_title; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="info<?php echo $language['language_id']; ?>" name="info" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="info('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_h1; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="infoh1<?php echo $language['language_id']; ?>" name="infoh1" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="infoh1('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_kw; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="infokey<?php echo $language['language_id']; ?>" name="infokey" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="infokey('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td style="width: 100px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="float: left;" />&nbsp;&nbsp;&nbsp;<?php echo $text_meta_desc; ?></td>
					<td style="padding: 1px 10px 1px 10px;">
						<input style="width: 85%; float: left;" type="text" id="infodesc<?php echo $language['language_id']; ?>" name="infodesc" value="">
					</td>
					<td style="padding: 1px 10px 1px 10px;">
						<a onclick="infodesc('<?php echo $language['language_id']; ?>');" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
			<?php } ?>	
				<tr>
					<td colspan="3"><?php echo $text_pattern_info; ?></td>
				</tr>
				<tr>
					<td>SEO-URL</td>
					<td>
						<?php echo $text_seo_rewr; ?><input id="seoinfo" type="checkbox" name="seo_rewrite" >
					</td>
					<td>
						<a onclick="seoinfo();" class="button"><?php echo $text_generate; ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="3"><center><span style="color: red;"><?php echo $text_seo_warn; ?></span></center></td>
				</tr>
			</tbody>
		</table>
	</div>
		<!--end_categories-->
       <!--/div-->
      </div>     
    </div>
  </div>
</div>
<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]--> 
<script type="text/javascript"><!--
function seomanuf() {
	var keyw = $("#seomanuf").is(':checked') ? 1 : 0;
	console.log(keyw)
	$.ajax({
		url: 'index.php?route=common/seogen/seomanuf&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'keyw_id=' + keyw,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function manuf(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/manuf&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'manuf_id=' + $("#manuf" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#manuf" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function manufh1(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/manuf&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'manufh1_id=' + $("#manufh1" + lang).attr('value')  + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#manufh1" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function manufkey(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/manuf&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'manufkey_id=' + $("#manufkey" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#manufkey" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function manufdesc(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/manuf&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'manufdesc_id=' + $("#manufdesc" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#manufdesc" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
//info
function seoinfo() {
	var keyw = $("#seoinfo").is(':checked') ? 1 : 0;
	console.log(keyw)
	$.ajax({
		url: 'index.php?route=common/seogen/seoinfo&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'keyw_id=' + keyw,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				//$("#category").attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function info(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/info&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'info_id=' + $("#info" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#info" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function infoh1(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/info&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'infoh1_id=' + $("#infoh1" + lang).attr('value')  + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#infoh1" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function infokey(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/info&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'infokey_id=' + $("#infokey" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#infokey" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function infodesc(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/info&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'infodesc_id=' + $("#infodesc" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#infodesc" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};

//prod
function category(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/cat&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'cat_id=' + $("#category" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#category" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function categoryh1(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/cat&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'cath1_id=' + $("#categoryh1" + lang).attr('value')  + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#categoryh1" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function categorykey(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/cat&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'catkey_id=' + $("#categorykey" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#categorykey" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function categorydesc(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/cat&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'catdesc_id=' + $("#categorydesc" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#categorydesc" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function seokeyw() {
	var keyw = $("#seokeyw").is(':checked') ? 1 : 0;
	console.log(keyw)
	$.ajax({
		url: 'index.php?route=common/seogen/keyw&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'keyw_id=' + keyw,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				//$("#category").attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
//categ
function product(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/pro&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'pro_id=' + $("#product" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#product" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function producth1(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/pro&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'proh1_id=' + $("#producth1" + lang).attr('value')  + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#producth1" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function productkey(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/pro&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'prokey_id=' + $("#productkey" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#productkey" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function productdesc(lang) {
	$.ajax({
		url: 'index.php?route=common/seogen/pro&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'prodesc_id=' + $("#productdesc" + lang).attr('value') + '&lang=' + lang,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				$("#productdesc" + lang).attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
function seokeyp() {
	var keyw = $("#seokeyp").is(':checked') ? 1 : 0;
	console.log(keyw)
	$.ajax({
		url: 'index.php?route=common/seogen/keyp&token=<?php echo $token; ?>',//		
		type: 'post',
		data: 'keyp_id=' + keyw,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning').remove();
			if (json['success']) {
				//$("#category").attr('value', '');
				$('.dashboard-heading').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow').delay(1500).fadeOut('fast');				
			}
			
		}
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#vtab-option a').tabs();
//--></script>
<?php echo $footer; ?>