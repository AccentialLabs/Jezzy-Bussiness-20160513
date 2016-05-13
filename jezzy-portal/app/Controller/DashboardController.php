<?php

require("../Vendor/phpmailer/PHPMailerAutoload.php");

class DashboardController extends AppController {

    public function __construct($request = null, $response = null) {
        $this->layout = 'default_business';
        parent::__construct($request, $response);
    }

    public function index() {
        $_SESSION['offerByUserId'] = null;
        $company = $this->Session->read('CompanyLoggedIn');
        if ($this->Session->read('userLoggedType') == 1) { //system admin
            $this->set('secundary_users', $this->getSecundaruUsers($company));
        } else {
            $user = $this->Session->read('SecondaryUserLoggedIn')[0]['secondary_users'];
            $this->set('secundary_users', $this->getSecundaruUsers($company, $user));
        }
        $births = $this->getBirthDays($company);
        $this->set('birthdays', $births);
        $this->set('deliveryToday', $this->getNumberDeliveryToday($company));
        $this->set('schedules', $this->getAllSchecule($company));
        $this->set('allSchedulesNext', $this->getAllSchecule($company, " > "));
        $this->set('allSchedulesPrevious', $this->getAllSchecule($company, " < "));
        $allCheckouts = $this->getLastCheckout($company);
        $checkouts1 = "";
        $checkouts2 = "";
        if (isset($allCheckouts[0]) && is_array($allCheckouts[0])) {
            $checkouts1 = $allCheckouts[0];
        }
        if (isset($allCheckouts[1]) && is_array($allCheckouts[1])) {
            $checkouts2 = $allCheckouts[1];
        }
        $this->set('checkouts1', $checkouts1);
        $this->set('checkouts2', $checkouts2);

        $servcs = $this->getCompanyServices($company);
        $secondaryUsers = $this->getSecundaryUsers($company);



        $this->set('secondaryUsers', $secondaryUsers);
        $this->set('services', $servcs);
        $this->set('company', $company);
    }

    public function personalScheduleDashboard() {
        $this->layout = '';
        if ($this->request->is('post')) {
            $this->GeneralFunctions = $this->Components->load('GeneralFunctions');
            $dataSend = $this->GeneralFunctions->convertDateBrazilToSQL($this->request->data['scheduleDay']);
            $userId = $this->request->data['userId'];
            $company = $this->Session->read('CompanyLoggedIn');
            $dateTimeHoje = new DateTime(date('Y-m-d'));
            $dateTimeSend = new DateTime($dataSend);
            $numberOfDays = (int) $dateTimeHoje->diff($dateTimeSend)->format('%a');
            if ($numberOfDays == 0) {
                $this->set('schedules', $this->getSchecule($company, $userId));
            } else {
                if ($numberOfDays > 0) {
                    $this->set('schedules', $this->getScheculeNext($company, $userId, $dataSend));
                } else {
                    $this->set('schedules', $this->getScheculePrevious($company, $userId, $dataSend));
                }
            }
        } else {
            $this->autoRender = false;
            return false;
        }
    }

    // <editor-fold  defaultstate="collapsed" desc="Private methods">
    /**
     * Gets the last checkout itens
     * @param type $company
     * @return type
     */
    private function getLastCheckout($company) {
        $params = array(
            'Checkout' => array(
                'conditions' => array(
                    'Checkout.company_id' => $company ['Company'] ['id'],
                    'Checkout.total_value > ' => '0',
                    'Checkout.payment_state_id' => 4
                ),
                'order' => array(
                    'Checkout.id' => 'DESC'
                )
            ),
            'PaymentState',
            'Offer',
            'User',
            'OffersUser'
        );
        return $this->AccentialApi->urlRequestToGetData('payments', 'all', $params);
    }

