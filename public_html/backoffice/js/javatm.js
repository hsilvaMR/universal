/****************************************************************/
/*                                                              */
/*                         TIAGO MENDES                         */
/*                   tiago_mendes@live.com.pt                   */
/*                          01-06-2016                          */
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

//          VALIDATE EMAIL
//==================================
function validateEmail(email){
	var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	var valid = emailReg.test(email);

	if(!valid) {
        return false;
    } else {
    	return true;
    }
}

//              MODALS              
//==================================
var id_del;
function mostrar0(IdModal){
	$('#'+IdModal).css("visibility","visible");
	$('#'+IdModal).css("opacity","1");
	//document.getElementById(IdModal).style.visibility="visible";
	//document.getElementById(IdModal).style.opacity="1";
}
function mostrar(IdModal,id){
    id_del = id;
    $('#'+IdModal).css("visibility","visible");
	$('#'+IdModal).css("opacity","0");
	//document.getElementById(IdModal).style.visibility = "visible";
	//document.getElementById(IdModal).style.opacity = "0";
	$("#"+IdModal).animate({opacity:"1"},300);
}
function esconder0(IdModal){
	//if(id=="1"){location.reload();}
	$('#'+IdModal).css("visibility","hidden");
	$('#'+IdModal).css("opacity","0");
	//document.getElementById(IdModal).style.visibility="hidden";
	//document.getElementById(IdModal).style.opacity="0";
}
function esconder(IdModal){
	//if(id=="1"){location.href="https://drink.boutique/bootstrap/shop";}
	$("#"+IdModal).animate({opacity:"0"},200);	
	setTimeout(function(){ $('#'+IdModal).css("visibility","hidden"); }, 200);
}

//              FOCUS              
//==================================
function focar(id){
	setTimeout(function(){ $('#'+id).focus(); }, 200);
}

//           RESET FORM              
//==================================
function limparForm(id){
	setTimeout(function(){ $('#'+id)[0].reset(); }, 200);
}
//<div class="upload_file btY"><span id="FICHEIRO">SELECIONAR FICHEIROS</span>
//<input type="file" name="imagem[]" accept="image/*"  onchange="lerFicheiros(this,'FICHEIRO');" multiple/></div>

//          INPUT FILE              
//==================================
function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    //var nome = input.value;
    //if(quantidade==1){$('#'+id).html(quantidade+' FICHEIRO');}
    //else{$('#'+id).html(quantidade+' FICHEIROS');}
    $('#'+id).html('<i class="fa fa-file-archive-o" aria-hidden="true"></i>');
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

//              MENU              
//==================================
function verMCONTA(){
	$("#MCONTA").css('display','block');
	$("#MCONTA").animate({opacity:"1"}, 250);
}
function ocultarMCONTA(){
	$("#MCONTA").animate({opacity:"0"},300);
	setTimeout(function(){ $("#MCONTA").css('display','none'); }, 350);
}
var timeout = 600;
var closetimer = 0;
function closetMCONTA(){ closetimer = window.setTimeout(ocultarMCONTA, timeout); }
function cancelMCONTA(){ if(closetimer) {window.clearTimeout(closetimer); closetimer = null;} }

function verMNOTIFICACOES(){
	$("#MNOTIFICACOES").css('display','block');
	$("#MNOTIFICACOES").animate({opacity:"1"}, 250);

	if($('#notificacoesHeader').html() == '<div class="bigLoading"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></div>')
	{
		getNotificacoes('0');
	}
}

function getNotificacoes(start) {
	$('#notificacoesHeader .viewMoreNotf').hide();
	$('#notificacoesHeader .loading2').show();
	$.get('/getNotificacoes/'+start, function( data ) {
		$('#notificacoesHeader .viewMoreNotf').remove();
		$('#notificacoesHeader .loading2').remove();
		if(start == '0')
		{
			$('#notificacoesHeader').html(data);
		}
		else
		{
			$('#notificacoesHeader').append(data);
		}
	});
}

function ocultarMNOTIFICACOES(){
	$("#MNOTIFICACOES").animate({opacity:"0"},300);
	setTimeout(function(){ $("#MNOTIFICACOES").css('display','none'); }, 350);
}
var timeout = 600;
var closetimer = 0;
function closetMNOTIFICACOES(){ closetimer = window.setTimeout(ocultarMNOTIFICACOES, timeout); }
function cancelMNOTIFICACOES(){ if(closetimer) {window.clearTimeout(closetimer); closetimer = null;} }

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