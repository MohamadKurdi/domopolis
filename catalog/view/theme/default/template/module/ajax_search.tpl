<style type="text/css">
.ui-autocomplete { z-index:999 !important; }
.ui-autocomplete img { float:left;width:50px;height:50px;padding:0 5px; }
.ui-autocomplete h3 { font-weight:bold;padding:0;margin:0; }
.ui-autocomplete p { margin:0;padding:0;width:172px;line-height:1;display:inline }
.ui-autocomplete span { float:right;font-weight:bold; }
</style>
<script type="text/javascript">
$(function() {
	$('#search_input').autocomplete({
		minLength: 0,
		source: function( request, response ) {
			$.ajax({
				url: "index.php?route=module/ajax_search/callback",
				dataType: "json",
				data: {
					limit: 10,
					keyword: request.term
				},
				success: function( data ) {
					response( $.map( data.prods, function( item ) {
						return {
							label: 	item.label,
							url: 	item.url,
							price:	item.price,
							desc: 	item.desc,
							img: 	item.img
						}
					}));
				}
			});
		},
		focus: function( event, ui ) {
			$('#search_input').val( ui.item.label );
			return false;
		},
		select: function(event, ui) {
			separator = (ui.item.url.indexOf('?') > -1) ? '&' : '?';
			location = ui.item.url.replace('&amp;', '&') + separator + 'ref=ac';
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><img src='" + item.img + "' width='50' height='50'><h3>" + item.label + "</h3><p>" + item.desc + "<span>" + item.price + "</span></p><div style='clear:both;'></div></a>" )
		.appendTo( ul );
	};
});
</script>