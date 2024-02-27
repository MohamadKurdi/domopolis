 <tr>
  <td class="left">
    <b><?php echo $order['date_start']; ?></b>
  </td>
  <td class="left">
    <b><?php echo $order['date_end']; ?></b>
  </td>

  <td class="center">
    <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#CF4A61; color:#FFF; white-space:nowrap;"><?php echo $order['orders']; ?></span>
  </td>

  <?php if ($this->config->get('config_enable_amazon_specific_modes')){ ?>
    <?php foreach (\hobotix\RainforestAmazon::amazonOffersType as $type) { ?>
      <td class="center">
        <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#e16a5d; color:#FFF; white-space:nowrap;"><?php echo $order['amazon_offers_types'][$type]; ?>%</span>
      </td>
    <?php } ?>
  <?php } ?>

  <td class="center">
    <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#FF7815; color:#FFF; white-space:nowrap;"><?php echo $order['products']; ?></span>
  </td>

  <?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
   <td class="right">
    <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['min_profitability']; ?> %</span>
  </td>

  <td class="right">
    <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['avg_profitability']; ?> %</span>
  </td>

  <td class="right">
    <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#FFF; white-space:nowrap;"><?php echo $order['max_profitability']; ?> %</span>
  </td>
<?php } ?>

<td class="right">
  <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#7F00FF; color:#FFF; white-space:nowrap;"><?php echo $order['avg_total']; ?></span>
</td>

<td class="right">
  <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#7F00FF; color:#FFF; white-space:nowrap;"><?php echo $order['avg_total_national']; ?></span>
</td>

<td class="right">
  <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#51A62D; color:#FFF; white-space:nowrap;"><?php echo $order['total']; ?></span>
</td>

<td class="right">
  <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#51A62D; color:#FFF; white-space:nowrap;"><?php echo $order['total_national']; ?></span>
</td>
</tr>