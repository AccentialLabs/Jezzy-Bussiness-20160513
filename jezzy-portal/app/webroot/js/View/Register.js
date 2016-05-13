$(function(){

	
	
	$(".phone").mask("(00) 0000-00009");
	
	$(".cnpj").mask("99.999.999/9999-99");
	
	$(".cpf").mask("999.999.999-99");
	
	$("#cep").keyup(function(){
		var zipcode = $("#cep").val();
		$("#loading-addres").fadeIn();
		
		if(zipcode.length == 8){
			$.ajax({			
			type: "POST",			
			data:{
				cep:zipcode
			},			
			url: "/jezzy-portal/company/searchAddressByZipcode",
			success: function(result){	
	
				 var objReturn = JSON.parse(result);
				 console.log(objReturn);
				 
				 $("#bairro").val(objReturn.bairro);
				 $("#localidade").val(objReturn.cidade);
				 $("#logradouro").val(objReturn.logradouro);
				 $("#uf").val(objReturn.estado);
				 
				 $("#loading-addres").fadeOut(200);
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
		}
	  });
		}
	});
	
	$("#btnSubmit").click(function(){
		$("#loading").fadeIn();
	});
	
});
