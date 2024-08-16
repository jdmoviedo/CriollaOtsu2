<?php
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ModelCorreo
{
	public static function Enviar($arrayRemitentes, $arrayDestinatarios, $asunto, $mensaje, $arrayAdjuntos = array(), $arrayNombreAdjuntos = array(), $arrayDestinatariosOcultos = array())
	{
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Exception.php');
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'PHPMailer.php');
		require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'SMTP.php');

		require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'email_smtp.php');


		$mail = new PHPMailer(); // Passing `true` enables exceptions
		$envioExitoso = false;
		$dominiosDesconocidos = array();
		$remitentesExitosos = "";
		$remitentesFallidos = "";
		$remitentesFallidosInfo = "";

		for ($i = 0; $i < count($arrayRemitentes); $i++) {
			$dominio = explode('@', $arrayRemitentes[$i]['correo'])[1];
			$proveedor = EmailSMTP::Proveedor($dominio);

			//si el proveedor no esta configurado
			if ($proveedor == false) {
				$dominiosDesconocidos[] = $dominio;
			} else {
				$smtp = EmailSMTP::smtp($proveedor);

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

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $asunto;
				$mail->Body    = $mensaje;
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				if ($mail->send()) {
					$remitentesExitosos .= $arrayRemitentes[$i]['correo'] . ",";
					$envioExitoso = true;
					break;
				} else {
					//$remitentesFallidosInfo[$arrayRemitentes[$i]['correo']] = $debug;
					$remitentesFallidosInfo .= $arrayRemitentes[$i]['correo'] . ": \n" . $debug . "\n";
				}
			}
		}

		if ($envioExitoso  == true) {
			$remitentesExitosos = trim(",", $remitentesExitosos);
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
				'Error' => $mail->ErrorInfo,
				'dominiosFaltantes' => $dominiosDesconocidos
			);
		}

		return $arrayRespuesta;
	}
}
