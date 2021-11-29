<?php


// Include the main TCPDF library (search for installation path).
require_once(base_path('public_html/vendor/tcpdf/tcpdf.php'));


class MYPDF extends TCPDF {
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		//$this->SetY(-15);
		//$this->SetX(15);

		// Set font
		//$this->SetFont('helvetica', 'I', 8);

		// Page number
		$this->Cell(0, 0,$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, true, 'R', 0, '', 0, true, 'T', 'M');

	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


//$pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<h3>test</h3>', $tc=array(0,0,0), $lc=array(0,0,0));
$pdf->SetFooterData(array(0,45,115),'');


//$pdf->writeHTML($hs, true, false, false, false, '');

$pdf->setPrintHeader(false);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', '9'));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));



$pdf->SetFont('freesans', '', 10, '', 'false');

$tbl = '';

$txt = '



<table cellpadding="6" cellspacing="0">
 <tr>
  <td width="120"><img src="site_v2/img/site/universal-logo.png" width="120" height="30"></td>
  <td width="230" style="text-align:right; line-height:15px;">
  	<b>
  		<span style="font-size:11px;color:#002d73;">LA | PRODUTOS LÁCTEOS, LDA.</span><br>
  	</b>
  	<span style="font-size:9px;color:#1974D8;"> OLIVEIRA DE AZEMÉIS, PORTUGAL</span>
  </td>
  <td width="120" style="line-height:20px; margin-left:50px;"><span style="font-size:10px;color:#1974D8;">Rua do Pego 25,<br>3721-902 Travanca <br>Oliveira de Azeméis</span></td>
  <td width="100" style="line-height:20px;"><span style="font-size:10px;color:#1974D8;">Tel: 256 666 300 <br>Fax: 256 682 183</span></td>
  <td width="100" style="line-height:20px;"><b style="font-size:10px;color:#002d73;">universal</b><span style="font-size:10px;color:#1974D8;">.com.pt</span></td>
 </tr>
</table>';



$html_morada = '';
$valor_total_t = 0;
$qtd_final = 0;

$valor_total_p = 0;
$qtd_total_line = 0;
$html_tr = '';
$html_value = '';
$color = '';
$count_prod = 0;
$cache = str_random(3);


foreach($encomenda_linha as $enc){
		

	$count_prod = $count_prod + count($enc->nome_produto);

	if($count_prod % 2 == 0){
	    $color = 'background-color:#fff;';
	} else {
	    $color = 'background-color:#fbfbfb;';
	}
  	$valor_caixa = $enc->qtd_caixa* $enc->preco_produto;
    $valor_line = $enc->quantidade * ($enc->qtd_caixa*$enc->preco_produto);
    $valor_dec = number_format((float)round( $valor_line ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
    $valor_total_t = number_format((float)round( $valor_total_t + $valor_dec ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
    $valor_total_p = number_format((float)round( $valor_total_p + $valor_dec ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
    $qtd_total_line = $qtd_total_line + $enc->quantidade;
    $qtd_final = $qtd_final + $enc->quantidade;

    $valor_iva = round($valor_total_t * 0.06, 2); 
    $valor_com_iva = round($valor_total_t + $valor_iva, 2);
  	
  	
  	$html_tr.= '

	  	<tr style="'.$color.'height:50px;">
	  		<td style="font-size:11px;height:50px;">'.$enc->nome_produto.'</td>
            <td style="text-align:right;font-size:11px;height:50px;">
            	1 '.trans('seller.Box').' <br> 
            	<span style="font-size:10px;">'.$enc->qtd_caixa.' '.trans('seller.articles').'</span>
            </td>
            <td style="font-size:11px;text-align:right;height:50px;">
            	'.$valor_caixa.' € <br> 
            	<span style="font-size:10px;">'.$enc->preco_produto.' €/'.trans('seller.article').'</span>
            </td>
            <td style="font-size:11px;text-align:right;height:50px;">'.$enc->quantidade.'</td>
            <td style="font-size:11px;text-align:right;height:50px;">'.$valor_dec.' €</td>
        </tr>
        ';

	

	$html_value= '
		<tr style="line-height:20px;">
            <td style="border-top:1px solid #eee;font-size:11px;">'.trans('seller.Total_partial').'</td>
            <td style="border-top:1px solid #eee;font-size:11px;"></td>
            <td style="border-top:1px solid #eee;font-size:11px;"></td>
            <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$qtd_total_line.'</b></td>
            <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$valor_total_p.' €</b></td>
        </tr>
	';

	/*Colocar abaixo do Total Parcial
	<tr style="line-height:20px;">
	    <td style="border-top:1px solid #eee;font-size:11px;">'.trans('seller.Carrying').'</td>
	    <td style="border-top:1px solid #eee;font-size:11px;"></td>
	    <td style="border-top:1px solid #eee;font-size:11px;"></td>
	    <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$qtd_final.'</b></td>
	    <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$valor_total_t.' €</b></td>
	</tr>*/

	$html_morada= '

		<table cellpadding="6" cellspacing="0">
		<tr style="border-top:1px solid #eee;line-height:20px;">
		  	<th style="border-top:1px solid #eee;border-bottom:1px solid #eee;font-size:11px;">
		  		<b>'.$enc->nome_personalizado.' </b> '.$enc->morada.', '.$enc->codigo_postal.' '.$enc->cidade.', '.$enc->pais.'
		  	</th>
		</tr>
		</table>
		<table cellpadding="6" cellspacing="0">
			
			<tr style="line-height:20px;">
			  <th style="border-top:1px solid #eee;border-bottom:1px solid #eee;font-size:11px;">'.trans('seller.Article').'</th>
			  <th style="border-top:1px solid #eee;border-bottom:1px solid #eee;text-align:right;font-size:11px;">'.trans('seller.Unity').'</th>
			  <th style="border-top:1px solid #eee;border-bottom:1px solid #eee;text-align:right;font-size:11px;">'.trans('seller.Price').'</th>
			  <th style="border-top:1px solid #eee;border-bottom:1px solid #eee;text-align:right;font-size:11px;">'.trans('seller.Amount').'</th>
			  <th style="border-top:1px solid #eee;border-bottom:1px solid #eee;text-align:right;font-size:11px;">'.trans('seller.Value').'</th>
			</tr>

			'.$html_tr.'
			
		</table>
		<table cellpadding="6" cellspacing="0">
			'.$html_value.'
		</table>
	';
}

if ($encomenda->estado_armazem == 'em_processamento') {
	$estado = '<span style="background-color:#ffcd40;">&emsp;'.trans('seller.processing').'&emsp;</span>';
}
elseif($encomenda->estado_armazem == 'concluida'){
	$estado = '<span style="background-color:#7AC34E;">&emsp;'.trans('seller.completed').'&emsp;</span>';
}
elseif($encomenda->estado_armazem == 'registada'){
	$estado = '<span style="background-color:#1974D8;">&emsp;'.trans('seller.resgisted').'&emsp;</span>';
}
else{
	$estado = '<span style="background-color:#1974D8;">&emsp;'.trans('seller.resgisted').'&emsp;</span>';
}

if ($encomenda->telefone == 0) { $telefone = ''; }
else{ $telefone = $encomenda->telefone; }


$html = '
	
	<table cellpadding="6" cellspacing="0">
	 	<tr>
	 		<td><span style="font-size:14px;color:#1974D8;width:100px;">'.trans('seller.Order').' '.$id.' </span></td>
	 		<td style="font-size:11px;float:right;text-align:right;">
	 			<span style="float:right;text-align:right;width:100px;">'.trans('seller.Status').'&nbsp;&nbsp;
	 				'.$estado.'
	 			</span>
	 		</td>
	 	</tr>
 	</table>

 	<br>
 	<br>

 	<table style="width:600px;">
		<tr style="float:left;width:180px;">
			<td style="float:left;width:180px;">
				<table style="float:left;width:180px;">
					<tr>
						<td width="40">'.trans('seller.Date').'</td>
						<td style="border-left:1px solid #000;">  '.date('Y-m-d',$encomenda->data).'</td>
					</tr>

				</table>
			</td>
			<td style="float:left;width:420px;">
				<table style="float:right;width:420px;">
					<tr style="float:left;width:420px;">
						<td width="65">'.trans('seller.User').' </td>
						<td style="border-left:1px solid #000;"> 
							<span>'.$encomenda->nome.' <br>  '.$telefone.' <br>  '.$encomenda->email.'</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

 	<br>
 	<br>
 	<br>
	<table cellpadding="6" cellspacing="0">
	 	<tr>
	 		<td><span style="font-size:14px;color:#1974D8;width:100px;">'.trans('seller.Details').'</span></td>
	 	</tr>
 	</table>
	<br>
	<br>
	'.$html_morada.'

	<table cellpadding="6" cellspacing="0">

        <tr style="line-height:20px;">
          <td style="border-top:1px solid #eee;font-size:11px;">'.trans('seller.Amount_Total').'</td>
          <td style="border-top:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$qtd_final.'</b></td>
          <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$valor_total_t.' €</b></td>
        </tr>

        <tr style="line-height:20px;">
          <td style="border-top:1px solid #eee;font-size:11px;">'.trans('seller.IVA').' (6%)</td>
          <td style="border-top:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;font-size:11px;text-align:right;"><b>'.$valor_iva.' €</b></td>
        </tr>

        <tr style="line-height:20px;">
          <td style="border-top:1px solid #eee;border-bottom:1px solid #eee;font-size:11px;">'.trans('seller.Total').'</td>
          <td style="border-top:1px solid #eee;border-bottom:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;border-bottom:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;border-bottom:1px solid #eee;"></td>
          <td style="border-top:1px solid #eee;border-bottom:1px solid #eee;font-size:11px;text-align:right;"><b>'.$valor_com_iva.' €</b></td>
        </tr>
      
    </table>';

// print a block of text using Write()
//$pdf->WriteHTML(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($txt, true, false, false, false, '');

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


//$pdf->writeHTML($html, true, false, false, false, '');
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$path = base_path('public_html/doc/orders');

// Supply a filename including the .pdf extension
//$filename = 'encomenda_universal'.$id.'.pdf';
$filename = $nome_pdf;


// Create the full path
$full_path = $path . '/' . $filename;

// Output PDF
$pdf->Output($full_path, 'F');

$pdf->Output($nome_pdf, 'D');

//============================================================+
// END OF FILE
//============================================================+
?>