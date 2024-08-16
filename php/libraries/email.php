<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class email
{
	public static function enviar($arrayRemitentes, $arrayDestinatarios, $asunto, $mensaje, $arrayAdjuntos, $arrayDestinatariosOcultos = array(), $arrayNombreAdjuntos = array())
	{
		//require_once(dirname(__DIR__).'/libraries/rutas.php');
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Exception.php');
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'PHPMailer.php');
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'SMTP.php');

		require_once('email_smtp.php');
		$mail = new PHPMailer();                              // Passing `true` enables exceptions
		$envioExitoso = false;
		$dominiosDesconocidos = array();
		$remitentesExitosos = "";
		$remitentesFallidos = "";
		//$remitentesFallidosInfo = array();
		$remitentesFallidosInfo = "";
		$remitentesFallidosDebug = "";

		//añado remitente de backup para que sea el ultimo en usarse cuando falle el que esta configurado en el plan
		$arrayRemitentes[] = array(
			'correo' => 'rvoips.backup@gmail.com',
			'contrasenia' => 'Asdf1234*'
		);
		// print_r($arrayRemitentes);exit;
		for ($i = 0; $i < count($arrayRemitentes); $i++) {
			$dominio = explode('@', $arrayRemitentes[$i]['correo'])[1];
			$proveedor = email_smtp::proveedor($dominio);

			//si el proveedor no esta configurado
			if ($proveedor == false) {
				$dominiosDesconocidos[] = $dominio;
			} else {
				$smtp = email_smtp::smtp($proveedor);

				//Server settings
				$mail->CharSet = "UTF-8";
				$mail->SMTPDebug = 2;							// Enable verbose debug output
				$debug = '';
				$mail->Debugoutput = function ($str, $level)  use (&$debug) {
					$debug .= "$level: $str\n";
				};
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = $smtp['host'];  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = $arrayRemitentes[$i]['correo'];                 // SMTP username
				$mail->Password = $arrayRemitentes[$i]['contrasenia'];                           // SMTP password
				$mail->SMTPSecure = $smtp['secure'];                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = $smtp['port'];

				$mail->setFrom($arrayRemitentes[$i]['correo']);
				/*echo $arrayDestinatarios[$i]['correo'];
			    print_r($arrayRemitentes);
			    print_r($arrayDestinatarios);
			    print_r($arrayAdjuntos);exit();*/

				//Recipients
				foreach ($arrayDestinatarios as $destinatario) {
					$mail->addAddress($destinatario);
				}

				//recipients hidden
				if (count($arrayDestinatariosOcultos) > 0) {
					foreach ($arrayDestinatariosOcultos as $destinatarioOculto) {
						$mail->addBCC($destinatarioOculto);
					}
				}

				//Attachments
				for ($j = 0; $j < count($arrayAdjuntos); $j++) {
					if (count($arrayNombreAdjuntos) > 0) {
						$mail->addAttachment($arrayAdjuntos[$j], $arrayNombreAdjuntos[$j]);         // Add attachments
					} else {
						$mail->addAttachment($arrayAdjuntos[$j]);         // Add attachments
					}
				}
				// foreach ($arrayAdjuntos as $adjunto) {
				// 	$mail->addAttachment($adjunto);         // Add attachments
				// }

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $asunto;
				//incrustar imagen para cuerpo de mensaje(no confundir con Adjuntar)
				//$mail->AddEmbeddedImage('../../img/email/semana_santa_rvo.jpeg', 'imagen');
				$mail->Body    = $mensaje;
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				if ($mail->send()) {
					$remitentesExitosos .= $arrayRemitentes[$i]['correo'] . ",";
					$envioExitoso = true;
					//si el remitente es un correo del nuevo proveedor rvo.com.co
					if ($proveedor == 'rvo.com.co') {
						$correo = $arrayRemitentes[$i]['correo'];
						$contrasenia = $arrayRemitentes[$i]['contrasenia'];

						self::save_mail_imap($smtp['path_imap'], $mail);
					}
					break;
				} else {
					//$remitentesFallidosInfo[$arrayRemitentes[$i]['correo']] = $debug;
					$remitentesFallidos .= $arrayRemitentes[$i]['correo'] . ",";
					$remitentesFallidosInfo .= $arrayRemitentes[$i]['correo'] . ": \n" . $debug . "\n";
					$remitentesFallidosDebug .= $debug;
				}
			}
		}

		if ($envioExitoso  == true) {
			$remitentesExitosos = trim($remitentesExitosos, ",");
			$arrayRespuesta = array(
				'status' => '1',
				'remitentesExitosos' => $remitentesExitosos,
				'remitentesFallidos' => $remitentesFallidos,
				'dominiosFaltantes' => $dominiosDesconocidos
			);
		} else {
			$arrayRespuesta = array(
				'status' => '0',
				'remitentesFallidos' => $remitentesFallidosInfo,
				'dominiosFaltantes' => $dominiosDesconocidos,
				'debug' => $remitentesFallidosDebug
			);
		}

		//si hay algun remitente fallido
		if($remitentesFallidos!="" && $remitentesFallidos != ","){
			$remitentesFallidos = trim($remitentesFallidos, ",");
			//envio alerta de remitente fallido si no existe el log
			if (!file_exists('email_log.txt')) {
				require_once('sms.php');
				$mensaje = "El remitente:" . $remitentesFallidos . " presenta fallas para enviar correos, revisa email_log.txt";
				$telefono = "3176159751";
				sms::enviar($mensaje, $telefono);
			}
			//si hay log para escribir
			$fp = fopen('email_log.txt', 'a');
			if ($fp) {
				fwrite($fp,$remitentesFallidosInfo);
				fclose($fp);
			}

		}

		return $arrayRespuesta;
	}

	//Section 2: IMAP
	//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
	//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
	//You can use imap_getmailboxes($imapStream, '/imap/ssl', '*' ) to get a list of available folders or labels, this can
	//be useful if you are trying to get this working on a non-Gmail IMAP server.
	//https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
	public static function save_mail_imap($path, $mail)
	{
		//You can change 'Sent Mail' to any other folder or tag
		//mail.rvo.com.co:993/imap/ssl

		//Tell your server to open an IMAP connection using the same username and password as you used for SMTP
		$imapStream = imap_open($path, $mail->Username, $mail->Password);

		$result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		imap_close($imapStream);

		return $result;
	}
}
