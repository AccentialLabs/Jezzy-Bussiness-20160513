<?php

/**
 * 
 * @author Rodrigo Salles, Wilson Junior
 * @copyright Accential
 */
App::uses('PaymentsAppController', 'Payments.Controller');

class PaymentsController extends PaymentsAppController {

    public $uses = array('Checkout');

    /* Credenciais MOIP */
    private $moipKey = '11PB4FPN68M1FE8MAPWUDIMEHFIGM8P6DMSBNXZZ';
    private $moipToken = 'JK75V6UGKYYUZR2ICVHJSSLD687UEJ9H';
    private $moipAccountLogin = 'accential';
    private $moipPercentageForSplit = '2.00';

    /**
     * Metodo padrao de toda classe de API.
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

            case 'save':
                $this->saveData();
                break;

            case 'delete':
                $this->deleteData();
                break;

            case 'checkouts':
                $this->checkoutsData();
                break;

            case 'calculateShippingValue':
                $this->calculateShippingValue();
                break;

            default:
                $this->getData();
                break;
        }
    }

    /**
     * Faz select solicitado com conditions e unbind para pagamentos.
     * @return void
     */
    protected function getData() {
        $unbind = $this->model->associationsToUnbindForParams($this->requestParams);
        $this->model->unbindModel($unbind);

        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $this->appData = $this->model->find($this->findType, $conditions);
    }

    /**
     * Adiciona / edita dados de pagamentos
     * @return void
     */
    protected function saveData() {
        $modelClassName = get_class($this->model);


        if (!empty($this->requestParams['Checkout'])) {

            $values = "'" . implode("','", array_values($this->requestParams['Checkout'])) . "'";
            //$this->Checkout->query("INSERT INTO trueo846_2.checkouts VALUES(NULL, {$values})");
            $this->Checkout->query("INSERT INTO checkouts VALUES(NULL, {$values})");
            $data = $this->model->find('first', array('order' => array('Checkout.id' => 'desc')));

            $this->appData = $data;
        } elseif ($this->model->saveAll($this->requestParams)) {
            # save OK...

            $data = $this->model->find('first', array('conditions' => array("{$modelClassName}.id" => $this->model->id)));
            $this->appData = array(
                'status' => $this->crudOperationStatus['save_ok'],
                'data' => $data
            );
        } else {
            # save NOT OK...
            $this->appData = array(
                'status' => $this->crudOperationStatus['save_error'],
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Exclui dados de pagamentos
     * @return array
     */
    protected function deleteData() {
        if (!empty($this->requestParams['id'])) {
            if ($this->Checkout->delete($this->requestParams['id'])) {
                # delete ok
                $this->appData = array('status' => $this->crudOperationStatus['delete_ok']);
            } else {
                # delete error
                $this->appData = array(
                    'status' => $this->crudOperationStatus['delete_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            # validation error
            $this->appData = array(
                'status' => $this->crudOperationStatus['delete_invalid_id'],
                'data' => $this->requestParams
            );
        }
    }

    /**
     * TRATA PAGAMENTO COM MOIP
     * @return array
     */
    protected function checkoutsData() {
        $setPlayer = $this->requestParams['Payments']['setPlayer'];
        $moip = new Moip();

        //$moip->setEnvironment('sandbox');
        $moip->setCredential(array(
            'key' => $this->moipKey,
            'token' => $this->moipToken
        ));

        $moip->setUniqueID($this->requestParams['Payments']['idUnique']);
        $moip->setValue($this->requestParams['Payments']['value']);
        $moip->setReason($this->requestParams['Payments']['reason']);
        $moip->setPayer($setPlayer);
        $moip->validate('Identification');

        if ($this->requestParams['Payments']['parcelamento'] == 'ACTIVE') {
            $minimunValue = '10.00';
            $value = $this->requestParams['Payments']['value'];
            $num = floor($value / $minimunValue);

            if ($num >= 2) {
                $parcels = ($num > 12 ? 12 : $num);
                if ($this->requestParams['Payments']['parcelamento_juros'] == 'ACTIVE') {
                    $moip->addParcel(2, $parcels);
                } else {
                    $moip->addParcel(2, $parcels, null, true);
                }
            }
        }

        //$moip->setReceiver('wilanetonet');
        $moip->setReceiver($this->requestParams['Payments']['email_empresa']);
        $moip->addComission('SPLIT DE PAGAMENTO', $this->moipAccountLogin, $this->moipPercentageForSplit, true);


        $moip->send();

        $xml = simplexml_load_string($moip->answer->xml);

        if ($xml->Resposta->Status == "Falha") {
            $this->appData = $xml->Resposta;
        } else {
            $ID = $xml->Resposta->ID;
            $token = $xml->Resposta->Token;
            $this->appData = array('ID' => "{$ID}", 'token' => "{$token}");
        }
    }

    /**
     * Calcula valor do frete - API dos Correios
     * @return array
     */
    protected function calculateShippingValue() {

        // Códigos dos serviços de entrega
        // 41106 PAC
        // 40010 SEDEX
        // 40045 SEDEX a Cobrar
        // 40215 SEDEX 10
        # valores default
        $defaultValues = array(
            'nCdEmpresa' => '',
            'sDsSenha' => '',
            'nCdFormato' => 1,
            'nVlComprimento' => 16,
            'nVlAltura' => 4,
            'nVlLargura' => 12,
            'sCdMaoPropria' => 'n',
            'nVlValorDeclarado' => '1.00',
            'sCdAvisoRecebimento' => 'n',
            'nCdServico' => 41106,
            'nVlDiametro' => 0,
            'StrRetorno' => 'xml'
        );

        $defaultQueryString = http_build_query($defaultValues);
        $paramsQueryString = http_build_query($this->requestParams);

        $urlQueryString = $defaultQueryString . '&' . $paramsQueryString;

        // Webservice POST URL
        $postUrl = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?{$urlQueryString}";

        // retorno da consulta
        $xml = simplexml_load_file($postUrl);

        // Verifica se não há erros
        if ($xml->cServico->Erro == '0') {
            $response = $xml->cServico;
            $jsonResponse = json_encode($response);
            $this->appData = json_decode($jsonResponse, true);
        } else {
            $this->appData = array('status' => 'CALCULATION_ERROR');
        }
    }

}
