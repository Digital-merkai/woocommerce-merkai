<?php
if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа
}

class Woocommerce_Merkai_Encryption {
    private $encryption_key;

    public function __construct() {
        $this->encryption_key = $this->get_encryption_key();
    }

    // Получение или создание ключа шифрования
    private function get_encryption_key() {
        $key = get_option('merkai_encryption_key');
        if (!$key) {
            $key = bin2hex(random_bytes(32)); // Генерация 256-битного ключа
            update_option('merkai_encryption_key', $key);
        }
        return $key;
    }

    // Метод шифрования
    public function encrypt($data) {
        if (empty($data)) {
            return $data;
        }
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            hex2bin($this->encryption_key),
            0,
            $iv
        );
        return base64_encode($iv . $encrypted);
    }

    // Метод дешифрования
    public function decrypt($data) {
        if (empty($data)) {
            return $data;
        }
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            hex2bin($this->encryption_key),
            0,
            $iv
        );
    }
}