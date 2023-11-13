<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td>
            <p><?php echo $entry_date_start; ?></p>
            <input type="date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" />
          </td>

          <td>
            <p><?php echo $entry_date_end; ?></p>
            <input type="date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" />
          </td>

          <td>
            <p><?php echo $entry_status; ?></p>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </td>

          <td>
              <p><i class="fa fa-sort"></i>&nbsp;&nbsp;Сортировать</p>
              <select name="filter_sort">
                <option value="*">- Как-нибудь -</option>
                <option value="quantity-desc" <?php if ($filter_sort == 'quantity-desc') { ?>selected="selected"<?php } ?>>Больше количество</option>
                <option value="quantity-asc" <?php if ($filter_sort == 'quantity-asc') { ?>selected="selected"<?php } ?>>Меньше количество</option>
                <option value="total-desc" <?php if ($filter_sort == 'total-desc') { ?>selected="selected"<?php } ?>>Больше сумма</option>
                <option value="total-asc" <?php if ($filter_sort == 'total-asc') { ?>selected="selected"<?php } ?>>Меньше сумма</option>
                <option value="name-asc" <?php if ($filter_sort == 'name-asc') { ?>selected="selected"<?php } ?>>Название А-Я</option>
                <option value="name-desc" <?php if ($filter_sort == 'name-desc') { ?>selected="selected"<?php } ?>>Название Я-А</option>
                <option value="manufacturer" <?php if ($filter_sort == 'manufacturer') { ?>selected="selected"<?php } ?>>Бренд</option>
              </select> 
          </td>

          <td>
            <p><i class="fa fa-bars"></i> Категория <span style="border-bottom:1px dashed #CF4A61; cursor:pointer; color:#CF4A61;" onclick="$('#filter_category_path').val(''); $('#filter_category_id').val('0');">очистить</span></p>
            <input type="text" name="filter_category_path" id="filter_category_path" value="<?php echo $filter_category_path; ?>" placeholder="Автодополнение" size="60" />
            <input type="number" step="1" name="filter_category_id" id="filter_category_id" value="<?php echo $filter_category_id; ?>" size="9" />
          </td>        
        </tr>

        <tr>
          <td>
            <p><i class="fa fa-amazon"></i>&nbsp;&nbsp;Офферы</p>
            <select name="filter_amazon_offers_type" style="width:120px;">
              <option value="*">Все варианты</option>                  
              <?php foreach (\hobotix\RainforestAmazon::amazonOffersType as $enum) { ?>
                <option value="<?php echo $enum; ?>"<?php echo ($filter_amazon_offers_type == $enum) ? ' selected="selected"' : ''; ?>><?php echo $enum; ?></option>
              <?php } ?>
            </select>
          </td>

          <td>
            <p><i class="fa fa-amazon"></i>&nbsp;&nbsp;Поставщик</p>
            <select name="filter_amazon_seller_quality" style="width:120px;">
              <option value="*">Все варианты</option>                 
              <?php foreach (\hobotix\RainforestAmazon::amazonSellerQualities as $enum) { ?>
                <option value="<?php echo $enum; ?>"<?php echo ($filter_amazon_seller_quality == $enum) ? ' selected="selected"' : ''; ?>><?php echo $enum; ?></option>
              <?php } ?>
            </select>
          </td>

          <td></td>
          <td></td>

          <td>
            <p><i class="fa fa-bars"></i> Бренд <span style="border-bottom:1px dashed #CF4A61; cursor:pointer; color:#CF4A61;" onclick="$('#filter_manufacturer').val(''); $('#filter_manufacturer_id').val('0');">очистить</span></p>
            <input type="text" name="filter_manufacturer" id="filter_manufacturer" value="<?php echo $filter_manufacturer; ?>" placeholder="Автодополнение" size="60" />
            <input type="number" step="1" name="filter_manufacturer_id" id="filter_manufacturer_id" value="<?php echo $filter_manufacturer_id; ?>" size="9" />
          </td>              
        </tr>   

        <tr>
          <td style="text-align: right;" colspan="5">  
            <a onclick="filter(0);" class="button"><i class="fa fa-filter"></i> Фильтр</a>
            <a onclick="filter(1);" class="button"><i class="fa fa-file-excel-o"></i> Фильтр + CSV</a>
          </td>
        </tr>     
      </table>

      <div class="pagination" style="margin-bottom:5px;"><?php echo $pagination; ?></div>

      <table class="list">
        <thead>
          <tr>
            <td class="left"></td>
            <td class="left"><?php echo $column_name; ?></td>
            <td style="width:80px;">Офферы</td>
            <td style="width:80px;">Поставщик</td>
            <td style="width:80px;">Закупка</td>
            <td style="width:80px;">Себестоимость</td>
            <td style="width:100px;">Продажная цена</td>
            <td style="width:80px;">Рентабельность</td>
            <td class="right">Единиц</td>
            <td class="right">Заказов</td>
            <td class="right">Всего</td>
            <td class="right"></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
              <tr>
                <td class="center" width="60">
                  <img src="<? echo $product['image']; ?>" loading="lazy" width="50" height="50" />
                </td>

                <td class="left">
                  <div style="font-weight: 700">
                      <? echo $product['name']; ?>
                      <?php if ($product['status']) { ?>
                         <i class="fa fa-check-circle" style="color:#51A62D"></i>
                      <?php } else { ?>
                        <i class="fa fa-times-circle" style="color:#CF4A61"></i>
                      <?php } ?>
                  </div>
                  <div style="margin-top:5px; margin-bottom:5px;"><small><? echo $product['de_name']; ?></small></div>

                  <div>
                    <span style="font-size:12px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#4ea24e;"><?php echo $product['product_id']; ?></span>

                    <?php if ($product['asin']) { ?>
                      <span style="font-size:12px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#<?php if ($product['asin'] == 'INVALID') { ?>CF4A61<?php } else { ?>FF9900<?php } ?>;"><?php echo $product['asin']; ?></span> 
                    <?php } ?>

                    <?php if ($product['ean']) { ?>
                      <span style="font-size:12px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:grey;"><?php echo $product['ean']; ?></span> 
                    <?php } ?>

                    <?php if ($product['sku']) { ?>
                      <span style="font-size:12px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#51A62D;"><?php echo $product['sku']; ?></span>  
                    <?php } ?> 
                  </div>
                  <div style="clear:both;"></div>
                  <div>
                    <?php if ($product['manufacturer']) { ?>
                      <span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $product['manufacturer']; ?></span>
                    <?php } ?>

                    <?php if ($product['category_path']) { ?>
                      <span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#4ea24e;"><?php echo $product['category_path']; ?></span>
                    <?php } else { ?>
                      <i class="fa fa-times-circle" style="color:#CF4A61"></i>
                    <?php } ?>
                  </div>

                </td>

                <td class="center" style="white-space:nowrap;">
                  <?php if ($product['amazon_offers_type']) { ?>
                    <span style="padding:4px 5px; background-color:#e16a5d; display:inline-block; text-decoration:none;font-size:16px; color:#FFF;"><? echo $product['amazon_offers_type']; ?></span>
                  <?php } ?>
                </td>

                <td class="center" style="white-space:nowrap;">
                  <?php if ($product['amazon_seller_quality']) { ?>
                    <span style="padding:4px 5px; background-color:#51A62D; display:inline-block; text-decoration:none;font-size:16px; color:#FFF;"><? echo $product['amazon_seller_quality']; ?></span>
                  <?php } ?>
                </td>

                <td class="center" style="white-space:nowrap;">
                  <div>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['amazon_best_price']; ?></span>
                  </div>

                  <div style="margin-top:5px;">
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['amazon_best_price_national']; ?></span>
                  </div>
                </td>

                <td class="center" style="white-space:nowrap;">
                  <div>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['cost']; ?></span>
                  </div>

                  <div style="margin-top:5px;">
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['cost_national']; ?></span>
                  </div>
                </td>

                <td class="center" style="white-space:nowrap;">
                 <?php if ($product['front_price'] || $product['front_special']) { ?>

                  <?php if ($product['front_special']) { ?>
                    <div>
                      <span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF"><? echo $product['front_special']; ?></span>
                    </div>

                    <div style="margin-top:5px;">
                      <span class="status_color" style="display:inline-block; font-size:10px; padding:3px 5px; background:#4ea24e; color:#FFF"><s><? echo $product['front_price']; ?></s></span>
                    </div>
                  <?php } else { ?>                 
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['front_price']; ?></span>
                  <?php } ?>

                <?php } else { ?>       
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF"><i class="fa fa-question"></i></span>
                <?php } ?>
                </td>

                <td class="center" style="white-space:nowrap;">
                  <span style="display:inline-block;padding:3px 5px; <?php if ((float)$product['profitability'] < 0) { ?>background:#ff5656;<?php } else { ?>background:#000;<?php } ?> color:#fff; white-space:nowrap;"><? echo $product['profitability']; ?> %</span>
                </td>

                <td class="center">
                  <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $product['quantity']; ?></span>               
                </td>

                <td class="center">
                  <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $product['orders']; ?></span>               
                </td>

                <td class="right" style="white-space:nowrap;">
                 <div>
                  <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['total']; ?></span>
                </div>

                <div style="margin-top:5px;">
                  <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['total_national']; ?></span>
                </div>                
              </td>

              <td class="center" nowrap style="whitespace:nowrap">
                  <a class="button" href="<? echo $this->url->link('sale/order', 'filter_product_id=' . $product['product_id'] . '&token=' . $this->session->data['token'], 'SSL') ?>" style="text-decoration:none;" target="_blank" /><i class="fa fa-bars"></i></a>&nbsp;&nbsp;

                  <a class="button" href="<? echo $this->url->link('catalog/product/update', 'product_id='.$product['product_id'].'&token=' . $this->session->data['token'], 'SSL') ?>" style="text-decoration:none;" target="_blank" /><i class="fa fa-edit"></i></a>&nbsp;&nbsp;

                  <a class="button" href="<? echo HTTPS_CATALOG . 'index.php?route=product/product&product_id='.$product['product_id']; ?>" style="text-decoration:none;" target="_blank" /><i class="fa fa-eye"></i>
                  </a>  
              </td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
