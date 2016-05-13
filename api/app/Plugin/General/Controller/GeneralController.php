<?php

App::uses('GeneralAppController', 'General.Controller');
class GeneralController extends AppController  {

    /**
     * Método padrão de toda classe de API.
     * 
     * @see AppController::index()
     */
    public function index() {
        $this->method = 'init';
        return parent::index();
    }

    /**
     * Verifica qual metodo esta sendo solicitado atraves do requestType
     * @return void
     */
    protected function init() {
        switch ($this->requestType) {
            case 'get':
                $this->getData();
                break;
        }
    }

    /**
     * Faz select solicitado com conditions e unbind.
     * @return void
     */
    protected function getData() {
        
        if ($this->findType === 'query') {
            $modelClassName = get_class($this->model);
            $query = $this->requestParams[$modelClassName]['query'];
            $data = $this->General->executQuery($query);
        } 
        $this->appData = utf8_encode(serialize($data));
    }
}
