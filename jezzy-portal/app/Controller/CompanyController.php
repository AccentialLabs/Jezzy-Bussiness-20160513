<?php

/**
 * All actions about user login on Jezzy
 */
require("../Vendor/phpmailer/PHPMailerAutoload.php");

class CompanyController extends AppController {

    // token e key para cadastro no moip
    protected $key = '11PB4FPN68M1FE8MAPWUDIMEHFIGM8P6DMSBNXZZ';
    protected $token = 'JK75V6UGKYYUZR2ICVHJSSLD687UEJ9H';

    public function __construct($request = null, $response = null) {
        $this->layout = 'default_login';
        parent::__construct($request, $response);
    }

    /**
     * Check the session every time the class is call, exepts on 'logout' 
     */
    public function beforeFilter() {
        if ($this->action !== "logout") {
            
        }
    }

    /**
     * Used just to show 'view' 
     */
    public function createDir() {

        $companyName = $this->request->data ['companyName'];

        mkdir("/../../{$companyName}", 0700);
    }

    public function registra() {
        $this->layout = "";
    }

    public function createCompany() {
        
    }

    public function register() {
        $this->layout = "";
    }

    /**
     * Action responsável por cadastrar empresa
     */
    public function inserCompany() {
        $this->layout = "";

        $password = $this->geraSenha();
        // CRIAÇÃO DE FORNECEDOR
        $sql = "INSERT INTO companies(" .
                "`corporate_name`,"
                . "`fancy_name`,"
                . "`description`,"
                . "`site_url`,"
                . "`category_id`,"
                . "`sub_category_id`,"
                . "`cnpj`,"
                . "`email`,"
                . "`password`,"
                . "`phone`,"
                . "`phone_2`,"
                . "`address`,"
                . "`complement`,"
                . "`city`,"
                . "`state`,"
                . "`district`,"
                . "`number`,"
                . "`zip_code`,"
                . "`responsible_name`,"
                . "`responsible_cpf`,"
                . "`responsible_email`,"
                . "`responsible_phone`,"
                . "`responsible_phone_2`,"
                . "`responsible_cell_phone`,"
                . "`responsible_birthday`,"
                . "`logo`,"
                . "`status`,"
                . "`login_moip`,"
                . "`register`,"
                . "`facebook_install`,"
                . "`date_register`,"
                . "`open_hour`,"
                . "`close_hour`,"
                . "`first_login`"
                . ") VALUES("
                . "'" . $this->request->data['Company']['corporate_name'] . "',"
                . "'" . $this->request->data['Company']['fancy_name'] . "',"
                . "'descricao forn',"
                . "'" . $this->request->data['Company']['site'] . "',"
                . "15,"
                . "15, "
                . "'" . $this->request->data['Company']['cnpj'] . "',"
                . "'" . $this->request->data['Company']['email'] . "',"
                . "'" . md5($password) . "',"
                . "'" . $this->request->data['Company']['phone'] . "',"
                . "'" . $this->request->data['Company']['phone_2'] . "',"
                . "'" . $this->request->data['Company']['address'] . "',"
                . "'" . $this->request->data['Company']['complement'] . "',"
                . "'" . $this->request->data['Company']['city'] . "',"
                . "'" . $this->request->data['Company']['uf'] . "',"
                . "'" . $this->request->data['Company']['district'] . "',"
                . "'" . $this->request->data['Company']['number'] . "',"
                . "'" . $this->request->data['Company']['cep'] . "',"
                . "'" . $this->request->data['Company']['responsible_name'] . "',"
                . "'" . $this->request->data['Company']['responsible_cpf'] . "',"
                . "'" . $this->request->data['Company']['responsible_email'] . "',"
                . "'" . $this->request->data['Company']['responsible_phone'] . "',"
                . "'" . $this->request->data['Company']['responsible_phone_2'] . "',"
                . "'" . $this->request->data['Company']['responsible_cell'] . "',"
                . "'" . $this->request->data['Company']['responsible_birthday'] . "',"
                . "'logo',"
                . "'ACTIVE',"
                . "0,"
                . "0,"
                . "0,"
                . "'0000-00-00 00:00:00',"
                . "'09:00:00.000000',"
                . "'18:00:00.000000',"
                . "1"
                . ");";

        $CompanysParam = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $CompanysParam);


        $selectSql = "select * from companies where cnpj LIKE '" . $this->request->data['Company']['cnpj'] . "';";
        $SelCompanyParam = array(
            'User' => array(
                'query' => $selectSql
            )
        );

        $retornoSelect = $this->AccentialApi->urlRequestToGetData('users', 'query', $SelCompanyParam);

        // CRIANDO DIRETORIOS PARA COMPANY
        $this->AccentialApi->createCompanyDir($retornoSelect[0]['companies']['id']);


