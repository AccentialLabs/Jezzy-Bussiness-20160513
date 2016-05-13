<?php

/**
 * All action about Users
 */
require("../Vendor/phpmailer/PHPMailerAutoload.php");

class UserController extends AppController {

    public function __construct($request = null, $response = null) {
        $this->layout = 'default_business';
        $this->set('title_for_layout', 'Usuarios');

        parent::__construct($request, $response);
    }

    public function beforeFilter() {
        if ($this->Session->read('userLoggedType') != 1) {
            $this->render('../Errors/wrong_way');
            //TODO: enviar e-mail para responsavel da empresa avisando da tentativa.
        }
        parent::beforeFilter();
    }

    /**
     * Show populated view
     */
    public function index() {
        $this->set('company', $this->Session->read('CompanyLoggedIn'));
        $this->set('secundaryUserTypes', $this->secundaryUserTypes());
        $this->set('secundaryUsers', $this->getSecundaryUsers($this->Session->read('CompanyLoggedIn.Company.id')));
    }

    /**
     * Update information of primary user.
     * If password change, logout user
     */
    public function updatePrimaryUser() {
        if ($this->request->is('post')) {
            $name = $this->request->data ['User'] ['name'];
            $email = $this->request->data ['User'] ['email'];
            $pass = $this->request->data ['User'] ['pass'];
            if (md5($pass) === $this->Session->read('CompanyLoggedIn.Company.password')) {
                $passNew1 = $this->request->data ['User'] ['passNew1'];
                $passNew2 = $this->request->data ['User'] ['passNew2'];
                if ($passNew1 === $passNew2) {
                    $params = array(
                        'Company' => array(
                            'id' => $this->Session->read('CompanyLoggedIn.Company.id'),
                            'responsible_name' => $name,
                            'email' => $email,
                            'password' => $passNew1
                        )
                    );
                    $updateSenha = $this->AccentialApi->urlRequestToSaveData('companies', $params);
                    $this->redirect("/login/logout");
                } else {
                    $this->Session->setFlash(__('Confirmação da senha diferente da nova senha.'));
                }
            } else {
                if (empty($pass)) {
                    $params = array(
                        'Company' => array(
                            'id' => $this->Session->read('CompanyLoggedIn.Company.id'),
                            'responsible_name' => $name,
                            'email' => $email
                        )
                    );
                    $update = $this->AccentialApi->urlRequestToSaveData('companies', $params);
                    if (is_null($update)) {
                        $company = $this->Session->read('CompanyLoggedIn');
                        $company['Company']['email'] = $email;
                        $company['Company']['responsible_name'] = $name;
                        $this->Session->write('CompanyLoggedIn', $company);
                    }
                } else {
                    $this->Session->setFlash(__('Senha antiga incorreta.'));
                }
            }
        }
        $this->redirect("index");
    }

    /**
     * Add secondary user on company. On success send e-mail
     * @return 0 for error and UserObject in success
     */
    public function addSecondaryUver() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->GeneralFunctions = $this->Components->load('GeneralFunctions');
            $company = $this->Session->read('CompanyLoggedIn');
            $password = $this->GeneralFunctions->generateRandomPassword();
            $param['SecondaryUser']['name'] = $this->request->data['SecondaryUser']['name'];
            $param['SecondaryUser']['email'] = $this->request->data['SecondaryUser']['email'];
            $param['SecondaryUser']['type'] = $this->request->data['SecondaryUser']['type'];
            $param['SecondaryUser']['company_id'] = $company['Company']['id'];
            $param['SecondaryUser']['normalPass'] = $password;
            $param['SecondaryUser']['hashPass'] = md5($password);

            $query = "INSERT INTO secondary_users(name, email, password, company_id, secondary_type_id)"
                    . " VALUES('" . $param['SecondaryUser']['name'] . "'"
                    . ",'" . $param['SecondaryUser']['email'] . "'"
                    . ",'" . $param['SecondaryUser']['hashPass'] . "'"
                    . "," . $param['SecondaryUser']['company_id'] . ""
                    . "," . $param['SecondaryUser']['type'] . ");";
            $params = array(
                'User' => array(
                    'query' => $query
                )
            );
            $addUserOffer = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
			
			$this->sendEmailNewUserTWO($param['SecondaryUser']['email'], $password);
			
            if (is_null($addUserOffer)) {
                //TODO: enviar email ao adicionar o usuario com sucesso.
                $data['normalPass'] = $param['SecondaryUser']['normalPass'];
                $data['name'] = $param['SecondaryUser']['name'];
                $data['email'] = $param['SecondaryUser']['email'];
                $msgReturn = $this->GeneralFunctions->postEmail('companies', 'secondaryUser', $data);
                $newUser = $this->getSecundaryUserByLoginAndPassword($param['SecondaryUser']['email'], $param['SecondaryUser']['hashPass'], $company['Company']['id']);
                return json_encode($newUser[0]);
            }

