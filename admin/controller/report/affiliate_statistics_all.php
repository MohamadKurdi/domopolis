<?php

class ControllerReportAffiliateStatisticsall extends Controller {

    public function index() {
        $this->language->load('report/affiliate_statistics_all');
        $this->document->setTitle($this->language->get('heading_title'));

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

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/affiliate_statistics_all', 'token=' . $this->session->data['token'] . $url),
            'separator' => ' :: '
        );

        $this->load->model('report/affiliate_statistics');

        $this->data['affiliates'] = array();

        $data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'start' => 0,
            'limit' => $this->config->get('config_admin_limit')
        );
        $resultCount = $this->model_report_affiliate_statistics->getAffiliatesCount($data);
        $resultOrders = $this->model_report_affiliate_statistics->getAffiliatesOrders($data);
        $resultShopping = $this->model_report_affiliate_statistics->getAffiliatesShopping($data);
        $resultSum = $this->model_report_affiliate_statistics->getAffiliatesSum($data);
        
        $result = array();
        $result=$resultCount+$resultOrders+$resultShopping+$resultSum;
        
        $this->data['affiliates'][] = array(
            'count_transitions' => (int)$result['transitions'],
            'count_orders' => (int)$result['orders'],
            'count_shopping' => (int)$result['shopping'],
            'sum_orders' => $this->currency->format($result['total'], $this->config->get('config_currency')),
            'sum_shopping' => $this->currency->format($result['totals'], $this->config->get('config_currency')),
            'sum_credited' => $this->currency->format($result['commission'], $this->config->get('config_currency')),
            'sum_paid' => $this->currency->format((-1) * $result['paid'], $this->config->get('config_currency')),
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_count_transitions'] = $this->language->get('column_count_transitions');
        $this->data['column_count_orders'] = $this->language->get('column_count_orders');
        $this->data['column_count_shopping'] = $this->language->get('column_count_shopping');
        $this->data['column_sum_orders'] = $this->language->get('column_sum_orders');
        $this->data['column_sum_shopping'] = $this->language->get('column_sum_shopping');
        $this->data['column_sum_credited'] = $this->language->get('column_sum_credited');
        $this->data['column_sum_paid'] = $this->language->get('column_sum_paid');


        $this->data['entry_date_start'] = $this->language->get('entry_date_start');
        $this->data['entry_date_end'] = $this->language->get('entry_date_end');

        $this->data['button_filter'] = $this->language->get('button_filter');

        $this->data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }
        
        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;

        $this->template = 'report/affiliate_statistics_all.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

}

?>