<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Laravel -->
<script src="{{ asset('site_v2/js/app.js') }}"></script>

<!-- Javascript -->
<!--<script src="{ { asset('site_v2/js/main.js') }}"></script>-->

<!--Header-->

<script>

	

	function openMenu(valor) {

		if (valor == "product") {

			$("#menu-premium").hide();
			$("#angle-premium").hide();
			$("#menu-products").show();
			$("#angle-product").show();

			$('#menu-products').mouseover(function () {
                $('#menu-products').show();
                $("#angle-product").show();

            });

            
	
			

            $('#menu-products').mouseleave(function () {
                setTimeout(function(){ $("#menu-products").hide();$("#angle-product").hide(); }, 800);
            });
		}
		if (valor == "premium") {
			$("#menu-products").hide();
			$("#angle-product").hide();
			$("#menu-premium").show();
			$("#angle-premium").show();

			$('#menu-premium').mouseover(function () {
                $('#menu-premium').show();
                $("#angle-premium").show();
            });
            $('#menu-premium').mouseleave(function () {
                setTimeout(function(){ $("#menu-premium").hide();$("#angle-premium").hide(); }, 1800);
            });
		}
	}

	function showHeaderXS(){
		setTimeout(function(){$("#submenu-xs").show();}, 300);
		$('body').css('overflow','hidden');
		$('.header-xs').animate({right: "280"}, 300);
    	$('#submenu-xs').animate({right: "0"}, 300);
    	$('body').animate({right: "280"}, 300);
	}
	
	function hideMenuXS(){
		$('#submenu-xs').hide();
		$('body').css('overflow','visible');
		$('.header-xs').animate({right: "0"}, 300);
		$('body').animate({right: "0"}, 300);
	}

	function showSubMenuXS(valor){
		if (valor == 'products') {
			var product = document.getElementById("header-prod");
			if (product.style.display == 'none') { product.style.display = "block"; } 
			else { product.style.display = "none"; }
		}
		else{
			var product = document.getElementById("header-premium");
			if (product.style.display == "none") { product.style.display = "block"; } 
			else { product.style.display = "none"; }
		}
	}
</script>

<!--Footer-->

<script>
	function showLang(){
		$("#lang").show();
		$("#lang-present").hide();
	}
	function hideLang(){
		$("#lang-present").show();
		$("#lang").hide();
	}
	function openFooterXS(){
        $("#condicoes").show();
        $("#angle-close").show();
        $("#angle-open").hide();
        $('html, body').animate({scrollTop: $(document).height()}, 700);
    }
    function openColseXS(){
    	$("#condicoes").hide();
    	$("#angle-close").hide();
        $("#angle-open").show();
    }
	function goup() {
	    $('html, body').animate({ scrollTop: 0 }, "slow");
	    return false;
	}
</script>