<?php
class ControllerSearchAjax extends Controller {

    public function clear(){
        if (!empty($this->request->post['id'])){
            $this->registry->get('searchAdaptor')->History->clearSearchHistory($this->request->post['id']);
        }
    }







}