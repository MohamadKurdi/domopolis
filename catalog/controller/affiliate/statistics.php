<?php

class ControllerAffiliateStatistics extends Controller {

    public function index() {
        if (!$this->affiliate->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('affiliate/statistics', '');
            $this->redirect($this->url->link('affiliate/login', '', 'SSL'));
        }

        $this->language->load('affiliate/statistics');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->redirect($this->url->link('affiliate/account', '', 'SSL'));
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', ''),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_statistics'),
            'href' => $this->url->link('affiliate/statistics', ''),
            'separator' => $this->language->get('text_separator')
        );

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        $data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end
        );

        $this->data['entry_date_start'] = $this->language->get('entry_date_start');
        $this->data['entry_date_end'] = $this->language->get('entry_date_end');

        $this->data['button_filter'] = $this->language->get('button_filter');
        $this->data['button_clear'] = $this->language->get('button_clear');
        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }


        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_statistics'] = $this->language->get('entry_statistics');
        $this->data['column_count_transitions'] = $this->language->get('column_count_transitions');
        $this->data['column_count_orders'] = $this->language->get('column_count_orders');
        $this->data['column_count_shopping'] = $this->language->get('column_count_shopping');
        $this->data['column_sum_orders'] = $this->language->get('column_sum_orders');
        $this->data['column_sum_shopping'] = $this->language->get('column_sum_shopping');
        $this->data['column_sum_credited'] = $this->language->get('column_sum_credited');
        $this->data['column_sum_paid'] = $this->language->get('column_sum_paid');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        $this->data['action'] = $this->url->link('affiliate/statistics', '');
        $affiliates[] = array();
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->load->model('module/statistics');
            $transitions = $this->model_module_statistics->GetStatistics($this->affiliate->getId(), $this->data);
            $Orders = $this->model_module_statistics->GetStatisticsOrders($this->affiliate->getId(), $this->data);
            $Shopping = $this->model_module_statistics->GetStatisticsShopping($this->affiliate->getId(), $this->data);
            $Sum = $this->model_module_statistics->GetStatisticsSum($this->affiliate->getId(), $this->data);
        }
        
        $affiliates = $transitions + $Orders + $Shopping + $Sum;

        foreach ($affiliates as $key => $value) {
            if (empty($affiliates[$key])) {
                $affiliates[$key] = 0.00;
            }
        }
        
        $affiliates['count_transitions'] = (int) $affiliates['count_transitions'];
        $affiliates['count_orders'] = (int) $affiliates['count_orders'];
        $affiliates['count_shopping'] = (int) $affiliates['count_shopping'];
        $affiliates['commission'] = $this->currency->format($affiliates['commission']);
        $affiliates['paid'] = $this->currency->format((-1) * $affiliates['paid']);
        $affiliates['sum_orders'] = $this->currency->format($affiliates['sum_orders']);
        $affiliates['sum_shopping'] = $this->currency->format($affiliates['sum_shopping']);

        $this->data['affiliates'] = $affiliates;
        $this->data['back'] = $this->url->link('affiliate/account', '');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/statistics.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/affiliate/statistics.tpl';
        } else {
            $this->template = 'default/template/affiliate/statistics.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

}

?>