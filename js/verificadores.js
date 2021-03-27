//DevMedia
function verificarcpf(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
  if (strCPF == "00000000000") return false;
     
  for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
  Resto = (Soma * 10) % 11;
   
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
   
  Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
   
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
}

function verificartxt(valor, qtd){
    return valor.length == qtd;
}

function verificarnome(nome){
    return nome.split(" ").length>1;
}

function verificarsenha(senha, rsenha, qtddig){
  return (senha == rsenha && senha.length>=qtddig);
}

function verificaremail(email){
  if(email.split(" ").length>1)
    return false;
  let partes = email.split("@");
  if(partes.length == 2){
    return partes[1].split(".").length>1;
  }
  else{
    return false;
  }
}

//Goblinlord
function verificardata(dateString){
  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  if(!dateString.match(regEx)) return false; 
  var d = new Date(dateString);
  var dNum = d.getTime();
  if(!dNum && dNum !== 0) return false; 
  return d.toISOString().slice(0,10) === dateString;
}