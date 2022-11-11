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
class ModelShoputilsPayments extends Model {

    function getMethods() {

        $result = array();

        $files = glob(DIR_APPLICATION . 'controller/payment/*.php');

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('payment/' . $extension);

                $text_link = $this->language->get('text_' . $extension);

                if ($text_link != 'text_' . $extension) {
                    $link = $this->language->get('text_' . $extension);
                } else {
                    $link = '';
                }

                $result[$extension] = array(
                    'name' => $this->language->get('heading_title'),
                    'link' => $link,
                    'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'sort_order' => $this->config->get($extension . '_sort_order'),
                );
            }
        }
        return $result;
    }
}
?>