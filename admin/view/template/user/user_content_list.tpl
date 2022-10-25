<?php echo $header; ?>
<style>
	a.button.active { color: #fff !important;background-color: #6A6A6A;text-decoration: none !important; }
	tr.hovered td {background-color:#faf9f1 !important;}
	tr.blue:hover td {background:#99CCFF !important; color:#FFF !important;}
	td.td_alert {background:#FF99CC !important;}
	.list tr td {padding: 5px 10px;}
	.list tr td {font-size: 16px;}
	.list thead tr td {font-size: 18px; padding: 8px 10px;}
	.list thead td.green, .list td.green, .green{color: #00AD07}
	.list thead td.orange, .list td.orange, .orange{color: #FF7815}
	.list thead td.red, .list td.red, .red{color: #CF4A61}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1>Учет работы контентов</h1>				  					
		</div>
		<div class="clear:both"></div>
		<div class="content" style="padding:5px;">
			<div> 
				<? foreach ($periods as $key => $period) { ?>
					<a class="button <? if ($current_period == $key) { ?>active<? } ?>" href="<? echo $period['href']; ?>" style="margin-right:10px; margin-bottom:5px;"><? echo $period['name']; ?></a>
				<? } ?>
			</div>
			<div style="margin-top:10px;"> 					
				<table class="list">		
					<thead>
						<tr>
							<td></td>
							<td class="center green"><i class="fa fa-edit"></i> Товары</td>
							<td class="center orange"><i class="fa fa-plus"></i> Товары</td>
							<td class="center red"><i class="fa fa-minus"></i> Товары</td>
							<td class="center">Категории</td>
							<td class="center">Бренды</td>
							<td class="center">Коллекции</td>
							<td class="center">Инфостраницы</td>
							<td class="center">Лендинги</td>
							<td class="center">Атрибуты</td>
							<td class="center">Опции</td>							
						</tr>
					</thead>			
					<? foreach ($users as $user) { ?>
						
						<tr>
							<td>
								<b><? echo $user['name']; ?></b>
							</td>
							<td class="right green">
								<?php if (!empty($user['product']['edit'])) { ?>
									<?php echo $user['product']['edit']; ?>
								<?php } ?>
							</td>
							<td class="right orange">
								<?php if (!empty($user['product']['add'])) { ?>
									<?php echo $user['product']['add']; ?>
								<?php } ?>
							</td>
							<td class="right red">
								<?php if (!empty($user['product']['delete'])) { ?>
									<?php echo $user['product']['delete']; ?>
								<?php } ?>
							</td>

							<?php foreach (['category', 'manufacturer', 'collection', 'information', 'landingpage', 'attribute', 'option'] as $idx) { ?>
							<td class="right">
								<?php if (!empty($user[$idx]['edit'])) { ?>
									<span class="green"><i class="fa fa-edit"></i><?php echo $user[$idx]['edit']; ?></span>&nbsp;&nbsp;
								<?php } ?>

								<?php if (!empty($user[$idx]['add'])) { ?>
									<span class="orange"><i class="fa fa-plus"></i><?php echo $user[$idx]['add']; ?></span>&nbsp;&nbsp;
								<?php } ?>

								<?php if (!empty($user[$idx]['delete'])) { ?>
									<span class="red"><i class="fa fa-minus"></i><?php echo $user[$idx]['delete']; ?></span>
								<?php } ?>
								
							</td>
							<? } ?>
						</tr>

					<? } ?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?> 	