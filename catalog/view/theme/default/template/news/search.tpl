<section class="blog-page">
<div class="wrap">
<!-- 	<h3 class="title"><?php echo $text_critea; ?></h3>
	  <div class="content">
	    <p><?php echo $entry_search; ?>
	      <?php if ($filter_name) { ?>
	      <input type="text" name="filter_artname" value="<?php echo $filter_name; ?>" />
	      <?php } else { ?>
	      <input type="text" name="filter_artname" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
	      <?php } ?>
	      <select name="filter_category_id">
	        <option value="0"><?php echo $text_category; ?></option>
	        <?php foreach ($categories as $category_1) { ?>
	        <?php if ($category_1['category_id'] == $filter_category_id) { ?>
	        <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
	        <?php } else { ?>
	        <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
	        <?php } ?>
	        <?php foreach ($category_1['children'] as $category_2) { ?>
	        <?php if ($category_2['category_id'] == $filter_category_id) { ?>
	        <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
	        <?php } else { ?>
	        <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
	        <?php } ?>
	        <?php foreach ($category_2['children'] as $category_3) { ?>
	        <?php if ($category_3['category_id'] == $filter_category_id) { ?>
	        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
	        <?php } else { ?>
	        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
	        <?php } ?>
	        <?php } ?>
	        <?php } ?>
	        <?php } ?>
	      </select>
	      <?php if ($filter_sub_category) { ?>
	      <input type="checkbox" name="filter_sub_category" value="1" id="sub_category" checked="checked" />
	      <?php } else { ?>
	      <input type="checkbox" name="filter_sub_category" value="1" id="sub_category" />
	      <?php } ?>
	      <label for="sub_category"><?php echo $text_sub_category; ?></label>
	    </p>
	    <?php if ($filter_description) { ?>
	    <input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
	    <?php } else { ?>
	    <input type="checkbox" name="filter_description" value="1" id="description" />
	    <?php } ?>
	    <label for="description"><?php echo $entry_description; ?></label>
	  </div>
	  <div class="buttons">
	    <div class="right"><a id="button-search" class="button"><span><?php echo $button_search; ?></span></a></div>
	  </div>
	  <h2><?php echo $text_search; ?></h2> -->
	  <?php if ($article) { ?>
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
	  <?php } else { ?>
	  <div class="content"><?php echo $text_empty; ?></div>
	  <?php }?>
	<script type="text/javascript"><!--
	$('#content input[name=\'filter_artname\']').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#button-search').trigger('click');
		}
	});

	$('#button-search').bind('click', function() {
		url = 'index.php?route=news/search';
		
		var filter_artname = $('#content input[name=\'filter_artname\']').attr('value');
		
		if (filter_artname) {
			url += '&filter_artname=' + encodeURIComponent(filter_artname);
		}

		var filter_category_id = $('#content select[name=\'filter_category_id\']').attr('value');
		
		if (filter_category_id > 0) {
			url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
		}
		
		var filter_sub_category = $('#content input[name=\'filter_sub_category\']:checked').attr('value');
		
		if (filter_sub_category) {
			url += '&filter_sub_category=true';
		}
			
		var filter_description = $('#content input[name=\'filter_description\']:checked').attr('value');
		
		if (filter_description) {
			url += '&filter_description=true';
		}

		location = url;
	});
	//--></script> 
	<script type="text/javascript"><!--
	$(document).ready(function() {
		$('img.article-image').each(function(index, element) {
			var articleWidth = $(this).parent().parent().width() * 0.7;
			var imageWidth = $(this).width() + 10;
			if (imageWidth >= articleWidth) {
				$(this).attr("align","center");
				$(this).parent().addClass('bigimagein');
			}
		});
	});
	//--></script> 
	<?php if ($disqus_status) { ?>
	<script type="text/javascript">
	var disqus_shortname = '<?php echo $disqus_sname; ?>';
	(function () {
	var s = document.createElement('script'); s.async = true;
	s.type = 'text/javascript';
	s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
	(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
	}());
	</script>
	<?php } ?>
	<?php if ($fbcom_status) { ?>
	<script type="text/javascript">
	      window.fbAsyncInit = function() {
	        FB.init({
	          appId      : '<?php echo $fbcom_appid; ?>',
			  status     : true,
	          xfbml      : true,
			  version    : 'v2.0'
	        });
	      };

	      (function(d, s, id){
	         var js, fjs = d.getElementsByTagName(s)[0];
	         if (d.getElementById(id)) {return;}
	         js = d.createElement(s); js.id = id;
	         js.src = "//connect.facebook.net/en_US/sdk.js";
	         fjs.parentNode.insertBefore(js, fjs);
	       }(document, 'script', 'facebook-jssdk'));
	</script>
	<?php } ?>
</div>
</div>
