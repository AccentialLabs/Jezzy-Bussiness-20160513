<?php

/**
 * 
 * @author Rodrigo Salles, Wilson Junior
 * @copyright Accential
 */
App::uses('CompaniesAppController', 'Companies.Controller');
App::uses('CakeEmail', 'Network/Email');

class CompaniesController extends CompaniesAppController {

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
     *
     * @return void
     */
    protected function init() {
        switch ($this->requestType) {
            case 'get' :
                $this->getData();
                break;

            case 'save' :
                $this->saveData();
                break;

            case 'delete' :
                $this->deleteData();
                break;

            case 'companyLogin' :
                $this->companyLogin();
                break;

            case 'passwordRecovery' :
                $this->passwordRecovery();
                break;

            case 'teste' :
                $this->teste();
                break;

            default :
                $this->getData();
                break;
        }
    }

    /**
     * Faz select solicitado com conditions e unbind.
     *
     * @return void
     */
    protected function getData() {
        if ($this->findType === 'query') {
            $modelClassName = get_class($this->model);
            $query = $this->requestParams [$modelClassName] ['query'];
            $data = $this->model->query($query);
        } else {
            $unbind = $this->model->associationsToUnbindForParams($this->requestParams);
            $this->model->unbindModel($unbind);
            $conditions = $this->model->setConditionsForSelect($this->requestParams);
            $data = $this->model->find($this->findType, $conditions);
        }
        $this->appData = $data;
    }

