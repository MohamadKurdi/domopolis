    <div class="content">
        <table class="list">
          <thead>
            <tr>           
			  <td>Валюта</td>
              <td class="left">Код</td>
			  <td></td>
			  <td class="left">Вн. курс к UAH</td>
			  <td class="left">Вн. курс к RUB</td>
              <td class="right">Внутренний курс к €</td>
			  					  
			  <td class="right">Реальный курс к €</td>	
			  <td class="right">Наценка, %</td>
              <td class="right">Дата изменения</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($currencies) { ?>
            <?php foreach ($currencies as $currency) { ?>
			<? $our_is_more = ($currency['value'] > $currency['value_real']); ?>
			<? $our_is_equal = ($currency['value'] == $currency['value_real']); ?>
            <tr>            
              <td class="left"><?php echo $currency['title']; ?></td>
              <td class="left"><?php echo $currency['code']; ?></td>
			  <td class="left"><? if (isset($currency['flag'])) { ?><img src="<? echo HTTPS_CATALOG ?>/image/flags/<?php echo $currency['flag']; ?>" /><? } ?></td>			 
			  <td class="left"><?php echo $currency['mc']; ?> = <?php echo $currency['rc']; ?></td>
				 <td class="left"><?php echo $currency['mc']; ?> = <?php echo $currency['r_rc']; ?></td> 	
              <td class="center">
			   <? if ($our_is_equal) { ?>
			   	  <span class="status_color_padding" style="background:#ff7f00; color:white;"> = </span>	   
			   <? }   elseif ($our_is_more) { ?>
				  <span class="status_color_padding" style="background:#4ea24e; color:white;"> > </span>
			  <? } else { ?>
				  <span class="status_color_padding" style="background:#cf4a61; color:white;"> < </span>
			  <? } ?>
			  </td>
			  <td class="right"><?php echo $currency['value_real']; ?></td> 	
			  <td class="right">
				<? if ($currency['plus_percent']) { ?>
				  <span class="status_color_padding" style="background:#ff7f00; color:white;"><?php echo $currency['plus_percent']; ?></span>
				<? } else { ?>
				  <span class="status_color_padding" style="background:#4ea24e; color:white;"><?php echo $currency['plus_percent']; ?></span>
				<? } ?>
			  </td> 	  
              <td class="right"><? echo $currency['date_modified']; ?></td>            
            </tr>
            <?php } ?>			
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>      
			   <span class="help">Источники обновления: FIXER.IO, UAH: ПриватБанк карточный курс, продажа, RUB: Русский Стандарт карточный курс, продажа (http://www.rsb.ru/courses/)<br /><br />
	   Внутренний курс к Евро влияет на ценообразование на витрине магазина.<br /><br />
	   Реальный курс к Евро - используется исключительно в формировании подсказки закупщикам при поиске возможной цены закупки.
	   </span>
    </div>