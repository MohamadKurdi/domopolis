<div class="content">
 <table class="form">
  <tr>
   <td width="1%"><img src="<?php echo $product_image; ?>" /></td>
   <td width="99%"><h3><?php echo $product_name; ?></h3></td>
  </tr>
 </table>
 <form id="form-layouts-list">
  <input type="hidden" name="selected[]" value="<?php echo $product_id; ?>" />
  <table class="list">
   <thead>
    <tr>
     <td class="left"><?php echo $column_stores; ?></td>
     <td class="left"><?php echo $column_layouts; ?></td>
    </tr>
   </thead>
   <tbody>
    <tr>
     <td class="left"><?php echo $text_default; ?></td>
     <td class="left">
      <select name="product_layouts[0][layout_id]">
       <option value=""></option>
       <?php foreach($layouts as $layout) { ?>
       <?php if (isset($product_layouts[0]) && $product_layouts[0] == $layout['layout_id']) { ?>
       <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
       <?php } else { ?>
       <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
       <?php } ?>
       <?php } ?>
      </select>
     </td>
    </tr>
    <?php foreach($stores as $store) { ?>
    <tr>
     <td class="left"><?php echo $store['name']; ?></td>
     <td class="left">
      <select name="product_layouts[<?php echo $store['store_id']; ?>][layout_id]">
       <option value=""></option>
       <?php foreach($layouts as $layout) { ?>
       <?php if (isset($product_layouts[$store['store_id']]) && $product_layouts[$store['store_id']] == $layout['layout_id']) { ?>
       <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
       <?php } else { ?>
       <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
       <?php } ?>
       <?php } ?>
      </select>
     </td>
    </tr>
    <?php } ?>
   </tbody>
   <tfoot>
    <tr>
     <td class="center" colspan="2"><a class="button" onclick="editProductList('layouts', 'upd');"><?php echo $button_save; ?></a> <a class="button" onclick="$('#dialog').dialog('close');">X</a></td>
    </tr>
   </tfoot>
  </table>
 </form>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#dialog').dialog('option', 'title', '<?php echo $column_design; ?>');
});
//--></script>