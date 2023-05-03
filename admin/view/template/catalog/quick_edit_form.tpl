<div id="aqe-popup">
  <div id="save-overlay" class="save-overlay">
    <div class="tbl">
      <div class="tbl_cell"><img src="view/image/aqe-pro/aqe_loading.gif" style="margin:-2px;"/></div>
    </div>
  </div>
  <div class="notice-container"></div>
  <form enctype="multipart/form-data" id="aqe-popup-form" onsubmit="return false;">
    <input type="hidden" name="i_id" value="<?php echo $i_id; ?>" />
<?php
switch($parameter) {
  case "store": ?>
    <select name="i_s" multiple="multiple" size="10" style="width:100%;min-width:300px;height: 500px;">
    <?php foreach ($stores as $store) { ?>
    <option value="<?php echo $store['store_id']; ?>"<?php echo (in_array($store['store_id'], $item_store)) ? ' selected="selected"': ''; ?>><?php echo $store['name']; ?></option>
    <?php } ?>
    </select>
    <?php break;
  case "filter": ?>
    <select name="i_f" multiple="multiple" size="10" style="width:100%;min-width:300px;height: 500px;">
    <?php foreach ($filters as $filter) { ?>
    <option value="<?php echo $filter['filter_id']; ?>"<?php echo (in_array($filter['filter_id'], $item_filter)) ? ' selected="selected"': ''; ?>><?php echo strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8')); ?></option>
    <?php } ?>
    </select>
    <?php break;
  case "filters": ?>
    <table id="filter" class="list">
      <thead>
        <tr>
          <td class="left"><span class="required">*</span> <?php echo $entry_name ?></td>
          <td class="right"><?php echo $entry_sort_order; ?></td>
          <td></td>
        </tr>
      </thead>
      <?php $filter_row = 0; ?>
      <?php foreach ($filters as $filter) { ?>
      <tbody id="filter-row<?php echo $filter_row; ?>">
        <tr>
          <td class="left"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
            <?php foreach ($languages as $language) { ?>
            <span>
              <input type="text" name="filter[<?php echo $filter_row; ?>][filter_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter['filter_description'][$language['language_id']]) ? $filter['filter_description'][$language['language_id']]['name'] : ''; ?>" />
              <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_filter[$filter_row][$language['language_id']])) { ?>
              <span class="error"><?php echo $error_filter[$filter_row][$language['language_id']]; ?></span>
              <?php } ?>
            </span>
            <?php } ?></td>
          <td class="right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" size="1" /></td>
          <td class="left"><a onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
        </tr>
      </tbody>
      <?php $filter_row++; ?>
      <?php } ?>
      <tfoot>
        <tr>
          <td colspan="2"></td>
          <td class="left"><a onclick="addFilter();" class="button"><?php echo $button_add_filter; ?></a></td>
        </tr>
      </tfoot>
    </table>
<script type="text/javascript"><!--
var filter_row = <?php echo $filter_row; ?>;

function addFilter() {
  html  = '<tbody id="filter-row' + filter_row + '">';
  html += '  <tr>';
  html += '    <td class="left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
  <?php foreach ($languages as $language) { ?>
  html += '    <input type="text" name="filter[' + filter_row + '][filter_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
  <?php } ?>
  html += '    </td>';
  html += '    <td class="right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" size="1" /></td>';
  html += '     <td class="left"><a onclick="$(\'#filter-row' + filter_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '  </tr>';
    html += '</tbody>';

  $('#filter tfoot').before(html);

  filter_row++;
}
//--></script>
    <?php break;
  default:
    break;
}
?>
  </form>
</div>
