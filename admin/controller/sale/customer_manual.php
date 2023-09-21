<?php
	class ControllerSaleCustomerManual extends Controller {

		
		public function index() {
			$this->language->load('sale/customer');
			$this->load->model('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));

			$this->getList();

		}




























	}