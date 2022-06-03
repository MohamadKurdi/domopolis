/* configurable settings */
// IF YOU WANT SWATCH IMAGES, USE THE OPENCART BUILT-IN SWATCH OPTION BY SETTING YOUR OPTION TYPE IN OPENCART TO "IMAGE".
// THEN ASSIGN THE SAME IMAGE USED FOR SWATCH TO THE OPTIONS BOOST IMAGE IN THE PRODUCT EDIT AREA.
// THAT WILL MAKE IT SWAP THE MAIN IMAGE WHEN CLICKED.

/* Theme Settings */
var img = '#image'; // The main image id

var origSrc 	= '';
var origTitle 	= '';
var origAlt 	= '';
var origPopup 	= '';

if (!window.console) {var console = {};}
if (!console.log) {console.log = function() {};}

jQuery(document).ready(function(){
	console.log('Options Boost: Initialized');

	// Store original image source
	origSrc 	= jQuery(img).attr('src');
	origTitle 	= jQuery(img).attr('title');
	origAlt 	= jQuery(img).attr('alt');
	origPopup 	= jQuery(img).parent().attr('href');

	if (jQuery(img).length == 0) {
		console.log('img id "'+img+'" not found');
	}

	//jQuery(':input[name^="option"]').change(function(){
	jQuery(':input[name^="option"][type=\'checkbox\']:checked, :input[type=\'hidden\'], :input[name^="option"][type=\'radio\'], select[name^="option"]').change(function(){
		obUpdate(jQuery(this));
	});

	// Force a change on load to support mods like default product options and any "onload" adjustments
	jQuery('select[name^="option"] :selected').change();
	jQuery('input[name^="option"][type=radio]:checked').change();
	jQuery('input[name^="option"][type=checkbox]:checked').change();
});

function getCookie(name) {
  name += '=';
  var parts = document.cookie.split(/;\s*/);
  for (var i = 0; i < parts.length; i++)
  {
    var part = parts[i];
    if (part.indexOf(name) == 0)
      return part.substring(name.length)
  }
  return null;
}

function obUpdate($this) {
	console.log('Options Boost: obUpdate Called by '+jQuery($this).attr('name'));

	// Get Type for determining checked/unchecked for checkbox
	$checked = true;
	if ($this.is('input:checkbox') && !$this.is(':checked')) {
		$checked = false;
	}

	// Set variables...
	var option_id	= $this.attr('id');
	var option_name	= $this.attr('name');
	var option_val	= $this.val();

	// Remove existing option info
	jQuery('#option_info').remove();
	jQuery('.ob_ajax_error').remove();

	//if (jQuery(option).val()) {
	if (option_val && $checked) {

		if (getCookie('isAceshop') == '1') {
			var ajaxurl = 'index.php?option=com_aceshop&format=raw&tmpl=component&route=product/product/updateImage';
		} else {
			var ajaxurl = 'index.php?route=product/product/updateImage';
		}

		// ajax lookup
		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			dataType: 'json',
			data: jQuery(':input[name^="option"][type=\'checkbox\']:checked, :input[type=\'hidden\'], :input[name^="option"][type=\'radio\']:checked, select[name^="option"]').serialize() + '&option_value_id='+option_val,
			beforeSend: function() {
				if (getCookie('isAceshop') == '1') {
					$this.after('<img class="ob_loading" src="components/com_aceshop/opencart/catalog/view/javascript/ajax_load_sm.gif" alt=""/>');
				} else {
					$this.after('<img class="ob_loading" src="catalog/view/javascript/ajax_load_sm.gif" alt=""/>');
				}
			},
			success: function (data) {

				jQuery.each(data, function(key, val) {
				  console.log(key+':'+val);
				});

				// Update the main image with the new image.
				var swatch 		= data.ob_swatch;
				var thumb 		= data.ob_thumb;
				var popup 		= data.ob_popup;
				var info 		= data.ob_info;
				var stock       = data.quantity;
				var name	    = data.name;
				var product_name = data.product_name;

				// Swap Image if exists...
				if (thumb) {
					jQuery(img).attr('src', thumb);
					jQuery(img).attr('title', name);
					jQuery(img).attr('alt', name);
					jQuery(img).parent().attr('href', popup);
				} else { //revert back to main if image not exists
					jQuery(img).attr('src', origSrc);
					jQuery(img).attr('title', origTitle);
					jQuery(img).attr('title', origAlt);
					jQuery(img).parent().attr('href', origPopup);
				}
				
				if (product_name && product_name.length > 0){
					$('#product_name').text(product_name);
				}
			

				// Add under main image or popup
				if (info) {
					xinfo = info.replace("~~", "");
					if (info.indexOf("~~") != -1) { alert(xinfo); }
					jQuery(img).parent().after('<p id="option_info">'+xinfo+'</p>');

				}

			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				console.log('Options Boost: Ajax Lookup Error. Please try again.');
			},
			complete: function() {
				jQuery('.ob_loading').remove();
			}
		});

	} else {
		jQuery(img).attr('src', origSrc);
		jQuery(img).attr('title', origTitle);
		jQuery(img).attr('title', origAlt);
		jQuery(img).parent().attr('href', origPopup);
	}
}