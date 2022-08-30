<? echo $config_gtm_body; ?>
<? echo $config_fb_pixel_body; ?>

<?php if ($config_vk_enable_pixel) { ?>
<? echo $config_vk_pixel_body; ?>
<?php } ?>

<?php if (!empty($general_minified_css_uri)) { ?>
<link href="<? echo $general_minified_css_uri; ?>" rel="stylesheet" media="screen" />
<?php } ?>

<?php if (!empty($added_minified_css_uri)) { ?>
<link href="<? echo $added_minified_css_uri; ?>" rel="stylesheet" media="screen" />
<?php } ?>


<?php if (!empty($general_minified_js_uri)) { ?>
<script src="<? echo $general_minified_js_uri; ?>"></script>
<?php } ?>

<?php if (!empty($added_minified_js_uri)) { ?>
<script src="<? echo $added_minified_js_uri; ?>"></script>
<?php } ?>

<?php  foreach ($incompatible_scripts as $incompatible_script) { ?>
<script src="<?php echo $incompatible_script; ?>?rnd=<?php echo mt_rand(0, 1000); ?>"></script>
<?php } ?>

<?php  foreach ($added_but_excluded_scripts as $added_but_excluded_script) { ?>
<script src="<?php echo $added_but_excluded_script; ?>?rnd=<?php echo mt_rand(0, 1000); ?>"></script>
<?php } ?>