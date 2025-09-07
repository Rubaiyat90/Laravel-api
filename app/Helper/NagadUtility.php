<?php

namespace App\Helper;

class NagadUtility {
    public static function generateRandomString(int $length = 40): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function encryptDataWithPublicKey(string $data, string $mode='production'): string
    {
        if ($mode === 'sandbox')
        {
            $pgPublicKey = env('SANDBOX_NAGAD_PG_PUBLIC_KEY');
        } else {
            $pgPublicKey = env('NAGAD_PG_PUBLIC_KEY');
        }

        $public_key = "-----BEGIN PUBLIC KEY-----\n" . $pgPublicKey . "\n-----END PUBLIC KEY-----";
        $key_resource = openssl_get_publickey($public_key);
        openssl_public_encrypt($data, $cryptText, $key_resource);

        return base64_encode($cryptText);
    }

    public static function generateSignature(string $data, string $mode='production'): string
    {
        if ($mode === 'sandbox')
        {
            $merchantPrivateKey = env('SANDBOX_NAGAD_MERCHANT_PRIVATE_KEY');
        } else {
            $merchantPrivateKey = env('NAGAD_MERCHANT_PRIVATE_KEY');
        }
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $merchantPrivateKey . "\n-----END RSA PRIVATE KEY-----";

        openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    public static function decryptDataWithPrivateKey(string $encryptedString, string $mode='production'): string
    {
        if ($mode === 'sandbox')
        {
            $merchantPrivateKey = env('SANDBOX_NAGAD_MERCHANT_PRIVATE_KEY');
        } else {
            $merchantPrivateKey = env('NAGAD_MERCHANT_PRIVATE_KEY');
        }

        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $merchantPrivateKey . "\n-----END RSA PRIVATE KEY-----";
        openssl_private_decrypt(base64_decode($encryptedString), $plain_text, $private_key);
        return $plain_text;
    }

    public static function post(string $url, array $data)
    {
        $url = curl_init($url);
        $data = json_encode($data);

        $header = array(
            'Content-Type:application/json',
            'X-KM-Api-Version:v-0.2.0',
            'X-KM-IP-V4:' . self::getClientIp(),
            'X-KM-Client-Type:PC_WEB'
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $data);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, false);

        $resultData = curl_exec($url);

        $result = json_decode($resultData, true);
        $header_size = curl_getinfo($url, CURLINFO_HEADER_SIZE);
        curl_close($url);
        $headers = substr($resultData, 0, $header_size);
        $body = substr($resultData, $header_size);

        return $result;
    }

    public static function get(string $url): bool|string
    {
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $file_contents = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);

        return $file_contents;
    }

    public static function getClientIp(): ?string
    {
        return request()->ip() ?? 'UNKNOWN';
    }
}
