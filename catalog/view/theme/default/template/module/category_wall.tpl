<div id="categoryWall-<?php echo $module; ?>" class="<?php echo $box_class; ?>">
  <?php if ($title_status) { ?>
  <<?php echo $tag; ?> class="<?php echo $heading_class; ?>"><?php echo $heading_title; ?></<?php echo $tag; ?>>
  <?php } ?>
  <div class="<?php echo $content_class; ?>">
    <ul class="cat-wall sc-<?php echo $design; ?>">
      <?php foreach ($categories as $category) { ?>
      <li class="item-id-<?php echo $category['category_id']; ?><?php echo $category['active'] ? ' active' : ''; ?>"> <a href="<?php echo $category['href']; ?>" class="<?php echo ($category['children'] ? 'i-toggle' : ''); ?> <?php echo $category['active'] ? 'active' : ''; ?>">
        <div class="sc-image"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>"></div>
        <div class="sc-name"><?php echo $category['name']; ?></div>
        </a>
        <?php if ($category['children']) { ?>
        <ul class="sc-<?php echo $view; ?>-view">
          <div>
            <?php if($design == 'accordion' || $design == 'flyout'){ ?>
            <?php if ($description_status && $category['description']) { ?>
            <div class="sc-title"><a href="<?php echo $category['href']; ?>"><?php echo $category['title']; ?></a></div>
            <div class="sc-descr"><?php echo $category['description']; ?></div>
            <?php } ?>
            <?php } ?>
            <?php foreach ($category['children'] as $child) { ?>
            <li class="item-id-<?php echo $child['category_id']; ?><?php echo $child['active'] ? ' active' : ''; ?>">
              <?php if($design == 'fixed'){ ?>
              <a href="<?php echo $child['href']; ?>" class="<?php echo $child['active'] ? 'active' : ''; ?>"><?php echo $child['name']; ?></a>
              <?php } else { ?>
              <?php if($view == 'list'){ ?>
              <div class="sc-name"><a href="<?php echo $child['href']; ?>" class="<?php echo $child['active'] ? 'active' : ''; ?>"><?php echo $child['name']; ?></a></div>
              <?php if($sub_image){ ?>
              <div class="sc-image"><img src="<?php echo $child['thumb']; ?>" alt="<?php echo $child['name']; ?>"></div>
              <?php } ?>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>" class="<?php echo ($child['child2_id'] ? 'i-toggle' : ''); ?> <?php echo $child['active'] ? 'active' : ''; ?>">
              <div class="sc-image"><img src="<?php echo $child['thumb']; ?>" alt="<?php echo $child['name']; ?>"></div>
              <div class="sc-name"><?php echo $child['name']; ?></div>
              </a>
              <?php } ?>
              <?php if($child['child2_id']){ ?>
              <ul class="sc-<?php echo $view; ?>">
                <div>
                  <?php if ($description_status && $child['description']) { ?>
                  <div class="sc-title"><a href="<?php echo $child['href']; ?>"><?php echo $child['title']; ?></a></div>
                  <div class="sc-descr"><?php echo $child['description']; ?></div>
                  <?php } ?>
                  <?php foreach ($child['child2_id'] as $child2) { ?>
                  <li class="item-id-<?php echo $child2['category_id']; ?><?php echo $child2['active'] ? ' active' : ''; ?>">
                    <?php if($view == 'list'){ ?>
                    <a href="<?php echo $child2['href']; ?>" class="<?php echo $child2['active'] ? 'active' : ''; ?>"><?php echo $child2['name']; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $child2['href']; ?>" class="<?php echo $child2['active'] ? 'active' : ''; ?>">
                    <div class="sc-image"><img src="<?php echo $child2['thumb']; ?>" alt="<?php echo $child2['name']; ?>"></div>
                    <div class="sc-name"><?php echo $child2['name']; ?></div>
                    </a>
                    <?php } ?>
                  </li>
                  <?php } ?>
                </div>
              </ul>
              <?php } ?>
              <?php } ?>
            </li>
            <?php } ?>
          </div>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <li class="brand-id-<?php echo $manufacturer['manufacturer_id']; ?><?php echo ($manufacturer['manufacturer_id'] == $manufacturer_id ? ' active' : ''); ?>"><a href="<?php echo $manufacturer['href']; ?>" class="<?php echo ($manufacturer['manufacturer_id'] == $manufacturer_id ? 'active' : ''); ?>">
        <div class="sc-image"><img src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>"></div>
        <div class="sc-name"><?php echo $manufacturer['name']; ?></div>
        </a> </a></li>
      <?php } ?>
      <?php foreach ($custom_items as $custom_item) { ?>
      <li class="citem-id-<?php echo $custom_item['ciid']; ?>"><a href="<?php echo $custom_item['href']; ?>">
        <div class="sc-image"><img src="<?php echo $custom_item['thumb']; ?>" alt="<?php echo $custom_item['item_title']; ?>"></div>
        <div class="sc-name"><?php echo $custom_item['item_title']; ?></div>
        </a></li>
      <?php } ?>
    </ul>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
  $('#categoryWall-<?php echo $module; ?> ul.cat-wall').each(function() {
    var items = $(this).children('li');
    items.css('width',(100/<?php echo $parent_column; ?>)-1 + '%');
    for (var i = 0; i < items.length; i+=<?php echo $parent_column; ?>) {
      items.slice(i, i+<?php echo $parent_column; ?>).wrapAll('<div class="sc-items-row"></div>');
    }
  });

  $('#categoryWall-<?php echo $module; ?> ul.cat-wall ul').not('ul.sc-fixed ul').each(function() {
    var items = $(this).not('ul.sc-list').find('>div').children('li');
    items.css('width',(100/<?php echo $child_column; ?>)-1 + '%');
    for (var i = 0; i < items.length; i+=<?php echo $child_column; ?>) {
      items.slice(i, i+<?php echo $child_column; ?>).wrapAll('<div class="sc-items-row"></div>');
    }
  });

  $('#categoryWall-<?php echo $module; ?> .sc-items-row').each(function() {
    $(this).after('<li class="sc-sub-row"></li>');
  });

  $('#categoryWall-<?php echo $module; ?> .i-toggle').each(function(i) {
    $(this).addClass('sc-sublink-'+(i+1));
    $(this).next().addClass('sc-subcont-'+(i+1));

    $('#categoryWall-<?php echo $module; ?> .sc-sublink-'+(i+1)).each(function() {
      var sub = $(this).next().not('.sc-list');
      $(this).closest('.sc-items-row').next().append(sub);
    });

    <?php if ($design == 'flyout') { ?>
      var timeout;
      $('#categoryWall-<?php echo $module; ?> .sc-sublink-'+(i+1)).hover(      
        function() {
          clearTimeout(timeout);
          $(this).addClass('sc-active').parent().siblings().find('>a').removeClass('sc-active');
          $(this).closest('.sc-items-row').next().find('.sc-subcont-'+(i+1)).addClass('open').fadeIn(100).siblings().removeClass('open').delay(50).fadeOut(100);
        },
        function() {
          timeout = setTimeout(function() {
            $('#categoryWall-<?php echo $module; ?> .sc-sublink-'+(i+1)).removeClass('sc-active').closest('.sc-items-row').next().find('.sc-subcont-'+(i+1)).removeClass('open').fadeOut(100);
          }, 500);        
        }
        );

      $('#categoryWall-<?php echo $module; ?> .sc-subcont-'+(i+1)).hover(
        function() {
          clearTimeout(timeout);
          $(this).fadeIn(100);
        },
        function() {
          timeout = setTimeout(function() {
            $('#categoryWall-<?php echo $module; ?> .sc-sublink-'+(i+1)).removeClass('sc-active').closest('.sc-items-row').next().find('.sc-subcont-'+(i+1)).removeClass('open').fadeOut(100);
          }, 500);
        }
        );
      <?php } ?>
      
      <?php if ($design == 'accordion') { ?>
        $('#categoryWall-<?php echo $module; ?> .sc-sublink-'+(i+1)).click(function() {
          $(this).closest('.sc-items-row').next().find('.sc-subcont-'+(i+1)).toggleClass('open').animate({'opacity': 'toggle', 'height': 'toggle'}, 350).parent().find('ul.open').not('.sc-subcont-'+(i+1)).removeClass('open').animate({'opacity': 'toggle', 'height': 'toggle'}, 350);      
          $(this).closest('.sc-items-row').next().siblings().find('ul.open').removeClass('open').animate({'opacity': 'toggle', 'height': 'toggle'}, 350);
          $(this).toggleClass('sc-active').closest('ul').find('.sc-active').not(this).toggleClass('sc-active');
          return false;
        });
      <?php } ?>
  });
  $('#categoryWall-<?php echo $module; ?> ul.sc-list').prev('.sc-image').addClass('sc-image-left');
});

$(window).load(function() {
  $('#categoryWall-<?php echo $module; ?> .cat-wall > .sc-items-row').each(function() {
    var el = $(this).children('li').find('> a');    
    var max = 0;
    $(el).each(function() {
      max = Math.max(max, $(this).height());
    }).animate({'height': max}, 100);
  });

  $('#categoryWall-<?php echo $module; ?> ul ul').show();
  $('#categoryWall-<?php echo $module; ?> ul ul > div > .sc-items-row').each(function() {
    var el = $(this).children('li').find('> a');    
    var max = 0;
    $(el).each(function() {
      max = Math.max(max, $(this).outerHeight());
    }).outerHeight(max);
  });
  $('#categoryWall-<?php echo $module; ?> ul ul').not('ul.sc-list, ul.sc-fixed ul').hide();
});
//--></script>