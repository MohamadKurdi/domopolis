$(document).ready(function(){
	

	$('#custom-ft a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	$('#customcontent .nav-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	$('#header_comptextus').find('li:first').addClass('active');
	$('#footer_comptextus').find('li:first').addClass('active');
	$('#products_page').find('li:first').addClass('active');
	$('#products_tab').find('li:first').addClass('active');
	$('#tab_colorsetting').find('li:first').addClass('active');
	$('#custom_footer_text').find('li:first').addClass('active');
	$('#tab_footersite').find('li:first').addClass('active');
    $('.tab-pane').find('.langu:first').addClass('active');
	$('.tab-pane').find('.langu2:first').addClass('active');
	$('.tab-pane').find('.langu3:first').addClass('active');
	$('.tab-pane').find('.langu4:first').addClass('active');
	$('.tab-pane').find('.langu5:first').addClass('active');

	
	$('#ft-menu-tab').find('li:first').addClass('active');
	
	
	$('#twitterhelp').live('click', function() {
		$('.helppopup1').dialog({
			open: function(event, ui) {},
			title: 'widget ID',
			width: 870,height: 720,resizable: false, closeOnEscape: true
		});	
	});
	
			$('#displaymenu').live('click', function() {
		$('.helppopup2').dialog({
			open: function(event, ui) {},
			title: 'Default and Mattimeo menu',
			width: 870, height: 720, resizable: false, closeOnEscape: true
		});	
	});
	
	$('#bannerheading').live('click', function() {
		$('.helppopup3').dialog({
			open: function(event, ui) {},
			title: 'Title and text banners',
			width: 870, height: 720, resizable: false, closeOnEscape: true
		});	
	});
	$('#bannerheading2').live('click', function() {
		$('.helppopup4').dialog({
			open: function(event, ui) {},
			title: 'Title and text banners',
			width: 870, height: 720, resizable: false, closeOnEscape: true
		});	
	});
	$('#categmodul').live('click', function() {
		$('.helppopup5').dialog({
			open: function(event, ui) {},
			title: 'Mattimeo Categories',
			width: 870, height: 650, resizable: false, closeOnEscape: true
		});	
	});
		$('#customfooter').live('click', function() {
		$('.helppopup6').dialog({
			open: function(event, ui) {},
			title: 'Custom footer',
			width: 870, height: 450, resizable: false, closeOnEscape: true
		});	
	});
	$('#categdescr').live('click', function() {
		$('.helppopup7').dialog({
			open: function(event, ui) {},
			title: 'Category description',
			width: 870, height: 650, resizable: false, closeOnEscape: true
		});	
	});		
    $('#categdescr2').live('click', function() {
		$('.helppopup8').dialog({
			open: function(event, ui) {},
			title: 'Category description',
			width: 870, height: 650, resizable: false, closeOnEscape: true
		});	
	});		
	
	
});

