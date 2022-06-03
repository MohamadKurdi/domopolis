<?php echo $header; ?>
<div id='content'>
    <ul class='breadcrumb'>
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
            <a href='<?php
				echo $breadcrumb[' href '];
				?>'>
                <?php echo $breadcrumb[ 'text']; ?>
            </a>
        </li>
        <?php } ?> </ul>
    <?php if ($error_warning) { ?>
    <div class='warning'>
        <?php echo $error_warning; ?>
    </div>
    <?php } ?>
    <div class='box'>
        <div class='heading'>
            <h1><?php
		echo $heading_title;
		?></h1>
            <div class='buttons'>
                <a onclick='$("#form").submit();' class='btn btn-success'>
                    <?php echo $button_save; ?>
                </a>
                <a onclick='location = "<?php
		echo $cancel;
		?>";' class='btn btn-danger'>
                    <?php echo $button_cancel; ?>
                </a>
            </div>
        </div>
        <div class='content'>
            <form action='<?php
		echo $action;
		?>' method='post' enctype='multipart/form-data' id='form'>
                <div id='tab-microdata' class='htabs'>
                    <a href='#tab-schemaorg'>
                        <?php echo $tab_schemaorg; ?>
                    </a>
                    <a href='#tab-opengraph'>
                        <?php echo $tab_opengraph; ?>
                    </a>
                    <a href='#tab-twittercard'>
                        <?php echo $tab_twittercard; ?>
                    </a>
                </div>
                <div id='tab-schemaorg' class='htabs-content'>
                    <table class='form'>
                        <tr>
                            <td style='padding-left:45px; width:auto'>
                                <?php echo $text_status; ?>
                            </td>
                            <td>
                                <select name='schemaorg_status'>
                                    <option value='1' <?php if ($schemaorg_status=='1' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_enabled; ?>
                                    </option>
                                    <option value='2' <?php if ($schemaorg_status=='2' ) echo 'selected="selected"'; ?>
                                        <?php if ($schemaorg_status=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_disabled; ?>
                                    </option>
                                </select>
                            </td>
                            <td style='padding-left:40px'>
                                <?php echo $text_island_type; ?>
                            </td>
                            <td>
                                <select name='schemaorg_island'>
                                    <option value='1' <?php if ($schemaorg_island=='1' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_island_1; ?>
                                    </option>
                                    <option value='2' <?php if ($schemaorg_island=='2' ) echo 'selected="selected"'; ?>
                                        <?php if ($schemaorg_island=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_island_2; ?>
                                    </option>
                                    <option value='3' <?php if ($schemaorg_island=='3' ) echo 'selected="selected"'; ?>
                                        <?php if ($schemaorg_island=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_island_3; ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding-left:45px; width:auto'></td>
                            <td></td>
                            <td style='padding-left:40px'>
                                <?php echo $text_price; ?>
                            </td>
                            <td>
                                <select name='schemaorg_price'>
                                    <option value='1' <?php if ($schemaorg_price=='1' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_price_1; ?>
                                    </option>
                                    <option value='2' <?php if ($schemaorg_price=='2' ) echo 'selected="selected"'; ?>
                                        <?php if ($schemaorg_price=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_price_2; ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4' style='text-align:center; width:auto'><img src='view/image/microdata/rdf.jpg' alt=''>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center; width:auto'><img src='view/image/microdata/island-type-1.jpg' alt=''>
                            </td>
                            <td colspan='2' style='text-align:center'><img src='view/image/microdata/island-type-2.jpg' alt=''>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center; width:auto'><img src='view/image/microdata/island-type-3.jpg' alt=''>
                            </td>
                            <td colspan='2' style='text-align:center'><img src='view/image/microdata/island-type-4.jpg' alt=''>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id='tab-opengraph' class='htabs-content'>
                    <table class='form'>
                        <tr>
                            <td style='padding-left:45px; width:auto'>
                                <?php echo $text_status; ?>
                            </td>
                            <td>
                                <select name='opengraph_status'>
                                    <option value='1' <?php if ($opengraph_status=='1' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_enabled; ?>
                                    </option>
                                    <option value='2' <?php if ($opengraph_status=='2' ) echo 'selected="selected"'; ?>
                                        <?php if ($opengraph_status=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_disabled; ?>
                                    </option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center; width:auto'><img src='view/image/microdata/fbog-1.jpg' alt=''>
                            </td>
                            <td colspan='2' style='text-align:center'><img src='view/image/microdata/fbog-2.jpg' alt=''>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id='tab-twittercard' class='htabs-content'>
                    <table class='form'>
                        <tr>
                            <td style='padding-left:45px; width:auto'>
                                <?php echo $text_status; ?>
                            </td>
                            <td>
                                <select name='twittercard_status'>
                                    <option value='1' <?php if ($twittercard_status=='1' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_enabled; ?>
                                    </option>
                                    <option value='2' <?php if ($twittercard_status=='2' ) echo 'selected="selected"'; ?>
                                        <?php if ($twittercard_status=='' ) echo 'selected="selected"'; ?>>
                                        <?php echo $text_disabled; ?>
                                    </option>
                                </select>
                            </td>
                            <td style='padding-left:40px'>
                                <?php echo $text_twitter_site; ?>
                            </td>
                            <td>
                                <input type='text' name='twitter_site' placeholder='<?php
									echo $text_twitter_place;
									?>' value='<?php
									echo $twitter_site;
									?>' /> </td>
                        </tr>
                        <tr>
                            <td style='padding-left:45px; width:auto'></td>
                            <td></td>
                            <td style='padding-left:40px'>
                                <?php echo $text_twitter_creator; ?>
                            </td>
                            <td>
                                <input type='text' name='twitter_creator' placeholder='<?php
									echo $text_twitter_place;
									?>' value='<?php
									echo $twitter_creator;
									?>' /> </td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align:center; width:auto'><img src='view/image/microdata/twittercard-1.jpg' alt=''>
                            </td>
                            <td colspan='2' style='text-align:center'><img src='view/image/microdata/twittercard-2.jpg' alt=''>
                            </td>
                        </tr>
                    </table>
                </div>             
            </form>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $('#tab-microdata a').tabs();
</script>
<?php echo $footer; ?>