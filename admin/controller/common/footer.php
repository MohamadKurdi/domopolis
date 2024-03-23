<?php
	class ControllerCommonFooter extends Controller {   
		protected function index() {
			$this->language->load('common/footer');
			
			$this->data['oc_version'] = VERSION;

            $updater = new \hobotix\Installer\Updater();
            $this->data['framework_version'] = $updater->get_global();
            $this->data['last_commit'] = $updater->last_commit();

            $this->data['query_string'] = '';
            if (!empty($this->request->server['QUERY_STRING'])){
                $this->data['query_string'] = $this->request->server['QUERY_STRING'];
            }

			if ($this->user->isLogged() && isset($this->session->data['token'])) {
				$this->data['url'] =  $this->url->link('common/home/session', 'token=' . $this->session->data['token']);
				$this->data['token'] = $this->session->data['token'];
				} else {
				$this->data['token'] = false;
			}
						
			$this->template = 'common/footer.tpl';
			
			$this->render();
		}
	}		