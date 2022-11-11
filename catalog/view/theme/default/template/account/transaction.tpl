<?php echo $header; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<div id="content"  class="account_wrap"><?php echo $content_top; ?>
  <div class="wrap two_column">
    <div class="side_bar">
        <?php echo $column_left; ?>
    </div>
    <div class="account_content">
  <p><?php echo $text_total; ?><b> <?php echo $total; ?></b>.</p>
  <div class="table-adaptive">
    <table class="list list-transactions">
      <thead>
        <tr>
          <td class="left"><?php echo $column_date_added; ?></td>
          <td class="left"><?php echo $column_description; ?></td>
          <td class="right"><?php echo $column_amount; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($transactions) { ?>
        <?php foreach ($transactions  as $transaction) { ?>
        <tr>
          <td class="left"><?php echo $transaction['date_added']; ?></td>
          <td class="left"><?php echo $transaction['description']; ?></td>
          <td class="right"><?php echo $transaction['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="5"><?php echo $text_empty; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="btn btn-acaunt"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
  </div>
  </div>
<?php echo $footer; ?>