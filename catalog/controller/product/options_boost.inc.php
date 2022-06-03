<?php
//-----------------------------------------
// Author: Qphoria@gmail.com
// Web: http://www.OpenCartGuru.com/
//-----------------------------------------

// Duplicate of the product price code but don't format the values
$discount_query = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

$xdiscount = false;
if ($discount_query) {
	$xdiscount = $xdiscount[0];
}

if ($xdiscount) {
	//$this->data['xprice'] = $this->tax->calculate($xdiscount, $product_info['tax_class_id'], $this->config->get('config_tax'));
	$this->data['xprice'] = ($xdiscount);

	$this->data['xspecial'] = FALSE;
} else {
	//$this->data['xprice'] = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
	$this->data['xprice'] = ($product_info['price']);

	if ($product_info['special']) {
		$this->data['xspecial'] = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
	} else {
		$this->data['xspecial'] = FALSE;
	}
}

$xprice = $this->data['xprice'];
$xspecial = $this->data['xspecial'];

// How much is the discount off the main price.
// use this to apply to the option price
$disc_factor = 0;
if ($xspecial) {
	$disc_factor = ((float)$product_info['special'] / (float)$product_info['price']);
}

//Q: Options Boost
//$this->load->model('catalog/options_boost');
//$options = $this->model_catalog_options_boost->getProductOptions($this->request->get['product_id']);

// Remove +0.0000 prices
//foreach ($options as $k => $option) {
foreach ($this->data['options'] as $k => $option) {

	// Skip text/textbox/etc
	if (!is_array($option['option_value'])) { continue ; }

	// Loop through each option value
	foreach ($option['option_value'] as $j => $option_value) {
		$optsufx = '';

		// Don't show if out of stock and subtract is true
		//if ($option_value['subtract'] && ($option_value['quantity'] == 0)) {
		//	unset($options[$k]['option_value'][$j]);
		//	continue;
		//}

		if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && isset($option_value['raw_price']) && (float)$option_value['raw_price']) {
		//if (!$this->config->get('config_customer_price') && (float)$option_value['raw_price']) {

			// Discount the option by the same price as the product discount for specials
			if ((float)$disc_factor) {
				$old_option_value['raw_price'] = $option_value['raw_price'];
				$option_value['raw_price'] *= $disc_factor;
			}

			//if ($option_value['price_prefix'] == '%' && $xspecial) {
			//	$options[$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xspecial * ((float)$option_value['raw_price']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')));
			//	$options[$k]['option_value'][$j]['price_prefix'] = '+';
			//} else
			if ($option_value['price_prefix'] == '%') {
				//$options[$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * ((float)$option_value['raw_price']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')));
				if ($disc_factor && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * ((float)$old_option_value['raw_price']/100)), $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</strike> <span style=color:red>+' . $this->currency->format($this->tax->calculate(($xprice * ((float)$option_value['raw_price']/100)), $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</span>';
				} else {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * ((float)$option_value['raw_price']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
				$this->data['options'][$k]['option_value'][$j]['price_prefix'] = '+';
			} elseif ($option_value['price_prefix'] == '*') { //check for *
				//$options[$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * (float)$option_value['raw_price']), $product_info['tax_class_id'], $this->config->get('config_tax')));
				if ($disc_factor && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * ((float)$old_option_value['raw_price'])), $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</strike> <span style=color:red>+' . $this->currency->format($this->tax->calculate(($xprice * ((float)$option_value['raw_price'])), $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</span>';
				} else {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate(($xprice * ((float)$option_value['raw_price'])), $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
				$this->data['options'][$k]['option_value'][$j]['price_prefix'] = '+';
			} elseif ($option_value['price_prefix'] == '=') { //check for =
				//$options[$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate((float)$option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				if ($disc_factor && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) {
					$this->data['options'][$k]['option_value'][$j]['price'] = '<strike>' . $this->currency->format($this->tax->calculate((float)$old_option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</strike> <span style=color:red>' . $this->currency->format($this->tax->calculate($option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</span>';
				} else {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate((float)$option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
				$this->data['options'][$k]['option_value'][$j]['price_prefix'] = '';
			} elseif ($option_value['price_prefix'] == '1') { //check for &. Don't display a price for this as it will change depending on the order and selected options
				//$options[$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate((float)$option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				if ($disc_factor && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) {
					$this->data['options'][$k]['option_value'][$j]['price'] = '<strike>' . $this->currency->format($this->tax->calculate($old_option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</strike> <span style=color:red>+' . $this->currency->format($this->tax->calculate($option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</span>';
				} else {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate($option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
				$this->data['options'][$k]['option_value'][$j]['price_prefix'] = '+';
				$optsufx = ' x 1';
			} else { // assume +
				if ($disc_factor && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) {
					$this->data['options'][$k]['option_value'][$j]['price'] = '<strike>' . $this->currency->format($this->tax->calculate($old_option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</strike> <span style=color:red>+' . $this->currency->format($this->tax->calculate($option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) . '</span>';
				} else {
					$this->data['options'][$k]['option_value'][$j]['price'] = $this->currency->format($this->tax->calculate($option_value['raw_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				}
			}
		} else {
			$this->data['options'][$k]['option_value'][$j]['price'] = FALSE;
		}


		// Image resize:
		if (!empty($this->data['options'][$k]['option_value'][$j]['ob_image'])) {
			$this->data['options'][$k]['option_value'][$j]['ob_swatch'] = $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['ob_image'], 40, 40);
			$this->data['options'][$k]['option_value'][$j]['ob_thumb'] 	= $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['ob_image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			$this->data['options'][$k]['option_value'][$j]['ob_popup'] 	= $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['ob_image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
		} elseif (!empty($this->data['options'][$k]['option_value'][$j]['raw_image'])) {
			$this->data['options'][$k]['option_value'][$j]['ob_swatch'] = $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['raw_image'], 40, 40);
			$this->data['options'][$k]['option_value'][$j]['ob_thumb'] 	= $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['raw_image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			$this->data['options'][$k]['option_value'][$j]['ob_popup'] 	= $this->model_tool_image->resize($this->data['options'][$k]['option_value'][$j]['raw_image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
		} else {
			$this->data['options'][$k]['option_value'][$j]['ob_swatch'] = '';
			$this->data['options'][$k]['option_value'][$j]['ob_thumb'] 	= '';
			$this->data['options'][$k]['option_value'][$j]['ob_popup'] 	= '';
		}

		// Add Stock Qty to name
		//$this->data['options'][$k]['option_value'][$j]['name'] = $this->data['options'][$k]['option_value'][$j]['name'] . ' [' . $this->data['options'][$k]['option_value'][$j]['quantity'] . '] ';

		// Add Sku to name
		//$this->data['options'][$k]['option_value'][$j]['name'] = $this->data['options'][$k]['option_value'][$j]['name'] . ' [' . $this->data['options'][$k]['option_value'][$j]['ob_sku'] . '] ';

		
		// Add (1x) suffix to one time options
		$this->data['options'][$k]['option_value'][$j]['price'] = $this->data['options'][$k]['option_value'][$j]['price'] . $optsufx;
	}
}

// Override original options
//$this->data['options'] = array();
//$this->data['options'] = $options;
//
?>