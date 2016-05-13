<font face="Myriad Pro, Arial" color="#535353">
	<table cellpadding="0" cellspacing="0" align="center" width="760px" background="http://secure.trueone.com.br/t1core/img/email/background-conteudo.gif">
		<tr>
			<td bgColor="#333333" colspan="3">

				<table cellpadding="0" cellspading="0" width="98%">
					<tr>
						<td><img src="http://secure.trueone.com.br/t1core/img/email/logo-trueone-topo.gif"></td>
						<td align="right"><font color="#ffffff" size="2"><em><?php echo $data['date'];?></em></font></td>
					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td width="5px" bgColor="#ffa800"></td>
			<td height="91px" bgColor="#535353" width="92px"><img src="http://secure.trueone.com.br/t1core/img/email/icone-destaque-titulo.gif"></td>
			<td height="91px" background="http://secure.trueone.com.br/t1core/img/email/background-titulo.gif">
				&nbsp;&nbsp;&nbsp;<font size="5"><em><strong>Seja Bem - Vindo ao Trueone</strong></font><br>
				&nbsp;&nbsp;&nbsp;<font size="3">Confira os seus dados de acesso</em></font>
			</td>
		</tr>
		<tr>
			<td width="3px" bgColor="#ffa800"></td>
			<td colspan='2' align="center" >

				<table cellpadding="5" cellspacing="0" width="100%">
					<tr>
						<td>

<p><em><strong>Olá, <?php echo $data['name'];?></strong></em>,</p>
<p>Abaixo seus dados para acesso ao TrueOne:</p>
<p>
	<strong>e-mail:</strong> <?php echo $data['Login'];?> <br/>
	<strong>senha:</strong> <?php echo $data['Senha'];?>
</p>
<p>
	Acesse o TrueOne através do link: 
	<a href="https://secure.trueone.com.br/portal/user/login"><font color="#ffa800">https://secure.trueone.com.br/portal/user/login</font></a>
</p>

</td>
</table>

</td>
</tr>

<tr>
<td width="5px" bgColor="#ffa800"></td>
<td colspan="2" align="center">
<br><br><br>
	<table cellpadding="0" cellspacing="0" width="95%">
		<tr>
			<td width="180px" align="center"><img src="http://secure.trueone.com.br/t1core/img/email/trueOne.png"></td>
			<td colspan="2">
				<em>
				<font size="3">
				atenciosamente,<br>
				<strong>Equipe trueOne</strong><br>
				<a href=""><font color="#ffa800">www.trueone.com.br</font></a>
				</font>
				</em>
			</td>
		</tr>
	</table>
<br><br><br>
			</td>
		</tr>
		<tr>
			<td bgColor="#333333" colspan="3" height="4"></td>
		</tr>
	</table>
</font>