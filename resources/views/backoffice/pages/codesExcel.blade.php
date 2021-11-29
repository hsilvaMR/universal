<?php
	require_once(base_path('public_html/vendor/phpexcel/Classes/PHPExcel.php'));
	require_once(base_path('public_html/vendor/phpexcel/Classes/PHPExcel/IOFactory.php'));


	$output = '
		<table>
			<tr>
				<th>id</th>
				<th>codigo</th>
				<th>id_inv</th>
				<th>codigo_inv</th>
				<th>serie</th>
				<th>estado</th>
				<th>data</th>
    		</tr>';
    	
    foreach($novo_array as $val){		
	    $output .='
    		<tr>
    			<td>'.$val['id'].'</td>
    			<td>'.$val['codigo'].'</td>
    			<td>'.$val['id_inv'].'</td>
    			<td>'.$val['codigo_inv'].'</td>
    			<td>'.$val['serie'].'</td>
    			<td>'.$val['estado'].'</td>
    			<td>'.$val['data'].'</td>
    		</tr>';
	}

	$output .='</table>';

	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=Códigos.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	

	//save file in server 

	//$path = base_path('public_html/encomenda/doc_fatura/');

	//$destinationPath = base_path('public_html/encomenda/doc_fatura/Codigos.xls');
    //$extension = strtolower($output->getClientOriginalExtension());
    //$getName = $output->getPathName();
    //$novo_fatura = 'Codigos.xls';

    //move_uploaded_file($novo_fatura, $path);

    //$output->save($path.'stats.xlsx');

	echo $output;

?>