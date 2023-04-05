<?php echo $header; ?>


<style type="text/css">
	table.ordermain,table.history{width:100%;border-collapse:collapse;margin-bottom:20px}
	table.orderadress,table.orderproduct{width:48%;border-collapse:collapse;margin-bottom:20px}
	table.ordermain > tbody > tr > th,table.orderadress > tbody > tr > th,table.list > thead > tr > th,table.history > tbody > tr > th,table.form > tbody > tr > th{background: #1f4962;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	table.ordermain > tbody > tr > td{width:25%}
	table.ordermain > tbody > tr > td:nth-child(odd),table.orderadress > tbody > tr > td:nth-child(odd){background:#EFEFEF}
	table.ordermain > tbody > tr > td,table.orderadress > tbody > tr > td{padding:5px;color:#000;border-bottom:1px dotted #CCC}
	.clr{clear:both}
	input[type="text"]{width:70%;}
	.blue_heading{text-align:center; padding:8px 0;cursor:pointer; background: #40a0dd;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="warning">product/special - Акционные предложения в {site_name} {page= - страница № ~page~}</div>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt="" />  <?php echo $heading_title; ?></h1>			
			<div class="buttons"><a onclick="$('#form-metaseo_anypage').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>			
		</div>
		
		<div class="content">
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>"  method="post" enctype="multipart/form-data" id="form-metaseo_anypage">
				<table id="metaseo_anypage" class="list">
					<thead>
						<tr>
							<td class="center" width="15%">Маршрут</td>
							<td class="center" width="30%">Мета тайтл</td>
							<td class="center" width="40%">Мета дескрипшн</td>
							<td class="center" width="5%"></td>
						</tr>
					</thead>
					<tbody>
						<?php $page_row = 0; ?>
						<?php foreach ($metaseo_anypage_routes as $metaseo_anypage) { ?>
							<?php if (isset($metaseo_anypage['route']) && $metaseo_anypage['route']) { ?>
								
								<tr id="metaseo_anypage-row<?php echo $page_row; ?>">
									<td class="center"><input type="text" class="form-control" name="metaseo_anypage_routes[<?php echo $page_row; ?>][route]" value="<?php echo $metaseo_anypage['route']; ?>" /></td>
									
									<td class="center">
										
										<?php foreach ($languages as $language) { ?>
											<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											<input type="text" name="metaseo_anypage_routes[<?php echo $page_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($metaseo_anypage['title'][$language['language_id']])?$metaseo_anypage['title'][$language['language_id']]:''; ?>" ><br />	
										<?php } ?>
										
									</td>
									
									<td class="center">
										
										<?php foreach ($languages as $language) { ?>
											<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											<input type="text" name="metaseo_anypage_routes[<?php echo $page_row; ?>][meta_description][<?php echo $language['language_id']; ?>]" value="<?php echo isset($metaseo_anypage['meta_description'][$language['language_id']])?	$metaseo_anypage['meta_description'][$language['language_id']]:''; ?>" ><br />
										<?php } ?>
										
									</td>
									
									<td class="center">
										<a onclick="$('#metaseo_anypage-row<?php echo $page_row; ?>').remove();" class="button">Удалить</a>
									</td>
									
								</tr>
								
								
							<?php }	?>
							<?php $page_row++; ?>
						<?php }	?>						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<td class="right"><a onclick="addmetaseo_anypage();" class="button">Добавить тэги</a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	var page_row = <?php echo $page_row; ?>;
	function addmetaseo_anypage() {
		var html = '';
		
		html  = '<tr id="metaseo_anypage-row' + page_row + '">';
		html  += '<td class="left"><input type="text" class="form-control" name="metaseo_anypage_routes[' + page_row + '][route]" value="" /></td>';
		html  += '<td class="left">';
		<?php foreach ($languages as $language) { ?>
			html  += '	<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
			html  += '	<input type="text" name="metaseo_anypage_routes[' + page_row + '][title][<?php echo $language['language_id']; ?>]" value="" ><br />';
		<?php } ?>		
		html  += '</td>';
		
		html  += '<td class="left">';
		
		<?php foreach ($languages as $language) { ?>
			html  += '	<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
			html  += '	<input type="text" name="metaseo_anypage_routes[' + page_row + '][meta_description][<?php echo $language['language_id']; ?>]" value="" ><br />';
		<?php } ?>
		
		html  += '</td>';
		html  += '<td class="left">';
		html  += '<a onclick="$(\'#metaseo_anypage-row'+ page_row +'\').remove();" class="button">Удалить</a>';
		html  += '</td>';
		html  += '</tr>';
	
	$('#metaseo_anypage tbody').append(html);
	
	page_row++;
	}
	
</script>

<?php echo $footer; ?>				