<?php
$field_name = $_POST['cf_name'];
$field_email = $_POST['cf_email'];
$field_message = $_POST['cf_message'];

#
# Exemplo de envio de e-mail SMTP PHPMailer
#
# Inclui os ficheiros class.phpmailer.php localizado na pasta phpmailer
require_once("phpmailer/class.phpmailer.php");
require_once("phpmailer/class.smtp.php");

# Inicia a classe PHPMailer
$mail = new PHPMailer();

# Define os dados do servidor e tipo de conexão
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = 'localhost'; # Endereço do servidor SMTP, na WebHS basta usar localhost caso a conta de email esteja na mesma máquina de onde esta a correr este código, caso contrário altere para o seu desejado ex: mail.nomedoseudominio.com
$mail->Port = 587; // Porta TCP para a conexão
$mail->SMTPAutoTLS = false; // Utiliza TLS Automaticamente se disponível
$mail->SMTPAuth = true; # Usar autenticação SMTP - Sim
$mail->Username = 'admin@lacticiniosazemeis.pt'; # Login de e-mail
$mail->Password = 'mkv7p5n9ks'; // # Password do e-mail
# Define o remetente (você)
$mail->From = $field_email; # Seu e-mail
$mail->FromName = $field_name; // Seu nome
#$mail->AddReplyTo('seu@e-mail.com.br', 'Nome');
# Define os destinatário(s)
$mail->AddAddress('info@lacticiniosazemeis.pt', 'Info'); # Os campos podem ser substituidos por variáveis
#$mail->AddAddress('webmaster@nomedoseudominio.com'); # Caso queira receber uma copia
//if(strlen($Cc) > 0){ $mail->AddCC($Cc); } #$mail->AddCC('pessoa2@dominio.com', 'Pessoa Nome 2'); # Copia
//if(strlen($CcOculto) > 0){ $mail->AddBCC($CcOculto); } #$mail->AddBCC('pessoa3@dominio.com', 'Pessoa Nome 3'); # Cópia Oculta
# Define os dados técnicos da Mensagem
$mail->IsHTML(true); # Define que o e-mail será enviado como HTML
$mail->CharSet = 'UTF-8'; #$mail->CharSet = 'iso-8859-1'; # Charset da mensagem (opcional)
# Define a mensagem (Texto e Assunto)
$mail->Subject = 'Contacto site'; # Assunto da mensagem
$mail->Body = $field_message;
$mail->AltBody = $field_message;

# Define os anexos (opcional)
#$mail->AddAttachment("c:/temp/documento.pdf", "documento.pdf"); # Insere um anexo
# Envia o e-mail
$enviado = $mail->Send();

# Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();

# Lingua
$lang = isset($_COOKIE['lingua']) ? $_COOKIE['lingua'] : 'pt';
if($lang=='pt'){
	$sucesso="Obrigado pela sua mensagem, seremos breves na resposta.";
	$insucesso="O envio falhou! Por favor, envie um email para geral@lacticiniosazemeis.pt";
}
if($lang=='en'){
	$sucesso="Thanks for your message, we will be brief in reply.";
	$insucesso="Upload failed! Please send an email to geral@lacticiniosazemeis.pt";
}
if($lang=='es'){
	$sucesso="Gracias por su mensaje, seremos breves en la respuesta.";
	$insucesso="¡El envío falló! Por favor, envíe un correo electrónico a geral@lacticiniosazemeis.pt";
}
if($lang=='fr'){
	$sucesso="Merci pour votre message, nous serons bref en réponse.";
	$insucesso="Le téléchargement a échoué! S'il vous plaît envoyez un courriel à geral@lacticiniosazemeis.pt";
}

# Exibe uma mensagem de resultado (opcional)
if($enviado) { ?>
	<script language="javascript" type="text/javascript">
		alert("<? echo $sucesso; ?>");
		window.location = '/';
	</script>
<?php
}else{ ?>
	<script language="javascript" type="text/javascript">
		alert("<? echo $insucesso; ?>");
		window.location = '/';
	</script>
<?php
}
?>