<?php echo $header; ?>
<style>
.list th{
border:1px solid;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">    
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /><?php echo $status_title ?> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="list">
      <thead>
        <tr>
            <th>Номер заказа</th>
            <th>Сумма</th>
            <th>Пользователь</th>
            <th>email</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($viewstatuses as $rows):
          echo "<tr>";
          echo "<td>".$rows['num_order']."</td>";
          echo "<td>".$rows['sum']."</td>";
          echo "<td>".$rows['user']."</td>";
          echo "<td>".$rows['email']."</td>";
          echo "<td>".$rows['date_enroled']."</td>";
          echo "</tr>";
          endforeach;
        ?>
        </tbody>
        </table>

  </div>
</div>
</div>
<?php echo $footer; ?> 

