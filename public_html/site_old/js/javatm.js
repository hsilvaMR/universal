/****************************************************************/
/*                                                              */
/*                         TIAGO MENDES                         */
/*                   tiago_mendes@live.com.pt                   */
/*                          22-01-2015                          */
/*                                                              */
/****************************************************************/

//              GERAL              
//==================================
// function funcao(variavel,variavel){ }
// var variavel = document.getElementById("input").value;
// var separada = variavel.split(':'); separada[0]; separada[1];
// document.getElementById("div").innerHTML = 'Texto na div!';
// setTimeout(function(){ ... },3000);
// document.getElementById("div").style.display = "none";
// parseInt(minimo)
// window.location.href = "/home";
// var url = document.URL;
// if(screen.width < 768){ ... }
// $("#div").removeClass("verde");
// $("#div").addClass("branca");
// $(document).ready(function() { ... });
// $("#BT_MENU").click(function(){ ... });
// $("#MENU").toggle();
// $("#MENU").animate({top:"0"},300);
// $("#BT_MENU").css("background","url(/imgs/btnclose.svg)");
// $(window).on("scroll", function () { if ($(this).scrollTop() > 100) { ... } else { ... } });
// onkeypress="if(event.keyCode==13)entrar();"

//               MENU               
//==================================
/* <div onmouseover="verMenu(); mcancel()" onmouseout="mclose()">SHOP</div>
<div id="menu_shop" onmouseover="mcancel()" onmouseout="mclose()"> ... </div>

function verMenu(){ document.getElementById("menu_shop").style.top = "90px"; }
function mcloseShop(){ document.getElementById("menu_shop").style.top = "-600px"; }

var timeout = 600;
var closetimer = 0;
function mclose(){ closetimer = window.setTimeout(mcloseShop, timeout); }
function mcancel(){ if(closetimer) {window.clearTimeout(closetimer); closetimer = null;} } */

//               AJAX               
//==================================
/* jQuery.ajax({
	 type: "POST",
	 url: "/_pasta/ficheiro.php",
	 data: 'nome='+nome+'&telefone='+telefone,
	 success: function(data){
	   $("#div").html(data);
	 }
}); */
// ficheiro.php
/* <?php include('../_connect.php'); $nome = $_POST['nome']; $telefone = $_POST['telefone']; include('_horario.php'); ?> */

//              JSON               
//==================================
/* $.post("_pasta/js_dados.php",{ nome:nome, telefone:telefone })
    .done(function(data){
	   var jsonRetorna = $.parseJSON(data);
	   var id = jsonRetorna['id']; var ligado = jsonRetorna['ligado'];
	   document.getElementById("aviso").innerHTML = jsonRetorna;
	   setTimeout(function(){ document.getElementById("aviso").innerHTML=''; },3000);
}); */
/*
<?php include('../_connect.php'); session_start();
$jsonReceiveData = json_encode($_POST);
$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($jsonReceiveData, TRUE)),RecursiveIteratorIterator::SELF_FIRST);

$valores = array();
foreach ($jsonIterator as $key => $val)
{ 
   if(is_array($val)) { foreach($val as $key1 => $val1) { $valores[$key][$key1] = $val1; } }
   else { $valores[$key] = $val; }
}
$nome = $valores['nome'];
$telefone = $valores['telefone'];

$retorna['id']='1200';
$retorna['ligado']='sim';
$ret = $retorna;
echo json_encode($ret);

echo json_encode($retorna); ?>
*/

//              MODALS              
//==================================
var id_del;
function mostrar0(IdModal){
	document.getElementById(IdModal).style.visibility="visible";
	document.getElementById(IdModal).style.opacity="1";
}
function mostrar(IdModal,id){
    id_del = id;
	document.getElementById(IdModal).style.visibility = "visible";
	document.getElementById(IdModal).style.opacity = "0";
	$("#"+IdModal).animate({opacity:"1"},300);
	if(IdModal == 'TMlogin'){document.getElementById('emailL').select();}
}
function esconder0(IdModal,id){
	if(id=="1"){location.reload();}
	document.getElementById(IdModal).style.visibility="hidden";
	document.getElementById(IdModal).style.opacity="0";
}
function esconder(IdModal,id){
	if(id=="1"){location.href="https://drink.boutique/bootstrap/shop";}
	$("#"+IdModal).animate({opacity:"0"},200);	
	setTimeout(function(){ document.getElementById(IdModal).style.visibility = "hidden"; }, 200);
}

//              COOKIE              
//==================================
function createCookie(name,value,horas){
	if (horas) {
		var date = new Date();
		date.setTime(date.getTime()+(horas*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

//              IDADE              
//==================================
/*function maiorIdade()
{
	data = new Date();
    ano = data.getFullYear();
			
	var anoIdade = document.getElementById("maiorIdade").value;
	document.getElementById("maiorIdade").value = "";
	
	if((ano-anoIdade) > 18 && anoIdade > 1800)
	{
		createCookie('IDADE','sim',48);
		esconder('TMmais18');		
	}
	else 
	{ document.getElementById("erro_idade").innerHTML="Não tens idade legal para entrar no site.";
	  setTimeout(function(){ document.getElementById("erro_idade").innerHTML=""; },5000); }
}*/

//           MASCARAS              
//==================================
/*$(document).ready(function(){
	$('#maiorIdade').mask('A000', {'translation': { A: {pattern: /[1-2]/} } });
	$('.mcpostal').mask('A000-000', {'translation': { A: {pattern: /[1-9]/} } });
	$('.mtlm').mask('000000000');
	$('.mnif').mask('000000000');
});*/