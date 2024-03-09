<?

class ControllerDPOpenAI extends Controller {
	public function requestbyai(){
		$request = $this->request->post['request'];

		if ($result = $this->openaiAdaptor->completion($request)){
			$this->response->setOutput($result);
		} else {
			$this->response->setOutput('');
		}
	}
}