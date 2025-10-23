<?php
namespace Core\Authentication;

use DateTime;

class TokenAuth
{
    protected string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(array $payload, int $validity = 86400): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        if ($validity > 0) {
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $this->secret, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    public function check(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        [$base64Header, $base64Payload, $base64Signature] = $parts;

        // recalcul de la signature
        $expectedSignature = hash_hmac(
            'sha256',
            $base64Header . '.' . $base64Payload,
            $this->secret,
            true
        );
        $expectedBase64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        // comparaison sécurisée
        return hash_equals($expectedBase64, $base64Signature);
    }


    public function getHeader(string $token): array
    {
        $array = explode('.', $token);
        return json_decode(base64_decode($array[0]), true);
    }

    public function getPayload(string $token): array
    {
        $array = explode('.', $token);
        return json_decode(base64_decode($array[1]), true);
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTime();
        return $payload['exp'] < $now->getTimestamp();
    }

    public function isValid(string $token): bool
    {
        return preg_match('/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token) === 1;
    }

    public function verify(string $token): bool
    {
        return $this->isValid($token) && $this->check($token) && !$this->isExpired($token);
    }
}
