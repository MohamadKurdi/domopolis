<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading order_head">
     <h1> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td>
			  <input type="hidden" name="call_id" value="<?php echo $callback_id; ?>"  disabled='disabled' />
			  <?php echo $text_name; ?></td>
              <td>			
			   <? if ($customer_href) { ?>
				 <a href='<?php echo $customer_href; ?>' target="_blank"><?php echo $name ?></a> (<? echo $real_customer['firstname'] .' '.$real_customer['lastname'];?>, id: <?php echo $customer_id; ?>) <i style="font-size:16px; cursor:pointer;" class="fa fa-sign-in go_to_store" data-customer-id="<?php echo $customer_id; ?>" data-store-id="<?php echo $real_customer['store_id']; ?>"></i>
				 
				 <script type="text/javascript">
							$(document).ready(function(){
								$('.go_to_store').on('click', function(){
										var store_id = $(this).attr('data-store-id');
										var customer_id = $(this).attr('data-customer-id');
		
										swal({ title: "Перейти в магазин?", text: "В личный кабинет покупателя", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, перейти!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
										window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id='+customer_id+'&store_id=' + store_id)
									});
		
								});		
							});
					 </script>
			   <? } else { ?>
					<?php  echo $name  ?>
			   <? } ?>
            </tr>
            <tr>
              <td><?php echo $text_telephone; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>"  disabled='disabled' /><span class='click2call' data-phone="<?php echo $telephone; ?>"></span></td>
            </tr>
            <tr>
              <td> <?php echo $text_comment; ?></td>
              <td><textarea style="width: 50%" rows="4" cols="40" name="comment"   ><?php echo $comment; ?></textarea></td>
            </tr>
			<tr>
              <td> <?php echo $text_comment_buyer; ?></td>
              <td><textarea style="width: 50%" rows="4" cols="40" name="comment_buyer"   disabled><?php echo $comment_buyer; ?></textarea></td>
            </tr>
			<tr>
              <td> <?php echo $text_email_buyer; ?></td>
              <td><input type="text" name="name" value="<?php echo $email_buyer; ?>"/></td>
            </tr>
			<tr>
			<tr>
				<td>Менеджер:</td>
				<td>
					<? echo $manager; ?>
				</td>
			</tr>
            <tr>
              <td> <?php echo $text_status; ?></td>
            <td><select name="status_id">

                <?php if ($status_id == '0') { ?>
                <option value="0" selected="selected"><?php echo $status_wait; ?></option>
                <option value="1" ><?php echo $status_done; ?></option>
                <?php } else { ?>
                <option value="0" ><?php echo $status_wait; ?></option>
                <option value="1" selected="selected"><?php echo $status_done; ?></option>
                <?php } ?>
 
              </select></td>
            </tr>
            <tr>
              <td> <?php echo $text_added; ?></td>
              <td><input type="text" name="date_added" value="<?php echo $date_added; ?>"  disabled/>
	      </td>
            </tr>
            <tr>
              <td> <?php echo $text_modified; ?></td>
              <td><input type="text" name="date_modified" value="<?php echo $date_modified; ?>"  disabled/>
	      </td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>