</div>

<script type="text/javascript">
    $('input[name=\'filter_manufacturer\']').autocomplete({
      delay: 500,
      source: function(request, response) {   
        $.ajax({
          url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
          dataType: 'json',
          success: function(json) {
            json.unshift({
              'manufacturer_id':  0,
              'name':  '<?php echo $text_none; ?>'
            });
            
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item.manufacturer_id
              }
            }));
          }
        });
      },
      select: function(event, ui) {
        $('input[name=\'filter_manufacturer\']').val(ui.item.label);
        $('input[name=\'filter_manufacturer_id\']').val(ui.item.value);
        
        return false;
      },
      focus: function(event, ui) {
        return false;
      }
    });
</script> 

<script type="text/javascript">
    $('input[name=\'filter_category_path\']').autocomplete({
      delay: 500,
      source: function(request, response) {   
        $.ajax({
          url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
          dataType: 'json',
          success: function(json) {
            json.unshift({
              'category_id':  0,
              'name':  '<?php echo $text_none; ?>'
            });
            
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item.category_id
              }
            }));
          }
        });
      },
      select: function(event, ui) {
        $('input[name=\'filter_category_path\']').val(ui.item.label);
        $('input[name=\'filter_category_id\']').val(ui.item.value);
        
        return false;
      },
      focus: function(event, ui) {
        return false;
      }
    });
