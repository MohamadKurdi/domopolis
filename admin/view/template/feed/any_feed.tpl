<?php echo $header; ?>
<?php if (isset($jquery)) echo $jquery; ?>
<style type="text/css">
div#users-contain { width: 350px; margin: 20px 0; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-state-error { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
#create-feed .ui-button-text {padding:0px;font-weight:normal;}
#create-feed.ui-state-hover {border: 0px solid black;}

.portlet { margin: 0 1em 1em 0;width:200px; }
.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; text-align:center;}
.portlet-content { padding: 0.4em;display:none;text-align:left;}
.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 32px !important; }
.ui-sortable-placeholder * { visibility: hidden; }
.clear{clear:both;}
.place_holder_container{position:relative;}

#content h2{color:#72aa00;}
.ui-dialog .ui-widget-header .ui-state-hover .ui-icon {
	background-image: url('view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_ffffff_256x240.png');
	color: white;
	background-position: -96px -128px;
}


.ui-widget label {font-size:1em;}
.ui-widget {font-family: Arial, Helvetica, sans-serif}
.ui-widget-header {
	border: 1px solid #AAA/*{borderColorHeader}*/;
	background: #DDD/*{bgColorHeader}*/ 50%/*{bgHeaderXPos}*/;
	font-family:sans-serif;
	font-size:1em;
	text-align:left;
	color:black;
	padding-top:3px;
	font-weight:bold;
}

.field_content .ui-widget-header {
	border-color:#009245; 
 	background: #8eb92a;
 	color:white;
 	background-image:url('view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_ffffff_256x240.png');
 	background: -moz-linear-gradient(top, #bfd255 0%, #8eb92a 50%, #72aa00 51%, #9ecb2d 100%);
 	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#bfd255), color-stop(50%,#8eb92a), color-stop(51%,#72aa00), color-stop(100%,#9ecb2d));
 	background: -webkit-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
 	background: -o-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
	background: -ms-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bfd255', endColorstr='#9ecb2d',GradientType=0 );
 	background: linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
}

.field_list .ui-widget-header .ui-icon {
	background-image:url('view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_ffffff_256x240.png');
} 

.ui-dialog .ui-widget-header{
	background: -moz-linear-gradient(top, #bfd255 0%, #8eb92a 50%, #72aa00 51%, #9ecb2d 100%);
 	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#bfd255), color-stop(50%,#8eb92a), color-stop(51%,#72aa00), color-stop(100%,#9ecb2d));
 	background: -webkit-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
 	background: -o-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
	background: -ms-linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bfd255', endColorstr='#9ecb2d',GradientType=0 );
 	background: linear-gradient(top, #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%);
 	color:white;
}

.ui-dialog .ui-widget-header .ui-icon{
	background-image:url('view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_ffffff_256x240.png');
	color:white;
}

.ui-state-highlight {
	height: 28px;
	line-height: 2em;
	border: 1px dashed black;
 	background: #eee;
 	-webkit-border-radius: 5px 5px 5px 5px;
 	-moz-border-radius: 5px 5px 5px 5px;
 	-khtml-border-radius: 5px 5px 5px 5px;
 	margin-bottom:8px;
	background-image:url('view/image/drop_here.png');
}

.ui-widget-header .ui-icon {
	background-image:url('view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_222222_256x240.png');
}

.delete_button {
	color: black;
	display: inline-block;
	padding: 2px 6px 2px 6px;
	background: #CCC;
	font-size:10px;
	float:right;
	-webkit-border-radius: 10px 10px 10px 10px;
	-moz-border-radius: 10px 10px 10px 10px;
	-khtml-border-radius: 10px 10px 10px 10px;
	border-radius: 10px 10px 10px 10px;
}
.delete_button:hover {
	cursor:pointer;
}

.profile_name {
	float:left;
}

.field_heading {
	text-align:left;
	margin:15px 0px 0px 0px;
}

.portlet .feed-settings {
	border: 1px solid #AAA/*{borderColorHeader}*/;
	background: #CCC/*{bgColorHeader}*/ 50%/*{bgHeaderXPos}*/ 50%/*{bgHeaderYPos}*/ repeat-x/*{bgHeaderRepeat}*/;
	color: #222/*{fcHeader}*/;
	font-weight: bold;
}

.portlet-header:hover {cursor:move;}
.static .portlet-header:hover {cursor:pointer;}
.static .ui-icon-closethick {visibility:hidden;}
.portlet-header .ui-icon{float:left;}
.portlet-header .ui-icon-closethick{float:right;}
.portlet-header .ui-icon:hover{cursor:pointer;}
.field_content, .field_content_source {
	float:left;
	background-color:#fff;
	margin-right:20px;
	margin-top:20px;
	padding:5px 30px 30px 30px;
	border-radius:10px;
	border:1px solid #999;
	width:202px;
	text-align:center;
}

#content_holder {
	display:none;
}

#field_content_source {
	width:94%;
	border-radius:10px;
	border:1px solid #999;
	padding:5px 30px 30px 30px;
	margin-right:20px;
}

#field_content_source .portlet{
	float:left;
}

