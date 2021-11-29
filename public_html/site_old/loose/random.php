<!doctype html>
<html lang="pt-pt">
<head>
<meta charset="utf-8">
<title>Universal</title>
<!-- 84246 36 22766 337626337 636337 -->
<!-- FAVICONS -->
<link rel="icon" href="favicon.ico">
<!--<link rel="shortcut icon" href="/img/favicon.ico">-->

<!-- GOOGLE -->
<!--<meta name="keywords" content="Suportes publicitarios digitais">
<meta name="description" content="Um novo mundo para os seus negócios.">-->
<meta name="author" content="Tiago Mendes">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

<!-- FACEBOOK
<meta property="og:title" content="Tecnoled"/>
<meta property="og:site_name" content="Tecnoled"/>
<meta property="og:url" content="http://tecnoled.biz/"/>
<meta property="og:image" content="http://tecnoled.biz/imgs/logo_face.jpg"/>
<meta property="og:description" content="Um novo mundo para os seus negócios."/> -->
<!-- FONT -->
<link href="https://fonts.googleapis.com/css?family=Khula:700" rel="stylesheet">
<!-- SCRIPT -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>

<style type="text/css">
	@charset "utf-8";
	*{ margin:0;padding:0;border:0;outline:none;border-radius:0px;-webkit-appearance:none;-moz-appearance:none;-webkit-font-smoothing:antialiased; 
	box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;font-family: 'Khula', sans-serif;}

	body{padding:0;margin:0;cursor:default;background:#002d73;text-align:center;}
	a{text-decoration:none;} a:link{text-decoration:none;} a:hover{text-decoration:none;}
	article{width:100%;height:auto;cursor:default;min-width:320px}
	section{width:300px;margin:auto;background-color:transparent;}
	.DB360, .DB568, .DB640, .DB768, .DB1024, .DB1200 {display:none;}
	.clear{clear:both;}
	.none{display:none;}

	/* BOTÕES */
	.btDIV{line-height:35px;text-align:center;}
	.btC, .btG, .btV, .btP{width:auto;height:35px;cursor:pointer;transition:all 0.2s linear;font-family:'open_sansbold';background:#fff}
	.btC{border:2px solid #666;color:#666;}
	.btC:hover{background-color:#666;color:#FFF;}
	.btG{border:2px solid #00A651;color:#00A651;}
	.btG:hover{background-color:#00A651;color:#FFF;}
	.btV{border:2px solid #D53A3A;color:#D53A3A;}
	.btV:hover{background-color:#D53A3A;color:#FFF;}
	.btP{border:2px solid #333;color:#333;}
	.btP:hover{background-color:#333;color:#FFF;}

	.random{width:100%;margin-top:70px;margin-bottom:-200px; text-align:center;color:#fff;font-size:400px;font-weight:bold;}
	.button{width:150px;height:150px;line-height:160px;font-size:32px;border-radius:50%;border:2px solid #fff;background:transparent;color:#fff;cursor:pointer;transition:all 0.2s linear;}
	.button:hover{background:#fff;color:#002d73;}
	.logo{width:100%;height:70px;margin-top:180px;background:url('/img/icons/logo_universal.svg')no-repeat center;background-size:contain;}
	.slogan{width:100%;height:40px;margin-top:20px;background:url('/img/icons/slogan.svg')no-repeat center;background-size:contain;}
</style>
</head>

<body>
<div id="RANDOM" class="random">400</div>
<button type="button" class="button" onclick="randomTM2();">INICIAR</button>
<div class="logo"></div>
<div class="slogan"></div>
<!-- SCRIPT -->
<script>
function randomTM2(){
	for (var i = 30; i >= 0; i--) {
		var minValue = 1;
	    var maxValue = 400;
	    var randomN = Math.floor(Math.random() * maxValue) + minValue ;

		$('#RANDOM').html(randomN);
	}

}

var start = 0;
var interval;
function randomN() {
    var minValue = 1;
    var maxValue = 400;
    var randomN = Math.floor(Math.random() * maxValue) + minValue ;
    $('#RANDOM').html(randomN);
}
function startN() {
    interval = window.setInterval(function () {
        randomN();
    }, 200);
}
function randomTM () {
    if(start == 1) {
        window.clearInterval(interval);
        start = 0;
        return;
    } else {
        startN();
        start = 1;
        return;
    }
};
</script>
</body>
</html>