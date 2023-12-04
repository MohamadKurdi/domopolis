<?php
/**
 * PDf Invoice part of email template extension
 *
 * @author: Ben Johnson, opencart-templates
 * @email: info@opencart-templates.co.uk
 * @website: http://www.opencart-templates.co.uk
 *
 */
if(!defined('DIR_SYSTEM')) exit;

# CONFIG in: library/shared/tcpdf/config
require_once(DIR_SYSTEM . 'library/shared/tcpdf/tcpdf.php');
require_once(DIR_SYSTEM . 'library/shared/tcpdf/include/tcpdf.EasyTable.php');
require_once(DIR_SYSTEM . 'library/shared/tcpdf/include/tcpdf.PDFImage.php');

class EmailTemplateInvoice extends TCPDF_EasyTable {

	var $data = array();

	var $fontFamily = 'dejavusans'; // or use 'dejavusans' for utf8 support

	/**
	 * Sets PDF Config
	 *
	 * @param array $data
	 * @return invoicePdf
	 */
	public function Build() {
		$this->SetAuthor('kitchen-profi');
		$this->SetCreator('tdpdf');
		$this->SetSubject($this->data['store']['config_name']);
		$this->SetTitle($this->data['store']['config_name']);
		//$this->SetKeywords();
		//$this->SetProtection(array('modify', 'copy'), '', null, 1, null);

		if($this->fontFamily == 'dejavusans'){
			$subset = false;
		} else {
			$subset = 'default';
		}
		$this->AddFont($this->fontFamily, '', $this->fontFamily, $subset);
		$this->AddFont($this->fontFamily, 'B', $this->fontFamily, $subset);

		$this->SetFont($this->fontFamily, '', 8);
		$this->SetTextColor(0, 0, 0);

		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);

		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->SetFillImageCell(false);

		$this->SetTableHeaderPerPage(true);

		$this->SetLineWidth(0.1);
		$this->SetCellPaddings(1, 1, 1, 1);

		$this->SetCellFillStyle(2);
		$this->SetFillColor(247, 247, 247);
		$this->SetDrawColor(150, 150, 150);

		$this->setCellHeightRatio(1.5);

