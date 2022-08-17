 $(document).ready(function(){       $('.accordion-tabs').children('li').first().children('a').addClass('is-active')
        .next().addClass('is-open').show();
        $('.accordion-tabs').on('click', 'li > a', function(event) {
            if (!$(this).hasClass('is-active')) {
                event.preventDefault();
                $('.accordion-tabs .is-open').removeClass('is-open').hide();
                $(this).next().toggleClass('is-open').toggle();
                $('.accordion-tabs').find('.is-active').removeClass('is-active');
                $(this).addClass('is-active');
                   // $('.scroll-pane').jScrollPane().reinitialise();
            } else {event.preventDefault();}
        });
        
        $(function()
            {$('.scroll-pane').jScrollPane();});

        var sync1 = $(".sync1");
        var sync2 = $(".sync2");

        sync1.owlCarousel({
		    items : 1,
            singleItem : true,
            slideSpeed : 1000,
            navigation: true,               
            video:true,
            lazyLoad:true,
            merge:true,
            pagination:false,
            touchDrag: false,
            mouseDrag: false,
            afterAction : syncPosition,
            responsiveRefreshRate : 200, 
        });

        sync2.owlCarousel({
            items : 2,
            itemsDesktop      : [1199,10],
            itemsDesktopSmall     : [979,10],
            itemsTablet       : [768,8],
            itemsMobile       : [479,4],
            pagination:false,
            loop: true,
            responsiveRefreshRate : 100,
            afterInit : function(el){
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });

        function syncPosition(el){
            var current = this.currentItem;
            $(".sync2")
            .find(".owl-item")
            .removeClass("synced")
            .eq(current)
            .addClass("synced")
            if($(".sync2").data("owlCarousel") !== undefined){
                center(current)
            }
        }

        $(".sync2").on("click", ".owl-item", function(e){
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo",number);
        });

        function center(number){
            var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
            var num = number;
            var found = false;
            for(var i in sync2visible){
                if(num === sync2visible[i]){
                    var found = true;
                }
            }

            if(found===false){
                if(num>sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", num - sync2visible.length+2)
                }else{
                    if(num - 1 === -1){
                        num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                }
            } else if(num === sync2visible[sync2visible.length-1]){
                sync2.trigger("owl.goTo", sync2visible[1])
            } else if(num === sync2visible[0]){
                sync2.trigger("owl.goTo", num-1)
            }

        }

}); 