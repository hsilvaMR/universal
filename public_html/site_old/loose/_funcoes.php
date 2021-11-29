<?php
function geraCodigo($tamanho)
{
	//$simbolos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvxz';
	$simbolos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$retorno = '';
	$caracteres = '';
	$caracteres .= $simbolos;
	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++)
	{
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand - 1];
	}
	return $retorno;
}
/*$senha = geraSenha2(10, true, true, true);
function geraSenha2($tamanho, $maiusculas, $numeros, $simbolos)
{
	 $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	 $num = '1234567890';
	 $simb = '!*-|\#$/()=?»«}][{§€£';
	 $retorno = '';
	 $caracteres = '';
	 $caracteres .= $lmin;
	 if ($maiusculas)
		$caracteres .= $lmai;
	 if ($numeros)
		$caracteres .= $num;
	 if ($simbolos)
		 $caracteres .= $simb;
	 $len = strlen($caracteres);
	 for ($n = 1; $n <= $tamanho; $n++)
	 {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand - 1];
	 }
	 return $retorno;
}*/

/*
$numero=mysqli_num_rows(mysqli_query($lnk,"SELECT * FROM rotulos WHERE codigo LIKE BINARY '$codigo'"));
echo "$numero ";

$id = '0';
$linha = mysqli_fetch_array(mysqli_query($lnk,"SELECT * FROM rotulos WHERE codigo LIKE BINARY '$codigo'"));
$id = $linha["id"];
echo " <b>$id</b>";



$num=$conn->query("SELECT COUNT(*) FROM rotulos WHERE codigo LIKE BINARY '$codigo'")->fetchColumn(); 
echo "$num ";

$id = '0';
$statement = $conn->query("SELECT * FROM rotulos WHERE codigo LIKE BINARY '$codigo'");
$line = $statement->fetch(PDO::FETCH_ASSOC);
$id = $line["id"];	
echo " <b>$id</b>";
*/
?>