        //	UPDATING LOGO
        $logo = $this->saveCompanyLogo($this->request->data['Company']['logo'], $retornoSelect[0]['companies']['id']);
        $LogoSql = "UPDATE companies SET logo = '" . $logo . "' WHERE id = " . $retornoSelect[0]['companies']['id'] . ";";
        $UpdCompanyParam = array(
            'User' => array(
                'query' => $LogoSql
            )
        );

        $reti = $this->AccentialApi->urlRequestToGetData('users', 'query', $UpdCompanyParam);


        //CRIANDO CATEGORY SUB CATEGORY
        $categorySql = "insert into companies_categories_sub_categories(category_id, sub_category_id, company_id) values(7,4,{$retornoSelect[0]['companies']['id']});";
        $CategoryCompanyParam = array(
            'User' => array(
                'query' => $categorySql
            )
        );

        $this->AccentialApi->urlRequestToGetData('users', 'query', $CategoryCompanyParam);


        //	ENVIANDO EMAIL COM USUARIO E SENHA
        $this->sendEmailNewUser($this->request->data['Company']['fancy_name'], $this->request->data['Company']['email'], $password);
		$this->sendEmailNewUserWithLayout($this->request->data['Company']['fancy_name'], $this->request->data['Company']['email'], $password);
		
