<?php

namespace Core;

/**
 * Criptografia de conteúdo em repouso com libsodium (XChaCha20-Poly1305).
 * O texto cifrado é armazenado em base64 com nonce de 24 bytes prefixado.
 */
class Crypto
{
    private const NONCE_BYTES = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

    private static function chaveDerivada(int $usuarioId): string
    {
        $base = defined('APP_KEY') ? (string) APP_KEY : '';
        if ($base === '') {
            throw new \RuntimeException('APP_KEY não definida. Defina SAVELOCKER_APP_KEY no ambiente ou em config.php.');
        }

        return sodium_crypto_generichash(
            'nota:v1:' . $usuarioId . ':' . $base,
            '',
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES
        );
    }

    /**
     * Cifra texto UTF-8 para armazenamento (base64).
     */
    public static function encryptNota(string $plain, int $usuarioId): string
    {
        if ($plain === '') {
            return '';
        }

        $chave = self::chaveDerivada($usuarioId);
        $nonce = random_bytes(self::NONCE_BYTES);
        $cipher = sodium_crypto_secretbox($plain, $nonce, $chave);

        return base64_encode($nonce . $cipher);
    }

    /**
     * Decifra texto vindo do banco. Se for legado em texto plano, devolve como está.
     */
    public static function decryptNota(string $stored, int $usuarioId): string
    {
        if ($stored === '') {
            return '';
        }

        $bin = base64_decode($stored, true);
        if ($bin === false || strlen($bin) < self::NONCE_BYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            return $stored;
        }

        $nonce = substr($bin, 0, self::NONCE_BYTES);
        $cipher = substr($bin, self::NONCE_BYTES);

        try {
            $chave = self::chaveDerivada($usuarioId);
        } catch (\RuntimeException) {
            return $stored;
        }

        $plain = sodium_crypto_secretbox_open($cipher, $nonce, $chave);
        if ($plain === false) {
            return $stored;
        }

        return $plain;
    }
}
