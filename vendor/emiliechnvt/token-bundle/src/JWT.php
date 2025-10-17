<?php

namespace EmilieChnvt\TokenBundle;
use DateTime;

class JWT
{
    public function generate(
        array  $header,
        array  $payload,
        string $secret,
        int    $validity = 86400
    ): string
    {

        if ($validity > 0) {
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp(); //issued at
            $payload['exp'] = $expiration; //expiration

        }


        $base64Header = base64_encode(json_encode($header)); //json_encode car de base on a un tableau et base64 veut une chaine de caractère
        $base64Payload = base64_encode(json_encode($payload));

//on retire + / =

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header); //remplace premier [] par 2ème [] dans base64header
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload); //remplace premier [] par 2ème [] dans base64header

//encode secret + génère signature
        $secret = base64_encode(SECRET);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);
//nettoie signature
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature); //remplace premier [] par 2ème [] dans base64header


//création token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $signature;

        return $jwt;
    }

    public function check(string $token, string $secret): bool
    {
        //récup header + payload
        $headers = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //génère token de verif
        $verifToken = $this->generate($headers, $payload, $secret, 0);

        return $token === $verifToken;
    }

    public function getHeader(string $token)
    {
        //démonter token
        $array = explode('.', $token); // a chaque point du token crée entrée dans tableau (header payload signature)

        //décode header
        $header = json_decode(base64_decode($array[0]), true);
        return $header;

    }

    public function getPayload(string $token)
    {
        $array = explode('.', $token);
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTime();
        return $payload['exp'] < $now->getTimestamp();

    }

    public function isValid(string $token): bool
    {
        return preg_match(
                '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
                $token
            ) === 1;
    }
}
