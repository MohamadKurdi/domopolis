<?php echo $header; ?>

<?php include($this->checkTemplate(dirname(FILE),'/structured_manufacturer/head.tpl'); ?>

<section class="blog-page">
  <div class="wrap">
     
      <?php if ($is_author) { ?>
          <?php if ($author_image || $author_desc) { ?>
              <div class="category-info">
                  <?php if ($author_image) { ?>
                      <div class="image"><img loading="lazy" src="<?php echo $author_image; ?>" alt="<?php echo $author; ?>"/></div>
                  <?php } ?>
                  <?php if ($author_desc) { ?>
                      <?php echo $author_desc; ?>
                  <?php } ?>
              </div>
          <?php } ?>
      <?php } ?>
      <?php if ($is_category) { ?>
          <?php if ($thumb || $description) { ?>
              <div class="category-info">
                  <?php if ($thumb) { ?>
                      <div class="image"><img loading="lazy" src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/></div>
                  <?php } ?>
                  <?php if ($description) { ?>
                      <?php echo $description; ?>
                  <?php } ?>
              </div>
          <?php } ?>
          <?php if ($ncategories) { ?>
              <h2><?php echo $text_refine; ?></h2>
              <div class="category-list" style="border-bottom: 2px solid #eee;">
                  <?php if (count($ncategories) <= 5) { ?>
                      <ul>
                          <?php foreach ($ncategories as $ncategory) { ?>
                              <li><a href="<?php echo $ncategory['href']; ?>"><?php echo $ncategory['name']; ?></a></li>
                          <?php } ?>
                      </ul>
                  <?php } else { ?>
                      <?php for ($i = 0; $i < count($ncategories);) { ?>
                          <ul>
                              <?php $j = $i + ceil(count($ncategories) / 4); ?>
                              <?php for (; $i < $j; $i++) { ?>
                                  <?php if (isset($ncategories[$i])) { ?>
                                      <li>
                                          <a href="<?php echo $ncategories[$i]['href']; ?>"><?php echo $ncategories[$i]['name']; ?></a>
                                      </li>
                                  <?php } ?>
                              <?php } ?>
                          </ul>
                      <?php } ?>
                  <?php } ?>
              </div>
          <?php } ?>
      <?php } ?>
      <?php if ($article) { ?>
         <?php if ($ntags && count($ntags) > 1) { ?>
              <!--tags-->
          <div class="tags">
              <div class="tags__row">
                    <span><?php echo $text_tags; ?></span>
                    <?php foreach($ntags as $ntag) { ?>
                    <a class="tags__link" href="<?php echo $ntag['href']; ?>"><?php echo $ntag['ntag']; ?></a>
                    <?php } ?>
                  </div>
              </div>
          <?php } ?>
     <div class="news-block">
          <?php foreach ($article as $articles) { ?>
              <div class="news-items">
                  <div class="news__photo">
                    <?php if ($articles['thumb']) { ?>
                      <img src="<?php echo $articles['thumb']; ?>" title="<?php echo $articles['name']; ?>" alt="<?php echo $articles['name']; ?>" /> 
                    <?php } ?>
                  </div>  
                  <?php if ($articles['name']) { ?>             
                    <div class="news__title">
                <a href="<?php echo $articles['href']; ?>"><?php echo $articles['name']; ?></a>
                    </div>    
                  <?php } ?>          
            <?php if ($articles['description']) { ?>
              <div class="news__desc">
                <?php echo $articles['description']; ?>
              </div>
                  <?php } ?>
                  <?php if ($articles['date_added']) { ?>
                  <div class="news__date"><?php echo $articles['date_added']; ?></div>
                  <?php } ?>
              </div>
          <?php } ?>
      </div>
      <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</section>
<script type="text/javascript"><!--
    $(document).ready(function () {
        $('img.article-image').each(function (index, element) {
            var articleWidth = $(this).parent().parent().width() * 0.7;
            var imageWidth = $(this).width() + 10;
            if (imageWidth >= articleWidth) {
                $(this).attr("align", "center");
                $(this).parent().addClass('bigimagein');
            }
        });
    });
    //--></script>
<?php } ?>
<?php if ($is_category) { ?>
    <?php if (!$ncategories && !$article) { ?>
        <div class="content"><?php echo $text_empty; ?></div>
    <?php } ?>
<?php } else { ?>
    <?php if (!$article) { ?>
        <div class="content"><?php echo $text_empty; ?></div>
    <?php } ?>
<?php } ?>


<?php echo $footer; ?>