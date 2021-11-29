<?php
date_default_timezone_set('Europe/Lisbon');
/***** LIGAR BASE DE DADOS *****/
$lnk = mysqli_connect("localhost", "lacticiniosazeme_user", "txBKlVvZ5Ph(", "lacticiniosazeme_base")or die("Erro BD" . mysqli_error($lnk));
mysqli_set_charset($lnk, "utf8");
/***** LIGAR BASE DE DADOS PDO *****/
$conn = new PDO('mysql:host=localhost;dbname=lacticiniosazeme_base;charset=utf8', 'lacticiniosazeme_user', 'txBKlVvZ5Ph(');
?>