</script> 

<script type="text/javascript">
  function filter(csv) {
   url = 'index.php?route=report/product_purchased&token=<?php echo $token; ?>';

   var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
   if (filter_date_start) {
    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
  }

  var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
  if (filter_date_end) {
    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
  }

  var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
  if (filter_order_status_id != 0) {
    url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
  }	

  var filter_sort = $('select[name=\'filter_sort\']').attr('value');
  if (filter_sort != '*') {
    url += '&filter_sort=' + encodeURIComponent(filter_sort);
  }

  var filter_amazon_seller_quality = $('select[name=\'filter_amazon_seller_quality\']').attr('value');
  if (filter_amazon_seller_quality != '*') {
    url += '&filter_amazon_seller_quality=' + encodeURIComponent(filter_amazon_seller_quality);
  }

  var filter_amazon_offers_type = $('select[name=\'filter_amazon_offers_type\']').attr('value');
  if (filter_amazon_offers_type != '*') {
    url += '&filter_amazon_offers_type=' + encodeURIComponent(filter_amazon_offers_type);
  }

  var filter_category_id = $('input[name=\'filter_category_id\']').attr('value');
  if (filter_category_id != 0) {
    url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
  } 

  var filter_manufacturer_id = $('input[name=\'filter_manufacturer_id\']').attr('value');
  if (filter_manufacturer_id != 0) {
    url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
  }

  if (csv){
    url += '&filter_download_csv=1';
  }

  location = url;
}
</script> 
<?php echo $footer; ?>