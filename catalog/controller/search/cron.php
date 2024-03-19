<?php
class ControllerSearchCron extends Controller {


    public function fullindexer(){
        if (!is_cli()){
            die('CLI ONLY');
        }

        $this->registry->get('searchAdaptor')->createIndices();
    }

    public function entityindexer(){
        if (!is_cli()){
            die('CLI ONLY');
        }

        $this->registry->get('searchAdaptor')->fillIndices('entities');
    }

    public function productsindexer(){
        if (!is_cli()){
            die('CLI ONLY');
        }

        $this->registry->get('searchAdaptor')->fillIndices('products');
    }

    public function product($product_id = false){
       $result = $this->registry->get('searchAdaptor')->getProduct($this->request->get['product_id']);
       $this->response->setJSON($result);
    }

    public function entity($entity_type = false, $entity_id = false){
        if (empty($entity_type)){
            $entity_type = $this->request->get['entity_type'];
        }

        if (empty($entity_id)){
            $entity_id = $this->request->get['entity_id'];
        }

        $result = $this->registry->get('searchAdaptor')->getEntity( $entity_type , $entity_id);
        $this->response->setJSON($result);
    }
}