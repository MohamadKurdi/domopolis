<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/
class ModelTotalShoputilsPaymentDiscounts extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
        if (!$this->config->get('shoputils_payment_discounts_status')){
            return;
        }

        $discount = (int)$this->config->get('shoputils_payment_discounts_percent');
        $payment_methods = unserialize($this->config->get('shoputils_payment_discounts_payment_methods'));
        $payment_method = isset($this->session->data['payment_method']['code']) ? $this->session->data['payment_method']['code'] : '';
        $payment_method_title = isset($this->session->data['payment_method']['title']) ? $this->session->data['payment_method']['title'] : '';

        if ($discount && in_array($payment_method, $payment_methods) && (!isset($this->session->data['coupon']) or mb_strlen($this->session->data['coupon'])==0)){
            $this->language->load('total/shoputils_payment_discounts');

            $products_total = $this->cart->getTotal();

            $discount_total = round($products_total * ($discount / 100), 2);

            if ($discount_total > 0){
                $total_data[] = array(
                    'code'       => 'shoputils_payment_discounts',
                    'title'      => sprintf($this->language->get('text_shoputils_payment_discounts'), 'Скидка за предоплату', $discount),
                    'text'       => '-' . $this->currency->format($discount_total),
                    'value'      => - $discount_total,
                    'sort_order' => (int)$this->config->get('shoputils_payment_discounts_sort_order'),
                  );
                  $total -= $discount_total;
            }
        };
    }

    public function changePaymentMethodTitle($payment_method){
        $this->language->load('total/shoputils_payment_discounts');
        $result = $payment_method;
        $payment_methods = unserialize($this->config->get('shoputils_payment_discounts_payment_methods'));
        $discount = (int)$this->config->get('shoputils_payment_discounts_percent');
        if ($discount && in_array($payment_method['code'], $payment_methods)){
            $result['title'] .= sprintf($this->language->get('text_shoputils_payment_discounts_title'), $discount);
        }
        return $result;
    }
}