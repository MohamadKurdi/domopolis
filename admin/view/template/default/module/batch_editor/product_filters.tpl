<div class="content">
 <table class="form">
  <tr>
   <td width="1%"><img src="<?php echo $product_image; ?>" /></td>
   <td width="99%"><h3><?php echo $product_name; ?></h3></td>
  </tr>
 </table>
 <form id="form-filters-list">
  <input type="hidden" name="selected[]" value="<?php echo $product_id; ?>" />
  <table class="list">
   <tbody>
    <tr>
     <td class="left"><input type="text" name="filters" value="" size="100" /></td>
    </tr>
    <tr>
     <td class="left">
      <div class="scrollbox" id="product-filters" style="width:100%; height:350px;">
       <?php $class = 'odd'; ?>
       <?php foreach ($product_filters as $product_filter) { ?>
       <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
       <div id="product-filters<?php echo $product_filter['id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_filter['name']; ?><img src="view/image/delete.png" /><input type="hidden" name="product_filters[]" value="<?php echo $product_filter['id']; ?>" /></div>
       <?php } ?>
      </div>
     </td>
    </tr>
   </tbody>
   <tfoot>
    <tr>
     <td class="center"><a class="button" onclick="editProductList('filters', 'upd');"><?php echo $button_save; ?></a> <a class="button" onclick="$('#dialog').dialog('close');">X</a></td>
    </tr>
   </tfoot>
  </table>
 </form>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#dialog').dialog('option', 'title', '<?php echo $column_filters; ?>');
	
	$('#form-filters-list #product-filters div img').live('click', function() {
		$(this).parent().remove();
		
		$('#form-filters-list #product-filters div:odd').attr('class', 'odd');
		$('#form-filters-list #product-filters div:even').attr('class', 'even');
	});
	
	filtersautocomplete('form-filters-list');
});
//--></script>