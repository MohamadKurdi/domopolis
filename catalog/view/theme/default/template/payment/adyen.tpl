<!DOCTYPE html>
<html>
  <body>
    <form method="post" action="https://test.adyen.com/hpp/select.shtml" id="adyenForm" name="adyenForm" target="_parent">

      <input type="hidden" name="merchantSig" value="<? echo $merchantSig ?>" />
      
	  <? foreach ($adyen_params as $key => $value) { ?>
		<input type="hidden" name="<? echo $key ?>" value="<? echo $value ?>" />
	  <? } ?>		  
	  
      <input type="submit" value="Send" />
      <input type="reset" />
    </form>
  </body>
</html>