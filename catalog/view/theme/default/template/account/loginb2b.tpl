<?php echo $header; ?>
<?php if ($success) { ?>
  <div class="wrap">
    <div class="success">
      <?php echo $success; ?>      
    </div>
  </div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" title="<?php echo $breadcrumb['text']; ?>" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a></span>
    <?php } ?>
  </div>

  <h1><?=$text_login_b2b ?></h1>
<!--   <div class="new_user">
    <p class="title_div">Новый пользователь</p>
    <div class="content_div">
        <p><b>Регистрация</b></p>
        <p>Зарегистрируйтесь на Kitchen-Profi | Россия и вы сможете: <br>
<br>
1. Делать свои покупки быстрее (не нужно будет постоянно вводить адрес доставки, контактную информацию); <br>
<br>
2. В своём личном кабинете просматривать состояние заказа, заказы сделанные ранее, добавлять товары в список желаний (закладки), а так же видеть оплаты, состояние Вашего личного счета.<br>
<br>
3. <a href="/information/#discounts">Получать скидки</a>, а также информацию про новинки и специальные предложения на электронную почту. 
</p>
        <a href="<?php //echo $registerb2b; ?>" class="button">Продолжить</a>
    </div>
  </div> -->
  <div class="reg_user">
    <!-- <p class="title_div">Зарегистрированный пользователь</p> -->
  
  <div class="content_div">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <p><?=$text_login_cabinet ?>:</p>
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
  <div class="info_menu_block">
      <ul class="info_menu_list">
  <li><a class="info_menu how_order" href="/information/#how_order">Как заказать</a></li>
  <li><a class="info_menu delivery_payment" href="/information/#payment">Оплата</a></li>
  <li><a class="info_menu delivery_payment" href="/information/#delivery">Доставка</a></li>
  <li><a class="info_menu discounts" href="/information/#discounts">Скидки</a></li>
  <li><a class="info_menu return" href="/information/#return">Возврат</a></li><!--  -->
  <li><a class="info_menu reliability" href="/information/#reliability">Надежность</a></li>
  <li><a class="info_menu present_sertificate" href="/information/#present_sertificate">Подарочные сертификаты</a></li>
  <li><a class="info_menu partner_program" href="/information/#partner_program">Партнерская программа</a></li>
</ul>
</div>
 </div> 
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
<?php echo $footer; ?>