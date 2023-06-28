<?php

namespace ArkonInstagram;

class Encryption
{
    const CIPHER = 'AES-256-CBC';
    const IV = 's*74f65A)9#fiS5w';
    const KEY_FILEPATH = _PS_MODULE_DIR_ . '/instagram/src/Encryption/key.txt';

    public static function generateKey(): bool
    {
        $passphrase = openssl_random_pseudo_bytes(32);
        $fp = fopen(_PS_MODULE_DIR_ . '/instagram/src/Encryption/key.txt', 'w');

        if (!$fp) {
            return false;
        }

        if (!fwrite($fp, $passphrase)) {
            return false;
        }

        if (!fclose($fp)) {
            return false;
        }

        return true;
    }

    public static function encrypt(string $data, bool $random_iv = false, string &$iv = '')
    {
        $passphrase = (new Encryption)->getPassphrase();
        if (empty($passphrase)) {
            return false;
        }

        if (!$random_iv) {
            return openssl_encrypt($data, self::CIPHER, $passphrase, 0, self::IV);
        }

        $ivlen = openssl_cipher_iv_length(self::CIPHER);
        $isCryptoStrong = false;
        $iv = openssl_random_pseudo_bytes($ivlen, $isCryptoStrong);

        if (!$isCryptoStrong) {
            return false;
        }

        return openssl_encrypt($data, self::CIPHER, $passphrase, 0, $iv);
    }

    public static function decrypt(string $data, string $iv = '')
    {
        $passphrase = (new Encryption)->getPassphrase();
        if (empty($passphrase)) {
            return false;
        }

        if (empty($iv)) {
            return openssl_decrypt($data, self::CIPHER, $passphrase, 0, self::IV);
        } else {
            return openssl_decrypt($data, self::CIPHER, $passphrase, 0, $iv);
        }
    }

    private function getPassphrase()
    {
        $passphrase = '';
        $fp = fopen(self::KEY_FILEPATH, 'r');
        if (!$fp) {
            return false;
        }

        while ($string = fgets($fp)) {
            $passphrase .= $string;
        }

        if (!fclose($fp)) {
            return false;
        }

        return $passphrase;
    }
}