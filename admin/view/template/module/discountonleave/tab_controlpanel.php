<div class="tabbable tabs-left" id="popup_tabs">
	
    <ul class="nav nav-tabs popup-list">
        <li class="static"><a class="addNewPopUp"><i class="fa fa-plus"></i> Add New Popup</a></li>
        <?php if (isset($moduleData['DiscountOnLeave'])) { ?>
            <?php foreach ($moduleData['DiscountOnLeave'] as $popup) { ?>
            <li><a href="#popup_<?php echo $popup['id']; ?>" data-toggle="tab" data-popup-id="<?php echo $popup['id']; ?>"><i class="fa fa-list-alt"></i> Popup <?php echo $popup['id']; ?> <i class="fa fa-minus-circle removePopUp"></i>
                <input type="hidden" name="<?php echo $moduleName; ?>[DiscountOnLeave][<?php echo $popup['id']; ?>][id]" value="<?php echo $popup['id']; ?>" />
                </a> </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="tab-content popup-settings">
            <?php if (isset($moduleData['DiscountOnLeave'])) { ?>
            <?php foreach ($moduleData['DiscountOnLeave'] as $popup) { 
                require(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_popuptab.tpl');
				
            } ?>
        <?php } ?>
    </div>
</div>
        
<script type="text/javascript"><!--

var selectorsBinds = function() {
        $('select[name*=method]').each(function () {
            if($(this).val() == 0) {
                $(this).parents('.row').next().next('.url_method').hide();
                $(this).parents('.row').next().next().next('.allpages_method').hide();
            }

            if($(this).val() == 1) {
                $(this).parents('.row').next().next('.url_method').hide();
                $(this).parents('.row').next().next().next('.allpages_method').show();
            }

            if($(this).val() == 2) {
                $(this).parents('.row').next().next('.url_method').show();
                $(this).parents('.row').next().next().next('.allpages_method').hide();
            }

            $(this).on('change', function() {
                if($(this).val() == 0) {
                    $(this).parents('.row').next().next('.url_method').hide(200);
                    $(this).parents('.row').next().next().next('.allpages_method').hide(200);
                }

                if($(this).val() == 1) {
                    $(this).parents('.row').next().next('.url_method').hide(200);
                    $(this).parents('.row').next().next().next('.allpages_method').show(200);
                }

                if($(this).val() == 2) {
                    $(this).parents('.row').next().next('.url_method').show(200);
                    $(this).parents('.row').next().next().next('.allpages_method').hide(200);
                }
            });
         }); 

        $('.repeatSelect').each(function(e){ 
            if($(this).val() == 2) {
                $(this).parents('.popups').find('.daysPicker').show();
            }
            else {
                $(this).parents('.popups').find('.daysPicker').hide();
            }
        });

        $('.repeatSelect').on('change', function(e){ 
            if($(this).val() == 2) {
                $(this).parents('.popups').find('.daysPicker').show(200);
            }
            else {
                $(this).parents('.popups').find('.daysPicker').hide(200);
            }
        });
        
    }


// Add PopUp
function addNewPopUp() {
	count = $('.popup-list li:last-child > a').data('popup-id') + 1 || 1;
	var ajax_data = {};
	ajax_data.token = '<?php echo $token; ?>';
	ajax_data.store_id = '<?php echo $store["store_id"]; ?>';
	ajax_data.popup_id = count;

	$.ajax({
		url: 'index.php?route=module/<?php echo $moduleNameSmall; ?>/get_popupwindow_settings',
		data: ajax_data,
		dataType: 'html',
		beforeSend: function() {
			NProgress.start();
		},
		success: function(settings_html) {
		$('.popup-settings').append(settings_html);
	
			if (count == 1) { $('a[href="#popup_'+ count +'"]').tab('show'); }
			tpl 	= '<li>';
			tpl 	+= '<a href="#popup_'+ count +'" data-toggle="tab" data-popup-id="'+ count +'">';
			tpl 	+= '<i class="fa fa-list-alt"></i> Popup '+ count;
			tpl 	+= '<i class="fa fa-minus-circle removePopUp"></i>';
			tpl 	+= '<input type="hidden" name="<?php echo $moduleName; ?>[DiscountOnLeave]['+ count +'][id]" value="'+ count +'"/>';
			tpl 	+= '</a>';
			tpl	+= '</li>';
			
			$('.popup-list').append(tpl);
			
			NProgress.done();
			$('.popup-list').children().last().children('a').trigger('click');
			window.localStorage['currentSubTab'] = $('.popup-list').children().last().children('a').attr('href');
			$('.removePopUp').on('click', function(e) { removePopUp(); });
            selectorsBinds();
		}
	});

    
}

// Remove PopUp
function removePopUp() {
	tab_link = $(event.target).parent();
	tab_pane_id = tab_link.attr('href');
	
	var confirmRemove = confirm('Are you sure you want to remove ' + tab_link.text().trim() + '?');
	
	if (confirmRemove == true) {
		tab_link.parent().remove();
		$(tab_pane_id).remove();
		
		if ($('.popup-list').children().length > 1) {
			$('.popup-list > li:nth-child(2) a').tab('show');
			window.localStorage['currentSubTab'] = $('.popup-list > li:nth-child(2) a').attr('href');
		}
	}
}

// Events for the Add and Remove buttons
$(document).ready(function() {
	// Add New Label
	$(document).on('click', '.addNewPopUp', function(e) { addNewPopUp(); });
    
	// Remove Label
	$(document).on('click', '.removePopUp', function(e) { removePopUp(); });

    selectorsBinds();

});

// Show the CKEditor
<?php 
if (isset($moduleData['DiscountOnLeave'])) { 
    foreach ($moduleData['DiscountOnLeave'] as $popup) {
        foreach ($languages as $language) { ?>
            $('#message_<?php echo $popup['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
                    height:300
            });
<?php   }
    }
} ?>

</script>

