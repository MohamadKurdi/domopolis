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
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="box">
			<div class="heading order_head">
				<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
				<div class="buttons">
					<?php if (count($stores) > 0) { ?>
						<div class="stores" style="margin-right: 5px; display: inline-block;">
							Магазин:&nbsp;
							<select name="store_id" id="store_id" onchange="location='<?php echo str_replace("'", "\\'",$action_without_store); ?>'+'&store_id='+$(this).val()">
								<?php foreach ($stores as $key => $value) { ?>
									<option value="<?php echo $value['store_id'] ?>" <?php echo $store_id == $value['store_id'] ? 'selected="selected"' : '' ?>><?php echo $value['store_id'] ?> - <?php echo $value['name'] ?></option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
			</div>
			<div class="content">     
				<table class="form" id="form">
					<?php $module_row = 0; ?>
					<?php foreach ($modules as $module): ?>
					<tr class="module<?php echo $module_row ?>">
						<input class="module<?php echo $module_row ?>" type="hidden" name="custom_template_module[<?php echo $module_row ?>][enabled]" value="0">
						<input class="module<?php echo $module_row ?>" type="hidden" name="custom_template_module[<?php echo $module_row ?>][layout_id]" value="0">
						<td colspan="2"></td>
					</tr>
					<tr class="module<?php echo $module_row ?> common<?php echo $module_row ?>">
						<td><?php echo $entry_module_type ?></td>
						<td>
							<select class="select_type" data-id="<?php echo $module_row ?>" name="custom_template_module[<?php echo $module_row ?>][type]" id="custom_template_module[<?php echo $module_row ?>][type]">
								<?php foreach ($module_types as $type_id => $type_caption): ?>
								<?php if ($type_id == $module['type']){ ?>
									<option value="<?php echo $type_id ?>" selected="selected"><?php echo $type_caption ?></option>  
									<?php } else { ?>
									<option value="<?php echo $type_id ?>"><?php echo $type_caption ?></option>
								<?php } ?>
								<?php endforeach ?>
							</select>
						</td>
					</tr>
					<tr class="categories module<?php echo $module_row ?>">
						<td><?php echo $entry_category ?></td>
						<td>
							<div class="scrollbox" style="height:400px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($categories as $category_key => $category_data) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($category_data['category_id'], $module['categories'])) { ?>
											<input class="checkbox" type="checkbox" name="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" checked="checked" />
											<label for="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]"><?php echo $category_data['name']; ?></label>
											<?php } else { ?>
											<input class="checkbox"type="checkbox" name="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" />
											<label for="custom_template_module[<?php echo $module_row ?>][categories][<?php echo $category_data['category_id']; ?>]"><?php echo $category_data['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
						</td>
					</tr>
					<tr class="informations module<?php echo $module_row ?>">
						<td><?php echo $entry_information ?></td>
						<td>
							<div class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($informations as $information_key => $information_data) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($information_data['information_id'], $module['informations'])) { ?>
											<input class="checkbox" type="checkbox" name="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]" value="<?php echo $information_data['information_id']; ?>" checked="checked" />
											<label for="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]"><?php echo $information_data['title']; ?></label>
											<?php } else { ?>
											<input class="checkbox" type="checkbox" name="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]" value="<?php echo $information_data['information_id']; ?>" />
											<label for="custom_template_module[<?php echo $module_row ?>][informations][<?php echo $information_data['information_id']; ?>]"><?php echo $information_data['title']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
						</td>
					</tr>
					<tr class="manufacturers module<?php echo $module_row ?>">
						<td><?php echo $entry_manufacturer ?></td>
						<td>
							<div class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($manufacturers as $manufacturer_key => $manufacturer_data) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($manufacturer_data['manufacturer_id'], $module['manufacturers'])) { ?>
											<input type="checkbox" name="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" checked="checked" />
											<label for="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]"><?php echo $manufacturer_data['name']; ?></label>
											<?php } else { ?>
											<input type="checkbox" name="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" />
											<label for="custom_template_module[<?php echo $module_row ?>][manufacturers][<?php echo $manufacturer_data['manufacturer_id']; ?>]"><?php echo $manufacturer_data['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
						</td>
					</tr>
					<tr class="products module<?php echo $module_row ?>">
						<td><?php echo $entry_product ?></td>
						<td><input type="text" class="product_autocomplete" id="autocomplete<?php echo $module_row ?>" data-id="<?php echo $module_row ?>" value="" /></td>
					</tr>
					<tr class="products module<?php echo $module_row ?>">
						<td>&nbsp;</td>
						<td>
							<div id="custom-template-product<?php echo $module_row ?>" class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php if (isset($module['parsed_products'])): ?>
								<?php foreach ($module['parsed_products'] as $product): ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div id="custom-template-product<?php echo $module_row ?><?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" class="delete" data-id="<?php echo $module_row ?>" alt="" />
									<input type="hidden" value="<?php echo $product['product_id']; ?>" />
								</div>
								<?php endforeach ?>
								<?php endif ?>
							</div>
						<input type="hidden" name="custom_template_module[<?php echo $module_row ?>][products]" value="<?php echo $module['products']; ?>" /></td>
					</td>
				</tr>
				<tr class="product_categories module<?php echo $module_row ?>">
					<td><?php echo $entry_category ?></td>
					<td>
						<div class="scrollbox">
							<?php $class = 'odd'; ?>
							<?php foreach ($categories as $category_key => $category_data) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($category_data['category_id'], $module['product_categories'])) { ?>
										<input type="checkbox" name="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" checked="checked" />
										<label for="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]"><?php echo $category_data['name']; ?></label>
										<?php } else { ?>
										<input type="checkbox" name="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]" id="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" />
										<label for="custom_template_module[<?php echo $module_row ?>][product_categories][<?php echo $category_data['category_id']; ?>]"><?php echo $category_data['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
						<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</td>
				</tr>
				<tr class="template module<?php echo $module_row ?> common<?php echo $module_row ?>">
					<td><?php printf($entry_template, $template_dir) ?></td>
					<td><input type="text" name="custom_template_module[<?php echo $module_row ?>][template_name]" value="<?php echo $module['template_name'] ?>">
						<a style="float:right;" onclick="$('.module<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
					</td>
				</tr>
				<?php $module_row++ ?>
				
				<?php endforeach ?>
				<tr id="add_module">
					<td class="left" colspan="2"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
				</tr>
			</table>
		</div>
	</div>
</form>
</div>
<script type="text/javascript"><!--
	$(document).ready(function(){
		init_form();
	});
	
	$('#form').submit(function(event){
		$.each($('.select_type'), function(){
			var elem_id = $(this).attr('data-id');
			var elem = $('input[name=\'custom_template_module['+elem_id+'][template_name]\']');
			if (empty($(elem).val())) {
				$(elem).parents('tr').addClass('highlight_error');
				$(elem).focus();
				//alert('<?php echo $text_empty_field; ?>');
				event.preventDefault();
				}else{
				return;
			}
		});
	});
	function empty( mixed_var ) {
		return ( mixed_var === "" || mixed_var === 0   || mixed_var === "0" || mixed_var === null  || mixed_var === false );
	}
	function init_form(){
		$.each($('.select_type'), function(){
			var elem_id = $(this).attr('data-id');
			$('.template').find('input').on('change keyup paste', function(event){
				if (empty($(this).val())) {
					$(this).parents('tr').addClass('highlight_error');
					}else{
					$(this).parents('tr').removeClass('highlight_error');
				}
			});
			switch (parseInt($(this).val())) {
				case 0:
				$('.common'+elem_id).addClass('categories');
				$('.common'+elem_id).removeClass('products informations manufacturers product_categories');
				$('.categories.module'+elem_id).show();
				$('.products.module'+elem_id+', .informations.module'+elem_id+', .manufacturers.module'+elem_id+', .product_categories.module'+elem_id).hide();
				break;
				case 1:
				$('.common'+elem_id).addClass('products');
				$('.common'+elem_id).removeClass('categories informations manufacturers product_categories');
			$('.products.module'+elem_id).show();
			$('.categories.module'+elem_id+', .informations.module'+elem_id+', .manufacturers.module'+elem_id+', .product_categories.module'+elem_id).hide();
			break;
			case 2:
			$('.common'+elem_id).addClass('informations');
			$('.common'+elem_id).removeClass('categories products manufacturers product_categories');
			$('.informations.module'+elem_id).show();
			$('.categories.module'+elem_id+', .products.module'+elem_id+', .manufacturers.module'+elem_id+', .product_categories.module'+elem_id).hide();
			break;
			case 3:
			$('.common'+elem_id).addClass('manufacturers');
			$('.common'+elem_id).removeClass('categories products informations product_categories');
			$('.manufacturers.module'+elem_id).show();
			$('.categories.module'+elem_id+', .products.module'+elem_id+', .informations.module'+elem_id+', .product_categories.module'+elem_id).hide();
			break; 
			case 4:
			$('.common'+elem_id).addClass('product_categories');
			$('.common'+elem_id).removeClass('products informations manufacturers categories');
			$('.product_categories.module'+elem_id).show();
			$('.categories.module'+elem_id+', .products.module'+elem_id+', .informations.module'+elem_id+', .manufacturers.module'+elem_id).hide();
			break;
			}
			});
			}
			$('.select_type').change(function(){
			init_form();
			});
			$('.product_autocomplete').autocomplete({
			delay: 500,
			source: function(request, response) {
			$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
			response($.map(json, function(item) {
			return {
            label: item.name,
            value: item.product_id
			}
			}));
			}
			});
			},
			select: function(event, ui) {
			$('#custom-template-product'+$(this).attr('data-id') + ui.item.value).remove();
			
			$('#custom-template-product'+$(this).attr('data-id')).append('<div id="custom-template-product'+$(this).attr('data-id') + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" class="delete" data-id="'+$(this).attr('data-id')+'" /><input type="hidden" value="' + ui.item.value + '" /></div>');
			
			$('#custom-template-product'+$(this).attr('data-id')+' div:odd').attr('class', 'odd');
			$('#custom-template-product'+$(this).attr('data-id')+' div:even').attr('class', 'even');
			
			data = $.map($('#custom-template-product'+$(this).attr('data-id')+' input'), function(element){
			return $(element).attr('value');
			});
			
			$('input[name=\'custom_template_module['+$(this).attr('data-id')+'][products]\']').attr('value', data.join());
			
			return false;
			},
			focus: function(event, ui) {
			return false;
			}
			});
			
			$('img.delete').live('click', function() {
			var elem_id = $(this).attr('data-id');
			$(this).parent().remove();
			
			$('#custom-template-product'+elem_id+' div:odd').attr('class', 'odd');
			$('#custom-template-product'+elem_id+' div:even').attr('class', 'even');
			
			data = $.map($('#custom-template-product'+elem_id+' input'), function(element){
			return $(element).attr('value');
			});
			
			$('input[name=\'custom_template_module['+elem_id+'][products]\']').attr('value', data.join());
			});
			//--></script>
			<script type="text/javascript"><!--
			var module_row = <?php echo $module_row; ?>;
			
			function addModule() {
			html = '';
			html += '<tr class="module'+ module_row +'">';
			html += '<input class="module'+ module_row +'" type="hidden" name="custom_template_module['+ module_row +'][enabled]" value="0">';
			html += '<input class="module'+ module_row +'" type="hidden" name="custom_template_module['+ module_row +'][layout_id]" value="0">';        
			html += '<td colspan="2"></td>';
			html += '</tr>';
			html += '<tr class="module'+ module_row +' common'+ module_row +'">';
			html +='<td><?php echo $entry_module_type ?></td>';
			html +='<td>';
			html +='<select class="select_type" data-id="'+ module_row +'" name="custom_template_module['+ module_row +'][type]" id="custom_template_module['+ module_row +'][type]">';
			<?php foreach ($module_types as $type_id => $type_caption): ?>
			html +='<option value="<?php echo $type_id ?>"><?php echo $type_caption ?></option>';
			<?php endforeach ?>
			html +='</select>';
			html +='</td>';
			html +='</tr>';
			html +='<tr class="categories module'+ module_row +'">';
			html +='<td><?php echo $entry_category ?></td>';
			html +='<td>';
			html +='<div class="scrollbox" style="height:400px;">';
			<?php $class = 'odd'; ?>
			<?php foreach ($categories as $category_key => $category_data): ?>
			<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
			html +='<div class="<?php echo $class; ?>">';
			html +='<input class="checkbox" type="checkbox" name="custom_template_module['+ module_row +'][categories][<?php echo $category_data['category_id'] ?>]" id="custom_template_module['+ module_row +'][categories][<?php echo $category_data['category_id'] ?>]" value="<?php echo $category_data['category_id']; ?>" />';
			html +='<label for="custom_template_module['+ module_row +'][categories][<?php echo $category_data['category_id'] ?>]"><?php echo $category_data['name']; ?></label>';
			html +='</div>';
			<?php endforeach ?>
			html +='</div>';
			html +='<a class="select_all" onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
			html +='</td>';
			html +='</tr>'
			html +='<tr class="informations module'+ module_row +'">';
			html +='<td><?php echo $entry_information ?></td>';
			html +='<td>';
			html +='<div class="scrollbox">';
			<?php $class = 'odd'; ?>
			<?php foreach ($informations as $information_key => $information_data): ?>
			<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
			html +='<div class="<?php echo $class; ?>">';
			html +='<input type="checkbox" name="custom_template_module['+ module_row +'][informations][<?php echo $information_data['information_id'] ?>]" id="custom_template_module['+ module_row +'][informations][<?php echo $information_data['information_id'] ?>]" value="<?php echo $information_data['information_id']; ?>" />';
			html +='<label for="custom_template_module['+ module_row +'][informations][<?php echo $information_data['information_id'] ?>]"><?php echo $information_data['title']; ?></label>';
			html +='</div>';
			<?php endforeach ?>
			html +='</div>';
			html +='<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
			html +='</td>';
			html +='</tr>';
			html +='<tr class="manufacturers module'+ module_row +'">';
			html +='<td><?php echo $entry_manufacturer ?></td>';
			html +='<td>';
			html +='<div class="scrollbox">';
			<?php $class = 'odd'; ?>
			<?php foreach ($manufacturers as $manufacturer_key => $manufacturer_data) : ?>
			<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
			html +='<div class="<?php echo $class; ?>">';
			html +='<input type="checkbox" name="custom_template_module['+ module_row +'][manufacturers][<?php echo $manufacturer_data['manufacturer_id'] ?>]" id="custom_template_module['+ module_row +'][manufacturers][<?php echo $manufacturer_data['manufacturer_id'] ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" />';
			html +='<label for="custom_template_module['+ module_row +'][manufacturers][<?php echo $manufacturer_data['manufacturer_id'] ?>]"><?php echo $manufacturer_data['name']; ?></label>';
			html +='</div>';
			<?php endforeach ?>
			html +='</div>';
			html +='<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
			html +='</td>';
			html +='</tr>';
			html +='<tr class="products module'+ module_row +'">';
			html +='<td><?php echo $entry_product ?></td>';
			html +='<td><input type="text" class="product_autocomplete" id="autocomplete'+ module_row +'" data-id="'+ module_row +'" value="" /></td>';
			html +='</tr>';
			html +='<tr class="products module'+ module_row +'">';
			html +='<td>&nbsp;</td>';
			html +='<td>';
			html +='<div id="custom-template-product'+ module_row +'" class="scrollbox"></div>';
			html +='<input type="hidden" name="custom_template_module['+ module_row +'][products]" value="" />';
			html +='</td>';
			html +='</tr>';
			html +='<tr class="product_categories module'+ module_row +'">';
			html +='<td><?php echo $entry_category ?></td>';
			html +='<td>';
			html +='<div class="scrollbox">';
			<?php $class = 'odd'; ?>
			<?php foreach ($categories as $category_key => $category_data) { ?>
			<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
			html +='<div class="<?php echo $class; ?>">';
			html +='<input type="checkbox" name="custom_template_module['+ module_row +'][product_categories][<?php echo $category_data['category_id']; ?>]" id="custom_template_module['+ module_row +'][product_categories][<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" />';
			html +='<label for="custom_template_module['+ module_row +'][product_categories][<?php echo $category_data['category_id']; ?>]"><?php echo $category_data['name']; ?></label>';
			html +='</div>';
			<?php } ?>
			html +='</div>';
			html +='<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
			html +='</td>';
			html +='</tr>';
			html +='<tr class="template module'+ module_row +' common'+ module_row +'">';
			html +='<td><?php echo $js_entry_template ?></td>';
			html +='<td><input type="text" name="custom_template_module['+ module_row +'][template_name]">';
			html +='<a style="float:right;" onclick="$(\'.module<?php echo $module_row; ?>\').remove();" class="button"><?php echo $button_remove; ?></a>';
			html +='</td>';
			html +='</tr>';
			
			$('#add_module').before(html);
			
			$('.product_autocomplete').autocomplete({
			delay: 500,
			source: function(request, response) {
			$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
			response($.map(json, function(item) {
			return {
            label: item.name,
            value: item.product_id
			}
			}));
			}
			});
			},
			select: function(event, ui) {
			$('#custom-template-product'+$(this).attr('data-id') + ui.item.value).remove();
			
			$('#custom-template-product'+$(this).attr('data-id')).append('<div id="custom-template-product'+$(this).attr('data-id') + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" class="delete" data-id="'+$(this).attr('data-id')+'" /><input type="hidden" value="' + ui.item.value + '" /></div>');
			
			$('#custom-template-product'+$(this).attr('data-id')+' div:odd').attr('class', 'odd');
			$('#custom-template-product'+$(this).attr('data-id')+' div:even').attr('class', 'even');
			
			data = $.map($('#custom-template-product'+$(this).attr('data-id')+' input'), function(element){
			return $(element).attr('value');
			});
			
			$('input[name=\'custom_template_module['+$(this).attr('data-id')+'][products]\']').attr('value', data.join());
			
			return false;
			},
			focus: function(event, ui) {
			return false;
			}
			});
			
			$('img.delete').live('click', function() {
			var elem_id = $(this).attr('data-id');
			$(this).parent().remove();
			
			$('#custom-template-product'+elem_id+' div:odd').attr('class', 'odd');
			$('#custom-template-product'+elem_id+' div:even').attr('class', 'even');
			
			data = $.map($('#custom-template-product'+elem_id+' input'), function(element){
			return $(element).attr('value');
			});
			
			$('input[name=\'custom_template_module['+elem_id+'][products]\']').attr('value', data.join());
			});
			init_form();
			$('.select_type').change(function(){
			init_form();
			});
			module_row++;
			}
			//--></script> 
			<style>
			.categories{
			background-color: #fff4d9;
			border-left: 5px solid #ffd166;
			}
			.informations{
			background-color: #f4ffed;
			border-left: 5px solid #b1db95;
			}
			.manufacturers{
			background-color: #ebfcff;
			border-left: 5px solid #4d90fe;
			}
			.products{
			background-color: #f7f2ff;
			border-left: 5px solid #c4a0ff;
			}
			.product_categories{
			background-color: #ffeded;
			border-left: 5px solid #ffcece;
			}
			.highlight_error{
			border-left: 5px solid #CE4C38;
			background: #ffc9c9;
			}
			select{
			width: 350px;
			}
			input[type="text"]{
			width: 340px;
			}
			</style>
			<?php echo $footer; ?>			