.field_text {
	width:95%;
}

#field_content_source .portlet-header .ui-icon-closethick{visibility:hidden;}
.ui-icon-triangle {padding:0px 2px;}

.button:hover {
	cursor: pointer;
}

.form {
	display:none;
}

#dialog-confirm {
	display:none;
}

.info_image{ 
	vertical-align: middle;
	padding-bottom: 3px;
}
</style>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading order_head">
    <h1><img src="view/image/module.png" /><?php echo $heading_title; ?><a href="http://helpdesk.hostjars.com/entries/22366881-any-feed" target="_blank"><img class="info_image" src="view/image/information.png" title="Information About AnyFeed"/></a></h1>
    <div class="buttons">
	    <a id="create-feed" class="button"><?php echo $entry_add_feed; ?></a>
	    <a onclick="return saveFeeds();" class="button"><span><?php echo $button_save; ?></span></a>
	    <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
	<!-- fields to include -->
		<div id="field_content_source" class="draggable">
		  	<h2><?php echo $entry_exclude_fields; ?></h2>
		   	<div id="field_source">
		    	<div class="inital_portlet_placement"></div>
		    	<?php foreach($source_fields as $source_name => $source_fields) {?>
		    		<div class="portlet feed_source">
		    			<div class="portlet-header"><?php echo $source_name; ?></div>
		    			<div class="portlet-content">
						<!-- Field settings common to all fields -->
						<?php foreach($common_field_settings as $common_setting_text => $common_field_setting) {?>
							<?php if($common_setting_text != 'prefix') {?>
								<label for="<?php echo $common_field_setting['name']; ?>"><?php echo $common_setting_text; ?>: </label>
								<?php if($common_field_setting['type'] == 'text') {?>
									<br /><input type="text" class="field_text" name="feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $common_setting_text;?>][value]" value="<?php echo $source_name; ?>"/>
								<?php } ?>
								<?php if($common_field_setting['type'] == 'checkbox') {?>
									<input type="checkbox" class="field_checkbox" name="feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $common_setting_text;?>][value]"/>
								<?php } ?>
								<input type='hidden' name='feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $common_setting_text;?>][type]' class='field_text' value='text'/>
								<input type='hidden' name='feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $common_setting_text;?>][name]' class='field_text' value='<?php echo $common_field_setting['name']; ?>'/>
								<br />
							<?php } ?>
						<?php } ?>
						<!-- Field settings specific to the one field -->
						<?php foreach($source_fields as $setting_text => $field_settings) {?>
							<?php if($setting_text != 'prefix') {?>
								<label for="<?php echo $field_settings['name']; ?>"><?php echo $setting_text; ?>: </label>
								<?php if($field_settings['type'] == 'text') {?>
									<br /><input type="text" class="field_text" name="feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $common_setting_text;?>][value]"/>
								<?php } ?>
								<?php if($field_settings['type'] == 'checkbox') {?>
									<?php ($field_settings['value'] == 1) ? $checked='checked="checked"':$checked='' ;?>
									<input type="checkbox" class="field_checkbox" name="feed_name_replace[fields][<?php echo $source_name; ?>][settings][<?php echo $setting_text;?>][value]" value="<?php echo $field_settings['value'];?>" <?php echo $checked;?>/>
								<?php } ?>
								<br />
							<?php } ?>
						<?php } ?>
						</div>
		    		</div>
		    	<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div id="content_holder" class="field_content">
			</div>
			<div id="dialog-form" title="Create new feed">
			    <p class="validateTips"><?php echo $text_select_name; ?></p>
			    <fieldset>
			        <label for="name"><?php echo $text_feed_name; ?></label>
			        <input type="text" name="feed_name" id="feed_name" value="new_feed"/>
			        <br />
			        <label for="profile"><?php echo $text_feed_profile; ?></label>
			        <select name="profile" id="profile" onchange="updateFeedName($(this).val());">
			        	<?php foreach($profiles as $name => $value) { ?>
			        		<option value="<?php echo $name ?>" class="profile_<?php echo $name ?>"><?php echo $value; ?></option>
			        	<?php } ?>
			        </select>
			    </fieldset>
			</div>
			<div id="dialog-confirm" title="Delete this feed?">
			    <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span> Make sure to press "Save" once changes are completed. This feed will be permanently deleted. Are you sure?</p>
			</div>
		</form>
		</div>
  </div>
</div>

<script type="text/javascript">
function updateFeedName(value) {
	$("#feed_name").val(prettyName(value));
}

function prettyName(name) {
	name = name.replace('/_/g', " ");
	name = name.charAt(0).toUpperCase() + name.slice(1);
	return name;
}

function encodedName(name) {
	name = name.replace(/\s/g, "_");
	name = name.toLowerCase();
	return name;
}

function saveFeeds() {
	$( '#form' ).submit();
}

function updateText(el) {
	if(typeof(el) == 'string') {
		action = $('.settings_'+el).val();
		parent = $('.settings_'+el).parents('.portlet');
	} else {		
		var action = el.value;
		parent = $(el).parents('.portlet');
	}
	if(action == 'CSV') {
		$(parent).find('.XML').hide();
		$(parent).find('.CSV').show();
	} else {
		$(parent).find('.CSV').hide();
		$(parent).find('.XML').show();
	}
}

function updateFieldNames() {
	$('.field_content input').each(function(){
		if($(this).attr('name').match(/feed_name_replace/g)) {
			profile = encodedName($(this).parents('.field_content').find('.profile_name').html());
			oldname = $(this).attr('name')+"";
			name = oldname.replace('feed_name_replace', profile);
			$(this).attr('name', name);
		}
	});
}

$(document).ready(function() {
	//load existing feeds
	var existingFeeds = new Array();
	//get each existing feed values for loading
	<?php foreach($preset_feeds as $name => $value) { 
		echo 'existingFeeds["' .$name . '"] = "' . $value . '";' ."\n";
	} ?>
	//load each existing feed
	for(var key in existingFeeds) {
		createFeed(key, existingFeeds[key], 0);
	}

	//set content field values
	$(".field_text").each(function(index) {
		$(this).val(encodedName($(this).val()));
	});

    $( "#dialog-form" ).dialog({
    	autoOpen: false,
        resizable: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: {
        	"Create a new feed": function() {
            	bValid = true;
				existing_profiles = $('.profile_name').html();
				if(typeof existingFeeds[encodedName($('#feed_name').val())] == 'undefined') {
	                if ( bValid ) {
	                	existingFeeds[encodedName($('#feed_name').val())] = 1;
	                	createFeed($( "#profile" ).val(), $('#feed_name').val(), 1);
	                	$( this ).dialog( "close" );
	                }
				} else {
					updateTips('This feed name already exists');
				}
        	},
       		Cancel: function() {
        		$( this ).dialog( "close" );
        	}
    	},
            close: function() {
            }
    });
 
    $( "#create-feed" )
    	.button()
        .click(function() {
        	$( "#dialog-form" ).dialog( "open" );
    });

	//remove dialog classes
	$( "#create-feed" ).removeClass();
	$( "#create-feed" ).addClass( "button" );

	tips = $( ".validateTips" );
	
	function updateTips( t ) {
         tips
             .text( t )
             .addClass( "ui-state-highlight" );
         setTimeout(function() {
             tips.removeClass( "ui-state-highlight", 1500 );
         }, 500 );
    }

    addedFeed();
    
	//add portlet classes for source portlets
    $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
         .find( ".portlet-header" )
             .addClass( "ui-widget-header ui-corner-all" )
             .prepend( "<span class='ui-icon ui-icon-triangle ui-icon-triangle-1-e'></span><span class='ui-icon ui-icon-closethick'>close</span>")
             .end()
         .find( ".portlet-content" );

	//create the field portlets for field information
    $( ".portlet-header .ui-icon-triangle" ).click(function() {
         $( this ).toggleClass( "ui-icon-triangle-1-s" ).toggleClass( "ui-icon-triangle-1-e" );
         $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
     });

    //remove item from list
 	$( ".ui-icon-closethick" ).click(function() {
 		$( this ).parents('.portlet:first').remove();
 	});
	
	function addedFeed() {
		//create sortable list of fields
	    $( ".field_list" ).each( function() {
	    	$(this).sortable({
	        	containment: $(this),
	        	axis: "y",
	        	placeholder: "ui-state-highlight",
	    	});
	    	$(this).disableSelection();
		});

	    //create the field source sortable list
	   $( ".feed_source" ).draggable({
	   	connectToSortable: ".field_list",
	    helper: function(event) {
	      	return $(this).clone();
	    },
	    cursor: "move",
	    autoOpen: false,
	    stop:function(event, ui){
			//if the target is a feed box, a new field is created
	        if(event.target.className.indexOf('portlet-header') >= 0) {
	           	//binds the expand/close functionality to the triangle on new field boxes
		    	$(".portlet-header .ui-icon-triangle").unbind("click").on("click", function(){
		      	  	$( this ).toggleClass( "ui-icon-triangle-1-s" ).toggleClass( "ui-icon-triangle-1-e" );
		       		$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
		    	});
		    	//binds the close event to new field boxes
				$(".ui-icon-closethick").on("click", function(){
					$( this ).parents('.portlet:first').remove();
				});
				//required to  ensure new input boxes are selectable
			   		$('input[type="text"]').mousedown(function(e){ e.stopPropagation(); });
	            }
				updateFieldNames();
	    	}
	    });

	    $( ".feed-source" ).disableSelection();

		$( ".field_content:last .portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
	    .find( ".portlet-header" )
	        .addClass( "ui-widget-header ui-corner-all" )
	        .prepend( "<span class='ui-icon ui-icon-triangle ui-icon-triangle-1-e'></span><span class='ui-icon ui-icon-closethick'>close</span>")
	        .end()
	    .find( ".portlet-content" );
		
		//create the field portlets for field information
		$( ".field_content:last  .portlet-header .ui-icon-triangle" ).click(function() {
		    $( this ).toggleClass( "ui-icon-triangle-1-s" ).toggleClass( "ui-icon-triangle-1-e" );
		    $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
		});
		$( ".field_content:last  .field_list" ).disableSelection();
		
		//remove item from list
		$( ".field_content:last  .ui-icon-closethick" ).click(function() {
			$( this ).parents('.field_content:first .portlet:first').remove();
		});

		//dialog box for deleting profiles
		$( ".remove" ).unbind("click").on("click", function() {
			//grab the current feed being removed
			item = this;
		    $( "#dialog-confirm" ).dialog({
			    resizable: false,
			    height:180,
			    modal: true,
			    buttons: {
			    	"Delete this feed": function() {
	 		    		//remove the feed from the current feed list
	 		    		profile = encodedName($( item ).parents("h2").find(".profile_name:first").html());
	 		    		delete existingFeeds[encodedName(profile)];
						$( item ).parents(".field_content:first").remove();
			        	$( this ).dialog( "close" );
			        },
			        Cancel: function() {
			        	$( this ).dialog( "close" );
			        }
			    }
			});
		});
	}
    
	
	function createFeed (profile, name, preset) {
		var json_url = 'index.php?route=feed/any_feed/getProfile&feed='+ profile +'&preset=' + preset + '&token=<?php echo $token ?>';
		var json = $.getJSON(json_url, function(data) {
			html = '<div class="field_content">';
			addedDefaults = false;
			 $.each(data, function(key, val) {
					switch(key) {
						case 'name':
							html += '<h2><span class="profile_name">' + prettyName(name) + '</span>&nbsp;<span class="remove delete_button">DELETE</span></h2><input type="hidden" name="feed_name_replace[name]" value="' + encodedName(name) + '"/>';
							break;
						case 'settings':
							settings = JSON.parse(val);
							html += '<div class="portlet static"><div class="portlet-header feed-settings ui-corner-all">Feed Settings</div><div class="portlet-content">';
							for (var key in settings){
								//type input loading
							    if (settings.hasOwnProperty(key)) {
									if(key == 'type') {
										html += "<label for='type'>Feed Type: </label><select class='settings_" + encodedName(name) + "' name='feed_name_replace[settings][type]' onchange='updateText(this);'>";
										var types = ['CSV', 'XML']; 
										for (index = 0; index < types.length; ++index) {
											html += "<option value='"+ types[index] +"'";
											if(settings[key] == types[index]) {
												html += " selected='true'";
											}
											html += ">"+types[index]+"</option>";
										}
										html += "</select><br/>";
										current_type = settings[key];
									}
							    }
							    if(!addedDefaults) {
							    	if (settings.hasOwnProperty(key)) {
										url = document.URL;
										match = '/admin/';
										url = url.substring(0, url.indexOf(match));
										//html += "<label class='feed_url' for='url'>Url: </label><input class='feed_url' name='feed_name_replace[settings][feed_url]' value='" + url + "/index.php?route=feed/any_feed&name=" + encodedName(name) + "' onclick='this.select();' readonly='readonly'><br />";
										html += "<label class='feed_url' for='url'>Feed Url: </label><a class='feed_url' name='feed_name_replace[settings][feed_url]' href='" + url + "/index.php?route=feed/any_feed&name=" + encodedName(name) + "' "; 
										html += "target='_blank'>Click for feed</a><br />";
										html += "<label class='CSV' for='delimiter'>Delimiter: </label><select class='CSV' name='feed_name_replace[settings][delimiter]' class='delimiter'><br class='CSV' />";

                                        var delimiters = [',', ':', ';', '|', '^', '\t'];
										for (index = 0; index < delimiters.length; ++index) {
											html += "<option value='"+ delimiters[index] +"'";
											if(settings.delimiter == delimiters[index]) {
												html += " selected='true'";
											}
											html += ">"+delimiters[index]+"</option>";
										}
										html += "</select>";
                                        html += "<br class='CSV' />";
                                        html += "<label class='CSV' for='filename'>Filename: </label>";
                                        html += "<input class='CSV' name='feed_name_replace[settings][filename]' type='text' value='";
                                        if(settings.filename) {
                                            html += settings.filename;
                                        }
                                        html += "'/><br class='CSV' />";
										html += "<label class='XML' for='cdata'>Use cdata: </label>";
										html += "<input class='XML' name='feed_name_replace[settings][cdata]' type='checkbox' value='1'";
                                        if(settings.cdata == '1') {
												html += ' checked=yes';
										}
										html += "/><br />";
                                        html += "<label class='XML' for='root_tag'>Root tag: </label>";
                                        html += "<input class='XML' name='feed_name_replace[settings][root_tag]' type='text' value='";
                                        if(settings.root_tag) {
                                            html += settings.root_tag;
                                        }
                                        html += "'/><br class='XML' />";

                                        html += "<label class='enable' for='enable'>Enable Feed: </label>";
										html += "<input class='enable' name='feed_name_replace[settings][enable]' type='checkbox' value='1'";
										if(settings.enable == '1') {
											html += ' checked=yes';
										}
										html += "/><br />";
									}
									addedDefaults = true;
								}
							}
							html += '</div></div>';
							break;
						case 'fields':
							html += '<h2 class="field_heading">Fields</h2>';
							html += '<div class="field_list"><table><tr><td class="spacer">&nbsp;<td></tr></table>';
							fields = JSON.parse(val);
							for (var key in fields){
								html += '<div class="portlet">';
								html += '<div class="portlet-header">' + key + '</div>';
								html += '<div class="portlet-content">';
								for (var value in fields[key]['settings']) {
									if (fields[key]['settings'].hasOwnProperty(value)) {
										field_settings = fields[key]['settings'][value];
										if(field_settings['type'] == 'text') {
											html += "<label for="+field_settings['name']+"'>" + value + ":</label>";
											html += "<br /><input name='feed_name_replace[fields]["+key+"][settings]["+value+"][value]' type='text' class='field_text' value='" + field_settings['value'] + "'/><br />";
											html += "<input type='hidden' name='feed_name_replace[fields]["+key+"][settings]["+value+"][type]' class='field_text' value='text'/>";
											html += "<input type='hidden' name='feed_name_replace[fields]["+key+"][settings]["+value+"][name]' class='field_text' value='" + field_settings['name'] + "'/>";
										}
										if(field_settings['type'] == 'checkbox') {
											html += "<label for="+field_settings['name']+"'>" + value + "</label>";
											html += "<input name='feed_name_replace[fields]["+key+"][settings]["+value+"][value]' type='checkbox' value='1'";
											if(field_settings['value'] == '1') {
												html += ' checked=yes';
											}
											html += "/><br />";
											html += "<input type='hidden' name='feed_name_replace[fields]["+key+"][settings]["+value+"][type]' class='field_text' value='checkbox'/>";
											html += "<input type='hidden' name='feed_name_replace[fields]["+key+"][settings]["+value+"][name]' class='field_text' value='" + field_settings['name'] + "'/>";
										}
									}
								}
								html += '</div>';
								html += '</div>';
							}	
							html += '</div>';
							break;
					}
			  });
			html += "</div>";
			html = html.replace(/feed_name_replace/g, encodedName(name));
			$(".field_content:last").after(html);
			updateText(encodedName(name));
			addedFeed();
		});
	}
});    
</script>
<?php echo $footer; ?>