<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $autoLayout = false;
    public $autoRender = false;
    public $components = array('Session', 'Utility');
    protected $secureTokenValidationStatus;
    protected $crudOperationStatus;
    protected $method = null;
    protected $findType = null;
    protected $requestType = null;
    protected $requestParams = null;
    protected $appData = null;
    protected $model = null;

    public function beforeFilter() {
        if (!$this->secureTokenValidationStatus)
            $this->secureTokenValidationStatus = Configure::read('secureTokenValidationStatus');
        if (!$this->crudOperationStatus)
            $this->crudOperationStatus = Configure::read('crudOperationStatus');
        $this->secureTokenValidation();
    }

    /**
     * Método padrão que trata os parametros da URL e direciona 
     * o fluxo da API.
     * 
     * @return string : String base64, resultado do processamento da requisição.
     */
    public function index() {
        $this->requestType = $this->request->params['type'];
        $this->requestParams = (!empty($_POST) ? json_decode($_POST['params'], true) : null);
        //$this->requestParams 	= $this->Utility->decodeData($this->request->params['params']);
        
        $this->findType = $this->request->params['findType'];

        $this->model = $this->callsCorrespondingModel();
        call_user_func_array(array($this, $this->method), array());
        return $this->Utility->encodeData($this->appData);
    }

    /**
     * Efetua a validação do TOKEN de segurança para 
     * comunicação com a API
     */
    protected function secureTokenValidation() {
        $statusMessage = array('status' => '');
        $secureToken = (!empty($this->request->params['token']) ? $this->request->params['token'] : null);

        if (!empty($secureToken)) {
            $now = time();
            $apiTimeout = Configure::read('apiRequestTimeout');
            $tokenTimestamp = $this->secureTokenDecode($secureToken);

            if ($tokenTimestamp) {
                $requestTimeout = $now - $tokenTimestamp;
                if ($requestTimeout >= $apiTimeout) {
                    $statusMessage['status'] = $this->secureTokenValidationStatus['expired'];
                    echo $this->Utility->encodeData($statusMessage);
                    exit;
                }
            } else {
                $statusMessage['status'] = $this->secureTokenValidationStatus['invalid'];
                echo $this->Utility->encodeData($statusMessage);
                exit;
            }
        } else {
            $statusMessage['status'] = $this->secureTokenValidationStatus['invalid'];
            echo $this->Utility->encodeData($statusMessage);
            exit;
        }
    }

    /**
     * Decodifica o TOKEN de segurança para validação
     */
    private function secureTokenDecode($token = null) {
        if (!$token)
            return null;

        $arrayToken = $this->Utility->decodeData($token);
        if (is_array($arrayToken)) {
            if (array_key_exists('secureNumbers', $arrayToken)) {
                return $arrayToken['secureNumbers'];
            }
        }
        return null;
    }

    /**
     * Define qual model vai ser chamada na requisicao
     */
    private function callsCorrespondingModel() {
        $key = array_keys($this->requestParams);
        $model = $key[0];
        if (!empty($model)) {
            $modelClass = $this->modelClass;
            $m = ($modelClass == $model) ? $this->$modelClass : $this->$modelClass->$model;
            return $m;
        }
        return null;
    }

}
