<?php
final class Encryption
{
    private $key;
    private $iv;

    public function __construct(\Registry $registry)
    {
        $this->key = hash('sha256', $registry->get('config')->get('config_encryption'), true);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    }

    public function encrypt($value)
    {
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
        $encoded = base64_encode($this->iv . $encrypted);
        return strtr($encoded, '+/=', '-_,');
    }

    public function decrypt($value)
    {
        $decoded = base64_decode(strtr($value, '-_,', '+/='));
        $iv = substr($decoded, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($decoded, openssl_cipher_iv_length('aes-256-cbc'));
        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}