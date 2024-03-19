<script>
    $('document').ready(function(){

        window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (window.SpeechRecognition) {
            console.log('Voice search supported, triggering');
            var current_input = $('#main-ajax-search').val();
            $('#main-ajax-search').after('<button class="voice_search_btn" id="voice-search-btn"><i class="fas fa-microphone"></i></button>');


            var recognition = new SpeechRecognition();
            recognition.interimResults = true;
            recognition.lang = 'ru-RU';
            recognition.addEventListener('result', _recognitionResultHandler);
            recognition.addEventListener('end', _recognitionEndHandler);
            recognition.addEventListener('error', _recognitionErrorHandler);
            recognition.addEventListener('nomatch', _recognitionNoMatchHandler);
            recognition.addEventListener('onnomatch', _recognitionNoMatchHandler);

            $('#voice-search-btn, #voice_modal .voice-modal__repeat').on('click touch', listenStart);

            $('.close_modals').on('click touch', function(){
                $('#voice_modal').hide();
                _recognitionEndHandler();
            });

            function listenStart(e){
                e.preventDefault();
                $('#voice_modal').show();

                $('#voice_modal .voice-modal__say').html("<?php echo $text_retranslate_29; ?>");
                $('#voice_modal .voice-modal__say').show();
                $('#voice_modal .body').removeClass('error_voice');
                $('#voice_modal .voice-modal__error').hide();


                $('#main-ajax-search').val('').attr("placeholder", "<?php echo $text_retranslate_29; ?>");
                $('#main-ajax-search').addClass('voice_input_active');
                $('#voice-search-btn').addClass('active');
                recognition.start();
            }

            function _recognitionErrorHandler(e){
                console.log('_recognitionErrorHandler fired');

                $('#voice_modal .body').addClass('error_voice');
                $('#voice_modal .voice-modal__error').show();
                $('#voice_modal .voice-modal__say').hide();

                $('#main-ajax-search').val(current_input);
                $('#voice-search-btn').removeClass('active');
                $('#main-ajax-search').removeClass('voice_input_active');
            }

            function _recognitionEndHandler(e){
                console.log('_recognitionEndHandler fired');
                $('#voice-search-btn').removeClass('active');
                $('#main-ajax-search').removeClass('voice_input_active').attr("placeholder", "<?php echo $text_retranslate_35; ?>");

            }

            function _recognitionNoMatchHandler(e){
                console.log('_recognitionNoMatchHandler fired');
                $('#main-ajax-search').val(current_input);

                $('#voice_modal .body').addClass('error_voice');
                $('#voice_modal .voice-modal__error').show();
                $('#voice_modal .voice-modal__say').hide();
            }


            function _recognitionResultHandler(e) {
                console.log('_recognitionResultHandler fired');
                if (e.results.length){

                    speechOutput = Array.from(e.results).map(function (result) { return result[0] }).map(function (result) { return result.transcript }).join('')

                    $('#main-ajax-search').val(speechOutput);
                    $('#voice_modal .voice-modal__text-recognize').html(speechOutput);
                    $('#voice_modal .voice-modal__say').hide();
                    if (e.results[0].isFinal) {
                        $('#main-ajax-search-submit').trigger('click');
                    }

                } else {
                    _recognitionNoMatchHandler(e);
                }
            }


        } else {
            console.log('Voice search not supported');
        }

    });
</script>

<script type="text/javascript">
    $(document).ready(function () {

          $('.search__btn').bind('click', function () {
            url = $('base').attr('href') + 'index.php?route=<?php echo $this->config->get('config_search_catalog_route'); ?>';
            var s = $('input[name=\'search\']').prop('value');
            if (s) {
              url += '&search=' + encodeURIComponent(s);
            }
            location = url;
          }),

          $(document).on('keydown','keypress', function (e) {
            if (keycode == 13) {
              url = $('base').attr('href') + 'index.php?route=<?php echo $this->config->get('config_search_catalog_route'); ?>';
              var t = $('header input[name=\'search\']').prop('value');
              t && (url += '&search=' + encodeURIComponent(t)),
              location = url
            }
          });






        }); 
    $('.button-search').bind('click', function() {
        url = $('base').prop('href') + 'index.php?route=<?php echo $this->config->get('config_search_catalog_route'); ?>';

        var search = $('input[name=\'search\']').prop('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        location = url;
    });

    $('#header input[name=\'search\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            url = $('base').prop('href') + 'index.php?route=<?php echo $this->config->get('config_search_catalog_route'); ?>';

            var search = $('input[name=\'search\']').prop('value');

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            location = url;
        }
    });


    function removeHistory(id){

        $.ajax({
            url: "index.php?route=search/ajax/clear",
            type: 'POST',
            data: {
                id: id
            },
            beforeSend: function(){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#search-wrap').find('.clear_btn').addClass('spinner');
            },
            complete: function(e){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-times"></i>');
                $('#search-wrap').find('.clear_btn').removeClass('spinner');
            },
            success: function() {
                getSearch();
            }
        });

    }

    function showSearch(){
        $('#search-wrap input').closest('#search-wrap').find('.autocomplete_wrap').show();
    }

    function getSearch(){
        let query = $('#main-ajax-search').val();
        $.ajax({
            url: "index.php?route=<?php echo $this->config->get('config_search_ajax_route'); ?>",
            dataType: "html",
            type: 'GET',
            data: {
                query: query
            },
            beforeSend: function(){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#search-wrap').find('.clear_btn').addClass('spinner');
            },
            complete: function(e){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-times"></i>');
                $('#search-wrap').find('.clear_btn').removeClass('spinner');
            },
            success: function(html) {
                $('#search-wrap .autocomplete_wrap').html(html);
            }
        });
    }

    $('#search-wrap input').on('click', function(){
        if($(this).val().length >= 1){
            showSearch();
        }
    });


    var timeoutID;

    document.querySelector('#search-wrap input').addEventListener('input', function(e) {
        clearTimeout(timeoutID);
        $('#search-wrap .clear_btn').show();
        timeoutID = setTimeout(function() {
            showSearch();
            getSearch();
        }, 1000);
    });

    $('#search-wrap input').focus(function(e) {
        showSearch();
    });

    $('#search-wrap .clear_btn').on('click', function(){
        $('#main-ajax-search').val('');
        $('#search-wrap').find('.autocomplete_wrap').hide();
        $(this).hide();
    });

    $(document).keypress(function (e) {

        if($('#main-ajax-search').is(":focus") || $('#main-ajax-search').val().length){
            var key = e.which;
            if(key == 13)  {
                $('#search-wrap .search__btn').trigger('click');
            }
        }

    });

    var valSearch = $('#search-wrap input').prop('value');
    if(valSearch.length  > 0){
        $('.search__field .clear_btn').show();
    }


    $(document).mouseup(function (e){
        var searchWrap = $("#search-wrap");
        if (!searchWrap.is(e.target) && searchWrap.has(e.target).length === 0) {
            $('#search-wrap').find('.autocomplete_wrap').hide();
        }
    });

    if(document.documentElement.clientWidth < 560) {
        setTimeout(function() {


            var widthDocument = document.documentElement.clientWidth,
                positionSearch = $('#search-wrap').offset().left+2;

            $('#search-wrap .search_wrap.autocomplete_wrap').css({
                width: widthDocument,
                left: -positionSearch
            });
        }, 1000);
    }
</script>