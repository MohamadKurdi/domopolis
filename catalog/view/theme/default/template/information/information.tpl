<?php echo $header; ?>
<?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
<?php echo $content_top; ?>



<div class="wrap info_description">
    <?php echo $description; ?>
</div>
<? if (isset($informations) && $informations) { ?>
    <style>
        .info_menu_list li a{
            display: flex !important;
            flex-direction: column;
            transition: .2s ease-in-out;
        }

        .info_menu_list li a i{
            font-size: 45px;
        }

        .info_menu_list li a span{
            font-size: 18px;
            margin-top: 20px;
            font-family: montserrat,sans-serif;
        }

        .info_menu_list li a img{
            display: none;
        }

        .info_menu_list li a:hover,
        .info_menu_list li.active a{
            color: #51a881;
        }
        p{
            font-size: 17px;
            line-height: 1.6em;
            margin-bottom: 20px;
        }
        p b{
            font-weight: 600;
        }
    </style>
    <section class="info_block">
        <div class="wrap">
        
            <div class="info_menu_block">
                <div class="information">
                    <ul class="info_menu_list">
                    <? foreach ($informations as $information) { ?>
                        <li data-id="<? echo $information['information_id'] ?>" 
                        class="menu-<? echo $information['information_id'] ?> <? if ($information['active']) {?> active <? } ?>">
                            <a class="info_menu" style="text-align:center;" href="<? echo $information['href'] ?>" title="<? echo $information['title'] ?>">
                            <i class="fas"></i>
                            <img src="<? echo $information['image'] ?>" title="" alt="<? echo $information['title'] ?>" /><span>
                            <? echo $information['title'] ?></span></a>
                        </li>
                    <? } ?>
                    </ul>
                </div>
            </div>
       
        </div>
    </section>
<? } ?>



<?php echo $content_bottom; ?>
<script>
    $('.menu-29').find('i').addClass('fa-info-circle');
    $('.menu-31').find('i').addClass('fa-truck');
    $('.menu-30').find('i').addClass('fa-wallet');
    $('.menu-34').find('i').addClass('fa-chess-rook');
    $('.menu-35').find('i').addClass('fa-gifts');
    $('.menu-32').find('i').addClass('fa-percent'); 
    $('.menu-33').find('i').addClass('fa-sync-alt'); 
</script>
<?php echo $footer; ?>
