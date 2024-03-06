
<div class="form-horizontal ft-contacts"> <div class="row-fluid">
		 <div class="span2"><h4><?php echo $text_slider; ?></h4></div>
		 <div class="span10">
			<?php 
				$ar 	= array( 'Nivo Slider' => false, 'Owl-carousel' => true);
				$valueq 	= $slider_status;
				$name	= 'slider_status';
				$id		= 'slider_status';
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
		</div>
	</div> <hr />
    <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_effect; ?></div>
		 <div class="span10">
			<?php 
				$ar 	= array( 
							'SELECT',
							'sliceDown',
							'sliceDownLeft',
							'sliceUp',
							'sliceUpLeft',
							'sliceUpDown',
							'sliceUpDownLeft',
							'fold',
							'fade',
							'random',
							'slideInRight',
							'slideInLeft',
							'boxRandom',
							'boxRain',
							'boxRainReverse',
							'boxRainGrow',
							'boxRainGrowReverse'
							); 
				$valueq 	= $slider_effect; $name	= 'slider_effect'; $id = 'slider_effect';
			?>
			
				<select id="<?php echo $id; ?>" name="<?php echo $name; ?>">
					<?php foreach ($ar as $key) { ?>
						<?php ($key ==  $valueq) ? $selected = 'selected' : $selected=''; ?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			
		</div>
	</div> <hr />
    <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_speed; ?></div>
		 <div class="span10">
			<?php 
				$valueq = $slider_animSpeed; $name = 'slider_animSpeed'; $id	= 'slider_animSpeed'; $placeholder	= '';
			?>
			<input type="text" placeholder="<?php echo $placeholder; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>" class="shortfield">
		</div>
	</div> <hr />
    <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_pause; ?></div>
		 <div class="span10">
			<?php 
				$valueq = $slider_pauseTime; $name = 'slider_pauseTime'; $id	= 'slider_pauseTime'; $placeholder	= '';
			?>
			<input type="text" placeholder="<?php echo $placeholder; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>" class="shortfield">
		</div>
	</div><hr />
    <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_arrows; ?></div>
		 <div class="span10">
			<?php 
				$ar 	= array( 'Yes' => 0, 'No' => 1);
				$valueq 	= $slider_directionNav;
				$name	= 'slider_directionNav';
				$id		= 'directionNav';
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
		</div>
	</div><hr />
     <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_navigation; ?></div>
		 <div class="span10">
			<?php 
				$ar 	= array( 'Yes' => 0, 'No' => 1); 
				$valueq 	= $slider_controlNav;
				$name	= 'slider_controlNav';
				$id		= 'controlNav';
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
		</div>
	</div> <hr />
    <div class="row-fluid">
		 <div class="span2"><?php echo $text_slider_hover; ?></div>
		 <div class="span10">
			<?php 
				$ar 	= array( 'Yes' => 0, 'No' => 1); 
				$valueq 	= $slider_pauseOnHover;
				$name	= 'slider_pauseOnHover';
				$id		= 'pauseOnHover';
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
		</div>
	</div>  
  
  
  
  
  
  
  
</div>