    /**
     * Get the number of deliverys for today
     * @param type $company
     * @return int
     */
    private function getNumberDeliveryToday($company) {
        $paramsDelivery = array(
            'Checkout' => array(
                'conditions' => array(
                    'Checkout.company_id' => $company['Company'] ['id'],
                    'Checkout.payment_state_id' => 1
                )
            ),
            'User',
            'Offer'
        );
        $deliveriesToDo = $this->AccentialApi->urlRequestToGetData('payments', 'all', $paramsDelivery);
        $deliveriesToday = array();
        if (!empty($deliveriesToDo)) {
            foreach ($deliveriesToDo as $delivery) {
                $dataDaEntrega = date('d/m/y', strtotime("+1 days", strtotime($delivery['Checkout']['date'])));
                $dataHoje = date('d/m/y');
                if ($dataDaEntrega == $dataHoje) {
                    $deliveriesToday['Today'][] = $delivery;
                }
            }
        }
        if (isset($deliveriesToday['Today'])) {
            return count($deliveriesToday['Today']);
        }
        return 0;
    }

    /**
     * get birthdays of the day
     */
    private function getBirthDays($company) {
        $minhaData = date('m-d');
        $bithSql = "SELECT * FROM users INNER JOIN companies_users ON companies_users.user_id = users.id WHERE companies_users.company_id = " . $company['Company'] ['id'] . " AND users.birthday LIKE '%$minhaData';";
        $birthParams = array('User' => array('query' => $bithSql));
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $birthParams);
    }

    /**
     * Get the secundary user of the system.
     * @param type $company
     * @param type $user
     * @return type
     */
    private function getSecundaruUsers($company, $user = null) {
        //TODO: este metodo pode ser unificado com o metodo do Dashboar <> Schedule <> Users
        $andQuery = "";
        if ($user != null) {
            if (is_array($user)) {
                $andQuery = " AND secondary_users.id = " . $user['id'] . " ";
            } else {
                $andQuery = " AND secondary_users.id = " . $user . " ";
            }
        }
        $secondUserSQL = "select secondary_users.name, secondary_users.id "
                . "from secondary_users "
                . "inner join secondary_users_types on secondary_users.secondary_type_id = secondary_users_types.id "
                . "where secondary_users.excluded = 0 AND company_id  = {$company['Company'] ['id']} $andQuery;";
        $secondUserParam = array(
            'User' => array(
                'query' => $secondUserSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);
    }

    /*
     * Recupera todos os agendamentos do dia
     */

    private function getAllSchecule($company, $dateComparison = "=") {
        $scehduleSQL = "
            SELECT schedules.*, secondary_users.*
            FROM schedules
            INNER JOIN secondary_users
                ON schedules.secondary_user_id = secondary_users.id
            WHERE schedules.companie_id = '" . $company['Company'] ['id'] . "'
            AND schedules.date " . $dateComparison . " '" . date('Y-m-d') . "'";
        $scheduleParam = array(
            'Schedule' => array(
                'query' => $scehduleSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);
    }

    //ftp://ftpuser:ACCftp1000@192.168.1.200/jezzy/uploads/company-119/config/arquivo.txt

    public function readFile() {
        $this->layout = "";
        $handle = @fopen("ftp://ftpuser:ACCftp1000@192.168.1.200/jezzy/uploads/company-119/config/arquivo.txt", "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                echo $buffer;
            }
            if (!feof($handle)) {
                echo "Erro: falha inexperada de fgets()\n";
            }

            fclose($handle);
        }
    }

    public function writeFile() {
        $this->autoRender = false;
        $myfile = fopen("../arquivo.txt", "w") or die("Unable to open file!");
        $txt = $this->request->data['fileText'];
        fwrite($myfile, $txt);
        fclose($myfile);

        return 'false';
    }

    public function checkForSchedulesSolicitation() {
        $this->layout = "";
        $company = $this->Session->read('CompanyLoggedIn');
        $sql = "SELECT * FROM schedules_solicitation WHERE status LIKE 'WAITING_COMPANY_RESPONSE' and company_id = {$company['Company']['id']} and date >= CURDATE();";
        $params = array(
            'Service' => array(
                'query' => $sql
            )
        );
        $schedulesSolicitations = $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);
        print_r(json_encode($schedulesSolicitations));
    }

    public function testeTemplate() {
        $this->layout = "";
    }

    public function approveScheduleSolicitation() {
        $this->layout = "";

        $id = $this->request->data['solicitationId'];
        $sql = "update schedules_solicitation set status = 'SOLICITATION_ACCEPTED' where id = {$id};";
        $params = array(
            'Service' => array(
                'query' => $sql
            )
        );
        $schedulesSolicitations = $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);

        //inserindo scheduler no banco de dados
        $sqlSelectSolicitation = "select * from schedules_solicitation where id = {$id};";
        $paramsSolici = array(
            'Service' => array(
                'query' => $sqlSelectSolicitation
            )
        );
        $solicitation = $this->AccentialApi->urlRequestToGetData('Services', 'query', $paramsSolici);



        //busca servico/valor/horas no banco
        //caso seja por id do service
        //$sqlSelectService = "select * from services where id = {$solicitation[0]['schedules_solicitation']['service_id']};";
        //caso seja por id do classe
        //$sqlSelectService = "select * from services where subclasse_id = {$solicitation[0]['schedules_solicitation']['service_id']};";
        $sqlSelectService = "select * from services where id = {$solicitation[0]['schedules_solicitation']['service_id']};";
        $paramsServic = array(
            'Service' => array(
                'query' => $sqlSelectService
            )
        );
        $service = $this->AccentialApi->urlRequestToGetData('Services', 'query', $paramsServic);

        //busca classes e subclasse no banco
        $sqlSelectClasse = "select * from subclasses inner join classes on classes.id = subclasses.classe_id where
		subclasses.id = {$service[0]['services']['subclasse_id']};";
        $paramsClass = array(
            'General' => array(
                'query' => $sqlSelectClasse
            )
        );
        $classSubclass = unserialize(utf8_decode($this->AccentialApi->urlRequestToGetData('General', 'query', $paramsClass)));


        //INSERT SCHEDULE
        $sqlInsertSchedule = "INSERT INTO SCHEDULES(
		classe_name, 
		subclasse_name,
		date,
		service_id,
		time_begin,
		time_end,
		client_name,
		client_phone,
		status,
		valor,
		user_id,
		companie_id,
		secondary_user_id
		) VALUES(
		'" . $classSubclass[0]['classes']['name'] . "',
		'" . utf8_encode($classSubclass[0]['subclasses']['name']) . "',
		'" . $solicitation[0]['schedules_solicitation']['date'] . "',
		" . $service[0]['services']['id'] . ",
		'" . $solicitation[0]['schedules_solicitation']['time_begin'] . "',
		'" . $solicitation[0]['schedules_solicitation']['time_end'] . "',
		'" . $solicitation[0]['schedules_solicitation']['user_name'] . "',
		'0000000000',
		1,
		" . $service[0]['services']['value'] . ",
		" . $solicitation[0]['schedules_solicitation']['user_id'] . ",
		" . $solicitation[0]['schedules_solicitation']['company_id'] . ",
		" . $solicitation[0]['schedules_solicitation']['secundary_user_id'] . "
		);";

        $insertSched = array(
            'Service' => array(
                'query' => $sqlInsertSchedule
            )
        );
        $sched = $this->AccentialApi->urlRequestToGetData('Services', 'query', $insertSched);

        $this->enviarEmailConfirmaAgendamento("matheusodilon0@gmail.com", $classSubclass);
        //echo $sqlSelectClasse;
		echo "*******************************************************************:";
		echo utf8_encode($classSubclass[0]['subclasses']['name']);
    }

    public function reproveScheduleSolicitation() {
        $this->layout = "";
        $id = $this->request->data['solicitationId'];
        $sql = "update schedules_solicitation set status = 'SOLICITATION_DOES_NOT_ACCEPTED' where id = {$id};";
        $params = array(
            'Service' => array(
                'query' => $sql
            )
        );
        $schedulesSolicitations = $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);
    }

    public function suggestNewSchedule() {
        $this->layout = "";
        $id = $this->request->data['id'];
        $data = $this->request->data['newSuggestedScheduling'];
        //$sql = "update schedules_solicitation set status = 'NEW_SUGGESTED_SCHEDULING' and newSuggestedScheduling = '{$newSuggestedScheduling}' where id = {$id};";
        $sql = "UPDATE `jezzy`.`schedules_solicitation` SET `status`='NEW_SUGGESTED_SCHEDULING', `newSuggestedScheduling`='{$data}' WHERE `id`='{$id}';";
        $params = array(
            'Service' => array(
                'query' => $sql
            )
        );
        $schedulesSolicitations = $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);
    }

    // </editor-fold>

    /**
     * PARA CADASTRO DE NOVO AGENDAMENTO
     */

    /**
     * Gets all the services for the company
     * @param type $company
     * @return type
     */
    private function getCompanyServices($company) {
        $queryInformation = "SELECT services.*,  subclasses.*
                FROM services 
                INNER JOIN subclasses ON subclasses.id = services.subclasse_id
                    AND services.companie_id = " . $company['Company'] ['id'] . "";
        $params = array(
            'Service' => array(
                'query' => $queryInformation
            )
        );
        return $this->AccentialApi->urlRequestToGetData('Services', 'query', $params);
    }

    private function getSecundaryUsers($company, $user = null) {
        //TODO: este metodo pode ser unificado com o metodo do Dashboar <> Schedule <> Users
        $andQuery = "";
        if ($user != null) {
            if (is_array($user)) {
                $andQuery = " AND secondary_users.id = " . $user['id'] . " ";
            } else {
                $andQuery = " AND secondary_users.id = " . $user . " ";
            }
        }
        $secondUserSQL = "select secondary_users.name, secondary_users.id "
                . "from secondary_users "
                . "inner join secondary_users_types on secondary_users.secondary_type_id = secondary_users_types.id "
                . "where secondary_users.excluded = 0 AND company_id  = {$company['Company'] ['id']} $andQuery;";
        $secondUserParam = array(
            'User' => array(
                'query' => $secondUserSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);
    }

    public function ajaxSendBirthdayEmail() {
        $this->autoRender = false;

        $id = $this->request->data['id'];
        //$email = $this->request->data['userEmail'];
        $email = "matheusodilon0@gmail.com";
        $emailBody = $this->request->data['bodyEmail'];
        $subject = $this->request->data['subject'];

        $this->sendEmail($email, $emailBody, $subject);
        echo "EMAIL ENVIADO";
    }

    public function sendEmail($email, $emailBody, $subject) {
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
        $mail->Subject = $subject; // Assunto da mensagem
        $mail->Body = $emailBody;
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

// Funcoes para o wizard de first user
    public function companyOpenCloseHour() {
        $this->autoRender = false;

        $company = $this->Session->read('CompanyLoggedIn');
        $id = $company['Company']['id'];
        $open = $this->request->data['openHour'];
        $close = $this->request->data['closeHour'];
        $workDays = $this->request->data['workDays'];

        $sql = "UPDATE companies SET open_hour = '{$open}', close_hour = '{$close}', work_days = '{$workDays}' WHERE id = {$id};";
        $secondUserParam = array(
            'User' => array(
                'query' => $sql
            )
        );
        $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);

        $sqlFirstLogin = "UPDATE companies SET first_login = 0 WHERE id = {$id};";
        $FirstLoginParam = array(
            'User' => array(
                'query' => $sqlFirstLogin
            )
        );
        $this->AccentialApi->urlRequestToGetData('users', 'query', $FirstLoginParam);
    }

    public function companyPassword() {
        $this->autoRender = false;

        $company = $this->Session->read('CompanyLoggedIn');
        $id = $company['Company']['id'];
        $password = $this->request->data['password'];

        $sql = "UPDATE companies SET password = '" . md5($password) . "' WHERE id = {$id};";
        $secondUserParam = array(
            'User' => array(
                'query' => $sql
            )
        );
        return $this->AccentialApi->urlRequestToGetData('users', 'query', $secondUserParam);
    }

    public function createMoIPAccount() {
        $this->autoRender = false;
        $company = $this->Session->read('CompanyLoggedIn');

        $birthday = $this->request->data['birthday'];
		$data = explode("/", $birthday);
		$birth = $data[2].'-'.$data[1].'-'.$data[0];
        $areaCode = $this->request->data['areaCode'];
        $phone = str_replace("-", "", str_replace(" ", "", str_replace(")", "", str_replace("(", "", $company['Company']['responsible_phone']))));
   
        $newPhone = substr($phone, 2, strlen($phone));
        $name = explode(" ", $company["Company"]["responsible_name"]);
        $cpf = str_replace("-", "", str_replace(".", "", $company['Company']['responsible_cpf']));

        $json = '{
  "email": {
    "address": "' . $company["Company"]['email'] . '"
  },
  "person": {
    "name": "' . $name[0] . '",
    "lastName": "' . $name[1] . '",
    "taxDocument": {
      "type": "CPF",
      "number": "' . $cpf . '"
    },
    "birthDate": "' . $birth . '",
    "phone": {
      "countryCode": "55",
      "areaCode": "' . $areaCode . '",
      "number": "' . $newPhone . '"
    },
    "address": {
      "street": "' . $company["Company"]["address"] . '",
      "streetNumber": "' . $company["Company"]["number"] . '",
      "district": "' . $company["Company"]["district"] . '",
      "zipCode": "' . $company["Company"]["zip_code"] . '",
      "city": "' . $company["Company"]["city"] . '",
      "state": "' . $company["Company"]["state"] . '",
      "country": "BRA"
    }
  },
  "type": "MERCHANT"
}';

//$json = '{
//  "email": {
//    "address": "rhayssanery@accential.com.br"
//  },
//  "person": {
//    "name": "Rhayssa",
//    "lastName": "Nery",
//    "taxDocument": {
//      "type": "CPF",
//      "number": "87968182123"
//    },
//    "birthDate": "1990-08-11",
//    "phone": {
//      "countryCode": "55",
//      "areaCode": "11",
//      "number": "958767620"
//    },
//    "address": {
//      "street": "Rua Cubatao",
//      "streetNumber": "411",
//      "district": "Vila Mariana",
//      "zipCode": "04113-040",
//      "city": "Sao Paulo",
//      "state": "SP",
//      "country": "BRA"
//    }
//  },
//  "type": "MERCHANT"
//}';
        $header = array();
        $header[] = 'Content-type: application/json';
        $header [] = "Authorization: OAuth fe4thhxju5ll725ftnquf1o716y6kpu";
        $auth = 'fe4thhxju5ll725ftnquf1o716y6kpu';
        // URL do SandBox - Nosso ambiente de testes
        // $url = "https://desenvolvedor.moip.com.br/sandbox/ws/alpha/PreCadastramento";
        $url = "https://sandbox.moip.com.br/v2/accounts";

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
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // efetua a requisi��o e coloca a resposta do servidor do MoIP em $ret
        $ret = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $dadosMoip = json_decode($ret, true);

		if(!empty($dadosMoip['errors'])){
		$sqlMoIPAccount = "UPDATE companies SET moip_id = '{$dadosMoip['additionalInfo']['account']['id']}', moip_account = '{$dadosMoip['additionalInfo']['account']['login']}' WHERE id = {$company['Company']['id']};";
		}else{
       $sqlMoIPAccount = "UPDATE companies SET moip_id = '{$dadosMoip['id']}', moip_account = '{$dadosMoip['login']}', link_setpassword_moip = '{$dadosMoip['_links']['setPassword']['href']}' WHERE id = {$company['Company']['id']};";
	}
		$MoipAccountParam = array(
            'User' => array(
                'query' => $sqlMoIPAccount
            )
        );
        $this->AccentialApi->urlRequestToGetData('users', 'query', $MoipAccountParam);

       // var_dump($ret);
        echo "**********";
        echo print_r($dadosMoip);
        echo "**********";
        print_r($sqlMoIPAccount);
    }

    public function enviarEmailConfirmaAgendamento($email, $service) {
        $mail = new PHPMailer(true);

        $company = $this->Session->read('CompanyLoggedIn');

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

        $emailBody = '  <table border="0" cellpadding="0" cellspacing="0"  style="background: #f2f2f2;">
            <tr>
                <td colspan="4"><img src="files/agendamento-confirmado/01.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-confirmado/01-2.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-confirmado/02.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="color: #9b9b9b;  font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                <td style="width: 175px; text-align: right;">Corte de Cabelo</td>
                <td style="text-align: center;">20/04/2016</td>
                <td style="width: 170px;">12:00</td>
                <td style="width: 150px;">Januaria</td>
            </tr>
            <tr>
                <td colspan="4"><br/><br/><img src="files/agendamento-confirmado/03.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
            <tr style="color: #9b9b9b;  font-family: Helvetica, Arial, sans-serif; font-size: 14px;">
                <td style="width: 150px; text-align: center;" colspan="4">
                    <br/>
                    <span><b>Salão ' . $company['Company']['fancy_name'] . '</b></span><br/>
                    <span>
                        ' . $company['Company']['address'] . ', ' . $company['Company']['number'] . ' - ' . $company['Company']['district'] . ' - CEP ' . $company['Company']['zip_code'] . ' - ' . $company['Company']['city'] . ' - ' . $company['Company']['state'] . '<br/>
                        Telefone: ' . $company['Company']['phone'] . ' - Horário de Funcionamento: ' . $company['Company']['work_days'] . ' das ' . date('g:i a', strtotime($company['Company']['open_hour'])) . ' às ' . date('g:i a', strtotime($company['Company']['close_hour'])) . '
                    </span>
                    <br/>
                </td>
            </tr>
            <tr>
                <td colspan="4"><img src="files/agendamento-confirmado/04.jpg"  style="vertical-align: bottom;"/></td>
            </tr>
               <tr>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/07.jpg" width="151" style="vertical-align: bottom; text-align: center;"/></td>
                <td  colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/08.jpg" width="151" style="vertical-align: bottom; text-align: center;"/></td>
                <td colspan="1"> <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/09.jpg" width="151" style="vertical-align: bottom; text-align: center;"/></td>
                <td colspan="1"><img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/10.jpg" width="151" style="vertical-align: bottom; text-align: center;"/></td>
            </tr>
             <tr>
                <td colspan="4" style="text-align: center;">
                    <img src="http://www.schabla.com.br/jezzy_images/transacao-finalizada/12.jpg"  style="vertical-align: bottom;"/>
                </td>
            </tr>
        </table>';

        $mail->Subject = "Agendamento Confirmado!"; // Assunto da mensagem
        $mail->Body = utf8_decode($emailBody);
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
	
	public function showTest(){
	$this->layout = "";
	 $query = "
            select * from subclasses inner join classes on classes.id = subclasses.classe_id where
		subclasses.name LIKE '%Maquiagem Básica%';";
        $params = array(
            'General' => array(
                'query' => $query
            )
        );
        $servicesArr = unserialize(utf8_decode($this->AccentialApi->urlRequestToGetData('General', 'query', $params)));
		print_r($servicesArr);
	}

}