<?php
/**
*
*/
define('IV', '93031823');
define('KEY_192', "LM£¤¥¦§¨©ª«¬®");

class aleatorios
{
  public static function generar_clave($longitud){
    $cadena="[^A-Z0-9]";
    return substr(preg_replace($cadena, "", md5(rand())) .
      preg_replace($cadena, "", md5(rand())) .
      preg_replace($cadena, "", md5(rand())),
      0, $longitud);
  }

  public static function hextobin($hex)
  {
    $rv = '';
    foreach(str_split($hex, 2) as $b) {
      $rv .= chr(hexdec($b));
    }
    return $rv;
  }

  public static function decrypt($dataEncrypt)
  {
    $token=$this->hextobin($dataEncrypt);
    $key=KEY_192;$iv= IV; $algorithm='tripledes';$mode='ecb';
    $td = mcrypt_module_open($algorithm, '', $mode, '') ;
    $iv = substr($iv, 0, mcrypt_enc_get_iv_size($td));
    $expected_key_size = mcrypt_enc_get_key_size($td);
    $key = substr($key, 0, $expected_key_size);
    mcrypt_generic_init($td, $key, $iv);
    $response = rtrim(mdecrypt_generic($td, $token), '');
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    return $response;
  }

  public static function encrypt($cleartext = ''){
    if (!$cleartext) return NULL;
    $cipher = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
    if (mcrypt_generic_init($cipher, KEY_192, IV) != -1)
    {
      $cipherText = mcrypt_generic($cipher,$cleartext );
      mcrypt_generic_deinit($cipher);
      return bin2hex($cipherText);
    }
  }
}
?>