		return $this;
	}

	/**
	 * Main method responsible for drawing the sections onto the page
	 * Group products into page(s)?
	 *
	 * WriteHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
	 * writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
	 *
	 * @return invoicePdf
	 */
	public function Draw(){
		$this->AddPage();

		if($this->data['config']['invoice_header']){
			$html = str_replace('\'', '"', $this->data['config']['invoice_header']);
			$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
			$html = $this->parseShortcodes($html);
			$this->writeHTML($html, true, false, false, '');
		}

		if($this->data['html']){
			$html = str_replace('\'', '"', $this->data['html']);
			$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
			$this->writeHTML($html, true, false, false, '');
		}

		$this->AddOrderTable();

		if($this->data['config']['invoice_footer']){
			$html = str_replace('\'', '"', $this->data['config']['invoice_footer']);
			$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
			$this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'C', true);
		}

		return $this;
	}

	protected function AddOrderTable(){
		$this->SetHeaderCellsFillColor($this->data['config']['invoice_color']);
		$this->SetHeaderCellsFontColor(255, 255, 255);

		$columns = array();
		$columns['product'] = array(
			'align' => 'L',
			'width' => 35
		);
		$columns['model'] = array(
			'align' => 'L',
			'width' => 20
		);
		$columns['quantity'] = array(
			'align' => 'C',
			'width' => 15
		);
		$columns['price'] = array(
			'align' => 'R',
			'width' => 15
		);
		$columns['total'] = array(
			'align' => 'R',
			'width' => 15
		);

		$w1 = $this->GetInnerPageWidth()/100;

		$alignment = $width = array();

		foreach($columns as $column){
			$alignment[] = $column['align'];
			$width[] = $w1 * $column['width'];
		}

		$this->SetCellAlignment($alignment);
		$this->SetCellWidths($width);

		if($this->data['order']['products']){
			$rows = array();

			foreach($this->data['order']['products'] as $product){
				$rows[] = array(
					'<a href="'.$product['url'].'" style="text-decoration:none; color:#000000;">'.$product['name'].'</a>' . $product['option'],
					$product['model'],
					$product['quantity'],
					$product['price'],
					$product['total']
				);
			}

			$this->EasyTable($rows, array(
				'<b style="line-height: 2pt">' . $this->getLanguage('column_product') . "</b>",
				'<b style="line-height: 2pt">' . $this->getLanguage('column_model') . "</b>",
				'<b style="line-height: 2pt">' . $this->getLanguage('column_quantity') . "</b>",
				'<b style="line-height: 2pt">' . $this->getLanguage('column_price') . "</b>",
				'<b style="line-height: 2pt">' . $this->getLanguage('column_total') . "</b>"
			));
		}

		if($this->data['order']['vouchers']){
			$rows = array();

			foreach($this->data['order']['vouchers'] as $voucher){
				$rows[] = array(
					$voucher['description'],
					'',
					1,
					$voucher['amount'],
					$voucher['amount']
				);
			}

			$this->EasyTable($rows);
		}

		if($this->data['order']['totals']){
			$this->SetFillColor(255,255,255);

			$w1 = $this->GetInnerPageWidth()/100;
			$this->SetCellWidths(array($w1*85, $w1*15));
			$this->SetCellAlignment(array('R','R'));

			$rows = array();

			foreach($this->data['order']['totals'] as $total){
				$rows[] = array(
					$total['title'],
					$total['text']
				);
			}

			$this->EasyTable($rows);
		}
	}

	/**
	 *
	 * @see tFPDF::Header()
	 */
	function Header(){

	}

	/**
	 * @see TCPDF::Footer()
	 */
	function Footer(){
		$this->SetY($this->y);
		$this->SetFont($this->fontFamily, '', 7);
		$w_page = isset($this->l['text_paging']) ? $this->l['text_paging'] : 'Page %s of %s';
		if (empty($this->pagegroups)) {
			$html = sprintf($w_page, $this->getAliasNumPage(), $this->getAliasNbPages());
		} else {
			$html = sprintf($w_page, $this->getPageNumGroupAlias(), $this->getPageGroupAlias());
		}
		$this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'C', true);
	}

	public function getLanguage($key) {
		if (isset($this->l[$key])) {
			return $this->l[$key];
		}
		return '';
	}

	public function parseShortcodes($html){
		$data = $this->data;

		if(isset($data['store'])){
			foreach($data['store'] as $key => $var){
				if(is_string($var) || is_int($var)){
					if(substr($key, 0, 7) == 'config_'){ // Replace config_ with store_
						$key = 'store_' . substr($key, 7);
					}
					$find[] = '{$'.$key.'}';
					$replace[] = $var;
				}
			}
			unset($data['store']);
		}

		foreach($data as $key => $var){
			if(is_array($var)){
				foreach($var as $key2 => $var2){
					if(is_string($var2) || is_int($var2)){
						$find[] = '{$'.$key.'.'.$key2.'}';
						$replace[] = $var2;
					}
				}
			} elseif(is_string($var) || is_int($var)){
				$find[] = '{$'.$key.'}';
				$replace[] = $var;
			}
		}

		foreach($this->l as $key => $var){
			$find[] = '{$'.$key.'}';
			$replace[] = $var;
		}

        return str_replace($find, $replace, $html);
	}

	function GetInnerPageWidth(){
		return $this->getPageWidth()-(PDF_MARGIN_LEFT+PDF_MARGIN_RIGHT);
	}

	/**
	 * Overload to allow HEX color
	 * @see TCPDF::SetDrawColor()
	 */
	function SetDrawColor($col1=0, $col2=-1, $col3=-1, $col4=-1, $ret=false, $name=''){
		if($col1[0] == '#'){
			list($col1, $col2, $col3) = $this->_hex2rbg($col1);
		}
		return parent::SetDrawColor($col1, $col2, $col3, $col4, $ret, $name);
	}

	/**
	 * Overload to allow HEX color
	 * @see TCPDF::SetTextColor()
	 */
	function SetTextColor($col1=0, $col2=-1, $col3=-1, $col4=-1, $ret=false, $name=''){
		if($col1[0] == '#'){
			list($col1, $col2, $col3) = $this->_hex2rbg($col1);
		}
		return parent::SetTextColor($col1, $col2, $col3, $col4, $ret, $name);
	}

	/**
	 * Overload to allow HEX color
	 * @see FPDF::SetFillColor()
	 */
	function SetFillColor($col1=0, $col2=-1, $col3=-1, $col4=-1, $ret=false, $name=''){
		if($col1[0] == '#'){
			list($col1, $col2, $col3) = $this->_hex2rbg($col1);
		}
		return parent::SetFillColor($col1, $col2, $col3, $col4, $ret, $name);
	}

	/**
	 * Overload to allow HEX color
	 * @see TCPDF_EasyTable::SetHeaderCellsFillColor()
	 */
	function SetHeaderCellsFillColor($R, $G=-1, $B=-1){
		if($R[0] == '#'){
			list($R, $G, $B) = $this->_hex2rbg($R);
		}
		return parent::SetHeaderCellsFillColor($R, $G, $B);
	}

	/**
	 * Overload to allow HEX color
	 * @see TCPDF_EasyTable::SetCellFontColor()
	 */
	function SetCellFontColor($R, $G=-1, $B=-1){
		if($R[0] == '#'){
			list($R, $G, $B) = $this->_hex2rbg($R);
		}
		return parent::SetCellFontColor($R, $G, $B);
	}

	# HEX to RGB
	function _hex2rbg($hex){
		$hex = substr($hex, 1);
		if(strlen($hex) == 6){
			list($col1, $col2, $col3) = array($hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]);
		} elseif(strlen($hex) == 3) {
			list($col1, $col2, $col3) = array($hex[0].$hex[0], $hex[1].$hex[1], $hex[2].$hex[2]);
		} else {
			return false;
		}
		return array(hexdec($col1), hexdec($col2), hexdec($col3));
	}

	# pixel -> millimeter in 72 dpi
	function _px2mm($px){
		return $px*25.4/72;
	}
}