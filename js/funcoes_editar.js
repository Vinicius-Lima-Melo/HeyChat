function aplicarmascara(opcao){
    if(opcao){
		$("#cep").mask("99999-999");
    }
    else{
		$("#cep").unmask("99999-999");
    }
}

$(document).ready(function() {
    aplicarmascara(true);
});