    /**
     * Adiciona / edita dados de empresas
     *
     * @return void
     */
    protected function saveData() {
        $modelClassName = get_class($this->model);

        $this->model->set($this->requestParams);
        if ($this->model->validates()) {
            if ($this->model->saveAll($this->requestParams)) {
                // save OK...

                $data = $this->model->find('first', array(
                    'conditions' => array(
                        "{$modelClassName}.id" => $this->model->id
                    )
                        ));
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_ok'],
                    'data' => $data
                );
            } else {
                // save NOT OK...
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            // validation error
            $this->appData = array(
                'status' => $this->crudOperationStatus ['save_validation'],
                'invalid_fields' => $this->model->validationErrors,
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Exclui dados de empresas
     *
     * @return array
     */
    protected function deleteData() {
        if (!empty($this->requestParams ['id'])) {
            if ($this->Company->delete($this->requestParams ['id'])) {
                // delete ok
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['delete_ok']
                );
            } else {
                // delete error
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['delete_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            // validation error
            $this->appData = array(
                'status' => $this->crudOperationStatus ['delete_invalid_id'],
                'data' => $this->requestParams
            );
        }
    }

    protected function emailToSecondUser() {
        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $data = $conditions;

        $emailData = array(
            'to' => $data ['responsible_email'],
            'Login' => $data ['responsible_email'],
            'Senha' => $data['password']
        );

        if ($this->sendEmail($emailData)) {
            // dados enviados por email
            $this->appData = array(
                'status' => $this->crudOperationStatus ['save_ok'],
                'data' => $this->requestParams
            );
        } else {
            // erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
            $this->appData = array(
                'status' => $this->crudOperationStatus ['error_envio'],
                'data' => $this->requestParams
            );
        }
    }

    protected function myTest() {
        $this->appData = array(
            'status' => 'status teste',
            'data' => 'data o crl');
    }

    protected function teste() {
        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $data = $conditions;

        $newPassword = $this->generateRandomPassword();

        $saveData = array(
            'Company' => array(
                'id' => $data ['id'],
                'password' => $newPassword
            )
        );

        if ($this->Company->save($saveData)) {
            // envia nova senha por e-mail
            $emailData = array(
                'to' => $data ['responsible_email'],
                'Login' => $data ['responsible_email'],
                'Senha' => $newPassword
            );
            if ($this->sendEmail($emailData)) {
                // dados enviados por email
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_ok'],
                    'data' => $this->requestParams
                );
            } else {
                // erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_error'],
                    'data' => $this->requestParams
                );
            }
            
          
        } else {
            // erro ao salvar.
            $this->appData = array(
                'status' => $this->crudOperationStatus ['save_error'],
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Enviar login e senha para empresa
     *
     * @return array
     */
    protected function companyLogin() {
        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $data = $this->model->find('first', $conditions);

        $newPassword = $this->generateRandomPassword();
        // grava nova senha no banco
        $saveData = array(
            'Company' => array(
                'id' => $data ['Company'] ['id'],
                'password' => $newPassword
            )
        );

        if ($this->Company->save($saveData)) {
            // envia nova senha por e-mail
            $emailData = array(
                'to' => $data ['Company'] ['responsible_email'],
                'Login' => $data ['Company'] ['responsible_email'],
                'Senha' => $newPassword
            );
            if ($this->sendEmail($emailData)) {
                // dados enviados por email
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_ok'],
                    'data' => $this->requestParams
                );
            } else {
                // erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            // erro ao salvar.
            $this->appData = array(
                'status' => $this->crudOperationStatus ['save_error'],
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Gera nova senha para acesso ao sistema.
     *
     * @return void
     */
    protected function passwordRecovery() {
        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $data = $this->model->find('first', $conditions);

        if (!empty($data ['Company'] ['id'])) {
            // gera nova senha
            $newPassword = $this->generateRandomPassword();

            // grava nova senha no banco
            $saveData = array(
                'Company' => array(
                    'id' => $data ['Company'] ['id'],
                    'password' => $newPassword  // o hash md5 é feito automaticamente em Company::beforeSave()
                )
            );

            if ($this->Company->save($saveData)) {
                // envia nova senha por e-mail
                $emailData = array(
                    'to' => $data ['Company'] ['responsible_email'],
                    'fancy_name' => $data ['Company'] ['fancy_name'],
                    'email' => $data ['Company'] ['responsible_email'],
                    'newPassword' => $newPassword,
                    'date' => date('d/m/Y'),
                    'hash' => md5($newPassword)
                );

                if ($this->sendEmail($emailData, true)) {
                    // senha salva e enviada por e-mail.
                    $this->appData = array(
                        'status' => $this->crudOperationStatus ['save_ok'],
                        'data' => $this->requestParams
                    );
                } else {
                    // erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
                    $this->appData = array(
                        'status' => $this->crudOperationStatus ['save_error'],
                        'data' => $this->requestParams
                    );
                }
            } else {
                // erro ao salvar.
                $this->appData = array(
                    'status' => $this->crudOperationStatus ['save_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            // não encontrou a empresa. Não existe ou dados incorretos.
            $this->appData = array();
        }
    }

    /**
     * Envia e-mail com com login e senha de empresa
     *
     * @param
     *        	array
     * @return bool
     */
    protected function sendEmail($emailData, $type = false) {
        if ($type == true) {
            // enviando e-mail
            $email = new CakeEmail('passwordRecovery');
            $email->to($emailData ['to']);
            $email->template('Companies.password_recovery', 'default')->emailFormat('html');
        } else {
            // enviando e-mail
            $email = new CakeEmail('companyLogin');
            $email->to($emailData ['to']);
            $email->template('Companies.login', 'default')->emailFormat('html');
        }

        $email->viewVars(array(
            'data' => $emailData
        ));

        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gera senhas aleatórias
     *
     * @return string
     */
    private function generateRandomPassword() {
        $alphabet = "23456789bcdefghijklmnopqrstuwxyzBCDEFGHIJKLMNOPQRSTUWXYZ";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i ++) {
            $n = rand(0, $alphaLength);
            $pass [] = $alphabet [$n];
        }
        return implode($pass);
    }

    public function postEmail($api, $type, $params) {
        $retorno = $this->curlRequest("http://localhost/sesteste/$api/$type.php", $params);
        return $retorno;
    }

}
