function aplicarmascara(opcao){
    if(opcao){
		$("#cep").mask("99999-999");
    }
    else{
		$("#cep").unmask("99999-999");
    }
}

function iniciarvalidacao(){
    $("input").removeClass("is-valid");
    $("input").removeClass("is-invalid");
    $("select").removeClass("is-invalid");
    $("select").removeClass("is-invalid");

}

function esconderItens(){
    $("#btn_abrirLogin").css('display', 'none');
    $("#btn_criarConta").css('display', 'none');
    $("#form_login").css('display', 'none');
    $("#form_cadastro").css('display', 'none');
}

$(document).ready(function() {
    aplicarmascara(true);
    iniciarvalidacao();
    

    $("#btn_criarConta").click(function() {
    	esconderItens();
    	$("#btn_abrirLogin").fadeIn(600);
    	$("#form_cadastro").fadeIn(600);

    });
    $("#btn_abrirLogin").click(function() {
    	esconderItens();
    	$("#btn_criarConta").fadeIn(600);
    	$("#form_login").fadeIn(600);
    });


    $("#imgPerfil").click(function() {
  	    $("#input_escondido").click();
    });	

	$("#input_escondido").change(function() {
		let file = $("#input_escondido").val();
		var fileName = file.split("\\");
        $("#imgPerfil").val(fileName[fileName.length - 1]);
	});


	$("#formulario_cadastro").submit(function(ev){
		iniciarvalidacao();
        aplicarmascara(false);
        
        let nome, apelido, fotoPerfil, email, senha, endereco, bairro, cidade,estado, CEP;

        nome = $("#nomeCompleto").val();
        apelido = $("#apelido").val();
        fotoPerfil = $("#imgPerfil").val();
        email = $("#email").val();
        senha = $("#senha").val();
        endereco = $("#endereco").val();
        bairro = $("#bairro").val();
        cidade = $("#cidade").val();
        estado = $("#estado").val();
        cep = $("#cep").val();
        termos = $("#termos").prop("checked");
  
        if(nome == "")
            $("#nomeCompleto").addClass("is-invalid");
        else
            $("#nomeCompleto").addClass("is-valid");


		if(apelido == "")
            $("#apelido").addClass("is-invalid");
        else
            $("#apelido").addClass("is-valid");

        if(email == "")
            $("#email").addClass("is-invalid");
        else{
            $.ajax({
            url: "funcoes/validarEmail.php",
            type: "POST",
            data: {Email:email},
            async: false,
            success : function(e){
                var json = JSON.parse(e);
                console.log(json);
                if (json) 
                    $("#email").addClass("is-valid");
                else if(!json){
                    $("#email").addClass("is-invalid");
                    $("#feedback_email").html("");
                    $("#feedback_email").addClass("invalid-feedback");
                    $("#feedback_email").append("Email já cadastrado.");
                }
            }
        });
        }

        if(senha == "")
            $("#senha").addClass("is-invalid");
        else if(senha.length < 8){
            $("#senha").addClass("is-invalid");
            $("#feedback_senha").html('');
            $("#feedback_senha").addClass("invalid-feedback");
            $("#feedback_senha").append('A senha deve conter no minimo 8 dígitos');
        }
        else
            $("#senha").addClass("is-valid");


        if(endereco == "")
            $("#endereco").addClass("is-invalid");
        else
            $("#endereco").addClass("is-valid");


        if(bairro == "")
            $("#bairro").addClass("is-invalid");
        else
            $("#bairro").addClass("is-valid");
        

        if(cidade == "")
            $("#cidade").addClass("is-invalid");
        else
            $("#cidade").addClass("is-valid");

        if(estado == "")
            $("#estado").addClass("is-invalid");
        else
            $("#estado").addClass("is-valid");

        if(cep == "")
            $("#cep").addClass("is-invalid");
        else
            $("#cep").addClass("is-valid");

        if(!termos)
            $("#termos").addClass("is-invalid");
        else
            $("#termos").addClass("is-valid");
        
        let qtdinvalido = $(".is-invalid").length;

        if(qtdinvalido > 0 ){
            ev.preventDefault(); 
        }

        aplicarmascara(true);

    });

});