            // $this->sendEmail("matheusodilon0@gmail.com", "esse é o corpo do email", "esse é o assunto");
        }
        return 0;
    }

    /**
     * Remove the secundary use from system
     * @return int
     */
    public function removeSecondUser() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $secondUserId = $this->request->data['SecondaryUser']['id'];
            $sql = "UPDATE secondary_users SET excluded = 1 where id = {$secondUserId};";
            $params = array('User' => array('query' => $sql));
            $delete = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
            if (is_null($delete)) {
                return 1;
            }
        }
        return 0;
    }

    // <editor-fold  defaultstate="collapsed" desc="Private Methods">
    /**
     * Get all types os secundary users on system
     * @return Array with all types of secundary users
     */
    private function secundaryUserTypes() {
        $query = "select * from secondary_users_types;";
        $params = array(
            'User' => array(
                'query' => $query
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
    }

    /**
     * Get secundary users of company
     * @param int $companyId
     * @return Array os all secundarys users of this company
     */
    private function getSecundaryUsers($companyId) {
        $secondUserSQL = "select * "
                . "from secondary_users "
                . "inner join secondary_users_types on secondary_users.secondary_type_id = secondary_users_types.id "
                . "where company_id  = {$companyId} ;";
        $secondUserParam = array(
            'User' => array(
                'query' => $secondUserSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);
    }

    /**
     * Get user based on login and password
     * @param string $secUserName
     * @param string $secUserPass
     * @param int $companyId
     * @return Array with one element - the user with the login and pass send
     */
    private function getSecundaryUserByLoginAndPassword($secUserName, $secUserPass, $companyId) {
        $secondUserSQL = "select * "
                . "from secondary_users "
                . "inner join secondary_users_types on secondary_users.secondary_type_id = secondary_users_types.id "
                . "where secondary_users.email = '{$secUserName}' "
                . "and secondary_users.password = '{$secUserPass}'"
                . "and company_id  = {$companyId} AND secondary_users.excluded = 0;";
        $secondUserParam = array(
            'User' => array(
                'query' => $secondUserSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);
    }

    public function ajaxAddNewUser() {
        $this->autoRender = false;

        $name = $this->request->data['User']['name'];
        $email = $this->request->data['User']['email'];
        $pass = $this->geraSenha();
		
        $sql = "INSERT INTO users(
		`name`,
		`email`, 
		`password`
		) 
		VALUES(
		'" . $this->request->data['User']['name'] . "',
		'" . $this->request->data['User']['email'] . "',
		'" . md5($pass) . "'
		
		);";

        $scheduleParam = array(
            'User' => array(
                'query' => $sql
            )
        );
        $scheduleReturn = $this->AccentialApi->urlRequestToGetData('users', 'query', $scheduleParam);

		$this->sendEmailNewUserTWO($this->request->data['User']['name'], $pass);
        //$this->sendEmail("matheusodilon0@gmail.com", "esse é o corpo do email", "esse é o assunto");

        return 'false';
    }

    public function editSecondaryUser() {
        $this->autoRender = false;
        $sql = "UPDATE secondary_users 
				SET
				name = '" . $this->request->data['SecondaryUser']['name'] . "',
				email = '" . $this->request->data['SecondaryUser']['email'] . "',
				secondary_type_id = '" . $this->request->data['SecondaryUser']['type'] . "'
				WHERE id = " . $this->request->data['SecondaryUser']['id'] . ";";

        $scheduleParam = array(
            'User' => array(
                'query' => $sql
            )
        );

        $scheduleReturn = $this->AccentialApi->urlRequestToGetData('users', 'query', $scheduleParam);
        return 'false';
    }

    public function reativeSecondUser() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $secondUserId = $this->request->data['SecondaryUser']['id'];
            $sql = "UPDATE secondary_users SET excluded = 0 where id = {$secondUserId};";
            $params = array('User' => array('query' => $sql));
            $delete = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
            if (is_null($delete)) {
                return 1;
            }
        }
        return 0;
    }

    public function sendEmailNewUser($name, $email, $pass) {
        $mail = new PHPMailer(true);

        // Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = 'oo0MvB2Qw'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Subject = "Bem-Vindo ao Jezzy"; // Assunto da mensagem
        $mail->Body = "Ola, {$name} seja bem-vindo ao Jezzy, seus dados de login sao: <br/> Usuário: {$email} <br/> Senha: {$pass} <br/><br/> <b>Boas Compras!</b>";
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
    }

    function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
// Caracteres de cada tipo
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
// Variáveis internas
        $retorno = '';
        $caracteres = '';
// Agrupamos todos os caracteres que poderão ser utilizados
        $caracteres .= $lmin;
        if ($maiusculas)
            $caracteres .= $lmai;
        if ($numeros)
            $caracteres .= $num;
        if ($simbolos)
            $caracteres .= $simb;
// Calculamos o total de caracteres possíveis
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);
// Concatenamos um dos caracteres na variável $retorno
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }

    public function sendEmailNewUserTWO($email, $pass) {
        $mail = new PHPMailer(true);


        // Define os dados do servidor e tipo de conexão
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "pro.turbo-smtp.com"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@jezzy.com.br'; // Usuário do servidor SMTP
        $mail->Password = 'oo0MvB2Qw'; // Senha do servidor SMTP
        // Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->From = "contato@jezzy.com.br"; // Seu e-mail
        $mail->FromName = "Contato - Jezzy"; // Seu nome

        $mail->AddAddress("{$email}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $email = '<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="4"><img src="https://uploaddeimagens.com.br/images/000/605/011/original/01.jpg?1460994666"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f2f2f2;">
                <td colspan="4">
                    <br/>
            <center>
                <span style="color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;"><i>Matheus, seja bem-vindo(a)!</i></span>
            </center>
            <br/><br/>
        </td>
    </tr>
    <tr>
        <td colspan="4"><img src="https://uploaddeimagens.com.br/images/000/605/012/original/02.jpg?1460994743"  style="vertical-align: bottom;"/></td>
    </tr>
    <tr style="background: #f2f2f2;">
        <td colspan="4">
    <center>
        <span style="color: #9b9b9b; font-family: Helvetica, Arial, sans-serif; font-size: 24px;">Salão XPTO</span>
    </center>
    <br/>
</td>
</tr>
<tr>
    <td colspan="4"><img src="https://uploaddeimagens.com.br/images/000/605/013/original/03.jpg?1460994775"  style="vertical-align: bottom;"/></td>
</tr>
<tr style="background: #f2f2f2; text-align: center;">
    <td colspan="4" style=" font-family: Helvetica, Arial, sans-serif; font-size: 16px;">
        <br/>
        <span style="color: #2597ac;">Login:</span><span style="color: #9b9b9b;">' . $email . '</span><br/>
        <span style="color: #2597ac;">Senha:</span><span style="color: #9b9b9b;">' . $pass . '</span><br/>
        <br/>
    </td>
</tr>
<tr style="background: #f7f7f7;">
        <td colspan="4">
            <br/>
            <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/04.jpg" />
            <br/>
        </td>
    </tr>
    <tr style="background: #f7f7f7; width: 800px;">
        <td style="width: 50px;" colspan="1">
        </td>
        <td style="width: 150px; text-align: right;" colspan="1">
            <a href="#"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/App Store.png" width="80"/></a>
        </td>
         <td style="width: 150px; text-align: left;" colspan="1">
             <a href="#"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/App Store.png" width="80"/></a>
        </td>
       <td style="width: 50px;" colspan="1">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/05-1.jpg" height="30" width="800" style="vertical-align: bottom;"/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/06.jpg"  style="vertical-align: bottom;"/>
        </td>
    </tr>
    <tr>
        <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg" width="200" style="vertical-align: bottom;"/></td>
          <td  colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg" width="200" style="vertical-align: bottom;"/></td>
            <td colspan="1"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg" width="200" style="vertical-align: bottom;"/></td>
              <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg" width="200" style="vertical-align: bottom;"/></td>
    </tr>
     <tr>
        <td colspan="4">
            <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/11.jpg"  style="vertical-align: bottom;"/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
        </td>
    </tr>
</table>';

        $mail->Subject = "Bem-Vindo ao Jezzy Empresas"; // Assunto da mensagem
        $mail->Body =  utf8_decode($email);
        $mail->AltBody = "";

        // Define os anexos (opcional)
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
        // Envia o e-mail
        $enviado = $mail->Send();

// Limpa os destinatários e os anexos
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
    }
	
	
	/*
	* CRIA USUÁRIO MOBILE
	* REQUISICAO DESSA FUNÇÃO DEVE SER FEITA VIA POST
	*/
	public function createMobileUser(){
		//$this->autoRender = false;
		
		$sql = "INSERT INTO users(
		`name`, 
		`email`, 
		`password`,
		`birthday`, 
		`phone`)
		VALUES(
		'John Travolta',
		'john@123.com',
		'e10adc3949ba59abbe56e057f20f883e',
		'1987-09-09',
		'11 11115555'
		)";
		
		$param = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $param);
	
	}
	

}