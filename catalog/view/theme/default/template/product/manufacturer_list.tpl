<?php echo $header; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>

<style type="text/css">
    .manufacturer-alphabet{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    box-shadow: 0 2px 20px 0 #bababa;
    padding: 10px 20px 10px 20px;
    margin-bottom: 30px !important;
    }
    .manufacturer-country{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    box-shadow: 0 2px 20px 0 #bababa;
    padding: 10px 20px 10px 20px;
    }
    .manufacturer-alphabet .name-block,
    .manufacturer-country .name-block{
    flex-basis: 17%;
    align-items: center;
    display: flex;
    }
    .manufacturer-alphabet .name-block{
    align-items: center;
    display: flex;
    } 
    .manufacturer-alphabet .name-block span,
    .manufacturer-country .name-block span{
    font-size: 18px;
    font-weight: 500; 
    }
    .manufacturer-alphabet .manufacturer-alphabet-block,
    .manufacturer-country .manufacturer-country-block{
    flex-basis: 83%;  
    padding: 0 15px;
    display: flex;
    flex-wrap: wrap;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item{
    display: flex;
    margin: 0 5px 0 0;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item a{
    align-items: center;
    border: 1px solid #eae9e8;
    padding: 6px 12px;
    text-decoration: none;
    cursor: pointer;
    transition: .3s ease-in-out;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item:hover{
    background: #51a881;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item:hover span{
    color: #fff;
    }
    .manufacturer-country .manufacturer-country-block{
        gap: 10px;
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item{
    /* flex-basis: 14%; */
    /* flex-basis: auto; */
    display: flex;
    flex-direction: row;
    align-items: center;
    border: 1px solid #eae9e8;
    
    margin: 0 5px 0 0;
    cursor: pointer;
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item a{
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 6px 12px;
    width: 100%;
    height: 100%;
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item:hover span{
    color: #51a881;
    }
/*     .manufacturer-country .manufacturer-country-block .manufacturer-country-item:nth-of-type(n+7){
    margin-top: 10px;
    }  */
    
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item span,
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item span{
    font-size: 14px;
    font-weight: 500;
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item img{
    max-width: 30px;
    max-height: 20px;
    margin-right: 10px;
    box-shadow: 0px 0px 5px #0000005e;
    }
    .manufacturer-country .show-all-item{
    display: flex;
    align-items: center;
    margin-left: 10px;
    }
    .manufacturer-country .show-all-item:after{
    content: '';
    width: 10px;
    height: 10px;
    background-size: cover;
    background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNjEycHgiIGhlaWdodD0iNjEycHgiIHZpZXdCb3g9IjAgMCA2MTIgNjEyIiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PGcgaWQ9Il94MzFfMF8zNF8iPjxnPjxwYXRoIGQ9Ik02MDQuNTAxLDEzNC43ODJjLTkuOTk5LTEwLjA1LTI2LjIyMi0xMC4wNS0zNi4yMjEsMEwzMDYuMDE0LDQyMi41NThMNDMuNzIxLDEzNC43ODJjLTkuOTk5LTEwLjA1LTI2LjIyMy0xMC4wNS0zNi4yMjIsMHMtOS45OTksMjYuMzUsMCwzNi4zOTlsMjc5LjEwMywzMDYuMjQxYzUuMzMxLDUuMzU3LDEyLjQyMiw3LjY1MiwxOS4zODYsNy4yOTZjNi45ODgsMC4zNTYsMTQuMDU1LTEuOTM5LDE5LjM4Ni03LjI5NmwyNzkuMTI4LTMwNi4yNjhDNjE0LjUsMTYxLjEwNiw2MTQuNSwxNDQuODMyLDYwNC41MDEsMTM0Ljc4MnoiLz48L2c+PC9nPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48L3N2Zz4=);
    margin-left: 6px;
    transform: rotate(-90deg);
    }
    .manufacturer-country, .manufacturer-alphabet{
    margin-bottom: 25px;
    }
    @media screen and (max-width: 560px){
    .manufacturer-country, .manufacturer-alphabet{
    flex-direction: column;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block, 
    .manufacturer-country .manufacturer-country-block,
    .manufacturer-country> div, 
    .manufacturer-alphabet > div{
    flex-basis: 100%;
    width: 100%;
    padding: 0
    }
    .manufacturer-alphabet .manufacturer-alphabet-block, 
    .manufacturer-country .manufacturer-country-block{
    margin-top: 15px
    }
    .manufacturer-alphabet .manufacturer-alphabet-block{
    display: grid;
    grid-template-columns:repeat(auto-fit,minmax(35px,1fr));
    gap: 10px; 
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item,
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item{
    margin: 0
    }
    .manufacturer-country .manufacturer-country-block{
    display: grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap: 10px; 
    }
    .manufacturer-country, 
    .manufacturer-alphabet{
    padding: 10px
    }
    .manufacturer-country .manufacturer-country-block .manufacturer-country-item span{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .manufacturer-alphabet .manufacturer-alphabet-block .manufacturer-alphabet-item a {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px 0;
    }
    }
</style>
<?php if ($this->config->get('config_store_id') == 2) { ?>
<?php } ?>
<section id="content" style="margin-top: 0">
    <div class="wrap">  
        <?php echo $content_top; ?>
        <?php if ($categories) { ?>
            
        
        
        
        <div class="manufacturer-alphabet">
            <div class="name-block">
                <span><?php echo $text_brands_by_alphabet; ?></span>
            </div>
            <div class="manufacturer-alphabet-block">
                <?php foreach ($categories as $category) { ?>
                    <div class="manufacturer-alphabet-item">                    
                        <a href="<?php echo $this_link; ?>#<?php echo $category['name']; ?>" ><span><?php echo $category['name']; ?></span></a>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        

            <div class="manufacturer-country">
                <div class="name-block">
                    <span><?php echo $text_translate_bybrand; ?></span>
                </div>
                <div class="manufacturer-country-block" data-count-item="6">
                    
                <?php foreach ($countrybrands as $countrybrand) { ?>
                    <div class="manufacturer-country-item">
                        <a href="<?php echo $countrybrand['href']; ?>" title="<?php echo $countrybrand['name']; ?>">
                            <?php if ($countrybrand['flag']) { ?>
                                <img src="/catalog/view/theme/kp/img/flags/brand/<?php echo $countrybrand['flag']?>.png" alt="<?php echo $countrybrand['name']; ?>">
                            <?php } ?>
                            <span><?php echo $countrybrand['name']; ?></span>
                        </a>
                    </div>
                <?php } ?>
                    
                </div>
            </div> 
            
            <!--   <?php if ($this->config->get('config_store_id') == 2) { ?>
                <?php } else { ?>
                <p class="manufacturer-alphabet" style="margin-bottom: 25px;"><b><?php echo $text_brands_by_alphabet; ?></b>
                <?php foreach ($categories as $category) { ?>
                    &nbsp;&nbsp;&nbsp;<a href="<?php echo $this_link; ?>#<?php echo $category['name']; ?>"><b><?php echo $category['name']; ?></b></a>
                <?php } ?>
                </p>
            <?php } ?> -->
            <div class="manufacturer-list">
                
                <button class="toggled_manufacturer btn">
                    <?php echo $text_translate_1; ?> <span class="toggle-s hidden"><?php echo $text_translate_3; ?></span><span class="toggle-l"><?php echo $text_translate_2; ?></span>
                </button>
                <div class="manufacturer-content ">
                    <ul>
                        <?php foreach ($categories as $category) { ?>                 
                            
                            <?php if ($category['manufacturer']) { ?>
                            
                            <?php for ($i = 0; $i < count($category['manufacturer']); $i++) { ?>
                                <?php if (isset($category['manufacturer'][$i])) { ?>
                                    <li <?php if ($i == 0){ ?> id="<?php echo $category['name']; ?>" <?php } ?> class="list">
                                        <div class="item img-box hidden">
                                            <a href="<?php echo $category['manufacturer'][$i]['href']; ?>" class="">
                                                <img src="<?php echo $category['manufacturer'][$i]['back_image']; ?>" alt="">
                                                <span class="bg-poster">
                                                    <img src="<? echo $category['manufacturer'][$i]['image']; ?>" title="<?php echo $category['manufacturer'][$i]['name']; ?>" alt="<?php echo $category['manufacturer'][$i]['name']; ?>" />
                                                </span>
                                            </a>    
                                            <div class="title-manufacturer">
                                                <span><?php echo $category['manufacturer'][$i]['name']; ?></span>
                                            </div>
                                        </div>
                              			<div class="item desc">
                                            <a href="<?php echo $category['manufacturer'][$i]['href']; ?>"><?php echo $category['manufacturer'][$i]['name']; ?></a>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            
                        <?php } ?>
                        
                    <?php } ?>
                    </ul> 
                </div>  
            </div>
            <?php } else { ?>
            <div class="content"><?php echo $text_empty; ?></div>
            <div class="buttons">
                <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
            </div>
        <?php } ?>
        <script>
            $(function() {
                $( document ).tooltip({
                    track: true,
                    items: ".manufacturer-list ul li",
                    content: function() {
                        var element = $( this );
                        return element.children('.tooltip').html();
                    }
                });
            });
        </script>
        <?php echo $content_bottom; ?>
    </div>
</section>
    <?php if ($categories) { ?>
        <script>
            var btnToggleList = document.querySelector('.manufacturer-list .toggled_manufacturer'),
            wrapList = document.querySelector('.manufacturer-content ul'),
            listManufacturer = document.querySelectorAll('.manufacturer-content ul li');
            
            btnToggleList.addEventListener('click', function(){
                let span = this.querySelectorAll('span');
                
                wrapList.classList.toggle('image');
                
                span.forEach(function(e){
                    e.classList.toggle('hidden');
                });           
                
                listManufacturer.forEach(function(i){
                    
                    i.classList.toggle('list');
                    let div = i.querySelectorAll('.item');
                    div.forEach(function(e){
                        e.classList.toggle('hidden');
                    });
                });
                
            });
            
            
            
        </script>
    <?php } ?>
    
    
    <script>
        $(document).ready(function() {
            var $page = $('html, body');
            $('a[href*="#"]').click(function() {
                $page.animate({
                    scrollTop: $($.attr(this, 'href')).offset().top
                }, 400);
                return false;
            });
        });
    </script>
<?php echo $footer; ?>