<?php
        if (isset($shop_rating) && $shop_rating) {
?> 
<span itemscope itemtype="http://schema.org/Organization">	
  <meta itemprop="name" content="<? echo $name; ?>" />  
  <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> <meta itemprop="reviewCount" content="<?php
            echo $shop_rating_count;
?>"> <meta itemprop="ratingValue" content="<?php
            echo $shop_rating;
?>"> <meta itemprop="bestRating" content="5"> <meta itemprop="worstRating" content="1"> </span> 
</span>

<?php  } ?>