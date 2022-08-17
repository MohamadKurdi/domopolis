 function openTextCreate(btn, needToBeWrap, startHeight ) {
 	var k = $(needToBeWrap).html().length;

 	if( k >= 1000 && k !=undefined ){

 		 	var openTextBtn = $('<input/>').attr({type: "button",id: btn, value: "Посмотреть полностью" });
		 	$(needToBeWrap).wrapInner( "<div id='inner-Open-Block'></div>" );

		 	$('#inner-Open-Block').css({'height': startHeight});
		  	
		  	$(needToBeWrap).append(openTextBtn);
		  	var blockBtn = document.getElementById(btn);
		  	$(blockBtn).on('click', function(){
		  		var heightBlock = $('#inner-Open-Block').height();
		  		if(heightBlock <= 150){
		  			$('#inner-Open-Block').css({'height':'100%'});
		  			$(this).attr({value: "Скрыть"});
				 			
		  		}
		  		else{
		  			$('#inner-Open-Block').css({'height': startHeight});
				 	$(this).attr({value: "Посмотреть полностью"});
		  		}
		  		
		  	});

 	}
 }
 $(function () {
	$('input[name="search"]').autocomplete({
		minLength: 3,
		source: function (request, response) {
			$.ajax({
				url: 'index.php?route=module/ajax_search/callback',
				dataType: 'json',
				data: {
					keyword: request.term
				},
				error: function (data) {
					console.log(data);
				},
				success: function (data) {
					if (!(typeof ga === 'undefined' || !ga) && request.term != '' && request.term.length > 3){
						ga('gtm1.send', 'pageview', 'index.php?route=module/ajax_search/callback&keyword='+request.term); 
					}
					response($.map(data.results, function (item) {
						return {
							label: item.label,
							url: item.url,
							price: item.price,
							desc: item.desc,
							img: item.img
						}
					}));
				}
			});
		},
		click: function (event, ui) {
			$('input[name="search"]').val(ui.item.label);
			return false;
		},
		select: function (event, ui) {
			separator = (ui.item.url.indexOf('?') > - 1) ? '&' : '?';
			location = ui.item.url.replace('&amp;', '&') + separator + 'ref=ac';
		}
		}).data('ui-autocomplete')._renderItem = function (ul, item) {
		return $('<li></li>').data('item.autocomplete', item).append('<a><img src=\'' + item.img + '\' width=\'50\' height=\'50\'><h4>' + item.label + '</h4><div style=\'clear:both;\'></div></a>').appendTo(ul);
	};
});