<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1>Учет рабочего времени и действий</h1>				  					
		</div>
		<div class="clear:both"></div>
		<div class="content" style="padding:10px;">
			
		</div>
	</div>
</div>
<?php echo $footer; ?> 	