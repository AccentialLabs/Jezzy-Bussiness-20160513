<?php

if(!empty($_GET)){
    $disabled = 'style="display:block;"';
}else {
    $disabled = "";
}

echo $this->Html->css('View/Dashboard.index', array('inline' => false)); ?>
<?php echo $this->Html->script('util', array('inline' => false)); ?>
<?php echo $this->Html->script('View/Dashboard.index', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery.mask'); ?>
<?php echo $this->Html->script('jquery.mask.min'); ?>
<!-- Lib reponsible for item of detais -->
<?php $this->Html->css('Library/Summernote/font-awesome/font-awesome.min', array('inline' => false)); ?>
<?php $this->Html->css('Library/Summernote/summernote', array('inline' => false)); ?>
<?php $this->Html->script('Library/Summernote/summernote.min', array('inline' => false)); ?>
<?php $this->Html->script('Library/Summernote/plugin/summernote-ext-hello', array('inline' => false)); ?>
<?php $this->Html->script('Library/Summernote/plugin/summernote-ext-hint', array('inline' => false)); ?>
<?php $this->Html->script('Library/Summernote/plugin/summernote-ext-video', array('inline' => false)); ?>

<div >
    <h1 class="page-header letterSize"><span>Dashboard</span></h1>

</div>
<div class="alert alert-success" role="alert" id='moipAccountSuccess'>Sua conta foi criada com sucesso! Seja bem-vindo ao Jezzy!</div>

<?php 
	$var[0] = array(
			"Cliente" => array(
				"name"=> "Matheus Odilon",
				
				"id"=> "279"
			),
			"Servico" => array(
				"name"=> "Banho de Brilho",
				"id"=> "7"
			),
			"schedule"=> "12:00",
			"date"=> "26/01/2016",
			"status"=> "WAITING_COMPANY_RESPONSE"
		);
		
		$var[1] = array(
			"Cliente" => array(
				"name"=> "Maria Marta",
				"id"=> "270"
			),
			"Servico" => array(
				"name"=> "Botox",
				"id"=> "9"
			),
			"schedule"=> "15:00",
			"date"=> "26/01/2016",
			"status"=> "WAITING_COMPANY_RESPONSE"
		);
		
		$variavel = json_encode($var);
		
		//echo $variavel;
?>

<div class="row ">
    <div class="col-md-6">
        <div class="col-md-12">
            <div class="row saleFinalize">
                Compras finalizadas

                <a href="../portal/saleReport" >
                    <span class='glyphicon glyphicon-plus plus-btn pull-right' id="plusComprasFinalizadas"></span>
                </a>

            </div>
            <div class="row">
                <table class="table table-bordered" id="comprasFinalizadas">
                    <tbody>
                        <tr>
                            <?php
                            if (isset($checkouts1) && is_array($checkouts1)) {
                                switch ($checkouts1['Checkout']['payment_method_id']) {
                                    case 3:
                                    case 5:
                                    case 7:
                                        $modoPagamento = 'Cartão de Crédito';
                                        break;
                                    default:
                                        $modoPagamento = 'Boleto';
                                        break;
                                }
                                $offerTitle = $checkouts1['Offer']['title'];
                                $offerValue = $checkouts1['Offer']['value'];
                                $firstName = split(" ", $checkouts1['User']['name'])[0];
                                echo '
                                    <td>'.'<a href="#">'.$offerTitle.'</a>'.'</td>
                                    <td>Pagamento<br>R$ '.$offerValue.'</td>
                                    <td>Pagamento<br>'.$modoPagamento.'</td>
                                    <td>Cliente<br>'.$firstName.'</td>';
                            }    
                            ?>
                        </tr>
                        <tr>
                            <?php
                            if (isset($checkouts2) && is_array($checkouts2)) {
                                switch ($checkouts2['Checkout']['payment_method_id']) {
                                    case 3:
                                    case 5:
                                    case 7:
                                        $modoPagamento = 'Cartão de Crédito';
                                        break;
                                    default:
                                        $modoPagamento = 'Boleto';
                                        break;
                                }
                                $offerTitle = $checkouts2['Offer']['title'];
                                $offerValue = $checkouts2['Offer']['value'];
                                $firstName = split(" ", $checkouts2['User']['name'])[0];
                                echo '
                                    <td>'.'<a href="#">'.$offerTitle.'</a>'.'</td>
                                    <td>Pagamento<br>R$ '.$offerValue.'</td>
                                    <td>Pagamento<br>'.$modoPagamento.'</td>
                                    <td>Cliente<br>'.$firstName.'</td>';
                            }    
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="col-md-4">
            <div class="row heightSquare leftSquare darkBlue">
                <span class="verticalAlign box-dash">Crie uma nova<br/> oferta</span>
            </div>
            <div class="row heightFirstSpace">
            </div>
            <div class="row heightSquare leftSquare darkBlue delivery">
                <a href="<?php echo $this->Html->url("/Product/productManipulation");?>">
                    <span class="glyphicon glyphicon-tag iconWhite verticalAlign"></span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row heightSquare leftSquare darkBlue">
                <span class="verticalAlign box-dash">Crie<br/>um novo<br/><span class="box-dash-agendamento">agendamento</span></span>
            </div>
            <div class="row heightFirstSpace">
            </div>
            <div class="row heightSquare leftSquare darkBlue delivery">
                <a href="#" id="showmodalnewSchedule"  data-toggle="modal" data-target="#myModalNewSchedule">
                    <span class="glyphicon glyphicon-calendar iconWhite verticalAlign"></span>
                </a>
            </div>
        </div>
        <!--
<div class="col-md-4">
    <div class="row heightSquare rightSquare darkBlue">
        <span class="verticalAlign box-dash">Entrega</br>para</br>hoje</span>
    </div>
    <div class="row heightFirstSpace">
    </div>
    <div class="row heightSquare rightSquare darkBlue delivery">
                <?php echo $deliveryToday; ?>
    </div>
</div>-->
    </div>
    <div class="col-md-3">
        <div class="col-md-12 lightblue heightFirstRow">
            <div class="row">
                <h4 class="birthDayColor">Aniversáriantes</br>do dia</h4>
            </div>
            <?php
            if (count($birthdays) > 0) {
                    foreach ($birthdays as $birthday) {
                        echo '<div class="row users-birthday" id="'.$birthday['users']['id'].'" useremail="'.$birthday['users']['email'].'"><a href="#" >' . $birthday['users']['name'] . '</a></div>';
                    }
            }
            ?>
        </div>
    </div>
</div>
<div class="hide">
    <div class="row marginTop15">
        <div class="col-md-12">
            <div class="btn-group">
                <input name="dateSchedule" type="date" class="form-control birthDayColor rowCenter" id="dateSchedule"/>
            </div>
            Funcionarios: 

        <?php
        if (isset($secundary_users)) {
            foreach ($secundary_users as $secundary_user) {
                echo '
                    <div class="btn-group">
                        <button name="employee" type="button" class="btn btn-primary" id="' . $secundary_user['secondary_users']['id'] . '" title="' . $secundary_user['secondary_users']['name'] . '">' . split(" ", $secundary_user['secondary_users']['name'])[0] . '</button>
                    </div>';
            }
        }
        ?>
            <div class="btn-group">
                <button name="limpar" type="button" class="btn-sm btn-default " id="limpar">Limpar</button>
            </div>
        </div>
    </div>
    <div class="row" id="columnsSchecule">
        <div class="col-md-3 marginTop15" id="colSchedule_1">

        </div>
        <div class="col-md-3 marginTop15" id="colSchedule_2">

        </div>
        <div class="col-md-3 marginTop15 " id="colSchedule_3">

        </div>
        <div class="col-md-3 marginTop15" id="colSchedule_4">

        </div>
    </div>
</div>
<!-- RELATORIO DE AGENDAMENTOS -->
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#sectionA">Agendamentos Hoje</a></li>
    <li><a data-toggle="tab" href="#sectionB">Agendamentos Passados</a></li>
    <li><a data-toggle="tab" href="#sectionC">Agendamentos Futuros</a></li>
</ul>
<div class="tab-content">
    <div id="sectionA" class="tab-pane fade in active">
        <table class="table table-bordered table-condensed small" id="schedulesToday">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servico</th>
                    <th>Data</th>
                    <th>Horario</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Funcionario</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    if (is_array($schedules)) {
                        foreach ($schedules as $schedule) {
                            if ($schedule['schedules']['status'] == 0) {
                                $scheduleStatus = "REALIZADO";
                            } else {
                                $scheduleStatus = "AGENDADO";
                            }

                            echo '
                                <tr>
                                    <td><a href="#">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td>' . $schedule['schedules']['subclasse_name'] . '</td>
                                    <td>' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</td>
                                    <td>' . substr($schedule['schedules']['time_begin'], 0, 5) . '</td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . split(" ", $schedule['secondary_users']['name'])[0] . '</td>
                                </tr>';
                        }
                    }
                    ?>
            </tbody>
        </table>
    </div>
    <div id="sectionB" class="tab-pane fade">
        <table class="table table-bordered table-condensed small" id="schedulesPass">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servico</th>
                    <th>Data</th>
                    <th>Horario</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Funcionario</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    if (is_array($allSchedulesPrevious)) {
                        foreach ($allSchedulesPrevious as $schedule) {
                            if ($schedule['schedules']['status'] == 0) {
                                $scheduleStatus = "AGENDADO";
                            } else {
                                $scheduleStatus = "REALIZADO";
                            }

                            echo '
                                <tr>
                                    <td><a href="#">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td>' . $schedule['schedules']['subclasse_name'] . '</td>
                                    <td>' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</td>
                                    <td>' . substr($schedule['schedules']['time_begin'], 0, 5) . '</td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . split(" ", $schedule['secondary_users']['name'])[0] . '</td>
                                </tr>';
                        }
                    }
                    ?>
            </tbody>
        </table>
    </div>
    <div id="sectionC" class="tab-pane fade">
        <table class="table table-bordered table-condensed small" id="futureSchedules">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servico</th>
                    <th>Data</th>
                    <th>Horario</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Funcionario</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    if (is_array($allSchedulesNext)) {
                        foreach ($allSchedulesNext as $schedule) {
                            if ($schedule['schedules']['status'] == 0) {
                                $scheduleStatus = "AGENDADO";
                            } else {
                                $scheduleStatus = "REALIZADO";
                            }

                            echo '
                                <tr>
                                    <td><a href="#">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td>' . $schedule['schedules']['subclasse_name'] . '</td>
                                    <td>' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</td>
                                    <td>' . substr($schedule['schedules']['time_begin'], 0, 5) . '</td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . split(" ", $schedule['secondary_users']['name'])[0] . '</td>
                                </tr>';
                        }
                    }
                    ?>
            </tbody>
        </table>
    </div>
</div>


<!-- POP UP REQUISIÇÕES DE AGENDAMENTO -->
<div id="myModalSchedulesRequisitions" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-md" >
        <div class="modal-content" id="modelContent">
            <div class="modal-body">
                <form action="<?php echo $this->Html->url("addSubclass"); ?>" method="post">
                    <div class="form-horizontal">
                        <legend>Solicitações de Agendamento</legend>
                        <div class="form-group notification-body" id="notification-body">

                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <div class=" buttonLocation">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-fecha-modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="recebe-cont"> </div>
<!-- <button type="button" class="btn btn-default" id="readFileBtn"> LER ARQUIVO</button> -->

<!-- DETALHE DE USUÁRIO -->
<div id="myModalUserDetails" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-md" >
        <div class="modal-content" id="modelContent">
            <div class="modal-body">
                <form action="<?php echo $this->Html->url("addSubclass"); ?>" method="post">
                    <legend>Detalhes do Usuário</legend>
                    <div class="form-horizontal" id="recebe">

                        <div class="form-group notification-body" id="notification-body">
                            <div class="col-md-4">
                                <img src="http://coolspotters.com/files/photos/95058/jorge-garcia-profile.jpg" class="user-details-photo"/>
                            </div>
                            <div class="col-md-8">
                                <h3>Jorge Michael</h3>
                                <hr />
                                <div>
                                    <span class="glyphicon glyphicon-envelope pull-left"></span>  <div class="description-info-user">jorge@michael.com</div>
                                    <span class="glyphicon glyphicon-user pull-left"></span> <div class="description-info-user">Masculino</div>
                                    <span class="glyphicon glyphicon-calendar pull-left"></span> <div class="description-info-user">11/08/1994</div>
                                    <span class="glyphicon glyphicon-home pull-left"></span><div class="description-info-user">De Ferraz de Vasconcelos - São Paulo, Rua Hermenegildo Barreto, 120 - 08540-500</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="info-user-galery">
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="pull-left quad">
                                    <a href="#" class="thumbnail">
                                        <img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
                                    </a>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseOne">Compras</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">

                                            <!-- checkout box-->
                                            <div class="col-md-4 checkouts-box">
                                                <div class="col-md-12 img-content" >
                                                    <img src="http://bimg2.mlstatic.com/camiseta-adulto-e-infantil-zelda-triforce_MLB-F-219462707_2113.jpg" class="checkouts-box-img" />
                                                </div>

                                                <div class="col-md-12 checkouts-content">
                                                    <div class="checkout-label">Camiseta qualquer por no brasil</div>
                                                    <hr class="checkouts-divisor"/>

                                                    <div class="checkouts-descriptions col-md-12">												
                                                        <div>
                                                            <div class="col-md-7 checkouts-collums left-collum">
                                                                Quantidade:
                                                            </div>
                                                            <div class="col-md-5 checkouts-collums">
                                                                3
                                                            </div>


                                                            <div class="col-md-7 checkouts-collums left-collum">
                                                                Pagamento:
                                                            </div>
                                                            <div class="col-md-5 checkouts-collums">
                                                                DÉBITO
                                                            </div>


                                                            <div class="col-md-7 checkouts-collums left-collum">
                                                                Data:
                                                            </div>
                                                            <div class="col-md-5 checkouts-collums">
                                                                21/12/2015
                                                            </div>

                                                            <div class="col-md-7 checkouts-collums left-collum">
                                                                Status:
                                                            </div>
                                                            <div class="col-md-5 checkouts-collums">
                                                                RECEBIDO
                                                            </div>

                                                            <div class="col-md-7 checkouts-collums left-collum">
                                                                TOTAL:
                                                            </div>
                                                            <div class="col-md-5 checkouts-collums">
                                                                R$ 1999,00
                                                            </div>
                                                        </div>
                                                    </div>										
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <div class=" buttonLocation">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- NEW SCHEDULE -->

<div id="myModalNewSchedule" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content" id="modelContent">
            <div class="modal-body">
                <div class="form-horizontal">
                    <legend>Agendamento</legend>


                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="dateSchecule">Data</label>
                            <input type="date" class="form-control" id="dateSchecule" placeholder="Data">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="initialTimeSchecule">Horário</label>
                            <input type="time" class="form-control" id="initialTimeSchecule" placeholder="Hora inicial">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="secondUserSchedule">Profissional</label>
                            <select class="form-control" id="secondUserSchedule">
                                <option value="0" selected>Profissional</option>
								<?php 
									if(isset($secondaryUsers)){
										foreach($secondaryUsers as $secondUser){
											echo "<option value='{$secondUser['secondary_users']['id']}'>{$secondUser['secondary_users']['name']}</option>";
										}
									}
								?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="serviceSchedule">Serviço a ser prestado</label>
                            <select class="form-control" id="serviceSchedule">
                                <option value="0" selected>Serviço</option>
                                <?php
                                if (isset($services)) {
                                    foreach ($services as $sevice) {
                                        echo '<option value="' . $sevice['services']['id'] . '">' . $sevice['subclasses']['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="valueSchedule">Valor do Serviço</label>

                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <input id="valueSchedule" type="number" class="form-control"  placeholder="Valor"  aria-label="Amount (to the nearest dollar)">

                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="clientSchedule">Nome do Cliente</label>
                            <input id="clientSchedule" type="text" class="form-control" placeholder="Nome do cliente">
                            <div class="content-names" id="content-names">

                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="#" class="see-user-profile" id="user-profile-link" onclick="showUserDetail()">ver perfil do cliente</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="emailSchedule">Email do Cliente</label>
                            <input id="emailSchedule" type="text" class="form-control" placeholder="Email do cliente">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="phoneSchedule">Telefone do Cliente</label>
                            <input id="phoneSchedule" maxlength="15"  type="tel" class="form-control numbersOnly"  placeholder="Telefone do cliente">
                        </div>
                        <br/><br/>
                        <div class="col-sm-12">
                            <input id="newUserSchedule"  type="checkbox" class="checkbox pull-left" checked="checked">
                            <span class="pull-left"> Novo Cliente</span>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 buttonLocation">
                                <input type="hidden" name="userId" id="userId" value="" />
                                <button type="button" class="btn btn-success" id="btnNewSchedule">Agendar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="myModalBirthday" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-title-personal">Menu do Aniversariante</h4>
            </div>
            <div class="modal-body">
                <p>
                    <label for="birthdaySendEmail">Enviar Email de Parabéns</label>
                    <select class="form-control" id="birthdaySendEmail">
                        <option>Usar Email de Aniversário padrão Jezzy</option>
                        <option id="birthdayNewLayoutEmail" value="birthdayNewLayoutEmail">Criar Layout de Email</option>
                        <option id="birthdayNewUniqueEmail"value="birthdayNewUniqueEmail">Criar Email único</option>
                    </select><br/>
                    ou<br/><br/>
                    <button type="button" class="btn btn-default" id="birthdayOfferToUser">Criar oferta para esse usuário</button>
                    <input type="hidden" id="UserBirthdaySelected" />
                    <input type="hidden" id="UserBirthdaySelectedEmail" />
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="myModalBirthdayEmailBody" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-title-personal">Email de Aniversário</h4>
            </div>
            <div class="modal-body">
                <p>
                    <label for="birthdayEmailSubject">Assunto</label>
                    <input type="text" class="form-control" id="birthdayEmailSubject" /><br/>

                    <label for="summernote">Corpo do Email</label>
                    <textarea class="form-control" name="summernote" id="summernote" cols="30" rows="10"></textarea>

                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="sendEmail">Enviar <span class="glyphicon glyphicon-share-alt"></span></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div class="list-group col-md-4 schedules-box">
    <a href="#" class="list-group-item disabled">
        Agendamentos para hoje
    </a>
  <?php 
	if(!empty($schedules)){
  foreach($schedules as $sche){?>
    <a href="#" class="list-group-item"><strong><?php echo $sche['schedules']['client_name']?></strong>/ <?php echo $sche['schedules']['subclasse_name']; ?> de <strong><?php echo substr($sche['schedules']['time_begin'], 0, 5); ?></strong> até <strong><?php echo substr($sche['schedules']['time_end'], 0, 5); ?></strong>
        <br /> <strong>Profissional: </strong><?php echo $sche['secondary_users']['name'] ?></a>
  <?php }}else{
	echo "<p> Sem agedamentos para hoje, até o momento!</p>";
  }?>

</div>


<!-- Modal -->
<div id="myModalWizard" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="CloseMyModalWizard">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Bem Vindo ao JEZZY Business!<br/>Antes de continuar o Jezzy precisa de mais algumas informações sobre você...</p>
                <br/>
                <div id="form-1" class="">
                    <h3>Altere sua senha</h3>
                    <input type="password" id="password" placeholder="Senha" class="form-control" /><br/>
                    <input type="password" id="confirmPassword" placeholder="Confirma Senhas" class="form-control"/><br/>			
                </div>
                <div id="form-2" style="display: none;">
                    <h3>Seu horário de trabalho para que possamos informar a seus clientes:</h3>
                    <input type="hour" id="openHour" placeholder="Abertura (07:00)" class="form-control" maxlength="5" data-mask="00:00" /><br/>
                    <input type="hour" id="closeHour" placeholder="Fechamento (20:00)" class="form-control" maxlength="5" data-mask="00:00" /><br/>	<br/>

                    <h3>Dias de funcionamento do seu Salão:</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Dom</th>
                                <th>Seg</th>
                                <th>Ter</th>
                                <th>Qua</th>
                                <th>Qui</th>
                                <th>Sex</th>
                                <th>Sab</th>
                            </tr>
                            <tr class="text-center">
                                <td>
                                    <input type="checkbox"  id="dom" val="dom" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox" id="seg" val="seg" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox"  id="ter" val="ter" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox" id="qua" val="qua" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox" id="qui" val="qui" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox" id="sex" val="sex" name="workDays"/>
                                </td>
                                <td>
                                    <input type="checkbox" id="sab" val="sab" name="workDays"/>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="wizard-next">proximo</button>
            </div>
        </div>

    </div>
</div>

<div class="loading">
<?php	echo $this->Html->image('loading.gif', array('alt' => 'CakePHP', 'class' => 'loading-wizard')); ?>
</div>
<input type="hidden" id="first_login" name="first_login" value='<?php echo $company['Company']['first_login']; ?>'/>


<div  id="createAccountMoipBody" <?php echo $disabled; ?>>
    <div class="col-md-12 text-right close-button" ><span id="moipBodyXis">X</span></div><br/>

    <div class='col-md-12'>
        <?php echo $this->Html->image('moip.png', array('class' => 'moipLogo')); ?>
    </div>
    <div class='col-md-12'>
        <span class='moip-description'>O MoIP é o responsável por fazer o processamento do pagamento de todas as vendas feitas dentro do Jezzy, para que você possa vender seus produtos e serviços continue o cadastro e tenha uma conta MoIP</span>
    </div>

    <div class="col-md-12">
        <h3>Responsavel pela conta:</h3>
        <br/>
        <div class="form-group">
            <input type="text" class="form-control col-md-5" placeholder="Nome" id='moipName' value='<?php echo $company['Company']['responsible_name'];?>' disabled="disabled"/>
            <input type="text" class="form-control col-md-5" placeholder="Email" id='moipEmail' value='<?php echo $company['Company']['responsible_email'];?>' disabled="disabled"/>
        </div><br/><br/>

        <div class="form-group">
            <input type="text" class="form-control col-md-5" placeholder="Cpf" id="moipCpf" value='<?php echo $company['Company']['responsible_cpf'];?>' disabled="disabled"/>
            <input type="text" class="form-control col-md-5" placeholder="Data de Nascimento" id="moipDate" value='<?php echo date('d/m/Y', strtotime($company['Company']['responsible_birthday']));?>' onfocus="(this.type='date')" disabled="disabled"/>
        </div><br/><br/>

        <div class="form-group">
            <input type="text" class="form-control col-md-5" placeholder="Código do País" id='moipCountryCode' value='55' disabled="disabled"/>
            <input type="text" class="form-control col-md-5" placeholder="Código de Área" id='moipAreaCode' value='<?php echo substr($company['Company']['responsible_phone'], 1, 2);?>' disabled="disabled"/>
            <input type="text" class="form-control col-md-5" placeholder="Telefone" id='moipPhone' value='<?php echo $company['Company']['responsible_phone'];?>' disabled="disabled"/>
        </div><br/><br/>
        <h3>Endereço:</h3>
        <div class="form-group">
            <input type="text" class="col-md-8 form-control " placeholder="Rua" id='moipStreet' value='<?php echo $company['Company']['address'];?>' disabled="disabled"/>
            <input type="text" class="col-md-3 form-control " placeholder="Número" id='moipNumber' value='<?php echo $company['Company']['number'];?>' disabled="disabled"/>
        </div>
        <br/><br/>
        <div class="form-group">
            <input type="text" class="form-control col-md-5" placeholder="CEP" id='moipZipCode' value='<?php echo $company['Company']['zip_code'];?>' disabled="disabled"/>
            <input type="text" class="form-control col-md-5" placeholder="Bairro" id='moipDistrict' value='<?php echo $company['Company']['district'];?>' disabled="disabled"/>
        </div>
        <br/><br/>
        <div class="form-group">
            <input type="text" class="form-control  col-md-5" placeholder="Cidade" id='moipCity' value='<?php echo $company['Company']['city'];?>' disabled="disabled"/>
            <input type="text" class="form-control  col-md-5" placeholder="Estado" id='moipState' value='<?php echo $company['Company']['state'];?>' disabled="disabled"/>
            <input type="text" class="form-control  col-md-5" placeholder="País" value="BRA" disabled="disabled" id='moipCountry'/>
        </div>
        <br/><br/>
        <div class='form-group text-center'>
            <div class="checkbox">
                <label><input type="checkbox" value="agree" id='moipAgree'>Concordo em criar minha conta MoIP</label>
            </div>
        </div>
        <div class='col-md-12 text-center'>
            <span class='moip-description'><a href="#">Termos e Condições</a></span>
        </div>
        <div class="form-group col-md-12">
            <button type='button' class='btn btn-default pull-right' id="cancelCreateMoip">Cancelar</button>
            <button type='button' class='btn btn-default pull-right' id='createMoip'>Cadastrar</button>
        </div>
        <br/><br/>
    </div>
</div>




