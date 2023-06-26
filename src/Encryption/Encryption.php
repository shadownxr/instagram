<?php

namespace ArkonInstagram;

class Encryption {
    const CIPHER = 'AES-256-CBC';
    const IV = 's*74f65A)9#fiS5w';
    const PASSPHRASE = 'l)%s"W887S{@DUHW';

    public static function encrypt(string $data){
        return openssl_encrypt($data, self::CIPHER, self::PASSPHRASE,0, self::IV);
    }

    public static function decrypt(string $data){
        return openssl_decrypt($data, self::CIPHER, self::PASSPHRASE,0, self::IV);
    }
}