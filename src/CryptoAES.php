<?php

namespace Arispati\CryptoAES;

/**
 * Crypto AES
 *
 * AES crypto library which can be used in php and javascript
 */
class CryptoAES
{

    /**
     * The secret key
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Class constructor
     *
     * @param string $secretKey
     */
    public function __construct($secretKey = 's3cr3tK3y')
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Encrypt any value
     *
     * @param mixed $value Any value
     * @return string
     */
    public function encrypt($value)
    {
        $passphrase = $this->secretKey;
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = ["ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt)];
        
        return json_encode($data);
    }

    /**
     * Decrypt a previously encrypted value
     *
     * @param string $jsonStr Json stringified value
     * @return mixed
     */
    public function decrypt(string $jsonStr)
    {
        $passphrase = $this->secretKey;
        $json = json_decode($jsonStr, true);
        $salt = hex2bin($json["s"]);
        $iv = hex2bin($json["iv"]);
        $ct = base64_decode($json["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = [];
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        
        return json_decode($data, true);
    }
}
