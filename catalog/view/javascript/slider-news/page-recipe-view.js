;(function($){

    'use strict';

    // Slider & Voting Elemente Cachen
    var $slider = $('#slider'),
        $votingButton = $('#slider-voting-button'),
        $votingContainer = $('#recipe-image-voting'),
        $playToggleButton = $('#playPauseButton'),
        isVotingEnabled = $slider.data('voting-enabled'); // Voting-Interface-Flag

    // Default auf true, ansonsten Wert vom data-attribute
    isVotingEnabled = typeof isVotingEnabled === 'undefined' ? true : false;


    // Oeffnen und schliessen der Bewertungsbox in der Rezeptanzeige
    $('.voting-toggle').click( function() {

        $('#recipe-statistic').slideUp();
        $('#recipe-voting').slideToggle();
        return false;

    });

    // Oeffnen und schliessen der Statistiken in der Rezeptanzeige
    $('.statistic-toggle').click( function() {

        $('#recipe-voting').slideUp();
        $('#recipe-statistic').slideToggle();
        return false;
    });

    /* Nivo-Slider fuer Rezept-Anzeige */
    var nivo_options = {
        animSpeed:        100, // Slide transition speed
        controlNav:       false,
        directionNav:     true,
        directionNavHide: false,
        effect:           'fade',
        nextText:         '', // Next directionNav text
        prevText:         ''  // Prev directionNav text
    };

    if ( $slider.find('img').length === 1 ) {
        nivo_options.manualAdvance = true;
    }

    /* Pause-Button fuer Slider */
    $playToggleButton.click(function (e) {

        e.preventDefault();

        if ($playToggleButton.hasClass('pause')) {
            $slider.data('nivoslider').stop();
            $playToggleButton.attr('class', 'play');
        }
        else {
            $slider.data('nivoslider').start();
            $playToggleButton.attr('class', 'pause');
        }
    });

    /* Bewertungs-Button beim MouseOver ueber einem Bild einblenden */

    // Button fuer Touch-Geraete immer einblenden
    if ( 'ontouchstart' in document.documentElement ) {

        $votingButton.show();

    // sonst nur beim Hovern ueber den Slider
    } else {

        $slider.hover(
            function(){
                $votingButton.fadeIn(300);
            },
            function(){
                $votingButton.fadeOut(300);
            }
        );
    }

    /*
     * Slider bei Klick auf den Bewertungs-Button anhalten und
     * Zoom des aktuellen Bildes oeffnen.
     */
    $votingButton.click(function(evt){

        evt.preventDefault();

        // stop slider
        $slider.data('nivoslider').stop();

        // toggle play/pause button
        $playToggleButton.attr('class', 'pause');

        // get current slide
        var current_slide = $slider.data('nivo:vars').currentSlide;

        $('.nivo-imageLink[id="' + current_slide + '"]').trigger('click');

        if ( window.user_token !== '' ) {
            $('#recipe-image-voting-action-box').show();
        }
    });

    /*
     * Slider auf bestimmtes Bild springen lassen, Autoplay deaktivieren
     * und Zoom oeffnen, wenn eine Bild-ID per Anker "#slide-123"
     * in der URL uebergeben wird.
     *
     * Der Zoom wird erst nach Intialisierung der Colorbox geoeffnet,
     * siehe weiter unten.
     */

    var fragment          = window.location.hash;
    var fragment_parts    = fragment.match(/slide-([0-9]+)/i);
    var init_image_group  = null;
    var slider_init_index = null;

    if ( (fragment !== '') && fragment_parts ) {

        init_image_group = fragment_parts[1];

        var image_slides  = $('.slideshow-image');

        $.each(image_slides, function(key, value){

            if ( value.getAttribute('data-image-group') === init_image_group ) {

                slider_init_index = key;
            }
        });

        nivo_options.startSlide = slider_init_index;
        nivo_options.manualAdvance = true;
    }

    /* Nivo Slider initialisieren */
    $slider.nivoSlider(nivo_options);
    $playToggleButton.show();



    $.colorbox.settings.trapFocus = false;

    /* Lightbox fuer Rezept-Anzeige */
    $('.nivo-imageLink').colorbox({
        close:     '<span>x</span>',
        maxHeight: '98%',
        maxWidth:  '98%',
        next:      '',
        opacity:   '0.8',
        onOpen:    initSlideShow,
        afterChange: refreshVotingData,
        onCleanup: updateSlider,
        photo:      true,
        previous:   '',
        rel:        'slideshow-image',
        transition: 'none'
    });


    function initSlideShow(){

        // Slider beim Klick auf ein Bild und Oeffnen der Zoom-Box anhalten
        if ( nivo_options.manualAdvance !== true ) {
            $slider.data('nivoslider').stop();
        }

        if (isVotingEnabled === true) {
            initVoting();
        }
    }

    function initVoting() {

        //+ Rezeptbild-Voting auslesen und ausgeben +//

        // CSS-Klasse fuer Touch-Geraete ergaenzen
        if ( 'ontouchstart' in document.documentElement ) {

            $('#recipe-image-voting').addClass('recipe-image-voting-touch');
        }

        // Daten auslesen
        var this_image  = $slider.find('.slideshow-image');
        var voting_data = this_image.data('voting');
        var image_group = this_image.data('image-group');

        // Votingdaten in Darstellungs-Element setzen
        $votingContainer.attr('data-image-group', image_group);
        $votingContainer.find('.rating-big .rating').addClass(voting_data.css_class);
        $votingContainer.appendTo('#cboxContent');
        $votingContainer.show();
    }

    function refreshVotingData() {

        if (isVotingEnabled !== true) {
            return false;
        }

        // Voting-Box ausblenden
        $('#recipe-image-voting-action-box').hide('fast');

        // Daten auslesen
        var this_image  = $(this).find('.slideshow-image');
        var voting_data = this_image.data('voting');
        var image_group = this_image.data('image-group');

        // Link zum Login mit neuem Hash versehen
        if ( document.getElementById('recipe-image-voting-login-link') ) {

            var login_link     = $('#recipe-image-voting-login-link');
            var login_url_base = login_link.attr('href');
            var login_url      = login_url_base.replace(/hash=[^"]*/, 'hash=slide-' + this_image.data('image-group'));

            $('#recipe-image-voting-login-link').attr('href', login_url);
        }

        // Votingdaten in Darstellungs-Element setzen
        $('#recipe-image-voting').attr('data-image-group', image_group);

        var rating_display = $('#recipe-image-voting .rating-big .rating');

        rating_display.removeClass();
        rating_display.addClass('rating ' + voting_data.css_class);

        // Fehlermeldungen ausblenden
        $('#recipe-image-voting-messages').fadeOut('fast', function(){

            $('#recipe-image-voting-messages li').hide();
        });
    }

    function updateSlider() {

        var slideshow_links = $('.slideshow-imagelink');

        // nur wenn mehr als ein Bild im Slider vorhanden ist muss der Slider zum aktuellen Bild springen
        if ( slideshow_links.length > 1 ) {

            var id;
            var backlink = $('.cboxPhoto').attr('src'); // scr des aktuell in der Lightbox angezeigten Bildes auslesen
            $('.slideshow-imagelink').each( function() { // Anhand des ausgelesenen src die passende Bild-ID herausfinden
                if ( $(this).attr('href') === backlink ) { id = $(this).attr('id'); }
            });

            $('#slider').data('nivoslider').start();
            slideTo(id); // Sliden
        }

        // Voting-Overlay ausblenden
        $('#recipe-image-voting').hide();
        $('#recipe-image-voting-action-box').hide();
    }



    /* Rezeptbildbewertung einblenden */
    $('body').on('click', '#recipe-image-voting:not(".noclick")', function(){

        $('#recipe-image-voting-messages').hide();
        $('#recipe-image-voting-action-box').toggle('fast');
    });

    /* Rezeptbildbewertung speichern */
    $('body').on('click', '#recipe-image-voting-action li', function(){

        // Daten sammeln
        var vote     = $(this).data('vote');
        var image_id = $('#recipe-image-voting').attr('data-image-group');

        var voting_data = {
            'image_id': image_id,
            'vote':     vote,
            'token':    window.user_token,
            'rezept_show_id': window.rezept_show_id
        };

        // POST-Request absenden
        $.post(
            '/ajax/recipe-image-vote.php',
            voting_data,
            function(data){

                // Bewertungs-Box mit Links ausblenden
                $('#recipe-image-voting-action-box').hide('fast');

                // Alte Fehlermeldungen ausblenden
                $('#recipe-image-voting-messages li').hide();
                $('#recipe-image-voting-messages').hide();

                // Bei erfolgreichem Speichern, die angezeigte Bewertung aktualisieren
                if ( data.success === true ) {

                    // Bewertung aktualisieren
                    var rating_display = $('#recipe-image-voting .rating-big .rating');

                    rating_display.removeClass();
                    rating_display.addClass('rating ' + data.rating.css_class);

                    var slide_image = $('.slideshow-image[data-image-group="' + image_id + '"]');

                    var voting_data_new = {
                        'average':   data.rating.average,
                        'count':     data.rating.count,
                        'css_class': data.rating.css_class
                    };

                    slide_image.data('voting', voting_data_new);

                    // Erfolgsmeldung ausgeben
                    showRecipeImageVotingMessage('#message-success');

                // Im Fehlerfall eine Meldung ausgeben
                } else {

                    showRecipeImageVotingMessage('#message-error-' + data.errors[0]);
                }
            },
            'json'
        );
    });

    /**
     * Zeigt eine Infomeldung (Erfolg, Fehler, etc.) auf dem Rezeptbild-Zoom an
     *
     * @param string  selector  CSS-Selektor
     */
    function showRecipeImageVotingMessage(selector) {

        var $votingFeedback = $('#recipe-image-voting-messages');

        $votingFeedback.find(selector).show();
        $votingFeedback.fadeIn('fast').delay(2000).fadeOut('fast');
    }

    /* Zoom oeffnen, wenn ueber die URL zu einem bestimmten Bild gesprungen wird */

    if ( slider_init_index !== null ) {

        // Statt dem initialen Pause-Button auf "play" wechseln, da wir den Autostart des
        // Sliders oben unterbunden haben und manuell gestartet werden muss.
        $playToggleButton.toggleClass('pause', false);
        $playToggleButton.toggleClass('play',  true);

        // Colorbox des entsprechenden Links ueber das registrierte Click-Event triggern
        $('.nivo-imageLink[id="' + slider_init_index + '"]').trigger('click');
    }

    // slideTo function for nivo-slider
    function slideTo (idx) {
        $('#slider').data('nivo:vars').currentSlide = idx - 1;
        $('#slider a.nivo-nextNav').trigger('click');
    }

    // Lightbox fuer Kommentar-Antwort
    $('#contentbox-2').find('.inline').colorbox({
        close :     '',
        inline:     true,
        width :     '616px',
        transition: 'none',
        opacity:    '0.8',
        onOpen: function(){
            // Relax JsHint (for now)
            /*jshint unused:false */
            var $commentLink = $(this),
                $replyOverlay = $('#comment-reply-box');

            var rezept_id                  = $commentLink.data('rezept_id');
            var rezept_kategorie_id        = $commentLink.data('rezept_kategorie_id');
            var kommentar_id               = $commentLink.data('kommentar_id');
            var kommentar_username         = $commentLink.data('kommentar_username');
            var kommentar_user_profile_url = $commentLink.data('kommentar_user_profile_url');
            var kommentar_text             = $('#kommentar_' + kommentar_id).html();

            $replyOverlay.find('.comment-reply-link').html('<a href="' + kommentar_user_profile_url + '">' + kommentar_username + '</a>');
            $replyOverlay.find('#comment-original').html(kommentar_text);
            $replyOverlay.find('.comment-reply-pid').attr('value', kommentar_id);

            // Kleines Delay, weil ansonsten der focus() bei versteckten Elementen nicht greift
            window.setTimeout(function(){
                $replyOverlay.find('textarea').focus();
            }, 10);
        }
    });

    // Schliessen-Button fuer Kommentar-Antwort-Overlay
    $('.comment-reply-close').click(function() {
        $.colorbox.close();
        return false;
    });

    /**
        Ich koche gerade
    **/
    // Lightbox fuer 'Ich koche gerade'
    $('#nowcooking-add').colorbox({
        close :     '<span>x</span>',
        inline:     true,
        width :     '400px',
        height:     '650px',
        transition: 'none',
        opacity:    '0.8'
    });

    // Lightbox nach Login wieder oeffnen
    if ( window.location.hash === '#nowcooking' ) {
        $('#nowcooking-add').trigger('click');
    }

    // Button 'Ich koche gerade'
    $('#nowcooking-cooknow').click( function() {

        var rezept_show_id = $(this).attr('data-recipe-show-id');
        $.ajax({
            url: '/ajax/rezept-zubereiten.php?recipe_show_id=' + rezept_show_id,
            success: function( data ) {

                // Button deaktivieren
                $('#recipe-prepare-list-add').removeClass('button-green').addClass('button-disabled');

                if ( data.success === false ) {

                    // Fehlermeldung anzeigen, wenn der Benutzer dieses Rezept schon kocht
                    if ( data.permission_denied === true ) {
                        $('#recipe-prepare-list-alert-cooked').fadeIn();
                    }

                    // Fehlermeldung anzeigen, wenn der Benutzer nicht eingeloggt ist
                    if ( data.not_logged_in === true ) {
                        $('#recipe-prepare-list-alert-login').fadeIn();
                    }
                } else if ( data.success === true ) {

                    // Bestaetigungsmeldung ausgeben
                    $('#recipe-prepare-list-alert-success').fadeIn();

                    // "Jetzt kochen"-Button deaktivieren
                    $('#nowcooking-cooknow').remove();

                }
            }
        });

    });

})(jQuery);
