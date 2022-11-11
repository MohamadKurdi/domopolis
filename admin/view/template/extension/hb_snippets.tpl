<?php echo $header; ?>
<link rel="stylesheet" href="view/stylesheet/css/hb-oc-bootstrap.css">
<link rel="stylesheet" href="view/stylesheet/css/hb-oc-bootstrap-theme.min.css">
<script src="view/stylesheet/js/bootstrap.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

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
  <div class="heading">
    <h1><img src="view/image/setting.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#hbform').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
        	<h4 style="color:#009900;"><?php echo $text_about; ?></h4><br />
			<h4><b>TESTING TOOL: <a href="https://developers.google.com/structured-data/testing-tool/" target="_blank">https://developers.google.com/structured-data/testing-tool/</a></b></h4><br />

  			<center><div id='loadgif' style='display:none;'><img src='view/image/loading-bar.gif'/></div></center>
			<div id="msgoutput" style="text-align:center;"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="hbform">
			<div id="tabs" class="htabs">
				<a href="#tab-kg"><?php echo $tab_kg; ?></a>
				<a href="#tab-product"><?php echo $tab_product; ?></a>
				<a href="#tab-contact"><?php echo $tab_contact; ?></a>
				<a href="#tab-breadcrumb"><?php echo $tab_breadcrumb; ?></a>
				<a href="#tab-og"><?php echo $tab_og; ?></a>
			</div>
			
			<div id="tab-kg">
			<style>.dashboard-content{min-height:0px}</style>
				<div class="latest">
				<div class="dashboard-heading"><?php echo $text_kg_logo; ?></div>
				<div class="dashboard-content">
				<table class="form">
					<tr>
						<td>Enable Logo URL</td>
						<td><select name="hb_snippets_logo_url" class="form-control">
			  <option value="1" <?php echo ($hb_snippets_logo_url == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
			  <option value="0" <?php echo ($hb_snippets_logo_url == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
			 </select></td>
					</tr>
				</table>
				</div>
				</div>
				<br />
				<div class="latest">
				<div class="dashboard-heading"><?php echo $text_kg_contact; ?></div>
				<div class="dashboard-content">
				<table class="form">
					<tr>
						<td>Enable Contacts to be shown in Search Results</td>
						<td><select name="hb_snippets_contact_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_contact_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_contact_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
					<tr>
						<td colspan="2">
						<div id="corp_contact">
							<?php $contact_row = 0; ?>
							<?php if ($hb_snippets_contact){ ?>
							<?php foreach ($hb_snippets_contact as $contact){ ?>							
								<div class="form-group" id="contact-row<?php echo $contact_row; ?>">
								<table class="form"><tr>
								<td><div class="col-sm-4"><input type="text" size="100" placeholder="+1-401-555-1212" name="hb_snippets_contact[<?php echo $contact_row; ?>][n]" value="<?php echo $contact['n']; ?>"></div></td>
								<td><div class="col-sm-4"><select name="hb_snippets_contact[<?php echo $contact_row; ?>][t]">
								<option <?php echo ($contact['t'] == 'Customer Service')? 'selected':''; ?> >Customer Service</option>
								<option <?php echo ($contact['t'] == 'Customer Support')? 'selected':''; ?> >Customer Support</option>
								<option <?php echo ($contact['t'] == 'Technical Support')? 'selected':''; ?> >Technical Support</option>
								<option <?php echo ($contact['t'] == 'Billing Support')? 'selected':''; ?> >Billing Support</option>
								<option <?php echo ($contact['t'] == 'Bill Payment')? 'selected':''; ?> >Bill Payment</option>
								<option <?php echo ($contact['t'] == 'Sales')? 'selected':''; ?> >Sales</option>
								<option <?php echo ($contact['t'] == 'Reservations')? 'selected':''; ?> >Reservations</option>
								<option <?php echo ($contact['t'] == 'Credit Card Support')? 'selected':''; ?> >Credit Card Support</option>
								<option <?php echo ($contact['t'] == 'Emergency')? 'selected':''; ?> >Emergency</option>
								<option <?php echo ($contact['t'] == 'Baggage Tracking')? 'selected':''; ?> >Baggage Tracking</option>
								<option <?php echo ($contact['t'] == 'Roadside Assistance')? 'selected':''; ?> >Roadside Assistance</option>
								<option <?php echo ($contact['t'] == 'Package Tracking')? 'selected':''; ?> >Package Tracking</option>
								</select>
								</div></td>
								<td><div class="col-sm-2">
								<a onclick="$('#contact-row<?php echo $contact_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a></div></td>
								</tr></table></div>
								<?php $contact_row++; ?>
							<?php } ?>	
							<?php } ?>	
							</div>
							<a onclick="addcontact();" class="btn btn-default">ADD CONTACT NUMBER</a>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</table>
				</div>
				</div>
				<br />
				<div class="latest">
				<div class="dashboard-heading"><?php echo $text_kg_social; ?></div>
				<div class="dashboard-content">
				<table class="form">
					<tr>
						<td>Enable Social Profile to be shown in Search Results</td>
						<td><select name="hb_snippets_social_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_social_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_social_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
					<tr>
						<td colspan="2"><div id="corp_social">
									<?php $social_row = 0; ?>
									<?php if ($hb_snippets_socials){ ?>
									<?php foreach ($hb_snippets_socials as $social){ ?>							
										<div class="form-group" id="social-row<?php echo $social_row; ?>">
										<table class="form"><tr>
										<td><div class="col-sm-8"><input type="text" size="130" placeholder="https://www.facebook.com/your-profile" name="hb_snippets_socials[<?php echo $social_row; ?>]" value="<?php echo $social; ?>"></div></td>
										<td><div class="col-sm-2">
										<a onclick="$('#social-row<?php echo $social_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a></div></td>
										</tr></table></div>
										<?php $social_row++; ?>
									<?php } ?>	
									<?php } ?>	
								</div>
							<a onclick="addsocial();" class="btn btn-default">ADD SOCIAL PROFILE LINKS</a></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</table>
				</div>
				</div>
				<br />
				<div class="latest">
				<div class="dashboard-heading"><?php echo $text_kg_searchbox; ?></div>
				<div class="dashboard-content">
				<table class="form">
					<tr>
						<td>Enable Sitelinks Search Box to be shown in Search Results</td>
						<td><select name="hb_snippets_search_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_search_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_search_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
				</table>
				</div>
				</div>
				<br />
				<div class="latest">
				<div class="dashboard-heading"><i class="fa fa-gears"></i> <?php echo $text_kg_generate; ?></div>
				<div class="dashboard-content">
				<table class="form">
					<tr>
						<td>Enable Knowledge Graph</td>
						<td><select name="hb_snippets_kg_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_kg_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_kg_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
					<tr>
						<td></td>
						<td><a onclick="generate_kg();" class="btn btn-warning">Generate JSON-LD Markup for Knowledge Graph</a></td>
					</tr>
					<tr>
						<td></td>
						<td><textarea name="hb_snippets_kg_data"  id="hb_snippets_kg_data" rows="10" cols="150"><?php echo $hb_snippets_kg_data; ?>
									 </textarea></td>
					</tr>
				</table>
				</div>
				</div>
			</div>
			<div id="tab-contact">
				<h4><?php echo $text_header_local; ?></h4>
					<table class="table table-hover">
			              <tr>
			                <td><?php echo $col_business_name; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_local_name" id="hb_snippets_local_name" value="<?php echo $hb_snippets_local_name;?>" /></td>
			              </tr>
						 <tr>
			                <td><?php echo $col_address; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_local_st" id="hb_snippets_local_st" value="<?php echo $hb_snippets_local_st;?>" /></td>
			              </tr>
			              <tr>
			                <td><?php echo $col_locality; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_local_location" id="hb_snippets_local_location" value="<?php echo $hb_snippets_local_location;?>" /></span></td>
			              </tr>
						  <tr>
			                <td><?php echo $col_state; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_local_state" id="hb_snippets_local_state" value="<?php echo $hb_snippets_local_state;?>" /></td>
			              </tr>
			              <tr>
			                <td><?php echo $col_postal; ?></td>
			               <td><input type="text" class="form-control" name="hb_snippets_local_postal" id="hb_snippets_local_postal" value="<?php echo $hb_snippets_local_postal;?>" /></td>
			              </tr>
						  <tr>
			                <td><?php echo $col_country; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_local_country" id="hb_snippets_local_country" value="<?php echo $hb_snippets_local_country;?>" /></td>
			              </tr>
						  						  <!--2 new parameter added-->
						  <tr>
			                <td><?php echo $col_store_image; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_store_image" id="hb_snippets_store_image" value="<?php echo $hb_snippets_store_image;?>" placeholder = "Enter your Local Store Picture Link" required/></td>
			              </tr>
						  <tr>
			                <td><?php echo $col_price_range; ?></td>
			                <td><input type="text" class="form-control" name="hb_snippets_price_range" id="hb_snippets_price_range" value="<?php echo $hb_snippets_price_range;?>" placeholder="$0 to $20000" required /></td>
			              </tr>
						  <tr>
						  <td></td>
						  <td><a class="btn btn-primary" onclick="generatelocalsnippet()"><?php echo $btn_generate; ?></a> <span id='loadgif2' style='display:none;'><img src='view/image/loading.gif'/></span></td>
						  </tr>
						  <tr>
			                <td><?php echo $col_local_snippet; ?></td>
			               <td><textarea name="hb_snippets_local_snippet" id="hb_snippets_local_snippet" rows="10" cols="60"><?php echo $hb_snippets_local_snippet;?></textarea></td>
			              </tr>
						  <tr>
					          <td><?php echo $col_enable; ?></td>
					          <td><select name="hb_snippets_local_enable" class="form-control">
							  <option value="y" <?php echo ($hb_snippets_local_enable == 'y')? 'selected':''; ?> >Yes</option>
							  <option value="n" <?php echo ($hb_snippets_local_enable == 'n')? 'selected':''; ?> >No</option>
							  </select></td>
					</tr>
			       </table>
			</div>
			<div id="tab-product">
				<p><i class="fa fa-info-circle"></i> Including structured data markup in web content helps Google algorithms better index and understand the content. Some data can also be used to create and display Rich Snippets within the search results. Information about a product, including price, availability, and review ratings will appear in
				search results.</p><br />
				<p><i class="fa fa-info-circle"></i> Product Information Rich Snippet is automatically installed by this extension provided you have a working vqmod. To check product page structured data, <a href="https://developers.google.com/structured-data/testing-tool/" target="_blank">https://developers.google.com/structured-data/testing-tool/</a> .</p><br />
				<table class="form">
					<tr>
						<td>Enable Product Page Rich Snippet</td>
						<td><select name="hb_snippets_prod_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_prod_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_prod_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
				</table>
			</div>
			
			<div id="tab-breadcrumb">
			<table class="form">
					<tr>
						<td>Enable Breadcrumbs</td>
						<td><select name="hb_snippets_bc_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_bc_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_bc_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
				</table>
			</div>
			
			<div id="tab-og">
				<p><i class="fa fa-info-circle"></i> The Open Graph protocol enables any web page to become a rich object in a social graph. For instance, this is used on Facebook to allow any web page to have the same functionality as any other object on Facebook.
				While many different technologies and schemas exist and could be combined together, there isn't a single technology which provides enough information to richly represent any web page within the social graph. The Open Graph protocol builds on these existing technologies and gives developers one thing to implement.
				<br> </p> <p><i class="fa fa-info-circle"></i> OpenGraph is automatically installed by this extension provided you have a working vqmod. To check OpenGraph <a href="http://opengraphcheck.com/" target="_blank">http://opengraphcheck.com/</a> .</p><br />
				<table class="form">
					<tr>
						<td>Enable OpenGraph Protocol</td>
						<td><select name="hb_snippets_og_enable" class="form-control">
									  <option value="1" <?php echo ($hb_snippets_og_enable == '1')? 'selected':''; ?> ><?php echo $text_enable; ?></option>
									  <option value="0" <?php echo ($hb_snippets_og_enable == '0')? 'selected':''; ?> ><?php echo $text_disable; ?></option>
									 </select></td>
					</tr>
					<tr>
						<td>Product Title Pattern (SHORTCODES: {name}, {price}, {brand}, {model})</td>
						<td><input name="hb_snippets_ogp" size="100" value="<?php echo $hb_snippets_ogp; ?>"></td>
					</tr>
					<tr>
						<td>Category Title Pattern (SHORTCODES: {name})</td>
						<td><input name="hb_snippets_ogc" size="100" value="<?php echo $hb_snippets_ogc; ?>"></td>
					</tr>
				</table>
			</div>
          </form>
		  </div>  	
  <br /><center> 
</div>

<script type="text/javascript">
$('#tabs a').tabs(); 
function generatelocalsnippet(){
$('#loadgif2').show();
	$.ajax({
		  type: 'post',
		  url: 'index.php?route=extension/hb_snippets/generatelocalsnippet&token=<?php echo $token; ?>',
		  data: {name: $('#hb_snippets_local_name').val(), street: $('#hb_snippets_local_st').val(), location: $('#hb_snippets_local_location').val(), postal:$('#hb_snippets_local_postal').val(),
		   state:$('#hb_snippets_local_state').val(), country:$('#hb_snippets_local_country').val(), store_image:$('#hb_snippets_store_image').val(), price_range:$('#hb_snippets_price_range').val()  },
		  dataType: 'json',
		  success: function(json) {
				if (json['success']) {
						var ss = json['success'];
					  	$('#hb_snippets_local_snippet').val(ss);
					   $('#loadgif2').hide();
				}
		  },			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	 });
}
function generate_kg(){
	$.ajax({
		  type: 'post',
		  url: 'index.php?route=extension/hb_snippets/generatekg&token=<?php echo $token; ?>',
		  dataType: 'json',
		  success: function(json) {
				if (json['success']) {
						var ss = json['success'];
					  	$('#hb_snippets_kg_data').val(ss);
				}
		  },			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	 });
}
</script>
<script type="text/javascript">
var contact_row = <?php echo $contact_row; ?>;
function addcontact(){

html  = '<div class="form-group"  id="contact-row' + contact_row + '"><table class="form"><tr>';
html += '<td><div class="col-sm-4"><input type="text" size="100" placeholder="+1-401-555-1212" name="hb_snippets_contact[' + contact_row + '][n]"></div></td>';
html += '<td><div class="col-sm-4"><select name="hb_snippets_contact[' + contact_row + '][t]">';
html += '<option>Customer Service</option>';
html += '<option>Customer Support</option>';
html += '<option>Technical Support</option>';
html += '<option>Billing Support</option>';
html += '<option>Bill Payment</option>';
html += '<option>Sales</option>';
html += '<option>Reservations</option>';
html += '<option>Credit Card Support</option>';
html += '<option>Emergency</option>';
html += '<option>Baggage Tracking</option>';
html += '<option>Roadside Assistance</option>';
html += '<option>Package Tracking</option>';
html += '</select></div></td>';
html += '<td><div class="col-sm-2"><a onclick="$(\'#contact-row' + contact_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a></div></td>';
html += '</tr></table></div>';
$('#corp_contact').append(html);
contact_row++;
}
var social_row = <?php echo $social_row; ?>;
function addsocial(){

html  = '<div class="form-group" id="social-row' + social_row + '"><table class="form"><tr>';
html += '<td><div class="col-sm-8"><input type="text" placeholder="https://www.facebook.com/your-profile" name="hb_snippets_socials[' + social_row + ']" size="130"></div></td>';
html += '<td><div class="col-sm-2"><a onclick="$(\'#social-row' + social_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="button"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></a></div></td>';
html += '</tr></table></div>';
$('#corp_social').append(html);
social_row++;
}
</script>
<?php echo $footer; ?>