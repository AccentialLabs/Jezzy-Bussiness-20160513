<div style=" top: 0;
     left: 0;
     position: absolute;
     width: 600px;
     background: #f3f3f3;
     height: 138%;">
    <div id="barra-direita" style="position: absolute;
         top: 0;
         left: 0;
         width: 60px;
         height: 100%;
         background: #888;">
    </div>

    <div>
        <img src="https://uploaddeimagens.com.br/images/000/429/580/full/Adventa-1.png?1421345127" id="adv-logo-sup" style="width: 150px;
             margin-left: 60px;
             margin-top: 20px;"/>
    </div>

    <div id="caixa-corpo" style="box-shadow: 0px -3px 5px #ccc;
         margin-left: 61px;
         height: 50px;
         background: #F8F8FF;
         margin-top: 10px;">
        <img src="http://uploaddeimagens.com.br/images/000/429/581/full/Icon.png?1421345160" id="icon-email" style="width: 45px; margin-left: 10px; margin-top: 10px; float: left;"/>   
        <div id="pedido" style="float: left; margin-left: 10px; margin-top: 10px; font-family: Helvetica;">
            <strong style="color: #555;">PEDIDO <?php echo $data['checkId'];?></strong>
            <br />
            <span style="color: #999999; font-size: 11px;">Transacao Finalizada!</span>
        </div>

    </div>

    <div style="position: absolute; margin-top: 20px; margin-left: 75px; ">
        <span style="color: #3399cc; font-family: Helvetica; font-size: 14px;"><i>Caro Sr(a). <?php echo $data['userName']; ?>,</i></span>
        <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
            <strong>Seu pedido foi recebido com sucesso!</strong><br/>
            Confira as informacoes abaixo que constam no seu pedido <strong>nº <?php echo $data['checkId']; ?></strong><br />
            realizado em <strong><?php echo $data['checkDate']?>;</strong>
        </div>

        <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
            <strong>Detalhes do pedido:</strong><br />
            <span>Forma de Pagamento: <?php echo $data['checkPaymentMethod'];?></span><br />
            <span>Parcelas: <?php echo $data['checkInstallment'];?> vez(es) de R$<?php echo $data['checkInstallmentValue'];?></span><br/>
            <span>Total Frete: R$<?php echo $data['shippingValue'];?></span><br/>
            <span>Total Desconto: R$<?php echo $data['discountValue'];?></span><br/>
            <span>Total Pedido: R$<?php echo $data['checkTotalValue'];?></span><br/>
        </div>


        <div id="linha-esquerda" style="margin-top: 20px; float: left; width: 205px; margin-left: 20px;"><hr style=" border: 0;
                                                                                                             height: 1px;
                                                                                                             opacity: 0.5;
                                                                                                             background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>
        <div id="texto-meio" style=" float: left; width: 80px; text-align: center; color: #f37165; font-family: Helvetica; margin-top: 18px;"><i>Produtos</i></div>
        <div id="linha-direita" style=" float: left; width: 205px; margin-top: 20px;"><hr style="border: 0;
                                                                                          height: 1px;
                                                                                          opacity: 0.5;
                                                                                          background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                          background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                          background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                          background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>

        <div style="margin-top: 60px;">
            <table style="width: 500px; font-size: 12px; color: #999; font-family: Helvetica;">
                <tr style="width: 100%;">
                    <td style="width: 25%;"><?php echo $data['offerTitle'];?></td>
                    <td style="width: 49%; text-align: center;"><hr style="border: 0;
                                                                    height: 1px;
                                                                    opacity: 0.5;
                                                                    background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                    background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                    background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                    background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>
                    <td style="width: 25%; text-align: right;">R$<?php echo $data['offerValue'];?></td>

                </tr>
            </table>
        </div>  
        <br/>
        <br/>


        <div id="linha-esquerda" style="margin-top: 0px; float: left; width: 205px; margin-left: 20px;"><hr style=" border: 0;
                                                                                                            height: 1px;
                                                                                                            opacity: 0.5;
                                                                                                            background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                            background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                            background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                            background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>
        <div id="texto-meio" style="float: left; width: 80px; text-align: center; color: #f37165; font-family: Helvetica; margin-top: -2px;"><i>Entrega</i></div>
        <div id="linha-direita" style="float: left; width: 205px; margin-top: 0px;"><hr style="border: 0;
                                                                                        height: 1px;
                                                                                        opacity: 0.5;
                                                                                        background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                        background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                        background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                        background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>

        <div style="margin-top: 30px;">
            <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
                <span>O endereco de entrega informado/selecionado por voce foi:</span><br/>
                <br/>
                <span>
                    <strong>
                        <?php echo $data['addressLabel'];?> <br/>
                        <?php echo $data['address'];?>, <?php echo $data['addressNum'];?> <?php echo ' '.$data['addressComple'];?> - <?php echo  $data['addressDristrict'];?><br/>
                        <?php echo $data['addressCity'];?> - <?php echo $data['addressState'];?> - CEP <?php echo $data['addressZipCode'];?>
                    </strong>
                </span>
                <br/>
                <br/>
                <span>
                    <strong>IMPORTANTE!</strong><br/>
                    <span style="line-height: 13px; font-size: 10px;">O <strong>Prazo de Entrega</strong> de <strong><?php echo $data['deliverytime'];?></strong> dias passara a partir da confirmacao do pagamento e sera ralizado pela propria<br/>
                        XPTO. O Adventa nao realiza, nem se responsabiliza pela entrega de produtos comercializados atraves do seu <br />
                        aplicativo ou site. Se tiver duvidas, por favor, consulte os Termos e Condicoes de Uso disponiveis em nosso site.<br/>
                        Se seu pedido possui mais de um item, estes podem ser enviados separadamente, de acordo com a disponibili-<br/>
                        dade do estoque da Empresa anunciante.
                    </span>
                </span>
            </div>
        </div>


        <div style="margin-top: 15px; ">

            <div id="linha-esquerda" style=" float: left; width: 205px; margin-left: 20px;"><hr style=" border: 0;
                                                                                                height: 1px;
                                                                                                opacity: 0.5;
                                                                                                background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>
            <div id="texto-meio" style="float: left; width: 80px; text-align: center; color: #f37165; font-family: Helvetica; "><i>Status</i></div>
            <div id="linha-direita" style="float: left; width: 220px; "><hr style="border: 0;
                                                                            height: 1px;
                                                                            opacity: 0.5;
                                                                            background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                            background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                            background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                            background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>

            <br/><br/>
            <div style="color:#999; font-size: 12px; font-family: Helvetica;">
                Seu pedido esta <strong><?php echo $data['checkPaymentStatus'];?></strong> pela instituicao financeira. Assim que for liberado, voce<br/>
                recebera um e-mail informando a alteracao do status.<br/>
                Qualquer duvida referente ao processo de aprovacao, entre em contato conosco pelo <br/>
                email <span style="color: #3399cc;">atendimento@adventa.com.br</span>, informando no campo assunto o numero do seu<br/>
                pedido. Se preferir, voce tambem pode entrar em contato diretamente com a Empresa <br />
                anunciante. Os dados de contato sao:
                <br/><br/>
                <span>
                    <strong>
                        Empresa <?php echo $data['compFancy'];?><br/>
                        <?php echo $data['compAddress'];?>,  <?php echo $data['compNumber'];?> <br/>
                        <?php echo $data['compDistrict'];?> - <?php echo $data['compCity'];?> - <?php echo $data['compState'];?><br/>
                        CEP <?php echo $data['compZipcode'];?><br/>
                        Telefone: <?php echo $data['compTel'];?><br/>
                        e-mail: <span style="color: #3399cc;"><?php echo $data['compEmail'];?></span>
                    </strong>
                </span>
                <br/><br/>
                <span style="font-size: 10px;">
                    Para sua seguranca, a intstituicao financeira pode realizar a analise de dados cadastrais; portanto, mantenha<br/>
                    seus dados atualizados no site. Para verificar ou atualizar seus dados, <span style="color: #3399cc;">clique aqui</span>.
                    <br/>
                    <br/>

                    A qualquer momento, voce pode acompanhar o andamento do seu pedido pelo portal <strong>Adventa</strong> acessando<br />
                    <span style="color: #3399cc;">www.adventa.com.br</span> no menu Minhas Compras. <span style="color: #3399cc;">Clique aqui</span> para verifica agora.
                    <br />
                    <br />
                    Se precisar, entre em contato com nossa central de atendimento<br />
                    Nosso horário de atendimento é de segunda a sexta das 8 as 18h.
                </span>
            </div>

            <div style="color:#999; margin-top:20px ;font-size: 12px; font-family: Helvetica;">
                Atenciosamente,<br/>
                <strong style="color: #999; font-size: 16px;">Equipe Adventa</strong><br/>
                <strong style="color: #999; font-size: 16px;"><a href="www.adventa.com.br" style="font-style: normal; color: #999; text-decoration: none">www.adventa.com.br</a></strong>
            </div>

            <div>
                <img src="http://uploaddeimagens.com.br/images/000/429/584/full/Adventa_logo_sombreado.png?1421345215" width="100px" style="margin-top: 15px;"/>
            </div>
        </div>



    </div>
</div>

<div style="height: 5px; width: 600px; position: absolute; top: 138%;
     left:0; background: #f37165;"></div>