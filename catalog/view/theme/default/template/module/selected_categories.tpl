<div class="box">
  <div class="box-heading">Популярные категории</div>
  <div class="box-content">
    <ul class="box-selected-category">
	
	  <?php foreach ($categories as $category) { ?>
		  <li>
		   <div>
			<a href="<?php echo $category['href']; ?>"><span class="sel_thumbn"><img src="<?php echo $category['thumb']; ?>" /></span><span><?php echo $category['name']; ?></span></a>
		   </div>
		  </li>
	  <?php } ?>
	  </div>
    </ul>

  <div style="clear:both"></div>
</div>
<style>
.box-selected-category {
	padding: 0;
	margin: 0;
	line-height: 14px;
}
.box-selected-category li {
	 display: inline-block;
	 width: 16.5%;
	 vertical-align: top;
	 float: left;
}
.sel_thumbn {
	display: block;
	width: 100%;
	margin-bottom: 10px;
}
.box-selected-category li > div {
    padding: 10px 5px;
    background: #fff;
    text-align: center;
    margin: 5px;
	height: 140px;
	border: 1px solid #ddd;
	line-height: 14px;
	}
.box-selected-category li > div a{	
	text-decoration: none;
	line-height: 14px;
    font-size: 12px;
	}
.box-selected-category li > div:hover a {	
	text-decoration: underline;
	}

	
</style>