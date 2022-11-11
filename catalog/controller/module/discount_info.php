<?php  
class ControllerModuleDiscountInfo extends Controller {
	protected function index($setting) {

            $this->language->load('module/discount_info');
            
            $this->data['column_discount'] = $this->language->get('column_discount');
            $this->data['entry_your_sum'] = $this->language->get('entry_your_sum');
            $this->data['entry_your_status'] = $this->language->get('entry_your_status');
            $this->data['entry_discount'] = $this->language->get('entry_discount');
            $this->data['entry_need_login'] = $this->language->get('entry_need_login');
            $this->data['entry_welcome'] = sprintf($this->language->get('entry_welcome'), $this->url->link('account/login', '', 'SSL'));

            $this->data['width'] = $setting['width'];
            $this->data['discount_id'] = $setting['discount_id'];
            
            $this->load->model('setting/discount');  
            $this->data['text_discount'] = array();
            
            if ($setting['discount_id'] <> 'discount_count') {
                $this->data['symbol_right'] = $this->currency->getSymbolRight();
                $this->data['symbol_left'] = $this->currency->getSymbolLeft(); 
            } else {
                $this->data['symbol_right'] = '';
                $this->data['symbol_left'] = '';
            }
            
            
            $customer_group_id = -1;
            if ($this->customer->isLogged()) $customer_group_id = $this->model_setting_discount->getCustomerGroupId($this->customer->isLogged());

            $available = 0;
            if ($customer_group_id == $setting['show'] || $setting['show'] == 0 || ($setting['show'] == 777 && $this->customer->isLogged())) $available = 1;
            
      
           // if (($setting['show'] == 0 && $this->customer->isLogged()) || $setting['show'] == 1) $available = 1; 
           
            //Discount_user_info
            if ($available == 1 && $setting['discount_id'] == 'discount' && $this->config->get('discount_status') > 0) {          
                    $this->data['heading_title'] = $this->language->get('heading_title_user');
                    $customer_id = $this->customer->isLogged();
                    $discount_rows_num = $this->config->get('discount_rows_num');
                    for ($num = 1; $num <= $discount_rows_num; $num++) {  
                        $discount_row_group = 'discount_group_row' . $num;
                        $discount_row_proc = 'discount_row' . $num . '_proc';
                        $discount_row_customer_list = 'discount_row' . $num . '_customer_list';

                        if ((int)$this->config->get($discount_row_group) >= 0 && (int)$this->config->get($discount_row_proc) > 0) {
                            $discount_data[] = array( 
                                'discount_row_group' => $this->config->get($discount_row_group),
                                'discount_row_proc'  => $this->config->get($discount_row_proc),
                                'discount_row_list'  => $this->config->get($discount_row_customer_list)
                            );
                        }    
                    }

                    $discount_proc = 0;
                    if (isset($discount_data)) {
                        $discount_data = $this->sort_array($discount_data, 'discount_row_proc');  
                        foreach ($discount_data as $disc)    
                            if ($customer_group_id == $disc['discount_row_group'] || $disc['discount_row_group'] == 0) {
                                $discount_proc = $disc['discount_row_proc'];  
                            } else if (($disc['discount_row_group'] == 777) && is_array($disc['discount_row_list'])) {
                                if (in_array($customer_id, $disc['discount_row_list']))
                                    $discount_proc = $disc['discount_row_proc'];  
                            } 
                    }

                    if ($this->customer->isLogged()) {
                        $this->data['your_discount'] = $discount_proc;   
                    } else {
                        $this->data['entry_need_login_user'] = $this->language->get('entry_need_login_user');
                    }
                    
                    $this->show_tpl();
            }

            //Discount_regular_info
            if ($available == 1 && $setting['discount_id'] == 'discount_regular' && $this->config->get('discount_regular_status') > 0 && $this->config->get('discount_regular_proc') > 0) {
                $this->data['text_discount'] = array();
                    $this->data['heading_title'] = $this->language->get('heading_title_regular');
                    $this->data['column_condition'] = $this->language->get('column_cumulative');
              
                    if ($this->customer->isLogged()) {
                        $this->data['your_sum'] = (int)$this->model_setting_discount->getSumTotal($setting['discount_id']);
                        if ($this->data['your_sum'] >= $this->config->get('discount_regular_total')) $this->data['your_discount'] = $this->config->get('discount_regular_proc');
                    }
                    
                    $this->data['text_discount'][] = array(
                                            'condition' => $this->config->get('discount_regular_total'),
                                            'discount'  => $this->config->get('discount_regular_proc')
                    );  
                    $this->show_tpl();
            }
            
            
            //Discount_cumulative_info
            if ($available == 1 && $setting['discount_id'] == 'discount_cumulative' && $this->config->get('discount_cumulative_status') > 0) {
                    $this->data['heading_title'] = $this->language->get('heading_title_cumulative');
                    $this->data['column_condition'] = $this->language->get('column_cumulative'); 
                    $this->data['column_status'] = $this->language->get('column_status'); 
                    $this->data['add_row_status'] = 0;
                
                    $discount_cumulative_rows_num = $this->config->get('discount_cumulative_rows_num');
                    for ($num = 1; $num <= $discount_cumulative_rows_num; $num++) {  
                        $discount_cumulative_row_name = 'discount_cumulative_row' . $num . '_name'; 
                        $discount_cumulative_row_color = 'discount_cumulative_row' . $num . '_color'; 
                        $discount_cumulative_row_count = 'discount_cumulative_row' . $num . '_cumulative';
                        $discount_cumulative_row_proc = 'discount_cumulative_row' . $num . '_proc';

                        if (trim($this->config->get($discount_cumulative_row_name)) <> '') $this->data['add_row_status'] += 1;
                            
                        if ((int)$this->config->get($discount_cumulative_row_count) > 0 && (int)$this->config->get($discount_cumulative_row_proc) > 0) {
                            $this->data['text_discount'][] = array( 
                                'name'      => $this->config->get($discount_cumulative_row_name),
                                'color'     => $this->config->get($discount_cumulative_row_color),
                                'condition' => $this->config->get($discount_cumulative_row_count),
                                'discount'  => $this->config->get($discount_cumulative_row_proc)
                            );
                        }
                    }
                    $this->data['text_discount'] = $this->sort_array($this->data['text_discount'], 'discount');
                    
                    if ($this->customer->isLogged()) {
                        $this->data['your_sum'] = (int)$this->model_setting_discount->getSumTotal($setting['discount_id']);
                                
                        if (isset($this->data['text_discount'])) {
                            foreach ($this->data['text_discount'] as $discount) 
                                if ($this->data['your_sum'] >= $discount['condition']) {
                                    $this->data['your_discount'] = $discount['discount'];  
                                    $this->data['your_name'] = $discount['name'];
                                    $this->data['your_color'] = $discount['color'];
                                }
                        }
                    }    
                     
                    $this->show_tpl();
            }
 
            
            //Discount_sum_info
            if ($available == 1 && $setting['discount_id'] == 'discount_sum' && $this->config->get('discount_sum_status') > 0) {
                    $this->data['heading_title'] = $this->language->get('heading_title_sum');
                    $this->data['column_condition'] = $this->language->get('column_cumulative'); 
     
                    $discount_sum_rows_num = $this->config->get('discount_sum_rows_num');
                    for ($num = 1; $num <= $discount_sum_rows_num; $num++) {  
                        $discount_sum_row_count = 'discount_sum_row' . $num . '_count';
                        $discount_sum_row_proc = 'discount_sum_row' . $num . '_proc';

                        if ((int)$this->config->get($discount_sum_row_count) > 0 && (int)$this->config->get($discount_sum_row_proc) > 0) {
                            $this->data['text_discount'][] = array( 
                                'condition' => $this->config->get($discount_sum_row_count),
                                'discount'  => $this->config->get($discount_sum_row_proc)
                            );
                        }
                    }
                    $this->data['text_discount'] = $this->sort_array($this->data['text_discount'], 'discount');
                    $this->show_tpl();
            }
            

            //Discount_count_info
           // if ($setting['discount_id'] == 'discount_count' && $this->config->get('discount_count_login_status') == 1 && !$this->customer->isLogged()) $available = 0;
            if ($available == 1 && $setting['discount_id'] == 'discount_count' && $this->config->get('discount_count_status') > 0) {
                    $this->data['heading_title'] = $this->language->get('heading_title_count');
                    $this->data['column_condition'] = $this->language->get('column_count');

                    $discount_count_rows_num = $this->config->get('discount_count_rows_num');
                    for ($num = 1; $num <= $discount_count_rows_num; $num++) {  
                        $discount_count_row_count = 'discount_count_row' . $num . '_count';
                        $discount_count_row_proc = 'discount_count_row' . $num . '_proc';

                        if ((int)$this->config->get($discount_count_row_count) > 0 && (int)$this->config->get($discount_count_row_proc) > 0) {
                            $this->data['text_discount'][] = array( 
                                'condition' => $this->config->get($discount_count_row_count),
                                'discount'  => $this->config->get($discount_count_row_proc)
                            );
                        }
                    }
                    
                    $this->data['text_discount'] = $this->sort_array($this->data['text_discount'], 'discount');
                    $this->show_tpl();
            }
            
	}
     
        
        
        private function sort_array($array, $by) {
            $result = array();  
            foreach ($array as $val) {
                if (!is_array($val) || !key_exists($by, $val)) {
                    continue;
                }
                end($result);
                $current = current($result);
                while ($current[$by] > $val[$by]) {
                    $result[key($result)+1] = $current;
                    prev($result);
                    $current = current($result);
                }
                $result[key($result)+1] = $val;
            }
            return $result;
        }
        
        
        private function show_tpl() {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/discount_info.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/module/discount_info.tpl';
            } else {
                    $this->template = 'default/template/module/discount_info.tpl';
            }

            $this->render();  
        }
}
?>