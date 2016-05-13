<?php

/**
 * 
 * @author Rodrigo Salles, Wilson Junior
 * @copyright Accential
 */
App::uses('UsersAppController', 'Users.Controller');
App::uses('CakeEmail', 'Network/Email');

//require 'Vendor/aws.phar';
//use Aws\Ses\SesClient;

class UsersController extends UsersAppController {

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
     * Direciona para os métodos corretos de acordo com requestType
     * @return void
     */
    protected function init() {
        switch ($this->requestType) {
            case 'get': $this->getData();
                break;
            case 'save': $this->saveData();
                break;
            case 'delete': $this->deleteData();
                break;
            case 'createUser': $this->createUser();
                break;
            case 'userLogin': $this->userLogin();
                break;
            case 'passwordRecovery': $this->passwordRecovery();
                break;
            case 'saveWishlist': $this->saveWishlist();
                break;
            case 'emailTest' : $this->testeEmail();
                break;
            case 'paymentStatusChange' : $this->paymentStatusChange();
                break;
            case 'sesTeste' : $this->sesTeste();
                break;
            case 'newUserAndroid' : $this->sendEmailNewUserAnd();
                break;
        }
    }

    /**
     * Retorna dados solicitados
     * @return void
     */
    protected function getData() {
        if ($this->findType === 'query') {
            $modelClassName = get_class($this->model);
            $query = $this->requestParams[$modelClassName]['query'];
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
     * Adiciona / edita dados de usuários
     * @return void
     */
    protected function saveData() {
        $modelClassName = get_class($this->model);

        $this->model->set($this->requestParams);
        if ($this->model->validates()) {
            if ($this->model->saveAll($this->requestParams)) {
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
        } else {
            # validation error
            $this->appData = array(
                'status' => $this->crudOperationStatus['save_validation'],
                'invalid_fields' => $this->model->validationErrors,
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Exclui dados de usuários
     * @return void
     */
    protected function deleteData() {
        if (!empty($this->requestParams['id'])) {
            if ($this->User->delete($this->requestParams['id'])) {
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
     * Inclui um novo usuário na base
     * @return void
     */
    protected function createUser() {
        if (!empty($this->requestParams)) {
            $userAlreadyExists = false;

            $fields = array('User.id');
            $response = $this->User->findByEmail($this->requestParams['User']['email'], $fields);
            if (!empty($response))
                $userAlreadyExists = true;

            if (!$userAlreadyExists) {
                // dados para enviar por e-mail: login e senha
                $emailData = array(
                    'name' => $this->requestParams['User']['name'],
                    'to' => $this->requestParams['User']['email'],
                    'Login' => $this->requestParams['User']['email'],
                    'Senha' => $this->requestParams['User']['password']
                );

                $this->User->create($this->requestParams);
                if ($this->User->save($this->requestParams)) {
                    # save OK...
                    $data = $this->User->find('first', array('conditions' => array("User.id" => $this->User->id)));

                    if ($this->sendEmail($emailData, true)) {
                        $this->appData = array(
                            'status' => $this->crudOperationStatus['save_ok'],
                            'data' => $data
                        );
                    }
                } else {
                    # save NOT OK...
                    $this->appData = array(
                        'status' => $this->crudOperationStatus['save_error'],
                        'data' => $this->requestParams
                    );
                }
            } else {
                # user exists
                $this->appData = array(
                    'status' => 'USER_ALREADY_EXISTS',
                    'data' => $this->requestParams
                );
            }
        }
    }

    /**
     * Gera nova senha para acesso ao sistema.
     * @return void
     */
    protected function passwordRecovery() {
        $conditions = $this->model->setConditionsForSelect($this->requestParams);
        $data = $this->model->find('first', $conditions);

        if (!empty($data['User']['id'])) {
            # gera nova senha
            $newPassword = $this->generateRandomPassword();

            # grava nova senha no banco
            $saveData = array(
                'User' => array(
                    'id' => $data['User']['id'],
                    'password' => $newPassword, // o hash md5 é feito automaticamente em User::beforeSave()
                )
            );

            if ($this->User->save($saveData)) {
                # envia nova senha por e-mail
                $emailData = array(
                    'to' => $data['User']['email'],
                    'name' => $data['User']['name'],
                    'email' => $data['User']['email'],
                    'newPassword' => $newPassword,
                    'date' => date('d/m/Y'),
                    'hash' => md5($newPassword)
                );

                if ($this->sendEmail($emailData)) {
                    # senha salva e enviada por e-mail.
                    $this->appData = array(
                        'status' => $this->crudOperationStatus['save_ok'],
                        'data' => $this->requestParams
                    );
                } else {
                    # erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
                    $this->appData = array(
                        'status' => $this->crudOperationStatus['save_error'],
                        'data' => $this->requestParams
                    );
                }
            } else {
                # erro ao salvar.
                $this->appData = array(
                    'status' => $this->crudOperationStatus['save_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            # não encontrou o usuário. Não existe ou dados incorretos.
            $this->appData = array();
        }
    }

    /**
     * Enviar login e senha para empresa
     * @return array
     */
    protected function userLogin() {

        # envia nova senha por e-mail
        $emailData = array(
            'to' => $this->requestParams['User']['email'],
            'Login' => $this->requestParams['User']['email'],
            'Senha' => $this->requestParams['User']['password']
        );
        if ($this->sendEmail($emailData, true)) {
            # dados enviados por email
            $this->appData = array(
                'status' => $this->crudOperationStatus['save_ok'],
                'data' => $this->requestParams
            );
        } else {
            # erro ao enviar e-mail. Tratar como erro ao salvar. Basta repetir o procedimento para nova senha.
            $this->appData = array(
                'status' => $this->crudOperationStatus['save_error'],
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Envia e-mail com dados de $emailData para recuperação de senha
     * @param array
     * @param bool $createUser - FALSE para enviar e-mail de recuperação de senha. TRUE para boas-vindas de novo usuário
     * @return bool
     */
    protected function sendEmail($emailData, $createUser = false) {
        if ($createUser) {
            // enviando e-mail para boas-vindas de novo usuário
            $email = new CakeEmail('userLogin');
            $email->to($emailData['to']);
            $email->template('Users.user_login', 'default')->emailFormat('html');
        } else {
            // enviando e-mail para recuperação de senha
            $email = new CakeEmail('passwordRecovery');
            $email->to($emailData['to']);
            $email->template('Users.share_offer', 'default')->emailFormat('html');
        }
        $email->viewVars(array('data' => $emailData));

        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Salva Wishlist de usuario e envia para empresas
     * @return void
     */
    protected function saveWishlist() {
        $category = $this->requestParams['UsersWishlist']['category_id'];
        $sub_category = $this->requestParams['UsersWishlist']['sub_category_id'];

        $par = array('Company' => array('fields' => array('id'), 'conditions' => array('category_id' => $category, 'sub_category_id' => $sub_category, 'status' => 'ACTIVE')));
        $empresas = $this->Utility->urlRequestToGetData('companies', 'all', $par);

        //verifica se retornou alguma empresa para wishlist selecionado
        if (is_array($empresas)) {
            //salva wishlist				
            if ($this->User->UsersWishlist->save($this->requestParams)) {
                # save OK...					
                $data = $this->User->UsersWishlist->find('first', array('conditions' => array("UsersWishlist.id" => $this->User->UsersWishlist->id)));
                $this->appData = array(
                    'status' => $this->crudOperationStatus['save_ok'],
                    'data' => $data
                );
                //pegando empresas selecionadas por categoria e subcategoria do wishlist				
                foreach ($empresas as $empresa) {
                    $arrayParams = array(
                        'UsersWishlistCompany' => array(
                            'id' => NULL,
                            'company_id' => $empresa['Company']['id'],
                            'wishlist_id' => $data['UsersWishlist']['id'],
                            'user_id' => $data['UsersWishlist']['user_id'],
                            'status' => 'WAIT',
                            'offer_id' => NULL
                        )
                    );
                    $save_companies_wishlist = $this->User->UsersWishlistCompany->save($arrayParams);
                }
            } else {
                # save NOT OK...
                $this->appData = array(
                    'status' => $this->crudOperationStatus['save_error'],
                    'data' => $this->requestParams
                );
            }
        } else {
            # save NOT OK...
            $this->appData = array(
                'status' => 'Nenhuma empresa selecionada para esse desejo',
                'data' => $this->requestParams
            );
        }
    }

    /**
     * Gera senhas aleatórias
     * @return string
     */
    private function generateRandomPassword() {
        $alphabet = "23456789bcdefghijklmnopqrstuwxyzBCDEFGHIJKLMNOPQRSTUWXYZ";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    protected function testeEmail() {
        $emailData = array(
            'to' => $this->requestParams['Email']['email'],
            'name' => 'Matheus',
            'email' => $this->requestParams['Email']['email'],
            'newPassword' => $this->requestParams['Email']['texto'],
            'date' => date('d/m/Y'),
            'hash' => md5('novasenha'),
            'photo' => $this->requestParams['Email']['texto'],
            'link' => $this->requestParams['Email']['link']
        );

        $email = new CakeEmail('userLogin');
        $email->to($emailData['to']);
        $email->template('Users.share_offer', 'default')->emailFormat('html');

        $email->viewVars(array('data' => $emailData));

        $email->send();

        $this->appData = array(
            'status' => $this->crudOperationStatus['save_ok'],
            'data' => $this->requestParams
        );
    }

    //envia email de mudança de status do pagamento para usuario
    protected function paymentStatusChange() {
        $data = $this->requestParams;

//        $emailData = array(
//            'to' => "matheusodilon0@gmail.com",
//            'name' => 'Matheus',
//            'email' => "matheusodilon0@gmail.com",
//            'newPassword' => "matheusodilon0@gmail.com",
//            'date' => date('d/m/Y'),
//            'hash' => md5('novasenha'),
//            'photo' => "matheusodilon0@gmail.com",
//            'link' => "matheusodilon0@gmail.com"
//        );

        $emailData = $this->requestParams;

        $email = new CakeEmail('testeAdventa');
        $email->to($emailData['to']);
        $email->template('Users.mudanca_status', 'default')->emailFormat('html');

        $email->viewVars(array('data' => $emailData));

        if ($email->send()) {
            $envio = "enviado!!!";
        } else {
            $envio = "nao enviado!!!";
        }

        $this->appData = array('teste' => 'recebeu o retorno', 'data' => $data, 'enviado' => $envio);
    }

    protected function sesTeste() {
        $data = $this->requestParams;
        $this->appData = array('TESTE' => 'NONONEOWNEW', 'teste2' => 'rsrs');
    }

    public function sendEmailNewUserAnd() {
        $name = $this->requestParams['userName'];
        $email = $this->requestParams['userEmail'];
        $pass = $this->requestParams['pwd'];

        $data['userName'] = $name;
        $data['userEmail'] = $email;
        $data['pwd'] = $pass;

        $return = $this->Utility->postEmail('users', 'newUser', $data);
    }

}
