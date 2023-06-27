<?php

namespace ArkonInstagram;

class Encryption
{
    const CIPHER = 'AES-256-CBC';
    const IV = 's*74f65A)9#fiS5w';
    const PASSPHRASE = 'l)%s"W887S{@DUHW';

    public static function encrypt(string $data, bool $random_iv = false, string &$iv = '')
    {
        $ivlen = openssl_cipher_iv_length(self::CIPHER);
        $isCryptoStrong = false;
        $iv = openssl_random_pseudo_bytes($ivlen, $isCryptoStrong);

        if (!$isCryptoStrong) {
            return false;
        }

        if(!$random_iv) {
            return openssl_encrypt($data, self::CIPHER, self::PASSPHRASE, 0, self::IV);
        } else {
            return openssl_encrypt($data, self::CIPHER, self::PASSPHRASE, 0, $iv);
        }
    }

    public static function decrypt(string $data, string $iv = ''): string
    {
        if(empty($iv)) {
            return openssl_decrypt($data, self::CIPHER, self::PASSPHRASE, 0, self::IV);
        } else {
            return openssl_decrypt($data, self::CIPHER, self::PASSPHRASE, 0, $iv);
        }
    }
}