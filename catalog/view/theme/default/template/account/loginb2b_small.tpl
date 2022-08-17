<h1>Вход для клиентов b2b</h1>
  <div class="reg_user">
  <div class="content_div">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <p>Войти Кабинет:</p>
          <!-- <b></b><br /> -->
          <input placeholder="<?php echo $entry_email; ?>" type="text" name="email" value="<?php echo $email; ?>" />
          <br />
          <br />
          <!-- <b></b><br /> -->
          <input placeholder="<?php echo $entry_password; ?>" type="password" name="password" value="<?php echo $password; ?>" />
          <br />
          <a class ="forgotten" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
          <br />
          <input type="submit" value="<?php echo $button_login; ?>" class="button" />
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
      </form>
  </div>
 </div> 
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 