<?php include($this->checkTemplate(dirname(__FILE__),'/../../../structured/breadcrumbs.tpl')); ?>

<!--logo-brand-->

<div class="logo-brand" <?php if (!$banner){ ?> style="height: auto;"<?php } ?>>
  <div class="wrap">
    <div class="main_baner_brand" style="background-image: url('<?php echo $banner; ?>');<?php if ($banner) { ?> padding: 0 25px; <?php } else { ?> height: auto; <?php } ?>">
      <div class="logo-box">
         <a href="<?php echo $main_link; ?>"><img src="<?php echo $manufacturer_logo; ?>" alt="<?php echo $manufacturer_name; ?>"></a>
      </div>
    </div>
  </div>
</div>

<!--/logo-brand-->

<!--links-->
<div class="links">
  <div class="wrap">
    <div class="links-group page-brand">
      <?php if ($collections_link): ?>
        <a class="btn<?php if ($active_button == 'collections') { ?> active<?php } ?>" href="<?php echo $collections_link; ?>"><?php echo $text_brands_head_collections; ?></a>
      <?php endif ?>

      <?php if ($newproducts_link): ?>
        <a class="btn<?php if ($active_button == 'newproducts') { ?> active<?php } ?>" href="<?php echo $newproducts_link; ?>"><?php echo $text_brands_head_new; ?></a>   
      <?php endif ?>

      <?php if ($special_link): ?>
        <a class="btn<?php if ($active_button == 'special') { ?> active<?php } ?>" href="<?php echo $special_link; ?>"><?php echo $text_brands_head_actions; ?></a>
      <?php endif ?>

      <?php if ($articles_link): ?>
        <a class="btn<?php if ($active_button == 'articles') { ?> active<?php } ?>" href="<?php echo $articles_link; ?>"><?php echo $text_brands_head_articles; ?></a>
      <?php endif ?>

      <?php if ($categories_link): ?>
        <a class="btn<?php if ($active_button == 'categories') { ?> active<?php } ?>" href="<?php echo $categories_link; ?>"><?php echo $text_brands_head_categories; ?></a>
      <?php endif ?>          
      
    </div>
  </div>
</div>
<!--/links-->
