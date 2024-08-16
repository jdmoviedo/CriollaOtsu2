<?php
	/**
	 *
	 */
	class email_smtp
	{
		public static function proveedor($dominio){
			$dominio = strtolower($dominio);
			$arrayDominio['gmail.com'] = 'gmail';
			$arrayDominio['hotmail.com'] = 'hotmail';
			$arrayDominio['hotmail.es'] = 'hotmail';
			$arrayDominio['outlook.com'] = 'hotmail';
			$arrayDominio['outlook.es'] = 'hotmail';
			$arrayDominio['yahoo.com'] = 'yahoo';
			$arrayDominio['laboratoriorvo.com'] = 'gmail';
			if( array_key_exists($dominio,$arrayDominio) ){
				return $arrayDominio[$dominio];
			}else{
				return false;
			}

		}

		public static function smtp( $proveedor ){
			$proveedor = strtolower($proveedor);
			$smtp['gmail']['host'] = 'smtp.gmail.com';
			$smtp['gmail']['port'] = '587';
			$smtp['gmail']['secure'] = 'tls';

			$smtp['hotmail']['host'] = 'smtp.live.com';
			$smtp['hotmail']['port'] = '587';
			$smtp['hotmail']['secure'] = 'tls';

			$smtp['yahoo']['host'] = 'smtp.mail.yahoo.com';
			$smtp['yahoo']['port'] = '587';
			$smtp['yahoo']['secure'] = 'tls';

			return $smtp[$proveedor];
		}

	}

?>