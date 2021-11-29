<!DOCTYPE HTML>
<html>
<head>
    <? include('_head.php'); ?>
    <title>Universal</title>
    <meta name="description" content="Reconhecida fábrica de produtos lácteos tradicionais, baseada em Oliveira de Azeméis.">
    <meta name="keywords" content="universal, queijo universal, queijo prato, lacticinios, lacticinios de azemeis, queijo, manteiga, manteiga universal, oliveira de azemeis, queijo bola">
</head>
<body class="no-js">
    <section class="main">
        <? include('_header.php'); ?>
        
        <section class="slider">
            <div class="wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <!-- upload/slide1.jpg upload/slide2.jpg upload/slide3.jpg img/slider-01.jpg img/slider-03.jpg -->
                        <div class="swiper-slide">
                            <img src="/img/slide/slider-02.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="/img/slide/slider-04.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="/img/slide/slide.jpg" height="360" alt="Queijo Universal">
                        </div>                        
                        <div class="swiper-slide">
                            <img src="/img/slide/slider-05.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="/img/slide/slider-06.jpg" height="360" alt="Queijo Universal">
                        </div>
                        
                    </div>
                    <div class="swiper-pagination-wrapper">
                        <div class="swiper-pagination"></div>
                    </div><!-- /swiper-pagination-wrapper -->
                </div>
            </div><!-- wrap -->
        </section><!-- slider -->
        <section class="features">
            <div class="wrap">
                <div class="features-columns clearfix">
                    <div class="features-column">
                        <? if($lang=='pt'){ ?>
                            <h3>Sobre nós</h3>
                            <p>A Universal nasce através da associação de 11 pequenos produtores de manteiga da zona de Oliveira de Azeméis.</p>
    						<p>A sociedade é constituída em 30 de Dezembro de 1940, com um capital de cerca de 1260 Euros, e entra em funcionamento a 1 de Abril do ano seguinte.</p>
    						<p>Durante dois anos, a <strong>manteiga</strong> é o único produto.</p>
    						<p>O processo de fabrico é manual, assim como a embalagem e a pesagem. Instalações de frio não existem, mas há os poços que são bons para a maturação das natas e da manteiga.</p>
    						<p>Embrulhada em pacotes de papel vegetal de 125g e 250g, esta manteiga traz o nome à empresa e vai-se tornando indispensável nas melhores pastelarias e charcutarias do mercado de Lisboa.</p>
						<? }if($lang=='en'){ ?>
                            <h3>About Us</h3>
                            <p>Universal is born through the association of 11 small producers of butter in the area of Oliveira de Azeméis located in the north of Portugal.</p>
                            <p>The company was incorporated on December 30, 1940, with a capital of about 1260 Euros, and starts operating on April 1 of the following year.</p>
                            <p>For two years, <strong>butter</strong> is the only product.</p>
                            <p>The manufacturing process is manual, as well as packaging and weighting. Cold facilities do not exist, but there are wells that are good for the maturation of the cream and the butter.</p>
                            <p>Wrapped in bundles of vegetable paper with 125g and 250g, this butter bears the name of the company and is becoming indispensable in the best pastries and delicatessens of the Lisbon market.</p>
                        <? }if($lang=='es'){ ?>
                            <h3>Sobre nosotros</h3>
                            <p>La Universal nace a través de la asociación de 11 pequeños productores de mantequilla de la zona de Oliveira de Azeméis en el norte de Portugal.</p>
                            <p>La sociedad está constituida el 30 de diciembre de 1940, con un capital de 1260 euros, y entra en funcionamiento a 1 de abril del año siguiente.</p>
                            <p>Durante dos años, la <strong>mantequilla</strong> es el único producto.</p>
                            <p>El proceso de fabricación es manual, así como el embalaje y el pesaje. Las instalaciones de frío no existen, pero hay los pozos que son buenos para la maduración de la crema y la mantequilla.</p>
                            <p>Envuelto en papel vegetal en paquetes de 125g y 250g, esta mantequilla aporta a la compañía su nombre y se está convirtiendo en esencial en las mejores tiendas de pastelería y charcutería en el mercado de Lisboa.</p>
                        <? }if($lang=='fr'){ ?>
                            <h3>A propos de nous</h3>
                            <p>"Universal" est nait à partir de 11 petits producteurs de beurre aux alentours de Oliveira de Azeméis.</p>
                            <p>La société a été constituée le 30 Décembre 1940, avec un capital d'environ 1260 Euros et est mis en œuvre  le 1 Avril de l'année suivante.</p>
                            <p>Pendant deux ans, le <strong>beurre</strong> et le seul produit.</p>
                            <p>Le processus de fabrication est manuel, ainsi comme l'emballage et le pesage. La chaîne de froids n'existe pas, mais il y a des puis qui sont bons pour la maturation des crèmes et du beurre.</p>
                            <p>Enveloppé en paquets de papier végétal de 125g et 250g, ce beurre donne le nom à la société et est indispensable sur les meilleurs pâtisseries et charcuteries du marcher de Lisbonne.</p>
                        <? } ?>
                    </div><!-- features-column -->
                    <div class="features-column">
                        <? if($lang=='pt'){ ?>
                            <h3>No princípio...</h3>
                            <p>Os dias do princípio são trabalhosos. Todos os braços são necessários para o fabrico da manteiga, mas ainda se lhes pede ajuda na construção da primeira unidade industrial da empresa, que fica de pé em 1941, na freguesia de Travanca, face à antiga estrada nacional nº 1 Lisboa - Porto.</p>
    						<p>É aí que a Universal cresce e se desenvolve, nunca perdendo o seu cariz familiar.</p>
    						<p>Em 1942 começam os primeiros ensaios de fabricação do <strong>queijo</strong>.</p>
                            <p>O leite vem de todas as aldeias das redondezas, onde o gado e os campos são tratados por lavradores a tempo inteiro.</p>
    						<p>Mas quando não chega para as necessidades, vai-se mais longe fazer a recolha.</p>
    						<p>A produção de queijo é que não pode parar.</p>
                            <p>Exigia-o a clientela, que na 2ª metade da década de 40 já pede tanto manteiga como queijo Universal.</p>
                        <? }if($lang=='en'){ ?>
                            <h3>At the beginning...</h3>
                            <p>The days of the beginning are laborious. All the arms are necessary for the manufacture of the butter, but they are still asked to help in the construction of the first industrial unit of the company, which stands in 1941 in the parish of Travanca, in Oliveira de Azeméis.</p>
                            <p>This is where the Universal grows and develops, never losing its familiar aspect.</p>
                            <p>In 1942 the first tests of <strong>cheese</strong> manufacture begin.</p>
                            <p>The milk comes from all the surrounding villages, where livestock and fields are treated by full-time farmers.</p>
                            <p>But when it does not cover the overall needs, then must go further to collect the milk.</p>
                            <p>The production of cheese can't stop.</p>
                            <p>It demanded it to the clientele that in the second half of the decade of the 40 already asks for as much butter as Universal cheese.</p>
                        <? }if($lang=='es'){ ?>
                            <h3>En el principio...</h3>
                            <p>Los días del principio son laboriosos. Se necesitan todas las armas para la fabricación de mantequilla, pero todavía piden ayuda en la construcción de la primera unidad industrial de la compañía, que se sitúa en 1941, en el pueblo de Travanca, en Oliveira de Azeméis.</p>
                            <p>Es ahí donde Universal crece y se desarrolla, nunca perdiendo su cariz familiar.</p>
                            <p>En 1942 comienzan los primeros ensayos de fabricación del <strong>queso</strong>.</p>
                            <p>La leche viene de todas las aldeas de los alrededores, donde el ganado y los campos son tratados por labradores a tiempo completo.</p>
                            <p>Pero cuando no llega a las necesidades, se va más lejos a hacer la recogida.</p>
                            <p>La producción de queso es que no puede parar.</p>
                            <p>Lo exigía la clientela, que en la segunda mitad de la década del 40 ya pide tanto mantequilla como queso Universal.</p>
                        <? }if($lang=='fr'){ ?>
                            <h3>Histoire...</h3>
                            <p>Les premiers jours sont laborieux. Tous les bras sont nécessaires pour la fabrication du beurre, et en plus on demande aux travailleurs l'aide à la construction de la première unité industrielle de la société, qui termine en 1941, á Travanca au bord de la principale route nationale nº1 Lisbonne – Porto.</p>
                            <p>C'est ici que "Universal" progresse et se développe, ne jamais perdant ca connotation familiale.</p>
                            <p>En 1942, débutent les premiers essaies de fabrication de <strong>fromage</strong>.</p>
                            <p>Le lait arrive de tous les petits villages voisins, ou les animaux et les champs sons traités par les paysans à plein temps.</p>
                            <p>Mais quand les quantités ne suffisent pas, il faut aller récolter plus loin.</p>
                            <p>La production de fromage ne peut pas s'arrêter.</p>
                            <p>Vers la deuxième décennie, les clients exigeaient déjá autant de beurre comme de fromage "Universal".</p>
                        <? } ?>
                    </div><!-- features-column -->
                    <div class="features-column">
                        <? if($lang=='pt'){ ?>
                            <h3>Universalizamo-nos!</h3>
    						<p>Os anos vão passando. Os mercados alteram-se e a empresa acentua e inova o fabrico do queijo, o seu produto de maior valor acrescentado.</p>
    						<p>A produção de manteiga vai-se mantendo, todavia.</p>
    						<p>Já nos anos 80, os fundadores passam testemunho à segunda geração, que assume a gestão da empresa.</p>
    						<p>As exigências dos tempos mais recentes e a natural obsolescência da fábrica primitiva determinam a necessidade da realização de avultados investimentos.</p>
    						<p>Inicia-se, então, nos finais da década de 80, um amplo projecto de ampliação, de renovação e de modernização de instalações e equipamentos, de modo a relançar a Universal num mercado cada vez mais aberto e concorrencial.</p>
    						<p>Após este esforço de investimentos, a Universal encontra-se apetrechada para garantir Qualidade e promover a valorização dos seus produtos.</p>
                        <? }if($lang=='en'){ ?>
                            <h3>We universalize!</h3>
                            <p>The years go by. Markets change and the company accentuates and innovates the manufacture of cheese, its highest added value product.</p>
                            <p>The production of butter is, however, maintained.</p>
                            <p>Already in the 80's, the founders give testimony to the second generation, who takes over the management of the company.</p>
                            <p>The demands of the more recent times and the natural obsolescence of the primitive factory determine the need for large investments.</p>
                            <p>At the end of the 1980s, a broad project of expansion, renovation and modernization of facilities and equipment began, in order to relaunch Universal in an increasingly open and competitive market.</p>
                            <p>After this investment effort, Universal is equipped to guarantee Quality and promote the appreciation of its products.</p>
                        <? }if($lang=='es'){ ?>
                            <h3>Nosotros Universalizamos!</h3>
                            <p>Los años van pasando. Los mercados cambian y la empresa acentúa e innova la fabricación del queso, su producto de mayor valor añadido.</p>
                            <p>La producción de mantequilla se mantiene, sin embargo.</p>
                            <p>Ya en los años 80, los fundadores pasan testimonio a la segunda generación, que asume la gestión de la empresa.</p>
                            <p>Las exigencias de los tiempos más recientes y la natural obsolescencia de la fábrica primitiva determinan la necesidad de realizar grandes inversiones.</p>
                            <p>Se inicia a finales de la década de los 80 un amplio proyecto de ampliación, renovación y modernización de instalaciones y equipamientos, para relanzar a Universal en un mercado cada vez más abierto y competitivo.</p>
                            <p>Después de este esfuerzo de inversiones, Universal se encuentra dotada para garantizar Calidad y promover la valorización de sus productos.</p>
                        <? }if($lang=='fr'){ ?>
                            <h3>On c'est universaliser!</h3>
                            <p>Les années passent. Les marchés changent et la société accentue et innove la production de fromage, qui est le produit avec la plus forte valeur ajoutée.</p>
                            <p>La production de beurre continue.</p>
                            <p>Plus tard dans les années 80,  les fondateurs passent le relais à la deuxième génération, qui assume la gestion de l'entreprise.</p>
                            <p>Les exigences des temps plus récents et la naturelle obsolescence de l'usine, déterminent la nécessité d'importants investissements.</p>
                            <p>Ainsi commence, au final des années 80, un grand projet d'agrandissement, de rénovation et modernisation des installations et équipements de façon à relancer "Universal" sur un marché de plus en plus ouvert et compétitif.</p>
                            <p>Après cet effort d'investissement, "Universal" se trouve bien équipée pour garantir une bonne qualité et promouvoir la valorisation de ces produits.</p>
                        <? } ?>
                    </div><!-- features-column -->
                </div><!-- features-columns -->
                <div class="separator"></div>
            </div><!-- wrap -->
        </section><!-- features -->
        <section class="textblock">
            <div class="wrap">
                <img src="/img/site/queijo.jpg" width="320" height="280" alt=""><!-- upload/image.jpg -->
                <div class="text-column">
                    <? if($lang=='pt'){ ?>
                        <h2>o Queijo</h2>
                        <p>Queijo de leite de vaca pasteurizado, com pasta firme e fácil de cortar, paladar suave e textura amarela.</p>
                        <p>- bola flamengo<br>
                        - meia bola flamengo<br>
                        - quarto de bola flamengo<br>
                        - barra flamengo<br>
                        - meia barra flamengo<br>
                        <!-- - queijo prato</p>-->
                    <? }if($lang=='en'){ ?>
                        <h2>the Cheese</h2>
                        <p>Pasteurized cow's milk cheese with firm paste and easy to cut, soft taste and yellow texture.</p>
                        <p>- flamengo ball<br>
                        - flamengo half-ball<br>
                        - flamengo quarter ball<br>
                        - flamengo loaf<br>
                        - flamengo half loaf<br>
                    <? }if($lang=='es'){ ?>
                        <h2>el Queso</h2>
                        <p>Queso de leche de vaca pasteurizada, con pasta firme y fácil de cortar, paladar suave y textura amarilla.</p>
                        <p>- bola flamengo<br>
                        - media bola flamengo<br>
                        - cuarto de bola flamengo<br>
                        - barra flamengo<br>
                        - media barra flamengo<br>
                    <? }if($lang=='fr'){ ?>
                        <h2>le Fromage</h2>
                        <p>Fromage au lait de vache pasteurisé avec pâte ferme et facile à couper, goût doux et texture jaune.</p>
                        <p>- boule de flamengo<br>
                        - demi-boule flamengo<br>
                        - quart de boule flamengo<br>
                        - un bar flamengo<br>
                        - demi barre flamengo<br>
                    <? } ?>
                </div><!-- text-column -->
                <div class="separator"></div>
            </div><!-- wrap -->
        </section><!-- textblock -->
        <section class="textblock textblock-last">
            <div class="wrap">
                <img src="/img/site/manteiga.jpg" width="320" height="280" class="align-right" alt=""><!-- upload/image2.jpg -->
                <div class="text-column">
                    <? if($lang=='pt'){ ?>
                        <h2>a Manteiga</h2>
                        <p>Produto obtido exclusivamente de natas pasteurizadas de leite de vaca, de sabor caracteristicamente tradicional, de cor amarela clara.</p>
                        <p>- 10 g com sal<br>
                        - 10 g sem sal<br>
                        <!--- 125 g com sal<br>
                        - 125 g sem sal<br>-->
                        - 250 g com sal<br>
                        - 250 g sem sal<br>
                        <!--- 1000 g com sal<br>-->
                    <? }if($lang=='en'){ ?>
                        <h2>the Butter</h2>
                        <p>Product obtained exclusively from pasteurized cream of cow's milk, with characteristically traditional taste, light yellow in color.</p>
                        <p>- 10 g salted<br>
                        - 10 g without salt<br>
                        - 250 g salted<br>
                        - 250 g without salt<br>
                    <? }if($lang=='es'){ ?>
                        <h2>la Mantequilla</h2>
                        <p>Producto obtenido exclusivamente de natas pasteurizadas de leche de vaca, de sabor tradicional, de color amarillo claro.</p>
                        <p>- 10 g con sal<br>
                        - 10 g sin sal<br>
                        - 250 g con sal<br>
                        - 250 g sin sal<br>
                    <? }if($lang=='fr'){ ?>
                        <h2>le Beurre</h2>
                        <p>Produit obtenu exclusivement à partir de crème de lait de vache pasteurisée, au goût caractéristique caractéristique, de couleur jaune clair.</p>
                        <p>- 10 g salé<br>
                        - 10 g non salé<br>
                        - 250 g salé<br>
                        - 250 g non salé<br>
                    <? } ?>
					</p>
                </div><!-- text-column -->
            </div><!-- wrap -->
        </section><!-- textblock -->
        
        <section class="twitter">
            <div class="wrap">
                <!--<img src="/img/site/universal.png" height="142" alt="Logotipo Universal">-->
                <img src="/img/icons/logo-queijo.png" height="146" alt="Logotipo Queijo Universal">
            </div><!-- wrap -->
        </section><!-- twitter -->
        <section class="newsletter">
            <div class="wrap">
                <div class="newsletter-title"><? if($lang=='pt'){ echo "Contactos"; }if($lang=='en'){ echo "Contacts"; }if($lang=='es'){ echo "Contactos"; }if($lang=='fr'){ echo "Contacts"; } ?></div>
                <div class="newsletter-wrapper">
                    <div class="newsletter-form clearfix">
                        <form name="form1" method="post" action="la_form.php" class="form-inline" role="form">
							<input name="cf_name" type="text" class="txtbox" placeholder="<? if($lang=='pt'){ echo "nome"; }if($lang=='en'){ echo "name"; }if($lang=='es'){ echo "nombre"; }if($lang=='fr'){ echo "nom"; } ?>" required><br>
                            <input name="cf_email" type="email" class="txtbox" placeholder="<? if($lang=='pt'){ echo "email"; }if($lang=='en'){ echo "email"; }if($lang=='es'){ echo "correo electrónico"; }if($lang=='fr'){ echo "email"; } ?>" required><br>
                            <textarea name="cf_message" class="txtbox" placeholder="<? if($lang=='pt'){ echo "mensagem"; }if($lang=='en'){ echo "message"; }if($lang=='es'){ echo "mensaje"; }if($lang=='fr'){ echo "message"; } ?>" required></textarea><br>
							<!--<input name="cf_message" type="largetext" class="txtbox" placeholder="mensagem"><br>-->
                            <br><input type="submit" name="Submit" value="<? if($lang=='pt'){ echo "enviar"; }if($lang=='en'){ echo "send"; }if($lang=='es'){ echo "enviar"; }if($lang=='fr'){ echo "envoyer"; } ?>">
                        </form>
                    </div><!-- newsletter-form -->
                </div><!-- newsletter-wrapper -->
                <? if($lang=='pt'){ ?>
                    <p class="newsletter-note">Poderá contactar-nos para o 256 666 300,<br>ou se preferir insira o seu email no campo de texto, que nós entraremos em contacto consigo.</p><br>
                    <p>Morada:<br>Apartado 5, 3721-902 Oliveira de Azeméis, Portugal</p>
                <? }if($lang=='en'){ ?>
                    <p class="newsletter-note">You can contact us on +351 256 666 300,<br>or if you prefer enter your email in the text field, we will contact you.</p><br>
                    <p>Address:<br>Apartado 5, 3721-902 Oliveira de Azeméis, Portugal</p>
                <? }if($lang=='es'){ ?>
                    <p class="newsletter-note">Puede ponerse en contacto con nosotros al +351 256 666 300,<br>o si prefiere insertar su correo electrónico en el campo de texto, que entraremos en contacto con usted.</p><br>
                    <p>Dirección:<br>Apartado 5, 3721-902 Oliveira de Azeméis, Portugal</p>
                <? }if($lang=='fr'){ ?>
                    <p class="newsletter-note">Vous pouvez nous contacter au 256 666 300,<br>ou si vous préférez entrer votre email dans le champ de texte, nous vous contacterons.</p><br>
                    <p>Adresse:<br>Apartado 5, 3721-902 Oliveira de Azeméis, Portugal</p>
                <? } ?>
            </div><!-- wrap -->
        </section><!-- newsletter -->

        <br><br><br><br>
        <div style="width:100%;height:32px;background:url('/img/icons/norte.svg')no-repeat center center;background-size:contain;"></div>
    </section><!-- main -->
    <footer>
        <div class="wrap">
        </div><!-- wrap -->
        <div class="footer-image"></div>
    </footer>
<script src="/js/jquery.js"></script>
<script src="/js/library.js"></script>
<script src="/js/script.js"></script>
<script src="/js/retina.js"></script>
<script src="/js/javatm.js"></script>
<script type="text/javascript">
function mudarIdioma(lang)
{
    document.getElementById("bt_<? echo $lang; ?>").style.color = "#bbb";
    document.getElementById("bt_"+lang).style.color = "#666";
    createCookie('lingua',lang,720);
    location.reload();
}

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
}
document.onkeypress = stopRKey;
</script>
</body>
</html>