        $this->redirect(array('controller' => 'login', 'action' => 'index'));
    }

    public function selectCompany() {
        $this->layout = "";

        $sql = "SELECT *  from classes inner join subclasses on  subclasses.classe_id = classes.id;";
        $CompanysParam = array(
            'User' => array(
                'query' => $sql
            )
        );

        $retorno = $this->AccentialApi->urlRequestToGetData('users', 'query', $CompanysParam);

        echo print_r($retorno);
    }

    public function saveCompanyLogo($logo, $companyId) {

        $this->autoRender = false;
        $url = "jezzyuploads/company-" . $companyId . "/config";
        $offersExtraPhotos = $this->AccentialApi->uploadAnyPhotoCompany($url, $logo, $companyId);
        // $saveDatabase = $this->saveImageUrl($this->request['data']['offerId'], $offersExtraPhotos, true);

        return $offersExtraPhotos;
    }

    public function sendEmailNewUser($fancyName, $companyemail, $pass) {
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

        $mail->AddAddress("{$companyemail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Subject = "Bem-Vindo ao Jezzy Empresas"; // Assunto da mensagem
        $mail->Body = "Ola, {$fancyName} seja bem-vindo ao Jezzy Empresas, seus dados de login sao: <br/> Usuario: {$companyemail} <br/> Senha: {$pass} <br/><br/> <b>Boas Compras!</b>";
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

    public function searchAddressByZipcode() {
		$this->layout = "";
       // $this->autoRender = false;
	   $cep = $this->request->data['cep'];
        $cURL = curl_init("http://api.postmon.com.br/v1/cep/{$cep}");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($cURL);
        curl_close($cURL);
        echo $resultado;
    }

    public function moipPreCadastroUrl() {
        $sua_key = $this->key;
        $seu_token = $this->token;
        $auth = $seu_token . ':' . $sua_key;
        /*
         * $sua_key = 'SKMQ5HKQFTFRIFQBJEOROIGM70I6QVIN9KA5YIWB'; $seu_token = 'WOA4NBQ2AUMHJQ2NJIA6Q6X4ECXHFJUR'; $auth = $seu_token.':'.$sua_key;
         */

        /**  $xml = "<PreCadastramento>
          <prospect>
          <idProprio>{$cadastro[0]['Company']['id']}</idProprio>
          <nome>{$cadastro[0]['Company']['responsible_name']}</nome>
          <sobrenome></sobrenome>
          <email>{$cadastro[0]['Company']['responsible_email']}</email>
          <dataNascimento></dataNascimento>
          <rg></rg>
          <cpf>{$cadastro[0]['Company']['responsible_cpf']}</cpf>
          <cep>{$cadastro[0]['Company']['zip_code']}</cep>
          <rua>{$cadastro[0]['Company']['address']}</rua>
          <numero>{$cadastro[0]['Company']['number']}</numero>
          <complemento>{$cadastro[0]['Company']['complement']}</complemento>
          <bairro>{$cadastro[0]['Company']['district']}</bairro>
          <cidade>{$cadastro[0]['Company']['city']}</cidade>
          <estado>{$cadastro[0]['Company']['state']}</estado>
          <telefoneFixo>{$cadastro[0]['Company']['responsible_phone']}</telefoneFixo>
          <razaoSocial>{$cadastro[0]['Company']['corporate_name']}</razaoSocial>
          <nomeFantasia>{$cadastro[0]['Company']['fancy_name']}</nomeFantasia>
          <cnpj>{$cadastro[0]['Company']['cnpj']}</cnpj>
          <cepEmpresa>{$cadastro[0]['Company']['zip_code']}</cepEmpresa>
          <ruaEmpresa>{$cadastro[0]['Company']['address']}</ruaEmpresa>
          <numeroEmpresa>{$cadastro[0]['Company']['number']}</numeroEmpresa>
          <complementoEmpresa></complementoEmpresa>
          <bairroEmpresa>{$cadastro[0]['Company']['district']}</bairroEmpresa>
          <cidadeEmpresa>{$cadastro[0]['Company']['city']}</cidadeEmpresa>
          <estadoEmpresa>{$cadastro[0]['Company']['state']}</estadoEmpresa>
          <telefoneFixoEmpresa>{$cadastro[0]['Company']['phone']}</telefoneFixoEmpresa>
          <tipoConta>1</tipoConta>
          </prospect>
          </PreCadastramento>
          "; */
        $xml = "<PreCadastramento>
		<prospect>
		<idProprio>123</idProprio>
		<nome>Marcos do Santos</nome>
						<sobrenome></sobrenome>
							<email>marcos@santos.com</email>
						<dataNascimento></dataNascimento>
							<rg></rg>
							<cpf>000.000.000-09</cpf>
						<cep>04013040</cep>
									<rua>Rua Cubatão</rua>
											<numero>411</numero>
						<complemento>sala 2</complemento>
							<bairro>vila mariana</bairro>
						<cidade>são paulo</cidade>
									<estado>SP</estado>
									<telefoneFixo>0000000000</telefoneFixo>
											<razaoSocial>Sale For me LTDA</razaoSocial>
											<nomeFantasia>Sale for M</nomeFantasia>
											<cnpj>000000000000000000</cnpj>
													<cepEmpresa>04013040</cepEmpresa>
													<ruaEmpresa>Rua cuvbatão</ruaEmpresa>
													<numeroEmpresa>411</numeroEmpresa>
													<complementoEmpresa></complementoEmpresa>
													<bairroEmpresa>vl mariana</bairroEmpresa>
													<cidadeEmpresa>são paulo</cidadeEmpresa>
													<estadoEmpresa>SP</estadoEmpresa>
													<telefoneFixoEmpresa>{00999998888</telefoneFixoEmpresa>
													<tipoConta>1</tipoConta>
													</prospect>
													</PreCadastramento>
													";

        // pr($xml);exit;
        // O HTTP Basic Auth � utilizado para autentica��o
        $header [] = "Authorization: Basic " . base64_encode($auth);

        // URL do SandBox - Nosso ambiente de testes
        // $url = "https://desenvolvedor.moip.com.br/sandbox/ws/alpha/PreCadastramento";
        $url = "https://www.moip.com.br/ws/alpha/PreCadastramento";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        // header que diz que queremos autenticar utilizando o HTTP Basic Auth
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        // informa nossas credenciais
        curl_setopt($curl, CURLOPT_USERPWD, $auth);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
        curl_setopt($curl, CURLOPT_POST, true);

        // Informa nosso XML de instru��o
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
        $ret = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $xml = simplexml_load_string($ret);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        print_r($array);
    }

    public function carrega() {
        $this->layout = "";
    }
	
	  public function sendEmailNewUserWithLayout($fancyName, $companyemail, $pass) {
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

        $mail->AddAddress("{$companyemail}");

        // Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->Subject = "Bem-Vindo ao Jezzy Empresas"; // Assunto da mensagem
        $mail->Body = ' <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td colspan="4"><img src="http://www.schabla.com.br/jezzy_images/boas-vindas/01.jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f7f7f7; text-align: center;">
                <td colspan="4">
                    <br/>
                    <span style="color: #999933; font-family: Helvetica, Arial, sans-serif; font-size: 36px;"><i>'.$fancyName.', seja bem-vindo!</i></span>
                    <br/>
                    <br/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="http://www.schabla.com.br/jezzy_images/boas-vindas/03.jpg" width="600" style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="background: #f7f7f7;">
                <td colspan="4" style="text-align: center;">
                    <span style="color: #2597AC; font-size: 12px;  font-family: Helvetica, Arial, sans-serif;">
                        <br/>
                        <b> E-mail: '.$companyemail.'<br/>
                            Senha: '.$pass.'</b>
                        <br/>
                    </span>
                </td>
            </tr>
            <tr style="background: #f7f7f7;">
                <td colspan="4">
                    <br/>
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/04.jpg" width="600"/>
                    <br/>
                </td>
            </tr>
            <tr style="background: #f7f7f7; width: 600px;">
                <td style="width: 50px;" colspan="1">
                </td>
                <td style="width: 150px; text-align: right;" colspan="1">
                    <a href="#"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/App%20Store.png" width="80"/></a>
                </td>
                <td style="width: 150px; text-align: left;" colspan="1">
                    <a href="#"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/Google%20Play.png" width="80"/></a>
                </td>
                <td style="width: 50px;" colspan="1">
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/05-1.jpg" width="600" height="30" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/06.jpg" width="600" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td  colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom;"/></td>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/11.jpg" width="600" style="vertical-align: bottom;"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/12.jpg" width="600" style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';
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

}
