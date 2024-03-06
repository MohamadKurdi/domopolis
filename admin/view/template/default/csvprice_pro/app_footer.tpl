<div id="dialog-scroll" style="display: none" title=""></div>
<div class="g f-content">
        <?php if( isset($note_status) && $note_status ) { ?>
        <div class="g-row" style="margin-top:6px;"><?php echo $text_show_help_note;?></div>
        <?php } ?>
        <div class="g-row f-copyright">        
        </div>
</div>
<div class="f-help-block f-tooltip" id="prop_desc_win">
	<div class="c_top l"></div>
	<div class="c_top r"></div>
	<div class="close" onclick="$('#prop_desc_win').hide()"></div>
	<div class="f-help-content" id="prop_desc_win_content"></div>
	<div class="c_btm l"></div>
	<div class="c_btm r"></div>
</div>
<script type="text/javascript"><!--
	$(document).ready(function(e) {
		if($.fn.jquery == '1.6.1') {
			$('a.delete').bind('click', deleteAlert);
		} else {
			$('a.delete').on('click', deleteAlert);
		}
		// remove old ocStore twick
		$(".scrollbox").each(function(i) {
		 	if ($(this).next().prop("tagName") == 'SPAN') {
				$(this).next().remove();
			}
		});
		// show help
		$('.f-row label').each(function() {
                        var h,i,e=$(this);
                        if ( e.attr('data-prop_id') ) {
                                h=e.html();
                                if( e.find("input").length ) {
                                        e.after('<img data-prop_id="'+e.attr('data-prop_id')+'" src="view/image/csvprice_pro/q.png" width="12" height="12" class="f-icon-helper q_sign" alt="<?php echo $text_show_help;?>" title="<?php echo $text_show_help;?>">');
                                        i=e.next();
                                } else {
                                        e.html('<img data-prop_id="'+e.attr('data-prop_id')+'" src="view/image/csvprice_pro/q.png" width="12" height="12" class="f-icon-helper q_sign" alt="<?php echo $text_show_help;?>" title="<?php echo $text_show_help;?>">'+h);
                                        i=e.find('.f-icon-helper');
                                }
                                if( i.length ) {
                                        i.click(function() {
                                                var offset = e.offset();
                                                $("#prop_desc_win_content").html(prop_descr[$(this).attr('data-prop_id')]);
                                                $("#prop_desc_win").css({
                                                        top: offset.top,
                                                        left: offset.left,// - 10,
                                                        display: "block"
                                                });
                                                return false;
                                        });
                                }
                        }
                });

	});
	
	function deleteAlert() {
		if (!confirm('<?php echo $text_confirm_delete;?>')) {
			return false;
		}
	}
	function tabsClassActive() {
		$('#tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
	}
	
	$(document).keyup(function(e) {
		if (e.keyCode == 27 && !$("#prop_desc_win").is(':hidden')) {
			$('#prop_desc_win').hide()
		}
	});
	
	$(function() {
	   var dialog = $( "#dialog-scroll" ).dialog({
	     autoOpen: false,
	     width: 800,
	     height: 600,
	     modal: true,
	     buttons: {
		"<?php echo $text_select;?>": function() {
		dialog.dialog( "close" );
	       }
	     },
	     open: function( event, ui ) {
	     	$(this).css('overflow', 'hidden');
		var h='<div class="scrollbox" style="width: 100% !important; height: 100% !important">' + $("#" + $(this).data('id') + " div.scrollbox").html() + '</div>';
		$(this).html(h);
		var i=$(this).data('id'),k=0;
		$("#" + i + ' input[type="checkbox"]').each(function(){
			$(this).attr("id", "l-scroll_" + k); k++;
		});k = 0;
		$('#dialog-scroll input[type="checkbox"]').each(function(){
			$(this).attr("id", "k-scroll_" + k);
			if($("#l-scroll_"+k).prop('checked')) {
				$(this).prop('checked', true);
			}
			k++;
		});
		$('#dialog-scroll input[type="checkbox"').change(function(){
			$('#' + this.id.replace('k-scroll_', 'l-scroll_')).prop('checked', this.checked);
		});
	     },
	     close: function() {
	     	$("#" + $(this).data('id') + ' input[type="checkbox"]').each(function(){
		$(this).attr("id", "");
	});
	     	$(this).html('');
	     }
	   });
	   $(".show_scroll").click(function(e) {
	   	e.preventDefault();
	   	dialog.data('id', $(this).parent().attr('id')).dialog( "open" );
	   	return false;
	   });
	 });
//--></script>