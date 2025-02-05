<?php
final class Front {
	protected $registry  	= null;
	protected $error		= null;
	protected $pre_action 	= [];

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

	public function dispatch($action, $error) {
		$this->error = $error;
		foreach ($this->pre_action as $pre_action) {			
			$result = $this->execute($pre_action);
			if ($result) {
				$action = $result;
				break;
			}
		}

		while ($action) {
			$action = $this->execute($action);
		}
	}
	
	public function dispatchDirect($action, $error) {
		$this->error = $error;

		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);
			if ($result) {
				$action = $result;
				break;
			}
		}

		while ($action) {
			$action = $this->execute($action);
		}
	}

	public function dispatchWithNoPreActions($action){
		if ($action){
			$action = $this->execute($action);
		}
	}

	private function execute($action) {
		if ($action->getFile() && file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			if (is_callable(array($controller, $action->getMethod()))) {				
				$action = call_user_func_array([$controller, $action->getMethod()], $action->getArgs());
			} else {
				$action = $this->error;

				$this->error = '';
			}
		} else {
			$action = $this->error;

			$this->error = '';
		}

		return $action;
	}
}