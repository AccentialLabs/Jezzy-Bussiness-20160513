<?php ?>


<div style=" top: 0;
     left: 0;
     position: fixed;
     width: 600px;
     background: #f3f3f3;
     height: 100%;">
    <div id="barra-direita" style="position: fixed;
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
         height: 100%;
         background: #F8F8FF;
         margin-top: 10px;">
        <img src="http://uploaddeimagens.com.br/images/000/429/581/full/Icon.png?1421345160" id="icon-email" style="width: 45px; margin-left: 10px; margin-top: 10px; float: left;"/>   
        <div id="pedido" style="float: left; margin-left: 10px; margin-top: 10px; font-family: Helvetica;">
            <strong style="color: #555;">PEDIDO <?php echo $data['checkId'];?></strong>
            <br />
            <span style="color: #999999; font-size: 11px;">Transacao Finalizada!</span>
        </div>
        <img src="http://uploaddeimagens.com.br/images/000/429/583/full/email_adv_final.png?1421345189" id="foto-compl" width="100.1%;" style="margin-top: 5px; margin-left: -1px;"/>

        <div id="linha-esquerda" style="margin-top: 20px; float: left; width: 205px; margin-left: 20px;"><hr style=" border: 0;
                                                                                                             height: 1px;
                                                                                                             opacity: 0.5;
                                                                                                             background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                                             background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>
        <div id="texto-meio" style="float: left; width: 80px; text-align: center; color: #f37165; font-family: Helvetica; margin-top: 20px;"><i>Obrigado!</i></div>
        <div id="linha-direita" style="float: left; width: 205px; margin-top: 20px;"><hr style="border: 0;
                                                                                         height: 1px;
                                                                                         opacity: 0.5;
                                                                                         background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                         background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                         background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
                                                                                         background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); "/></div>

        <div style="position: fixed; margin-top: 50px; margin-left: 25px; ">
            <span style="color: #3399cc; font-family: Helvetica; font-size: 14px;"><i>Caro Sr(a). <?php echo $data['userName'];?>,</i></span>
            <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">Esperamos que tenha recebido os produtos que comprou corretamente e que esteja<br/> satisfeito(a) com sua escolha!
            </div>
            <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
                Aproveite e avalie a sua compra e a Empresa <?php echo $data['compName']; ?> em nosso site, assim outras pessoas<br />
                poderao usufruir dos seus comentarios e realizarm compras mais confiantes.
            </div>

            <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
                <span style="color: #3399cc; cursor: pointer;">Clique aqui</span> para acessar seu pedido. Apos entrar com seuys dados de login, clique em<br/>
                "Comentario e Opinioes". 
            </div>

            <div style="color:#999; margin-top:15px ;font-size: 12px; font-family: Helvetica;">
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
<div style="height: 5px; width: 600px; position: fixed; bottom: 0;
     left:0; background: #f37165;"></div>

