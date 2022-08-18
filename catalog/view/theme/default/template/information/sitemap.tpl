<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style type="text/css">
#content-sitemap .sitemap-info{
  flex-direction: column;
}
  #content-sitemap .sitemap-info .left,
  #content-sitemap .sitemap-info .right {
      width: 100%;
  }
  .cat_1{
    margin-bottom: 20px;
  }
  .cat_1 span.text,
  .cat_1 > li > a{
    border-bottom: .1rem solid #e5e5e5;
    padding-bottom: 1.4rem;
    font-size: 20px;
    font-weight: 500;
    
    color: #50a781;
    margin-bottom: 25px;
    padding-bottom: 10px;
    display: block;
  }
  .cat_1 span.text{
    color: #333;
  }
  .cat_2{
    columns: 3;
    padding-left: 0 !important;
  }
  .cat_2 > li > a{
    color: #50a781;
    border-bottom: 1px dashed;
    margin-bottom: 6px;
    display: inline-block;
    font-weight: 500;
  }
  .cat_2 > li > a:hover{
    border-bottom-color: transparent;
  }
  .cat_3{
    padding-left: 0 !important;
  }
  @media screen  and (max-width: 920px){
     .cat_2{
        columns: 2;
      }   
  }
  @media screen  and (max-width: 568px){
     .cat_2{
        columns: 1;
      }   
  }
</style>
<?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
<div id="content-sitemap"><?php echo $content_top; ?>
  <div class="wrap">
  <div class="sitemap-info">
    <div class="left">
      <?php foreach ($categories as $category_1) { ?>
      <ul class="cat_1">        
        <li><a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <?php if ($category_1['children']) { ?>
          <ul class="cat_2">
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
              <?php if ($category_2['children']) { ?>
              <ul  class="cat_3">
                <?php foreach ($category_2['children'] as $category_3) { ?>
                  <li><a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        
      </ul>
      <?php } ?>
    </div>
    <div class="right">
      <ul class="cat_1">
        <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
          <ul class="cat_2">
            <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
            <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
            <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
            <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a></li>
        <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
        <li><a href="<?php echo $search; ?>"><?php echo $text_search; ?></a></li>

      </ul>
      <ul  class="cat_1">
        <li>
            <span class="text"><?php echo $text_information; ?></span>
            <ul class="cat_2">
            <?php foreach ($informations as $information) { ?>
            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
            <?php } ?>
            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <?php echo $content_bottom; ?>
  </div>
</div>
<?php echo $footer; ?>