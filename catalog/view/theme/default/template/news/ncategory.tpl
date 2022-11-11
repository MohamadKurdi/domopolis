<section class="blog-page">
  <div class="wrap">
     
      <?php if ($is_author) { ?>
          <?php if ($author_image || $author_desc) { ?>
              <div class="category-info">
                  <?php if ($author_image) { ?>
                      <div class="image"><img src="<?php echo $author_image; ?>" alt="<?php echo $author; ?>"/></div>
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
                      <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/></div>
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

      <?php if ($_ntags && count($_ntags) > 1) { ?>
          <div class="tags">
            <div class="tags__row" data-count-item-tags="6">
              <?php foreach($_ntags as $ntag) { ?>
                <a class="tags__link" href="<?php echo $ntag['href']; ?>"><?php echo $ntag['ntag']; ?></a>
              <?php } ?>
            </div>
          </div>
      <?php } ?>

      <?php if ($article) { ?>
     <div class="news-block">
          <?php foreach ($article as $articles) { ?>
              <div class="news-items">
                  <div class="news__photo">
                    <?php if ($articles['thumb']) { ?>
                      <a href="<?php echo $articles['href']; ?>">
                        <img src="<?php echo $articles['thumb']; ?>" title="<?php echo $articles['name']; ?>" alt="<?php echo $articles['name']; ?>" /> 
                      </a>   
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
                  <div class="news__date"><?php echo $articles['date_added']; ?><div class="total-article-views" style="float: right;"><i class="fas fa-eye"></i><span> <?php echo $articles['viewed']; ?></span></div></div>
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

        if ($(window).width() <= '768'){
            $('[data-count-item-tags]').each(function (index, value) {
              var dataCountItem = $(this).attr('data-count-item-tags');
              var countItem = $(this).children('a');
              var countItemLength = $(this).children('a').length;
              if (countItemLength > dataCountItem) {
                $(this).append('<div class="show-all-item" style="padding-left: 6px;">Показать все</div>');
                countItem.slice(dataCountItem, countItemLength).hide();
              }
            });

            $('[data-count-item-tags] .show-all-item').click(function () {
              $(this).closest('[data-count-item-tags]').children('a').slideDown(300);
              $(this).hide();
            });
        } 

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
<?php if ($disqus_status) { ?>
    <script type="text/javascript">
        var disqus_shortname = '<?php echo $disqus_sname; ?>';
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>
<?php } ?>
<?php if ($fbcom_status) { ?>
    <script type="text/javascript">
        window.fbAsyncInit = function () {
            FB.init({
                appId: '<?php echo $fbcom_appid; ?>',
                status: true,
                xfbml: true,
                version: 'v2.0'
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>
<?php } ?>