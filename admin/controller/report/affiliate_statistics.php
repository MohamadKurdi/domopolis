<?php

class ControllerReportAffiliateStatistics extends Controller {

    public function index() {
        $this->language->load('report/affiliate_statistics');
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

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
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
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/affiliate_statistics', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->load->model('report/affiliate_statistics');

        $this->data['affiliates'] = array();

        $data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $affiliate_total = $this->model_report_affiliate_statistics->getTotalCommission($data);

        $results = $this->model_report_affiliate_statistics->getAffiliatesName($data);

        foreach ($results as $result) {
            $resultCount = $this->model_report_affiliate_statistics->getAffiliatesCount($data, $result['affiliate_id']);
            $resultOrders = $this->model_report_affiliate_statistics->getAffiliatesOrders($data, $result['affiliate_id']);
            $resultShopping = $this->model_report_affiliate_statistics->getAffiliatesShopping($data, $result['affiliate_id']);
            $resultSum = $this->model_report_affiliate_statistics->getAffiliatesSum($data, $result['affiliate_id']);

            $result = $result + $resultCount + $resultOrders + $resultShopping + $resultSum;
            
            $this->data['affiliates'][] = array(
                'affiliate' => $result['affiliate'],
                'email' => $result['email'],
                'count_transitions' => (int)$result['transitions'],
                'count_orders' => (int)$result['orders'],
                'count_shopping' => (int)$result['shopping'],
                'sum_orders' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'sum_shopping' => $this->currency->format($result['totals'], $this->config->get('config_currency')),
                'sum_credited' => $this->currency->format($result['commission'], $this->config->get('config_currency')),
                'sum_paid' => $this->currency->format((-1) * $result['paid'], $this->config->get('config_currency')),
				'link' => $this->url->link('sale/order', 'filter_affiliate_id='.$result['affiliate_id'].'&token='.$this->session->data['token'])
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_affiliate'] = $this->language->get('column_affiliate');
        $this->data['column_email'] = $this->language->get('column_email');
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

        $pagination = new Pagination();
        $pagination->total = $affiliate_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('report/affiliate_statistics', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;

        $this->template = 'report/affiliate_statistics.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

}

?>