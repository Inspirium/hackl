<?php

namespace App\Actions;

class SignImage {

    public static function sign($url) {
        if (!$url) {
            return '';
        }
        if (!strpos($url, 'tenis.plus')) {
            return $url;
        }
        if (strpos($url, '.gif') || strpos($url, '.svg')) {
            return $url;
        }
        $key = 'b5b54148b47074a162ff4bd3323353b1ad11401d84159bb8d2d4dc855a2145b3';
        $salt = '2eec61e5608da13beb3b8002deb178c0437be3b22b9ed38427cdeade6875d53e';

        $keyBin = pack("H*" , $key);
        if(empty($keyBin)) {
            die('Key expected to be hex-encoded string');
        }

        $saltBin = pack("H*" , $salt);
        if(empty($saltBin)) {
            die('Salt expected to be hex-encoded string');
        }

        $encodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');

        $path = "/preset:{$preset}/{$encodedUrl}.{$extension}";

        $signature = rtrim(strtr(base64_encode(hash_hmac('sha256', $saltBin.$path, $keyBin, true)), '+/', '-_'), '=');

        return sprintf("https://images.telegram.hr/%s%s", $signature, $path);
    }
}
