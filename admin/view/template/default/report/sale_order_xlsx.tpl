      <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_date_start; ?></td>
              <td class="left"><?php echo $column_date_end; ?></td>
              <td class="center">Заказов</td>

              <?php if ($this->config->get('config_enable_amazon_specific_modes')){ ?>
                <?php foreach (\hobotix\RainforestAmazon::amazonOffersType as $type) { ?>
                  <td class="center" style="width:50px;"><?php echo $type; ?></td>
                <?php } ?>
              <?php } ?>

              <td class="center">Товаров</td>

              <?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
               <td class="right">Мин. рентабельность</td>
               <td class="right">Ср. рентабельность</td>
               <td class="right">Макс рентабельность</td>
             <?php } ?>

             <td class="right">Ср. чек, <?php echo $this->config->get('config_currency');?></td>
             <td class="right">Ср. чек, <?php echo $this->config->get('config_regional_currency');?></td>
             <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_currency');?></td>
             <td class="right"><?php echo $column_total; ?>, <?php echo $this->config->get('config_regional_currency');?></td>
           </tr>
         </thead>
         <tbody>
          <?php if (!empty($orders)) { ?>
            <?php foreach ($orders as $order) { ?>
              <?php include(dirname(__FILE__) . '/sale_order_orders.tpl'); ?>
            <?php } ?>
          <?php } ?>          

          <?php if (!empty($categories)) { ?>
            <?php foreach ($categories as $category) { ?>
              <tr>
                <td style="border-top:2px solid black;" colspan="<?php if ($this->config->get('config_show_profitability_in_order_list')) { ?>16<?php } else { ?>11<?php } ?>">
                  <b style="font-size:16px; color:<?php if ($category['orders']) { ?>#51A62D<?php } else { ?>#e16a5d<?php } ?>"><?php echo $category['category']; ?></b>
                </td>
              </tr>

              <?php foreach ($category['orders'] as $order) { ?>
                <?php include(dirname(__FILE__) . '/sale_order_orders.tpl'); ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>