<?php

const DATABASE = __DIR__ . '/database.sqlite';

/**
 * Chave mestra para derivar segredos de criptografia (nunca versione valor real no Git).
 * Preferência: variável de ambiente SAVELOCKER_APP_KEY (string longa e aleatória).
 */
if (! defined('APP_KEY')) {
    $envKey = getenv('SAVELOCKER_APP_KEY');
    if (is_string($envKey) && $envKey !== '') {
        define('APP_KEY', $envKey);
    } else {
        // Apenas desenvolvimento: gere uma chave forte em produção e use SAVELOCKER_APP_KEY.
        define('APP_KEY', 'dev-only-change-me-use-env-SAVELOCKER_APP_KEY-in-production-32chars-min');
    }
}
