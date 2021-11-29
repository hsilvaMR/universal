<?php
include('_connect.php');
include('_funcoes.php');

/*
for($i=1; $i<=10000; $i++) {

    do{
	    $codigo = geraCodigo(6);
	}while( $conn->query("SELECT COUNT(*) FROM rotulos WHERE codigo LIKE BINARY '$codigo'")->fetchColumn() );

	$statement = $conn->prepare("INSERT INTO rotulos(codigo,serie,data) VALUES (:codigo,'A',:data)");
	$statement->execute([ ':codigo'=>$codigo, ':data'=>strtotime(date('Y-m-d H:i:s')) ]);	
}

echo "Inseridos com sucesso!";
*/


/*
$statement = $conn->query("SELECT * FROM rotulos WHERE id_inv='' ORDER BY id ASC LIMIT 0, 10000");
while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $id = $row["id"];
    $codigo = $row["codigo"];
    $num = $id;
    switch (strlen($id)) {
    	case '1': $num = '00000000000'.$id; break;//11
    	case '2': $num = '0000000000'.$id; break;//10
    	case '3': $num = '000000000'.$id; break;//9
    	case '4': $num = '00000000'.$id; break;//8
    	case '5': $num = '0000000'.$id; break;//7
    	case '6': $num = '000000'.$id; break;//6
    	case '7': $num = '00000'.$id; break;//5
    	case '8': $num = '0000'.$id; break;//4
    	case '9': $num = '000'.$id; break;//3
    	case '10': $num = '00'.$id; break;//2
    	case '11': $num = '0'.$id; break;//1    	
    	default: $num = $id; break;
    }
    $id_inv = strrev($num);
    $codigo_inv = strrev($codigo);
    //echo $num.' - '.$id_inv.' * '.$codigo.' - '.$codigo_inv.'<br>';

    $statementU = $conn->prepare("UPDATE rotulos SET id_inv=:id_inv, codigo_inv=:codigo_inv WHERE id=:id");
	$statementU->execute([ ':id'=>$id, ':id_inv'=>$id_inv, ':codigo_inv'=>$codigo_inv ]);

}
echo 'SUCESSO';
*/
?>