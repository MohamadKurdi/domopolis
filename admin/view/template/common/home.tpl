<?php echo $header; ?>
<style>
	.tooltip {
	position: absolute;
	z-index: 1070;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-style: normal;
	font-weight: normal;
	letter-spacing: normal;
	line-break: auto;
	line-height: 1.42857;
	text-align: left;
	text-align: start;
	text-decoration: none;
	text-shadow: none;
	text-transform: none;
	white-space: normal;
	word-break: normal;
	word-spacing: normal;
	word-wrap: normal;
	font-size: 11px;
	opacity: 0;
	filter: alpha(opacity=0); }
	.tooltip.in {
    opacity: 0.9;
    filter: alpha(opacity=90); }
	.tooltip.top {
    margin-top: -3px;
    padding: 5px 0; }
	.tooltip.right {
    margin-left: 3px;
    padding: 0 5px; }
	.tooltip.bottom {
    margin-top: 3px;
    padding: 5px 0; }
	.tooltip.left {
    margin-left: -3px;
    padding: 0 5px; }
	
	.tooltip-inner {
	max-width: 200px;
	padding: 3px 8px;
	color: #fff;
	text-align: center;
	background-color: #000;
	border-radius: 3px; }
	
	.tooltip-arrow {
	position: absolute;
	width: 0;
	height: 0;
	border-color: transparent;
	border-style: solid; }
	
	.tooltip.top .tooltip-arrow {
	bottom: 0;
	left: 50%;
	margin-left: -5px;
	border-width: 5px 5px 0;
	border-top-color: #000; }
	.tooltip.top-left .tooltip-arrow {
    bottom: 0;
    right: 5px;
    margin-bottom: -5px;
    border-width: 5px 5px 0;
    border-top-color: #000; }
	.tooltip.top-right .tooltip-arrow {
    bottom: 0;
    left: 5px;
    margin-bottom: -5px;
    border-width: 5px 5px 0;
    border-top-color: #000; }
	.tooltip.right .tooltip-arrow {
    top: 50%;
    left: 0;
    margin-top: -5px;
    border-width: 5px 5px 5px 0;
    border-right-color: #000; }
	.tooltip.left .tooltip-arrow {
    top: 50%;
    right: 0;
    margin-top: -5px;
    border-width: 5px 0 5px 5px;
    border-left-color: #000; }
	.tooltip.bottom .tooltip-arrow {
    top: 0;
    left: 50%;
    margin-left: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.tooltip.bottom-left .tooltip-arrow {
    top: 0;
    right: 5px;
    margin-top: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.tooltip.bottom-right .tooltip-arrow {
    top: 0;
    left: 5px;
    margin-top: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.list.big tbody td.sum{font-size:9px !important;}
	.list.big thead td {font-size: 13px !important;}
	.list.big tbody td.discount {font-size: 10px !important;}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">   
    <div class="content">
	  <div id="template_bygroup">
	  <?
		 if ($template_prefix = $this->user->getTemplatePrefix()){
			if (file_exists(DIR_TEMPLATE . 'common/homes/home'.$template_prefix.'.tpl')){
				include(DIR_TEMPLATE . 'common/homes/home'.$template_prefix.'.tpl');				
			}
		 }
		?>
	  </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>